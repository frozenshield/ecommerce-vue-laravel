<template>
    <div class="flex gap-6 p-6">
        <!-- Left: Coupons Table -->
        <div class="flex-1">
            <!-- Coupons Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <DataTable 
                    :value="coupons" 
                    :paginator="false"
                    :key="`table-${currentPage}-${tableKey}`"
                    tableStyle="min-width: 50rem"
                    class="p-datatable-sm"
                >
                    <template #empty>
                        <div class="text-center py-8">
                            <i class="pi pi-info-circle text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500">{{ loading ? 'Loading coupons...' : 'No coupons found' }}</p>
                            <p class="text-xs text-gray-400 mt-2">
                                {{ loading ? 'Please wait while we fetch the data.' : 'Add a new coupon using the form on the right.' }}
                            </p>
                        </div>
                    </template>
                    <Column field="coupon_code" header="Coupon Code" class="text-left">
                        <template #body="slotProps">
                            <span class="text-gray-900 text-sm font-medium">{{ slotProps.data.coupon_code }}</span>
                        </template>
                    </Column>
                    
                    <Column field="by_percent" header="By Percent" class="text-left">
                        <template #body="slotProps">
                            <span class="text-orange-600 text-sm font-medium">
                                {{ slotProps.data.by_percent ? `${slotProps.data.by_percent}%` : '-' }}
                            </span>
                        </template>
                    </Column>
                    
                    <Column field="by_currency" header="By Currency" class="text-left">
                        <template #body="slotProps">
                            <span class="text-gray-900 text-sm">
                                {{ slotProps.data.by_currency ? `Php ${slotProps.data.by_currency}` : '-' }}
                            </span>
                        </template>
                    </Column>
                    
                    <Column field="expired_date" header="Expired Date" class="text-left">
                        <template #body="slotProps">
                            <span class="text-gray-500 text-sm">{{ slotProps.data.expired_date }}</span>
                        </template>
                    </Column>
                    
                    <Column field="status" header="Status" class="text-left">
                        <template #body="slotProps">
                            <Tag 
                                :value="slotProps.data.status" 
                                :severity="slotProps.data.status === 'active' ? 'success' : 'secondary'"
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
                :model="selectedCoupon ? getMenuItems(selectedCoupon) : []" 
                :popup="true" 
            />

            <!-- Pagination -->
            <div class="flex items-center justify-between mt-6">
                <div class="text-sm text-gray-700">
                    Showing {{ coupons.length }} of {{ totalCoupons }} coupons
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

        <!-- Right: New Coupon Form -->
        <div class="w-80">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">New Coupon</h3>
                
                <form @submit.prevent="handleSubmit" class="space-y-4">
                    <!-- Coupon Code -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Coupon Code</label>
                        <InputText 
                            v-model="newCoupon.coupon_code" 
                            placeholder="Enter coupon code"
                            class="w-full"
                            :class="{ 'p-invalid': errors.coupon_code }"
                            maxlength="100"
                        />
                        <small v-if="errors.coupon_code" class="text-red-500">{{ errors.coupon_code }}</small>
                    </div>

                    <!-- Discount Type -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">By Percent (%)</label>
                        <InputNumber 
                            v-model="newCoupon.by_percent" 
                            placeholder="Enter percentage"
                            suffix="%"
                            :min="0"
                            :max="99.99"
                            :maxFractionDigits="2"
                            class="w-full"
                        />
                    </div>

                    <!-- Currency Value -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">By Currency (Php)</label>
                        <InputNumber 
                            v-model="newCoupon.by_currency" 
                            mode="currency"
                            currency="USD"
                            locale="en-US"
                            placeholder="Enter amount"
                            :min="0"
                            :maxFractionDigits="2"
                            class="w-full"
                        />
                    </div>

                    <!-- Expired Date -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Expired Date</label>
                        <Calendar 
                            v-model="newCoupon.expired_date" 
                            placeholder="Select date"
                            dateFormat="yy-mm-dd"
                            :minDate="new Date()"
                            class="w-full"
                        />
                    </div>

                    <!-- Status -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Status</label>
                        <Dropdown 
                            v-model="newCoupon.status" 
                            :options="statusOptions"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="Select Status"
                            class="w-full"
                        />
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Description</label>
                        <Textarea 
                            v-model="newCoupon.description" 
                            placeholder="Enter coupon description"
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
import { ref, reactive, onMounted, nextTick, computed } from 'vue'
import { useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Tag from 'primevue/tag'
import Button from 'primevue/button'
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import Textarea from 'primevue/textarea'
import Calendar from 'primevue/calendar'
import Dropdown from 'primevue/dropdown'
import Menu from 'primevue/menu'
import { getAuthHeaders, handleAuthError, requireAuth } from '../../utils/auth'

// Define interface for coupon
interface Coupon {
    coupon_id?: number
    coupon_code: string
    by_percent: number
    by_currency: number
    expired_date: string
    status: string
    description?: string
}

const loading = ref(false)
const totalCoupons = ref(0)
const currentPage = ref(1)
const tableKey = ref(0)
const router = useRouter()
const menu = ref()
const selectedCoupon = ref<Coupon | null>(null)

// Computed properties for pagination
const totalPages = computed(() => {
    return Math.ceil(totalCoupons.value / 10) // 10 items per page
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

const newCoupon = reactive({
    coupon_code: '',
    by_percent: null,
    by_currency: null,
    expired_date: null,
    status: 'active',
    description: ''
})

const errors = reactive({
    coupon_code: ''
})

const statusOptions = [
    { label: 'Active', value: 'active' },
    { label: 'Inactive', value: 'inactive' }
]

// Sample coupons data matching the database schema
const coupons = ref<Coupon[]>([
    // Initial empty array - will be populated from API
])


// Pagination functions
const goToPage = async (page: number | string) => {
    if (typeof page === 'string') return // Skip ellipsis clicks
    
    if (page >= 1 && page <= totalPages.value && page !== currentPage.value) {
        await fetchCoupons(page)
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

// Fetch coupons from API
const fetchCoupons = async (page: number = 1) => {
    loading.value = true
    console.log('fetchCoupons called with page:', page)
    try {
        // Temporarily disable auth check for testing
        /*
        // Check authentication first
        if (!requireAuth(redirectToLogin)) {
            console.log('Authentication check failed')
            loading.value = false
            return
        }
        */

        const apiUrl = `http://127.0.0.1:8000/api/coupon?page=${page}&per_page=10`
        console.log('Fetching from URL:', apiUrl)

        const response = await fetch(apiUrl, {
            method: 'GET',
            headers: getAuthHeaders()
        })

        console.log('Response status:', response.status)
        console.log('Response content-type:', response.headers.get('content-type'))

        if (!response.ok) {
            const errorText = await response.text()
            console.log('Error response text:', errorText)
            
            // Handle authentication errors
            if (handleAuthError(response.status, redirectToLogin)) {
                return
            }
            
            throw new Error(`Failed to fetch coupons: ${response.status} - ${errorText}`)
        }

        // Check if response is JSON before parsing
        const contentType = response.headers.get('content-type')
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Server returned non-JSON response. Check your Laravel API.')
        }

        const result = await response.json()
        console.log('API Response:', result)
        
        // Handle different possible response structures
        let couponsData = []
        
        console.log('Processing result:', result)
        
        if (result && result.data && Array.isArray(result.data)) {
            // Laravel pagination structure
            couponsData = result.data
            console.log('Using Laravel pagination structure, data:', couponsData)
        } else if (Array.isArray(result)) {
            // Direct array structure
            couponsData = result
            console.log('Using direct array structure, data:', couponsData)
        } else if (result && result.coupons && Array.isArray(result.coupons)) {
            // Custom coupons structure
            couponsData = result.coupons
            console.log('Using custom coupons structure, data:', couponsData)
        } else {
            console.log('Unknown response structure:', result)
        }
        
        console.log('Final couponsData:', couponsData)
        
        // Map API response to component format
        const apiCoupons = couponsData.map((coupon: any, index: number) => ({
            coupon_id: coupon.coupon_id || coupon.id || index + 1,
            coupon_code: coupon.coupon_code || 'Unknown Code',
            by_percent: coupon.by_percent || 0,
            by_currency: coupon.by_currency || 0,
            expired_date: coupon.expired_date ? new Date(coupon.expired_date).toISOString().split('T')[0] : new Date().toISOString().split('T')[0],
            status: coupon.status || 'active',
            description: coupon.description || ''
        }))

        console.log('Mapped apiCoupons:', apiCoupons)

        // Set coupons data
        coupons.value = apiCoupons
        console.log('coupons.value after assignment:', coupons.value)
        
        // Handle Laravel pagination metadata
        if (result && result.total !== undefined) {
            totalCoupons.value = result.total
        } else if (result && result.count !== undefined) {
            totalCoupons.value = result.count
        } else if (result && result.last_page !== undefined) {
            // Laravel pagination sometimes uses last_page and per_page
            const perPage = result.per_page || 10
            totalCoupons.value = (result.last_page - 1) * perPage + apiCoupons.length
        } else {
            // Fallback calculation
            if (apiCoupons.length === 10) {
                totalCoupons.value = Math.max(50, page * 10 + 10) // Estimate
            } else {
                totalCoupons.value = (page - 1) * 10 + apiCoupons.length
            }
        }
        
        currentPage.value = page
        
        // Force Vue to update the table
        tableKey.value++
        await nextTick()
        
    } catch (error) {
        // Set empty array if there's an error
        coupons.value = []
        
        const errorMessage = error instanceof Error ? error.message : 'Failed to fetch coupons'
        alert(`Error: ${errorMessage}`)
        
    } finally {
        loading.value = false
    }
}

// Load coupons when component mounts
onMounted(() => {
    console.log('AdminCoupons component mounted, calling fetchCoupons(1)')
    
    // Then try to fetch from API
    fetchCoupons(1)
})

// Menu items for coupon actions
const getMenuItems = (coupon: Coupon) => [
    {
        label: `Mark as ${coupon.status === 'active' ? 'inactive' : 'active'}`,
        icon: coupon.status === 'active' ? 'pi pi-eye-slash' : 'pi pi-eye',
        command: () => toggleCouponStatus(coupon)
    },
    {
        separator: true
    },
    {
        label: 'Edit Coupon',
        icon: 'pi pi-pencil',
        command: () => editCoupon(coupon)
    },
    {
        label: 'Delete Coupon',
        icon: 'pi pi-trash',
        command: () => deleteCoupon(coupon)
    }
]

// Show action menu for coupon
const showActionMenu = (event: Event, coupon: Coupon) => {
    selectedCoupon.value = coupon
    menu.value.toggle(event)
}

const validateForm = () => {
    errors.coupon_code = ''
    
    if (!newCoupon.coupon_code.trim()) {
        errors.coupon_code = 'Coupon code is required'
        return false
    }
    
    if (newCoupon.coupon_code.length > 100) {
        errors.coupon_code = 'Coupon code must be 100 characters or less'
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
        const couponData = {
            coupon_code: newCoupon.coupon_code,
            by_percent: newCoupon.by_percent || 0,
            by_currency: newCoupon.by_currency || 0,
            expired_date: newCoupon.expired_date ? 
                new Date(newCoupon.expired_date).toISOString().split('T')[0] : 
                new Date().toISOString().split('T')[0],
            status: newCoupon.status,
            description: newCoupon.description || ''
        }

        // Make API call to Laravel backend
        const response = await fetch('http://127.0.0.1:8000/api/coupon', {
            method: 'POST',
            headers: getAuthHeaders(),
            body: JSON.stringify(couponData)
        })

        if (!response.ok) {
            const errorData = await response.json()
            
            // Handle authentication errors
            if (handleAuthError(response.status, redirectToLogin)) {
                return
            }
            
            throw new Error(errorData.message || 'Failed to create coupon')
        }

        // After adding, calculate the last page where the new coupon will be
        const newTotal = totalCoupons.value + 1
        const perPage = 10 // As defined in the fetch function
        const lastPage = Math.ceil(newTotal / perPage)

        // Fetch the last page to show the new coupon
        await fetchCoupons(lastPage)
        
        // Reset form
        resetForm()
        
        // Show success message
        alert('Coupon created successfully!')
        
    } catch (error) {
        console.error('Error adding coupon:', error)
        
        // Show error message
        const errorMessage = error instanceof Error ? error.message : 'Failed to create coupon'
        alert(`Error: ${errorMessage}`)
    } finally {
        loading.value = false
    }
}

const resetForm = () => {
    newCoupon.coupon_code = ''
    newCoupon.by_percent = null
    newCoupon.by_currency = null
    newCoupon.expired_date = null
    newCoupon.status = 'active'
    newCoupon.description = ''
    errors.coupon_code = ''
}

// Toggle coupon status between Active/Inactive
const toggleCouponStatus = async (coupon: Coupon) => {
    try {
        // Check authentication first
        if (!requireAuth(redirectToLogin)) {
            return
        }

        // Calculate the new status
        const newStatus = coupon.status === 'active' ? 'inactive' : 'active'

        // For the API, we need to send all required fields for the resource
        const updateData = {
            coupon_code: coupon.coupon_code,
            by_percent: coupon.by_percent,
            by_currency: coupon.by_currency,
            expired_date: coupon.expired_date,
            status: newStatus,
            description: coupon.description || ''
        }

        const response = await fetch(`http://127.0.0.1:8000/api/coupon/${coupon.coupon_id}/toggle-status`, {
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
            
            throw new Error(errorData.message || `Failed to toggle coupon status (HTTP ${response.status})`)
        }
        
        // Immediately update the local coupon status for instant UI feedback
        const couponIndex = coupons.value.findIndex(c => c.coupon_id === coupon.coupon_id)
        if (couponIndex !== -1) {
            // Update the local array immediately
            coupons.value[couponIndex] = {
                ...coupons.value[couponIndex],
                status: newStatus
            }
            
            // Force Vue to re-render the table
            tableKey.value++
            await nextTick()
        }
        
        // Show success message
        alert(`Coupon "${coupon.coupon_code}" status updated to ${newStatus} successfully`)
        
        // Then refresh from API to ensure data consistency
        setTimeout(async () => {
            await fetchCoupons(currentPage.value)
        }, 1000)
        
    } catch (error) {
        console.error('Error toggling coupon status:', error)
        
        // Show error message
        const errorMessage = error instanceof Error ? error.message : 'Failed to toggle coupon status'
        alert(`Error: ${errorMessage}`)
        
        // Refresh the current page to restore correct state if there was an error
        await fetchCoupons(currentPage.value)
    }
}

// Edit coupon function (placeholder)
const editCoupon = (coupon: Coupon) => {
    console.log('Edit coupon:', coupon)
    alert(`Edit functionality for "${coupon.coupon_code}" - Coming soon!`)
}

// Delete coupon function
const deleteCoupon = async (coupon: Coupon) => {
    console.log('Delete coupon:', coupon)
    
    // Show confirmation dialog
    if (!confirm(`Are you sure you want to delete "${coupon.coupon_code}"?`)) {
        return
    }

    try {
        // Check authentication first
        if (!requireAuth(redirectToLogin)) {
            return
        }

        const response = await fetch(`http://127.0.0.1:8000/api/coupon/${coupon.coupon_id}`, {
            method: 'DELETE',
            headers: getAuthHeaders()
        })

        if (!response.ok) {
            const errorData = await response.json()
            
            // Handle authentication errors
            if (handleAuthError(response.status, redirectToLogin)) {
                return
            }
            
            throw new Error(errorData.message || 'Failed to delete coupon')
        }
        
        // Immediately remove the coupon from the local array to update UI instantly
        coupons.value = coupons.value.filter(c => c.coupon_id !== coupon.coupon_id)
        
        // Update total count
        totalCoupons.value = Math.max(0, totalCoupons.value - 1)
        
        // Force Vue to update the table immediately
        tableKey.value++
        await nextTick()
        
        // Show success message
        alert(`Coupon "${coupon.coupon_code}" has been deleted successfully!`)
        
        // Then refresh from API to ensure data consistency
        await fetchCoupons(currentPage.value)
        
        // If current page is now empty and we're not on page 1, go to previous page
        if (coupons.value.length === 0 && currentPage.value > 1) {
            await fetchCoupons(currentPage.value - 1)
        }
    } catch (error) {
        console.error('Error deleting coupon:', error)
        
        // Show error message
        const errorMessage = error instanceof Error ? error.message : 'Failed to delete coupon'
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