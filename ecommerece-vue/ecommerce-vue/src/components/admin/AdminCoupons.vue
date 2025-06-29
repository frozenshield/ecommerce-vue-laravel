<template>
    <div class="flex gap-6 p-6">
        <!-- Left: Coupons Table -->
        <div class="flex-1">
            <!-- Coupons Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <DataTable 
                    :value="coupons" 
                    :paginator="false"
                    tableStyle="min-width: 50rem"
                    class="p-datatable-sm"
                >
                    <Column field="code" header="Code" class="text-left">
                        <template #body="slotProps">
                            <span class="text-gray-900 text-sm font-medium">{{ slotProps.data.code }}</span>
                        </template>
                    </Column>
                    
                    <Column field="percent" header="Percent" class="text-left">
                        <template #body="slotProps">
                            <span class="text-orange-600 text-sm font-medium">{{ slotProps.data.percent }}</span>
                        </template>
                    </Column>
                    
                    <Column field="currency" header="Currency" class="text-left">
                        <template #body="slotProps">
                            <span class="text-gray-900 text-sm">{{ slotProps.data.currency }}</span>
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
                    Show {{ coupons.length }} in {{ totalCoupons }} items.
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

        <!-- Right: New Coupon Form -->
        <div class="w-80">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">New Coupon</h3>
                
                <form @submit.prevent="handleSubmit" class="space-y-4">
                    <!-- Coupon Code -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Coupon Code</label>
                        <InputText 
                            v-model="newCoupon.code" 
                            placeholder="placeholder"
                            class="w-full"
                            :class="{ 'p-invalid': errors.code }"
                        />
                        <small v-if="errors.code" class="text-red-500">{{ errors.code }}</small>
                    </div>

                    <!-- Discount Type -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">By Percent</label>
                        <InputNumber 
                            v-model="newCoupon.percent" 
                            placeholder="Enter percentage"
                            suffix="%"
                            :min="0"
                            :max="100"
                            class="w-full"
                        />
                    </div>

                    <!-- Currency Value -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">By Currency</label>
                        <InputNumber 
                            v-model="newCoupon.currency" 
                            mode="currency"
                            currency="USD"
                            locale="en-US"
                            placeholder="Enter amount"
                            class="w-full"
                        />
                    </div>

                    <!-- Expired Date -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Expired Date</label>
                        <Calendar 
                            v-model="newCoupon.expiredDate" 
                            placeholder="Select date"
                            dateFormat="mm/dd/yy"
                            class="w-full"
                        />
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Description</label>
                        <Textarea 
                            v-model="newCoupon.description" 
                            placeholder="Enter category description"
                            rows="4"
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
import InputNumber from 'primevue/inputnumber'
import Textarea from 'primevue/textarea'
import Calendar from 'primevue/calendar'

const loading = ref(false)
const totalCoupons = ref(30)

const newCoupon = reactive({
    code: '',
    percent: null,
    currency: null,
    expiredDate: null,
    description: ''
})

const errors = reactive({
    code: ''
})

// Sample coupons data from the image
const coupons = ref([
    {
        code: 'MARTFURY-2020',
        percent: '10%',
        currency: '$50.00',
        createdAt: 'Jul 21, 2020',
        status: 'Expired'
    },
    {
        code: 'MARTFURY-MID2020',
        percent: '5%',
        currency: '$25.00',
        createdAt: 'Jul 21, 2020',
        status: 'Expired'
    },
    {
        code: 'SUMMERHOT',
        percent: '7.5%',
        currency: '$50.00',
        createdAt: 'Jul 21, 2020',
        status: 'Expired'
    },
    {
        code: 'EXPLORE2020',
        percent: '3%',
        currency: '$10.00',
        createdAt: 'Jul 21, 2020',
        status: 'Expired'
    },
    {
        code: 'LAPTOP2020',
        percent: '10%',
        currency: '$50.00',
        createdAt: 'Jul 21, 2020',
        status: 'Expired'
    }
])

const validateForm = () => {
    errors.code = ''
    
    if (!newCoupon.code.trim()) {
        errors.code = 'Coupon code is required'
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
        
        // Add new coupon to the list
        const newCouponData = {
            code: newCoupon.code,
            percent: newCoupon.percent ? `${newCoupon.percent}%` : '0%',
            currency: newCoupon.currency ? `$${Number(newCoupon.currency).toFixed(2)}` : '$0.00',
            createdAt: new Date().toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            }),
            status: 'Active'
        }
        
        coupons.value.unshift(newCouponData)
        
        // Reset form
        resetForm()
        
        console.log('Coupon added:', newCouponData)
    } catch (error) {
        console.error('Error adding coupon:', error)
    } finally {
        loading.value = false
    }
}

const resetForm = () => {
    newCoupon.code = ''
    newCoupon.percent = null
    newCoupon.currency = null
    newCoupon.expiredDate = null
    newCoupon.description = ''
    errors.code = ''
}

const handleActionMenu = (coupon: any) => {
    console.log('Action menu clicked for coupon:', coupon)
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
:deep(.p-inputnumber-input),
:deep(.p-calendar-w-btn .p-inputtext) {
    border: 1px solid #d1d5db !important;
    border-radius: 0.25rem !important;
    padding: 0.5rem 0.75rem !important;
    font-size: 0.875rem !important;
    width: 100% !important;
}

:deep(.p-inputtext:focus),
:deep(.p-inputnumber:focus-within),
:deep(.p-calendar:focus-within) {
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

/* Calendar Styling */
:deep(.p-calendar) {
    width: 100% !important;
}

:deep(.p-calendar .p-inputtext) {
    width: 100% !important;
}

/* InputNumber Styling */
:deep(.p-inputnumber) {
    width: 100% !important;
}

:deep(.p-inputnumber .p-inputnumber-input) {
    width: 100% !important;
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

/* Tag Styling for Coupon Status */
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

/* Orange percentage styling */
.text-orange-600 {
    color: #ea580c;
}
</style>