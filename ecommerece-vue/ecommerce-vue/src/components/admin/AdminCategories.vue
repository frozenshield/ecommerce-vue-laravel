<template>
    <div class="flex gap-6 p-6">
        <!-- Left: Categories Table -->
        <div class="flex-1">
            <!-- Categories Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <DataTable 
                    :value="categories" 
                    :paginator="false"
                    tableStyle="min-width: 50rem"
                    class="p-datatable-sm"
                >
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
                                :severity="slotProps.data.status === 'Active' ? 'success' : 'secondary'"
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
                                @click="handleActionMenu(slotProps.data)"
                            />
                        </template>
                    </Column>
                </DataTable>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between mt-6">
                <div class="text-sm text-gray-700">
                    Show {{ categories.length }} in {{ totalCategories }} items.
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-1 text-sm text-gray-500 hover:text-gray-700">
                        <i class="pi pi-chevron-left"></i>
                    </button>
                    <button class="px-3 py-1 text-sm bg-yellow-400 text-black rounded">1</button>
                    <button class="px-3 py-1 text-sm text-gray-500 hover:text-gray-700">2</button>
                    <button class="px-3 py-1 text-sm text-gray-500 hover:text-gray-700">3</button>
                    <button class="px-3 py-1 text-sm text-gray-500 hover:text-gray-700">
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
import { ref, reactive } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Tag from 'primevue/tag'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import Dropdown from 'primevue/dropdown'

const loading = ref(false)
const totalCategories = ref(50)

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

// Sample categories data
const categories = ref([
    {
        name: 'Electronics',
        description: 'Electronic devices and gadgets',
        products: 125,
        createdAt: 'Jul 21, 2020',
        status: 'Active'
    },
    {
        name: 'Clothing & Apparel',
        description: 'Fashion and clothing items',
        products: 89,
        createdAt: 'Jul 21, 2020',
        status: 'Active'
    },
    {
        name: 'Home & Garden',
        description: 'Home improvement and garden supplies',
        products: 67,
        createdAt: 'Jul 21, 2020',
        status: 'Active'
    },
    {
        name: 'Sports & Outdoors',
        description: 'Sports equipment and outdoor gear',
        products: 45,
        createdAt: 'Jul 21, 2020',
        status: 'Inactive'
    },
    {
        name: 'Books & Media',
        description: 'Books, magazines, and media content',
        products: 34,
        createdAt: 'Jul 21, 2020',
        status: 'Active'
    }
])

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
        // Simulate API call
        await new Promise(resolve => setTimeout(resolve, 1000))
        
        // Add new category to the list
        const newCategoryData = {
            name: newCategory.name,
            description: newCategory.description || 'No description provided',
            products: 0,
            createdAt: new Date().toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            }),
            status: newCategory.status
        }
        
        categories.value.unshift(newCategoryData)
        
        // Reset form
        resetForm()
        
        console.log('Category added:', newCategoryData)
    } catch (error) {
        console.error('Error adding category:', error)
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

const handleActionMenu = (category: any) => {
    console.log('Action menu clicked for category:', category)
    // Add your action menu logic here
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
</style>