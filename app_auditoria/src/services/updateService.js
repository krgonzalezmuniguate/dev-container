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
  async update (resource, data, useFormData = true) {
    const payload = useFormData ? toFormData(data) : data
    return await axios.post(resource, payload)
  }
}
