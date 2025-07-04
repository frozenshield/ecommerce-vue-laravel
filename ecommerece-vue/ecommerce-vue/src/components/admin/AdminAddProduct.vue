<template>
    <div class="p-6">
        <!-- Header with Back Button -->
        <div class="flex items-center gap-4 mb-6">
            <Button 
                icon="pi pi-arrow-left" 
                text 
                severity="secondary"
                @click="goBack"
                class="text-gray-600 hover:text-gray-800"
            />
            <h1 class="text-2xl font-bold text-gray-900">Add New Product</h1>
        </div>

        <!-- Add Product Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form @submit.prevent="handleSubmit" class="space-y-6">
                <!-- Product Name -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Product Name *</label>
                    <InputText 
                        v-model="product.product_name" 
                        placeholder="Enter product name"
                        class="w-full"
                        :class="{ 'p-invalid': errors.product_name }"
                    />
                    <small v-if="errors.product_name" class="text-red-500">{{ errors.product_name }}</small>
                </div>

                <!-- Category -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Category *</label>
                    <Dropdown 
                        v-model="product.category" 
                        :options="categoryOptions"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="Select Category"
                        :loading="categoriesLoading"
                        class="w-full"
                        :class="{ 'p-invalid': errors.category }"
                    />
                    <small v-if="errors.category" class="text-red-500">{{ errors.category }}</small>
                    <small v-if="categoriesLoading" class="text-blue-500">Loading categories...</small>
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Description</label>
                    <Textarea 
                        v-model="product.product_description" 
                        placeholder="Enter product description"
                        rows="4"
                        class="w-full"
                    />
                </div>

                <!-- Price and Stock -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Price *</label>
                        <InputNumber 
                            v-model="product.product_price" 
                            mode="currency"
                            currency="PHP"
                            locale="en-US"
                            :min="0"
                            :maxFractionDigits="2"
                            class="w-full"
                            :class="{ 'p-invalid': errors.product_price }"
                        />
                        <small v-if="errors.product_price" class="text-red-500">{{ errors.product_price }}</small>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Stock Quantity *</label>
                        <InputNumber 
                            v-model="product.product_stock" 
                            :min="0"
                            class="w-full"
                            :class="{ 'p-invalid': errors.product_stock }"
                        />
                        <small v-if="errors.product_stock" class="text-red-500">{{ errors.product_stock }}</small>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Product Image</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                        <input 
                            type="file" 
                            ref="fileInput"
                            accept="image/*"
                            @change="handleImageUpload"
                            class="hidden"
                        />
                        <div v-if="!product.image_url" class="space-y-2">
                            <i class="pi pi-cloud-upload text-3xl text-gray-400"></i>
                            <p class="text-sm text-gray-600">
                                <button 
                                    type="button" 
                                    @click="fileInput.click()"
                                    class="text-blue-600 hover:text-blue-700 font-medium"
                                >
                                    Click to upload
                                </button>
                                or drag and drop
                            </p>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                        </div>
                        <div v-else class="space-y-2">
                            <img 
                                :src="product.image_url" 
                                alt="Product preview"
                                class="w-32 h-32 object-cover rounded-lg mx-auto border border-gray-200"
                            />
                            <div class="flex gap-2 justify-center">
                                <button 
                                    type="button" 
                                    @click="fileInput.click()"
                                    class="text-blue-600 hover:text-blue-700 text-sm font-medium"
                                >
                                    Change Image
                                </button>
                                <button 
                                    type="button" 
                                    @click="removeImage"
                                    class="text-red-600 hover:text-red-700 text-sm font-medium"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                    <Button 
                        type="button"
                        label="Cancel" 
                        severity="secondary"
                        outlined
                        @click="goBack"
                        class="px-6"
                    />
                    <Button 
                        type="submit"
                        label="Add Product" 
                        :loading="loading"
                        class="bg-lime-600 hover:bg-lime-700 border-lime-600 px-6"
                    />
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import InputNumber from 'primevue/inputnumber'
import Dropdown from 'primevue/dropdown'
import { getAuthHeaders, handleAuthError } from '../../utils/auth'

// Interfaces
interface Category {
    id?: number
    category_name: string
    name?: string
}

interface Product {
    product_name: string
    category: number | string  // Can be category_id (number) or empty string initially
    product_description: string
    product_price: number | null
    product_stock: number
    image_url: string
}

