<template>
    <div class="flex gap-6 p-6">
        <!-- Left: Categories Table -->
        <div class="flex-1">
            <!-- Categories Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <DataTable 
                    :value="categories" 
                    :paginator="false"
                    :key="`table-${currentPage}-${tableKey}`"
                    tableStyle="min-width: 50rem"
                    class="p-datatable-sm"
                >
                    <template #empty>
                        <div class="text-center py-8">
                            <i class="pi pi-info-circle text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500">{{ loading ? 'Loading categories...' : 'No categories found' }}</p>
                            <p class="text-xs text-gray-400 mt-2">
                                {{ loading ? 'Please wait while we fetch the data.' : 'Add a new category using the form on the right.' }}
                            </p>
                        </div>
                    </template>
                    <Column field="name" header="Category Name" class="text-left">
                        <template #body="slotProps">
                            <span class="text-gray-900 text-sm font-medium">{{ slotProps.data.name }}</span>
                        </template>
                    </Column>
                    
                    <Column field="description" header="Description" class="text-left">
                        <template #body="slotProps">
                            <span class="text-gray-500 text-sm">{{ slotProps.data.description }}</span>
                        </template>
                    </Column>
                    
                    <Column field="products" header="Products" class="text-left">
                        <template #body="slotProps">
                            <span class="text-gray-900 text-sm">{{ slotProps.data.products }}</span>
                        </template>
                    </Column>
                    
                    <Column field="createdAt" header="Created at" class="text-left">
                        <template #body="slotProps">
                            <span class="text-gray-500 text-sm">{{ slotProps.data.createdAt }}</span>
                        </template>
                    </Column>
                    
                    <Column field="status" header="Status" class="text-left">
                        <template #body="slotProps">
                            <Tag 
                                :value="slotProps.data.status" 
                                :severity="slotProps.data.status.toLowerCase() === 'active' ? 'success' : 'secondary'"
                                class="text-xs font-semibold"
                            />
                        </template>
                    </Column>
                    
                    <Column header="Actions" class="text-left">
                        <template #body="slotProps">
                            <Button 
                                icon="pi pi-ellipsis-h" 
                                text 
                                severity="secondary" 
                                size="small"
                                class="text-gray-400 hover:text-gray-600"
                                @click="showActionMenu($event, slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
            </div>

            <!-- Action Menu -->
            <Menu 
                ref="menu" 
                :model="selectedCategory ? getMenuItems(selectedCategory) : []" 
                :popup="true" 
            />

            <!-- Pagination -->
            <div class="flex items-center justify-between mt-6">
                <div class="text-sm text-gray-700">
                    Showing {{ categories.length }} of {{ totalCategories }} items
                </div>
                <div class="flex items-center space-x-2">
                    <!-- Previous Button -->
                    <button 
                        @click="goToPreviousPage" 
                        :disabled="currentPage <= 1"
                        class="px-3 py-1 text-sm text-gray-500 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <i class="pi pi-chevron-left"></i>
                    </button>
                    
                    <!-- Dynamic Page Numbers -->
                    <template v-for="page in visiblePages" :key="page">
                        <button 
                            v-if="typeof page === 'number'"
                            @click="goToPage(page)"
                            :class="currentPage === page 
                                ? 'px-3 py-1 text-sm bg-orange-400 text-white rounded font-medium' 
                                : 'px-3 py-1 text-sm text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded'"
                        >
                            {{ page }}
                        </button>
                        <span v-else class="px-3 py-1 text-sm text-gray-400">...</span>
                    </template>
                    
                    <!-- Next Button -->
                    <button 
                        @click="goToNextPage" 
                        :disabled="currentPage >= totalPages"
                        class="px-3 py-1 text-sm text-gray-500 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <i class="pi pi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Right: New Category Form -->
        <div class="w-80">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">New Category</h3>
                
                <form @submit.prevent="handleSubmit" class="space-y-4">
                    <!-- Category Name -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Category Name</label>
                        <InputText 
                            v-model="newCategory.name" 
                            placeholder="Enter category name"
                            class="w-full"
                            :class="{ 'p-invalid': errors.name }"
                        />
                        <small v-if="errors.name" class="text-red-500">{{ errors.name }}</small>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Description</label>
                        <Textarea 
                            v-model="newCategory.description" 
                            placeholder="Enter category description"
                            rows="4"
                            class="w-full"
                        />
                    </div>

                    <!-- Status -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Status</label>
                        <Dropdown 
                            v-model="newCategory.status" 
                            :options="statusOptions"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Select Status"
                            class="w-full"
                        />
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-4">
                        <Button 
                            type="button"
                            label="RESET" 
                            severity="secondary"
                            outlined
                            @click="resetForm"
                            class="flex-1 reset-btn text-xs font-bold"
                        />
                        <Button 
                            type="submit"
                            label="ADD NEW" 
                            :loading="loading"
                            class="flex-1 add-btn text-xs font-bold"
                        />
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, nextTick, computed } from 'vue'
import { useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Tag from 'primevue/tag'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import Dropdown from 'primevue/dropdown'
import Menu from 'primevue/menu'
import { getAuthHeaders, handleAuthError, requireAuth } from '../../utils/auth'

// Define interface for category
interface Category {
    id?: number
    name: string
    description: string
    products: number
    createdAt: string
    status: string
}

const loading = ref(false)
const totalCategories = ref(0)  // Changed from 50 to 0 initially
const currentPage = ref(1)
const tableKey = ref(0)
const router = useRouter()
const menu = ref()
const selectedCategory = ref<Category | null>(null)

// Computed properties for pagination
const totalPages = computed(() => {
    return Math.ceil(totalCategories.value / 10) // 10 items per page
})

const visiblePages = computed(() => {
    const total = totalPages.value
    const current = currentPage.value
    const pages: (number | string)[] = []
    
    if (total <= 7) {
        // Show all pages if total is 7 or less
        for (let i = 1; i <= total; i++) {
            pages.push(i)
        }
    } else {
        // Complex pagination logic
        if (current <= 4) {
            // Show first 5 pages + ... + last page
            for (let i = 1; i <= 5; i++) {
                pages.push(i)
            }
            if (total > 6) {
                pages.push('...')
                pages.push(total)
            }
        } else if (current >= total - 3) {
            // Show first page + ... + last 5 pages
            pages.push(1)
            if (total > 6) {
                pages.push('...')
            }
            for (let i = total - 4; i <= total; i++) {
                pages.push(i)
            }
        } else {
            // Show first page + ... + current-1, current, current+1 + ... + last page
            pages.push(1)
            pages.push('...')
            for (let i = current - 1; i <= current + 1; i++) {
                pages.push(i)
            }
            pages.push('...')
            pages.push(total)
        }
    }
    
    return pages
})

// Helper function to redirect to login
const redirectToLogin = () => {
    router.push('/adminlogin')
}

const newCategory = reactive({
    name: '',
    description: '',
    status: 'active'
})

const errors = reactive({
    name: ''
})

const statusOptions = [
    { label: 'active', value: 'active' },
    { label: 'inactive', value: 'inactive' }
]

const categories = ref<Category[]>([
    // Initial empty array - will be populated from API
])

// Menu items for category actions
const getMenuItems = (category: Category) => [
    {
        label: `Mark as ${category.status === 'active' ? 'inactive' : 'active'}`,
        icon: category.status === 'active' ? 'pi pi-eye-slash' : 'pi pi-eye',
        command: () => toggleCategoryStatus(category)
    },
    {
        separator: true
    },
    {
        label: 'Edit Category',
        icon: 'pi pi-pencil',
        command: () => editCategory(category)
    },
    {
        label: 'Delete Category',
        icon: 'pi pi-trash',
        command: () => deleteCategory(category)
    }
]

// Pagination functions
const goToPage = async (page: number | string) => {
    if (typeof page === 'string') return // Skip ellipsis clicks
    
    if (page >= 1 && page <= totalPages.value && page !== currentPage.value) {
        await fetchCategories(page)
    }
}

const goToPreviousPage = async () => {
    if (currentPage.value > 1) {
        await goToPage(currentPage.value - 1)
    }
}

const goToNextPage = async () => {
    if (currentPage.value < totalPages.value) {
        await goToPage(currentPage.value + 1)
    }
}

// Fetch categories from API
const fetchCategories = async (page: number = 1) => {
    loading.value = true
    try {
        // Check authentication first
        if (!requireAuth(redirectToLogin)) {
            loading.value = false
            return
        }

        const apiUrl = `http://127.0.0.1:8000/api/product_category?page=${page}&per_page=10`

        const response = await fetch(apiUrl, {
            method: 'GET',
            headers: getAuthHeaders()
        })

        if (!response.ok) {
            const errorText = await response.text()
            
            // Handle authentication errors
            if (handleAuthError(response.status, redirectToLogin)) {
                return
            }
            
            throw new Error(`Failed to fetch categories: ${response.status} - ${errorText}`)
        }

        // Check if response is JSON before parsing
        const contentType = response.headers.get('content-type')
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Server returned non-JSON response. Check your Laravel API.')
        }

        const result = await response.json()
        
        // Handle different possible response structures
        let categoriesData = []
        
        if (result && result.data && Array.isArray(result.data)) {
            // Laravel pagination structure
            categoriesData = result.data
        } else if (Array.isArray(result)) {
            // Direct array structure
            categoriesData = result
        } else if (result && result.categories && Array.isArray(result.categories)) {
            // Custom categories structure
            categoriesData = result.categories
        }
        
        // Map API response to component format
        const apiCategories = categoriesData.map((category: any, index: number) => ({
            id: category.product_category_id || category.id || index + 1,
            name: category.name || 'Unnamed Category',
            description: category.description || 'No description provided',
            products: category.products_count || category.products || 0,
            createdAt: category.created_at ? new Date(category.created_at).toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            }) : new Date().toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            }),
            status: category.status || 'active'
        }))

        // Set categories data
        categories.value = apiCategories
        
        // Handle Laravel pagination metadata
        if (result && result.total !== undefined) {
            totalCategories.value = result.total
        } else if (result && result.count !== undefined) {
            totalCategories.value = result.count
        } else if (result && result.last_page !== undefined) {
            // Laravel pagination sometimes uses last_page and per_page
            const perPage = result.per_page || 10
            totalCategories.value = (result.last_page - 1) * perPage + apiCategories.length
        } else {
            // Fallback calculation
            if (apiCategories.length === 10) {
                totalCategories.value = Math.max(50, page * 10 + 10) // Estimate
            } else {
                totalCategories.value = (page - 1) * 10 + apiCategories.length
            }
        }
        
        currentPage.value = page
        
        // Force Vue to update the table
        tableKey.value++
        await nextTick()
        
    } catch (error) {
        // Set empty array if there's an error
        categories.value = []
        
        const errorMessage = error instanceof Error ? error.message : 'Failed to fetch categories'
        alert(`Error: ${errorMessage}`)
        
    } finally {
        loading.value = false
        console.log(`Loading set to false`)
    }
}

