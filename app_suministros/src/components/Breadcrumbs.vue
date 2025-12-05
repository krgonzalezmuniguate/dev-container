<template>
  <nav class="flex text-gray-700 text-sm" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
      <li v-for="(crumb, index) in crumbs" :key="index" class="inline-flex items-center">
        <!-- Enlace externo -->
        <a
          v-if="crumb.path && isExternalLink(crumb.path)"
          :href="crumb.path"
          class="inline-flex items-center text-blue-600 hover:underline"
        >
          <span v-if="crumb.icon" class="mr-2">
            <i :class="crumb.icon"></i>
          </span>
          {{ crumb.name }}
        </a>
        
        <!-- Enlace interno con router-link -->
        <router-link
          v-else-if="crumb.path"
          :to="crumb.path"
          class="inline-flex items-center text-blue-600 hover:underline"
        >
          <span v-if="crumb.icon" class="mr-2">
            <i :class="crumb.icon"></i>
          </span>
          {{ crumb.name }}
        </router-link>

        <!-- Último breadcrumb (sin enlace) -->
        <span v-else class="inline-flex items-center text-gray-500">
          <span v-if="crumb.icon" class="mr-2">
            <i :class="crumb.icon"></i>
          </span>
          {{ crumb.name }}
        </span>

        <!-- Separador -->
        <span v-if="index < crumbs.length - 1" class="mx-2 text-gray-400">/</span>
      </li>
    </ol>
  </nav>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  breadcrumbs: {
    type: Array,
    required: true,
    default: () => [
      { name: 'Home', path: '/', icon: 'fas fa-home' },
      { name: 'Category', path: '/category' },
      { name: 'Current Page', path: '' }, // Ruta actual sin enlace
    ],
  },
});

const crumbs = props.breadcrumbs;

// Función para verificar si la ruta es una URL externa
const isExternalLink = (path) => {
  return path.startsWith('http://') || path.startsWith('https://');
};
</script>
