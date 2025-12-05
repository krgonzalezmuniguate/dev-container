// src/services/authService.js

import axios from 'axios'
// axios.defaults.headers.common['Content-Type'] = 'application/json';

const authService = {
  /**
   * Intenta loguear al usuario con las credenciales proporcionadas.
   * @param {string} email - El email del usuario.
   * @param {string} password - La contraseña del usuario.
   * @returns {Promise<object>} - Una promesa que resuelve con los datos del usuario y el token
   * o rechaza con un objeto de error.
   */
  async login (email, password) {
    try {
      const response = await axios.post('login', { email, password })
      return response.data // Devuelve los datos que el store necesita (user y token)
    } catch (error) {
      throw error
    }
  },

  /**
   * Realiza la petición de logout al backend.
   * Asume que el token de autorización ya está configurado en Axios.
   * @returns {Promise<object>} - Una promesa que resuelve con la respuesta del logout
   * o rechaza con un error.
   */
  async logout () {
    try {
      const response = await axios.post('logout')
      return response.data
    } catch (error) {
      throw error
    }
  },

  /**
   * Obtiene los datos del usuario autenticado desde el backend.
   * Asume que el token de autorización ya está configurado en Axios.
   * @returns {Promise<object>} - Una promesa que resuelve con los datos del usuario
   * o rechaza con un error.
   */

  async changePassword (currentPassword, newPassword, newPasswordConfirmation) {
    try {
      const response = await axios.post('change_password', {
        current_password: currentPassword,
        new_password: newPassword,
        new_password_confirmation: newPasswordConfirmation
      })
      return response.data
    } catch (error) {
      // Re-lanza el error para que el componente que llama lo pueda manejar.
      // Los errores de validación de Laravel (status 422) o de autenticación (status 401)
      // estarán disponibles en error.response.data
      throw error
    }
  }
}

export default authService
