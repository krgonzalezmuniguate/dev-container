<template>
    <div class="w-full space-y-4">
        <!-- Header con título y acciones -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 v-if="title" class="text-2xl font-bold text-gray-900">{{ title }}</h2>
                <p v-if="description" class="text-sm text-gray-600 mt-1">{{ description }}</p>
            </div>
            <div class="flex items-center gap-2">
                <slot name="header-actions"></slot>
            </div>
        </div>

        <!-- Filtros y búsqueda -->
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Búsqueda general -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Búsqueda</label>
                    <div class="relative">
                        <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
                        <input v-model="searchQuery" type="text" placeholder="Buscar en todos los campos..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                </div>

                <!-- Filtro por columna -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Filtrar por</label>
                    <select v-model="filterColumn"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todas las columnas</option>
                        <option v-for="column in searchableColumns" :key="column.key" :value="column.key">
                            {{ column.label }}
                        </option>
                    </select>
                </div>

                <!-- Items por página -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mostrar</label>
                    <select v-model="itemsPerPage"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option :value="10">10</option>
                        <option :value="25">25</option>
                        <option :value="50">50</option>
                        <option :value="100">100</option>
                    </select>
                </div>
            </div>

            <!-- Filtros avanzados -->
            <div v-if="showAdvancedFilters" class="mt-4 pt-4 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div v-for="filter in advancedFilters" :key="filter.key">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ filter.label }}</label>
                        <select v-model="activeFilters[filter.key]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos</option>
                            <option v-for="option in filter.options" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Toggle filtros avanzados -->
            <div class="mt-4 flex justify-between items-center">
                <button @click="showAdvancedFilters = !showAdvancedFilters"
                    class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
                    <ChevronDown :class="{ 'rotate-180': showAdvancedFilters }" class="w-4 h-4 transition-transform" />
                    {{ showAdvancedFilters ? 'Ocultar' : 'Mostrar' }} filtros avanzados
                </button>

                <div class="flex items-center gap-2">
                    <button @click="clearFilters" class="text-sm text-gray-600 hover:text-gray-800">
                        Limpiar filtros
                    </button>
                    <span class="text-sm text-gray-500">
                        {{ filteredData.length }} de {{ data.length }} registros
                    </span>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <!-- Checkbox para seleccionar todos -->
                            <th v-if="selectable" class="w-12 px-4 py-3">
                                <input type="checkbox" :checked="isAllSelected" @change="toggleSelectAll"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                            </th>

                            <!-- Columnas -->
                            <th v-for="column in columns" :key="column.key" :class="[
                                'px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider',
                                column.sortable ? 'cursor-pointer hover:bg-gray-100' : ''
                            ]" @click="column.sortable ? sort(column.key) : null">
                                <div class="flex items-center gap-2">
                                    {{ column.label }}
                                    <div v-if="column.sortable" class="flex flex-col">
                                        <ChevronUp :class="[
                                            'w-3 h-3',
                                            sortKey === column.key && sortOrder === 'asc' ? 'text-blue-600' : 'text-gray-400'
                                        ]" />
                                        <ChevronDown :class="[
                                            'w-3 h-3 -mt-1',
                                            sortKey === column.key && sortOrder === 'desc' ? 'text-blue-600' : 'text-gray-400'
                                        ]" />
                                    </div>
                                </div>
                            </th>

                            <!-- Columna de acciones -->
                            <th v-if="$slots.actions"
                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(item, index) in paginatedData" :key="getItemKey(item, index)" :class="[
                            'hover:bg-gray-50 transition-colors',
                            selectedItems.includes(getItemKey(item, index)) ? 'bg-blue-50' : ''
                        ]">
                            <!-- Checkbox de selección -->
                            <td v-if="selectable" class="px-4 py-3">
                                <input type="checkbox" :checked="selectedItems.includes(getItemKey(item, index))"
                                    @change="toggleSelect(getItemKey(item, index))"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                            </td>

                            <!-- Celdas de datos -->
                            <td v-for="column in columns" :key="column.key" class="px-4 py-3 text-sm text-gray-900">
                                <slot :name="`cell-${column.key}`" :item="item"
                                    :value="getNestedValue(item, column.key)" :column="column">
                                    <span v-if="column.type === 'badge'"
                                        :class="getBadgeClass(getNestedValue(item, column.key))">
                                        {{ formatValue(getNestedValue(item, column.key), column) }}
                                    </span>
                                    <span v-else-if="column.type === 'currency'" class="font-medium">
                                        {{ formatValue(getNestedValue(item, column.key), column) }}
                                    </span>
                                    <span v-else-if="column.type === 'date'">
                                        {{ formatValue(getNestedValue(item, column.key), column) }}
                                    </span>
                                    <span v-else>
                                        {{ formatValue(getNestedValue(item, column.key), column) }}
                                    </span>
                                </slot>
                            </td>

                            <!-- Acciones -->
                            <td v-if="$slots.actions" class="px-4 py-3 text-right text-sm">
                                <slot name="actions" :item="item" :index="index"></slot>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Estado vacío -->
            <div v-if="paginatedData.length === 0" class="text-center py-12">
                <div class="text-gray-400 mb-4">
                    <Search class="w-12 h-12 mx-auto" />
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron resultados</h3>
                <p class="text-gray-500">Intenta ajustar tus filtros de búsqueda</p>
            </div>
        </div>

        <!-- Paginación -->
        <div v-if="totalPages > 1"
            class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white px-4 py-3 border border-gray-200 rounded-lg">
            <div class="text-sm text-gray-700">
                Mostrando {{ startItem }} a {{ endItem }} de {{ filteredData.length }} resultados
            </div>

            <div class="flex items-center gap-2">
                <button @click="currentPage = 1" :disabled="currentPage === 1"
                    class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Primera
                </button>

                <button @click="currentPage--" :disabled="currentPage === 1"
                    class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    <ChevronLeft class="w-4 h-4" />
                </button>

                <div class="flex items-center gap-1">
                    <button v-for="page in visiblePages" :key="page" @click="currentPage = page" :class="[
                        'px-3 py-1 text-sm border rounded-md',
                        currentPage === page
                            ? 'bg-blue-600 text-white border-blue-600'
                            : 'border-gray-300 hover:bg-gray-50'
                    ]">
                        {{ page }}
                    </button>
                </div>

                <button @click="currentPage++" :disabled="currentPage === totalPages"
                    class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    <ChevronRight class="w-4 h-4" />
                </button>

                <button @click="currentPage = totalPages" :disabled="currentPage === totalPages"
                    class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Última
                </button>
            </div>
        </div>

        <!-- Acciones masivas -->
        <div v-if="selectedItems.length > 0"
            class="fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-4">
            <span class="text-sm font-medium">{{ selectedItems.length }} elementos seleccionados</span>
            <slot name="bulk-actions" :selectedItems="selectedItems" :clearSelection="clearSelection">
                <button @click="clearSelection" class="text-sm bg-blue-700 hover:bg-blue-800 px-3 py-1 rounded">
                    Limpiar selección
                </button>
            </slot>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Search, ChevronDown, ChevronUp, ChevronLeft, ChevronRight } from 'lucide-vue-next'

