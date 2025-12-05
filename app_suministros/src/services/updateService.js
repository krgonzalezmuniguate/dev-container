import axios from 'axios'

function toFormData (data) {
  const formData = new FormData()
  for (const key in data) {
    if (data[key] instanceof File) {
      formData.append(key, data[key])
    } else if (Array.isArray(data[key])) {
      data[key].forEach((val, index) => {
        formData.append(`${key}[${index}]`, val)
      })
    } else {
      formData.append(key, data[key])
    }
  }
  return formData
}
export const updateService = {
  async update (resource, data = {}, useFormData = false) {
    try {
      let payload = {}
      if (Object.keys(data).length > 0) {
        payload = useFormData ? toFormData(data) : data
      }
      return await axios.post(resource, payload || {})
    } catch (error) {
      if (error.response) {
        throw new Error(error.response.data?.message || 'Error en la petición')
      } else if (error.request) {
        throw new Error('No hubo respuesta del servidor')
      } else {
        throw new Error(error.message)
      }
    }
  }
}
