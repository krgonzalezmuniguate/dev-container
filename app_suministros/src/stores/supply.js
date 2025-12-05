import { defineStore } from 'pinia'
import { ref, reactive } from 'vue'
import { useMaintenanceStore } from './maintenance'
import { useIndexService } from '@/services/indexService'
import { useShowService } from '@/services/showService'
import { storeService } from '@/services/storeService'
import { destroyService } from '@/services/destroyService'
import { updateService } from '@/services/updateService'
import { useGlobalStore } from './global'
import { handleHttpError } from '@/utils/handleHttpError'
import { limpiar } from '../utils/functions'
export const useSupplyStore = defineStore('supply', () => {
  const main = useMaintenanceStore()
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
      key: 'name'
    },
    {
      title: 'STOCK',
      align: 'left',
      key: 'stock'
    },
    {
      title: 'STOCK MINIMO',
      align: 'left',
      key: 'minimum_stock'
    },
    {
      title: 'CATEGORIA',
      align: 'left',
      key: 'category'
    },
    {
      title: 'UNIDAD DE MEDIDA',
      align: 'left',
      key: 'measures'
    },
    {
      title: 'ESTADO',
      align: 'middle',
      key: 'state_format'
    },
    {
      title: 'ACCIONES',
      align: 'middle',
      key: 'actions',
      sort: true
    }
  ])
  let errors = ref([])
  let validationErrors = ref([])
  let id = ref(null)
  let record = ref([])
  let loadingRecord = ref(false)
  let records = ref([])
  let loadingRecords = ref(false)
  let loadingOptions = ref(false)
  let optionUpdate = ref(false)
  let optionNew = ref(false)
  let optionDelete = ref(false)
  let buttonUpdate = ref(false)
  let buttonNew = ref(false)
  let buttonDelete = ref(false)
  let newRecord = reactive({
    name: null,
    stock: null,
    minimum_stock: null,
    maximum_stock: null,
    provider_id: null,
    supply_category_id: null,
    measurement_unit_id: null,
    type_acquisition: null,
    expiration_date: null,
    description: null
  })
  function options (type, item = null) {
    record.value = item
    switch (type) {
      case 1:
        optionNew.value = true
        main.index_medida()
        main.index_categoria()
        main.index_proveedor()
        break
      case 2:
        optionDelete.value = true
        break
      default:
        break
    }
    loadingOptions.value = true
  }
  async function index () {
    loadingRecords.value = true
    errors.value = ''
    records.value = []
    try {
      records.value = await useIndexService('supplies')
      if (records.value == null) {
        global.setAlert('Error al cargar los datos.', 'danger', 'Error')
      }
    } catch (err) {
      errors.value = 'No se pudieron cargar los registros.'
      handleHttpError(err, global.setAlert)
    } finally {
      loadingRecords.value = false
    }
  }
  async function show () {
    loadingRecord.value = true
    errors.value = ''
    records.value = []
    try {
      record.value = await useShowService('supplies', id.value)
    } catch (err) {
      errors.value = 'No se pudieron cargar los registros.'
      handleHttpError(err, global.setAlert)
    } finally {
      loadingRecord.value = false
    }
  }
  async function store () {
    buttonNew.value = true
    errors.value = ''
    validationErrors.value = []
    try {
      const url = 'supplies'
      const response = await storeService.store(url, newRecord, false)
      global.setAlert(response.data?.message || 'Registro creado exitosamente.', 'success', 'Éxito')
      optionNew.value = false
      index()
      limpiar(newRecord)
      return true
    } catch (err) {
      validationErrors.value = err.response?.data.errors
      handleHttpError(err, global.setAlert)
      return false
    } finally {
      buttonNew.value = false
    }
  }
  async function update () {
    optionUpdate.value = true
    errors.value = ''
    validationErrors.value = []
    try {
      const url = 'supplies/' + record.value.id
      const response = await updateService.update(url, record.value, false)
      global.setAlert(
        response.data?.message || 'Registro actualizado exitosamente.',
        'success',
        'Éxito'
      )
      return true
    } catch (err) {
      validationErrors.value = err.response.data.errors
      handleHttpError(err, global.setAlert)
      return false
    } finally {
      optionUpdate.value = false
    }
  }
  async function destroy () {
    buttonDelete.value = true
    errors.value = ''
    validationErrors.value = []
    try {
      const url = 'supplies/' + record.value.id
      const response = await destroyService.destroy(url)
      global.setAlert(
        response.data?.message || 'Registro eliminado exitosamente.',
        'success',
        'Éxito'
      )
      optionDelete.value = false
      index();
    } catch (err) {
      validationErrors.value = err.response?.data?.errors || []
      handleHttpError(err, global.setAlert)
    } finally {
      buttonDelete.value = false
    }
  }
  return {
    errors,
    validationErrors,
    headers,
    records,
    loadingRecords,
    record,
    loadingRecord,
    loadingOptions,
    id,
    optionUpdate,
    optionNew,
    optionDelete,
    buttonUpdate,
    buttonNew,
    buttonDelete,
    newRecord,

    options,
    index,
    show,
    store,
    update,
    destroy,
  }
})
