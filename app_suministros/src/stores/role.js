import { defineStore } from 'pinia'
import { ref, reactive } from 'vue'
import { useGlobalStore } from './global'
import { useIndexService } from '@/services/indexService'
import { useShowService } from '@/services/showService'
import { updateService } from '@/services/updateService'
import { storeService } from '@/services/storeService'
import { destroyService } from '@/services/destroyService'
import { handleHttpError } from '@/utils/handleHttpError'
import { limpiar } from '../utils/functions'
export const useRoleStore = defineStore('role', () => {
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
    name: '',
    last_name: '',
    code_job: '',
    email: '',
    ethnic_group: '',
    profile_picture_url: '',
    manager_id: ''
  })
  let file = ref(null)
  function options (type, item = null) {
    record.value = item
    switch (type) {
      case 1:
        optionNew.value = true
        break
      case 2:
        optionUpdate.value = true
        break
      case 3:
        optionDelete.value = true
        break
      case 4:
        optionRol.value = true
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
      records.value = await useIndexService('roles')
      // console.log(records.value)
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
  async function store () {
    optionNew.value = true
    errors.value = ''
    validationErrors.value = []
    try {
      const url = 'personal_details'
      const dataToCreate = { ...newRecord }
      dataToCreate.profile_picture_url = file.value
      const response = await storeService.store(url, dataToCreate, true)
      global.setAlert(
        response.data?.message || 'Detalles personales creados exitosamente.',
        'success',
        'Éxito'
      )
      optionNew.value = false
      this.index()
      limpiar(newRecord)
    } catch (err) {
      validationErrors.value = err.response?.data.errors
      console.log(err)
      handleHttpError(err, global.setAlert)
    }
  }
  async function update () {
    optionUpdate.value = true
    errors.value = ''
    validationErrors.value = []
    try {
      const url = 'personal_details/' + record.value.id
      const dataToUpdate = { ...record.value }
      dataToUpdate.user_id = record.value.id
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
  async function destroy () {
    buttonDelete.value = true
    errors.value = ''
    validationErrors.value = []

    try {
      const url = 'personal_details/' + record.value.id
      const response = await destroyService.destroy(url)
      global.setAlert(
        response.data?.message || 'Detalles personales eliminados exitosamente.',
        'success',
        'Éxito'
      )
      optionDelete.value = false
      this.index()
    } catch (err) {
      validationErrors.value = err.response?.data?.errors || []
      console.log(validationErrors.value)
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
    file,

    options,
    index,
    show,
    store,
    update,
    destroy,
  }
})
