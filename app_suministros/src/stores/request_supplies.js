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

export const useRequestStore = defineStore('request', () => {
  const global = useGlobalStore()
  const headers = ref([
    {
      title: 'ID',
      align: 'left',
      key: 'id'
    },
    {
      title: 'DESCRIPCIÓN',
      align: 'left',
      key: 'description'
    },
    {
      title: 'ESTADO',
      align: 'middle',
      key: 'state'
    },
    {
      title: 'ACCIONES',
      align: 'center',
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
  let optionAuthorization = ref(false)
  let optionManager = ref(false)
  let optionView = ref(false)
  let buttonUpdate = ref(false)
  let buttonManager = ref(false)
  let buttonAuthorization = ref(false)
  let buttonProcessing = ref(false)
  let buttonNew = ref(false)
  let buttonDelete = ref(false)
  let newRecord = reactive({
    description: '',
    user_id: ''
  })
  let supplyLines = ref([])
  let description = ref('')
  let reason_reject = ref('')
  function options (type, item = null) {
    record.value = item
    switch (type) {
      case 1:
        optionNew.value = true
        break
      case 3:
        optionDelete.value = true
        break
      case 4:
        optionAuthorization.value = true
        break
      case 5:
        optionManager.value = true
        break
      case 6:
        optionView.value = true
        break
      default:
        break
    }
    loadingOptions.value = true
  }
  async function index_to_manager () {
    loadingRecords.value = true
    errors.value = ''
    records.value = []
    try {
      records.value = await useIndexService('request_supplies_manager')
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
    loadingRecords.value = true
    errors.value = ''
    records.value = []
    try {
      records.value = await useShowService('request_supplies', id.value)
    } catch (err) {
      errors.value = 'No se pudieron cargar los registros.'
      handleHttpError(err, global.setAlert)
    } finally {
      loadingRecords.value = false
    }
  }
  async function show_by_user () {
    loadingRecords.value = true
    errors.value = ''
    records.value = []
    let user = JSON.parse(localStorage.getItem('user'))
    try {
      records.value = await useShowService('request_supplies_user', user?.id)
      if (records.value == null) {
        global.setAlert('Error al mostrar los datos.', 'success', 'Éxito')
      }
    } catch (err) {
      errors.value = 'No se pudieron cargar los registros.'
      handleHttpError(err, global.setAlert)
    } finally {
      loadingRecords.value = false
    }
  }
  async function show_to_chiefs () {
    loadingRecords.value = true
    errors.value = ''
    records.value = []
    let user = JSON.parse(localStorage.getItem('user'))
    try {
      records.value = await useShowService('request_supplies/supervisor', user?.id)
      if (records.value == null) {
        global.setAlert(
          response.data?.message || 'Detalles personales creados exitosamente.',
          'success',
          'Éxito'
        )
      }
    } catch (err) {
      errors.value = 'No se pudieron cargar los registros.'
      handleHttpError(err, global.setAlert)
    } finally {
      loadingRecords.value = false
    }
  }
  async function store () {
    buttonNew.value = true
    errors.value = ''
    validationErrors.value = []
    try {
      let user = JSON.parse(localStorage.getItem('user'))
      const url = 'request_supplies'
      newRecord.user_id = user?.id
      const dataToCreate = { ...newRecord }
      const response = await storeService.store(url, dataToCreate, false)
      global.setAlert(
        response.data?.message || 'Solicitud creada exitosamente.',
        'success',
        'Éxito'
      )
      optionNew.value = false
      this.show_by_user()
      limpiar(newRecord)
    } catch (err) {
      validationErrors.value = err.response?.data.errors
      handleHttpError(err, global.setAlert)
    } finally {
      buttonNew.value = false
    }
  }
  async function approbe () {
    buttonAuthorization.value = true
    errors.value = ''
    validationErrors.value = []
    try {
      const url = 'request_supplies/approbe/' + record.value.id
      const response = await updateService.update(url)
      global.setAlert(
        response.data?.message || 'Detalles personales actualizados exitosamente.',
        'success',
        'Éxito'
      )
      optionAuthorization.value = false
      this.show_to_chiefs()
    } catch (err) {
      // validationErrors.value = err.response.data.errors || 'Error'
      // console.log(err.response.data.errors)
      handleHttpError(err, global.setAlert)
    } finally {
      buttonAuthorization.value = false
    }
  }
  async function reject (type=null) {
    buttonAuthorization.value = true
    errors.value = ''
    validationErrors.value = [];
    let url = '';
    const data = {
      reason_reject: reason_reject.value
    }
    try {
      if(type){
        url = 'request_supplies/reject/' + records.value.id
      }else{
        url = 'request_supplies/reject/' + record.value.id
      }
      const response = await updateService.update(url, data)
      global.setAlert(
        response.data?.message || 'Solicitud de suministros rechazada exitosamente.',
        'success',
        'Éxito'
      )
      reason_reject.value = '';
      if(type){
        return true;
      }else{
        optionAuthorization.value = false
        show_to_chiefs()
        return false;
      }
    } catch (err) {
      validationErrors.value = err.response.data.errors || 'Error'
      handleHttpError(err, global.setAlert)
    } finally {
      buttonAuthorization.value = false
    }
  }
  async function destroy () {
    buttonDelete.value = true
    errors.value = ''
    validationErrors.value = []
    try {
      const url = 'request_supplies/' + record.value.id
      const response = await destroyService.destroy(url)
      console.log(url)
      global.setAlert(
        response.data?.message || 'Solicitud eliminada exitosamente.',
        'success',
        'Éxito'
      )
      optionDelete.value = false
      this.show_by_user()
    } catch (err) {
      validationErrors.value = err.response?.data?.errors || []
      handleHttpError(err, global.setAlert)
    } finally {
      buttonDelete.value = false
    }
  }
  async function processing () {
    buttonProcessing.value = true
    errors.value = ''
    validationErrors.value = []
    let user = JSON.parse(localStorage.getItem('user'))
    const datos = {
      suministros: supplyLines.value,
      description: description.value,
      user_id: user.id,
      request_id: id.value
    }
    try {
      const url = 'supplies_movement'
      const response = await storeService.store(url, datos, false)
      global.setAlert(response.data?.message || 'despacho creado exitosamente.', 'success', 'Éxito')
      this.show()
      limpiar(supplyLines)
      return true
    } catch (err) {
      validationErrors.value = err.response?.data.errors
      handleHttpError(err, global.setAlert)
      return false
    } finally {
      buttonProcessing.value = false
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
    optionAuthorization,
    optionManager,
    optionView,
    buttonAuthorization,
    buttonManager,
    buttonUpdate,
    buttonNew,
    buttonDelete,
    buttonProcessing,
    newRecord,
    supplyLines,
    description,
    reason_reject,

    options,
    index_to_manager,
    show,
    show_to_chiefs,
    show_by_user,
    store,
    approbe,
    reject,
    destroy,
    processing
  }
})
