<template>
    <div>
        <Header />
        <div class="min-h-screen bg-gray-50 p-6">
            <div class="max-w-7xl mx-auto">
                <!-- Ejemplo básico -->
                <div class="mb-8">
                    <DataTable :data="users" :columns="userColumns" title="Gestión de Usuarios"
                        description="Lista completa de usuarios del sistema" :selectable="false"
                        :advanced-filters="userFilters" @selection-change="handleSelectionChange"
                        @sort-change="handleSortChange">
                        <!-- Acciones en el header -->
                        <template #header-actions>
                            <button
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2"
                                @click="store.options(1)">
                                <Plus class="w-4 h-4" />
                                Nuevo Usuario
                            </button>
                            <button
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                                <Download class="w-4 h-4" />
                                Exportar
                            </button>
                        </template>

                        <!-- Template personalizado para avatar -->
                        <template #cell-avatar="{ item }">
                            <div class="flex items-center gap-3">
                                <img :src="item.avatar" :alt="item.name" class="w-8 h-8 rounded-full object-cover" />
                                <div>
                                    <div class="font-medium text-gray-900">{{ item.name }}</div>
                                    <div class="text-sm text-gray-500">{{ item.email }}</div>
                                </div>
                            </div>
                        </template>

                        <!-- Template para el rol con badge personalizado -->
                        <template #cell-role="{ value }">
                            <span :class="getRoleClass(value)">
                                {{ value }}
                            </span>
                        </template>

                        <!-- Template para acciones -->
                        <template #actions="{ item }">
                            <div class="flex items-center gap-2">
                                <button @click="editUser(item)" class="text-blue-600 hover:text-blue-800 p-1"
                                    title="Editar">
                                    <Edit class="w-4 h-4" />
                                </button>
                                <button @click="viewUser(item)" class="text-green-600 hover:text-green-800 p-1"
                                    title="Ver detalles">
                                    <Eye class="w-4 h-4" />
                                </button>
                                <button @click="deleteUser(item)" class="text-red-600 hover:text-red-800 p-1"
                                    title="Eliminar">
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                        </template>

                        <!-- Acciones masivas -->
                        <template #bulk-actions="{ selectedItems, clearSelection }">
                            <button @click="bulkDelete(selectedItems)"
                                class="text-sm bg-red-600 hover:bg-red-700 px-3 py-1 rounded mr-2">
                                Eliminar seleccionados
                            </button>
                            <button @click="bulkExport(selectedItems)"
                                class="text-sm bg-green-600 hover:bg-green-700 px-3 py-1 rounded mr-2">
                                Exportar seleccionados
                            </button>
                            <button @click="clearSelection"
                                class="text-sm bg-gray-600 hover:bg-gray-700 px-3 py-1 rounded">
                                Limpiar selección
                            </button>
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
        <Top />
        <NewUser />
    </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'
import { useUserStore } from '@/stores/user';
import Header from '@/components/Header.vue';
import Top from '@/components/Top.vue';
import { Plus, Download, Edit, Eye, Trash2, Package, Copy } from 'lucide-vue-next'
import NewUser from '@/components/Modals/newUser.vue';

// Estado reactivo
const store = useUserStore();
const notification = ref('')

// Datos de usuarios
const users = ref([
    {
        id: 1,
        name: 'Ana García',
        email: 'ana.garcia@email.com',
        avatar: '/placeholder.svg?height=32&width=32',
        role: 'Administrador',
        status: 'Activo',
        lastLogin: '2024-01-15',
        department: 'IT',
        salary: 65000
    },
    {
        id: 2,
        name: 'Carlos López',
        email: 'carlos.lopez@email.com',
        avatar: '/placeholder.svg?height=32&width=32',
        role: 'Editor',
        status: 'Activo',
        lastLogin: '2024-01-14',
        department: 'Marketing',
        salary: 45000
    },
    {
        id: 3,
        name: 'María Rodríguez',
        email: 'maria.rodriguez@email.com',
        avatar: '/placeholder.svg?height=32&width=32',
        role: 'Usuario',
        status: 'Inactivo',
        lastLogin: '2024-01-10',
        department: 'Ventas',
        salary: 38000
    },
    {
        id: 4,
        name: 'David Martín',
        email: 'david.martin@email.com',
        avatar: '/placeholder.svg?height=32&width=32',
        role: 'Editor',
        status: 'Activo',
        lastLogin: '2024-01-15',
        department: 'IT',
        salary: 52000
    },
    {
        id: 5,
        name: 'Laura Sánchez',
        email: 'laura.sanchez@email.com',
        avatar: '/placeholder.svg?height=32&width=32',
        role: 'Administrador',
        status: 'Activo',
        lastLogin: '2024-01-13',
        department: 'RRHH',
        salary: 58000
    }
])

// Configuración de columnas para usuarios
const userColumns = [
    {
        key: 'avatar',
        label: 'Usuario',
        sortable: false,
        searchable: false
    },
    {
        key: 'role',
        label: 'Rol',
        sortable: true,
        type: 'badge'
    },
    {
        key: 'status',
        label: 'Estado',
        sortable: true,
        type: 'badge'
    },
    {
        key: 'department',
        label: 'Departamento',
        sortable: true
    },
    {
        key: 'lastLogin',
        label: 'Último acceso',
        sortable: true,
        type: 'date'
    },
    {
        key: 'salary',
        label: 'Salario',
        sortable: true,
        type: 'currency'
    }
]

