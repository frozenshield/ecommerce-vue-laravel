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

            <!-- Right: Add Customer Button -->
            <div class="flex items-center gap-2">
                <Button 
                    icon="pi pi-plus"
                    label="ADD CUSTOMER"
                    class="add-customer-btn font-bold text-xs px-6 py-2 rounded-sm"
                    @click="addCustomer"
                />
            </div>
        </div>

        <!-- Customers Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <DataTable 
                :value="customers" 
                :paginator="false"
                tableStyle="min-width: 50rem"
                class="p-datatable-sm"
            >
                <Column field="id" header="ID" class="text-left">
                    <template #body="slotProps">
                        <span class="text-blue-600 font-medium text-sm">{{ slotProps.data.id }}</span>
                    </template>
                </Column>
                
                <Column field="name" header="Name" class="text-left">
                    <template #body="slotProps">
                        <span class="text-gray-900 text-sm font-medium">{{ slotProps.data.name }}</span>
                    </template>
                </Column>
                
                <Column field="phone" header="Phone Number" class="text-left">
                    <template #body="slotProps">
                        <span class="text-gray-500 text-sm">{{ slotProps.data.phone }}</span>
                    </template>
                </Column>
                
                <Column field="balance" header="Balances" class="text-left">
                    <template #body="slotProps">
                        <span class="text-blue-600 text-sm font-medium">{{ slotProps.data.balance }}</span>
                    </template>
                </Column>
                
                <Column field="orders" header="Total orders" class="text-left">
                    <template #body="slotProps">
                        <span class="text-gray-900 text-sm">{{ slotProps.data.orders }}</span>
                    </template>
                </Column>
                
                <Column field="status" header="Status" class="text-left">
                    <template #body="slotProps">
                        <Tag 
                            :value="slotProps.data.status" 
                            :severity="getStatusSeverity(slotProps.data.status)"
                            class="text-xs font-semibold"
                        />
                    </template>
                </Column>
                
                <Column field="createdAt" header="Created at" class="text-left">
                    <template #body="slotProps">
                        <span class="text-gray-500 text-sm">{{ slotProps.data.createdAt }}</span>
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
                Show {{ customers.length }} in {{ totalCustomers }} items.
            </div>
            <div class="flex items-center space-x-2">
                <button @click="goToPreviousPage" :disabled="currentPage === 1" class="px-3 py-1 text-sm text-gray-500 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="pi pi-chevron-left"></i>
                </button>
                <button 
                    v-for="page in [1, 2, 3]" 
                    :key="page"
                    @click="goToPage(page)"
                    :class="currentPage === page ? 'bg-yellow-400 text-black' : 'text-gray-500 hover:text-gray-700'"
                    class="px-3 py-1 text-sm rounded cursor-pointer"
                >
                    {{ page }}
                </button>
                <button @click="goToNextPage" :disabled="currentPage === 3" class="px-3 py-1 text-sm text-gray-500 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="pi pi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import InputText from 'primevue/inputtext'
import Dropdown from 'primevue/dropdown'
import Button from 'primevue/button'
import Tag from 'primevue/tag'

// Reactive data
const searchQuery = ref('')
const selectedStatus = ref(null)
const currentPage = ref(1)
const totalCustomers = ref(30)

// Status options for dropdown
const statusOptions = [
    { label: 'All Status', value: null },
    { label: 'Active', value: 'active' },
    { label: 'Block', value: 'block' },
    { label: 'Pending', value: 'pending' }
]

