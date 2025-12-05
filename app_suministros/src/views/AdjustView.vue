<template>
    <div>
        <Header />
        <div class="min-h-screen bg-slate-50 p-6">
            <div v-if="store.loadingRecords" class="space-y-6">
                <!-- Header Loading -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <div class="animate-pulse">
                        <div class="h-6 bg-slate-200 rounded w-1/3 mb-4"></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div class="h-4 bg-slate-200 rounded"></div>
                            <div class="h-4 bg-slate-200 rounded"></div>
                            <div class="h-4 bg-slate-200 rounded"></div>
                            <div class="h-4 bg-slate-200 rounded"></div>
                        </div>
                        <div class="h-20 bg-slate-200 rounded"></div>
                    </div>
                </div>

                <!-- Lines Loading -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <div class="animate-pulse">
                        <div class="h-6 bg-slate-200 rounded w-1/4 mb-4"></div>
                        <div class="space-y-3">
                            <div v-for="i in 3" :key="i" class="flex gap-4">
                                <div class="h-10 bg-slate-200 rounded flex-1"></div>
                                <div class="h-10 bg-slate-200 rounded w-24"></div>
                                <div class="h-10 bg-slate-200 rounded w-10"></div>
                            </div>
                        </div>
                        <div class="flex gap-3 mt-6">
                            <div class="h-10 bg-slate-200 rounded w-24"></div>
                            <div class="h-10 bg-slate-200 rounded w-24"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="max-w-4xl mx-auto" v-else>
                <!-- Detalle de Suministros -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-slate-900">Suministros a Ajustar</h2>
                        <button @click="addSupplyLine" :disabled="store.supplyLines.length >= 10"
                            class="px-4 py-2 bg-cyan-600 text-white rounded-md hover:bg-cyan-700 disabled:bg-slate-300 disabled:cursor-not-allowed transition-colors duration-200 text-sm font-medium">
                            Agregar
                        </button>
                    </div>

                    <div class="space-y-3">
                        <div v-for="(line, index) in store.supplyLines" :key="line.id"
                            class="flex items-center gap-4 p-4 bg-slate-50 rounded-lg border border-slate-200">
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-slate-600 mb-1">Suministro</label>
                                <Select v-model="line.supplyId" :options="supplies"
                                    placeholder="Seleccionar suministro..." class="w-full px-3 py-2" />
                            </div>
                            <div class="w-32">
                                <label class="block text-xs font-medium text-slate-600 mb-1">Cantidad</label>
                                <input v-model.number="line.quantity" type="number" min="1"
                                    :max="getMaxQuantity(line.supplyId)"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                    placeholder="0" />
                            </div>

                            <button @click="removeSupplyLine(index)"
                                class="mt-5 p-2 text-red-600 hover:bg-red-50 rounded-md transition-colors duration-200"
                                title="Eliminar línea">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        <div class="flex-1 items-center gap-4 p-4 bg-slate-50 rounded-lg border border-slate-200">
                            <label class="block text-xs font-medium text-slate-600 mb-1">Descripción</label>
                            <textarea v-model="store.description" maxlength="255"
                                class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            </textarea>
                            <p class="text-right text-xs text-slate-500 mt-1">
                                {{ store.description.length }} / 255
                            </p>
                        </div>
                        <div v-if="store.supplyLines.length === 0" class="text-center py-8 text-slate-500">
                            No hay líneas de suministros. Haz clic en "Agregar" para comenzar.
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-200">
                        <button @click=""
                            class="px-6 py-2 border border-slate-300 text-slate-700 rounded-md hover:bg-slate-50 transition-colors duration-200 font-medium">
                            Cancelar
                        </button>
                        <button @click="post()" :disabled="!canSave"
                            class="px-6 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 disabled:bg-slate-300 disabled:cursor-not-allowed transition-colors duration-200 font-medium">
                            Guardar Procesamiento
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <Top />
        <NewRequest />
        <Process />
    </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router';
import { useRequestStore } from '@/stores/request_supplies';
import { useSupplyStore } from '@/stores/supply';
import Header from '@/components/Header.vue';
import Top from '@/components/Top.vue';
import { Plus, Download, Edit, Eye, Trash2, ListRestart, ListTodo } from 'lucide-vue-next'
import NewRequest from '@/components/Modals/newRequest.vue';
import Process from '@/components/Modals/Process.vue';

const store = useRequestStore();
const supply = useSupplyStore();
const route = useRoute();
const router = useRouter();
const url = import.meta.env.VITE_URL_STORAGE;
const showRejectionReason = ref(false)
const showValidationReason = ref(false)
const id_full = computed(() => {
    const date = new Date(store.records.created_at);
    const year = date.getFullYear();
    return store.records.id + '-' + year
})
const supplies = computed(() => {
    return supply.records.filter((item) => item.state === '1').map((item) => ({
        ...item,
        name: `${item.name} ( ${item.stock} )`,
    }));
})
let nextId = 1
function getMaxQuantity(supplyId) {
    const selectedSupply = this.supplies.find(supply => supply.id === supplyId);
    return selectedSupply ? selectedSupply.stock : 99999;
};
// Computed para validar si se puede guardar
const canSave = computed(() => {
    return store.supplyLines.length > 0 &&
        store.supplyLines.every(line => line.supplyId && line.quantity > 0)
})

// Funciones
const addSupplyLine = () => {
    if (store.supplyLines.length < 10) {
        store.supplyLines.push({
            id: nextId++,
            supplyId: '',
            quantity: 1
        })
    }
}

const removeSupplyLine = (index) => {
    store.supplyLines.splice(index, 1)
}

// Agregar una línea inicial
addSupplyLine()
onMounted(() => {
    store.id = route.params.id;
    store.show();
    supply.index();
});

async function post() {
    const response = await store.processing();
    if (response === true) {
        router.push({ name: 'manager' });
    }
}
async function reject() {
    if (showRejectionReason.value) {
        if (store.reason_reject.length >= 10) {
            const response = await store.reject(1);
            if (response === true) {
                router.push({ name: 'manager' });
            }
        } else {
            showValidationReason.value = true;
        }
    } else {
        showRejectionReason.value = true;
    }
};
</script>

<style scoped>
@keyframes shimmer {
    0% {
        transform: translateX(-100%);
    }

    100% {
        transform: translateX(100%);
    }
}

.animate-shimmer {
    animation: shimmer 2s infinite;
}
</style>