// Props
const props = defineProps({
    data: {
        type: Array,
        required: true
    },
    columns: {
        type: Array,
        required: true
    },
    title: {
        type: String,
        default: ''
    },
    description: {
        type: String,
        default: ''
    },
    selectable: {
        type: Boolean,
        default: false
    },
    itemKey: {
        type: String,
        default: 'id'
    },
    advancedFilters: {
        type: Array,
        default: () => []
    }
})

// Emits
const emit = defineEmits(['selection-change', 'sort-change'])

// Estado reactivo
const searchQuery = ref('')
const filterColumn = ref('')
const sortKey = ref('')
const sortOrder = ref('asc')
const currentPage = ref(1)
const itemsPerPage = ref(25)
const selectedItems = ref([])
const showAdvancedFilters = ref(false)
const activeFilters = ref({})

// Computed properties
const searchableColumns = computed(() => {
    return props.columns.filter(column => column.searchable !== false)
})

const filteredData = computed(() => {
    let filtered = [...props.data]

    // Búsqueda general
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase()
        filtered = filtered.filter(item => {
            if (filterColumn.value) {
                const value = getNestedValue(item, filterColumn.value)
                return String(value).toLowerCase().includes(query)
            } else {
                return searchableColumns.value.some(column => {
                    const value = getNestedValue(item, column.key)
                    return String(value).toLowerCase().includes(query)
                })
            }
        })
    }

    // Filtros avanzados
    Object.entries(activeFilters.value).forEach(([key, value]) => {
        if (value) {
            filtered = filtered.filter(item => {
                const itemValue = getNestedValue(item, key)
                return itemValue === value
            })
        }
    })

    // Ordenamiento
    if (sortKey.value) {
        filtered.sort((a, b) => {
            const aVal = getNestedValue(a, sortKey.value)
            const bVal = getNestedValue(b, sortKey.value)

            let comparison = 0
            if (aVal > bVal) comparison = 1
            if (aVal < bVal) comparison = -1

            return sortOrder.value === 'desc' ? -comparison : comparison
        })
    }

    return filtered
})

