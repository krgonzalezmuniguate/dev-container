<template>
    <div>
        <Modal v-model="store.optionView" :title="modalConfig.title" :subtitle="modalConfig.subtitle"
            :size="modalConfig.size" :show-header="modalConfig.showHeader" :show-footer="modalConfig.showFooter"
            :show-close-button="modalConfig.showCloseButton" :show-cancel-button="modalConfig.showCancelButton"
            :show-confirm-button="modalConfig.showConfirmButton" :cancel-button-text="modalConfig.cancelButtonText"
            :confirm-button-text="modalConfig.confirmButtonText" :confirm-button-class="modalConfig.confirmButtonClass"
            :loading-text="modalConfig.loadingText" :close-on-overlay="modalConfig.closeOnOverlay"
            :close-on-escape="modalConfig.closeOnEscape" :loading="modalConfig.loading"
            :body-classes="modalConfig.bodyClasses" :footer-classes="modalConfig.footerClasses">
            <div class="grid gap-4">
                <!-- Encabezado -->
                <p class="text-base text-gray-600">
                    La solicitud de suministros de
                    <span class="font-bold text-primary">{{ store.record.user }}</span>.
                </p>
                <!-- Info de la solicitud -->
                <div v-if="store.loadingOptions" class="bg-white rounded-2xl shadow p-6 space-y-4">
                    <div class="flex items-center">
                        <label class="text-teal-800 font-semibold w-1/3">Descripción</label>
                        <div class="text-gray-800 font-medium">{{ store.record.description }}</div>
                    </div>
                    <div class="flex items-center">
                        <label class="text-teal-800 font-semibold w-1/3">Fecha</label>
                        <div class="text-gray-800 font-medium">{{ store.record.fecha_creacion_format }}</div>
                    </div>
                    <div class="flex items-center">
                        <label class="text-teal-800 font-semibold w-1/3">Estado</label>
                        <div class="text-gray-800 font-medium">{{ store.record.state }}</div>
                    </div>
                    <div class="flex items-center" v-if="store.record.reason_rejection">
                        <label class="text-teal-800 font-semibold w-1/3">Motivo de rechazo</label>
                        <div class="text-gray-800 font-medium">{{ store.record.reason_rejection }}</div>
                    </div>
                </div>
            </div>
            <template #footer>
            </template>
        </Modal>
    </div>
</template>
<script setup>
import { reactive, ref, computed } from 'vue'
import { useRequestStore } from '@/stores/request_supplies';
import { useSupplyStore } from '@/stores/supply';
import Modal from '@/components/Modal.vue'
import { ChevronDown, ChevronUp, Check, X, Eye } from 'lucide-vue-next'
const store = useRequestStore();
const supply = useSupplyStore();
const modalConfig = reactive({
    title: 'Ver solicitud',
    subtitle: '',
    size: 'md',
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
const showAvailability = ref(false)
const showRejectionReason = ref(false)
const showValidationReason = ref(false)
const searchQuery = ref("")
const filteredDatos = computed(() => {
    if (!searchQuery.value) return supply.records.filter((item) => item.state == 1 && item.stock >= 1)
    return supply.records.filter(item =>
        item.state == 1 && item.stock >= 1 &&
        item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
});
async function toggleAvailability() {
    if (!showAvailability.value) {
        await supply.index()
    }
    showAvailability.value = !showAvailability.value
};

function reject() {

    if (showRejectionReason.value) {
        if (store.reason_reject.length >= 10) {
            store.reject();
        } else {
            showValidationReason.value = true;
        }
    } else {
        showRejectionReason.value = true;
    }
};
</script>
<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>