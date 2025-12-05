// utilidades.js

function limpiar (variable) {
  if (variable && variable.value !== undefined) {
    // Si es una ref, resetea el valor a null (o a su valor inicial)
    variable.value = null
  } else if (typeof variable === 'object' && variable !== null) {
    // Si es un objeto (reactivo o plano), itera sobre las propiedades y resetea a null
    for (let key in variable) {
      if (variable.hasOwnProperty(key)) {
        variable[key] = null
      }
    }
  } else if (Array.isArray(variable)) {
    // Si es un array, limpia todos los elementos
    variable.length = 0 // O variable.splice(0, variable.length);
  } else {
    // Para otros tipos (strings, números, etc.), resetea a null o undefined
    variable = null // O variable = undefined;
  }
}

export { limpiar } // Exporta la función para poder usarla en otros archivos