// Filtros avanzados para usuarios
const userFilters = [
    {
        key: 'role',
        label: 'Rol',
        options: [
            { value: 'Administrador', label: 'Administrador' },
            { value: 'Editor', label: 'Editor' },
            { value: 'Usuario', label: 'Usuario' }
        ]
    },
    {
        key: 'status',
        label: 'Estado',
        options: [
            { value: 'Activo', label: 'Activo' },
            { value: 'Inactivo', label: 'Inactivo' }
        ]
    },
    {
        key: 'department',
        label: 'Departamento',
        options: [
            { value: 'IT', label: 'IT' },
            { value: 'Marketing', label: 'Marketing' },
            { value: 'Ventas', label: 'Ventas' },
            { value: 'RRHH', label: 'RRHH' }
        ]
    }
]

// Datos de productos
const products = ref([
    {
        id: 1,
        name: 'Laptop Dell XPS 13',
        image: '/placeholder.svg?height=48&width=48',
        category: 'Electrónicos',
        price: 1299.99,
        stock: 15,
        status: 'Activo',
        supplier: 'Dell Inc.'
    },
    {
        id: 2,
        name: 'iPhone 15 Pro',
        image: '/placeholder.svg?height=48&width=48',
        category: 'Electrónicos',
        price: 1199.99,
        stock: 3,
        status: 'Activo',
        supplier: 'Apple Inc.'
    },
    {
        id: 3,
        name: 'Silla Ergonómica',
        image: '/placeholder.svg?height=48&width=48',
        category: 'Oficina',
        price: 299.99,
        stock: 25,
        status: 'Activo',
        supplier: 'Herman Miller'
    },
    {
        id: 4,
        name: 'Monitor 4K Samsung',
        image: '/placeholder.svg?height=48&width=48',
        category: 'Electrónicos',
        price: 449.99,
        stock: 0,
        status: 'Agotado',
        supplier: 'Samsung'
    }
])

// Configuración de columnas para productos
const productColumns = [
    {
        key: 'image',
        label: 'Imagen',
        sortable: false,
        searchable: false
    },
    {
        key: 'name',
        label: 'Producto',
        sortable: true
    },
    {
        key: 'category',
        label: 'Categoría',
        sortable: true
    },
    {
        key: 'price',
        label: 'Precio',
        sortable: true,
        type: 'currency'
    },
    {
        key: 'stock',
        label: 'Stock',
        sortable: true
    },
    {
        key: 'status',
        label: 'Estado',
        sortable: true,
        type: 'badge'
    },
    {
        key: 'supplier',
        label: 'Proveedor',
        sortable: true
    }
]

// Filtros avanzados para productos
const productFilters = [
    {
        key: 'category',
        label: 'Categoría',
        options: [
            { value: 'Electrónicos', label: 'Electrónicos' },
            { value: 'Oficina', label: 'Oficina' }
        ]
    },
    {
        key: 'status',
        label: 'Estado',
        options: [
            { value: 'Activo', label: 'Activo' },
            { value: 'Agotado', label: 'Agotado' }
        ]
    }
]

// Métodos para usuarios
const editUser = (user) => {
    showNotification(`Editando usuario: ${user.name}`)
}

const viewUser = (user) => {
    showNotification(`Viendo detalles de: ${user.name}`)
}

const deleteUser = (user) => {
    if (confirm(`¿Estás seguro de eliminar a ${user.name}?`)) {
        const index = users.value.findIndex(u => u.id === user.id)
        if (index > -1) {
            users.value.splice(index, 1)
            showNotification(`Usuario ${user.name} eliminado`)
        }
    }
}

const bulkDelete = (selectedIds) => {
    if (confirm(`¿Eliminar ${selectedIds.length} usuarios seleccionados?`)) {
        users.value = users.value.filter(user => !selectedIds.includes(user.id))
        showNotification(`${selectedIds.length} usuarios eliminados`)
    }
}

const bulkExport = (selectedIds) => {
    showNotification(`Exportando ${selectedIds.length} usuarios...`)
}

// Métodos para productos
const editProduct = (product) => {
    showNotification(`Editando producto: ${product.name}`)
}

const duplicateProduct = (product) => {
    const newProduct = {
        ...product,
        id: Math.max(...products.value.map(p => p.id)) + 1,
        name: `${product.name} (Copia)`
    }
    products.value.push(newProduct)
    showNotification(`Producto duplicado: ${newProduct.name}`)
}

// Métodos de utilidad
const getRoleClass = (role) => {
    const baseClasses = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium'

    switch (role) {
        case 'Administrador':
            return `${baseClasses} bg-purple-100 text-purple-800`
        case 'Editor':
            return `${baseClasses} bg-blue-100 text-blue-800`
        case 'Usuario':
            return `${baseClasses} bg-gray-100 text-gray-800`
        default:
            return `${baseClasses} bg-gray-100 text-gray-800`
    }
}

const getStockIndicatorClass = (stock) => {
    if (stock === 0) return 'bg-red-500'
    if (stock < 10) return 'bg-yellow-500'
    return 'bg-green-500'
}

const showNotification = (message) => {
    notification.value = message
    setTimeout(() => {
        notification.value = ''
    }, 3000)
}

// Handlers de eventos
const handleSelectionChange = (selectedItems) => {
    console.log('Elementos seleccionados:', selectedItems)
}

const handleSortChange = (sortInfo) => {
    console.log('Ordenamiento cambiado:', sortInfo)
}
</script>

<style scoped></style>