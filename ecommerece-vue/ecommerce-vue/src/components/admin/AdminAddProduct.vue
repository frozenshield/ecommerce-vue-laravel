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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Product Name *</label>
                        <InputText 
                            v-model="product.name" 
                            placeholder="Enter product name"
                            class="w-full"
                            :class="{ 'p-invalid': errors.name }"
                        />
                        <small v-if="errors.name" class="text-red-500">{{ errors.name }}</small>
                    </div>

                    <!-- SKU -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">SKU *</label>
                        <InputText 
                            v-model="product.sku" 
                            placeholder="Enter SKU"
                            class="w-full"
                            :class="{ 'p-invalid': errors.sku }"
                        />
                        <small v-if="errors.sku" class="text-red-500">{{ errors.sku }}</small>
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Description</label>
                    <Textarea 
                        v-model="product.description" 
                        placeholder="Enter product description"
                        rows="4"
                        class="w-full"
                    />
                </div>

                <!-- Price and Stock -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Price *</label>
                        <InputNumber 
                            v-model="product.price" 
                            mode="currency"
                            currency="GBP"
                            locale="en-GB"
                            class="w-full"
                            :class="{ 'p-invalid': errors.price }"
                        />
                        <small v-if="errors.price" class="text-red-500">{{ errors.price }}</small>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Stock Quantity</label>
                        <InputNumber 
                            v-model="product.stockQuantity" 
                            :min="0"
                            class="w-full"
                        />
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Status</label>
                        <Dropdown 
                            v-model="product.status" 
                            :options="statusOptions"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Select Status"
                            class="w-full"
                        />
                    </div>
                </div>

                <!-- Categories -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Categories</label>
                    <MultiSelect 
                        v-model="product.categories" 
                        :options="categoryOptions"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="Select Categories"
                        class="w-full"
                        :maxSelectedLabels="3"
                    />
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
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import InputNumber from 'primevue/inputnumber'
import Dropdown from 'primevue/dropdown'
import MultiSelect from 'primevue/multiselect'

const router = useRouter()
const loading = ref(false)

const product = reactive({
    name: '',
    sku: '',
    description: '',
    price: null,
    stockQuantity: 0,
    status: 'Stock',
    categories: []
})

const errors = reactive({
    name: '',
    sku: '',
    price: ''
})

const statusOptions = [
    { label: 'In Stock', value: 'Stock' },
    { label: 'Out of Stock', value: 'Out-of-stock' }
]

const categoryOptions = [
    { label: 'Bags', value: 'bags' },
    { label: 'Clothing & Apparel', value: 'clothing' },
    { label: 'Computers & Technologies', value: 'computers' },
    { label: 'Technologies', value: 'technologies' },
    { label: 'Babies & Moms', value: 'babies' },
    { label: 'Refrigerators', value: 'refrigerators' },
    { label: 'Accessories', value: 'accessories' },
    { label: 'Air Conditioners', value: 'air-conditioners' },
    { label: 'Books & Office', value: 'books' },
    { label: 'Cars & Motorcycles', value: 'cars' }
]

const validateForm = () => {
    // Reset errors
    errors.name = ''
    errors.sku = ''
    errors.price = ''

    let isValid = true

    if (!product.name) {
        errors.name = 'Product name is required'
        isValid = false
    }

    if (!product.sku) {
        errors.sku = 'SKU is required'
        isValid = false
    }

    if (!product.price || product.price <= 0) {
        errors.price = 'Price is required and must be greater than 0'
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
        // Simulate API call
        await new Promise(resolve => setTimeout(resolve, 1000))
        
        console.log('Product added:', product)
        
        // Show success message (you can use PrimeVue Toast here)
        alert('Product added successfully!')
        
        // Navigate back to products list
        router.push({ name: 'AdminProducts' })
    } catch (error) {
        console.error('Error adding product:', error)
        alert('Error adding product. Please try again.')
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
    border-radius: 0.125rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

:deep(.p-inputtext:focus),
:deep(.p-dropdown:focus),
:deep(.p-multiselect:focus),
:deep(.p-inputnumber:focus-within) {
    border-color: #84cc16;
    box-shadow: 0 0 0 2px rgba(132, 204, 22, 0.2);
}

:deep(.p-invalid) {
    border-color: #ef4444;
}

:deep(.p-button) {
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.5rem 1.5rem;
}
</style>
