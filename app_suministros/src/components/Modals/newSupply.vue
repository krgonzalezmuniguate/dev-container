<template>
  <div>
    <Modal v-model="store.optionNew" :title="modalConfig.title" :subtitle="modalConfig.subtitle"
      :size="modalConfig.size" :show-header="modalConfig.showHeader" :show-footer="modalConfig.showFooter"
      :show-close-button="modalConfig.showCloseButton" :show-cancel-button="modalConfig.showCancelButton"
      :show-confirm-button="modalConfig.showConfirmButton" :cancel-button-text="modalConfig.cancelButtonText"
      :confirm-button-text="modalConfig.confirmButtonText" :confirm-button-class="modalConfig.confirmButtonClass"
      :loading-text="modalConfig.loadingText" :close-on-overlay="modalConfig.closeOnOverlay"
      :close-on-escape="modalConfig.closeOnEscape" :loading="modalConfig.loading"
      :body-classes="modalConfig.bodyClasses" :footer-classes="modalConfig.footerClasses">
      <div class="grid grid-cols-3 gap-0">
        <Input title="nombre" option="label" v-model="store.newRecord.nombre" type="text" minlength="3" maxlength="100"
          @keyup.esc="store.newRecord.nombre = ''" :required="true" />
        <Input title="stock inicial" option="numeric" v-model="store.newRecord.stock" required type="number" min="0"
          @keyup.esc="store.newRecord.stock = ''" />
        <Input title="stock minimo" option="numeric" v-model="store.newRecord.minimo" required type="number" min="1"
          @keyup.esc="store.newRecord.minimo = ''" />
        <Input title="proveedor" option="select" v-model="store.newRecord.proveedor" :datos="main.proveedores"
          values="id" names="name" />
        <Input title="categoria" option="select" v-model="store.newRecord.categoria" :datos="main.categorias"
          values="id" names="name" :required="true" />
        <Input title="unidad de medida" option="select" v-model="store.newRecord.medida" :datos="main.medidas"
          values="id" names="name" :required="true" />
        <div class="col-span-3">
          <Input title="descripción" option="area" v-model="store.newRecord.descripcion" required minlength="8"
            maxlength="250" @keyup.esc="store.newRecord.descripcion = ''" />
        </div>
        <div class="col-span-3">
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
import { onMounted, reactive } from 'vue'
import { useSupplyStore } from '@/stores/supply';
import { useMaintenanceStore } from '@/stores/maintenance';
import { limpiar } from '@/utils/functions'

const store = useSupplyStore();
const main = useMaintenanceStore();
const modalConfig = reactive({
  title: 'Agregar Suministro',
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
});
function cancel() {
  limpiar(store.newRecord);
  store.optionNew = false;
  store.validationErrors = [];
};
</script>

<style scoped></style>