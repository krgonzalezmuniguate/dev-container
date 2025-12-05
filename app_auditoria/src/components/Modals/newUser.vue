<template>
    <div>
        <!-- Modal con todas las opciones configurables -->
        <Modal v-model="store.optionNew" :title="modalConfig.title" :subtitle="modalConfig.subtitle"
            :size="modalConfig.size" :show-header="modalConfig.showHeader" :show-footer="modalConfig.showFooter"
            :show-close-button="modalConfig.showCloseButton" :show-cancel-button="modalConfig.showCancelButton"
            :show-confirm-button="modalConfig.showConfirmButton" :cancel-button-text="modalConfig.cancelButtonText"
            :confirm-button-text="modalConfig.confirmButtonText" :confirm-button-class="modalConfig.confirmButtonClass"
            :loading-text="modalConfig.loadingText" :close-on-overlay="modalConfig.closeOnOverlay"
            :close-on-escape="modalConfig.closeOnEscape" :loading="modalConfig.loading"
            :body-classes="modalConfig.bodyClasses" :footer-classes="modalConfig.footerClasses" @close="handleClose"
            @cancel="handleCancel" @confirm="handleConfirm">
            <div>
                <input placeholder="nombres" class="border border-gray-400 rounded-3xl px-3 py-1 w-full my-2" />
                <input placeholder="apellidos" class="border border-gray-400 rounded-3xl px-3 py-1 w-full my-2" />
                <input type="email" placeholder="correo@example.com"
                    class="border border-gray-400 rounded-3xl px-3 py-1 w-full my-2" />
                <select class="border border-gray-400 rounded-3xl px-3 py-1 w-full my-2">
                    <option value="0">Seleccione un rol</option>
                    <option>1</option>
                    <option>1</option>
                    <option>1</option>
                    <option>1</option>
                    <option>1</option>
                    <option>1</option>
                </select>
            </div>
        </Modal>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useUserStore } from '@/stores/user'
import Modal from '@/components/Modal.vue'

// Estado reactivo
const store = useUserStore();
const modalOpen = ref(false)
const useCustomSlots = ref(false)
const events = ref([])

// Configuración del modal
const modalConfig = reactive({
    title: 'Registro de usuarios',
    subtitle: '',
    size: 'lg',
    showHeader: true,
    showFooter: true,
    showCloseButton: true,
    showCancelButton: true,
    showConfirmButton: true,
    cancelButtonText: 'Cancelar',
    confirmButtonText: 'Guardar',
    confirmButtonClass: 'bg-green-600 hover:bg-blue-700 focus:ring-green-500',
    loadingText: 'Procesando...',
    closeOnOverlay: true,
    closeOnEscape: true,
    loading: false,
    bodyClasses: 'p-8',
    footerClasses: 'bg-gray-50'
})

// Métodos para manejar eventos
const addEvent = (type, message) => {
    events.value.unshift({
        type,
        message,
        timestamp: new Date().toLocaleTimeString()
    })

    // Mantener solo los últimos 10 eventos
    if (events.value.length > 10) {
        events.value = events.value.slice(0, 10)
    }
}

const handleClose = () => {
    addEvent('close', 'Modal cerrado')
}

const handleCancel = () => {
    addEvent('cancel', 'Operación cancelada')
}

const handleConfirm = () => {
    addEvent('confirm', 'Operación confirmada')
    modalOpen.value = false
}

const handleCustomAction = () => {
    addEvent('custom', 'Acción personalizada ejecutada')
}

const getEventColor = (type) => {
    const colors = {
        close: 'text-gray-600',
        cancel: 'text-red-600',
        confirm: 'text-green-600',
        custom: 'text-purple-600'
    }
    return colors[type] || 'text-gray-600'
}
</script>