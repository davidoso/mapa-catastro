// http://www.bootstrapdash.com/demo/stellar-admin/jquery/pages/ui-features/popups.html
// Required files: sweetalert.min.js & alerts.js
(function($) {
  showSweetAlert = function(queryNumber, layerName, btnType) {
    'use strict';
    var title, text, queryNumberText;

    if(btnType === 'ftl') { // Set filter to layer title and text. 'ftl' in search_datatable.js
      if(queryNumber == 1) {
        title = '¿Desea reemplazar la consulta que ya existe?';
        queryNumberText = 'su consulta se eliminará';
      }
      else {
        title = '¿Desea reemplazar las ' + queryNumber + ' consultas que ya existen?';
        queryNumberText = 'sus ' + queryNumber + ' consultas se eliminarán';
      }
      text = 'Si agrega la capa: ' + layerName + ' (SIN FILTROS), ' + queryNumberText + ' de la tabla de búsqueda porque el mapa desplegará todos los elementos de la capa dentro del área de influencia.';
    }
    else { // Set layer to filter title and text. 'ltf' in search_datatable.js
      title = '¿Desea reemplazar la capa que ya existe?';
      text = 'Si agrega esta consulta, la capa: ' + layerName + ' (SIN FILTROS) se eliminará de la tabla de búsqueda porque el mapa desplegará solo los elementos que cumplen esta condición, en lugar de todos.'
    }

    swal({
      title: title,
      text: text,
      icon: 'warning',
      buttons: {
        cancel: {
          text: "Cancelar",
          value: null,
          visible: true,
          className: "btn btn-danger",
          closeModal: true,
        },
        confirm: {
          text: "Aceptar",
          value: true,
          visible: true,
          className: "btn btn-primary btn-" + btnType,
          closeModal: true
        }
      }
    })
  }
})(jQuery);

/*if(type === 'basic') {
  swal({
    text: 'Any fool can use a computer',
    button: {
      text: "OK",
      value: true,
      visible: true,
      className: "btn btn-primary"
    }
  })
} // basic
else if(type === 'success-message') {
  swal({
    title: 'Congratulations!',
    text: 'You entered the correct answer',
    icon: 'success',
    button: {
      text: "Continue",
      value: true,
      visible: true,
      className: "btn btn-primary"
    }
  })
} // success-message
else if(type === 'auto-close') {
  swal({
    title: 'Auto close alert!',
    text: 'I will close in 2 seconds.',
    timer: 2000,
    button: false
  }).then(
    function () {},
      // handling the promise rejection
    function (dismiss) {
      if (dismiss === 'timer') {
        console.log('I was closed by the timer')
      }
  })
} // auto-close
else if(type === 'warning-message-and-cancel') {}*/