const router = useRouter()
const loading = ref(false)
const categoriesLoading = ref(false)
const fileInput = ref()

const product = reactive<Product>({
    product_name: '',
    category: '',
    product_description: '',
    product_price: null,
    product_stock: 0,
    image_url: ''
})

const errors = reactive({
    product_name: '',
    category: '',
    product_price: '',
    product_stock: ''
})

const categoryOptions = ref<Array<{label: string, value: string}>>([])

// Fetch categories from API
const fetchCategories = async () => {
    categoriesLoading.value = true
    try {
        const response = await fetch('http://127.0.0.1:8000/api/product_category/all', {
            method: 'GET',
            headers: getAuthHeaders()
        })
        
        if (!response.ok) {
            const handled = handleAuthError(response.status, () => router.push('/adminlogin'))
            if (handled) return
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        
        const result = await response.json()
        console.log('Categories API response:', result)
        console.log('Response type:', typeof result)
        console.log('Is array:', Array.isArray(result))
        
        // Handle different possible response structures
        let categoriesData: any[] = []
        if (result && result.data && Array.isArray(result.data)) {
            categoriesData = result.data
            console.log('Using result.data')
        } else if (Array.isArray(result)) {
            // Check if it's an array of arrays (your case)
            if (result.length > 0 && Array.isArray(result[0])) {
                categoriesData = result[0]
                console.log('Using result[0] - array of arrays detected')
            } else {
                categoriesData = result
                console.log('Using result directly')
            }
        } else if (result && result.categories && Array.isArray(result.categories)) {
            categoriesData = result.categories
            console.log('Using result.categories')
        } else {
            console.log('No matching structure found, checking result properties:', Object.keys(result || {}))
        }
        
        console.log('Categories data extracted:', categoriesData)
        console.log('First category sample:', categoriesData[0])
        
        // Map categories to dropdown options with extensive field detection
        categoryOptions.value = categoriesData.map((category: any) => {
            console.log('Processing category:', category)
            console.log('Category keys:', Object.keys(category))
            
            // Use category_name for display (what user sees)
            const label = category.category_name || 
                         category.name || 
                         category.title || 
                         `Category ${category.category_id || category.id || 'Unknown'}`
            
            // Use category_id for the actual value (what gets submitted)
            const value = category.category_id?.toString() || 
                         category.id?.toString() || 
                         category.product_category_id?.toString() ||
                         ''
            
            console.log(`Mapped: "${label}" (ID: ${value})`)
            
            return {
                label: label,        // Display name: "Electronics", "Fashion", etc.
                value: value         // Actual ID: 1, 2, 3, etc.
            }
        })
        
        console.log('Final mapped categories:', categoryOptions.value)
        
    } catch (error) {
        console.error('Error fetching categories:', error)
        
        // Fallback to default categories if API fails
        categoryOptions.value = [
            { label: 'Electronics', value: 'Electronics' },
            { label: 'Fashion', value: 'Fashion' },
            { label: 'Computer', value: 'Computer' },
            { label: 'Gaming', value: 'Gaming' },
            { label: 'Accessories', value: 'Accessories' },
            { label: 'Health', value: 'Health' },
            { label: 'Home & Garden', value: 'Home' },
            { label: 'Sports', value: 'Sports' },
            { label: 'Books', value: 'Books' },
            { label: 'Beauty', value: 'Beauty' }
        ]
        
        alert('Failed to load categories. Using default categories.')
    } finally {
        categoriesLoading.value = false
    }
}

// Load categories when component mounts
onMounted(() => {
    fetchCategories()
})

const handleImageUpload = (event: Event) => {
    const target = event.target as HTMLInputElement
    const file = target.files?.[0]
    
    if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file')
            return
        }
        
        // Validate file size (10MB max)
        if (file.size > 10 * 1024 * 1024) {
            alert('File size must be less than 10MB')
            return
        }
        
        // Create preview URL
        const reader = new FileReader()
        reader.onload = (e) => {
            product.image_url = e.target?.result as string
        }
        reader.readAsDataURL(file)
    }
}

const removeImage = () => {
    product.image_url = ''
    if (fileInput.value) {
        fileInput.value.value = ''
    }
}

