import { defineStore } from 'pinia'
import { ref, reactive } from 'vue'
import { useIndexService } from '@/services/indexService'
import { useShowService } from '@/services/showService'
import { storeService } from '@/services/storeService'
import { destroyService } from '@/services/destroyService'
import { updateService } from '@/services/updateService'
import { useGlobalStore } from './global'
import { handleHttpError } from '@/utils/handleHttpError'
import { limpiar } from '../utils/functions'
export const useMovementStore = defineStore('movement', () => {
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
      title: 'SITIO',
      align: 'end',
      key: 'site'
    },
    {
      title: 'FOTO',
      align: 'middle',
      key: 'image'
    },
    {
      title: 'ESTADO',
      align: 'middle',
      key: 'state'
    },
    {
      title: 'ACCIONES',
      align: 'middle',
      key: 'actions',
      sort: true
    }
  ])
  let errors = ref([])
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
  function options (type, item = null) {
    record.value = item
    switch (type) {
      case value:
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
  return {
    errors,
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

    options,
    index
  }
})