// Sample customers data from the image
const customers = ref([
    {
        id: 'MATCUS-0',
        name: 'Jenny Simmonds',
        phone: '(+921) 211-32-1145',
        balance: '$211.00',
        orders: 10,
        status: 'active',
        createdAt: 'Jul 21, 2020'
    },
    {
        id: 'MATCUS-1',
        name: 'Ammara Molloy',
        phone: '(+921) 916-971-217',
        balance: '$211.00',
        orders: 10,
        status: 'active',
        createdAt: 'Jul 21, 2020'
    },
    {
        id: 'MATCUS-2',
        name: 'Anisa Forster',
        phone: '(+921) 319-176-113',
        balance: '$211.00',
        orders: 10,
        status: 'active',
        createdAt: 'Jul 21, 2020'
    },
    {
        id: 'MATCUS-3',
        name: 'Hashir Wilson',
        phone: '(+921) 393-112-298',
        balance: '$211.00',
        orders: 10,
        status: 'block',
        createdAt: 'Jul 21, 2020'
    },
    {
        id: 'MATCUS-4',
        name: 'Grover Sampson',
        phone: '(+921) 393-872-137',
        balance: '$211.00',
        orders: 10,
        status: 'active',
        createdAt: 'Jul 21, 2020'
    },
    {
        id: 'MATCUS-5',
        name: 'Nelson Mckeown',
        phone: '(+921) 393-872-998',
        balance: '$211.00',
        orders: 10,
        status: 'block',
        createdAt: 'Jul 21, 2020'
    },
    {
        id: 'MATCUS-6',
        name: 'Zunaira Akhtar',
        phone: '(+921) 393-872-145',
        balance: '$211.00',
        orders: 10,
        status: 'active',
        createdAt: 'Jul 21, 2020'
    },
    {
        id: 'MATCUS-7',
        name: 'Natan Kramer',
        phone: '(+921) 293-872-145',
        balance: '$211.00',
        orders: 10,
        status: 'block',
        createdAt: 'Jul 21, 2020'
    },
    {
        id: 'MATCUS-8',
        name: 'Jesse Pollard',
        phone: '(+921) 291-32-145',
        balance: '$211.00',
        orders: 10,
        status: 'active',
        createdAt: 'Jul 21, 2020'
    }
])

// Methods
const getStatusSeverity = (status: string) => {
    switch (status) {
        case 'active': return 'success'
        case 'block': return 'danger'
        case 'pending': return 'warning'
        default: return 'secondary'
    }
}

const goToPage = (page: number) => {
    currentPage.value = page
}

const goToPreviousPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--
    }
}

const goToNextPage = () => {
    if (currentPage.value < 3) {
        currentPage.value++
    }
}

const applyFilter = () => {
    console.log('Applying filter with:', { searchQuery: searchQuery.value, status: selectedStatus.value })
    // Implement filter logic here
}

const addCustomer = () => {
    console.log('Adding new customer')
    // Navigate to add customer page or open modal
}

const handleActionMenu = (customer: any) => {
    console.log('Action menu clicked for customer:', customer)
    // Add your action menu logic here
}
</script>

<style scoped>
/* Custom Button Styling */
:deep(.add-customer-btn) {
    background: #84cc16 !important;
    border: 1px solid #84cc16 !important;
    color: white !important;
    transition: all 0.2s ease !important;
    font-weight: 700 !important;
    font-size: 0.75rem !important;
    padding: 0.5rem 1.5rem !important;
    border-radius: 0.125rem !important;
}

:deep(.add-customer-btn:hover) {
    background: #65a30d !important;
    border-color: #65a30d !important;
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

/* Search Input Styling */
:deep(.p-inputtext) {
    border: 1px solid #d1d5db !important;
    border-radius: 0.125rem !important;
    padding: 0.5rem 0.75rem !important;
    font-size: 0.875rem !important;
}

:deep(.p-inputtext:focus) {
    border-color: #84cc16 !important;
    box-shadow: 0 0 0 2px rgba(132, 204, 22, 0.2) !important;
}

/* Dropdown Styling */
:deep(.p-dropdown) {
    border: 1px solid #d1d5db !important;
    border-radius: 0.125rem !important;
    font-size: 0.875rem !important;
}

:deep(.p-dropdown:focus) {
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

/* Tag Styling for Customer Status */
:deep(.p-tag.p-tag-success) {
    background: #84cc16;
    color: white;
    border: none;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
}

:deep(.p-tag.p-tag-danger) {
    background: #64748b;
    color: white;
    border: none;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
}

:deep(.p-tag.p-tag-warning) {
    background: #f59e0b;
    color: white;
    border: none;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Pagination Styling */
button[class*="px-3 py-1"] {
    transition: all 0.2s ease;
    border: none;
    background: transparent;
}

button[class*="px-3 py-1"]:hover:not(:disabled) {
    background-color: #f3f4f6;
}

button[class*="px-3 py-1"]:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.bg-yellow-400 {
    background-color: #fbbf24 !important;
    border-radius: 0.25rem;
}

/* Search Input Container */
.relative .pi-search {
    pointer-events: none;
}

/* Table alternating row colors */
:deep(.p-datatable .p-datatable-tbody > tr:nth-child(even)) {
    background: #fafafa;
}

:deep(.p-datatable .p-datatable-tbody > tr:nth-child(even):hover) {
    background: #f3f4f6;
}
</style>