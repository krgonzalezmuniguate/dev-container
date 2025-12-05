<template>
  <div class="bg-white rounded-2xl z-10 shadow-lg p-5">
    <section class="mx-auto mb-1">
      <div class="mt-2 md:flex md:items-center md:justify-between">
        <div class="flex items-center px-2 py-1.5 rounded-2xl shadow-xl border">
          <span>Mostrar</span>
          <select v-model="rowsPerPage" @change="resetPage"
            class="text-center bg-white font-bold w-full focus:outline-none ring-0">
            <option>10</option>
            <option>25</option>
            <option>50</option>
            <option>100</option>
          </select>
          <span>registros</span>
        </div>
        <div class="relative flex items-center mt-4 md:mt-0 gap-2">
          <div class="flex gap-2">
            <Tooltip message="Generar excel" position="top">
              <button
                class="rounded-full bg-green-500 h-10 w-10 flex justify-center items-center text-white font-bold disabled:bg-slate-400 disabled:cursor-not-allowed"
                @click="exportData()" :disabled="loadingExportData">
                <Sheet class="w-4 h-4" />
              </button>
            </Tooltip>
            <Tooltip message="Generar pdf" position="top">
              <button
                class="rounded-full bg-red-500 h-10 w-10 flex justify-center items-center text-white font-bold disabled:bg-slate-400 disabled:cursor-not-allowed"
                @click="exportDataPDF()" :disabled="loadingExportData">
                <FileText class="w-4 h-4" />
              </button>
            </Tooltip>
          </div>

          <input v-model="search" type="search" placeholder="Buscar"
            class="block w-full py-1.5 pr-12 text-gray-700 bg-white border shadow-lg border-gray-200 rounded-2xl md:w-80 placeholder-gray-400/70 pl-5 rtl:pr-5 rtl:pl-11 focus:border-blue-400 focus:ring-blue-300 focus:outline-none focus:ring focus:ring-opacity-40">
          <span class="absolute inset-y-0 right-0 flex items-center pr-3"
            @click="showAdvancedFilters = !showAdvancedFilters">
            <div class="bg-[#FCD436] h-6 w-6 flex items-center justify-center rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5 text-gray-800 rotate-90">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
              </svg>
            </div>
          </span>
        </div>
      </div>
      <!-- nuevo-->
      <div class="mt-4 flex justify-center">
        <div v-show="showAdvancedFilters" class="space-y-2 border border-gray-200 p-4 rounded-xl bg-gray-100">
          <div v-for="(filter, index) in advancedFilters" :key="index" class="flex flex-wrap gap-2 items-center">
            <select v-model="filter.key" class="border px-2 py-1 rounded-md text-sm">
              <option disabled value="">Campo</option>
              <option v-for="h in headers" :key="h.key" :value="h.key">{{ h.title }}</option>
            </select>

            <select v-model="filter.operator" class="border px-2 py-1 rounded-md text-sm">
              <option value="contains">Contiene</option>
              <option value="equals">Igual</option>
              <option value="startsWith">Empieza con</option>
              <option value="endsWith">Termina con</option>
              <option value=">">Mayor que</option>
              <option value="<">Menor que</option>
            </select>

            <input v-model="filter.value" type="text" placeholder="Valor"
              class="border px-2 py-1 rounded-md text-sm w-48" />

            <button @click="removeAdvancedFilter(index)" class="text-red-500 text-xs hover:underline">
              <X class="w-4 h-4" />
            </button>
          </div>

          <div class="flex gap-2">
            <button @click="addAdvancedFilter" class="text-green-600 text-sm hover:underline">+ Agregar
              filtro</button>
            <!-- <button @click="applyAdvancedFilters"
                  class="bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600">Aplicar filtros</button>
                <button @click="clearAdvancedFilters" class="text-gray-500 text-sm hover:underline">Limpiar</button> -->
          </div>
        </div>
      </div>
      <!-- nuevo-->
      <div class="flex flex-col">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="inline-block min-w-full py-5 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden border-2 border-gray-200 shadow-lg md:rounded-lg">
              <table v-if="!isMobile" class="min-w-full divide-y divide-gray-200">
                <thead>
                  <tr :class="color">
                    <th v-for="(head, index) in props.headers" :key="index" @click="(head.sort) ? sort(head.key) : ''"
                      scope="col" class="px-4 py-3.5 text-sm font-semibold rtl:text-right cursor-pointer"
                      :width="head.width" :align="head.align ?? 'left'">
                      {{ head.title }}
                      <span v-if="sortColumn === head.key">
                        {{ sortDir === 'asc' ? '▲' : '▼' }}
                      </span>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="item in paginatedData" :key="item.id" class="hover:bg-gray-100 text-gray-800">
                    <td v-for="(head, headIndex) in headers" :key="headIndex" class="px-4 py-2" :align="head.align"
                      :width="head.width">
                      <slot :name="head.key" :item="item">
                        <p class="text-sm font-normal">{{ getObjectValue(item, head.key) }}</p>
                      </slot>
                    </td>
                  </tr>
                  <tr v-if="paginatedData == 0">
                    <td :colspan="headers.length" class="text-center py-4 text-gray-500">
                      No hay datos para mostrar
                    </td>
                  </tr>
                </tbody>
              </table>
              <div v-else>
                <div v-for="item in paginatedData" :key="item.id"
                  class="p-4 mb-2 border rounded-lg shadow-md divide-y divide-gray-200">
                  <div v-for="(head, headIndex) in headers" :key="headIndex" class="mb-2 flex items-center">
                    <strong class="w-1/3 text-xs">{{ head.title }}:</strong>
                    <span class="ml-2 w-2/3">
                      <slot :name="head.key" :item="item">
                        <p class="text-xs font-normal">{{ getObjectValue(item, head.key) }}</p>

                      </slot>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="flex items-center justify-between">
        <!-- RESPONSIVE MOBILE BUTTONS -->
        <div class="flex flex-1 justify-between md:hidden">
          <a @click="(currentPage == 1) ? currentPage = 1 : currentPage--"
            class="relative inline-flex items-center rounded-md border bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
            Anterior
          </a>
          <a @click="(currentPage == totalPages) ? currentPage = totalPages : currentPage++"
            class="relative ml-3 inline-flex items-center rounded-md border bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
            Siguiente
          </a>
        </div>
        <div class="hidden md:flex md:flex-1 sm:items-center sm:justify-between select-none">
          <div>
            <p class="text-xs text-gray-500">
              Mostrando
              <span class="font-medium">{{ currentEndIndexDisplay }}</span>
              de
              <span class="font-medium">{{ currentFilteredDataLength }}</span>
              resultados
            </p>
          </div>
          <div v-show="filteredData.length >= rowsPerPage && displayedPages.length > 1">
            <nav aria-label="Pagination">
              <!-- Botón anterior -->
              <a @click="currentPage > 1 && currentPage--" class="justify-center items-center space-x-2 px-1.5"
                :disabled="currentPage === 1">
                <span class="sr-only">Previous</span>
                <span class="w-6 h-6 px-4 py-2 border rounded-full bg-gray-100"
                  :class="[currentPage === 1 ? 'hidden' : '']">&laquo;</span>
              </a>
              <!-- Página 1 (solo si no está en displayedPages) -->
              <a v-if="currentPage !== 1 && !displayedPages.includes(1)" @click="setCurrentPage(1)"
                class="cursor-pointer px-4 py-2 border rounded-full"
                :class="[currentPage === 1 ? 'bg-blue-500 text-white' : 'text-black']">
                1
              </a>
              <!-- Páginas intermedias -->
              <a v-for="page in displayedPages" :key="page" @click="setCurrentPage(page)"
                class="px-4 py-2 border rounded-full"
                :class="page === currentPage ? 'bg-blue-500 text-white' : 'text-black'">
                {{ page }}
              </a>
              <!-- Puntos suspensivos -->
              <span v-if="displayedPages.length && displayedPages[displayedPages.length - 1] < totalPages - 1">
                ...
              </span>
              <!-- Última página (solo si no está en displayedPages) -->
              <a v-if="currentPage !== totalPages && !displayedPages.includes(totalPages)"
                @click="setCurrentPage(totalPages)" class="cursor-pointer px-4 py-2 border rounded-full"
                :class="[currentPage === totalPages ? 'bg-blue-500 text-white' : 'text-black']">
                {{ totalPages }}
              </a>
              <!-- Botón siguiente -->
              <a @click="currentPage < totalPages && currentPage++" class="justify-center items-center space-x-2">
                <span class="sr-only">Next</span>
                <span class="w-6 h-6 px-4 py-2 border rounded-full bg-gray-100"
                  :class="[currentPage === totalPages ? 'hidden' : '']">&raquo;</span>
              </a>
            </nav>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue';
