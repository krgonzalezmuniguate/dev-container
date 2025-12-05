<template>
    <div>
        <Header />
        <div class="min-h-screen bg-gray-50 p-6" v-if="!store.loadingRecords">
            <div class="font-extrabold text-4xl text-center mb-10">Mis Solicitudes</div>
            <div class="max-w-7xl mx-auto">
                <!-- Ejemplo básico -->
                <div class="flex justify-center gap-3 mb-1">
                    <Tooltip message="Crear un nueva solicitud" position="top">
                        <button @click="store.options(1, null)"
                            class="rounded-full bg-green-500 border-2 border-green-600 h-10 w-10 flex justify-center items-center text-white font-bold">
                            <Plus class="w-4 h-4" />
                        </button>
                    </Tooltip>
                    <Tooltip message="Recargar datos" position="top">
                        <button @click="store.show_by_user()"
                            class="rounded-full bg-yellow-500 boder-2 border-yellow-600 h-10 w-10 flex justify-center items-center text-white font-bold">
                            <ListRestart class="w-4 h-4" />
                        </button>
                    </Tooltip>
                </div>
                <div class="mb-8">
                    <DataTable :data="store.records" :headers="store.headers">
                        <template #state="{ item }">
                            <span
                                :class="[item.status === 'pending' ? 'bg-teal-500' : item.status === 'authorized' ? 'bg-green-500' : item.status === 'processed' ? 'bg-teal-500' : 'bg-red-500']"
                                class="px-2 rounded-xl text-white font-bold">{{ item.state }}</span>
                        </template>
                        <!-- Template para acciones -->
                        <template #actions="{ item }">
                            <div class="flex items-center justify-center gap-2">
                                <Tooltip message="Ver documento" position="top" v-if="item.path_file">
                                    <button @click="downloadPdf(item.id)"
                                        class="text-blue-700 hover:text-blue-900 p-1 font-bold" title="Eliminar">
                                        <BookText class="w-4 h-4" />
                                    </button>
                                </Tooltip>
                                <Tooltip message="Eliminar" position="top" v-if="item.status === 'pending'">
                                    <button @click="store.options(3, item)" class="text-red-600 hover:text-red-800 p-1"
                                        title="Eliminar">
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </Tooltip>
                            </div>
                        </template>
                    </DataTable>
                </div>
            </div>
        </div>
        <div  v-else>
            <Skeleton :columns="6" :rows="6" :compact="false" />
        </div>
        <Top />
        <NewRequest />
        <DeleteRequest />
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRequestStore } from '@/stores/request_supplies';
import Header from '@/components/Header.vue';
import Top from '@/components/Top.vue';
import { Plus, Download, Edit, Eye, Trash2, ListRestart, ListTodo, BookText } from 'lucide-vue-next'
import DeleteRequest from '@/components/Modals/deleteRequest.vue';
import NewRequest from '@/components/Modals/newRequest.vue';
import pdfService from '@/services/pdfService.js';
// Estado reactivo
const store = useRequestStore();
const notification = ref('')
const url = import.meta.env.VITE_URL_STORAGE;
function downloadPdf(id) {
    pdfService.downloadSolicitud(id);
}
onMounted(() => {
    store.show_by_user();
});
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