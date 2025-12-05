<template>
    <div>
        <Header />
        <Breadcrumbs :breadcrumbs="breadcrumbs" class="mt-2 ml-5" />
        <div class="p-5" v-if="!store.loadingRecords">
            <div class="mt-3 bg-white shadow-all rounded-lg pt-10">
                <div class="w-full flex justify-center gap-2">
                    <Tooltip message="Nuevo insumo" position="top">
                        <button @click="toGo(null, 2)"
                            class="rounded-full bg-green-500 border-2 border-green-600 h-10 w-10 flex justify-center items-center text-white font-bold">
                            <Plus class="w-4 h-4" />
                        </button>
                    </Tooltip>
                    <Tooltip message="Recargar datos" position="top">
                        <button @click="store.index()"
                            class="rounded-full bg-yellow-500 boder-2 border-yellow-600 h-10 w-10 flex justify-center items-center text-white font-bold">
                            <ListRestart class="w-4 h-4" />
                        </button>
                    </Tooltip>
                    
                </div>
                <DataTable :data="store.records" :headers="store.headers" :loading="store.loadingRecords">
                    <template #state_format="{ item }">
                        <div class="rounded-full text-white font-bold px-2"
                            :class="[item.state_format == 'ACTIVO' ? 'bg-green-500' : 'bg-red-500']">{{
                                item.state_format }}</div>
                    </template>
                    <template #actions="{ item }">
                        <div class="flex items-center">
                            <Tooltip message="Ver detalle" position="top">
                                <Eye class="hover:scale-110 cursor-pointer text-orange-600 mt-4 text-2xl"
                                    @click="toGo(item.id, 1)" />
                            </Tooltip>
                            <Tooltip message="Editar" position="top" v-if="item.state_format == 'ACTIVO'">
                                <Edit class="hover:scale-110 cursor-pointer text-sky-500 mt-4 text-2xl"
                                    @click="toGo(item.id, 3)" />
                            </Tooltip>
                            <Tooltip message="Eliminar" position="top" v-if="item.state_format == 'ACTIVO'">
                                <Trash2 class="hover:scale-110 cursor-pointer text-red-500 mt-4 text-2xl"
                                    @click="store.options(2, item)" />
                            </Tooltip>
                        </div>
                    </template>
                </DataTable>
            </div>
        </div>
        <div v-else
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
            <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col items-center">
                <svg class="animate-spin h-10 w-10 text-blue-500 mb-4" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle>
                    <path d="M12 2a10 10 0 0 1 10 10" stroke-opacity="0.75"></path>
                </svg>
                <p class="text-gray-700 font-semibold">Cargando el registro</p>
            </div>
        </div>
        <DeleteSupply />
    </div>
    <Top />
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useSupplyStore } from '@/stores/supply';
import { Plus, Download, Edit, Eye, Trash2, ListRestart, ListTodo, ArrowUpDown } from 'lucide-vue-next'
import Header from '@/components/Header.vue';
import Top from '@/components/Top.vue';
import DeleteSupply from '@/components/Modals/deleteSupply.vue';


const store = useSupplyStore();
const router = useRouter();
const breadcrumbs = [
    { name: 'Pagina principal', path: '/dai/home', icon: 'fas fa-home' },
    { name: 'Suministros', path: '' }
];
function toGo(id, type) {
    if (type === 1) {
        router.push({ name: 'supply', params: { id: id } });
    } else if (type === 3) {
        router.push({ name: 'update_supply', params: { id: id } });
    } else {
        router.push({ name: 'new_supply' });
    }
}
onMounted(() => {
    store.index();
});
</script>