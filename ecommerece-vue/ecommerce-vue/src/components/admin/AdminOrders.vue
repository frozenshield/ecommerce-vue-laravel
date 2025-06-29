<template>
    <div class="p-6">
        <!-- Header with Search, Filter and Actions -->
        <div class="flex items-center justify-between gap-4 mb-6">
            <!-- Left: Search and Filter -->
            <div class="flex items-center gap-4">
                <div class="relative">
                    <InputText 
                        v-model="searchQuery"
                        placeholder="Search..."
                        class="pl-10 pr-4 py-2 w-80 border border-gray-300 rounded-sm text-sm"
                    />
                    <i class="pi pi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                
                <Dropdown 
                    v-model="selectedStatus"
                    :options="statusOptions"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="Status"
                    class="w-48 border border-gray-300 rounded-sm"
                />
                
                <Button 
                    icon="pi pi-filter"
                    label="FILTER"
                    class="filter-btn bg-gray-100 border border-gray-300 text-gray-700 font-bold text-xs px-6 py-2 rounded-sm"
                    @click="applyFilter"
                />
            </div>

            <!-- Right: Action Buttons -->
            <div class="flex items-center gap-2">
                <Button 
                    icon="pi pi-plus"
                    label="NEW ORDER"
                    class="new-order-btn font-bold text-xs px-6 py-2 rounded-sm"
                    @click="createNewOrder"
                />
                <Button 
                    icon="pi pi-download"
                    label="EXPORT"
                    class="export-btn bg-white border border-gray-300 text-gray-700 font-bold text-xs px-6 py-2 rounded-sm"
                    @click="exportOrders"
                />
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <DataTable 
                :value="orders" 
                :paginator="false"
                tableStyle="min-width: 50rem"
                class="p-datatable-sm"
            >
                <Column field="id" header="ORDER ID" class="text-left">
                    <template #body="slotProps">
                        <span class="text-blue-600 font-medium text-sm">#{{ slotProps.data.id }}</span>
                    </template>
                </Column>
                
                <Column field="customer" header="CUSTOMER" class="text-left">
                    <template #body="slotProps">
                        <span class="text-gray-900 text-sm">{{ slotProps.data.customer }}</span>
                    </template>
                </Column>
                
                <Column field="date" header="DATE" class="text-left">
                    <template #body="slotProps">
                        <span class="text-gray-500 text-sm">{{ slotProps.data.date }}</span>
                    </template>
                </Column>
                
                <Column field="status" header="STATUS" class="text-left">
                    <template #body="slotProps">
                        <Tag 
                            :value="slotProps.data.status" 
                            :severity="getStatusSeverity(slotProps.data.status)"
                            class="text-xs font-semibold"
                        />
                    </template>
                </Column>
                
                <Column field="total" header="TOTAL" class="text-left">
                    <template #body="slotProps">
                        <span class="text-gray-900 text-sm font-medium">{{ slotProps.data.total }}</span>
                    </template>
                </Column>
                
                <Column field="items" header="ITEMS" class="text-left">
                    <template #body="slotProps">
                        <span class="text-gray-500 text-sm">{{ slotProps.data.items }} items</span>
                    </template>
                </Column>
                
                <Column header="ACTIONS" class="text-left">
                    <template #body="slotProps">
                        <div class="flex items-center gap-2">
                            <Button 
                                icon="pi pi-eye" 
                                text 
                                severity="secondary" 
                                size="small"
                                class="text-gray-400 hover:text-blue-600"
                                @click="viewOrder(slotProps.data)"
                                v-tooltip="'View Order'"
                            />
                            <Button 
                                icon="pi pi-pencil" 
                                text 
                                severity="secondary" 
                                size="small"
                                class="text-gray-400 hover:text-green-600"
                                @click="editOrder(slotProps.data)"
                                v-tooltip="'Edit Order'"
                            />
                            <Button 
                                icon="pi pi-ellipsis-h" 
                                text 
                                severity="secondary" 
                                size="small"
                                class="text-gray-400 hover:text-gray-600"
                                @click="showMoreActions(slotProps.data)"
                                v-tooltip="'More Actions'"
                            />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between mt-6">
            <div class="text-sm text-gray-700">
                Showing {{ startRecord }} to {{ endRecord }} of {{ totalRecords }} entries
            </div>
            <Paginator 
                :rows="rowsPerPage" 
                :totalRecords="totalRecords" 
                :first="(currentPage - 1) * rowsPerPage"
                @page="onPageChange"
                template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
                class="border-none bg-transparent"
            >
                <template #firstpagelinkicon>
                    <i class="pi pi-angle-double-left text-gray-500"></i>
                </template>
                <template #prevpagelinkicon>
                    <i class="pi pi-chevron-left text-gray-500"></i>
                </template>
                <template #nextpagelinkicon>
                    <i class="pi pi-chevron-right text-gray-500"></i>
                </template>
                <template #lastpagelinkicon>
                    <i class="pi pi-angle-double-right text-gray-500"></i>
                </template>
            </Paginator>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import Dropdown from 'primevue/dropdown'