import axios from 'axios';
import { useGlobalStore } from '@/stores/global';
const global = useGlobalStore();
const search = ref('');
const startIndex = ref(1);
const endIndex = ref(1);
const currentPage = ref(1);
const rowsPerPage = ref(25);
const searchables = [];
const sortColumn = ref(null);
const sortDir = ref('asc');
const isMobile = ref(false);
const loadingExportData = ref(false)
const props = defineProps({
  headers: null,
  data: null,
  color: {
    default: 'bg-[#F2FAFF] text-[#6F7582]'
  },
  loading: true
});
watch(search, () => {
  currentPage.value = 1;
});
import { Plus, Download, Edit, Eye, Trash2, Package, Copy, Sheet, FileText, X } from 'lucide-vue-next'
const data = computed(() => props.data);
const showAdvancedFilters = ref(false);

const advancedFilters = ref([
  { key: '', operator: 'contains', value: '' }
]);

function addAdvancedFilter() {
  advancedFilters.value.push({ key: '', operator: 'contains', value: '' });
}

function removeAdvancedFilter(index) {
  advancedFilters.value.splice(index, 1);
}

function clearAdvancedFilters() {
  advancedFilters.value = [{ key: '', operator: 'contains', value: '' }];
  currentPage.value = 1;
}

function applyAdvancedFilters() {
  currentPage.value = 1;
}
const checkScreenSize = () => {
  isMobile.value = window.innerWidth <= 640;
};

