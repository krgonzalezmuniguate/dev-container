import { defineStore } from 'pinia'
import { ref, reactive } from 'vue'
import axios from 'axios'
import { useGlobalStore } from './global'
import { useIndexService } from '@/services/indexService'
import { useShowService } from '@/services/showService'
import { updateService } from '@/services/updateService'
import { storeService } from '@/services/storeService'
import { handleHttpError } from '@/utils/handleHttpError'
import { limpiar } from '../utils/functions'
export const useUserStore = defineStore('user', () => {
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
      title: 'APELLIDO',
      align: 'end',
      key: 'last_name'
    },
    {
      title: 'FECHA DE NACIMIENTO',
      align: 'end',
      key: 'date_of_birth_format'
    },
    {
      title: 'DPI',
      align: 'end',
      key: 'dpi'
    },
    {
      title: 'NIT',
      align: 'end',
      key: 'nit'
    },
    {
      title: 'FOTO',
      align: 'middle',
      key: 'profile_picture_url'
    },
    {
      title: 'ESTADO',
      align: 'middle',
      key: 'state'
    },
    {
      title: 'ACCIONES',
      align: 'end',
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
    user_id: '',
    date_of_birth: '',
    phone_number: '',
    address: '',
    nit: '',
    dpi: '',
    gender: '',
    city: '',
    country: '',
    profile_picture_url: ''
  })
  const setErrors = errors => {
    errors.value = errors // Actualiza la variable reactiva con los nuevos errores
  }
  function options (type, item = null) {
    record.value = item
    switch (type) {
      case 1:
        optionNew.value = true
        break
      case 2:
        optionUpdate.value = true
        break
      default:
        break
    }
    loadingOptions.value = true
  }
  async function index () {
    loadingRecord.value = true
    errors.value = ''
    records.value = []
    try {
      records.value = await useIndexService('personal_details')
      console.log(records.value)
    } catch (err) {
      errors.value = 'No se pudieron cargar los registros.'
    } finally {
      loadingRecord.value = false
    }
  }
  async function show () {
    loadingRecord.value = true
    errors.value = ''
    record.value = []
    let user = JSON.parse(localStorage.getItem('user'))
    try {
      record.value = await useShowService('personal_details', user?.id)
    } catch (err) {
      errors.value = 'No se pudieron cargar los registros.'
    } finally {
      loadingRecord.value = false
    }
  }
  async function update () {
    optionUpdate.value = true
    errors.value = ''
    validationErrors.value = []
    const user = JSON.parse(localStorage.getItem('user'))
    try {
      const url = 'personal_details/' + user.id
      const dataToUpdate = { ...record.value }
      dataToUpdate.user_id = user.id
      const response = await updateService.update(url, dataToUpdate, false)
      global.setAlert(
        response.data?.message || 'Detalles personales actualizados exitosamente.',
        'success',
        'Éxito'
      )
    } catch (err) {
      validationErrors.value = err.response.data.errors
      console.log(err.response.data.errors)
      handleHttpError(err, global.setAlert)
    } finally {
      optionUpdate.value = false
    }
  }
  return {
    errors,
    setErrors,
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
    update
  }
})
