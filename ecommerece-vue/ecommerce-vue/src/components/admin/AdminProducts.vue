<template>
    <div class="flex justify-end mt-10">
        <Button 
            @click="goToAddProduct"
            class="add-product-btn font-bold text-xs px-6 py-2 rounded-sm"
        >
            <span class="text-lg mr-2">+</span>
            NEW PRODUCT
        </Button>
    </div>

    <div class="flex items-center gap-4 mb-6 mt-8 justify-between">
        <!-- Left: Filters and Filter Button -->
        <div class="flex items-center gap-4">
            <select class="w-64 border border-gray-300 rounded-sm px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-lime-400">
                <option>Select Category</option>
                <!-- Add more options here -->
            </select>
            <select class="w-64 border border-gray-300 rounded-sm px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-lime-400">
                <option>Product Type</option>
            </select>
            <select class="w-64 border border-gray-300 rounded-sm px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-lime-400">
                <option>Status</option>
            </select>
            <button class="flex items-center gap-2 bg-gray-100 border border-gray-300 rounded-sm px-6 py-2 text-xs font-bold text-gray-700 hover:bg-gray-200 transition">
                <i class="pi pi-filter"></i>
                FILTER
            </button>
        </div>
        <!-- Right: Search Bar -->
        <div class="flex items-center border border-gray-300 rounded-sm px-2 py-2 max-w-xs w-full bg-white">
            <input type="text" placeholder="Search product" class="flex-1 border-none outline-none text-sm bg-transparent" />
            <i class="pi pi-search text-gray-400"></i>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <DataTable 
            :value="products" 
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
                    <span class="text-gray-900 text-sm">{{ slotProps.data.name }}</span>
                </template>
            </Column>
            
            <Column field="sku" header="SKU" class="text-left">
                <template #body="slotProps">
                    <span class="text-gray-500 text-sm">{{ slotProps.data.sku }}</span>
                </template>
            </Column>
            
            <Column field="stock" header="Stock" class="text-left">
                <template #body="slotProps">
                    <Tag 
                        :value="slotProps.data.stock" 
                        :severity="slotProps.data.stock === 'Stock' ? 'success' : 'danger'"
                        class="text-xs font-semibold"
                    />
                </template>
            </Column>
            
            <Column field="price" header="Price" class="text-left">
                <template #body="slotProps">
                    <span class="text-gray-900 text-sm">{{ slotProps.data.price }}</span>
                </template>
            </Column>
            
            <Column field="categories" header="Categories" class="text-left">
                <template #body="slotProps">
                    <span class="text-gray-500 text-sm">{{ slotProps.data.categories }}</span>
                </template>
            </Column>
            
            <Column field="date" header="Date" class="text-left">
                <template #body="slotProps">
                    <span class="text-gray-500 text-sm">{{ slotProps.data.date }}</span>
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

    <div class="flex items-center justify-between mt-6">
        <div class="text-sm text-gray-700">
            Show 10 in 30 items.
        </div>
        <Paginator 
            :rows="10" 
            :totalRecords="30" 
            :first="(currentPage - 1) * 10"
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

</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Tag from 'primevue/tag'
import Button from 'primevue/button'
import Paginator from 'primevue/paginator'

const router = useRouter()
const currentPage = ref(1)

const goToAddProduct = () => {
    router.push({ name: 'AdminAddProduct' })
}

const onPageChange = (event: any) => {
    currentPage.value = event.page + 1
}

const handleActionMenu = (product: any) => {
    console.log('Action menu clicked for product:', product)
    // Add your action menu logic here
}

const products = ref([
    {
        id: 'ABH-0',
        name: 'Herschel Leather Duffle Bag In Brown Color',
        sku: 'AB12345789-1',
        stock: 'Stock',
        price: '£125.30',
        categories: 'Bags,Clothing & Apparel',
        date: '2019/11/06'
    },
   
])
</script>

<style scoped>
/* Custom Add Product Button */
:deep(.add-product-btn) {
    background: #65a30d !important;
    border: 1px solid #65a30d !important;
    color: white !important;
    transition: all 0.2s ease !important;
    font-weight: 700 !important;
    font-size: 0.75rem !important;
    padding: 0.5rem 1.5rem !important;
    border-radius: 0.125rem !important;
}

:deep(.add-product-btn:hover) {
    background: #4d7c0f !important;
    border-color: #4d7c0f !important;
}

:deep(.add-product-btn:focus) {
    box-shadow: 0 0 0 2px rgba(101, 163, 13, 0.2) !important;
}

/* Custom PrimeVue DataTable styling */
:deep(.p-datatable) {
    border: none;
    border-radius: 0.5rem;
}

:deep(.p-datatable .p-datatable-header) {
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    padding: 1rem 1.5rem;
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

/* Custom Tag styling */
:deep(.p-tag.p-tag-success) {
    background: #dcfce7;
    color: #166534;
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

/* Custom Paginator styling */
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
</style>