if (props.headers) {
  props.headers.map(el => {
    searchables.push(el.key.trim());
  });
}
const exportData = async () => {

  loadingExportData.value = true

  try {

    const response = await axios.post('exportar-excel',
      {
        columns: props.headers,
        data: filteredData.value
      },
      {
        responseType: 'blob'
      })

    const url = window.URL.createObjectURL(new Blob([response.data]));

    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', 'export.xlsx')

    document.body.appendChild(link)
    link.click();

    window.URL.revokeObjectURL(url)
    document.body.removeChild(link)


  } catch (error) {

    global.setAlert('Error al exportar la data', 'danger')

  } finally {

    loadingExportData.value = false
  }
}
const exportDataPDF = async () => {

  loadingExportData.value = true

  try {
    const type = import.meta.env.VITE_NAME_APP;
    const response = await axios.post('exportar-pdf',
      {
        columns: props.headers,
        data: filteredData.value,
        type: type,
        version: import.meta.env.VITE_VERSION
      },
      {
        responseType: 'blob'
      })

    const url = window.URL.createObjectURL(new Blob([response.data]));
    const fecha = new Date();
    const ano = fecha.getFullYear();
    const mes = String(fecha.getMonth() + 1).padStart(2, '0');
    const dia = String(fecha.getDate()).padStart(2, '0');
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', `${type}-${dia}-${mes}-${ano}.pdf`)

    document.body.appendChild(link)
    link.click();

    window.URL.revokeObjectURL(url)
    document.body.removeChild(link)


  } catch (error) {

    global.setAlert('Error al exportar la data', 'danger')

  } finally {

    loadingExportData.value = false
  }
}
const filteredData = computed(() => {
  let items = Array.isArray(sortedItems.value) ? sortedItems.value : [];

  // Filtros de búsqueda rápida
  const searchTerms = search.value.trim().split(';').map(term => term.trim().toLowerCase());
  if (searchTerms.length > 0 && searchTerms[0] !== '') {
    items = items.filter(item =>
      searchTerms.every(searchTerm =>
        searchables.some(column =>
          String(getObjectValue(item, column)).toLowerCase().includes(searchTerm)
        )
      )
    );
  }

  // Filtros avanzados
  advancedFilters.value.forEach(filter => {
    const key = filter.key;
    const value = filter.value.toLowerCase().trim();
    const operator = filter.operator;

    if (!key || !value) return;

    items = items.filter(item => {
      const itemValue = String(getObjectValue(item, key)).toLowerCase();

      switch (operator) {
        case 'contains':
          return itemValue.includes(value);
        case 'equals':
          return itemValue === value;
        case 'startsWith':
          return itemValue.startsWith(value);
        case 'endsWith':
          return itemValue.endsWith(value);
        case '>':
          return itemValue > value;
        case '<':
          return itemValue < value;
        default:
          return true;
      }
    });
  });

  return items;
});

