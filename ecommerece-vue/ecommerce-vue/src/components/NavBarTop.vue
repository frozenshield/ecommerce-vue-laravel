<template>
    <div class="bg-yellow-500 text-white px-4 md:px-10 py-4 shadow-md border-b border-gray-200">
        <div class="flex justify-between items-center max-w-7xl mx-auto">
            
            <div class="text-3xl font-bold shrink-0 mr-4">
                <span class="text-black">russ</span><span class="text-white">fury</span>
            </div>

            <div class="hidden md:flex items-center bg-white rounded overflow-hidden w-full max-w-xl mx-4">
                <Dropdown
                    :options="categories"
                    v-model="selectedCategory"
                    optionLabel="label"
                    class="w-32 border-r"
                />
                <InputText 
                     v-model="searchTerm"
                     placeholder="I'm shopping for.." 
                     class="flex-1 px-3 py-2 border-none focus:ring-0"  />
                <Button 
                     icon="pi pi-search" 
                     class="p-button-warning px-4 hover:bg-yellow-500" 
                     @click="onSearch" />
           </div>

           <div class="flex space-x-6 items-center">
                <div class="relative">
                    <i class="pi pi-heart mx-2 hover:text-yellow-200"></i>
                    <span v-if="wishlistCount > 0" class="absolute -top-2 -right-2 bg-black text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ wishlistCount }}</span> 
                </div>
                <div class="relative">
                    <i class="pi pi-shopping-cart mx-2 hover:text-yellow-200"></i>
                    <span v-if="cartCount > 0" class="absolute -top-2 -right-2 bg-black text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ cartCount }}</span> 
                </div>

                <div class="relative">
                    <i class="pi pi-user mx-2" @click="toggleUserPanel" ref="userIcon"></i>
                    <OverlayPanel ref="userPanel">
                        <div class="p-4 w-40 flex flex-col space-y-2">
                             <template v-if="auth.isAuthenticated">
                                <router-link to="/profile" class="flex items-center text-black hover:text-orange-500 transition-colors">
                                     <i class="pi pi-user mr-2"></i> Profile
                                </router-link>
                                <button @click="handleLogout" class="flex items-center text-black hover:text-orange-500 transition-colors">
                                     <i class="pi pi-sign-out mr-2"></i> Logout
                                </button>
                            </template>
                            <template v-else>
                                <router-link to="/login" class="flex items-center text-black hover:text-orange-500 transition-colors">
                                    <i class="pi pi-sign-in mr-2"></i> Login
                                </router-link>
                                <router-link to="/register" class="flex items-center text-black hover:text-orange-500 transition-colors">
                                    <i class="pi pi-user-plus mr-2"></i> Register
                                </router-link>
                            </template>
                        </div>
                    </OverlayPanel>
                </div>    
           </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import OverlayPanel from 'primevue/overlaypanel';

const auth = useAuthStore();
const router = useRouter();

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

const handleLogout = () => {
    auth.logout();
    router.push('/');
}

</script>

<style scoped>
.pi {
    font-size: 1.75rem;
    cursor: pointer;
    color: black;
}
</style>