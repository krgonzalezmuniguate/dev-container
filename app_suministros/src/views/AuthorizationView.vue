<template>
    <div>
        <Header />
        <div class="min-h-screen bg-gray-50 p-6" v-if="!store.loadingRecords">
            <div class="font-extrabold text-4xl text-center mb-10">Solicitudes por revisar</div>
            <div class="max-w-7xl mx-auto">
                <!-- Ejemplo básico -->
                <div class="flex justify-center gap-3 mb-1">
                    <!-- <Tooltip message="Crear un nueva solicitud" position="top">
                        <button @click="store.options(1, null)"
                            class="rounded-full bg-green-500 border-2 border-green-600 h-10 w-10 flex justify-center items-center text-white font-bold">
                            <Plus class="w-4 h-4" />
                        </button>
                    </Tooltip> -->
                    <Tooltip message="Recargar datos" position="top">
                        <button @click="store.show_to_chiefs()"
                            class="rounded-full bg-yellow-500 boder-2 border-yellow-600 h-10 w-10 flex justify-center items-center text-white font-bold">
                            <ListRestart class="w-4 h-4" />
                        </button>
                    </Tooltip>
                </div>
                <div class="mb-8">
                    <DataTable :data="store.records" :headers="store.headers">
                        <template #state="{ item }">
                            <span
                                :class="[item.status === 'pending' ? 'bg-sky-500' : item.status === 'authorized' ? 'bg-green-500' : item.status === 'processed' ? 'bg-teal-500' : 'bg-red-500']"
                                class="px-2 rounded-xl text-white font-bold">{{ item.state }}</span>
                        </template>
                        <!-- Template para acciones -->
                        <template #actions="{ item }">
                            <div class="flex items-center justify-center gap-2">
                             <Tooltip message="Ver solicitud" position="top">
                                    <button @click="store.options(6, item)"
                                        class="text-orange-700 hover:text-orange-900 p-1 font-bold" title="Eliminar">
                                        <Eye class="w-4 h-4" />
                                    </button>
                                </Tooltip>
                                <Tooltip message="Ver documento" position="top" v-if="item.path_file">
                                    <button @click="downloadPdf(item.id)"
                                        class="text-blue-700 hover:text-blue-900 p-1 font-bold" title="Eliminar">
                                        <BookText class="w-4 h-4" />
                                    </button>
                                </Tooltip>
                                <Tooltip message="Autorizar/Rechazar" position="top">
                                    <button @click="store.options(4, item)"
                                        class="text-teal-700 hover:text-teal-900 p-1 font-bold"
                                        v-if="item.status === 'pending'" title="Eliminar">
                                        <ListTodo class="w-4 h-4" />
                                    </button>
                                </Tooltip>
                            </div>
                        </template>
                    </DataTable>
                </div>

                <!-- Notificaciones -->
                <div v-if="notification"
                    class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
                    {{ notification }}
                </div>
            </div>
        </div>
        <div class="w-full space-y-4 p-6" v-else>
            <!-- Header con título y controles skeleton -->
            <div class="flex items-center justify-between mb-6">
                <div class="space-y-2">
                    <div class="h-8 bg-gray-200 rounded-md w-48 animate-pulse relative overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer">
                        </div>
                    </div>
                    <div class="h-4 bg-gray-200 rounded-md w-32 animate-pulse relative overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer">
                        </div>
                    </div>
                </div>

                <!-- Controles de búsqueda y filtros skeleton -->
                <div class="flex items-center space-x-3">
                    <div class="h-10 bg-gray-200 rounded-md w-64 animate-pulse relative overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer">
                        </div>
                    </div>
                    <div class="h-10 bg-gray-200 rounded-md w-24 animate-pulse relative overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla skeleton -->
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                <!-- Header de la tabla skeleton -->
                <div class="bg-gray-50 border-b">
                    <div class="grid grid-cols-12 gap-4 p-4">
                        <div v-for="col in 4" :key="col.id" :class="col.class">
                            <div class="h-4 bg-gray-300 rounded animate-pulse relative overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filas de datos skeleton -->
                <div class="divide-y divide-gray-200">
                    <div v-for="row in 2" :key="row" class="grid grid-cols-12 gap-4 p-4 hover:bg-gray-50">
                        <div v-for="col in 4" :key="`${row}-${col.id}`" :class="col.class">
                            <div class="h-4 bg-gray-200 rounded animate-pulse relative overflow-hidden"
                                :style="{ width: col.id === 2 ? '80%' : col.id === 3 ? '90%' : '70%' }">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paginación skeleton -->
            <div class="flex items-center justify-between pt-4">
                <div class="h-4 bg-gray-200 rounded w-32 animate-pulse relative overflow-hidden">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer">
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <div v-for="page in 5" :key="page"
                        class="h-8 w-8 bg-gray-200 rounded animate-pulse relative overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent animate-shimmer">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Indicador de carga central simple -->
            <div class="flex items-center justify-center py-8">
                <div class="flex items-center space-x-3">
                    <div class="w-6 h-6 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-gray-600 animate-pulse">Cargando datos...</span>
                </div>
            </div>

            <!-- Puntos de carga animados -->
            <div class="flex justify-center space-x-2 pt-4">
                <div v-for="dot in 3" :key="dot" class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" :style="{
                    animationDelay: `${(dot - 1) * 0.2}s`,
                    animationDuration: '1s',
                }"></div>
            </div>
        </div>
        <Top />
        <NewRequest />
        <Authorization />
        <View />
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRequestStore } from '@/stores/request_supplies';
import Header from '@/components/Header.vue';
import Top from '@/components/Top.vue';
import NewRequest from '@/components/Modals/newRequest.vue';
import Authorization from '@/components/Modals/Authorization.vue'
import View from '@/components/Modals/View.vue'
import pdfService from '@/services/pdfService.js';
import { Plus, Download, Edit, Eye, BookText, ListRestart, ListTodo } from 'lucide-vue-next'
// Estado reactivo
const store = useRequestStore();
const notification = ref('')
const url = import.meta.env.VITE_URL_STORAGE;
function downloadPdf(id) {
    pdfService.downloadSolicitud(id);
}
onMounted(() => {
    store.show_to_chiefs();
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