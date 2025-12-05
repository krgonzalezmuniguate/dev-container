import axios from 'axios'
import { handleHttpError } from '@/utils/handleHttpError'
import { useGlobalStore } from '@/stores/global'

export async function useIndexService (baseUrl) {
  const global = useGlobalStore() // Asegúrate de que useGlobalStore() te dé acceso a setAlert
  try {
    const response = await axios.get(baseUrl)
    if (response.status === 200) {
      return response.data.datos ?? []
    } else {
      const error = new Error(response.data?.message || 'Error inesperado')
      error.response = response // Adjunta la respuesta para que handleHttpError la procese
      throw error
    }
  } catch (err) {
    // Aquí es donde usas tu función handleHttpError
    handleHttpError(err, global.setAlert)
    throw err // Vuelves a lanzar el error para que quien llame a useIndexService también lo maneje
  }
}
