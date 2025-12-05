<template>
    <div
        class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900 flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Animated background elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div
                class="absolute -top-40 -right-40 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob">
            </div>
            <div
                class="absolute -bottom-40 -left-40 w-80 h-80 bg-indigo-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute top-40 left-40 w-80 h-80 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000">
            </div>
        </div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
            <!-- Logo animado -->
            <div class="flex justify-center animate-bounce-slow">
                <div
                    class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-2xl transform hover:scale-110 transition-all duration-300 hover:rotate-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6"></path>
                    </svg>
                </div>
            </div>

            <h2 class="mt-8 text-center text-4xl font-bold text-white animate-fade-in-up">
                <span class="bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
                    Sistema Auditoría
                </span>
            </h2>
            <p class="mt-4 text-center text-lg text-gray-300 animate-fade-in-up animation-delay-200">
                Acceso autorizado únicamente
            </p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md relative z-10">
            <div
                class="bg-white/10 backdrop-blur-lg py-10 px-6 shadow-2xl rounded-2xl sm:px-12 border border-white/20 animate-slide-up">
                <form class="space-y-8" @submit.prevent="handleLogin()">
                    <!-- Email Field -->
                    <div class="animate-fade-in-up animation-delay-400">
                        <label for="email" class="block text-sm font-semibold text-white mb-2">
                            Correo electrónico
                        </label>
                        <div class="relative group">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                v-model="store.credentials.email" @focus="emailFocused = true"
                                @blur="emailFocused = false"
                                class="appearance-none block w-full px-4 py-3 bg-white/20 border border-white/30 rounded-xl placeholder-gray-300 text-white focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all duration-300 backdrop-blur-sm hover:bg-white/25"
                                placeholder="usuario@muniguate.com" />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="h-5 w-5 transition-all duration-300"
                                    :class="emailFocused ? 'text-purple-300 scale-110' : 'text-gray-400'" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                    </path>
                                </svg>
                            </div>
                            <!-- Animated underline -->
                            <div class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-purple-400 to-pink-400 transition-all duration-300"
                                :class="emailFocused ? 'w-full' : 'w-0'"></div>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="animate-fade-in-up animation-delay-600">
                        <label for="password" class="block text-sm font-semibold text-white mb-2">
                            Contraseña
                        </label>
                        <div class="relative group">
                            <input id="password" name="password" :type="showPassword ? 'text' : 'password'"
                                autocomplete="current-password" required v-model="store.credentials.password"
                                @focus="passwordFocused = true" @blur="passwordFocused = false"
                                class="appearance-none block w-full px-4 py-3 bg-white/20 border border-white/30 rounded-xl placeholder-gray-300 text-white focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all duration-300 backdrop-blur-sm hover:bg-white/25"
                                placeholder="••••••••••••" />
                            <button type="button" @click="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center group">
                                <svg v-if="!showPassword"
                                    class="h-5 w-5 transition-all duration-300 group-hover:scale-110"
                                    :class="passwordFocused ? 'text-purple-300' : 'text-gray-400 hover:text-white'"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <svg v-else class="h-5 w-5 transition-all duration-300 group-hover:scale-110"
                                    :class="passwordFocused ? 'text-purple-300' : 'text-gray-400 hover:text-white'"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21">
                                    </path>
                                </svg>
                            </button>
                            <!-- Animated underline -->
                            <div class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-purple-400 to-pink-400 transition-all duration-300"
                                :class="passwordFocused ? 'w-full' : 'w-0'"></div>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between animate-fade-in-up animation-delay-800">
                        <div class="flex items-center group cursor-pointer" @click="rememberMe = !rememberMe">
                            <!-- <div class="relative">
                                <input id="remember-me" name="remember-me" type="checkbox" v-model="rememberMe"
                                    class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-white/30 rounded bg-white/20 transition-all duration-300" />
                                <div v-if="rememberMe"
                                    class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <svg class="w-3 h-3 text-white animate-scale-in" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <label for="remember-me"
                                class="ml-3 block text-sm text-white group-hover:text-purple-200 transition-colors cursor-pointer">
                                Mantener sesión activa
                            </label> -->
                        </div>

                        <div class="text-sm">
                            <a href="#"
                                class="font-medium text-purple-300 hover:text-white transition-all duration-300 hover:underline">
                                ¿Problemas de acceso?
                            </a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="animate-fade-in-up animation-delay-1000">
                        <button type="submit" :disabled="store.loadingLogin" @mouseenter="buttonHovered = true"
                            @mouseleave="buttonHovered = false"
                            class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg v-if="!store.loadingLogin"
                                    class="h-5 w-5 text-purple-300 group-hover:text-white transition-all duration-300"
                                    :class="buttonHovered ? 'animate-bounce' : ''" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <svg v-else class="animate-spin h-5 w-5 text-purple-300" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                            <span class="relative">
                                {{ store.loadingLogin ? 'Verificando acceso...' : 'Acceder al Sistema' }}
                            </span>
                            <!-- Animated shine effect -->
                            <div v-if="!store.loadingLogin"
                                class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                style="background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.2) 50%, transparent 70%); animation: shine 2s infinite;">
                            </div>
                        </button>
                    </div>
                </form>

                <!-- Footer info -->
                <div class="mt-8 text-center animate-fade-in-up animation-delay-1200">
                    <p class="text-xs text-gray-400">
                        Acceso restringido • Solo personal autorizado
                    </p>
                </div>

                <!-- PRUEBA -->
                <div class="p-6">
                    <Errors :errors="store.errors" :auto-dismiss="false" @dismiss="handleDismiss"
                        @dismiss-all="handleDismissAll" />
                </div>
                <!-- -->
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router';
const store = useAuthStore();
const router = useRouter();

