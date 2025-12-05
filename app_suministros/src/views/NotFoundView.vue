<template>
    <div class="min-h-screen bg-gray-50 overflow-hidden">
        <!-- Header/Navbar -->
        <header class="bg-white shadow-sm border-b border-gray-200 relative z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <button @click="goHome"
                            class="text-xl font-semibold text-gray-900 hover:text-blue-600 transition-colors duration-200">
                            DAI
                        </button>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button @click="goHome"
                            class="text-sm text-gray-600 hover:text-gray-900 transition-colors duration-200">
                            Volver al inicio
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Elementos flotantes animados de fondo -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div v-for="(circle, index) in floatingCircles" :key="index" class="absolute rounded-full opacity-10"
                :class="circle.color" :style="circle.style"></div>
        </div>

        <!-- Contenido principal -->
        <div class="relative z-10 flex items-center justify-center min-h-[calc(100vh-4rem)] px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto">

                <!-- Número 404 animado -->
                <div class="relative mb-8">
                    <div class="text-8xl sm:text-9xl font-bold text-gray-200 select-none animate-pulse-slow">
                        404
                    </div>

                    <!-- Elementos decorativos animados -->
                    <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center">
                        <div class="relative">
                            <!-- Círculos orbitales -->
                            <div v-for="(orbit, index) in orbits" :key="index"
                                class="absolute border-2 border-blue-200 rounded-full animate-spin-slow"
                                :style="orbit.style">
                                <div class="w-3 h-3 bg-blue-500 rounded-full absolute animate-bounce"
                                    :style="orbit.dotStyle"></div>
                            </div>

                            <!-- Icono central -->
                            <div
                                class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center animate-float">
                                <SearchIcon class="w-10 h-10 text-blue-600" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Texto principal con animación de escritura -->
                <div class="mb-6">
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4 animate-fade-in-up">
                        {{ typedText }}
                        <span class="animate-blink">|</span>
                    </h1>
                    <p class="text-lg text-gray-600 animate-fade-in-up animation-delay-300">
                        La página que buscas no existe o ha sido movida.
                    </p>
                </div>

                <!-- Sugerencias animadas -->
                <div class="mb-8 animate-fade-in-up animation-delay-600">
                    <p class="text-gray-500 mb-4">¿Qué te gustaría hacer?</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-lg mx-auto">
                        <button v-for="(suggestion, index) in suggestions" :key="index" @click="suggestion.action"
                            class="group p-4 bg-white rounded-lg shadow-sm border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1"
                            :style="{ animationDelay: `${800 + index * 200}ms` }">
                            <component :is="suggestion.icon"
                                class="w-6 h-6 text-gray-400 group-hover:text-blue-500 mx-auto mb-2 transition-colors duration-200" />
                            <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">{{ suggestion.text }}
                            </p>
                        </button>
                    </div>
                </div>

                <!-- Botones principales -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up animation-delay-1000">
                    <button @click="goHome"
                        class="group px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                        <HomeIcon class="w-5 h-5 mr-2 group-hover:animate-bounce" />
                        Ir al inicio
                    </button>

                    <button @click="goBack"
                        class="group px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                        <ArrowLeftIcon class="w-5 h-5 mr-2 group-hover:animate-pulse" />
                        Volver atrás
                    </button>
                </div>

                <!-- Mensaje de ayuda -->
                <div
                    class="mt-12 p-4 bg-blue-50 border border-blue-200 rounded-lg animate-fade-in-up animation-delay-1200">
                    <div class="flex items-start">
                        <InfoIcon class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" />
                        <div class="ml-3 text-left">
                            <p class="text-sm font-medium text-blue-800">¿Necesitas ayuda?</p>
                            <p class="text-sm text-blue-700 mt-1">
                                Si crees que esto es un error, puedes
                                <button @click="reportError" class="underline hover:no-underline font-medium">
                                    reportar el problema
                                </button>
                                o contactar a nuestro equipo de soporte.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router';
