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
                    Show {{ categories.length }} in {{ totalCategories }} items.
                </div>
                <div class="flex items-center space-x-2">
                    <button 
                        @click="goToPreviousPage" 
                        :disabled="currentPage <= 1"
                        class="px-3 py-1 text-sm text-gray-500 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <i class="pi pi-chevron-left"></i>
                    </button>
                    <button 
                        @click="goToPage(1)"
                        :class="currentPage === 1 ? 'px-3 py-1 text-sm bg-yellow-400 text-black rounded' : 'px-3 py-1 text-sm text-gray-500 hover:text-gray-700'"
                    >
                        1
                    </button>
                    <button 
                        @click="goToPage(2)"
                        :class="currentPage === 2 ? 'px-3 py-1 text-sm bg-yellow-400 text-black rounded' : 'px-3 py-1 text-sm text-gray-500 hover:text-gray-700'"
                    >
                        2
                    </button>
                    <button 
                        @click="goToPage(3)"
                        :class="currentPage === 3 ? 'px-3 py-1 text-sm bg-yellow-400 text-black rounded' : 'px-3 py-1 text-sm text-gray-500 hover:text-gray-700'"
                    >
                        3
                    </button>
                    <button 
                        @click="goToNextPage" 
                        :disabled="currentPage >= Math.ceil(totalCategories / 10)"
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
import { ref, reactive, onMounted, nextTick } from 'vue'
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
const totalCategories = ref(50)
const currentPage = ref(1)
const tableKey = ref(0)
const router = useRouter()
const menu = ref()
const selectedCategory = ref<Category | null>(null)

// Helper function to redirect to login
const redirectToLogin = () => {
    router.push('/adminlogin')
}

const newCategory = reactive({
    name: '',
    description: '',
    status: 'Active'
})

const errors = reactive({
    name: ''
})

const statusOptions = [
    { label: 'Active', value: 'Active' },
    { label: 'Inactive', value: 'Inactive' }
]

const categories = ref<Category[]>([
    // Initial empty array - will be populated from API
])