import Button from 'primevue/button'
import Tag from 'primevue/tag'
import Paginator from 'primevue/paginator'

const router = useRouter()

// Reactive data
const searchQuery = ref('')
const selectedStatus = ref(null)
const currentPage = ref(1)
const rowsPerPage = ref(10)

// Status options for dropdown
const statusOptions = [
    { label: 'All Status', value: null },
    { label: 'Pending', value: 'Pending' },
    { label: 'Processing', value: 'Processing' },
    { label: 'Shipped', value: 'Shipped' },
    { label: 'Delivered', value: 'Delivered' },
    { label: 'Cancelled', value: 'Cancelled' }
]

// Sample orders data
const orders = ref([
    {
        id: '10001',
        customer: 'John Smith',
        date: '2025-06-28',
        status: 'Delivered',
        total: '£156.99',
        items: 3
    },
    {
        id: '10002',
        customer: 'Sarah Johnson',
        date: '2025-06-27',
        status: 'Processing',
        total: '£299.50',
        items: 5
    },
    {
        id: '10003',
        customer: 'Mike Davis',
        date: '2025-06-27',
        status: 'Shipped',
        total: '£89.99',
        items: 2
    },
    {
        id: '10004',
        customer: 'Emily Brown',
        date: '2025-06-26',
        status: 'Pending',
        total: '£449.99',
        items: 7
    },
    {
        id: '10005',
        customer: 'David Wilson',
        date: '2025-06-26',
        status: 'Cancelled',
        total: '£79.99',
        items: 1
    },
    {
        id: '10006',
        customer: 'Lisa Anderson',
        date: '2025-06-25',
        status: 'Delivered',
        total: '£199.99',
        items: 4
    },
    {
        id: '10007',
        customer: 'Robert Taylor',
        date: '2025-06-25',
        status: 'Processing',
        total: '£329.99',
        items: 6
    },
    {
        id: '10008',
        customer: 'Jennifer Garcia',
        date: '2025-06-24',
        status: 'Shipped',
        total: '£159.99',
        items: 3
    }
])

// Computed properties
const totalRecords = computed(() => orders.value.length)
const startRecord = computed(() => (currentPage.value - 1) * rowsPerPage.value + 1)
const endRecord = computed(() => Math.min(currentPage.value * rowsPerPage.value, totalRecords.value))

// Methods
const getStatusSeverity = (status: string) => {
    switch (status) {
        case 'Delivered': return 'success'
        case 'Processing': return 'info'
        case 'Shipped': return 'warning'
        case 'Pending': return 'secondary'
        case 'Cancelled': return 'danger'
        default: return 'secondary'
    }
}

const onPageChange = (event: any) => {
    currentPage.value = event.page + 1
}

const applyFilter = () => {
    console.log('Applying filter with:', { searchQuery: searchQuery.value, status: selectedStatus.value })
    // Implement filter logic here
}

const createNewOrder = () => {
    console.log('Creating new order')
    // Navigate to new order page or open modal
}

const exportOrders = () => {
    console.log('Exporting orders')
    // Implement export functionality
}

const viewOrder = (order: any) => {
    console.log('Viewing order:', order)
    // Navigate to order details page
}

const editOrder = (order: any) => {
    console.log('Editing order:', order)
    // Navigate to edit order page
}

const showMoreActions = (order: any) => {
    console.log('More actions for order:', order)
    // Show context menu or dropdown with more actions
}
</script>