// Load categories when component mounts
onMounted(() => {
    fetchCategories(1)
})

const validateForm = () => {
    errors.name = ''
    
    if (!newCategory.name.trim()) {
        errors.name = 'Category name is required'
        return false
    }
    
    return true
}

const handleSubmit = async () => {
    if (!validateForm()) {
        return
    }

    loading.value = true

    try {
        // Check authentication first
        if (!requireAuth(redirectToLogin)) {
            loading.value = false
            return
        }

        // Prepare data for API
        const categoryData = {
            name: newCategory.name,
            description: newCategory.description || '',
            status: newCategory.status
        }

        // Make API call to Laravel backend
        const response = await fetch('http://127.0.0.1:8000/api/product_category/', {
            method: 'POST',
            headers: getAuthHeaders(),
            body: JSON.stringify(categoryData)
        })

        if (!response.ok) {
            const errorData = await response.json()
            
            // Handle authentication errors
            if (handleAuthError(response.status, redirectToLogin)) {
                return
            }
            
            throw new Error(errorData.message || 'Failed to create category')
        }

        // After adding, calculate the last page where the new category will be
        const newTotal = totalCategories.value + 1
        const perPage = 10 // As defined in the fetch function
        const lastPage = Math.ceil(newTotal / perPage)

        // Fetch the last page to show the new category
        await fetchCategories(lastPage)
        
        // Reset form
        resetForm()
        
        // Show success message
        alert('Category created successfully!')
        
    } catch (error) {
        console.error('Error adding category:', error)
        
        // Show error message
        const errorMessage = error instanceof Error ? error.message : 'Failed to create category'
        alert(`Error: ${errorMessage}`)
    } finally {
        loading.value = false
    }
}

