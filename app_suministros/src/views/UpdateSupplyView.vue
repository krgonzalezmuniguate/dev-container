<template>
    <Header />
    <div>
        <Breadcrumbs :breadcrumbs="breadcrumbs" class="mt-10 ml-5" />
        <form class="grid grid-cols-3 gap-0 mt-5 mx-5" @submit.prevent="submitForm()" v-if="!store.loadingRecord">
            <div class="flex flex-col space-y-1">
                <Input title="nombre" option="label" v-model="store.record.name" type="text" minlength="3"
                    :error-message="errors.name" maxlength="100" @keyup.esc="store.record.name = ''"
                    :required="true" />
            </div>
            <Input title="stock actual" option="numeric" v-model="store.record.stock" required type="number" min="0"
                @keyup.esc="store.record.stock = ''" :error-message="errors.stock" disabled readonly/>
            <Input title="stock minimo" option="numeric" v-model="store.record.minimum_stock" required type="number"
                min="1" @keyup.esc="store.record.minimum_stock = ''" :error-message="errors.minimum_stock" />
            <Input title="stock maximo" option="numeric" v-model="store.record.maximum_stock" required type="number"
                min="1" @keyup.esc="store.record.maximum_stock = ''" :error-message="errors.maximum_stock" />
            <Input title="proveedor" option="select" v-model="store.record.provider_id" :datos="main.proveedores"
                values="id" names="name" :reduce="item => item.id" />
            <Input title="categoria" option="select" v-model="store.record.supply_category_id"
                :datos="main.categorias" values="id" names="name" :required="true" :reduce="item => item.id"
                :error-message="errors.supply_category_id" />
            <div class="col-span-2">
                <Input title="unidad de medida" option="select" v-model="store.record.measurement_unit_id"
                    :datos="main.medidas" values="id" names="name" :required="true" :reduce="item => item.id"
                    :loading="main.loading_medidas" />
            </div>
            <Input title="tipo de adquisición" option="select" v-model="store.record.type_acquisition" :datos="types"
                values="name" names="name" :reduce="item => item.name" :required="true" :loading="false" />
            <div class="ml-2 col-span-3">
                <label class="font-bold">¿El suministro tiene fecha de caducidad?</label>
                <div class="flex gap-3 my-1.5">
                    <div class="flex gap-1">
                        <label>SÍ</label>
                        <input name="fecha" type="radio" value="true" v-model="date" />
                    </div>
                    <div class="flex gap-1">
                        <label>NO</label>
                        <input name="fecha" type="radio" value="false" v-model="date"
                            :error-message="errors.supply_category_id" />
                    </div>
                </div>
            </div>
            <div class="col-span-3 mt-3" v-if="date === true">
                <Input option="date" title="Fecha de caducidad" v-model="store.record.expiration_date"
                    :error-message="errors.expiration_date" />
            </div>
            <div class="col-span-3">
                <Input title="descripción" option="area" v-model="store.record.description" required minlength="8"
                    maxlength="250" @keyup.esc="store.record.description = ''" />
            </div>
            <div class="col-span-3 my-2">
                <Errors :errors="store.validationErrors" />
            </div>
            <div class="col-span-3 flex justify-between">
                <button type="button" :disabled="store.buttonUpdate"
                    class="bg-red-500 rounded-xl text-white font-semibold px-2 py-1 flex items-center justify-center gap-1">Cancelar
                    <X class="h-4 w-4" />
                </button>
                <button type="submit" :disabled="store.buttonUpdate"
                    class="bg-green-500 rounded-xl text-white font-semibold px-2 py-1 flex items-center justify-center gap-1 disabled:bg-slate-400 disabled:cursor-not-allowed">Enviar
                    <Save class="h-4 w-4" />
                </button>
            </div>
        </form>
    </div>
    <Top />
</template>

<script setup>
import { onMounted, ref, reactive, computed, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router';
import { useSupplyStore } from '@/stores/supply';
import { useMaintenanceStore } from '@/stores/maintenance';
import { Save, X } from 'lucide-vue-next';
import Header from '@/components/Header.vue';
import Top from '@/components/Top.vue';
const store = useSupplyStore();
const router = useRouter();
const route = useRoute();
const main = useMaintenanceStore();
const breadcrumbs = [
    { name: 'Pagina principal', path: '/dai/home', icon: 'fas fa-home' },
    { name: 'Suministros', path: '/supplies' },
    { name: 'Actualizar suministro', path: '' }
];
const types = [
    { name: 'arrendamiento' },
    { name: 'compra' },
    { name: 'donacion' },
    { name: 'traslado' },
];
const errors = reactive({
    name: '',
    stock: null,
    minimum_stock: null,
    maximum_stock: null,
    supply_category_id: null,
    expiration_date: null,
    description: ''
});
async function submitForm() {
    const response = await store.update();
    if (response === true) {
        router.push('/supplies');
    }
};
const date = computed(()=>{
    if(store.record.expiration_date){
        return true;
    }else{
        return false;
    }
})
watch(
    () => route.params.id,
    (newId) => {
        console.log('Nuevo ID detectado:', newId);
        store.id = newId;
        store.show();
    }
);
onMounted(() => {
    store.id = route.params.id;
    store.show();
    main.index_medida()
    main.index_categoria()
    main.index_proveedor()
});
</script>

<style scoped></style>