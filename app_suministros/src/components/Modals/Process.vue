<template>
    <div>
        <Modal v-model="store.optionManager" :title="modalConfig.title" :subtitle="modalConfig.subtitle"
            :size="modalConfig.size" :show-header="modalConfig.showHeader" :show-footer="modalConfig.showFooter"
            :show-close-button="modalConfig.showCloseButton" :show-cancel-button="modalConfig.showCancelButton"
            :show-confirm-button="modalConfig.showConfirmButton" :cancel-button-text="modalConfig.cancelButtonText"
            :confirm-button-text="modalConfig.confirmButtonText" :confirm-button-class="modalConfig.confirmButtonClass"
            :loading-text="modalConfig.loadingText" :close-on-overlay="modalConfig.closeOnOverlay"
            :close-on-escape="modalConfig.closeOnEscape" :loading="modalConfig.loading"
            :body-classes="modalConfig.bodyClasses" :footer-classes="modalConfig.footerClasses">
            <div class="grid gap-2">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">Descripción de
                            solicitud</label>
                        <textarea id="firstName" v-model="store.record.description" type="text"
                            placeholder="No. articulo" rows="3" readonly disabled
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />

                        <fieldset class="border-1 border-red-600 grid grid-cols-2 gap-2">
                            <legend>Procesar</legend>
                            <select
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option selected disabled>SELECCIONE</option>
                            </select>
                            <input type="number" placeholder="cantidad"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </fieldset>
                    </div>
                </div>
                <div>
                    <Errors :errors="store.validationErrors" />
                </div>
            </div>
            <template #footer>
                <button @click="cancel()"
                    class="bg-red-600 hover:bg-red-700 focus:ring-red-500 p-2 rounded-xl text-white">Cancelar</button>
                <button @click="store.store()" type="button"
                    class="bg-green-600 hover:bg-green-700 focus:ring-green-500 p-2 rounded-xl text-white disabled:opacity-100 disabled:cursor-not-allowed disabled:bg-slate-400"
                    :disabled="store.buttonNew">Guardar</button>


            </template>
        </Modal>
    </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { useRequestStore } from '@/stores/request_supplies'
import { limpiar } from '@/utils/functions'
import Modal from '@/components/Modal.vue'
const store = useRequestStore();
const url = import.meta.env.VITE_URL_STORAGE;
// Configuración del modal
const modalConfig = reactive({
    title: 'Procesar solicitud',
    subtitle: '',
    size: 'lg',
    showHeader: true,
    showFooter: true,
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
    limpiar(store.newRecord);
    store.optionManager = false;
    store.validationErrors = [];
};
</script>

<style lang="css" scoped>
textarea {
    resize: none;
}
</style>