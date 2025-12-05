<template>
    <Header />
    <div class="bg-gradient-to-br from-blue-50 to-indigo-100" v-if="!store.loadingRecord">
        <div class="container mx-auto py-10 px-4 md:px-6 ">
            <div class="max-w-2xl mx-auto">
                <h1 class="text-3xl font-bold mb-6 text-center">Perfil de Usuario</h1>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <form @submit.prevent="submitForm" class="space-y-6">
                        <!-- Carga de imagen -->
                        <div class="flex justify-center mb-6">
                            <div class="flex flex-col items-center gap-4">
                                <div class="relative">
                                    <div
                                        class="h-32 w-32 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                                        <img v-if="store.record?.profile_picture_url" :src="image" alt="Foto de perfil"
                                            class="h-full w-full object-cover" />
                                        <span v-else class="text-gray-400 text-2xl">?</span>
                                    </div>
                                </div>
                                <p v-if="errors.avatar" class="text-red-500 text-sm mt-1">{{ errors.avatar }}</p>
                            </div>
                        </div>

                        <!-- Nombre y apellido -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="firstName"
                                    class="block text-sm font-medium text-gray-700 mb-1">Nombres</label>
                                <input id="firstName" v-model="store.record.name" type="text" placeholder="Juan"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': errors.firstName }" readonly disabled />
                                <p v-if="errors.firstName" class="text-red-500 text-sm mt-1">{{ errors.firstName }}</p>
                            </div>

                            <div>
                                <label for="lastName"
                                    class="block text-sm font-medium text-gray-700 mb-1">Apellidos</label>
                                <input id="lastName" v-model="store.record.last_name" type="text" placeholder="Pérez"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': errors.lastName }" disabled />
                                <p v-if="errors.lastName" class="text-red-500 text-sm mt-1">{{ errors.lastName }}</p>
                            </div>
                        </div>

                        <!-- Correo electrónico muni-->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo
                                Electrónico Municipal</label>
                            <input id="email" v-model="store.record.email" type="email"
                                placeholder="juan.perez@ejemplo.com"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': errors.email }" disabled />
                            <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
                        </div>
                        <!-- CUMPLEAÑOS -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Fecha de
                                nacimiento</label>
                            <input id="phone" v-model="store.record.date_of_birth" type="date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': errors.birthday }" />
                            <p v-if="errors.birthday" class="text-red-500 text-sm mt-1">{{ errors.birthday }}</p>
                        </div>
                        <!-- Teléfono y genero-->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                                <input id="phone" v-model="store.record.phone_number" type="text" placeholder="12345678"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': errors.phone }" />
                                <p v-if="errors.phone" class="text-red-500 text-sm mt-1">{{ errors.phone }}</p>
                            </div>

                            <div>
                                <label for="lastName"
                                    class="block text-sm font-medium text-gray-700 mb-1">Genero</label>
                                <select
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    v-model="store.record.gender" :class="{ 'border-red-500': errors.gender }">
                                    <option disabled selected>--Seleccione--</option>
                                    <option value="M">MASCULINO</option>
                                    <option value="F">FEMENIMO</option>
                                </select>
                                <p v-if="errors.gender" class="text-red-500 text-sm mt-1">{{ errors.gender }}</p>
                            </div>
                        </div>
                        <!-- Correo electrónico -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo
                                Electrónico Personal</label>
                            <input id="email" v-model="store.record.email_personal" type="email"
                                placeholder="juan.perez@ejemplo.com"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': errors.email_personal }" />
                            <p v-if="errors.email_personal" class="text-red-500 text-sm mt-1">{{ errors.email_personal
                            }}
                            </p>
                        </div>
                        <!--DPI y NIT -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="dpi" class="block text-sm font-medium text-gray-700 mb-1">DPI</label>
                                <input id="dpi" v-model="store.record.dpi" type="text" placeholder="10100101"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': errors.dpi }" />
                                <p v-if="errors.dpi" class="text-red-500 text-sm mt-1">{{ errors.dpi }}</p>
                            </div>

                            <div>
                                <label for="nit" class="block text-sm font-medium text-gray-700 mb-1">NIT</label>
                                <input id="nit" v-model="store.record.nit" type="text" placeholder="1111-1"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': errors.nit }" />
                                <p v-if="errors.nit" class="text-red-500 text-sm mt-1">{{ errors.nit }}</p>
                            </div>
                        </div>
                        <!-- Dirección -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                            <input id="address" v-model="store.record.address" type="text"
                                placeholder="Calle Principal #123"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': errors.address }" />
                            <p v-if="errors.address" class="text-red-500 text-sm mt-1">{{ errors.address }}</p>
                        </div>
                        <!-- Municipio y departamento -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="firstName"
                                    class="block text-sm font-medium text-gray-700 mb-1">Municipio</label>
                                <input id="firstName" v-model="store.record.city" type="text" placeholder="Guatemala"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': errors.city }" />
                                <p v-if="errors.city" class="text-red-500 text-sm mt-1">{{ errors.city }}</p>
                            </div>

                            <div>
                                <label for="lastName"
                                    class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                                <input id="lastName" v-model="store.record.department" type="text"
                                    placeholder="Guatemala"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    :class="{ 'border-red-500': errors.department }" />
                                <p v-if="errors.department" class="text-red-500 text-sm mt-1">{{ errors.department }}
                                </p>
                            </div>
                        </div>
                        <!-- Pais -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Pais</label>
                            <input id="address" v-model="store.record.country" type="text" placeholder="Guatemala"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': errors.country }" />
                            <p v-if="errors.country" class="text-red-500 text-sm mt-1">{{ errors.country }}</p>
                        </div>


                        <!-- Botón de envío -->
                        <button type="submit"
                            class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                            :disabled="isLoading">
                            {{ isLoading ? "Guardando..." : "Guardar Perfil" }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Toast de notificación -->
            <div v-if="showToast" class="fixed bottom-4 left-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg">
                {{ toastMessage }}
            </div>
            <Errors :errors="store.validationErrors" />
        </div>
    </div>
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6" v-else>
        <div class="w-full max-w-md space-y-8">
            <!-- Logo/Avatar central con animación -->
            <div class="text-center">
                <div class="relative mx-auto w-24 h-24 mb-6">
                    <!-- Círculo exterior rotativo -->
                    <div class="absolute inset-0 rounded-full border-4 border-slate-200"></div>
                    <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-blue-500 animate-spin"
                        style="animation-duration: 2s"></div>

                    <!-- Círculo interior con progreso -->
                    <div class="absolute inset-2 rounded-full bg-white shadow-lg flex items-center justify-center">
                        <User class="w-8 h-8 text-slate-600" />
                    </div>

                    <!-- Indicador de progreso -->
                    <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                        <div class="bg-white rounded-full px-3 py-1 shadow-lg border">
                            <span class="text-sm font-semibold text-slate-700">{{ Math.round(progress) }}%</span>
                        </div>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-slate-800 mb-2">Cargando Perfil</h2>
                <p class="text-slate-600">Preparando tu información personal</p>
            </div>

            <!-- Barra de progreso principal -->
            <div class="space-y-2">
                <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-full transition-all duration-300 ease-out relative"
                        :style="{ width: `${progress}%` }">
                        <!-- Efecto de brillo -->
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-pulse">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Puntos de carga animados -->
            <div class="flex justify-center space-x-2">
                <div v-for="dot in 5" :key="dot" class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" :style="{
                    animationDelay: `${(dot - 1) * 0.15}s`,
                    animationDuration: '1.2s',
                }"></div>
            </div>

            <!-- Mensaje de estado -->
            <div class="text-center">
                <p class="text-sm text-slate-500 animate-pulse">Cargando datos del perfil...</p>
            </div>
        </div>
    </div>
    <Top />
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useUserStore } from '@/stores/user'
import { CameraIcon, XIcon, User } from 'lucide-vue-next'
import Header from '@/components/Header.vue'
import Top from '@/components/Top.vue'

