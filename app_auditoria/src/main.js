import './assets/main.css'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import VueApexCharts from 'vue3-apexcharts'
axios.defaults.baseURL = import.meta.env.VITE_MY_API_URL_BASE
import { useAuthStore } from './stores/auth'
import ErrorsComponent from './components/Error.vue'
import DataTableComponent from './components/DataTable.vue'
const app = createApp(App)
app.use(createPinia())
const authStore = useAuthStore()
authStore.initializeAuth()
app.use(router)
app.use(VueApexCharts)

app.component('Errors', ErrorsComponent)
app.component('DataTable', DataTableComponent)
app.mount('#app')
