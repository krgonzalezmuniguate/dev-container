import axios from 'axios'
import { handleHttpError } from '@/utils/handleHttpError'
import { useGlobalStore } from '@/stores/global'
const baseURL = import.meta.env.VITE_URL_PUBLIC;
export default {
  downloadSolicitud (id) {
    const global = useGlobalStore()
    const url = `${baseURL}request_supplies_download/${id}`
    axios({
      url,
      method: 'GET',
      responseType: 'blob'
    })
      .then(response => {
        const blob = new Blob([response.data], { type: 'application/pdf' })
        const link = document.createElement('a')
        link.href = window.URL.createObjectURL(blob)
        link.download = `Solicitud_${id}.pdf`
        link.click()
        window.URL.revokeObjectURL(link.href)
      })
      .catch(err => {
        handleHttpError(err, global.setAlert)
      })
  }
}