const resetForm = () => {
    newCategory.name = ''
    newCategory.description = ''
    newCategory.status = 'active'
    errors.name = ''
}

// Show action menu for category
const showActionMenu = (event: Event, category: Category) => {
    selectedCategory.value = category
    menu.value.toggle(event)
}

// Toggle category status between Active/Inactive
const toggleCategoryStatus = async (category: Category) => {
    try {
        // Check authentication first
        if (!requireAuth(redirectToLogin)) {
            return
        }

        // Calculate the new status
        const newStatus = category.status === 'active' ? 'inactive' : 'active'

        // For PUT, we need to send all required fields for the resource
        const updateData = {
            name: category.name,
            description: category.description,
            status: newStatus
        }

        const response = await fetch(`http://127.0.0.1:8000/api/product_category/${category.id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                ...getAuthHeaders(),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(updateData)
        })

        if (!response.ok) {
            const responseText = await response.text()
            let errorData
            try {
                errorData = JSON.parse(responseText)
            } catch (e) {
                errorData = { message: responseText }
            }
            
            // Handle authentication errors
            if (handleAuthError(response.status, redirectToLogin)) {
                return
            }
            
            throw new Error(errorData.message || `Failed to toggle category status (HTTP ${response.status})`)
        }
        
        // Immediately update the local category status for instant UI feedback
        const categoryIndex = categories.value.findIndex(c => c.id === category.id)
        if (categoryIndex !== -1) {
            // Update the local array immediately
            categories.value[categoryIndex] = {
                ...categories.value[categoryIndex],
                status: newStatus
            }
            
            // Force Vue to re-render the table
            tableKey.value++
            await nextTick()
        }
        
        // Show success message
        alert(`Category "${category.name}" status updated to ${newStatus} successfully`)
        
        // Then refresh from API to ensure data consistency
        console.log(`Refreshing page ${currentPage.value} to sync with backend`)
        setTimeout(async () => {
            await fetchCategories(currentPage.value)
        }, 1000)
        
    } catch (error) {
        console.error('Error toggling category status:', error)
        
        // Show error message
        const errorMessage = error instanceof Error ? error.message : 'Failed to toggle category status'
        alert(`Error: ${errorMessage}`)
        
        // Refresh the current page to restore correct state if there was an error
        await fetchCategories(currentPage.value)
    }
}

// Placeholder functions for edit and delete (you can implement these later)
const editCategory = (category: Category) => {
    console.log('Edit category:', category)
    alert(`Edit functionality for "${category.name}" - Coming soon!`)
}

const deleteCategory = async (category: Category) => {
    console.log('Delete category:', category)
    
    // Show confirmation dialog
    if (!confirm(`Are you sure you want to delete "${category.name}"?`)) {
        return
    }

    try {
        // Check authentication first
        if (!requireAuth(redirectToLogin)) {
            return
        }

        console.log(`Deleting category with ID: ${category.id}`)
        const response = await fetch(`http://127.0.0.1:8000/api/product_category/${category.id}`, {
            method: 'DELETE',
            headers: getAuthHeaders()
        })

        console.log(`Delete response status: ${response.status}`)

        if (!response.ok) {
            const errorData = await response.json()
            
            // Handle authentication errors
            if (handleAuthError(response.status, redirectToLogin)) {
                return
            }
            
            throw new Error(errorData.message || 'Failed to delete category')
        }

        const result = await response.json()
        console.log('Category deleted successfully:', result)
        
        // Immediately remove the category from the local array to update UI instantly
        console.log(`Categories before removal: ${categories.value.length}`)
        categories.value = categories.value.filter(c => c.id !== category.id)
        console.log(`Categories after removal: ${categories.value.length}`)
        
        // Update total count
        totalCategories.value = Math.max(0, totalCategories.value - 1)
        
        // Force Vue to update the table immediately
        tableKey.value++
        await nextTick()
        
        // Show success message
        alert(`Category "${category.name}" has been deleted successfully!`)
        
        // Then refresh from API to ensure data consistency
        console.log(`Refreshing page ${currentPage.value} after deletion`)
        await fetchCategories(currentPage.value)
        
        // If current page is now empty and we're not on page 1, go to previous page
        if (categories.value.length === 0 && currentPage.value > 1) {
            console.log(`Current page is empty, going to page ${currentPage.value - 1}`)
            await fetchCategories(currentPage.value - 1)
        }
    } catch (error) {
        console.error('Error deleting category:', error)
        
        // Show error message
        const errorMessage = error instanceof Error ? error.message : 'Failed to delete category'
        alert(`Error: ${errorMessage}`)
    }
}
</script>

