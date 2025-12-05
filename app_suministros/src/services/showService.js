import axios from 'axios'
import { handleHttpError } from '@/utils/handleHttpError'
import { useGlobalStore } from '@/stores/global'

export async function useShowService (baseUrl, id) {
  const global = useGlobalStore() // Para usar global.setAlert
  try {
    const response = await axios.get(`${baseUrl}/${id}`)
    if (response.status === 200) {
      return response.data.datos ?? null
    } else {
      const error = new Error(response.data?.message || 'Error inesperado')
      error.response = response
      throw error
    }
  } catch (err) {
    handleHttpError(err, global.setAlert)
    throw err
  }
}
