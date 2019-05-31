<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas canvas-color" id="sidebar">
    <ul class="nav">
      <!--  repite las opcciones de consultas señaladas mas abajo
        <li class="nav-item">
            <center>
            <div class="btn-group">
                <div class="btn-group btn-flex">
                    <button type="button" class="btn btn-danger" id="btnQuery">
                        <i class="fas fa-fw fa-search" aria-hidden="true"></i>&nbsp;&nbsp;CONSULTAR</button>
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"> 
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span></button>
                    <ul class="dropdown-menu" role="menu" id="dropdown-options">
                        --<li class="opt-lbl"><a class="opt-lbl-text" href="#"><i class="fas fa-fw fa-table" aria-hidden="true"></i>&nbsp;&nbsp;VER TABLA DE BÚSQUEDA</a></li>
                        <li class="opt-tooltip"><i class="fas fa-fw fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="Ver las consultas agregadas por capa con y sin filtros (campo/valor)"></i></li> --

                        <li class="opt-lbl"><a class="opt-lbl-text" href="#" data-opt="1"><i class="fas fa-fw fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;AGREGAR CAPA</a></li>
                        <li class="opt-tooltip"><i class="fas fa-fw fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="Agregar la capa sin filtros (campo/valor) a la tabla de búsqueda"></i></li>

                        <li class="opt-lbl"><a class="opt-lbl-text" href="#" data-opt="2"><i class="fas fa-fw fa-map-marker" aria-hidden="true"></i>&nbsp;&nbsp;LIMPIAR MAPA</a></li>
                        <li class="opt-tooltip"><i class="fas fa-fw fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="Limpiar los marcadores y polígonos trazados en el mapa"></i></li>

                        <li class="opt-lbl"><a class="opt-lbl-text" href="#" data-opt="3"><i class="fas fa-fw fa-undo" aria-hidden="true"></i>&nbsp;&nbsp;LIMPIAR TODO</a></li>
                        <li class="opt-tooltip"><i class="fas fa-fw fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="Limpiar el mapa y la tabla de búsqueda"></i></li>

                        <li class="opt-lbl"><a class="opt-lbl-text" href="#" data-opt="4"><i class="fas fa-fw fa-question-circle" aria-hidden="true"></i>&nbsp;&nbsp;AYUDA</a></li>
                        <li class="opt-tooltip"><i class="fas fa-fw fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="Abrir modal de ayuda y preguntas frecuentes"></i></li>
                    </ul>
                </div>
            </div>
            </center>
        </li>
        -->
        <li class="nav-item nav-category">
            <span class="nav-link">CAPA</span>
        </li>
        <li class="nav-item">
            <center><select class="selectpicker show-tick" id="cbCapas" name="cbCapas" title="Capa en donde buscar.." data-live-search="true" data-live-search-placeholder="Buscar capa.." data-live-search-style="contains" data-style="btn-info" >
                <?php foreach($cbCapas as $key => $value): ?>
                    <optgroup label="<?php echo $key; ?>">
                        <?php foreach($value as $index => $capa): ?>
                            <option data-tokens="<?php echo $key; ?>" value="<?php echo $capa['capa']; ?>"><?php echo $capa['capa']; ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select></center>
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">CAMPO</span>
        </li>
        <li class="nav-item">
            <center id="divCampos">
                <label class="nav-link">
                    <span class="menu-title">Seleccione una capa..</span>
                    <i class="fas fa-fw fa-layer-group" data-toggle="tooltip" data-placement="top" title="Para agregar un filtro, primero seleccione una capa"></i>
                </label>
            </center>
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">VALOR</span>
        </li>
        <li class="nav-item">
            <center id="divValores">
                <label class="nav-link">
                    <span class="menu-title">Seleccione un campo..</span>
                    <i class="fas fa-fw fa-filter" data-toggle="tooltip" data-placement="top" title="Para agregar un filtro, primero seleccione un campo"></i>
                </label>
            </center>
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">ÁREA DE INFLUENCIA</span>
        </li>
        <li class="nav-item">
            <center>
                <select class="selectpicker show-tick" id="cbShapes" name="cbShapes" data-style="btn-info">
                    <option value="Circle">CIRCULO</option>
                    <option value="Square">CUADRADO</option>
                    <option value="None">NINGUNA</option>
                    <option value="Polygon">POLÍGONO</option>
                    <option value="Box">RECTÁNGULO</option>
                </select>
            </center>
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">UNIR CONDICIONES MEDIANTE</span>
        </li>
        <li class="nav-item" id="div-rbtns">
            <div class="col-sm-6" id="div-rbtns-or">
                <div class="form-radio">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="booleanOps" id="rbtnOR" value="OR" checked> O (OR)
                    </label>
                </div>
            </div>
            <div class="col-sm-6" id="div-rbtns-and">
                <div class="form-radio">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="booleanOps" id="rbtnAND" value="AND"> Y (AND)
                    </label>
                </div>
            </div>
        </li> <!-- div-rbtns -->
        <li class="nav-item nav-category">
            <span class="nav-link">ACCIONES</span>
        </li>
        <li class="nav-item">
            <label class="nav-link label-options" data-opt="0">
                <span class="menu-title">Consultar</span>
                <i class="fas fa-fw fa-search" data-toggle="tooltip" data-placement="top" title="Consultar en el mapa"></i>
            </label>
        </li>
        <!-- <li class="nav-item">
            <label class="nav-link">
                <span class="menu-title">Ver tabla de búsqueda</span>
                <i class="fas fa-fw fa-table" data-toggle="tooltip" data-placement="top" title="Ver las consultas agregadas por capa con y sin filtros (campo/valor)"></i>
            </label>
        </li> -->
        <li class="nav-item">
            <label class="nav-link label-options" data-opt="1">
                <span class="menu-title">Agregar capa</span>
                <i class="fas fa-fw fa-plus" data-toggle="tooltip" data-placement="top" title="Agregar la capa sin filtros (campo/valor) a la tabla de búsqueda"></i>
            </label>
        </li>
        <li class="nav-item">
            <label class="nav-link label-options" data-opt="2">
                <span class="menu-title">Limpiar mapa</span>
                <i class="fas fa-fw fa-map-marker " data-toggle="tooltip" data-placement="top" title="Limpiar los marcadores y polígonos trazados en el mapa"></i>
            </label>
        </li>
        <li class="nav-item">
            <label class="nav-link label-options" data-opt="3">
                <span class="menu-title">Limpiar todo</span>
                <i class="fas fa-fw fa-undo " data-toggle="tooltip" data-placement="top" title="Limpiar el mapa y la tabla de búsqueda"></i>
            </label>
        </li>
        <li class="nav-item">
            <label class="nav-link label-options" data-opt="4">
                <span class="menu-title">Ayuda</span>
                <i class="fas fa-fw fa-question-circle" data-toggle="tooltip" data-placement="top" title="Abrir modal de ayuda y preguntas frecuentes"></i>
            </label>
        </li>
    </ul>
</nav>