<style scoped>
/* Custom Button Styling */
:deep(.add-btn) {
    background: #84cc16 !important;
    border: 1px solid #84cc16 !important;
    color: white !important;
    transition: all 0.2s ease !important;
    font-weight: 700 !important;
    font-size: 0.75rem !important;
    padding: 0.5rem 1rem !important;
    border-radius: 0.25rem !important;
}

:deep(.add-btn:hover) {
    background: #65a30d !important;
    border-color: #65a30d !important;
}

:deep(.reset-btn) {
    background: white !important;
    border: 1px solid #d1d5db !important;
    color: #374151 !important;
    font-weight: 700 !important;
    font-size: 0.75rem !important;
    padding: 0.5rem 1rem !important;
    border-radius: 0.25rem !important;
    transition: all 0.2s ease !important;
}

:deep(.reset-btn:hover) {
    background: #f9fafb !important;
    border-color: #9ca3af !important;
}

/* Form Input Styling */
:deep(.p-inputtext),
:deep(.p-dropdown),
:deep(.p-dropdown-label) {
    border: 1px solid #d1d5db !important;
    border-radius: 0.25rem !important;
    padding: 0.5rem 0.75rem !important;
    font-size: 0.875rem !important;
    width: 100% !important;
}

:deep(.p-inputtext:focus),
:deep(.p-dropdown:focus) {
    border-color: #84cc16 !important;
    box-shadow: 0 0 0 2px rgba(132, 204, 22, 0.2) !important;
}

