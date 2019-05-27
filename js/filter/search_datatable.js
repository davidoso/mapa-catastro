$(document).ready(function() {
    var con = 1;
    var numero =0;

    var tabla = $("#myDataTable").DataTable({
        // https://datatables.net/reference/option/dom
        dom: '<"row"<"col-sm-8 my-dt-title"><"col-sm-4"f>>t<"row"<"col-sm-6"i><"col-sm-6"p>>',
        initComplete: function() {
            $('.my-dt-title').html("<h1>Tabla de búsqueda</h1>");
        },
        "columns": [ 
            {   
                "data": "icono",
                "searchable": false,
                "orderable": false,
             
            },
            {"data":"capa"},
            {"data":"campo"},
            {"data":"valor"},
            {"data":"total"},
            
           /* {
                "className":"details-control",
                "target": -3,
                "data": "opt_plus",
                "defaultContent": "<button id='btn_plus'  class='btn btn-outline-success btn-sm' title='Todos los resultados'>&nbsp;<i class='fas fa-plus'></i></button>",
                "searchable": false,
                "orderable": false
            },*/
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
        "lengthChange": false,
        "order": [[ 3, "desc" ]]
    }); // $("#myDataTable").DataTable()

    $(document).on("keyup", "#inputValor", function(e) {
        
        if(e.which == 13) { // Enter keycode
            this.value = this.value.toUpperCase().replace(/\s{2,}/g, " ").trim(); // Same as focusout in filter.js
            var icono = document.getElementById("cbCapas").value;
            icono = icono.split(' ').join('_');
            icono = icono.replace("á", "a");
            icono = icono.replace("é", "e");
            icono = icono.replace("í", "i");
            icono = icono.replace("ó", "o");
            icono = icono.replace("ú", "u");
            
            
          
            var capa = document.getElementById("cbCapas").value;
            var campo = document.getElementById("cbCampos").value;
            var valor = this.value;
            
            
            icono = "<img class='center' src='images/mapMarkers/" + icono + ".png'>";
            if(valor != '') { // Do not show notification if the input is empty
                // Validate at least 4 alphanumeric characters in case the user copy-paste some text
                re = /^[A-Z0-9ÁÉÍÓÚÑ\s]{4,}$/;
                if(!re.test(valor)) {
                    showToastNotif('Nombre inválido', 'Ingrese al menos 4 caracteres alfanuméricos', 'bottom-right', 'error');
                }
                else {
                    if(valor.length > 30) { // In case an advanced user changes the input maxlength attribute
                        showToastNotif('Nombre inválido', 'Ingrese no más de 30 caracteres alfanuméricos', 'bottom-right', 'error');
                    }
                    else {
                        checkBeforeAddFilter(icono, capa, campo, valor);
                    }
                }
            }
        }
    });

    $(document).on("change", "#cbValores", function() {
        //icono error ?
        var icono = document.getElementById("cbCapas").value;
        icono = icono.toLowerCase();
        icono = icono.split(' ').join('_');
        icono = icono.replace("á", "a");
        icono = icono.replace("é", "e");
        icono = icono.replace("í", "i");
        icono =  icono.replace("ó", "o");
        icono =  icono.replace("ú", "u");
       
       
        icono = icono = "<img class='center' src='images/mapMarkers/" + icono + ".png'>";
        var capa = document.getElementById("cbCapas").value;
        var campo = document.getElementById("cbCampos").value;
        var valor = document.getElementById("cbValores").value;

        checkBeforeAddFilter(icono, capa, campo, valor);
    });

    // https://stackoverflow.com/questions/13343566/set-select-option-selected-by-value
    $("#myDataTable tbody").on('click', '[id=btn_edit]', function() {
        var data = tabla.row( $(this).parents('tr') ).data();
        var nuevoMessage = "campo/valor";

        if(data.campo != '(SIN FILTROS)') { // Change option in #cbCampos in case the row is a single filter
            globalColumnToEdit = data.campo;
            nuevoMessage = "valor";
        }
        else {
            globalColumnToEdit = '';
        }

        $("#cbCapas").val(data.capa).change(); // Change selected option in #cbCapas

        /* Delete current row because it will be added again with a new value (or even a whole new row
        if the user selects another layer and column) */
        tabla.row( $(this).parents('tr') ).remove().draw();
        showToastNotif('Modo edición habilitado', 'Seleccione un nuevo ' + nuevoMessage + ' para reemplazar la consulta anterior', 'bottom-right', 'success');
    });

    // https://datatables.net/reference/api/row().remove()
    $("#myDataTable tbody").on('click', '[id=btn_delete]', function() {
        tabla.row( $(this).parents('tr') ).remove().draw();

        showToastNotif('Consulta eliminada', 'La consulta se ha eliminado de la tabla de búsqueda', 'bottom-right', 'success');
    });


    // https://stackoverflow.com/questions/25866466/delete-a-row-from-a-datatable
    // https://datatables.net/forums/discussion/43162/removing-rows-from-a-table-based-on-a-column-value
    // https://datatables.net/reference/type/row-selector
    $(document).on("click", ".btn-ftl", function() { // Delete single filter(s) to layer rows (ftl)
        var capa = document.getElementById("cbCapas").value;

        tabla.rows( function (idx, data, node) {
            return data.capa === capa;
        }).remove().draw(); // Remove at least one row

        addLayer(capa); // Add layer row
    });

    $(document).on("click", ".btn-ltf", function() { // Delete layer to single filter(s) rows (ltf)
        var icono = document.getElementById("cbCapas").value;
        var capa = document.getElementById("cbCapas").value;
        var campo = document.getElementById("cbCampos").value;
        var valor = "";
        if(document.getElementById("cbValores"))
            valor = document.getElementById("cbValores").value;
        else
            valor = document.getElementById("inputValor").value;

        tabla.rows( function (idx, data, node) {
            return data.capa === capa;
        }).remove().draw(); // Remove one and only one row (layer)

        addFilter(icono, capa, campo, valor); // Add single filter row
    });

  

    function checkBeforeAddFilter(icono,capa, campo, valor) {
        var data = tabla.data();
        var totalRows = data.count();

        if(totalRows == 0) { // If the table is empty, add the row without checking anything
            addFilter(icono, capa, campo, valor);
        }
        else {
            var duplicateRow, layerRow = false;

            for(i = 0; i < totalRows; i++) {
                if(data[i].capa == capa && data[i].campo == campo && data[i].valor == valor) {
                    duplicateRow = true;
                    showToastNotif('Consulta duplicada', 'La consulta ya existe en la tabla de búsqueda', 'bottom-right', 'warning');
                    break;
                }
                if(data[i].capa == capa && data[i].campo == '(SIN FILTROS)') {
                    layerRow = true;
                    showSweetAlert(1, capa, 'ltf'); // ltf stands for 'layer to filter'
                    break;
                }
            }
            // Add the row if it doesn't exist already and there's no the same layer without filters
            if(!duplicateRow && !layerRow) {
                addFilter(icono, capa, campo, valor);
            }
        } // else(totalRows == 0)
    }

    function addFilter(icono, capa, campo, valor) { // Add row either from #inputValor or #cbValores
        tabla.row.add({
            "icono": icono,
            "capa": capa,
            "campo": campo,
            "valor": valor,
            // "total": Math.floor(Math.random() * 99) // Random number from 0 to 100 (testing purposes)
            "total": "-",
            "opt_edit": "<button id='btn_edit' class='btn btn-outline-warning btn-sm' title='Editar consulta'><i class='fas fa-pencil-alt'></i></button>",
            "opt_delete": "<button id='btn_delete' class='btn btn-outline-danger btn-sm' title='Eliminar consulta'>&nbsp;<i class='fas fa-trash'></i></button>"
         }).draw();

        showToastNotif('Consulta agregada', 'Capa: ' + capa + ', en campo: ' + campo + ', con valor: ' + valor, 'bottom-right', 'info');
    }

    $("#btnQuery").on("click", function () {
        queryMap();
    });

    //$("#dropdown-options li a").click(function() {}) Another way to define function
    $("#dropdown-options li a").on("click", function () {
        var opt = $(this).data("opt");
        switchOption(opt);
    });

    $(".nav-item .label-options").on("click", function () {
        var opt = $(this).data("opt");
        if(opt == 0) {
            queryMap(); // First label option, same as btnQuery in split button
        }
        else {
            switchOption(opt); // Same remaining 4 labels as dropdown options
            
        }
        
    });

    function switchOption(opt) {
        switch(opt) {
            case 1: // AGREGAR CAPA
                checkBeforeAddLayer();
                break;
            case 2: // LIMPIAR MAPA
                clearMap();
                break;
            case 3: // LIMPIAR TODO
                clearAll();
                break;
            case 4: // AYUDA
                $('#modal-help').modal('show');
                break;
        }
    }

    function checkBeforeAddLayer() {
        var capa = document.getElementById("cbCapas").value;
        if(capa == '') {
            showToastNotif('Capa no seleccionada', 'Seleccione una capa, por favor', 'bottom-right', 'warning');
        }
        else {
            var data = tabla.data();
            var totalRows = data.count();
            var duplicateRow = false;
            var sameLayerRows = 0;
           
            if(totalRows == 0) { // If the table is empty, add the row without checking anything
                addLayer(capa);
            }
            else {
                for(i = 0; i < totalRows; i++) {
                    if(data[i].capa == capa)
                        // If the row already exists, alert and do nothing after the for loop ends
                        if(data[i].campo == '(SIN FILTROS)') {
                            duplicateRow = true;
                            showToastNotif('Consulta duplicada', 'La consulta ya existe en la tabla de búsqueda', 'bottom-right', 'warning');
                        }
                        else
                            sameLayerRows++;
                }
                if(!duplicateRow)
                    // The table has 1+ rows but no one is about the current layer, so it's ok to add it
                    if(sameLayerRows == 0) {
                        addLayer(capa);
                    }
                    else {
                        showSweetAlert(sameLayerRows, capa, 'ftl'); // ftl stands for 'filter to layer'
                    }
            } // else(totalRows == 0)
            
        } // else(capa == '')
    }

    function addLayer(capa) { // Add row either from dropdown or label option click in sidebar

        var icono 
        icono = capa.toLowerCase()
        icono = icono.split(' ').join('_');
        icono = icono.replace("á", "a");
        icono = icono.replace("é", "e");
        icono = icono.replace("í", "i");
        icono = icono.replace("ó", "o");
        icono = icono.replace("ú", "u");
       
       
        tabla.row.add({
            "icono": "<img class='center' src='images/mapMarkers/" + icono + ".png'>",
            "capa": capa,
            "campo": "(SIN FILTROS)",
            "valor": "(SIN FILTROS)",
            // "total": Math.floor(Math.random() * 99) // Random number from 0 to 100 (testing purposes)
            "total": "-",
            "opt_edit": "<button id='btn_edit' class='btn btn-outline-warning btn-sm' title='Editar consulta'><i class='fas fa-pencil-alt'></i></button>",
            "opt_delete": "<button id='btn_delete' class='btn btn-outline-danger btn-sm' title='Eliminar consulta'>&nbsp;<i class='fas fa-trash'></i></button>"
        }).draw();

        showToastNotif('Consulta agregada', 'Capa: ' + capa + ' (SIN FILTROS)', 'bottom-right', 'info');
    }

    function validQuery() {
        if(tabla.data().count() == 0) {
            showToastNotif('Tabla de búsqueda vacía', 'Seleccione al menos una capa y agregue una consulta con o sin filtros (campo/valor)', 'bottom-right', 'error');
            return false;
        }
        else {
            if(boxSource.getFeatures().length == 0) {
                showToastNotif('Sin área de influencia', 'Trace una figura en el mapa para delimitar el área de influencia donde se aplicarán las consultas de la tabla de búsqueda', 'bottom-right', 'error');
                return false;
            }
            return true;
        }
    }

    function queryMap() {
        if(validQuery()) {
            flickrSource.clear();   // Delete UTM2DEC points (map markers) added during the previous query
            if(selectedMarker) {    // Delete last selected map marker in case it exists
                selectedMarker.getFeatures().clear();
            }
            $('body').css('cursor', 'wait');
            continueIfQueryIsValid(tabla.data()); // Calls getMapTotals() and getMapMarkers() in map.js
        } // if(validQuery())
    }

    function clearMap() {
        boxSource.clear();      // Clear features in boxSource vector layer drawn with addInteraction() in map.js
        flickrSource.clear();   // Clear map markers from previous query drawn with printMarkers() in map.js
        if(selectedMarker)      // Clear last selected map marker if exists
            selectedMarker.getFeatures().clear();
    }

    function clearAll() {
        
        clearMap();                                                 // Clear map
        tabla.clear().draw();                                       // Clear datatable
        document.getElementById("rbtnOR").checked = true;           // Select OR operator
        // Reset 4 selects in sidebar
        document.getElementById("divValores").innerHTML = '<label class="nav-link"><span class="menu-title">Seleccione un campo..</span><i class="fas fa-fw fa-filter"></i></label>';
        document.getElementById("divCampos").innerHTML = '<label class="nav-link"><span class="menu-title">Seleccione una capa..</span><i class="fas fa-fw fa-layer-group"></i></label>';
        // https://stackoverflow.com/questions/41883537/how-to-reset-value-of-bootstrap-select-after-button-click
        $("#cbCapas").val("default").selectpicker("refresh");
        $("#cbShapes").val("Box").change();
        var x = document.getElementById("map");
        var y = document.getElementById("map-table");
        var z = document.getElementById("mouse-position");
            x.style.display = "block";
            z.style.display = "block";
            y.style.display = "none";

        var m = document.getElementById("boton-mapa");
        var t = document.getElementById("boton-tabla");
            m.style.color ="orange";
            t.style.color ="white";

        var div_t = document.createElement("div");
        var title = document.createElement("h1");
        div_t.className = "div_t";
        div_t.setAttribute("align", "center");
        title.innerHTML = "No hay datos que mostrar";
        div_t.appendChild(title);

       
        var exportar_b = document.getElementById("exportar-boton");
        exportar_b.style.visibility = "hidden";

        remElement(document.getElementById('map-table'));
        y.appendChild(div_t);
    }

    // Removing eleemnts usig outerHTML property
    function remElement(obj) {
        obj.innerHTML="";
    }
    ///https://jsfiddle.net/mnsinger/65hqxygo/
    function exportTableToCSV($table, filename) {
    
        var $rows = $table.find('tr:has(td),tr:has(th)'),
            // Temporary delimiter characters unlikely to be typed by keyboard
            // This is to avoid accidentally splitting the actual contents
            tmpColDelim = String.fromCharCode(11), // vertical tab character
            tmpRowDelim = String.fromCharCode(0), // null character
    
            // actual delimiter characters for CSV format
            colDelim = '","',
            rowDelim = '"\r\n"',
    
            // Grab text from table into CSV formatted string
            csv = '"' + $rows.map(function (i, row) {
                var $row = $(row), $cols = $row.find('td,th');
    
                return $cols.map(function (j, col) {
                    var $col = $(col), text = $col.text();
    
                    return text.replace(/"/g, '""'); 
    
                }).get().join(tmpColDelim);
    
            }).get().join(tmpRowDelim)
                .split(tmpRowDelim).join(rowDelim)
                .split(tmpColDelim).join(colDelim) + '"',

            // Data 
            csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);
            console.log(csv);
            
        	if (window.navigator.msSaveBlob) { 
        		window.navigator.msSaveOrOpenBlob(new Blob([csv], {type: "text/plain;charset=utf-8;"}), "Datos_consultas.csv")
        	} 
        	else {
        		$(this).attr({ 'download': filename, 'href': csvData, 'target': '_blank' }); 
        	}
    }
    
    $("#exportar").on('click', function (event) {
        exportTableToCSV.apply(this, [$('#myDataSetTable'), 'Datos_consultas.csv']);

    });

     
}); // $(document).ready()
/////////////////////////////////////////////////////////////////////////////////////////////////////////



 
   
