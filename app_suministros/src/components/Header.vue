<template>
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo Section -->
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 flex items-center">
                        <!-- Company Logo -->
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-blue-700 rounded-lg flex items-center justify-center shadow-lg cursor-pointer"
                            @click="gotoHome()">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h1 class="text-xl font-bold text-gray-900 cursor-pointer" @click="gotoHome()">DAI</h1>
                        </div>
                    </div>
                </div>



                <!-- User Section -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <!-- <button class="relative p-2 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-5 5v-5zM10.5 3.5a6 6 0 0 1 6 6v2l1.5 3h-15l1.5-3v-2a6 6 0 0 1 6-6z" />
                        </svg>
                        <!-- Notification Badge --
                        <span
                            class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                            3
                        </span>
                    </button> -->

                    <!-- User Menu -->
                    <div class="relative" ref="userMenuRef">
                        <button @click="toggleUserMenu"
                            class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <!-- User Avatar -->
                            <div class="relative">
                                <img :src="user.avatar" :alt="user.name"
                                    class="w-8 h-8 rounded-full object-cover ring-2 ring-gray-200" />
                                <!-- Online Status -->
                                <div
                                    class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-400 border-2 border-white rounded-full">
                                </div>
                            </div>

                            <!-- User Info (Hidden on mobile) -->
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium text-gray-900">{{ user.name }}</p>
                                <p class="text-xs text-gray-500">{{ user.role }}</p>
                            </div>

                            <!-- Dropdown Arrow -->
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                                :class="{ 'rotate-180': isUserMenuOpen }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <transition enter-active-class="transition ease-out duration-200"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-150"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95">
                            <div v-if="isUserMenuOpen"
                                class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                <!-- User Info Header -->
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <img :src="user.avatar" :alt="user.name"
                                            class="w-10 h-10 rounded-full object-cover" />
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ user.name }}</p>
                                            <p class="text-xs text-gray-500">{{ user.email }}</p>
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
                                                {{ user.role }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-1">
                                    <router-link v-for="item in userMenuItems" :key="item.name" :to="item.route"
                                        @click="handleMenuAction(item.action)"
                                        class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200"
                                        :class="{ 'text-red-600 hover:text-red-700 hover:bg-red-50': item.danger }">
                                        <component :is="item.icon"
                                            class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500"
                                            :class="{ 'text-red-400 group-hover:text-red-500': item.danger }" />
                                        {{ item.name }}
                                        <span v-if="item.badge"
                                            class="ml-auto bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full">
                                            {{ item.badge }}
                                        </span>
                                    </router-link>
                                </div>

                                <!-- Footer -->
                                <div class="border-t border-gray-100 px-4 py-2">
                                    <p class="text-xs text-gray-500">
                                        Última conexión: {{ lastLogin }}
                                    </p>
                                </div>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation (Optional) -->
        <div v-if="isMobileMenuOpen" class="md:hidden border-t border-gray-200 bg-white">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <router-link v-for="item in navigationItems" :key="item.name" :path="item.route"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50"
                    :class="{ 'text-blue-600 bg-blue-50': item.active }">
                    {{ item.name }}
                </router-link>
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted, computed } from 'vue'
import { useRouter } from 'vue-router';
import {
    UserIcon,
    CogIcon,
    KeyIcon,
    BellIcon,
    LogOutIcon,
    ShieldIcon,
    HelpCircleIcon
} from 'lucide-vue-next'
import { useAuthStore } from '@/stores/auth';
// Component state
const store = useAuthStore();
const router = useRouter();
const isUserMenuOpen = ref(false)
const isMobileMenuOpen = ref(false)
const userMenuRef = ref(null)
let userdecode = null;
try {
    userdecode = JSON.parse(localStorage.getItem('user'));
} catch (e) {
    console.warn('Error parsing user from localStorage:', e);
}
const user = reactive({
    name: userdecode?.smallname || 'Desconocido',
    email: userdecode?.email || 'Desconocido',
    role: store.rol,
    avatar: import.meta.env.VITE_IMAGE_PUBLIC + (userdecode?.image || '@/assets/muni.png')
});

// Navigation items
const navigationItems = reactive([
    { name: 'Dashboard', active: true },
    { name: 'Aplicaciones', active: false },
    { name: 'Reportes', active: false },
    { name: 'Configuración', active: false }
])

// User menu items
const userMenuItems = reactive([
    {
        name: 'Mi Perfil',
        icon: UserIcon,
        danger: false,
        route: '/profile'
    },

    {
        name: 'Cambiar Contraseña',
        icon: KeyIcon,
        danger: false,
        route: '/change'
    },
    {
        name: 'Ayuda',
        icon: HelpCircleIcon,
        danger: false,
        route: '/faq'
    },
    {
        name: 'Cerrar Sesión',
        icon: LogOutIcon,
        action: 'logout',
        route: '/login',
        danger: true
    }
])

// Computed
const lastLogin = ref('Hace 0 horas')

// Methods
const toggleUserMenu = () => {
    isUserMenuOpen.value = !isUserMenuOpen.value
}

const handleMenuAction = (action) => {
    isUserMenuOpen.value = false

    switch (action) {
        case 'profile':
            console.log('Abriendo perfil de usuario')
            alert('Navegando a Mi Perfil')
            break
        case 'settings':
            console.log('Abriendo configuración')
            alert('Navegando a Configuración')
            break
        case 'changePassword':
            changePassword()
            break
        case 'security':
            console.log('Abriendo configuración de seguridad')
            alert('Navegando a Seguridad')
            break
        case 'help':
            console.log('Abriendo ayuda')
            alert('Navegando a Centro de Ayuda')
            break
        case 'logout':
            console.log('Cerrando sesión')
            store.logout();
            break
        default:
            console.log(`Acción no reconocida: ${action}`)
    }
}


const changePassword = () => {
    console.log('Cerrando sesión')
    router.push({ name: 'change' });
}
const gotoHome = () => {
    const isDev = process.env.NODE_ENV === 'development';
    if (isDev) {
        router.push({ name: 'home' });
    } else {
        window.location.href = '/dai/home';
    }
}

// Click outside to close menu
const handleClickOutside = (event) => {
    if (userMenuRef.value && !userMenuRef.value.contains(event.target)) {
        isUserMenuOpen.value = false
    }
}

// Lifecycle
onMounted(() => {
    document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
});
</script>

<style scoped>
/* Custom scrollbar for dropdown if needed */
.dropdown-menu::-webkit-scrollbar {
    width: 4px;
}

.dropdown-menu::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.dropdown-menu::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

.dropdown-menu::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>