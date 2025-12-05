<template>
    <div>
        <Modal v-model="store.optionDelete" :title="modalConfig.title" :subtitle="modalConfig.subtitle"
            :size="modalConfig.size" :show-header="modalConfig.showHeader" :show-footer="modalConfig.showFooter"
            :show-close-button="modalConfig.showCloseButton" :show-cancel-button="modalConfig.showCancelButton"
            :show-confirm-button="modalConfig.showConfirmButton" :cancel-button-text="modalConfig.cancelButtonText"
            :confirm-button-text="modalConfig.confirmButtonText" :confirm-button-class="modalConfig.confirmButtonClass"
            :loading-text="modalConfig.loadingText" :close-on-overlay="modalConfig.closeOnOverlay"
            :close-on-escape="modalConfig.closeOnEscape" :loading="modalConfig.loading"
            :body-classes="modalConfig.bodyClasses" :footer-classes="modalConfig.footerClasses">
            <div class="grid gap-2">
                <div class=" w-full animate-fade-in-up">
                    <div class="flex flex-col items-center text-center p-6 pb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="h-16 w-16 text-red-500 mb-4">
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                            <path d="M12 9v4" />
                            <path d="M12 17h.01" />
                        </svg>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">
                            ¿Estás absolutamente seguro?
                        </h2>
                        <p class="text-lg text-gray-600 leading-relaxed">
                            Esta acción <span class="font-semibold text-red-600">no se puede deshacer</span>. Esto
                            eliminará permanentemente tu registro y removerá tus datos del servidor.
                        </p>
                    </div>
                    <div class="flex justify-center gap-4 p-6 pt-0">
                        <button id="cancelBtn"
                        @click="cancel()"
                            class="px-6 py-3 text-base border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-200">
                            Cancelar
                        </button>
                        <button id="confirmBtn" @click="store.destroy()"
                            class="px-6 py-3 text-base bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200 disabled:opacity-100 disabled:cursor-not-allowed disabled:bg-slate-400"
                            :disabled="store.buttonDelete">
                            Sí, eliminar
                        </button>
                    </div>
                </div>
                <div>
                    <Errors :errors="store.validationErrors" />
                </div>
            </div>

        </Modal>
    </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { useSupplyStore } from '@/stores/supply'
import { limpiar } from '@/utils/functions'
import Modal from '@/components/Modal.vue'
import { CameraIcon, XIcon } from 'lucide-vue-next'
// Estado reactivo
const store = useSupplyStore();
const url = import.meta.env.VITE_URL_STORAGE;
// Configuración del modal
const modalConfig = reactive({
    title: 'Eliminar usuario',
    subtitle: '',
    size: 'md',
    showHeader: false,
    showFooter: false,
    showCloseButton: true,
    showCancelButton: false,
    showConfirmButton: false,
    cancelButtonText: 'Cancelar',
    confirmButtonText: 'Guardar',
    confirmButtonClass: 'bg-green-600 hover:bg-blue-700 focus:ring-green-500',
    loadingText: 'Procesando...',
    closeOnOverlay: false,
    closeOnEscape: false,
    loading: false,
    bodyClasses: 'p-8',
    footerClasses: 'bg-gray-50'
})

function cancel() {
    store.optionDelete = false
};
</script>