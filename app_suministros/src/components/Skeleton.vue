<script setup>
import { computed } from "vue";

const props = defineProps({
  columns: {
    type: Number,
    default: 4, // cantidad de columnas
  },
  rows: {
    type: Number,
    default: 3, // cantidad de filas
  },
  showPagination: {
    type: Boolean,
    default: true,
  },
  showLoader: {
    type: Boolean,
    default: true,
  },
  compact: {
    type: Boolean,
    default: false, // activa modo compacto
  },
});

// Generar columnas con clases de ejemplo (ajustables)
const generatedColumns = computed(() =>
  Array.from({ length: props.columns }, (_, i) => ({
    id: i + 1,
    class: `col-span-${Math.floor(12 / props.columns)}`, // distribuye en 12
  }))
);

// Clases dinámicas segun compacto
const rowClass = computed(() =>
  props.compact ? "grid grid-cols-12 gap-2 p-2" : "grid grid-cols-12 gap-4 p-4"
);

const headerClass = computed(() =>
  props.compact ? "grid grid-cols-12 gap-2 p-2" : "grid grid-cols-12 gap-4 p-4"
);
</script>

<template>
  <div class="space-y-6">
    <!-- Header con título y controles -->
    <div class="flex items-center justify-between mb-6">
      <div class="space-y-2">
        <div
          class="h-6 bg-gray-200 rounded-md w-40 animate-pulse relative overflow-hidden"
          :class="{ 'h-4 w-32': compact }"
        >
          <div
            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer"
          ></div>
        </div>
        <div
          class="h-4 bg-gray-200 rounded-md w-28 animate-pulse relative overflow-hidden"
          :class="{ 'h-3 w-20': compact }"
        >
          <div
            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer"
          ></div>
        </div>
      </div>

      <!-- Controles -->
      <div class="flex items-center space-x-3">
        <div
          class="h-8 bg-gray-200 rounded-md w-52 animate-pulse relative overflow-hidden"
          :class="{ 'h-6 w-40': compact }"
        >
          <div
            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer"
          ></div>
        </div>
        <div
          class="h-8 bg-gray-200 rounded-md w-20 animate-pulse relative overflow-hidden"
          :class="{ 'h-6 w-16': compact }"
        >
          <div
            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer"
          ></div>
        </div>
      </div>
    </div>

    <!-- Tabla skeleton -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
      <!-- Header tabla -->
      <div class="bg-gray-50 border-b">
        <div :class="headerClass">
          <div
            v-for="col in generatedColumns"
            :key="col.id"
            :class="col.class"
          >
            <div
              class="h-4 bg-gray-300 rounded animate-pulse relative overflow-hidden"
              :class="{ 'h-3': compact }"
            >
              <div
                class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer"
              ></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filas -->
      <div class="divide-y divide-gray-200">
        <div
          v-for="row in rows"
          :key="row"
          :class="rowClass"
        >
          <div
            v-for="col in generatedColumns"
            :key="`${row}-${col.id}`"
            :class="col.class"
          >
            <div
              class="h-4 bg-gray-200 rounded animate-pulse relative overflow-hidden"
              :class="{ 'h-3': compact }"
              :style="{ width: col.id === 2 ? '80%' : col.id === 3 ? '90%' : '70%' }"
            >
              <div
                class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Paginación -->
    <div
      v-if="showPagination"
      class="flex items-center justify-between pt-4"
    >
      <div
        class="h-4 bg-gray-200 rounded w-28 animate-pulse relative overflow-hidden"
        :class="{ 'h-3 w-20': compact }"
      >
        <div
          class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer"
        ></div>
      </div>

      <div class="flex items-center space-x-2">
        <div
          v-for="page in 5"
          :key="page"
          class="h-8 w-8 bg-gray-200 rounded animate-pulse relative overflow-hidden"
          :class="{ 'h-6 w-6': compact }"
        >
          <div
            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer"
          ></div>
        </div>
      </div>
    </div>

    <!-- Loader -->
    <div v-if="showLoader" class="flex items-center justify-center py-8">
      <div class="flex items-center space-x-3">
        <div
          class="w-6 h-6 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"
          :class="{ 'w-4 h-4 border': compact }"
        ></div>
        <span class="text-gray-600 animate-pulse text-sm"
          >Cargando datos...</span
        >
      </div>
    </div>

    <!-- Dots -->
    <div v-if="showLoader" class="flex justify-center space-x-2 pt-4">
      <div
        v-for="dot in 3"
        :key="dot"
        class="w-2 h-2 bg-blue-500 rounded-full animate-bounce"
        :style="{
          animationDelay: `${(dot - 1) * 0.2}s`,
          animationDuration: '1s',
        }"
      ></div>
    </div>
  </div>
</template>
