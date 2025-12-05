<template>
  <Header />
  <div>
    <Breadcrumbs :breadcrumbs="breadcrumbs" class="mt-10 ml-5" />
    <div class="p-5" v-if="!store.loadingRecord">
      <div class="mt-3 bg-white shadow-all rounded-lg pt-10">
        <div class="w-full grid md:grid-cols-2 lg:grid-cols-3 px-10 leading-[3]">
          <div class="font-semibold">Nombre: <span class="font-normal italic">{{ store.record?.name ?? '' }}</span>
          </div>
          <div class="font-semibold">Descripción: <span class="font-normal italic">{{ store.record?.description ?? ''
              }}</span></div>
          <div class="font-semibold">STOCK Actual: <span class="font-normal italic">{{ store.record?.stock ?? ''
              }}</span></div>
          <div class="font-semibold">STOCK Minimo: <span class="font-normal italic">{{ store.record?.stock ?? ''
              }}</span></div>
          <div class="font-semibold">Categoría: <span class="font-normal italic">{{ store.record?.category ?? ''
              }}</span></div>
          <div class="font-semibold">Unidad de medida: <span class="font-normal italic">{{ store.record?.measures ?? ''
              }}</span></div>
          <div class="font-semibold">Fecha de creación: <span class="font-normal italic">{{
            store.record?.created_at_format ?? '' }}</span></div>
          <div class="font-semibold">Estado: <span class="font-normal italic">{{ store.record?.state_format ?? ''
              }}</span></div>
        </div>
      </div>
      <div class="mt-3 bg-white shadow-all rounded-lg pt-10">
        <div v-if="store.record.movements">
          <DataTable :data="store.record.movements" :headers="headers"
            :loading="store.loading_movimientos" />
        </div>
      </div>
    </div>
    <div v-else
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
      <div class="bg-white p-6 rounded-2xl shadow-lg flex flex-col items-center">
        <svg class="animate-spin h-10 w-10 text-blue-500 mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle>
          <path d="M12 2a10 10 0 0 1 10 10" stroke-opacity="0.75"></path>
        </svg>
        <p class="text-gray-700 font-semibold">Cargando el registro</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { useSupplyStore } from '@/stores/supply';
import Header from '@/components/Header.vue';
const route = useRoute();
const store = useSupplyStore();

const breadcrumbs = [
  { name: 'Pagina principal', path: '/dai/home', icon: 'fas fa-home' },
  { name: 'Suministros', path: '/supplies' },
  { name: 'Suministro', path: '' }
];
const headers = ref([
  {
    title: 'ID',
    align: 'left',
    key: 'id'
  },
  {
    title: 'TIPO DE MOVIMIENTO',
    align: 'left',
    key: 'type_format'
  },
  {
    title: 'CANTIDAD',
    align: 'left',
    key: 'quantity'
  },
  {
    title: 'DESCRIPCIÓN',
    align: 'left',
    key: 'description'
  },
  {
    title: 'NO. SOLICITUD',
    align: 'left',
    key: 'request_id'
  },
{
    title: 'FECHA DE MOVIMIENTO',
    align: 'left',
    key: 'created_at_format'
  },
])
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
});
</script>