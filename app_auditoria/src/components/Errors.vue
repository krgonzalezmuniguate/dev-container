<template>
    <TransitionGroup name="error" tag="div" class="space-y-3">
        <div v-for="(messages, field) in errors" :key="field" class="error-item">
            <div class="flex items-start space-x-3 p-4 bg-red-50 border border-red-200 rounded-lg shadow-sm">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-red-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-medium text-red-800 capitalize mb-1">
                        {{ formatFieldName(field) }}
                    </h3>

                    <TransitionGroup name="message" tag="ul" class="space-y-1">
                        <li v-for="(message, index) in messages" :key="`${field}-${index}`"
                            class="text-sm text-red-700 flex items-center space-x-2">
                            <span class="w-1 h-1 bg-red-400 rounded-full flex-shrink-0"></span>
                            <span>{{ message }}</span>
                        </li>
                    </TransitionGroup>
                </div>

                <button @click="dismissError(field)"
                    class="flex-shrink-0 p-1 rounded-md text-red-400 hover:text-red-600 hover:bg-red-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-red-50"
                    :aria-label="`Dismiss ${field} error`">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </TransitionGroup>
</template>

<script setup>
import { ref, computed } from 'vue'

// Props
const props = defineProps({
    errors: {
        type: Object,
        default: () => ({})
    },
    autoDismiss: {
        type: Boolean,
        default: false
    },
    dismissDelay: {
        type: Number,
        default: 5000
    }
})

// Emits
const emit = defineEmits(['dismiss', 'dismissAll'])

// Reactive data
const dismissedErrors = ref(new Set())

// Computed
const errors = computed(() => {
    const filteredErrors = {}

    Object.keys(props.errors).forEach(field => {
        if (!dismissedErrors.value.has(field)) {
            filteredErrors[field] = props.errors[field]
        }
    })

    return filteredErrors
})

// Methods
const formatFieldName = (field) => {
    return field
        .replace(/_/g, ' ')
        .replace(/\./g, ' ')
        .split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ')
}

const dismissError = (field) => {
    dismissedErrors.value.add(field)
    emit('dismiss', field)
}

const dismissAll = () => {
    Object.keys(props.errors).forEach(field => {
        dismissedErrors.value.add(field)
    })
    emit('dismissAll')
}

// Auto dismiss functionality
if (props.autoDismiss) {
    setTimeout(() => {
        dismissAll()
    }, props.dismissDelay)
}

// Expose methods for parent component
defineExpose({
    dismissError,
    dismissAll
})
</script>

<style scoped>
/* Error container animations */
.error-enter-active {
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.error-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.error-enter-from {
    opacity: 0;
    transform: translateX(-20px) scale(0.95);
}

.error-leave-to {
    opacity: 0;
    transform: translateX(20px) scale(0.95);
}

/* Message animations */
.message-enter-active {
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    transition-delay: 0.1s;
}

.message-leave-active {
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.message-enter-from {
    opacity: 0;
    transform: translateY(-10px);
}

.message-leave-to {
    opacity: 0;
    transform: translateY(10px);
}

/* Error item hover effect */
.error-item {
    transition: transform 0.2s ease-in-out;
}

.error-item:hover {
    transform: translateY(-1px);
}

/* Pulse animation for new errors */
@keyframes pulse-error {

    0%,
    100% {
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
    }

    50% {
        box-shadow: 0 0 0 8px rgba(239, 68, 68, 0);
    }
}

.error-item:first-child .bg-red-50 {
    animation: pulse-error 1s ease-in-out;
}
</style>