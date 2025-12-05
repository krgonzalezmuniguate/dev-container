<template>
    <div v-if="hasErrors" class="space-y-3">
        <div v-for="(fieldErrors, fieldName) in errors" :key="fieldName"
            class="rounded-md bg-red-50 border border-red-200 p-3">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-red-800 capitalize">
                        <!-- {{ formatFieldName(fieldName) }} -->
                    </h3>
                    <div class="mt-2">
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            <li v-for="error in fieldErrors" :key="error">
                                {{ error }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Versión compacta - Todos los errores en un solo bloque -->
    <div v-if="compact && hasErrors" class="rounded-md bg-red-50 border border-red-200 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">
                    {{ title || 'Errores de Validación' }}
                </h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li v-for="(fieldErrors, fieldName) in errors" :key="fieldName">
                            <span class="font-medium capitalize">{{ formatFieldName(fieldName) }}:</span>
                            <span v-for="(error, index) in fieldErrors" :key="error">
                                {{ error }}{{ index < fieldErrors.length - 1 ? ', ' : '' }} </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

// Props del componente
const props = defineProps({
    errors: {
        type: Object,
        default: () => ({})
    },
    compact: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: ''
    }
})

// Computed para verificar si hay errores
const hasErrors = computed(() => {
    return props.errors && Object.keys(props.errors).length > 0
})

// Función para formatear nombres de campos
const formatFieldName = (fieldName) => {
    return fieldName
        .replace(/([A-Z])/g, ' $1')
        .replace(/^./, str => str.toUpperCase())
        .trim()
};
</script>