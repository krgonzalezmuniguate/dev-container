// utils/base64.js

/**
 * Codifica una cadena o un objeto en Base64.
 * Si el dato es un objeto, se convierte primero a JSON.
 *
 * @param {string|object} data - Datos a codificar.
 * @returns {string} - Cadena codificada en Base64.
 */
export function encodeBase64 (data) {
  if (typeof data === 'object') {
    // Convierte el objeto a JSON y luego lo codifica
    data = JSON.stringify(data)
  }
  return btoa(data) // btoa codifica en base64
}

/**
 * Decodifica una cadena Base64 a su formato original.
 * Si el dato decodificado es JSON, lo convierte de nuevo a objeto.
 *
 * @param {string} encodedData - Cadena codificada en Base64.
 * @returns {string|object} - Cadena o objeto decodificado.
 */
export function decodeBase64 (encodedData) {
  const decodedData = atob(encodedData) // atob decodifica desde base64

  // Intenta parsear como JSON, si es posible
  try {
    return JSON.parse(decodedData)
  } catch (e) {
    return decodedData // Si no es un objeto JSON, devuelve la cadena tal cual
  }
}
