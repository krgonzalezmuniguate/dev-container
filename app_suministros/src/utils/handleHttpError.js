export function handleHttpError (error, setAlert, setErrors = null) {
  if (error.response) {
    setAlert(
      `Error ${error.response.status}: ${error.response.data?.message || 'Error del servidor'}`,
      'danger',
      'Error'
    )
    if (setErrors && error.response.data.errors) {
      setErrors(error.response.data.errors)
    }
  } else if (error.request) {
    setAlert('No se recibió respuesta del servidor', 'danger', 'Error')
  } else {
    setAlert(error, 'danger', 'Error')
  }
}