const validateForm = () => {
    // Reset errors
    errors.product_name = ''
    errors.category = ''
    errors.product_price = ''
    errors.product_stock = ''

    let isValid = true

    if (!product.product_name.trim()) {
        errors.product_name = 'Product name is required'
        isValid = false
    }

    if (!product.category) {
        errors.category = 'Category is required'
        isValid = false
    }

    if (!product.product_price || product.product_price <= 0) {
        errors.product_price = 'Price is required and must be greater than 0'
        isValid = false
    }

    if (product.product_stock < 0) {
        errors.product_stock = 'Stock quantity cannot be negative'
        isValid = false
    }

    return isValid
}

const handleSubmit = async () => {
    if (!validateForm()) {
        return
    }

    loading.value = true

    try {
        const response = await fetch('http://127.0.0.1:8000/api/products', {
            method: 'POST',
            headers: getAuthHeaders(),
            body: JSON.stringify({
                product_name: product.product_name,
                product_category_id: parseInt(product.category.toString()), // Convert to number
                product_description: product.product_description,
                product_price: product.product_price,
                product_stock: product.product_stock,
                image_url: product.image_url || null
            })
        })
        
        if (!response.ok) {
            const handled = handleAuthError(response.status, () => router.push('/adminlogin'))
            if (handled) return
            
            const errorData = await response.json().catch(() => ({}))
            console.error('Server error response:', errorData)
            console.error('Request payload was:', {
                product_name: product.product_name,
                product_category_id: product.category,
                product_description: product.product_description,
                product_price: product.product_price,
                product_stock: product.product_stock,
                image_url: product.image_url || null
            })
            
            // Handle validation errors from Laravel
            if (response.status === 422 && errorData.errors) {
                console.error('Validation errors:', errorData.errors)
                
                // Map Laravel validation errors to our error object
                Object.keys(errorData.errors).forEach(field => {
                    // Map backend field names to frontend field names
                    const fieldMapping: { [key: string]: string } = {
                        'product_category_id': 'category',
                        'product_name': 'product_name',
                        'product_price': 'product_price',
                        'product_stock': 'product_stock'
                    }
                    
                    const frontendField = fieldMapping[field] || field
                    if (frontendField in errors) {
                        errors[frontendField as keyof typeof errors] = errorData.errors[field][0]
                    }
                })
                
                // Show validation errors in alert for debugging
                const errorMessages = Object.entries(errorData.errors)
                    .map(([field, messages]) => `${field}: ${Array.isArray(messages) ? messages[0] : messages}`)
                    .join('\n')
                alert(`Validation errors:\n${errorMessages}`)
                return
            }
            
            throw new Error(errorData.message || `HTTP error! status: ${response.status}`)
        }
        
        const result = await response.json()
        console.log('Product added successfully:', result)
        
        // Show success message (you can use PrimeVue Toast here)
        alert('Product added successfully!')
        
        // Navigate back to products list
        router.push({ name: 'AdminProducts' })
    } catch (error) {
        console.error('Error adding product:', error)
        alert(`Error adding product: ${error instanceof Error ? error.message : 'Please try again.'}`)
    } finally {
        loading.value = false
    }
}

const goBack = () => {
    router.push({ name: 'AdminProducts' })
}
</script>

<style scoped>
/* Custom styling for form elements */
:deep(.p-inputtext),
:deep(.p-dropdown),
:deep(.p-multiselect),
:deep(.p-inputnumber-input) {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

:deep(.p-dropdown) {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
}

:deep(.p-dropdown .p-dropdown-label) {
    border: none;
    padding: 0.5rem 0.75rem;
}

:deep(.p-inputtext:focus),
:deep(.p-dropdown:focus),
:deep(.p-multiselect:focus),
:deep(.p-inputnumber:focus-within) {
    border-color: #84cc16;
    box-shadow: 0 0 0 2px rgba(132, 204, 22, 0.2);
    outline: none;
}

:deep(.p-invalid) {
    border-color: #ef4444;
}

:deep(.p-button) {
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.5rem 1.5rem;
}

/* Image upload area styling */
.border-dashed:hover {
    border-color: #9ca3af;
    background-color: #f9fafb;
}

/* File input button styling */
button[type="button"] {
    transition: color 0.2s ease;
}

button[type="button"]:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}
</style>