// Menu items for category actions
const getMenuItems = (category: Category) => [
    {
        label: `Mark as ${category.status === 'Active' ? 'Inactive' : 'Active'}`,
        icon: category.status === 'Active' ? 'pi pi-eye-slash' : 'pi pi-eye',
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
const goToPage = async (page: number) => {
    console.log(`Attempting to go to page ${page}`)
    console.log(`Current page: ${currentPage.value}`)
    console.log(`Total categories: ${totalCategories.value}`)
    console.log(`Max pages: ${Math.ceil(totalCategories.value / 10)}`)
    
    if (page >= 1 && page <= Math.ceil(totalCategories.value / 10)) {
        console.log(`Fetching page ${page}...`)
        await fetchCategories(page)
    } else {
        console.log(`Page ${page} is out of range`)
    }
}

const goToPreviousPage = async () => {
    if (currentPage.value > 1) {
        await goToPage(currentPage.value - 1)
    }
}

const goToNextPage = async () => {
    if (currentPage.value < Math.ceil(totalCategories.value / 10)) {
        await goToPage(currentPage.value + 1)
    }
}

// Fetch categories from API
const fetchCategories = async (page: number = 1) => {
    console.log(`=== FETCHING CATEGORIES FOR PAGE ${page} ===`)
    loading.value = true
    try {
        // Check authentication first
        if (!requireAuth(redirectToLogin)) {
            loading.value = false
            return
        }

        const apiUrl = `http://127.0.0.1:8000/api/product_category?page=${page}&per_page=10`
        console.log(`API URL: ${apiUrl}`)

        const response = await fetch(apiUrl, {
            method: 'GET',
            headers: getAuthHeaders()
        })

        console.log(`API Response status: ${response.status}`)

        if (!response.ok) {
            const errorData = await response.json()
            
            // Handle authentication errors
            if (handleAuthError(response.status, redirectToLogin)) {
                return
            }
            
            throw new Error(`Failed to fetch categories: ${response.status} - ${errorData.message || 'Unknown error'}`)
        }

        const result = await response.json()
        console.log('API Response:', result)
        console.log('Response status:', response.status)
        console.log('Response ok:', response.ok)
        
        // Handle different possible response structures
        let categoriesData = []
        
        if (Array.isArray(result.data)) {
            categoriesData = result.data
        } else if (Array.isArray(result)) {
            categoriesData = result
        } else if (result.categories && Array.isArray(result.categories)) {
            categoriesData = result.categories
        } else {
            console.warn('Unexpected API response structure:', result)
            categoriesData = []
        }
        
        console.log(`Found ${categoriesData.length} categories from API`)
        
        // Map API response to component format
        const apiCategories = categoriesData.map((category: any, index: number) => {
            return {
                id: category.id || index + 1,
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
                status: category.status || 'Active'
            }
        })

        if (apiCategories.length > 0) {
            console.log('Using API data')
            categories.value = apiCategories
            
            // Handle Laravel pagination metadata
            if (result.total !== undefined) {
                totalCategories.value = result.total
            } else if (result.count !== undefined) {
                totalCategories.value = result.count
            } else {
                totalCategories.value = apiCategories.length
            }
            
            currentPage.value = page
            console.log(`API data loaded: ${apiCategories.length} categories on page ${page}`)
            console.log(`Total categories: ${totalCategories.value}`)
            console.log('Categories on this page:', apiCategories.map((c: any) => c.name))
            
            // Force Vue to update the table
            tableKey.value++
            await nextTick()
        } else {
            console.log('API returned no data, using sample data')
            // If no categories returned, add some sample data for testing pagination
            // Create sample data based on page
            const sampleData = [
                { id: 1, name: 'Electronics', description: 'Electronic devices', products: 25, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 2, name: 'Clothing', description: 'Fashion items', products: 18, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 3, name: 'Books', description: 'Educational materials', products: 12, createdAt: 'Jun 29, 2025', status: 'inactive' },
                { id: 4, name: 'Home & Garden', description: 'Home improvement', products: 35, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 5, name: 'Sports', description: 'Sports equipment', products: 22, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 6, name: 'Beauty', description: 'Cosmetics and care', products: 40, createdAt: 'Jun 29, 2025', status: 'inactive' },
                { id: 7, name: 'Toys', description: 'Children toys', products: 15, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 8, name: 'Automotive', description: 'Car accessories', products: 28, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 9, name: 'Health', description: 'Health products', products: 33, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 10, name: 'Music', description: 'Musical instruments', products: 19, createdAt: 'Jun 29, 2025', status: 'inactive' },
                { id: 11, name: 'Pet Supplies', description: 'Pet food and toys', products: 27, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 12, name: 'Office', description: 'Office supplies', products: 24, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 13, name: 'Kitchen', description: 'Kitchen appliances', products: 31, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 14, name: 'Gaming', description: 'Video games', products: 16, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 15, name: 'Jewelry', description: 'Fashion jewelry', products: 29, createdAt: 'Jun 29, 2025', status: 'inactive' },
                { id: 16, name: 'Travel', description: 'Travel accessories', products: 21, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 17, name: 'Baby', description: 'Baby products', products: 38, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 18, name: 'Food', description: 'Food items', products: 42, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 19, name: 'Outdoor', description: 'Outdoor equipment', products: 26, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 20, name: 'Photography', description: 'Camera equipment', products: 14, createdAt: 'Jun 29, 2025', status: 'inactive' },
                { id: 21, name: 'Art & Craft', description: 'Art supplies', products: 17, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 22, name: 'Tools', description: 'Hand tools', products: 23, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 23, name: 'Garden', description: 'Garden tools', products: 30, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 24, name: 'Fitness', description: 'Fitness equipment', products: 25, createdAt: 'Jun 29, 2025', status: 'active' },
                { id: 25, name: 'Stationery', description: 'Office stationery', products: 20, createdAt: 'Jun 29, 2025', status: 'active' }
            ]
            
            const itemsPerPage = 10
            const startIndex = (page - 1) * itemsPerPage
            const endIndex = startIndex + itemsPerPage
            
            console.log(`Sample data pagination: startIndex=${startIndex}, endIndex=${endIndex}`)
            console.log(`Page ${page} should show categories ${startIndex + 1} to ${endIndex}`)
            
            categories.value = sampleData.slice(startIndex, endIndex)
            totalCategories.value = sampleData.length
            currentPage.value = page
            
            console.log(`Set ${categories.value.length} categories for page ${page}`)
            console.log('Categories:', categories.value.map(c => c.name))
            
            // Force Vue to update the table
            tableKey.value++
            await nextTick()
        }
        
        console.log(`=== FINAL STATE: Page ${currentPage.value}, Total: ${totalCategories.value}, Showing: ${categories.value.length} ===`)
        
    } catch (error) {
        console.error('Error fetching categories:', error)
        
        // Show user-friendly error message
        const errorMessage = error instanceof Error ? error.message : 'Failed to fetch categories'
        alert(`Error: ${errorMessage}`)
        
        // Keep categories empty to show the actual issue
        categories.value = []
    } finally {
        loading.value = false
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

        const result = await response.json()
        
        // Refresh current page to show updated data
        await fetchCategories(currentPage.value)
        
        // Reset form
        resetForm()
        
        // Show success message (you can replace this with a toast notification)
        alert('Category created successfully!')
        
        console.log('Category added successfully:', result)
    } catch (error) {
        console.error('Error adding category:', error)
        
        // Show error message (you can replace this with a toast notification)
        const errorMessage = error instanceof Error ? error.message : 'Failed to create category'
        alert(`Error: ${errorMessage}`)
    } finally {
        loading.value = false
    }
}

const resetForm = () => {
    newCategory.name = ''
    newCategory.description = ''
    newCategory.status = 'Active'
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

        const response = await fetch(`http://127.0.0.1:8000/api/product_category/${category.id}/toggle-status`, {
            method: 'PATCH',
            headers: getAuthHeaders()
        })

        if (!response.ok) {
            const errorData = await response.json()
            
            // Handle authentication errors
            if (handleAuthError(response.status, redirectToLogin)) {
                return
            }
            
            throw new Error(errorData.message || 'Failed to toggle category status')
        }

        const result = await response.json()
        
        // Refresh current page to show updated data
        await fetchCategories(currentPage.value)
        
        // Show success message
        alert(`Category status updated successfully`)
        
        console.log('Category status toggled successfully:', result)
    } catch (error) {
        console.error('Error toggling category status:', error)
        
        // Show error message
        const errorMessage = error instanceof Error ? error.message : 'Failed to toggle category status'
        alert(`Error: ${errorMessage}`)
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

        const response = await fetch(`http://127.0.0.1:8000/api/product_category/${category.id}`, {
            method: 'DELETE',
            headers: getAuthHeaders()
        })

        if (!response.ok) {
            const errorData = await response.json()
            
            // Handle authentication errors
            if (handleAuthError(response.status, redirectToLogin)) {
                return
            }
            
            throw new Error(errorData.message || 'Failed to delete category')
        }

        const result = await response.json()
        
        // If we're on the last page and it becomes empty, go to previous page
        const shouldGoToPreviousPage = categories.value.length === 1 && currentPage.value > 1
        const pageToFetch = shouldGoToPreviousPage ? currentPage.value - 1 : currentPage.value
        
        // Refresh the appropriate page
        await fetchCategories(pageToFetch)
        
        // Show success message
        alert(`Category "${category.name}" has been deleted successfully!`)
        
        console.log('Category deleted successfully:', result)
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

button[class*="px-3 py-1"]:hover {
    background-color: #f3f4f6;
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