<template>
    <div class="bg-yellow-400 text-white px-4 md:px-10 py-4 shadow-md border-b border-gray-200">
        <div class="flex justify-between items-center">
            
            <div class="text-3xl font-bold">
                <span class="text-black">russ</span><span class="text-white">fury</span>
            </div>

            <div class="hidden md:flex items-center bg-white rounded overflow-hidden w-1/2">
                <Dropdown
                    :options="categories"
                    v-model="selectedCategory"
                    optionLabel="label"
                    class="w-32 border-r"
                />
                <InputText v-model="searchTerm" placeholder="I'm shopping for.." class="flex-1 px-3 py-2" />
                <Button icon="pi pi-search" class="p-button-warning px-4" @click="onSearch" />
           </div>

           <div class="flex space-x-4 items-center">
                <div class="relative">
                    <i class="pi pi-heart"></i>
                    <span v-if="wishlistCount > 0" class="absolute -top-2 -right-2 bg-black text-white text-xs rounded-full px-1">{{ wishlistCount }}</span> 
                </div>
                <div class="relative">
                    <i class="pi pi-shopping-cart"></i>
                    <span v-if="cartCount > 0" class="absolute -top-2 -right-2 bg-black text-white text-xs rounded-full px-1">{{ cartCount }}</span> 
                </div>
                <div class="relative">
                    <i class="pi pi-user" @click="toggleUserPanel" ref="userIcon"></i>
                    <OverlayPanel ref="userPanel">
                        <div class="p-4 w-40 flex flex-col space-y-2">
                            <router-link to="/login" class="flex items-center text-black hover:text-orange-500 transition-colors">
                                <i class="pi pi-sign-in mr-2"></i> Login
                            </router-link>
                            <router-link to="/register" class="flex items-center text-black hover:text-orange-500 transition-colors">
                                <i class="pi pi-user-plus mr-2"></i> Register
                            </router-link>
                        </div>
                    </OverlayPanel>
                </div>    
           </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import OverlayPanel from 'primevue/overlaypanel';

const userIcon = ref();
const userPanel = ref();
const wishlistCount = ref(1);
const cartCount = ref(1);
const searchTerm = ref('');
const selectedCategory = ref(null);
const categories = ref([
    { label: 'All', value: 'all'},
    { label: 'Electronics', value: 'electronics'},
    { label: 'Fashion', value: 'fashion'},
    { label: 'Home', value:'home'},
]);

const onSearch = () => {
    console.log('Search for:', searchTerm.value, 'in category:', selectedCategory.value);
};

function toggleUserPanel(event) {
    userPanel.value.toggle(event);
}

</script>

<style scoped>
.pi {
    font-size: 1.25rem;
    cursor: pointer;
}
</style>