<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <center>
            <div class="btn-group">
                <div class="btn-group btn-flex">
                    <button type="button" class="btn btn-danger">
                        <i class="fas fa-fw fa-search" aria-hidden="true"></i>&nbsp;&nbsp;CONSULTAR</button>
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span></button>
                    <ul class="dropdown-menu" role="menu" id="dropdown-options">
                        <li style="width: 90%;"><a href="#"><i class="fas fa-fw fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;AGREGAR CAPA</a></li>
                        <li style="font-family: helvetica; width: 10%;"><i id='iHelp' class='fa fa-fw fa-info-circle' data-toggle='tooltip' data-placement='right' title='Agregar capa sin filtros a la tabla de búsqueda'></i></li>
                        <li style="width: 90%;"><a href="#"><i class="fas fa-fw fa-map-marker" aria-hidden="true"></i>&nbsp;&nbsp;LIMPIAR MAPA</a></li>
                        <li style="font-family: helvetica; width: 10%;"><i id='iHelp' class='fa fa-info-circle' data-toggle='tooltip' data-placement='right' title='Limpiar los marcadores y polígonos trazados en el mapa'></i></li>
                        <li style="width: 90%;"><a href="#"><i class="fas fa-fw fa-undo" aria-hidden="true"></i>&nbsp;&nbsp;LIMPIAR TODO</a></li>
                        <li style="font-family: helvetica; width: 10%"><i id='iHelp' class='fa fa-fw fa-info-circle' data-toggle='tooltip' data-placement='right' title='Limpiar mapa y tabla de búsqueda'></i></li>
                    </ul>
                </div>
            </div>
            </center>
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">CAPA</span>
        </li>
        <li class="nav-item">
            <center><select class="selectpicker show-tick" id="cbCapas" name="cbCapas" title="Capa en donde buscar.." data-live-search="true" data-live-search-placeholder="Buscar capa.." data-live-search-style="contains">
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
                    <i class="fas fa-fw fa-layer-group"></i>
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
                    <i class="fas fa-fw fa-filter"></i>
                </label>
            </center>
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">ÁREA DE INFLUENCIA</span>
        </li>
        <li class="nav-item">
            <center>
                <select class="selectpicker show-tick" id="cbShapes" name="cbShapes">
                    <option value="Box">RECTÁNGULO</option>
                    <option value="Square">CUADRADO</option>
                    <option value="Polygon">POLÍGONO</option>
                    <option value="None">NINGUNA</option>
                </select>
            </center>
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">UNIR CONDICIONES MEDIANTE</span>
        </li>
        <li class="nav-item">
            <label class="nav-link">
                <span class="menu-title">Rbtns..</span>
                <i class="fas fa-fw fa-filter"></i>
            </label>
        </li>
        <!-- PONER LAS MISMAS OPCIONES QUE EL DROPDOWN -->
        <li class="nav-item nav-category">
            <span class="nav-link">MÁS OPCIONES</span>
        </li>
        <li class="nav-item">
            <label class="nav-link">
                <span class="menu-title">Ver tabla de búsqueda</span>
                <i class="fas fa-fw fa-table"></i>
            </label>
        </li>
        <li class="nav-item">
            <label class="nav-link">
                <span class="menu-title">Ayuda</span>
                <i class="fas fa-fw fa-question-circle"></i>
            </label>
        </li>
    </ul>
</nav>