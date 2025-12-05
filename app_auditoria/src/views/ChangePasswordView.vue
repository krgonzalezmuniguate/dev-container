<template>
    <Header />
    <div class="bg-gradient-to-br from-blue-50 to-indigo-100">
        <div class="container mx-auto py-10 px-4 md:px-6">
            <div class="w-[60%] mx-auto">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Cambiar contraseña</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Actualiza tu contraseña para mantener tu cuenta segura
                        </p>
                    </div>

                    <div class="px-6 py-6">
                        <div v-if="showSuccess"
                            class="mb-6 flex items-center p-4 bg-green-50 border border-green-200 rounded-lg">
                            <CheckCircleIcon class="w-5 h-5 text-green-600 mr-3" />
                            <div>
                                <p class="text-sm font-medium text-green-800">
                                    ¡Contraseña actualizada!
                                </p>
                                <p class="text-sm text-green-700">
                                    Tu contraseña ha sido cambiada exitosamente.
                                </p>
                            </div>
                        </div>

                        <form @submit.prevent="handleSubmit" class="space-y-6">
                            <div>
                                <label for="current-password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Contraseña actual
                                </label>
                                <div class="relative">
                                    <input id="current-password" v-model="store.currentPassword"
                                        :type="showCurrentPassword ? 'text' : 'password'"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10"
                                        required />
                                    <button type="button" @click="showCurrentPassword = !showCurrentPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <EyeIcon v-if="!showCurrentPassword" class="h-4 w-4 text-gray-400" />
                                        <EyeOffIcon v-else class="h-4 w-4 text-gray-400" />
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="new-password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nueva contraseña
                                </label>
                                <div class="relative">
                                    <input id="new-password" v-model="store.newPassword"
                                        :type="showNewPassword ? 'text' : 'password'"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10"
                                        required />
                                    <button type="button" @click="showNewPassword = !showNewPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <EyeIcon v-if="!showNewPassword" class="h-4 w-4 text-gray-400" />
                                        <EyeOffIcon v-else class="h-4 w-4 text-gray-400" />
                                    </button>
                                </div>

                                <div v-if="store.newPassword" class="mt-2">
                                    <div class="flex items-center space-x-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full transition-all duration-300"
                                                :class="passwordStrengthColor"
                                                :style="{ width: passwordStrengthWidth }"></div>
                                        </div>
                                        <span class="text-xs font-medium" :class="passwordStrengthTextColor">
                                            {{ passwordStrengthText }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirmar nueva contraseña
                                </label>
                                <div class="relative">
                                    <input id="confirm-password" v-model="store.newPasswordConfirmation"
                                        :type="shownewPasswordConfirmation ? 'text' : 'password'"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10"
                                        :class="{
                                            'border-red-300 focus:ring-red-500 focus:border-red-500':
                                                store.newPasswordConfirmation && !passwordsMatch,
                                        }" required />
                                    <button type="button"
                                        @click="shownewPasswordConfirmation = !shownewPasswordConfirmation"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <EyeIcon v-if="!shownewPasswordConfirmation" class="h-4 w-4 text-gray-400" />
                                        <EyeOffIcon v-else class="h-4 w-4 text-gray-400" />
                                    </button>
                                </div>
                                <p v-if="store.newPasswordConfirmation && !passwordsMatch"
                                    class="mt-1 text-sm text-red-600">
                                    Las contraseñas no coinciden
                                </p>
                            </div>

                            <div v-if="error" class="flex items-center p-4 bg-red-50 border border-red-200 rounded-lg">
                                <AlertCircleIcon class="w-5 h-5 text-red-600 mr-3" />
                                <p class="text-sm text-red-700">{{ error }}</p>
                            </div>

                            <div class="flex justify-end space-x-3 pt-4">
                                <button type="button" @click="resetForm"
                                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Cancelar
                                </button>
                                <button type="submit" :disabled="!isFormValid || isLoading"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                                    <div v-if="isLoading"
                                        class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                                    {{ isLoading ? 'Actualizando...' : 'Actualizar contraseña' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <Errors :errors="store.validationErrors" />
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <InfoIcon class="h-5 w-5 text-blue-400 mt-0.5" />
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Consejos para una contraseña segura
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Usa al menos 8 caracteres</li>
                                    <li>Incluye mayúsculas, minúsculas, números y símbolos</li>
                                    <li>Evita información personal como nombres o fechas</li>
                                    <li>No reutilices contraseñas de otras cuentas</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Top />
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import { useAuthStore } from '@/stores/auth'
import {
    EyeIcon,
    EyeOffIcon,
    CheckCircleIcon,
    AlertCircleIcon,
    InfoIcon
} from 'lucide-vue-next'
import Header from '@/components/Header.vue'
import Top from '@/components/Top.vue'
const store = useAuthStore();

// Estados de visibilidad de contraseñas
const showCurrentPassword = ref(false)
const showNewPassword = ref(false)
const shownewPasswordConfirmation = ref(false)

// Estados de la aplicación
const isLoading = ref(false)
const error = ref('')
const showSuccess = ref(false)

// Computed properties
const passwordsMatch = computed(() => {
    return store.newPassword === store.newPasswordConfirmation
})

const passwordStrength = computed(() => {
    const password = store.newPassword
    let score = 0

    if (password.length >= 8) score++
    if (/[a-z]/.test(password)) score++
    if (/[A-Z]/.test(password)) score++
    if (/[0-9]/.test(password)) score++
    if (/[^A-Za-z0-9]/.test(password)) score++

    return score
})

const passwordStrengthText = computed(() => {
    const strength = passwordStrength.value
    if (strength <= 2) return 'Débil'
    if (strength <= 3) return 'Media'
    if (strength <= 4) return 'Fuerte'
    return 'Muy fuerte'
})

const passwordStrengthColor = computed(() => {
    const strength = passwordStrength.value
    if (strength <= 2) return 'bg-red-500'
    if (strength <= 3) return 'bg-yellow-500'
    if (strength <= 4) return 'bg-green-500'
    return 'bg-green-600'
})

const passwordStrengthTextColor = computed(() => {
    const strength = passwordStrength.value
    if (strength <= 2) return 'text-red-600'
    if (strength <= 3) return 'text-yellow-600'
    if (strength <= 4) return 'text-green-600'
    return 'text-green-700'
})

const passwordStrengthWidth = computed(() => {
    return `${(passwordStrength.value / 5) * 100}%`
})

const isFormValid = computed(() => {
    return store.currentPassword &&
        store.newPassword &&
        store.newPasswordConfirmation &&
        passwordsMatch.value &&
        passwordStrength.value >= 3
})

// Métodos
const handleSubmit = async () => {
    isLoading.value = true
    error.value = ''
    showSuccess.value = false

    try {
        // Simulación de llamada a API
        const alert = await store.changePassword()

        // Simular éxito
        if (alert) {
            showSuccess.value = true
            resetForm()

            // Ocultar mensaje de éxito después de 5 segundos
            setTimeout(() => {
                showSuccess.value = false
            }, 5000)
        }

    } catch (err) {
        error.value = 'Error al actualizar la contraseña. Inténtalo de nuevo.'
    } finally {
        isLoading.value = false
    }
}

const resetForm = () => {
    store.currentPassword = '',
    store.newPassword = '',
    store.newPasswordConfirmation = ''
    error.value = ''
};
</script>

<style scoped>
/* Animaciones personalizadas */
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>