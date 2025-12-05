import './assets/main.css'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import VueApexCharts from 'vue3-apexcharts'
import 'vue-select/dist/vue-select.css';
axios.defaults.baseURL = import.meta.env.VITE_MY_API_URL_BASE
import { useAuthStore } from './stores/auth'
import ErrorsComponent from './components/Error.vue'
import DataTableComponent from './components/DataTable.vue'
import Tooltip from './components/Tooltip.vue'
import Select from './components/select.vue'
import Skeleton from './components/Skeleton.vue'
import Breadcrumbs from './components/Breadcrumbs.vue'
import Modal from './components/Modal.vue'
import Input from './components/Input.vue'
import vSelect from 'vue-select'
const app = createApp(App)
app.use(createPinia())
const authStore = useAuthStore()
authStore.initializeAuth()
app.use(router)
app.use(VueApexCharts)

app.component('Errors', ErrorsComponent)
app.component('DataTable', DataTableComponent)
.component('Breadcrumbs', Breadcrumbs)
app.component('Tooltip', Tooltip)
app.component('Select', Select)
app.component('Skeleton', Skeleton)
app.component('Modal', Modal)
app.component('Input', Input)
app.component('v-select', vSelect)
app.mount('#app')
