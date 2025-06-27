<template>
    <div class="flex justify-end mt-10">
        <button class="item-center gap-2 bg-lime-600 text-white font-bold text-xs px-6 py-2 rounded-sm hover:bg-lime-700 transition">
            <span class="text-lg">+</span>
            NEW PRODUCT
        </button>
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

</template>