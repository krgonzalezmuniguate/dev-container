<template>
  <Header />
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-8">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Panel de Aplicaciones</h1>
        <p class="text-gray-600">Accede a todas las herramientas de DAI</p>
      </div>

      <!-- Apps Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div v-for="(app, index) in store.apps" :key="app.id" class="relative group" @mouseenter="hoveredApp = app.id"
          @mouseleave="hoveredApp = null" :style="{ animationDelay: `${index * 100}ms` }">
          <!-- Main App Card -->
          <div @click="handleAppClick(app)"
            class="relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:-translate-y-2 overflow-hidden"
            :class="{
              'ring-4 ring-blue-500 ring-opacity-50': selectedApp?.id === app.id,
              'z-10': openDropdown === app.id
            }">
            <!-- Background Gradient -->
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
              :style="{ background: app.gradient }"></div>

            <!-- Content -->
            <div class="relative p-8 text-center">
              <!-- Icon Container -->
              <div class="mb-6 relative">
                <div
                  class="w-20 h-20 mx-auto rounded-2xl flex items-center justify-center transition-all duration-300 group-hover:scale-110"
                  :style="{ backgroundColor: app.color }">
                  <component :is="iconComponents[app.icon]"
                    class="w-10 h-10 text-white transition-transform duration-300 group-hover:rotate-12" />
                </div>

                <!-- Floating particles effect - Solo en hover -->
                <div v-if="hoveredApp === app.id" class="absolute inset-0 pointer-events-none">
                  <div v-for="particle in 6" :key="particle"
                    class="absolute w-2 h-2 rounded-full opacity-60 animate-ping" :style="{
                      backgroundColor: app.color,
                      top: `${Math.random() * 100}%`,
                      left: `${Math.random() * 100}%`,
                      animationDelay: `${particle * 200}ms`
                    }"></div>
                </div>
              </div>

              <!-- Title -->
              <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-white transition-colors duration-300">
                {{ app.title }}
                <ChevronDownIcon v-if="app.subApps" class="inline-block w-4 h-4 ml-2 transition-transform duration-200"
                  :class="{ 'rotate-180': openDropdown === app.id }" />
              </h3>

              <!-- Description -->
              <p
                class="text-gray-600 text-sm group-hover:text-white group-hover:text-opacity-90 transition-colors duration-300">
                {{ app.description }}
              </p>

              <!-- Click Ripple Effect -->
              <div v-if="clickedApp === app.id" class="absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 bg-white rounded-2xl animate-ping opacity-25"></div>
              </div>
            </div>

            <!-- Bottom accent line -->
            <div
              class="absolute bottom-0 left-0 right-0 h-1 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
              :style="{ backgroundColor: app.color }"></div>
          </div>

          <!-- Dropdown Menu - Solo aparece al hacer click -->
          <div v-if="app.subApps && openDropdown === app.id" class="absolute top-full left-0 right-0 mt-2 z-20"
            @click.stop>
            <div class="bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden animate-scale-in">
              <!-- Dropdown Header -->
              <div class="px-4 py-3 border-b border-gray-100" :style="{ backgroundColor: `${app.color}10` }">
                <div class="flex items-center justify-between">
                  <div>
                    <h4 class="font-semibold text-gray-900 text-sm">{{ app.title }}</h4>
                    <p class="text-xs text-gray-600 mt-1">{{ app.subApps.length }} aplicaciones</p>
                  </div>
                  <button @click="closeDropdown"
                    class="p-1 hover:bg-gray-100 rounded-full transition-colors duration-200">
                    <XIcon class="w-4 h-4 text-gray-500" />
                  </button>
                </div>
              </div>

              <!-- Dropdown Items -->
              <div class="py-2 max-h-64 overflow-y-auto">
                <a v-for="subApp in app.subApps" :key="subApp.id" :href="subApp.url" rel="noopener noreferrer"
                  @click="handleSubAppClick(subApp)"
                  class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors duration-150 cursor-pointer group/item">
                  <!-- Sub App Icon -->
                  <div
                    class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-transform duration-150 group-hover/item:scale-105"
                    :style="{ backgroundColor: `${subApp.color}20` }">
                    <component :is="iconComponents[subApp.icon]" class="w-4 h-4" :style="{ color: subApp.color }" />
                  </div>

                  <!-- Sub App Info -->
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">
                      {{ subApp.title }}
                    </p>
                    <p class="text-xs text-gray-500 truncate">
                      {{ subApp.description }}
                    </p>
                  </div>

                  <!-- External Link Icon -->
                  <ExternalLinkIcon
                    class="w-4 h-4 text-gray-400 opacity-0 group-hover/item:opacity-100 transition-opacity duration-150" />
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Overlay para cerrar dropdown al hacer click fuera -->
    <div v-if="openDropdown" @click="closeDropdown" class="fixed inset-0 z-5"></div>
  </div>
  <Top />