:deep(.p-invalid) {
    border-color: #ef4444 !important;
}

/* Textarea Styling */
:deep(.p-inputtextarea) {
    border: 1px solid #d1d5db !important;
    border-radius: 0.25rem !important;
    padding: 0.5rem 0.75rem !important;
    font-size: 0.875rem !important;
    width: 100% !important;
    resize: vertical !important;
}

:deep(.p-inputtextarea:focus) {
    border-color: #84cc16 !important;
    box-shadow: 0 0 0 2px rgba(132, 204, 22, 0.2) !important;
}

/* DataTable Styling */
:deep(.p-datatable) {
    border: none;
    border-radius: 0.5rem;
}

:deep(.p-datatable .p-datatable-thead > tr > th) {
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    padding: 1rem 1.5rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 500;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

:deep(.p-datatable .p-datatable-tbody > tr > td) {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    background: white;
}

:deep(.p-datatable .p-datatable-tbody > tr:hover) {
    background: #f9fafb;
}

:deep(.p-datatable .p-datatable-tbody > tr:last-child > td) {
    border-bottom: none;
}

/* Tag Styling */
:deep(.p-tag.p-tag-success) {
    background: #dcfce7;
    color: #166534;
    border: none;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
}

:deep(.p-tag.p-tag-secondary) {
    background: #f3f4f6;
    color: #4b5563;
    border: none;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
}

/* Form Container Styling */
.bg-white {
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

/* Pagination styling */
button[class*="px-3 py-1"] {
    transition: all 0.2s ease;
}

button[class*="px-3 py-1"]:hover:not(:disabled) {
    background-color: #f3f4f6;
}

.bg-orange-400 {
    background-color: #fb923c !important;
}

.bg-yellow-400 {
    background-color: #fbbf24 !important;
}

/* Menu Styling */
:deep(.p-menu) {
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    min-width: 180px;
}

:deep(.p-menu .p-menuitem-link) {
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    color: #374151;
    transition: all 0.2s ease;
}

:deep(.p-menu .p-menuitem-link:hover) {
    background: #f9fafb;
    color: #111827;
}

:deep(.p-menu .p-menuitem-icon) {
    color: #6b7280;
    margin-right: 0.5rem;
}

:deep(.p-menu .p-separator) {
    margin: 0.25rem 0;
    border-top: 1px solid #e5e7eb;
}
</style>