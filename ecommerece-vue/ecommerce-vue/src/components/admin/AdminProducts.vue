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
            <input 
                type="text" 
                placeholder="Search product" 
                v-model="searchQuery"
                @input="handleSearch"
                class="flex-1 border-none outline-none text-sm bg-transparent" 
            />
            <i class="pi pi-search text-gray-400"></i>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <DataTable 
            :value="products" 
            :paginator="true"
            :rows="10"
            :totalRecords="totalProducts"
            :lazy="true"
            :loading="loading"
            @page="onPageChange"
            tableStyle="min-width: 50rem"
            class="p-datatable-sm"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            :rowsPerPageOptions="[10, 25, 50]"
        >
            <Column field="product_id" header="Product ID" class="text-left">
                <template #body="slotProps">
                    <span class="text-blue-600 font-medium text-sm">{{ slotProps.data.product_id }}</span>
                </template>
            </Column>
            
            <Column field="product_name" header="Product Name" class="text-left">
                <template #body="slotProps">
                    <span class="text-gray-900 text-sm font-medium">{{ slotProps.data.product_name }}</span>
                </template>
            </Column>
            
            <Column field="category" header="Category" class="text-left">
                <template #body="slotProps">
                    <span class="text-blue-600 text-sm font-medium">
                        {{ slotProps.data.category || 'Uncategorized' }}
                    </span>
                </template>
            </Column>
            
            <Column field="product_description" header="Description" class="text-left">
                <template #body="slotProps">
                    <span class="text-gray-500 text-sm">{{ 
                        slotProps.data.product_description 
                            ? (slotProps.data.product_description.length > 50 
                                ? slotProps.data.product_description.substring(0, 50) + '...' 
                                : slotProps.data.product_description)
                            : 'No description'
                    }}</span>
                </template>
            </Column>
            
            <Column field="product_price" header="Price" class="text-left">
                <template #body="slotProps">
                    <span class="text-gray-900 text-sm font-semibold">PhP{{ slotProps.data.product_price }}</span>
                </template>
            </Column>
            
            <Column field="product_stock" header="Stock" class="text-left">
                <template #body="slotProps">
                    <Tag 
                        :value="slotProps.data.product_stock > 0 ? 'In Stock' : 'Out of Stock'" 
                        :severity="slotProps.data.product_stock > 0 ? 'success' : 'danger'"
                        class="text-xs font-semibold"
                    />
                    <span class="ml-2 text-xs text-gray-500">({{ slotProps.data.product_stock }})</span>
                </template>
            </Column>
            
            <Column field="image_url" header="Image" class="text-left">
                <template #body="slotProps">
                    <div class="flex items-center">
                        <img 
                            v-if="slotProps.data.image_url || slotProps.data.img_url" 
                            :src="slotProps.data.image_url || slotProps.data.img_url" 
                            :alt="slotProps.data.product_name"
                            class="w-10 h-10 object-cover rounded border border-gray-200"
                            @error="handleImageError"
                        />
                        <div 
                            v-else 
                            class="w-10 h-10 bg-gray-100 rounded border border-gray-200 flex items-center justify-center"
                        >
                            <i class="pi pi-image text-gray-400 text-sm"></i>
                        </div>
                    </div>
                </template>
            </Column>
            
            <Column field="created_at" header="Created Date" class="text-left">
                <template #body="slotProps">
                    <span class="text-gray-500 text-sm">{{ formatDate(slotProps.data.created_at) }}</span>
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
        
        <!-- Custom pagination info -->
        <div class="flex items-center justify-between mt-4 px-2">
            <div class="text-sm text-gray-600">
                Showing {{ ((currentPage - 1) * 10) + 1 }} to {{ Math.min(currentPage * 10, totalProducts) }} of {{ totalProducts }} products
            </div>
            <div class="text-sm text-gray-500">
                Page {{ currentPage }} of {{ Math.ceil(totalProducts / 10) }}
            </div>
        </div>
    </div>

</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Tag from 'primevue/tag'
import Button from 'primevue/button'

const router = useRouter()
const currentPage = ref(1)
const totalProducts = ref(0)
const loading = ref(false)
const searchQuery = ref('')
let searchTimeout: number

