<template>
    <div>
        <Modal v-model="store.optionAuthorization" :title="modalConfig.title" :subtitle="modalConfig.subtitle"
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
                    Gestione la solicitud de suministros de
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
                </div>

                <!-- Botón Ver disponibilidad -->
                <div class="flex justify-center items-center">
                    <button @click="toggleAvailability"
                        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-semibold shadow text-xs">
                        Ver disponibilidad
                        <component :is="showAvailability ? ChevronUp : ChevronDown" class="w-3 h-3" />
                    </button>
                </div>

                <!-- Lista de disponibilidad -->
                <transition name="fade">
                    <div v-if="showAvailability" class="bg-white rounded-2xl shadow p-4 space-y-2">
                        <h3 class="text-teal-800 font-semibold">Disponibilidad de suministros</h3>
                        <input v-model="searchQuery" type="text" placeholder="Buscar suministro..."
                            class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                        <ul class="divide-y divide-gray-200">
                            <li v-for="item in filteredDatos" :key="item.id" class="py-2 flex justify-between text-xs">
                                <span class="text-gray-700">{{ item.name }}</span>
                                <span class="text-gray-900 font-semibold">{{ item.stock }}</span>
                            </li>
                        </ul>
                        <p v-if="filteredDatos.length === 0" class="text-gray-500 italic">
                            No hay disponibilidad registrada.
                        </p>
                    </div>
                </transition>
                <transition name="fade" v-if="showRejectionReason">
                    <textarea placeholder="motivo de rechazo"
                    v-model="store.reason_reject"
                        class="w-full px-3 pt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </transition>
                <span v-if="showValidationReason"
                    class="text-xs text-red-500">
                    La razón del rechazo debe tener al menos 10 caracteres.
                </span>
                <!-- Errores -->
                <Errors :errors="store.validationErrors" />
                <!-- Botones de acción -->
                <div class="flex justify-between w-full space-x-4">
                    <button @click="reject()"
                        class="bg-red-600 hover:bg-red-700 p-2 rounded-xl text-white flex items-center justify-center font-semibold disabled:opacity-100 disabled:cursor-not-allowed disabled:bg-slate-400"
                        :disabled="store.buttonAuthorization">
                        Rechazar
                        <X class="ml-2 w-5 h-5" />
                    </button>
                    <button @click="store.approbe()" type="button"
                        class="bg-green-600 hover:bg-green-700 p-2 rounded-xl text-white flex items-center justify-center font-semibold disabled:opacity-100 disabled:cursor-not-allowed disabled:bg-slate-400"
                        :disabled="store.buttonAuthorization">
                        Autorizar
                        <Check class="ml-2 w-5 h-5" />
                    </button>
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
    title: 'Gestionar solicitud',
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
            showValidationReason.value = false;
        } else {
            showValidationReason.value = true;
        }
    } else {
        showRejectionReason.value = true;
    }
}
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