function getObjectValue(object, key) {
  const keys = key.split('.');
  return keys.reduce((value, currentKey) => {
    return value && value[currentKey];
  }, object);
}
const paginatedData = computed(() => {
  const currentPg = Number(currentPage.value || 1);
  const rwsPerPage = Number(rowsPerPage.value || 1);

  const effectiveRowsPerPage = rwsPerPage > 0 ? rwsPerPage : 1;

  const startIndexCalc = (currentPg - 1) * effectiveRowsPerPage;
  const endIndexCalc = startIndexCalc + effectiveRowsPerPage;

  // Asegura que los índices sean enteros no negativos
  const start = Math.max(0, Math.floor(startIndexCalc));
  const end = Math.max(0, Math.floor(endIndexCalc));

  // --- Validación extra para filteredData.value justo antes de slice ---
  if (!Array.isArray(filteredData.value)) {
    console.error("Error: filteredData.value no es un array antes de slice. Valor actual:", filteredData.value);
    return [];
  }

  // --- NUEVO: Asegurarse de que endIndex y filteredData.length sean numéricos y válidos para el template ---
  // Puedes crear variables computadas separadas o al menos asegurarte de que los valores sean limpios.
  // Aunque ya deberíamos tener 'end', vamos a usarlo con precaución.
  // Si necesitas 'endIndex' en el template, asegúrate de que sea una ref o computed válida
  // Y siempre un número.

  // No necesitas asignar a startIndex.value y endIndex.value si solo los usas aquí.
  // Si los usas en el template directamente, asegúrate de que sean refs.
  // Por ejemplo:
  // startIndex.value = start; // Si startIndex es una ref
  // endIndex.value = end;     // Si endIndex es una ref

  return filteredData.value.slice(start, end);
});

// --- NUEVO: Crea computed properties para los valores del template ---
// Esto hace que los valores sean reactivos y seguros para el template.
const currentEndIndexDisplay = computed(() => {
  // Aseguramos que endIndex (calculado en paginatedData) y filteredData.length sean números.
  const effectiveEndIndex = Number(endIndex.value); // Asumiendo endIndex es una ref
  const effectiveFilteredDataLength = Number(filteredData.value.length);

  // Si por alguna razón no son números válidos, devolver 0 o el valor que consideres seguro.
  if (isNaN(effectiveEndIndex) || isNaN(effectiveFilteredDataLength)) {
    return 0; // O un valor seguro para mostrar, o incluso lanzar un error de depuración aquí
  }

  return Math.min(effectiveEndIndex, effectiveFilteredDataLength);
});

const currentFilteredDataLength = computed(() => {
  // Asegura que sea un array y luego su longitud
  return Array.isArray(filteredData.value) ? filteredData.value.length : 0;
});
const totalPages = computed(() => {
  const divisor = rowsPerPage.value === 0 ? 1 : rowsPerPage.value; // Evita la división por cero
  return Math.ceil(filteredData.value.length / divisor);
});

const setCurrentPage = page => {
  currentPage.value = page;
};

const displayedPages = computed(() => {
  const totalDisplayedPages = 6;
  const halfDisplayedPages = Math.floor(totalDisplayedPages / 2);
  let startPage = Math.max(currentPage.value - halfDisplayedPages, 1);
  let endPage = Math.min(startPage + totalDisplayedPages - 1, totalPages.value);

  if (endPage - startPage + 1 < totalDisplayedPages) {
    startPage = Math.max(endPage - totalDisplayedPages + 1, 1);
  }

  return Array(endPage - startPage + 1).fill().map((_, index) => startPage + index);
});

function sort(column) {
  if (sortColumn.value === column) {
    sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortColumn.value = column;
    sortDir.value = 'asc';
  }
}

const sortedItems = computed(() => {
  if (sortColumn.value) {
    return data.value.slice().sort((a, b) => {
      const valA = getObjectValue(a, sortColumn.value);
      const valB = getObjectValue(b, sortColumn.value);

      if (typeof valA === 'number' && typeof valB === 'number') {
        return sortDir.value === 'asc' ? valA - valB : valB - valA;
      } else {
        return sortDir.value === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
      }
    });
  }
  return data.value;
});

const resetPage = () => {
  currentPage.value = 1;
};

onMounted(() => {
  setTimeout(() => {
    if (data.value && data.value.length === 0 && props.loading === true) {
      props.loading = false;
    }
  }, 2000);
  checkScreenSize();
  window.addEventListener('resize', checkScreenSize);
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', checkScreenSize);
});
</script>

<style scoped>
th {
  padding-top: 8px;
  padding-bottom: 8px;
  text-align: center;
  vertical-align: middle;
  color: #6F7582;
}

td {
  padding-top: 6px;
  padding-bottom: 6px;
  text-align: center;
  vertical-align: middle;
}

@media (max-width: 768px) {
  .mobile-table {
    display: none;
  }
}
</style>