const goToAddProduct = () => {
    router.push({ name: 'AdminAddProduct' })
}

// Handle search with debouncing
const handleSearch = () => {
    clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        currentPage.value = 1 // Reset to first page when searching
        fetchProducts(1, searchQuery.value)
    }, 500) // 500ms delay
}

// Handle pagination change
const onPageChange = (event: any) => {
    const newPage = event.page + 1 // PrimeVue pages are 0-indexed
    console.log('Page changed to:', newPage)
    currentPage.value = newPage
    fetchProducts(newPage, searchQuery.value)
}

// Fetch products from backend API
const fetchProducts = async (page: number = 1, search: string = '') => {
    loading.value = true
    console.log(`Fetching products - Page: ${page}, Search: "${search}"`)
    
    try {
        // Get auth token from localStorage
        const token = localStorage.getItem('token') // Changed from 'admin_token' to 'token'
        
        if (!token) {
            console.error('No auth token found')
            // Redirect to login if no token
            router.push('/adminlogin')
            return
        }
        
        // Build API URL with search parameter
        let apiUrl = `http://127.0.0.1:8000/api/admin/products?page=${page}&per_page=10`
        if (search.trim()) {
            apiUrl += `&search=${encodeURIComponent(search.trim())}`
        }
        
        console.log('API URL:', apiUrl)
        
        // Real API integration with Laravel backend
        const response = await fetch(apiUrl, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        
        console.log('API Response status:', response.status)
        
        if (!response.ok) {
            if (response.status === 401) {
                console.error('Unauthorized - redirecting to login')
                localStorage.removeItem('token')
                router.push('/adminlogin')
                return
            }
            throw new Error(`HTTP error! status: ${response.status}`)
        }
        
        const result = await response.json()
        console.log('Products API response:', result)
        
        // Handle Laravel pagination response
        if (result.data && Array.isArray(result.data)) {
            products.value = result.data
            totalProducts.value = result.total || 0
            currentPage.value = result.current_page || page
            console.log(`Loaded ${result.data.length} products, total: ${result.total}, current page: ${result.current_page}`)
        } else {
            console.warn('Unexpected API response structure:', result)
            throw new Error('Invalid API response structure')
        }
        
    } catch (error) {
        console.error('Error fetching products:', error)
        
        // Fallback to sample data for development
        console.log('Using fallback sample data')
        const itemsPerPage = 10
        const startIndex = (page - 1) * itemsPerPage
        const endIndex = startIndex + itemsPerPage
        
        // Apply search filter to sample data
        let filteredProducts = allProducts
        if (search.trim()) {
            const searchLower = search.toLowerCase()
            filteredProducts = allProducts.filter(product => 
                product.product_name.toLowerCase().includes(searchLower) ||
                product.product_description?.toLowerCase().includes(searchLower)
            )
        }
        
        totalProducts.value = filteredProducts.length
        products.value = filteredProducts.slice(startIndex, endIndex)
        console.log(`Fallback data - Page ${page}, showing ${products.value.length} of ${totalProducts.value} products`)
    } finally {
        loading.value = false
    }
}

// Load products when component mounts
onMounted(() => {
    fetchProducts(1)
})

const handleActionMenu = (product: any) => {
    console.log('Action menu clicked for product:', product)
    // Add your action menu logic here
}

// Format date for display
const formatDate = (dateString: string) => {
    if (!dateString) return 'N/A'
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric', 
        year: 'numeric' 
    })
}

// Handle image loading errors
const handleImageError = (event: Event) => {
    const target = event.target as HTMLImageElement
    if (target) {
        target.style.display = 'none'
    }
}

// Define product interface
interface Product {
    product_id: number
    product_name: string
    product_description: string | null
    product_price: number
    product_stock: number
    category?: string
    image_url: string | null
    img_url: string | null
    created_at: string
}

const products = ref<Product[]>([])

