import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useGlobalStore = defineStore('global', () => {
  // INICIO ALERTA TOAST
  //----------------------------------------
  const toast = ref({
    message: '',
    type: '',
    title: ' A T E N C I O N '
  })

  function setAlert (message, type, title = ' A T E N C I O N ') {
    toast.value.message = message
    toast.value.type = type
    toast.value.title = title
  }
  //----------------------------------------
  // FIN ALERTA TOAST

  return {
    toast,
    setAlert
  }
})