// Estado del formulario
const store = useUserStore();


// Estado de errores
const errors = reactive({
    avatar: '',
    firstName: '',
    lastName: '',
    email: '',
    email_personal: '',
    phone: '',
    gender: '',
    address: '',
    birthday: '',
    dpi: '',
    nit: '',
    city: '',
    department: '',
    country: '',
})
const image = computed(() => {
    return import.meta.env.VITE_IMAGE_PUBLIC + store.record?.profile_picture_url
})
const progress = ref(0)
const isLoading = ref(false)
const showToast = ref(false)
const toastMessage = ref('')
defineExpose({
    progress
})
// Validación del formulario
const validateForm = () => {
    let isValid = true
    // Resetear errores
    Object.keys(errors).forEach(key => {
        errors[key] = ''
    })

    // Validar nombre
    if (!store.record.name || store.record.name.length < 2) {
        errors.firstName = 'El nombre debe tener al menos 2 caracteres.'
        isValid = false
    }

    // Validar apellido
    if (!store.record.last_name || store.record.last_name.length < 2) {
        errors.lastName = 'El apellido debe tener al menos 2 caracteres.'
        isValid = false
    }
    // Validar fecha
    if (!store.record.date_of_birth) {
        errors.birthday = 'La fecha de nacimiento es obligatoria.';
        isValid = false;
    } else {
        const date = new Date(store.record.date_of_birth);
        // Verificar si la fecha es válida (no es "Invalid Date")
        // y si la fecha parseada corresponde a la original para evitar fechas como "2023-02-30"
        if (isNaN(date.getTime()) || date.toISOString().slice(0, 10) !== store.record.date_of_birth) {
            errors.birthday = 'La fecha debe ser válida y en formato AAAA-MM-DD.';
            isValid = false;
        }
    }

    // Validar email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!store.record.email || !emailRegex.test(store.record.email)) {
        errors.email = 'Por favor ingresa un correo electrónico válido.'
        isValid = false
    }
    const emailpersonalRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!store.record.email_personal || !emailpersonalRegex.test(store.record.email_personal)) {
        errors.email_personal = 'Por favor ingresa un correo electrónico válido.'
        isValid = false
    }

    // Validar teléfono
    if (!store.record.phone_number || store.record.phone_number.length < 8) {
        errors.phone = 'El número de teléfono debe tener al menos 8 dígitos.'
        isValid = false
    }
    //genero
    if (!store.record.gender || store.record.gender.length < 1) {
        errors.gender = 'El Genero es requerido.'
        isValid = false
    }
    //dpi
    if (!store.record.dpi || store.record.dpi.length !== 13) {
        errors.dpi = 'El número de CUI debe tener 13 dígitos.'
        isValid = false
    }
    //nit
    if (!store.record.nit || store.record.nit.length < 3) {
        errors.nit = 'El número de NIT debe tener al menos 3 dígitos.'
        isValid = false
    }
    // Validar dirección
    if (!store.record.address || store.record.address.length < 5) {
        errors.address = 'La dirección debe tener al menos 5 caracteres.'
        isValid = false
    }
    // Validar municipio
    if (!store.record.city || store.record.city.length < 4) {
        errors.city = 'El municipio debe tener al menos 4 caracteres.'
        isValid = false
    }

    // Validar departamento
    if (!store.record.department || store.record.department.length < 4) {
        errors.department = 'El departamento debe tener al menos 4 caracteres.'
        isValid = false
    }
    // Validar pais
    if (!store.record.country || store.record.country.length < 4) {
        errors.country = 'El pais debe tener al menos 4 caracteres.'
        isValid = false
    }

    return isValid
}
// Envío del formulario
const submitForm = async () => { // Añadimos recordId como parámetro
    if (!validateForm()) {
        return;
    }
    isLoading.value = true; // Activa el indicador de carga
    try {
        await store.update();
    } catch (error) {
        console.error('Error al enviar el formulario:', error);
    } finally {
        isLoading.value = false; // Desactiva el indicador de carga al finalizar
    }
};
onMounted(() => {
    store.show()
});
</script>