<style scoped>
/* Custom Button Styling */
:deep(.new-order-btn) {
    background: #65a30d !important;
    border: 1px solid #65a30d !important;
    color: white !important;
    transition: all 0.2s ease !important;
    font-weight: 700 !important;
    font-size: 0.75rem !important;
    padding: 0.5rem 1.5rem !important;
    border-radius: 0.125rem !important;
}

:deep(.new-order-btn:hover) {
    background: #4d7c0f !important;
    border-color: #4d7c0f !important;
}

:deep(.filter-btn) {
    background: #f3f4f6 !important;
    border: 1px solid #d1d5db !important;
    color: #374151 !important;
    font-weight: 700 !important;
    font-size: 0.75rem !important;
    padding: 0.5rem 1.5rem !important;
    border-radius: 0.125rem !important;
    transition: all 0.2s ease !important;
}

:deep(.filter-btn:hover) {
    background: #e5e7eb !important;
}

:deep(.export-btn) {
    background: white !important;
    border: 1px solid #d1d5db !important;
    color: #374151 !important;
    font-weight: 700 !important;
    font-size: 0.75rem !important;
    padding: 0.5rem 1.5rem !important;
    border-radius: 0.125rem !important;
    transition: all 0.2s ease !important;
}

:deep(.export-btn:hover) {
    background: #f9fafb !important;
}

/* Search Input Styling */
:deep(.p-inputtext) {
    border: 1px solid #d1d5db !important;
    border-radius: 0.125rem !important;
    padding: 0.5rem 0.75rem !important;
    font-size: 0.875rem !important;
}

:deep(.p-inputtext:focus) {
    border-color: #65a30d !important;
    box-shadow: 0 0 0 2px rgba(101, 163, 13, 0.2) !important;
}

/* Dropdown Styling */
:deep(.p-dropdown) {
    border: 1px solid #d1d5db !important;
    border-radius: 0.125rem !important;
    font-size: 0.875rem !important;
}

:deep(.p-dropdown:focus) {
    border-color: #65a30d !important;
    box-shadow: 0 0 0 2px rgba(101, 163, 13, 0.2) !important;
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

/* Tag Styling for Order Status */
:deep(.p-tag.p-tag-success) {
    background: #dcfce7;
    color: #166534;
    border: none;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
}

:deep(.p-tag.p-tag-info) {
    background: #dbeafe;
    color: #1e40af;
    border: none;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
}

:deep(.p-tag.p-tag-warning) {
    background: #fef3c7;
    color: #92400e;
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

:deep(.p-tag.p-tag-danger) {
    background: #fecaca;
    color: #991b1b;
    border: none;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
}

/* Paginator Styling */
:deep(.p-paginator) {
    border: none;
    background: transparent;
    padding: 0;
}

:deep(.p-paginator .p-paginator-pages .p-paginator-page) {
    background: transparent;
    border: none;
    color: #6b7280;
    padding: 0.5rem 0.75rem;
    margin: 0 0.125rem;
    border-radius: 0.25rem;
    min-width: auto;
}

:deep(.p-paginator .p-paginator-pages .p-paginator-page.p-highlight) {
    background: white;
    color: black;
    border: 1px solid #d1d5db;
}

:deep(.p-paginator .p-paginator-pages .p-paginator-page:hover) {
    background: #f3f4f6;
    color: #374151;
}

:deep(.p-paginator .p-paginator-first),
:deep(.p-paginator .p-paginator-prev),
:deep(.p-paginator .p-paginator-next),
:deep(.p-paginator .p-paginator-last) {
    background: transparent;
    border: none;
    color: #6b7280;
    padding: 0.5rem 0.75rem;
    margin: 0 0.125rem;
}

:deep(.p-paginator .p-paginator-first:hover),
:deep(.p-paginator .p-paginator-prev:hover),
:deep(.p-paginator .p-paginator-next:hover),
:deep(.p-paginator .p-paginator-last:hover) {
    background: #f3f4f6;
    color: #374151;
}

/* Action Button Hover Effects */
:deep(.p-button-text:hover .pi-eye) {
    color: #2563eb !important;
}

:deep(.p-button-text:hover .pi-pencil) {
    color: #16a34a !important;
}
</style>
