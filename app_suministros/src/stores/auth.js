import { defineStore } from 'pinia'
import { ref, reactive, computed } from 'vue'
import axios from 'axios'
import authService from '@/services/authService'
export const useAuthStore = defineStore('auth', () => {
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
  let apps = ref([])
  let validationErrors = ref([])
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
  let loadingLogin = ref(false)
  const token = ref(localStorage.getItem('authToken') || null)
  const user = ref(JSON.parse(localStorage.getItem('user')) || null)
  const rol = ref(localStorage.getItem('rol') || null)
  const expirationTime = ref(parseInt(localStorage.getItem('expirationTime')) || null)
  const isAuthenticated = computed(() => !!token.value)
  let credentials = reactive([{ email: null, password: null }])
  let loadingChangePassword = ref(false)
  let successMessageChangePassword = ref(null)
  let errorMessageChangePassword = ref(null)
  let currentPassword = ref(null)
  let newPassword = ref(null)
  let newPasswordConfirmation = ref(null)
  async function login () {
    loadingLogin.value = true
    errors.value = null
    try {
      const response = await authService.login(credentials.email, credentials.password)
      const now = new Date()
      const expiresAt = now.getTime() + response.expires_in * 1000

      token.value = response.access_token
      user.value = response.user
      rol.value = response.roles
      expirationTime.value = expiresAt

      localStorage.setItem('authToken', response.access_token)
      localStorage.setItem('rol', response.roles)
      localStorage.setItem('user', JSON.stringify(response.user))
      localStorage.setItem('expirationTime', expiresAt.toString())
      apps.value = response.apps
      axios.defaults.headers.common['Authorization'] = `Bearer ${response.access_token}`
      return true
    } catch (error) {
      if (error.response?.data?.errors) {
        errors.value = error.response.data.errors
      } else {
        errors.value = { general: 'Error desconocido al iniciar sesión.' }
      }
      return false
    } finally {
      loadingLogin.value = false
    }
  }
  async function initializeAuth () {
    const isDevMode = import.meta.env.VITE_APP_DISABLE_AUTH === 'true'
    if (isDevMode) {
      if (!localStorage.getItem('authToken')) {
        console.log('✅ Modo de desarrollo: Autenticación falsa activada.')
        const MOCK_USER = {
          id: 5,
          name: 'Walter Tejeda',
          email: 'test@example.com',
          apps: {
            id: 2,
            title: 'Suministros',
            description: 'Gesti\u00f3n integral de suministros',
            icon: 'ContainerIcon',
            color: '#F59E0B',
            gradient: 'linear-gradient(135deg, #F59E0B 0%, #D97706 100%)',
            url: null,
            subApps: [
              {
                id: 14,
                title: 'Autorizar Suministros',
                description: 'AUTORIZAR SUMINISTROS',
                icon: 'ShieldCheckIcon',
                color: '#EF4444',
                url: '/suministros/authorization'
              }
            ]
          }
        }
        const MOCK_TOKEN = 'fake-dev-token-xyz'
        localStorage.setItem('authToken', MOCK_TOKEN)
        localStorage.setItem('user', JSON.stringify(MOCK_USER))
        console.log('LocalStorage de desarrollo establecido correctamente')
      }
      token.value = localStorage.getItem('authToken')
      user.value = JSON.parse(localStorage.getItem('user'))
      apps.value = user.value.apps
      axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
      return
    }
    const storedToken = localStorage.getItem('authToken')
    const storedUser = localStorage.getItem('user')
    if (storedToken) {
      token.value = storedToken
      axios.defaults.headers.common['Authorization'] = `Bearer ${storedToken}`
      try {
        const response = await axios.get('me')
        user.value = response.data
        localStorage.setItem('user', JSON.stringify(response.data.user))
        console.log('Datos de usuario actualizados desde la API:', user.value)
        apps.value = response.data.apps
      } catch (error) {
        console.error('Fallo al obtener datos de usuario o token inválido:', error)
        localStorage.removeItem('authToken')
        localStorage.removeItem('user')
        token.value = null
        user.value = null
        delete axios.defaults.headers.common['Authorization']
      }
    } else if (storedUser) {
      try {
        user.value = JSON.parse(storedUser)
        console.warn(
          'No se encontró token de autenticación, pero se cargaron datos de usuario de localStorage. Considera cerrar la sesión.'
        )
      } catch {
        localStorage.removeItem('user')
        user.value = null
      }
    }
  }
  function isTokenExpired () {
    const storedTime = localStorage.getItem('expirationTime')
    if (!storedTime) return true
    const now = new Date().getTime()
    return now >= parseInt(storedTime)
  }
  function logout () {
    token.value = null
    user.value = null
    expirationTime.value = null
    localStorage.removeItem('authToken')
    localStorage.removeItem('user')
    localStorage.removeItem('expirationTime')
    localStorage.removeItem('rol')
    delete axios.defaults.headers.common['Authorization']
  }
  async function changePassword () {
    loadingChangePassword.value = true // Activa el estado de carga
    successMessageChangePassword.value = null // Limpia mensajes anteriores
    errorMessageChangePassword.value = null // Limpia errores anteriores
    errors.value = {} // Limpia errores generales por si acaso
    console.log(currentPassword.value)
    try {
      const response = await authService.changePassword(
        currentPassword.value,
        newPassword.value,
        newPasswordConfirmation.value
      )

      successMessageChangePassword.value =
        response.message || 'Contraseña actualizada exitosamente.'

      // Opcional: Limpiar los campos del formulario después del éxito
      currentPassword.value = ''
      newPassword.value = ''
      newPasswordConfirmation.value = ''

      return true
    } catch (error) {
      console.error('Error al cambiar la contraseña:', error.response?.data || error.message)

      if (error.response?.data?.errors) {
        validationErrors.value = error.response?.data?.errors
        // Errores de validación de Laravel (ej: la nueva contraseña no cumple los requisitos)
        const validationErrors = Object.values(error.response.data.errors).flat()
        errorMessageChangePassword.value = validationErrors.join(', ') // Unir todos los mensajes en uno
        // Si quieres mantener el formato de objeto para 'errors.value'
        errors.value = error.response.data.errors
      } else if (error.response?.data?.error) {
        // Errores específicos de JWT (ej: 'Token expirado', 'Token inválido', 'Credenciales inválidas')
        errorMessageChangePassword.value = error.response.data.error
        if (error.response.data.details) {
          errorMessageChangePassword.value += ' ' + error.response.data.details
        }
      } else if (error.response?.data?.message) {
        // Otros mensajes de error del backend
        errorMessageChangePassword.value = error.response.data.message
      } else {
        errorMessageChangePassword.value = 'Hubo un problema desconocido al cambiar la contraseña.'
      }
      return false
    } finally {
      loadingChangePassword.value = false // Desactiva el estado de carga
    }
  }
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
  return {
    errors,
    validationErrors,
    headers,
    records,
    loadingRecords,
    loadingLogin,
    loadingRecord,
    loadingOptions,
    record,
    id,
    apps,
    optionUpdate,
    optionNew,
    optionDelete,
    buttonUpdate,
    buttonNew,
    buttonDelete,
    credentials,
    token,
    user,
    rol,
    isAuthenticated,
    currentPassword,
    newPassword,
    newPasswordConfirmation,

    login,
    logout,
    initializeAuth,
    isTokenExpired,
    changePassword,
    options
  }
})