const showPassword = ref(false)


// Estados de focus para animaciones
const emailFocused = ref(false)
const passwordFocused = ref(false)
const buttonHovered = ref(false)

// Funciones
const togglePassword = () => {
    showPassword.value = !showPassword.value
}

const handleDismiss = (field) => {
    console.log(`Error dismissed for field: ${field}`)
}

const handleDismissAll = () => {
    console.log('All errors dismissed')
}
async function handleLogin() {
    const success = await store.login()
    if (success) {
        router.push({ name: 'home' })
    }
}
</script>

<style scoped>
/* Animaciones personalizadas */
@keyframes blob {
    0% {
        transform: translate(0px, 0px) scale(1);
    }

    33% {
        transform: translate(30px, -50px) scale(1.1);
    }

    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }

    100% {
        transform: translate(0px, 0px) scale(1);
    }
}

@keyframes shine {
    0% {
        transform: translateX(-100%);
    }

    100% {
        transform: translateX(100%);
    }
}

@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(50px) scale(0.95);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes bounce-slow {

    0%,
    20%,
    50%,
    80%,
    100% {
        transform: translateY(0);
    }

    40% {
        transform: translateY(-10px);
    }

    60% {
        transform: translateY(-5px);
    }
}

@keyframes scale-in {
    from {
        transform: scale(0);
    }

    to {
        transform: scale(1);
    }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animate-bounce-slow {
    animation: bounce-slow 3s infinite;
}

.animate-fade-in-up {
    animation: fade-in-up 0.6s ease-out forwards;
}

.animate-slide-up {
    animation: slide-up 0.8s ease-out forwards;
}

.animate-scale-in {
    animation: scale-in 0.2s ease-out;
}

.animation-delay-200 {
    animation-delay: 0.2s;
}

.animation-delay-400 {
    animation-delay: 0.4s;
}

.animation-delay-600 {
    animation-delay: 0.6s;
}

.animation-delay-800 {
    animation-delay: 0.8s;
}

.animation-delay-1000 {
    animation-delay: 1.0s;
}

.animation-delay-1200 {
    animation-delay: 1.2s;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}
</style>