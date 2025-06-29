<template>
    <div class="p-6 bg-gray-50 min-h-screen">
        <!-- Breadcrumb -->
        <Breadcrumb :model="breadcrumbItems" class="mb-6">
            <template #item="{ item }">
                <router-link v-if="item.route" :to="item.route" class="text-blue-600 hover:text-blue-800">
                    {{ item.label }}
                </router-link>
                <span v-else class="text-gray-500">{{ item.label }}</span>
            </template>
        </Breadcrumb>

        <!-- Settings Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Common Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Common</h3>
                <div class="space-y-4">
                    <div @click="navigateTo('general')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-cog text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">General</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update your general settings and activate</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('email')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-envelope text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Email</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update your email settings and email</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('email-templates')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-file text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Email templates</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Email templates using HTML & system variables</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('email-rules')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-envelope text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Email rules</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Configure email rules for notification</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('media')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-flag text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Media</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update your media settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('permalink')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-palette text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Permalink</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update your permalink settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('languages')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-globe text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Languages</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update your website languages</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('admin-appearance')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-eye text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Admin appearance</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update admin style, rtl button, and css...</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('api-settings')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-cog text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">API Settings</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update your API settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('cache')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-code text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Cache</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Configure caching for optimized speed</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('databases')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-database text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Databases</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Settings for installation</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('website-tracking')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chart-line text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Website Tracking</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Configure website tracking</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('optimize')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-server text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Optimize</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Minify HTML output, inline CSS, remove comments...</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('storage')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-folder text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Storage</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Manage storage configuration</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Localization Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Localization</h3>
                <div class="space-y-4">
                    <div @click="navigateTo('locales')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-map-marker text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Locales</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View, download and import locales</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('theme-translations')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-globe text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Theme Translations</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Manage the theme translations</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('other-translations')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-language text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Other Translations</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Manage the other translations (admin, plugins, packages...)</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ecommerce Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ecommerce</h3>
                <div class="space-y-4">
                    <div @click="navigateTo('ecommerce-general')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-cog text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">General</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update your general settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('currencies')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-money-bill text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Currencies</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update currency settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('store-locations')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-map-marker text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Store locations</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update the lists of your choice</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('products-settings')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-box text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Products</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update your products settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('product-search')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-search text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Product Search</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update product search settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('digital-products')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-tablet text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Digital Products</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update digital products settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('product-reviews')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-star text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Product Reviews</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update product reviews settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('checkout')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-check text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Checkout</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update checkout settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('invoice-template')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-file text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Invoice Template</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Configure invoice template</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('return')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-undo text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Return</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update return settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('shipping-label')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-file text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Shipping Label Template</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Configure shipping label template</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('customers-settings')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-users text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Customers</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Configure customer settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('shipping')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-truck text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Shipping</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update shipping settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('marketplace')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-shopping-cart text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Marketplace</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update marketplace settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('webhooks')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-server text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Webhooks</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Configure webhook settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('tracking')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-eye text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Tracking</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update tracking settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('standard-format')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-file text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Standard & Format</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update standard & format settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('flash-sale')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-bolt text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Flash Sale</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update flash sale settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Others Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 md:col-span-2 lg:col-span-3">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Others</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div @click="navigateTo('social-login')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-sign-in text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Social Login</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update your social login settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('blog')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-file text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Blog</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update blog settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('newsletter')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-envelope text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Newsletter</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update newsletter settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('contact')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-shield text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Contact</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Settings for contact plugin</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('captcha')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-key text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Captcha</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update reCAPTCHA and math captcha...</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('google-analytics')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chart-bar text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Google Analytics</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Config credentials for Google Analytics</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('faqs')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-question text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">FAQs</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">View and update FAQs settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('simple-sliders')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-share-alt text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Simple Sliders</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Shortcode for simple sliders</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                    
                    <div @click="navigateTo('ads-settings')" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors duration-200 border border-transparent hover:border-gray-200">
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-cog text-blue-600 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Ads Settings</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">Manage ads settings</p>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            <i class="pi pi-chevron-right text-gray-400 text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 pt-4 border-t border-gray-200 text-center text-sm text-gray-500">
            Copyright 2025 © Botble Technologies. Version 1.40.7 | Page loaded in 0.98 seconds
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Breadcrumb from 'primevue/breadcrumb'

// Breadcrumb items
const breadcrumbItems = ref([
    { label: 'ADMIN BOARD', route: '/admin' },
    { label: 'SETTINGS' }
])

// Navigation handler
const navigateTo = (setting: string) => {
    console.log(`Navigating to ${setting} settings`)
    // You can implement navigation logic here
    // router.push(`/admin/settings/${setting}`)
}
</script>