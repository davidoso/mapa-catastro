<!DOCTYPE html>
<html lang="es-MX">
<head>
	<?php $this->load->view('sections/head'); ?>
</head>
<body>
<!--
    IMPORTANTE:
        1. Este header se debe reemplazar con la barra de navegación azul del mapa cartográfico
        2. El botón "Aplicar filtro" de la barra de herramientas del mapa puede abrir este proyecto (el frontend es sólo esta vista filter.php que carga otros vistas php al final con scripts JS y modales)
-->
	<?php $this->load->view('sections/header'); ?>

    <!-- <form action="#" id="formFilter" onsubmit="addFilter(); return false"> -->
        <div class="row" style="background: yellow; height: 84vh;">
            <div class="col-sm-5" style="height: 100%;">
                <!-- <div class="col-sm-12">
                    <div class="col-sm-offset-1 col-sm-11" style="text-align: right; border-bottom: 1px solid #F3A530;">
                    <label>Preguntas frecuentes</label>
	                <a data-toggle="modal" data-target="#myModalHelp" style="cursor: pointer;">
	                    <img src="images/help.png" class="img-circle" title="Preguntas frecuentes" style="height: 32px; width: 32px; margin-bottom: 15px;">
	                </a>
                    </div>
                </div> -->
     
 <!-- <table class="table table-striped table-bordered" id="myDataTable" width="100%" cellspacing="0">
                <thead class="tbl-blue-th">
                    <tr>
                        <th width="25%">Capa</th>
                        <th width="50%">Campo</th>
                        <th width="20%">Valor</th>
                        <th width="20%">Totales</th>
                        <th width="5%"><i class="fa fa-download" title="Descargar"></i></th>
                    </tr>
                </thead>
                <tfoot class="tbl-blue-th">
                    <tr>
                        <th width="25%">Capa</th>
                        <th width="50%">Campo</th>
                        <th width="20%">Valor</th>
                        <th width="20%">Totales</th>
                        <th width="5%"><i class="fa fa-download" title="Descargar"></i></th>
                    </tr>
                </tfoot>
                <tbody>
                <td> MONUMENTOS HSITORICOS</td>
                <td> GENERO ARQUITECTONICO</td>
                <td> JARDIN HISTORICO</td>
                <td> -</td>
                </tbody>
            </table> -->
            <div class="panel-group" id="accordion"> <!-- panel-group -->
                    
                    <div class="panel panel-success" style="border-top: #aaa;"> <!-- accordion -->
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a title="Mostrar/ocultar tabla de filtros de búsqueda agregados" data-toggle="collapse" data-parent="#accordion" href="#filtrosAgregados" onclick="return expandCollapseAccordion()">
                                Filtros de búsqueda agregados <i id="faFiltros" class="fa fa-compress" aria-hidden="true"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="filtrosAgregados" class="collapse in"> <!-- panel-collapse -->
                            <div class="panel-body"> <!-- panel-body -->


                    <div class="col-sm-6">
                        <div class="btn-group btn-flex">
                            <button type="button" class="btn btn-warning">
                    <i class="fa fa-search fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;CONSULTAR</button>
                            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu pull-right" role="menu" id="dropdown-options">
                                <li style="width: 90%;"><a href="#"><i class="fa fa-plus fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;AGREGAR CAPA</a></li>
                                <li style="font-family: helvetica; width: 10%;"><i id='iHelp' class='fa fa-info-circle' data-toggle='tooltip' data-placement='right' title='Agregar capa sin filtros a la tabla de búsqueda'></i></li>
                                <li style="width: 90%;"><a href="#"><i class="fa fa-map-marker fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;LIMPIAR MAPA</a></li>
                                <li style="font-family: helvetica; width: 10%;"><i id='iHelp' class='fa fa-info-circle' data-toggle='tooltip' data-placement='right' title='Limpiar los marcadores y polígonos trazados en el mapa'></i></li>
                                <li style="width: 90%;"><a href="#"><i class="fa fa-undo fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;LIMPIAR TODO</a></li>
                                <li style="font-family: helvetica; width: 10%"><i id='iHelp' class='fa fa-info-circle' data-toggle='tooltip' data-placement='right' title='Limpiar mapa y tabla de búsqueda'></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-offset-1 col-sm-5">
                        <a data-toggle="modal" data-target="#myModalHelp" style="cursor: pointer;">
                            <img src="images/help.png" class="img-circle" title="Preguntas frecuentes" style="height: 28px; width: 28px; margin-bottom: 15px;">
                        </a>
                    </div>

                <div class="col-sm-12 divOption">
                    <div class="col-sm-3" style="text-align: right;">
                        <h4 class="text-pink">Capa: </h4>
                    </div>
                    <div class="col-sm-8">
                        <select class="form-control selectpicker show-tick" id="cbCapas" name="cbCapas" title="Seleccione la capa en donde buscar.." data-live-search="true" data-live-search-placeholder="Buscar capa.." data-live-search-style="contains">
                            <?php foreach($cbCapas as $key => $value): ?>
                                <optgroup label="<?php echo $key; ?>">
                                    <?php foreach($value as $index => $capa): ?>
                                        <option data-tokens="<?php echo $key; ?>" value="<?php echo $capa['capa']; ?>"><?php echo $capa['capa']; ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 divOption">
                    <div class="col-sm-3" style="text-align: right;">
                        <h4 class="text-pink">Campo: </h4>
                    </div>
                    <div class="col-sm-8" id="divCampos">
                    </div>
                </div>
                <div class="col-sm-12 divOption">
                    <div class="col-sm-3" style="text-align: right;">
                        <h4 class="text-pink">Valor: </h4>
                    </div>
                    <div class="col-sm-8" id="divValores">
                    </div>
                </div>
                <div class="col-sm-12 divOption">
                    <div class="col-sm-6" style="text-align: right;">
                        <h4 class="text-pink">Opción para unir condiciones: </h4>
                    </div>
                    <div class="col-sm-6" id="divRbtns">
                        <center>
                            <input type="radio" name="booleanOps" id="rbtnOR" value="OR" title="UNIR CONDICIONES MEDIANTE OPERADOR OR" checked>
                            <b><label for="rbtnOR" title="UNIR CONDICIONES MEDIANTE OPERADOR OR" id="lbl_rbtnOR"> O (OR)</label></b>
                            <input type="radio" name="booleanOps" id="rbtnAND" value="AND" title="UNIR CONDICIONES MEDIANTE OPERADOR AND">
                            <b><label for="rbtnAND" title="UNIR CONDICIONES MEDIANTE OPERADOR AND"> Y (AND)</label></b>
                        </center>
                    </div>
                </div>
                <div class="col-sm-12 divOption">
                    <div class="col-sm-6" style="text-align: right;">
                        <h4 class="text-pink">Área de influencia: </h4>
                    </div>
                    <div class="col-sm-5">
                        <select class="form-control selectpicker show-tick" id="cbShapes" name="cbShapes">
                            <option value="Box">RECTÁNGULO</option>
                            <option value="Square">CUADRADO</option>
                            <option value="Polygon">POLÍGONO</option>
                            <option value="None">NINGUNA</option>
                        </select>
                    </div>
                </div>
                </div> <!-- panel-body -->
                        </div> <!-- panel-collapse -->
                    </div> <!-- accordion -->
                </div> <!-- panel-group -->

            </div> <!-- col-sm-5 -->
            <!-- <div class="row">
                <div class="col-sm-offset-3 col-sm-7">
                    <button type="button" title="AGREGAR CAPA SIN FILTROS A LA TABLA DE BÚSQUEDA" class="btn btn-success btn-block" onclick="addLayer()" id="btnAddLayer">AGREGAR CAPA&nbsp;
                    <i class="fa fa-plus" aria-hidden="true"></i></button>
                </div>
            </div> -->
            <!-- <div class="col-sm-12" style="margin-left: 15px; margin-bottom: 20px;">
                <div class="col-sm-6">
                    <button type="submit" title="AGREGAR FILTRO A LA TABLA DE BÚSQUEDA" class="btn btn-success btn-block" id="btnAddFilter">AGREGAR FILTRO&nbsp;
                    <i class="fa fa-plus" aria-hidden="true"></i></button>
                </div>
                <div class="col-sm-6">
                    <button type="button" title="CONSULTAR EN EL MAPA" class="btn btn-primary btn-block" id="btnQuery">CONSULTAR&nbsp;
                    <i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
            </div>
            <div class="col-sm-12" style="margin-left: 15px;">
                <div class="col-sm-6">
                    <button type="button" title="LIMPIAR LOS MARCADORES Y POLÍGONOS TRAZADOS EN EL MAPA" class="btn btn-danger btn-block" onclick="clearMap()" id="btnClearBoxes">LIMPIAR MAPA&nbsp;
                    <i class="fa fa-map-marker" aria-hidden="true"></i></button>
                </div>
                <div class="col-sm-6">
                    <button type="button" title="LIMPIAR TODOS LOS FILTROS" class="btn btn-danger btn-block" onclick="deleteFilters()" id="btnClearAll">LIMPIAR TODO&nbsp;
                    <i class="fa fa-undo" aria-hidden="true"></i></button>
                </div>
            </div> -->

        <div class="col-sm-7" style="height: 100%;">
        <div id="navbar-main" style="margin-bottom: 20px;"> <!-- navbar-main -->
                
                <div class="panel-group" id="accordion"> <!-- panel-group -->
                    
                    <div class="panel panel-success" style="border-top: #aaa;"> <!-- accordion -->
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a title="Mostrar/ocultar tabla de filtros de búsqueda agregados" data-toggle="collapse" data-parent="#accordion" href="#filtrosAgregados" onclick="return expandCollapseAccordion()">
                                Filtros de búsqueda agregados <i id="faFiltros" class="fa fa-compress" aria-hidden="true"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="filtrosAgregados" class="collapse in"> <!-- panel-collapse -->
                            <div class="panel-body"> <!-- panel-body -->
                                <!-- <h3 align="center" class="text-info"><strong>Filtros agregados</strong></h3> -->
                                <div class="row">
                                    <div class="col-sm-11" style="width: 88%;">
                                        <table class="table table-bordered">
                                            <thead id="tblFiltrosH">
                                                <th width="20%">CAPA</th>
                                                <th width="35%">CAMPO</th>
                                                <th width="35%">VALOR</th>
                                                <th width="10%">OPCIONES</th>
                                            </thead>
                                            <tbody id="tblFiltros">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm-1" style="width: 12%; padding: 0px;">
                                        <table class="table">
                                            <thead id="tblTotalesH">
                                                <th width="120%">TOTALES</th>
                                            </thead>
                                            <tbody id="tblTotales">
                                            </tbody>
                                        </table>
                                    </div>
                                </div> <!-- row -->
                            </div> <!-- panel-body -->
                        </div> <!-- panel-collapse -->
                    </div> <!-- accordion -->
                </div> <!-- panel-group -->
            </div> <!-- navbar-main -->
            <div class="panel panel-default" style="border-color: #aaa;">
                <div class="panel-heading" style="border-bottom: 1px solid #bbb;">Mapa</div>
                <!-- Comentar este panel-body para mostrar el mapa sin bordes -->
                <!-- <div class="panel-body panel-height"> -->
                    <div id="map" class="map"></div>
                    <div id="mouse-position" class="text-success" align="right"></div>
                <!-- </div> -->
            </div>
        </div> <!-- col-sm-8 -->
    <!-- </form> -->
