<template>
    <!-- Scroll to Top Button -->
    <transition enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 scale-75 translate-y-4" enter-to-class="opacity-100 scale-100 translate-y-0"
        leave-active-class="transition-all duration-200 ease-in" leave-from-class="opacity-100 scale-100 translate-y-0"
        leave-to-class="opacity-0 scale-75 translate-y-4">
        <button v-if="showButton" @click="scrollToTop" class="fixed bottom-6 right-6 z-50 group"
            :class="{ 'animate-bounce': isScrolling }">
            <!-- Main Button -->
            <div class="relative">
                <!-- Background with gradient -->
                <div
                    class="w-14 h-14 bg-gradient-to-r from-blue-600 to-blue-700 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center group-hover:scale-110 group-active:scale-95">
                    <!-- Ripple effect on click -->
                    <div v-if="showRipple" class="absolute inset-0 bg-white rounded-full animate-ping opacity-25"></div>

                    <!-- Arrow Icon -->
                    <svg class="w-6 h-6 text-white transition-transform duration-300 group-hover:-translate-y-0.5"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                </div>

                <!-- Progress Ring -->
                <svg class="absolute inset-0 w-14 h-14 -rotate-90 transition-all duration-300"
                    :class="{ 'opacity-100': showProgress, 'opacity-0': !showProgress }">
                    <circle cx="28" cy="28" r="26" stroke="currentColor" stroke-width="2" fill="transparent"
                        class="text-blue-200" />
                    <circle cx="28" cy="28" r="26" stroke="currentColor" stroke-width="2" fill="transparent"
                        stroke-linecap="round" class="text-white transition-all duration-300"
                        :stroke-dasharray="circumference" :stroke-dashoffset="strokeDashoffset" />
                </svg>

                <!-- Floating particles effect -->
                <div v-if="showParticles" class="absolute inset-0 pointer-events-none">
                    <div v-for="particle in 4" :key="particle"
                        class="absolute w-1 h-1 bg-blue-300 rounded-full animate-ping" :style="{
                            top: `${20 + Math.random() * 20}px`,
                            left: `${20 + Math.random() * 20}px`,
                            animationDelay: `${particle * 150}ms`,
                            animationDuration: '1s'
                        }"></div>
                </div>
            </div>

            <!-- Tooltip -->
            <div
                class="absolute bottom-full right-0 mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                <div class="bg-gray-900 text-white text-xs px-3 py-2 rounded-lg whitespace-nowrap shadow-lg">
                    Volver arriba
                    <div
                        class="absolute top-full right-4 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900">
                    </div>
                </div>
            </div>
        </button>
    </transition>


</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'

// Component state
const showButton = ref(false)
const showRipple = ref(false)
const showParticles = ref(false)
const showProgress = ref(true)
const isScrolling = ref(false)
const scrollY = ref(0)

// Progress ring calculations
const circumference = 2 * Math.PI * 26 // radius = 26
const strokeDashoffset = computed(() => {
    const progress = scrollY.value / (document.documentElement.scrollHeight - window.innerHeight)
    return circumference - (progress * circumference)
})

// Demo content
const contentSections = [
    {
        id: 1,
        title: 'Sección 1: Introducción',
        content: [
            'Este es un ejemplo de contenido largo para demostrar el funcionamiento del botón scroll to top.',
            'El botón aparece cuando te desplazas hacia abajo y desaparece cuando estás en la parte superior.',
            'Incluye animaciones suaves, efectos visuales y un indicador de progreso circular.'
        ]
    },
    {
        id: 2,
        title: 'Sección 2: Características',
        content: [
            'El botón tiene múltiples efectos visuales: hover, click, partículas flotantes y animaciones.',
            'Se muestra un tooltip informativo cuando pasas el mouse sobre él.',
            'El anillo de progreso indica qué tan abajo has llegado en la página.'
        ]
    },
    {
        id: 3,
        title: 'Sección 3: Funcionalidad',
        content: [
            'Al hacer click, la página se desplaza suavemente hacia arriba.',
            'El botón tiene estados visuales que responden a las interacciones del usuario.',
            'Es completamente responsive y funciona en dispositivos móviles y desktop.'
        ]
    },
    {
        id: 4,
        title: 'Sección 4: Personalización',
        content: [
            'Puedes cambiar fácilmente los colores, tamaños y efectos del botón.',
            'La posición es configurable (bottom-right por defecto).',
            'Los umbrales de aparición y las animaciones son personalizables.'
        ]
    },
    {
        id: 5,
        title: 'Sección 5: Implementación',
        content: [
            'El componente es fácil de integrar en cualquier aplicación Vue 3.',
            'Usa Composition API y es completamente reactivo.',
            'Incluye cleanup automático de event listeners para evitar memory leaks.'
        ]
    }
]

// Methods
const handleScroll = () => {
    scrollY.value = window.scrollY
    showButton.value = window.scrollY > 300

    // Stop scrolling animation after a delay
    clearTimeout(window.scrollTimeout)
    isScrolling.value = true
    window.scrollTimeout = setTimeout(() => {
        isScrolling.value = false
    }, 150)
}

const scrollToTop = () => {
    // Show ripple effect
    showRipple.value = true
    showParticles.value = true

    // Smooth scroll to top
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    })

    // Reset effects
    setTimeout(() => {
        showRipple.value = false
    }, 300)

    setTimeout(() => {
        showParticles.value = false
    }, 1000)
}

// Lifecycle
onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true })
    handleScroll() // Initial check
})

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
    clearTimeout(window.scrollTimeout)
})
</script>

<style scoped>
/* Custom animations */
@keyframes float {

    0%,
    100% {
        transform: translateY(0px);
    }

    50% {
        transform: translateY(-10px);
    }
}

.float-animation {
    animation: float 3s ease-in-out infinite;
}

/* Smooth scrolling for older browsers */
html {
    scroll-behavior: smooth;
}

/* Hide scrollbar for demo */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>