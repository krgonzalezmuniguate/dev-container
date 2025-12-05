<template>
    <div>
        <Modal v-model="store.optionUpdate" :title="modalConfig.title" :subtitle="modalConfig.subtitle"
            :size="modalConfig.size" :show-header="modalConfig.showHeader" :show-footer="modalConfig.showFooter"
            :show-close-button="modalConfig.showCloseButton" :show-cancel-button="modalConfig.showCancelButton"
            :show-confirm-button="modalConfig.showConfirmButton" :cancel-button-text="modalConfig.cancelButtonText"
            :confirm-button-text="modalConfig.confirmButtonText" :confirm-button-class="modalConfig.confirmButtonClass"
            :loading-text="modalConfig.loadingText" :close-on-overlay="modalConfig.closeOnOverlay"
            :close-on-escape="modalConfig.closeOnEscape" :loading="modalConfig.loading"
            :body-classes="modalConfig.bodyClasses" :footer-classes="modalConfig.footerClasses">
            <div class="grid gap-2">
                <!-- Nombre y apellido -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">Nombres</label>
                        <input id="firstName" v-model="store.record.name" type="text" placeholder="Juan"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'border-red-500': errors.firstName }" />
                        <p v-if="errors.firstName" class="text-red-500 text-sm mt-1">{{ errors.firstName }}</p>
                    </div>

                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Apellidos</label>
                        <input id="lastName" v-model="store.record.last_name" type="text" placeholder="Pérez"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'border-red-500': errors.lastName }" />
                        <p v-if="errors.lastName" class="text-red-500 text-sm mt-1">{{ errors.lastName }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Etnia</label>
                        <select type="email" placeholder="Etnia"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'border-red-500': errors.email }" v-model="store.record.ethnic_group">
                            <option disabled selected>Selecciona una etnia</option>
                            <option v-for="etnia in etnias" :key="etnia" :value="etnia">
                                {{ etnia }}
                            </option>
                        </select>
                        <p v-if="errors.lastName" class="text-red-500 text-sm mt-1">{{ errors.lastName }}</p>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo
                            Electrónico Municipal</label>
                        <input id="email" v-model="store.record.email" type="email" placeholder="juan.perez@ejemplo.com"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'border-red-500': errors.email }" />
                        <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Código de empleado o
                            DPI</label>
                        <input id="email" v-model="store.record.code_job" type="text" placeholder="2025"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'border-red-500': errors.email }" />
                        <p v-if="errors.lastName" class="text-red-500 text-sm mt-1">{{ errors.lastName }}</p>
                    </div>
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Jefe o encargado</label>
                        <select type="email" placeholder="Jefe"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'border-red-500': errors.email }" v-model="store.record.manager_id">
                            <option disabled selected>Selecciona un jefe</option>
                            <option v-for="chief in chiefs" :key="chief.id" :value="chief.id">
                                {{ chief.name }}
                            </option>
                        </select>
                        <p v-if="errors.lastName" class="text-red-500 text-sm mt-1">{{ errors.lastName }}</p>
                    </div>
                </div>

                <div class="flex justify-center mb-6">
                    <div class="flex flex-col items-center gap-4">
                        <div class="relative">
                            <div
                                class="h-32 w-32 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                                <img v-if="store.record?.profile_picture_url" :src="profilePictureSrc"
                                    alt="Foto de perfil" class="h-full w-full object-cover" />
                                <span v-else class="text-gray-400 text-2xl">?</span>
                            </div>
                            <button v-if="store.record.profile_picture_url" type="button" @click="removeImage"
                                class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-600 focus:outline-none">
                                <XIcon class="h-4 w-4" />
                            </button>
                        </div>
                        <div class="flex items-center gap-2">
                            <label
                                class="flex items-center gap-2 px-3 py-1.5 text-sm border border-gray-300 rounded-md hover:bg-gray-50 cursor-pointer">
                                <CameraIcon class="h-4 w-4" />
                                <span>Subir foto</span>
                                <input type="file" accept="image/*" class="sr-only" @change="handleImageUpload" />
                            </label>
                        </div>
                        <!-- v-if="errors.avatar" -->
                        <p class="text-red-500 text-sm mt-1"></p>
                    </div>

                </div>
                <div>
                    <Errors :errors="store.validationErrors" />
                </div>
            </div>
            <template #footer>
                <button @click="store.optionUpdate = false"
                    class="bg-red-600 hover:bg-red-700 focus:ring-red-500 p-2 rounded-xl text-white">Cancelar</button>
                <button @click="store.updateAdmin()" type="button"
                    class="bg-green-600 hover:bg-green-700 focus:ring-green-500 p-2 rounded-xl text-white disabled:opacity-100 disabled:cursor-not-allowed"
                    :disabled="!store.optionUpdate">Guardar</button>


            </template>
        </Modal>
    </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { useUserStore } from '@/stores/user'
import Modal from '@/components/Modal.vue'
import { CameraIcon, XIcon } from 'lucide-vue-next'
// Estado reactivo
const store = useUserStore();
const modalOpen = ref(false)
const useCustomSlots = ref(false)
const events = ref([])
const url = import.meta.env.VITE_URL_STORAGE;
// Configuración del modal
const modalConfig = reactive({
    title: 'Editar usuario',
    subtitle: '',
    size: 'lg',
    showHeader: true,
    showFooter: true,
    showCloseButton: true,
    showCancelButton: false,
    showConfirmButton: false,
    cancelButtonText: 'Cancelar',
    confirmButtonText: 'Guardar',
    confirmButtonClass: 'bg-green-600 hover:bg-blue-700 focus:ring-green-500',
    loadingText: 'Procesando...',
    closeOnOverlay: false,
    closeOnEscape: false,
    loading: false,
    bodyClasses: 'p-8',
    footerClasses: 'bg-gray-50'
})
const etnias = [
    'Maya',
    'Xinca',
    'Ladino',
    'Garifuna',
    'Otro',
];
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
//chief
const chiefs = [
    {
        'id': 1,
        'name': 'Manuel López'
    },
    {
        'id': 2,
        'name': 'Alina Carreño'
    },
    {
        'id': 5,
        'name': 'Walter Tejeda'
    },
    {
        'id': 6,
        'name': 'Hugo Chávez'
    },
    {
        'id': 18,
        'name': 'Marlon Vásquez'
    },
];
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
const profilePictureSrc = computed(() => {
    const pic = store.record?.profile_picture_url
    if (!pic) return '' // No hay imagen

    // Si empieza con 'data:' (base64) o 'blob:' => es imagen local subida por el usuario
    if (pic.startsWith('data:') || pic.startsWith('blob:')) {
        return pic
    }

    // Si no, es imagen del servidor
    return url + pic
})
// Manejo de carga de imagen
const handleImageUpload = (e) => {
    const file = e.target.files[0]
    if (!file) return

    const reader = new FileReader()
    reader.onload = (e) => {
        store.record.profile_picture_url = e.target.result
    }
    reader.readAsDataURL(file)
    store.file = file;
}

// Eliminar imagen
const removeImage = () => {
    store.record.profile_picture_url = ''
    store.file = null;
};
</script>