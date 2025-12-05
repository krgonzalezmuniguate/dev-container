import axios from 'axios'

export const destroyService = {
  async destroy(resource) {
    return await axios.delete(resource)
  }
}
