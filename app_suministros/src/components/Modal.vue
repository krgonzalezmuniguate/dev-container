<template>
    <!-- Overlay del modal -->
    <Teleport to="body">
        <Transition enter-active-class="transition-opacity duration-300" enter-from-class="opacity-0"
            enter-to-class="opacity-100" leave-active-class="transition-opacity duration-300"
            leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="isVisible" class="fixed inset-0 bg-black bg-opacity-50 z-40 flex items-center justify-center p-4"
                @click="handleOverlayClick">
                <!-- Modal Container -->
                <Transition enter-active-class="transition-all duration-300"
                    enter-from-class="opacity-0 scale-95 translate-y-4"
                    enter-to-class="opacity-100 scale-100 translate-y-0"
                    leave-active-class="transition-all duration-300"
                    leave-from-class="opacity-100 scale-100 translate-y-0"
                    leave-to-class="opacity-0 scale-95 translate-y-4">
                    <div v-if="isVisible" :class="[
                        'bg-white rounded-xl shadow-2xl w-full max-h-[90vh] flex flex-col xl:mt-0 lg:mt-12',
                        sizeClasses
                    ]" @click.stop>
                        <!-- Header -->
                        <div v-if="showHeader" class="flex items-center justify-between p-6 border-b border-gray-200">
                            <div>
                                <h2 v-if="title" class="text-2xl font-bold text-gray-900">{{ title }}</h2>
                                <p v-if="subtitle" class="text-sm text-gray-600 mt-1">{{ subtitle }}</p>
                                <slot name="header"></slot>
                            </div>
                            <button v-if="showCloseButton" @click="closeModal"
                                class="p-2 hover:bg-gray-100 rounded-full transition-colors duration-200 group"
                                :disabled="loading">
                                <X class="w-5 h-5 text-gray-400 group-hover:text-gray-600" />
                            </button>
                        </div>

                        <!-- Body -->
                        <div :class="['overflow-y-auto flex-1', bodyClasses]">
                            <slot></slot>
                        </div>

                        <!-- Footer -->
                        <div v-if="showFooter"
                            :class="['flex items-center justify-end gap-3 p-6 border-t border-gray-200', footerClasses]">
                            <slot name="footer">
                                <button v-if="showCancelButton" type="button" @click="handleCancel" :disabled="loading"
                                    class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                    {{ cancelButtonText }}
                                </button>
                                <button v-if="showConfirmButton" type="button" @click="handleConfirm"
                                    :disabled="loading" :class="[
                                        'px-6 py-2 text-sm font-medium text-white border border-transparent rounded-lg focus:ring-2 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2',
                                        confirmButtonClass
                                    ]">
                                    <Loader2 v-if="loading" class="w-4 h-4 animate-spin" />
                                    {{ loading ? loadingText : confirmButtonText }}
                                </button>
                            </slot>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { X, Loader2 } from 'lucide-vue-next'

// Props
const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: ''
    },
    subtitle: {
        type: String,
        default: ''
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['xs', 'sm', 'md', 'lg', 'xl', 'full'].includes(value)
    },
    showHeader: {
        type: Boolean,
        default: true
    },
    showFooter: {
        type: Boolean,
        default: true
    },
    showCloseButton: {
        type: Boolean,
        default: true
    },
    showCancelButton: {
        type: Boolean,
        default: true
    },
    showConfirmButton: {
        type: Boolean,
        default: true
    },
    cancelButtonText: {
        type: String,
        default: 'Cancelar'
    },
    confirmButtonText: {
        type: String,
        default: 'Confirmar'
    },
    confirmButtonClass: {
        type: String,
        default: 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'
    },
    loadingText: {
        type: String,
        default: 'Cargando...'
    },
    closeOnOverlay: {
        type: Boolean,
        default: true
    },
    closeOnEscape: {
        type: Boolean,
        default: false
    },
    loading: {
        type: Boolean,
        default: false
    },
    bodyClasses: {
        type: String,
        default: 'p-6'
    },
    footerClasses: {
        type: String,
        default: 'bg-gray-50'
    }
})

// Emits
const emit = defineEmits(['update:modelValue', 'close', 'cancel', 'confirm'])

// Estado reactivo
const isVisible = ref(props.modelValue)

// Computed
const sizeClasses = computed(() => {
    const sizes = {
        xs: 'max-w-sm',
        sm: 'max-w-md',
        md: 'max-w-2xl',
        lg: 'max-w-4xl',
        xl: 'max-w-6xl',
        full: 'max-w-full mx-4'
    }
    return sizes[props.size] || sizes.md
})

// Watchers
watch(() => props.modelValue, (newValue) => {
    isVisible.value = newValue
})

watch(isVisible, (newValue) => {
    emit('update:modelValue', newValue)

    if (newValue) {
        // Prevenir scroll del body cuando el modal está abierto
        document.body.style.overflow = 'hidden'
    } else {
        // Restaurar scroll del body
        document.body.style.overflow = ''
    }
})

// Métodos
const closeModal = () => {
    if (props.loading) return

    isVisible.value = false
    emit('close')
}

const handleCancel = () => {
    emit('cancel')
    // closeModal()
}

const handleConfirm = () => {
    // emit('confirm')
}

const handleOverlayClick = () => {
    if (props.closeOnOverlay && !props.loading) {
        closeModal()
    }
}

// Manejo de teclas
const handleKeydown = (event) => {
    if (event.key === 'Escape' && isVisible.value && props.closeOnEscape && !props.loading) {
        closeModal()
    }
}

// Lifecycle hooks
onMounted(() => {
    if (props.closeOnEscape) {
        document.addEventListener('keydown', handleKeydown)
    }
})

onUnmounted(() => {
    document.removeEventListener('keydown', handleKeydown)
    // Asegurar que se restaure el scroll del body
    document.body.style.overflow = ''
});
</script>