// Sample data for fallback/development
const allProducts: Product[] = [
    {
        product_id: 1,
        product_name: 'Herschel Leather Duffle Bag In Brown Color',
        product_description: 'Premium quality leather duffle bag perfect for travel and everyday use. Features multiple compartments and durable construction.',
        product_price: 125.30,
        product_stock: 15,
        category: 'Fashion',
        image_url: '/images/products/product05.png',
        img_url: null,
        created_at: '2024-12-25T10:30:00Z'
    },
    {
        product_id: 2,
        product_name: 'Wireless Gaming Headphones',
        product_description: 'High-quality wireless headphones with noise cancellation and premium sound quality for gaming.',
        product_price: 89.99,
        product_stock: 0,
        category: 'Electronics',
        image_url: '/images/products/headphone1.png',
        img_url: null,
        created_at: '2024-12-24T14:15:00Z'
    },
    {
        product_id: 3,
        product_name: 'Smartphone Case Premium',
        product_description: 'Protective case for smartphones with shock absorption and wireless charging compatibility.',
        product_price: 24.99,
        product_stock: 50,
        category: 'Accessories',
        image_url: '/images/products/phone1.png',
        img_url: null,
        created_at: '2024-12-23T09:45:00Z'
    },
    {
        product_id: 4,
        product_name: 'Bluetooth Speaker Portable',
        product_description: 'Compact portable speaker with excellent sound quality and long battery life.',
        product_price: 67.50,
        product_stock: 8,
        category: 'Electronics',
        image_url: '/images/products/accessory1.png',
        img_url: null,
        created_at: '2024-12-22T16:20:00Z'
    },
    {
        product_id: 5,
        product_name: 'Gaming Console Controller',
        product_description: null,
        product_price: 45.00,
        product_stock: 25,
        category: 'Gaming',
        image_url: '/images/products/console1.png',
        img_url: null,
        created_at: '2024-12-21T11:30:00Z'
    },
    {
        product_id: 6,
        product_name: 'Wireless Mouse Pro',
        product_description: 'Ergonomic wireless mouse with precision tracking and long battery life.',
        product_price: 32.99,
        product_stock: 40,
        category: 'Computer',
        image_url: '/images/products/accessory2.png',
        img_url: null,
        created_at: '2024-12-20T08:15:00Z'
    },
    {
        product_id: 7,
        product_name: 'USB-C Hub Adapter',
        product_description: 'Multi-port USB-C hub with HDMI, USB 3.0, and power delivery support.',
        product_price: 49.99,
        product_stock: 12,
        category: 'Computer',
        image_url: '/images/products/accessory3.png',
        img_url: null,
        created_at: '2024-12-19T13:45:00Z'
    },
    {
        product_id: 8,
        product_name: 'Mechanical Keyboard RGB',
        product_description: 'Premium mechanical keyboard with RGB backlighting and custom switches.',
        product_price: 129.99,
        product_stock: 18,
        category: 'Computer',
        image_url: '/images/products/accessory4.png',
        img_url: null,
        created_at: '2024-12-18T10:20:00Z'
    },
    {
        product_id: 9,
        product_name: 'Wireless Earbuds Pro',
        product_description: 'Premium wireless earbuds with active noise cancellation and premium sound.',
        product_price: 199.99,
        product_stock: 0,
        category: 'Electronics',
        image_url: '/images/products/headphone2.png',
        img_url: null,
        created_at: '2024-12-17T15:30:00Z'
    },
    {
        product_id: 10,
        product_name: 'Smartwatch Fitness Tracker',
        product_description: 'Advanced fitness tracker with heart rate monitoring and GPS.',
        product_price: 89.99,
        product_stock: 35,
        category: 'Health',
        image_url: '/images/products/accessory5.png',
        img_url: null,
        created_at: '2024-12-16T09:10:00Z'
    },
    {
        product_id: 11,
        product_name: 'Portable Power Bank',
        product_description: 'High-capacity power bank with fast charging and multiple ports.',
        product_price: 39.99,
        product_stock: 60,
        category: 'Electronics',
        image_url: '/images/products/accessory6.png',
        img_url: null,
        created_at: '2024-12-15T14:25:00Z'
    },
    {
        product_id: 12,
        product_name: 'Webcam HD 1080p',
        product_description: 'Professional HD webcam with auto-focus and noise reduction.',
        product_price: 79.99,
        product_stock: 22,
        category: 'Computer',
        image_url: '/images/products/accessory7.png',
        img_url: null,
        created_at: '2024-12-14T11:40:00Z'
    }
]
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

/* Blue category styling */
.text-blue-600 {
    color: #2563eb;
}
</style>