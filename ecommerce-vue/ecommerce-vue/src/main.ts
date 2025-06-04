import './assets/main.css'

import { createApp } from 'vue'
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import App from './App.vue'
import router from './router'


//PrimeVue Components Import
import Button from "primevue/button"

const app = createApp(App)
app.use(PrimeVue,{
    theme: {
        preset: Aura
    }
});

// app vue component
app.component('Button', Button);


app.use(router)
app.use(PrimeVue)

app.mount('#app')