</template>
<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth';
import {
  UsersIcon,
  FileTextIcon,
  ContainerIcon,
  BarChart3Icon,
  SettingsIcon,
  ChevronDownIcon,
  ExternalLinkIcon,
  XIcon,
  UserCheckIcon,
  ClipboardListIcon,
  CalendarIcon,
  DollarSignIcon,
  TrendingUpIcon,
  PieChartIcon,
  ShieldCheckIcon,
  DatabaseIcon,
  BookIcon,
} from 'lucide-vue-next'
import Top from '@/components/Top.vue';
import Header from '@/components/Header.vue';
// Estado reactivo - Simplificado
const store = useAuthStore();
const hoveredApp = ref(null)
const selectedApp = ref(null)
const clickedApp = ref(null)
const openDropdown = ref(null) // Solo un dropdown abierto a la vez
const iconComponents = {
  UsersIcon,
  UserCheckIcon,
  CalendarIcon,
  ClipboardListIcon,
  DollarSignIcon,
  BarChart3Icon,
  FileTextIcon,
  TrendingUpIcon,
  PieChartIcon,
  ContainerIcon,
  SettingsIcon,
  ShieldCheckIcon,
  DatabaseIcon,
  BookIcon,
  // Add all your icons here
};
// Datos de las aplicaciones


// Métodos optimizados
const handleAppClick = (app) => {
  selectedApp.value = app
  clickedApp.value = app.id

  // Si tiene subapps, toggle del dropdown
  if (app.subApps) {
    openDropdown.value = openDropdown.value === app.id ? null : app.id
  } else if (app.url) {
    // Si no tiene subapps, navegar directamente
    window.location.href = app.url
  }

  // Limpiar efecto de click
  setTimeout(() => {
    clickedApp.value = null
  }, 300)
}

const closeDropdown = () => {
  openDropdown.value = null
}

const handleSubAppClick = (subApp) => {
  console.log(`Navegando a: ${subApp.title} - ${subApp.url}`)
  // Cerrar dropdown después de click
  setTimeout(() => {
    closeDropdown()
  }, 100)
};
</script>

<style scoped>
/* Animación más rápida para el dropdown */
@keyframes scaleIn {
  from {
    opacity: 0;
    transform: scale(0.95) translateY(-5px);
  }

  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

.animate-scale-in {
  animation: scaleIn 0.15s ease-out;
}

/* Transiciones más rápidas */
.transition-all {
  transition-duration: 200ms;
}

.transition-colors {
  transition-duration: 150ms;
}

.transition-transform {
  transition-duration: 150ms;
}

/* Z-index para overlay */
.z-5 {
  z-index: 5;
}

.z-10 {
  z-index: 10;
}

.z-20 {
  z-index: 20;
}

/* Scroll suave para dropdowns largos */
.overflow-y-auto {
  scrollbar-width: thin;
  scrollbar-color: #CBD5E0 transparent;
}

.overflow-y-auto::-webkit-scrollbar {
  width: 4px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background-color: #CBD5E0;
  border-radius: 2px;
}
</style>