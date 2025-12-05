import { defineStore } from 'pinia'
import { ref, reactive } from 'vue'
import axios from 'axios'
import { useIndexService } from '@/services/indexService'
import { useShowService } from '@/services/showService'
import { storeService } from '@/services/storeService'
import { destroyService } from '@/services/destroyService'
import { updateService } from '@/services/updateService'
import { handleHttpError } from '@/utils/handleHttpError'
import { useGlobalStore } from './global'
export const useMaintenanceStore = defineStore('maintenance', () => {
  const global = useGlobalStore()
  const headers = ref([
    {
      title: 'ID',
      align: 'left',
      key: 'id'
    },
    {
      title: 'NOMBRE',
      align: 'left',
      key: 'NOMBRE'
    },
    {
      title: 'SITIO',
      align: 'end',
      key: 'SITIO'
    },
    {
      title: 'FOTO',
      align: 'middle',
      key: 'FOTO'
    },
    {
      title: 'ESTADO',
      align: 'middle',
      key: 'ESTADO'
    },
    {
      title: 'ACCIONES',
      align: 'middle',
      key: 'acciones',
      sort: true
    }
  ])
  let errors = ref([])
  let id = ref(null)
  let registro = ref([])
  let loading_registro = ref(false)
  let medidas = ref([])
  let loading_medidas = ref(false)
  let categorias = ref([])
  let loading_categorias = ref(false)
  let proveedores = ref([])
  let loading_proveedores = ref(false)
  let loading_opciones = ref(false)
  let opcion_editar = ref(false)
  let opcion_nuevo = ref(false)
  let opcion_eliminar = ref(false)
  let btn_editar = ref(false)
  let btn_nuevo = ref(false)
  let btn_eliminar = ref(false)
  async function index_medida () {
    loading_medidas.value = true
    medidas.value = []
    try {
      medidas.value = await useIndexService('supplies_measures')
      if (medidas.value == null) {
        global.setAlert('Error al cargar los datos.', 'danger', 'Error')
      }
    } catch (err) {
      errors.value = 'No se pudieron cargar los registros.'
      handleHttpError(err, global.setAlert)
    } finally {
      loading_medidas.value = false
    }
  }
  async function index_categoria () {
    loading_categorias.value = true
    categorias.value = []
    try {
      categorias.value = await useIndexService('supplies_category')
      if (categorias.value == null) {
        global.setAlert('Error al cargar los datos.', 'danger', 'Error')
      }
    } catch (err) {
      errors.value = 'No se pudieron cargar los registros.'
      handleHttpError(err, global.setAlert)
    } finally {
      loading_categorias.value = false
    }
  }
  async function index_proveedor () {
    try {
      loading_proveedores.value = true
      proveedores.value = []
      proveedores.value = await useIndexService('supplies_providers')
      if (proveedores.value == null) {
        global.setAlert('Error al cargar los datos.', 'danger', 'Error')
      }
    } catch (err) {
      errors.value = 'No se pudieron cargar los registros.'
      handleHttpError(err, global.setAlert)
    } finally {
      loading_proveedores.value = false
    }
  }
  function opciones (type, item = null) {
    registro.value = item
    switch (type) {
      case value:
        // Add your case logic here
        break
      default:
        break
    }
    loading_opciones.value = true
  }
  return {
    errors,
    headers,
    medidas,
    loading_medidas,
    categorias,
    loading_categorias,
    proveedores,
    loading_proveedores,
    registro,
    loading_registro,
    loading_opciones,
    id,
    opcion_editar,
    opcion_nuevo,
    opcion_eliminar,
    btn_editar,
    btn_nuevo,
    btn_eliminar,

    opciones,
    index_categoria,
    index_proveedor,
    index_medida
  }
})
