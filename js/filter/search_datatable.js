$(document).ready(function() {
    var tabla = $("#myDataTable").DataTable({
        /*"columnDefs": [{
            "targets": [0], // First column (normally id)
            "visible": false
        }],
        "ajax": {
            "url": "http://localhost/projectfolder/index.php/controller/function",
            "type": "post",
            "datatype": "json"
        },*/
        // https://datatables.net/reference/option/dom
        dom: '<"row"<"col-sm-8 my-dt-title"><"col-sm-4"f>>t<"row"<"col-sm-6"i><"col-sm-6"p>>',
        initComplete: function() {
            $('.my-dt-title').html("<h1>Tabla de búsqueda</h1>");
        },
        "columns": [
            {"data":"capa"},
            {"data":"campo"},
            {"data":"valor"},
            {"data":"total"},
            {
                "target": -2,
                "data": "opt_edit",
                "defaultContent": "<button id='btn_edit' class='btn btn-outline-warning btn-sm' title='Editar consulta'><i class='fas fa-pencil-alt'></i></button>",
                "searchable": false,
                "orderable": false
            },
            {
                "target": -1,
                "data": "opt_delete",
                "defaultContent": "<button id='btn_delete' class='btn btn-outline-danger btn-sm' title='Eliminar consulta'>&nbsp;<i class='fas fa-trash'></i></button>",
                "searchable": false,
                "orderable": false
            }
        ],
        "order": [ [0, "asc"], [1, "asc"] ], // Sort by layer and column
        /*"language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },*/
        "pageLength": 5,
        "pagingType": "simple_numbers",
        "lengthChange": false
    }); // $("#myDataTable").DataTable()


    $(document).on("keyup", "#inputValor", function(e) {
        if(e.which == 13) { // Enter keycode
            this.value = this.value.toUpperCase().replace(/\s{2,}/g, " ").trim(); // Same as focusout
            var capa = document.getElementById("cbCapas").value;
            var campo = document.getElementById("cbCampos").value;
            var valor = this.value;

            if(valor != '') {
                // Validate at least 4 alphanumeric characters in case the user copy-paste some text
                re = /^[A-Z0-9Ñ\s]{4,}$/;
                if(!re.test(valor)) {
                    alert("Ingrese al menos 4 caracteres alfanuméricos");
                }
                else {
                    addIfNotDuplicate(capa, campo, valor);
                }
            }
        }
    });

    $(document).on("change", "#cbValores", function() {
        var capa = document.getElementById("cbCapas").value;
        var campo = document.getElementById("cbCampos").value;
        var valor = document.getElementById("cbValores").value;

        addIfNotDuplicate(capa, campo, valor);
    });

    // https://datatables.net/reference/api/row().remove()
    $("#myDataTable tbody").on('click','[id=btn_delete]',function(){
        tabla.row($(this).parents('tr')).remove().draw();
    });

    function addIfNotDuplicate(capa, campo, valor) {
        var data = tabla.data();
        var totalRows = tabla.data().count();
        //console.log("Total rows: " + totalRows);

        if(totalRows == 0) {
            addQuery(capa, campo, valor);
        }
        else {
            var duplicate = false;
            for(i = 0; i < totalRows; i++) {
                if(capa == data[i].capa && campo == data[i].campo && valor == data[i].valor) {
                    duplicate = true;
                    alert("Ya se agregó la consulta");
                    break;
                }
            }
            if(!duplicate) {
                addQuery(capa, campo, valor);
            }
        }
    }

    function addQuery(capa, campo, valor) { // Add filter either from inputValor or cbValores
        tabla.row.add({
            "capa": capa,
            "campo": campo,
            "valor": valor,
            // "total": Math.floor(Math.random() * 99) // Random number from 0 to 100 (testing)
            "total": "-",
            "opt_edit": "<button id='btn_edit' class='btn btn-outline-warning btn-sm' title='Editar consulta'><i class='fas fa-pencil-alt'></i></button>",
            "opt_delete": "<button id='btn_delete' class='btn btn-outline-danger btn-sm' title='Eliminar consulta'>&nbsp;<i class='fas fa-trash'></i></button>"
         }).draw();
    }

    /*$("#btnQuery").on( "click", function () {

    });*/
}); // $(document).ready()