import {
    SearchIcon,
    HomeIcon,
    ArrowLeftIcon,
    InfoIcon,
    BookOpenIcon,
    MessageCircleIcon,
    SettingsIcon
} from 'lucide-vue-next'
const router = useRouter();
// Texto animado
const fullText = "¡Oops! Página no encontrada"
const typedText = ref("")
const typewriterIndex = ref(0)

// Elementos flotantes
const floatingCircles = ref([])
const orbits = ref([])

// Sugerencias
const suggestions = ref([
    {
        icon: BookOpenIcon,
        text: "Ver documentación",
        action: () => console.log("Ir a documentación")
    },
    {
        icon: MessageCircleIcon,
        text: "Contactar soporte",
        action: () => console.log("Contactar soporte")
    },
    {
        icon: SettingsIcon,
        text: "Configuración",
        action: () => console.log("Ir a configuración")
    }
])

// Funciones de navegación
const goHome = () => {
    router.push({ name: 'home' });
}

const goBack = () => {
    window.history.back()
}

const reportError = () => {
    console.log("Reportar error")
    // Aquí podrías abrir un modal o redirigir a un formulario
}

// Animación de escritura
const startTypewriter = () => {
    const interval = setInterval(() => {
        if (typewriterIndex.value < fullText.length) {
            typedText.value += fullText[typewriterIndex.value]
            typewriterIndex.value++
        } else {
            clearInterval(interval)
        }
    }, 100)
}

// Generar elementos flotantes
const generateFloatingElements = () => {
    // Círculos flotantes
    for (let i = 0; i < 8; i++) {
        floatingCircles.value.push({
            color: i % 2 === 0 ? 'bg-blue-500' : 'bg-purple-500',
            style: {
                width: `${Math.random() * 100 + 50}px`,
                height: `${Math.random() * 100 + 50}px`,
                left: `${Math.random() * 100}%`,
                top: `${Math.random() * 100}%`,
                animationDelay: `${Math.random() * 5}s`,
                animationDuration: `${Math.random() * 10 + 10}s`
            }
        })
    }

    // Órbitas
    for (let i = 0; i < 3; i++) {
        const size = 80 + (i * 40)
        orbits.value.push({
            style: {
                width: `${size}px`,
                height: `${size}px`,
                top: `${-size / 2}px`,
                left: `${-size / 2}px`,
                animationDuration: `${8 + i * 2}s`,
                animationDirection: i % 2 === 0 ? 'normal' : 'reverse'
            },
            dotStyle: {
                top: '-6px',
                left: '50%',
                marginLeft: '-6px'
            }
        })
    }
}

onMounted(() => {
    startTypewriter()
    generateFloatingElements()
});
</script>

<style scoped>
/* Animaciones personalizadas */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes float {

    0%,
    100% {
        transform: translateY(0px);
    }

    50% {
        transform: translateY(-20px);
    }
}

@keyframes blink {

    0%,
    50% {
        opacity: 1;
    }

    51%,
    100% {
        opacity: 0;
    }
}

@keyframes pulse-slow {

    0%,
    100% {
        opacity: 0.1;
    }

    50% {
        opacity: 0.3;
    }
}

@keyframes spin-slow {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

/* Clases de animación */
.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out forwards;
    opacity: 0;
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

.animate-blink {
    animation: blink 1s infinite;
}

.animate-pulse-slow {
    animation: pulse-slow 4s ease-in-out infinite;
}

.animate-spin-slow {
    animation: spin-slow linear infinite;
}

/* Delays de animación */
.animation-delay-300 {
    animation-delay: 300ms;
}

.animation-delay-600 {
    animation-delay: 600ms;
}

.animation-delay-1000 {
    animation-delay: 1000ms;
}

.animation-delay-1200 {
    animation-delay: 1200ms;
}

/* Animaciones de elementos flotantes */
.floating-circles>div {
    animation: float 15s ease-in-out infinite;
}

/* Efectos hover mejorados */
.group:hover .group-hover\:animate-bounce {
    animation: bounce 1s infinite;
}

.group:hover .group-hover\:animate-pulse {
    animation: pulse 2s infinite;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .text-8xl {
        font-size: 4rem;
    }

    .text-9xl {
        font-size: 5rem;
    }
}
</style>