</div>



<script>
    // Variables globales, se reinician cada vez que se pulsa Limpiar Todo con clear(clearAll)
    var addedFiltersCounter = 0;
    var rowIndexToUpdate = -1;
    var accordionIsCollapsed = false;
    var maxNumberOfBoxShapes = 2;

    function expandCollapseAccordion() {
        var divMap = document.getElementById("map");
        var faFiltros = document.getElementById("faFiltros");
        if(accordionIsCollapsed) {
            divMap.setAttribute("style", "height: 371px;");
            faFiltros.setAttribute("class", "fa fa-compress");
        }
        else {
            divMap.setAttribute("style", "height: 565px;");
            faFiltros.setAttribute("class", "fa fa-expand");
        }
        accordionIsCollapsed = !accordionIsCollapsed;
        divMap.innerHTML = "";
        map = new ol.Map({
            controls: ol.control.defaults().extend([
                new ol.control.FullScreen(),
                new ol.control.ScaleLine(),
                new ol.control.ZoomSlider()
            ]).extend([mousePositionControl]),
            target: 'map',
            layers: [layer, flickrLayer, boxLayer],
            view: view
        });

        /* The following 2 lines are needed because after the div map is expanded or collaped,
        the box-drawing mouse and map marker's modal on click interactions are restored.
        Map cursor is always ready to draw box or polygon shapes when map is clicked */
        addInteraction();

        /* Map cursor is ready to select mouseovered markers after successHandler(data)
        adds features to flickrSource */
        map.addInteraction(selectedMarker);
    }

    function clear(clearAll) {
		if(clearAll) {
            document.getElementById("cbCapas").selectedIndex = 0;
            document.getElementById("rbtnOR").checked = true;
            flickrSource.clear();       // Limpiar puntos UTM2DEC dibujados en el mapa con successHandler(data)
            addedFiltersCounter = 0;    // Reiniciar contador de filtros
            rowIndexToUpdate = -1;      // Reiniciar bandera para identificar fila a actualizar en la tabla
            maxNumberOfBoxShapes = 2;   // Reiniciar cantidad máxima de shapes dibujadas a la vez en el mapa
            clearMap();                 // Quitar polígonos dibujados y reiniciar coordenadas de box shape
		}
        document.getElementById("cbCampos").innerText = null;
        marginDivFiltros();
    }

    function clearMap() {
        boxSource.clear();          // Limpiar shapes dibujadas con addInteraction()
        flickrSource.clear();       // Limpiar marcadores de la consulta anterior
        if(selectedMarker)          // Limpiar último marcador seleccionado (si existe)
            selectedMarker.getFeatures().clear();
    }

    function marginDivFiltros() {
        var div = document.getElementById("divFiltros");
        div.innerText = null;
        div.setAttribute("style", "margin-bottom: 50px;"); // Mantener botones en misma posición
    }

    // Eliminar fila (filtro individual) en tabla de Filtros de Búsqueda
    function deleteRow(r) {
        var i = r.parentNode.parentNode.rowIndex - 1;
        document.getElementById("tblFiltros").deleteRow(i);

        // Si la tabla Totales tiene datos, eliminar la fila equivalente
        var tableT = document.getElementById("tblTotales");
        var tableTtotalRows = tableT.rows.length;
        if(tableTtotalRows != 0) {
            tableT.deleteRow(i);
        }
        else {
            tableT.innerHTML = "";
        }
    }

    function updateRow(r) {
        var table = document.getElementById("tblFiltros");
        var i = r.parentNode.parentNode.rowIndex - 1;
        rowIndexToUpdate = i;
        var cbCapasOps = document.getElementById("cbCapas").options;
        var capa = table.rows[i].cells[0].innerHTML;
        var campo = table.rows[i].cells[1].innerHTML;

        for(var i = 0; i < cbCapasOps.length; i++) {
            if(cbCapasOps[i].innerText == capa) {
                cbCapasOps[i].selected = true;
                switchSelectCapa();
                var cbCamposOps = document.getElementById("cbCampos").options;
                for(var j = 0; j < cbCamposOps.length; j++) {
                    if(cbCamposOps[j].innerText == campo) {
                        cbCamposOps[j].selected = true;
                        switchSelectCampo();
                        break; // cbCamposOps loop
                    }
                }
                break; // cbCapasOps loop
            }
        }
        toggleEditMode(true); // Habilitar modo edición
    }

    function deleteFilters() {
		document.getElementById("tblFiltros").innerHTML = "";
        document.getElementById("tblTotales").innerHTML = "";
		clear(true);
	}

	function addFilter() {
        var table = document.getElementById("tblFiltros");
        var capa = document.getElementById("cbCapas").options[cbCapas.selectedIndex].value;
        var campo = document.getElementById("cbCampos").options[document.getElementById("cbCampos").selectedIndex].value;
        var filterCanceled = false; // Bandera para cancelar la adición del filtro si el usuario lo decide
        var row;

        // Verificar si antes de agregar un filtro normal existe la misma capa sin filtros
        var rowToReplace = checkLayerWithoutFilters(capa, true);
        //alert("addFilter() -> Row to update/replace: " + rowToReplace);
        /* Si el modal no se abre, no existe la fila de capa sin filtros, por tanto el retorno es -1
        En este caso, se comprueba la respuesta cuando el modal aparece, que el usuario puede confirmar
        eliminar la fila con la capa sin filtros que ya existe o cancelar la adición del filtro actual */
        if(rowToReplace == -1) {
            if(rowIndexToUpdate != -1) {
                table.deleteRow(rowIndexToUpdate);
                row = table.insertRow(rowIndexToUpdate);
                rowIndexToUpdate = -1;
                toggleEditMode(false); // Deshabilitar modo edición
            }
            else {
                row = table.insertRow(-1);
            }
            addedFiltersCounter++;

            var cell0	= row.insertCell(0);
            cell0.id	= "capa" + addedFiltersCounter;
            var cell1	= row.insertCell(1);
            cell1.id	= "campo" + addedFiltersCounter;
            var cell2	= row.insertCell(2);
            cell2.id	= "filtro" + addedFiltersCounter;
            var cell3	= row.insertCell(3);
            cell3.id	= "botones";

            cell0.innerHTML = capa;
            cell1.innerHTML = campo;
            if(document.getElementById("cbValores"))
                cell2.innerHTML = document.getElementById("cbValores").options[document.getElementById("cbValores").selectedIndex].text;
            if(document.getElementById("inputValor"))
                cell2.innerHTML = document.getElementById("inputValor").value;

            cell3.innerHTML = '<button class="btnDeleteRow" type="button" onclick="deleteRow(this)" title="Eliminar"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></button><button type="button" onclick="updateRow(this)" title="Editar"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></button>';
            row.cells[3].setAttribute("style", "text-align: center;"); // Centrar botones Eliminar/Actualizar

            // Limpiar tabla Totales después de la inserción/edición de un filtro
            document.getElementById("tblTotales").innerHTML = "";
        }
    }

    function addLayer() {
        var capa = document.getElementById("cbCapas").options[cbCapas.selectedIndex].value;
        if(capa == "") {
            // 3 líneas necesarias para adaptar el mensaje de alerta
            $("#myModalMapWarnings").modal("show");
            $("#h3ModalMapWarning").text("No se ha seleccionado una capa");
            $("#messageModalMapWarning").text("Por favor, seleccione una capa para buscar todos los elementos en ella (sin filtros de búsqueda)");
        }
        else {
            var table = document.getElementById("tblFiltros");
            var totalRows = table.rows.length;
            var capaAddedFiltersCounter = 0;
            var alreadyAddedLayerFlag = false;

            for(var i = 0; i < totalRows; i++) {
                if(capa == table.rows[i].cells[0].innerHTML) {
                    capaAddedFiltersCounter++;
                    if("(SIN FILTROS)" == table.rows[i].cells[1].innerHTML) {
                        alreadyAddedLayerFlag = true;
                        break;
                    }
                }
            }
            /* No se había agregado ningún filtro de la misma capa antes,
            entonces se agrega la capa sin filtros en las columnas Campo y Valor (Posibilidad 1/3) */
            if(capaAddedFiltersCounter == 0) {
                addLayerInTable();
            }
            else {
                /* Si existe sólo una fila con el valor de la capa a agregar en la columna Capa
                hay 2 opciones: que sea la capa sin filtros o que sea un filtro normal */
                if(alreadyAddedLayerFlag) { // Posibilidad 2/3: capa sin filtros
                    $("#myModalMapWarnings").modal("show");
                    $("#h3ModalMapWarning").text("La capa " + capa + " (sin filtros) ya existe");
                    $("#messageModalMapWarning").text("La capa seleccionada ya existe en la tabla de búsqueda");
                }
                else {
                    if(capaAddedFiltersCounter == 1) { // Posibilidad 3.1/3: un filtro normal
                        var strAFC = "Ya existe un filtro de búsqueda<br>en la capa: " + capa;
                        var strFilterNum = "el filtro de búsqueda específico se eliminará";
                    }
                    else { // Posibilidad 3.2/3: más de un filtro normal
                        var strAFC = "Ya existen " + capaAddedFiltersCounter
                            + " filtros de búsqueda<br> en la capa: " + capa;
                        var strFilterNum = "los " + capaAddedFiltersCounter
                            + " filtros de búsqueda específicos se eliminarán";
                    }
                    document.getElementById("btnModalConfirm").setAttribute("onclick", "addLayerConfirm()");
                    // 3 líneas necesarias para adaptar el mensaje de alerta + botones Confirmar/Cancelar
                    $("#myModalMapWarnings2").modal("show");
                    document.getElementById("h3ModalMapWarning2").innerHTML = strAFC;
                    document.getElementById("messageModalMapWarning2").innerHTML = "Si agrega la capa, "
                        + strFilterNum + " ya que la consulta desplegará todos los elementos de la capa dentro del área de influencia. <span style='color: red;'>¿Desea continuar?</span>";
                } // else(alreadyAddedLayerFlag)
            } // else(capaAddedFiltersCounter == 0)
        } // else(capa == "")
    }

    function addLayerInTable() {
        var table = document.getElementById("tblFiltros");
        var capa = document.getElementById("cbCapas").options[cbCapas.selectedIndex].value;
        var row = table.insertRow(-1);

        addedFiltersCounter++;

        var cell0	= row.insertCell(0);
        cell0.id	= "capa" + addedFiltersCounter;
        var cell1	= row.insertCell(1);
        cell1.id	= "campo" + addedFiltersCounter;
        var cell2	= row.insertCell(2);
        cell2.id	= "filtro" + addedFiltersCounter;
        var cell3	= row.insertCell(3);
        cell3.id	= "botones";

        cell0.innerHTML = capa;
        cell1.innerHTML = "(SIN FILTROS)";
        cell2.innerHTML = "(SIN FILTROS)";
        cell3.innerHTML = '<button class="btnDeleteRow" type="button" onclick="deleteRow(this)" title="Eliminar"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></button><button type="button" onclick="updateRow(this)" title="Editar"><i class="fa fa-pencil fa-lg" aria-hidden="true"></i></button>';
        row.cells[3].setAttribute("style", "text-align: center;"); // Centrar botones Eliminar/Actualizar

        // Limpiar tabla Totales después de la inserción/edición de un filtro
        document.getElementById("tblTotales").innerHTML = "";
    }

    /* Eliminar 1 o más filtros ya existentes en la tabla para después agregar la capa sin filtros
    en las columnas Campo y Valor mediante addLayerInTable(). Este método es llamado por la
    Posibilidad 3.1/3: un filtro normal y Posibilidad 3.2/3: más de un filtro normal mencionadas en addLayer() */
    function addLayerConfirm() {
        var table = document.getElementById("tblFiltros");
        var capa = document.getElementById("cbCapas").options[cbCapas.selectedIndex].value;
        var totalRows = table.rows.length;
        var rowsToDelete = [];

        // Para que funcione no puede haber: Capa (Sin filtros) y Capa con +1 de un filtro normal a la vez
        for(var i = 0; i < totalRows; i++) {
            if(capa == table.rows[i].cells[0].innerHTML) {
                rowsToDelete.push(i);
            }
        }
        //alert("addLayerConfirm() -> Rows to delete: " + rowsToDelete);

        var rowsDeletedCounter = 0;
        for(var i = 0; i < rowsToDelete.length; i++) {
            document.getElementById("tblFiltros").deleteRow(rowsToDelete[i] - rowsDeletedCounter);
            rowsDeletedCounter++;
        }
        addLayerInTable();
        addLayerCancel();
    }

    /* Cerrar modal con botones para Confirmar/Cancelar adición de una capa que reemplaza los filtros previos
    o a la inversa, un filtro normal que reemplaza la fila con la capa sin filtros */
    function addLayerCancel() {
        $("#myModalMapWarnings2").modal("hide");
    }

    function addFilterConfirm() {
        var table = document.getElementById("tblFiltros");
        var capa = document.getElementById("cbCapas").options[cbCapas.selectedIndex].value;
        var campo = document.getElementById("cbCampos").options[document.getElementById("cbCampos").selectedIndex].value;
        var rowToReplace = checkLayerWithoutFilters(capa, false);

        table.rows[rowToReplace].cells[1].innerHTML = campo;
        if(document.getElementById("cbValores"))
            table.rows[rowToReplace].cells[2].innerHTML = document.getElementById("cbValores").options[document.getElementById("cbValores").selectedIndex].text;
        if(document.getElementById("inputValor"))
            table.rows[rowToReplace].cells[2].innerHTML = document.getElementById("inputValor").value;

        /* Si el botón Agregar Filtro se encuentra en modo edición y se edita un filtro con una capa que ya
        estaba en la tabla sin filtros previos, se reemplaza el texto "(SIN FILTROS)" en las columnas
        Campo y Valor con los valores nuevos y la fila que originalmente se iba a editar se elimina.
        Si la fila a editar es la misma capa (SIN FILTROS), no se elimina porque ya se reemplazo el texto */
        if(rowIndexToUpdate != -1) {
            if(rowIndexToUpdate != rowToReplace) {
                table.deleteRow(rowIndexToUpdate);
            }
            rowIndexToUpdate = -1;
            toggleEditMode(false); // Deshabilitar modo edición
        }

        // Limpiar tabla Totales después de la inserción/edición de un filtro
        document.getElementById("tblTotales").innerHTML = "";

        $("#myModalMapWarnings2").modal("hide");
    }

    function checkLayerWithoutFilters(capa, considerAlreadyAddedLayerFlag) {
        var table = document.getElementById("tblFiltros");
        var totalRows = table.rows.length;
        var alreadyAddedLayerFlag = false;
        var rowToReplace = -1;

        for(var i = 0; i < totalRows; i++) {
            if(capa == table.rows[i].cells[0].innerHTML) {
                if("(SIN FILTROS)" == table.rows[i].cells[1].innerHTML) {
                    alreadyAddedLayerFlag = true;
                    rowToReplace = i;
                    break;
                }
            }
        }

        if(considerAlreadyAddedLayerFlag) {
            if(alreadyAddedLayerFlag) {
            document.getElementById("btnModalConfirm").setAttribute("onclick", "addFilterConfirm()");
            // 3 líneas necesarias para adaptar el mensaje de alerta + botones Confirmar/Cancelar
            $("#myModalMapWarnings2").modal("show");
            $("#h3ModalMapWarning2").text("La capa " + capa + " (sin filtros) ya existe");
            document.getElementById("messageModalMapWarning2").innerHTML = "Si agrega el filtro de búsqueda específico, la capa sin filtros será reemplazada ya que la consulta desplegará sólo los elementos que cumplen esa condición, en lugar de todos. <span style='color: red;'>¿Desea continuar?</span>";
            }
        }

        return rowToReplace;
    }

    // Habilitar/deshabilitar elementos dependiendo si el usuario está editando un filtro
    function toggleEditMode(disabledValue) {
        // Restaurar botón de Agregar Filtro
        if(disabledValue) { // Original a modo edición
            document.getElementById("btnAddFilter").setAttribute("class", "btn btn-warning btn-block");
            document.getElementById("btnAddFilter").innerHTML = 'EDITAR FILTRO&nbsp; <i class="fa fa-pencil" aria-hidden="true"></i>';
        }
        else { // Modo edición a original
            document.getElementById("btnAddFilter").setAttribute("class", "btn btn-success btn-block");
            document.getElementById("btnAddFilter").innerHTML = 'AGREGAR FILTRO&nbsp; <i class="fa fa-plus" aria-hidden="true"></i>';
        }
        // Cambiar botones de eliminar filas en la tabla de búsqueda, de agregar capa y de limpiar todo
        toggleBtnDeleteRow(disabledValue);
        document.getElementById("btnAddLayer").disabled = disabledValue;
        document.getElementById("btnClearAll").disabled = disabledValue;
        document.getElementById("btnClearBoxes").disabled = disabledValue;
    }

    /* Habilitar/deshabilitar botones para eliminar filas en la tabla de búsqueda
    si el botón Agregar Filtro se encuentra en modo edición */
    function toggleBtnDeleteRow(disabledValue) {
        var deleteButtons = document.getElementsByClassName("btnDeleteRow");
        for(var i = 0; i < deleteButtons.length; i++) {
            deleteButtons[i].disabled = disabledValue;
        }
    }

    /* Adaptar opciones del 2do combobox según la capa seleccionada (1er combobox)
    El array con los campos a filtrar es obtenido de la tabla "ctrl_campos_a_filtrar"
    Update3: Not needed after the AJAX call that fills 2nd combobox (fields) */
    /*function switchSelectCapa() {
        var cbCapas = document.getElementById("cbCapas");
        var capa = cbCapas.options[cbCapas.selectedIndex].value;

        switch(capa) {
            <X?php foreach($cbCampos as $key => $value): ?>
                case "<X?php echo $key; ?>":
                    clear(false);
                    <X?php
                        echo "cbCampos.options[cbCampos.options.length] = new Option('< Seleccionar >', '');";
                        foreach($value as $index => $campo) {
                        echo "cbCampos.options[cbCampos.options.length] = new Option('" . $campo['campo'] . "', '" . $campo['campo'] . "');";
                        }
                    ?>
                    break;
            <X?php endforeach; ?>
            default:
                clear(false);
        } // switch(capa)
    } // function switchSelectCapa()*/

    function switchSelectCapa() {
        var cbCapas = document.getElementById("cbCapas");
        var capa = cbCapas.options[cbCapas.selectedIndex].value;
        var divCampos = document.getElementById("divCampos");
        //clear(false);
        $("body").css('cursor', 'wait');

        $.ajax({
            type: "get",
            url: "index.php/Map_c/getCampos",
            data: { capa:capa },
            dataType: 'json',
            success: function(data) {
                // e.g. 'BANCOS' turns to 'Capa: Bancos' in the dropdown-header
                var header = "Capa: " + capa.charAt(0) + capa.substring(1).toLowerCase();
                var campos = "";
                $(data).each(function(k, v) {
                    campos += '<option value="' + v.campo + '">' + v.campo + '</option>';
                });
                divCampos.innerHTML = null;
                divCampos.innerHTML = '<select class="form-control selectpicker show-tick" id="cbCampos" name="cbCampos" title="Seleccione el campo a filtrar.."><optgroup label="' + header + '">'+ campos + '</select>';
                $('#cbCampos').selectpicker({
				    style: 'btn-info',
				    size: 10
                });
                $('#cbCapas').trigger('mouseleave');
                $('#cbCampos').focus();
                $('#cbCampos').selectpicker('toggle');
                /*$('#cbCampos').parent().addClass('open'); // This way the searchbox isn't focused
                $('#cbCampos').selectpicker('refresh');*/
                $("body").css('cursor', 'auto');
            },
            error: function() {
                console.log("Error! switchSelectCapa() failed. Search columns could not be retrieved");
                $("body").css('cursor', 'auto');
            }
        }); // AJAX
    }

    //  Adaptar opciones del 3er combobox según el campo seleccionado (2do combobox)
    //  Hay 2 opciones para ingresar valores de búsqueda:
    //  1. Mediante INPUTS DE TEXTO: se deben especificar 3 parámetros en el array enviado a "macro_valor_input":
    //  la clase a validar (vLetras, vNumeros, o vAlfanumerico), el placeholder y el maxlength del input; y
    //  2. Mediante SELECTS: esta vista recibe del controlador arrays con los valores disponibles para el campo
    //  en cuestión y los envía a "macro_valor_select". Sólo se debe especificar el nombre del array
    /*function switchSelectCampo() {
        var cbCapas = document.getElementById("cbCapas");
        var capa = cbCapas.options[cbCapas.selectedIndex].value;

        var cbCampos = document.getElementById("cbCampos");
        var campo = cbCampos.options[cbCampos.selectedIndex].value;

        var div = document.getElementById("divFiltros");
        div.setAttribute("style", "margin-bottom: 16px;"); // Mantener botones en misma posición

        switch(capa) {
            case "BANCOS":
                div.innerHTML = null;
                switch(campo) {
                    case "NOMBRE":
                        div.innerHTML =
                            <X?php
                                $data = array(
                                    'rclass' => "vAlfanumerico",
                                    'rplaceholder' => "Ingrese el banco a buscar",
                                    'rmaxlength' => 30
                                );
                                $this->load->view('macro_valor_input', $data);
                            ?>
                        break;
                    case "SERVICIO":
                        div.innerHTML =
                            <X?php
                                $data['dbarray'] = $bancos_servicio;
                                $this->load->view('macro_valor_select', $data);
                            ?>
                        break;
                    default:
                        marginDivFiltros();
                }
                break;
            case "HOTELES":
                div.innerHTML = null;
                switch(campo) {
                    case "NOMBRE":
                        div.innerHTML =
                            <X?php
                                $data = array(
                                    'rclass' => "vAlfanumerico",
                                    'rplaceholder' => "Ingrese el hotel a buscar",
                                    'rmaxlength' => 30
                                );
                                $this->load->view('macro_valor_input', $data);
                            ?>
                        break;
                    default:
                        marginDivFiltros();
                }
                break;
            case "POSTES":
                div.innerHTML = null;
                switch(campo) {
                    case "EMPRESA":
                        div.innerHTML =
                            <X?php
                                $data['dbarray'] = $postes_empresa;
                                $this->load->view('macro_valor_select', $data);
                            ?>
                        break;
                    case "MATERIAL":
                        div.innerHTML =
                            <X?php
                                $data['dbarray'] = $postes_material;
                                $this->load->view('macro_valor_select', $data);
                            ?>
                        break;
                    case "CONDICIÓN FÍSICA":
                        div.innerHTML =
                            <X?php
                                $data['dbarray'] = $postes_cond_fisica;
                                $this->load->view('macro_valor_select', $data);
                            ?>
                        break;
                    default:
                        marginDivFiltros();
                }
                break;
            case "TELÉFONOS PÚBLICOS":
                div.innerHTML = null;
                switch(campo) {
                    case "EMPRESA":
                        div.innerHTML =
                            <X?php
                                $data['dbarray'] = $telefonos_empresa;
                                $this->load->view('macro_valor_select', $data);
                            ?>
                        break;
                    case "TIPO":
                        div.innerHTML =
                            <X?php
                                $data['dbarray'] = $telefonos_tipo;
                                $this->load->view('macro_valor_select', $data);
                            ?>
                        break;
                    case "FUNCIONA":
                        div.innerHTML =
                            <X?php
                                $data['dbarray'] = $telefonos_funciona;
                                $this->load->view('macro_valor_select', $data);
                            ?>
                        break;
                    case "CONDICIÓN FÍSICA":
                        div.innerHTML =
                            <X?php
                                $data['dbarray'] = $telefonos_cond_fisica;
                                $this->load->view('macro_valor_select', $data);
                            ?>
                        break;
                    default:
                        marginDivFiltros();
                }
                break;
            case "LUMINARIAS":
                div.innerHTML = null;
                switch(campo) {
                    case "FUENTE":
                        div.innerHTML =
                            <X?php
                                $data['dbarray'] = $luminarias_fuente;
                                $this->load->view('macro_valor_select', $data);
                            ?>
                        break;
                    case "MATERIAL":
                        div.innerHTML =
                            <X?php
                                $data['dbarray'] = $luminarias_material;
                                $this->load->view('macro_valor_select', $data);
                            ?>
                        break;
                    case "CONDICIÓN FÍSICA":
                        div.innerHTML =
                            <X?php
                                $data['dbarray'] = $luminarias_cond_fisica;
                                $this->load->view('macro_valor_select', $data);
                            ?>
                        break;
                    default:
                        marginDivFiltros();
                }
                break;
            case "MONUMENTOS HISTÓRICOS":
                div.innerHTML = null;
                switch(campo) {
                    case "ÉPOCA":
                        div.innerHTML =
                            <X?php
                                $data['dbarray'] = $monumentos_epoca;
                                $this->load->view('macro_valor_select', $data);
                            ?>
                        break;
                    case "GÉNERO ARQUITECTÓNICO":
                        div.innerHTML =
                            <X?php
                                $data['dbarray'] = $monumentos_genero_arquitectonico;
                                $this->load->view('macro_valor_select', $data);
                            ?>
                        break;
                    default:
                        marginDivFiltros();
                }
                break;
            case "PANTEÓN MUNICIPAL":
                div.innerHTML = null;
                switch(campo) {
                    case "MATERIAL":
                        div.innerHTML =
                            <X?php
                                $data['dbarray'] = $panteon_material;
                                $this->load->view('macro_valor_select', $data);
                            ?>
                        break;
                    case "CONDICIÓN FÍSICA":
                        div.innerHTML =
                            <X?php
                                $data['dbarray'] = $panteon_cond_fisica;
                                $this->load->view('macro_valor_select', $data);
                            ?>
                        break;
                    default:
                        marginDivFiltros();
                }
                break;
        } // switch(capa)

        //
        // Nota: estas validaciones no pueden estar afuera en $document.ready como normalmente debiera ser
        // porque jQuery NO detecta cambios en DOM (inputs incrustados con innerHTML en switchSelectCampo)
        //

        // Hacer todos los textbox mayúsculas cuando pierden el foco
        $("input[type=text]").focusout(function() { this.value = this.value.toUpperCase().trim(); });

        // Validar que sólo se ingresen letras, números o alfanumérico en los textbox dependiendo su clase
        $(".vLetras").keypress(function(evt){
            var charCode = (evt.which) ? evt.which : event.keyCode;
            return validarLetras(charCode);
        });

        $(".vNumeros").keypress(function(evt){
            var charCode = (evt.which) ? evt.which : event.keyCode;
            return validarNumeros(charCode);
        });

        $(".vAlfanumerico").keypress(function(evt){
            var charCode = (evt.which) ? evt.which : event.keyCode;
            return validarAlfanumerico(charCode);
        });

		// Funciones que permiten/bloquean la pulsación de determinada tecla en inputs type="text"
	    function validarLetras(charCode){
			return !(charCode > 31 && (charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122)
				&& charCode != 32 && (charCode <= 192 || charCode >= 255));
        }

	    function validarNumeros(charCode){
			return !(charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 32 && (charCode <= 192 || charCode >= 255));
        }

	    function validarAlfanumerico(charCode){
			return (validarLetras(charCode) || validarNumeros(charCode));
        }
    } // function switchSelectCampo()*/

    function switchSelectCampo() {
        var cbCapas = document.getElementById("cbCapas");
        var capa = cbCapas.options[cbCapas.selectedIndex].value;
        var cbCampos = document.getElementById("cbCampos");
        var campo = cbCampos.options[cbCampos.selectedIndex].value;
        var divValores = document.getElementById("divValores");
        $("body").css('cursor', 'wait');

        if(campo == "NOMBRE") {
            var my_class, my_placeholder, my_maxlength;
            switch(capa) {
                case "BANCOS":
                    my_class = "vAlfanumerico";
                    my_placeholder = "Ingrese el banco a buscar";
                    my_maxlength = 30;
                    break;
                case "HOTELES":
                    my_class = "vAlfanumerico";
                    my_placeholder = "Ingrese el hotel a buscar";
                    my_maxlength = 30;
                    break;
            }
            divValores.innerHTML = null;
            divValores.innerHTML = '<input type="text" class="form-control ' + my_class + '" id="inputValor" name="inputValor" placeholder="' + my_placeholder + '" maxlength="' + my_maxlength + '">';
            $('#cbCampos').trigger('mouseleave');
            $('#inputValor').focus();
            $("body").css('cursor', 'auto');
        } // if (campo == "NOMBRE")
        else {
            $.ajax({
                type: "get",
                url: "index.php/Map_c/getValores",
                data: { capa:capa, campo:campo },
                dataType: 'json',
                success: function(data) {
                    // e.g. 'CONDICIÓN FÍSICA' turns to 'Campo: Condición física' in the dropdown-header
                    var header = "Campo: " + campo.charAt(0) + campo.substring(1).toLowerCase();
                    var valores = "";
                    $(data).each(function(k, v) {
                        valores += '<option value="' + v.valor + '">' + v.valor + '</option>';
                    });
                    divValores.innerHTML = null;
                    divValores.innerHTML = '<select class="form-control selectpicker show-tick" id="cbValores" name="cbValores" title="Seleccione el valor a filtrar.." data-live-search="true" data-live-search-placeholder="Buscar valor.." data-live-search-style="contains"><optgroup label="' + header + '">'+ valores + '</select>';
                    $('#cbValores').selectpicker({
                        style: 'btn-info',
                        size: 10
                    });
                    $('#cbCampos').trigger('mouseleave');
                    $('#cbValores').focus();
                    $('#cbValores').selectpicker('toggle');
                    $("body").css('cursor', 'auto');
                },
                error: function() {
                    console.log("Error! switchSelectCampo() failed. Values could not be retrieved");
                    $("body").css('cursor', 'auto');
                }
            }); // AJAX
        } // else (campo == "NOMBRE")
    }
</script>

<?php $this->load->view('sections/map'); ?>
<?php $this->load->view('sections/map_dec2utm'); ?>
<?php $this->load->view('sections/map_utm2dec'); ?>
<?php $this->load->view('sections/footer'); ?>
<?php $this->load->view('modals/modal_help'); ?>
<?php $this->load->view('modals/modal_warning'); ?>
<?php $this->load->view('modals/modal_warning2'); ?>
<?php $this->load->view('modals/modal_marker'); ?>

</body>
</html>