const totalPages = computed(() => {
    return Math.ceil(filteredData.value.length / itemsPerPage.value)
})

const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value
    const end = start + itemsPerPage.value
    return filteredData.value.slice(start, end)
})

const startItem = computed(() => {
    return (currentPage.value - 1) * itemsPerPage.value + 1
})

const endItem = computed(() => {
    return Math.min(currentPage.value * itemsPerPage.value, filteredData.value.length)
})

const visiblePages = computed(() => {
    const pages = []
    const maxVisible = 5
    let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2))
    let end = Math.min(totalPages.value, start + maxVisible - 1)

    if (end - start + 1 < maxVisible) {
        start = Math.max(1, end - maxVisible + 1)
    }

    for (let i = start; i <= end; i++) {
        pages.push(i)
    }

    return pages
})

const isAllSelected = computed(() => {
    return paginatedData.value.length > 0 &&
        paginatedData.value.every(item => selectedItems.value.includes(getItemKey(item)))
})

// Métodos
const getNestedValue = (obj, path) => {
    return path.split('.').reduce((current, key) => current?.[key], obj)
}

const getItemKey = (item, index = null) => {
    return getNestedValue(item, props.itemKey) || index
}

const formatValue = (value, column) => {
    if (value == null) return '-'

    switch (column.type) {
        case 'currency':
            return new Intl.NumberFormat('es-ES', {
                style: 'currency',
                currency: 'EUR'
            }).format(value)
        case 'date':
            return new Date(value).toLocaleDateString('es-ES')
        case 'number':
            return new Intl.NumberFormat('es-ES').format(value)
        default:
            return value
    }
}

const getBadgeClass = (value) => {
    const baseClasses = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium'

    switch (String(value).toLowerCase()) {
        case 'activo':
        case 'active':
        case 'completado':
            return `${baseClasses} bg-green-100 text-green-800`
        case 'inactivo':
        case 'inactive':
        case 'pendiente':
            return `${baseClasses} bg-yellow-100 text-yellow-800`
        case 'cancelado':
        case 'cancelled':
        case 'error':
            return `${baseClasses} bg-red-100 text-red-800`
        default:
            return `${baseClasses} bg-gray-100 text-gray-800`
    }
}

const sort = (key) => {
    if (sortKey.value === key) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc'
    } else {
        sortKey.value = key
        sortOrder.value = 'asc'
    }

    emit('sort-change', { key: sortKey.value, order: sortOrder.value })
}

const toggleSelect = (itemKey) => {
    const index = selectedItems.value.indexOf(itemKey)
    if (index > -1) {
        selectedItems.value.splice(index, 1)
    } else {
        selectedItems.value.push(itemKey)
    }
}

const toggleSelectAll = () => {
    if (isAllSelected.value) {
        // Deseleccionar todos los elementos visibles
        paginatedData.value.forEach(item => {
            const itemKey = getItemKey(item)
            const index = selectedItems.value.indexOf(itemKey)
            if (index > -1) {
                selectedItems.value.splice(index, 1)
            }
        })
    } else {
        // Seleccionar todos los elementos visibles
        paginatedData.value.forEach(item => {
            const itemKey = getItemKey(item)
            if (!selectedItems.value.includes(itemKey)) {
                selectedItems.value.push(itemKey)
            }
        })
    }
}

const clearSelection = () => {
    selectedItems.value = []
}

const clearFilters = () => {
    searchQuery.value = ''
    filterColumn.value = ''
    activeFilters.value = {}
    currentPage.value = 1
}

// Watchers
watch([searchQuery, filterColumn, activeFilters, itemsPerPage], () => {
    currentPage.value = 1
}, { deep: true })

watch(selectedItems, (newSelection) => {
    emit('selection-change', newSelection)
}, { deep: true })
</script>