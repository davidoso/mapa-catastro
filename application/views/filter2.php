<!DOCTYPE html>
<html lang="en">
<head>
  <?php $this->load->view('sections/head2'); ?>
</head>
<body>
  <div class="container-scroller">
	<?php $this->load->view('sections/navbar'); ?>


    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <div class="row row-offcanvas row-offcanvas-right">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
          <li class="nav-item">
              <center>

          <div class="btn-group">
          <div class="btn-group btn-flex">
                            <button type="button" class="btn btn-danger">
                    <i class="fa fa-search fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;CONSULTAR</button>
                            <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu" id="dropdown-options">
                                <li style="width: 90%;"><a href="#"><i class="fa fa-plus fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;AGREGAR CAPA</a></li>
                                <li style="font-family: helvetica; width: 10%;"><i id='iHelp' class='fa fa-info-circle' data-toggle='tooltip' data-placement='right' title='Agregar capa sin filtros a la tabla de búsqueda'></i></li>
                                <li style="width: 90%;"><a href="#"><i class="fa fa-map-marker fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;LIMPIAR MAPA</a></li>
                                <li style="font-family: helvetica; width: 10%;"><i id='iHelp' class='fa fa-info-circle' data-toggle='tooltip' data-placement='right' title='Limpiar los marcadores y polígonos trazados en el mapa'></i></li>
                                <li style="width: 90%;"><a href="#"><i class="fa fa-undo fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;LIMPIAR TODO</a></li>
                                <li style="font-family: helvetica; width: 10%"><i id='iHelp' class='fa fa-info-circle' data-toggle='tooltip' data-placement='right' title='Limpiar mapa y tabla de búsqueda'></i></li>
                            </ul>
                        </div>
            </div>
            </center>
            </li>



            <li class="nav-item nav-category">
              <span class="nav-link">CAPA</span>
            </li>
            <li class="nav-item">
            <center>
            <select class="selectpicker show-tick" id="cbCapas" name="cbCapas" title="Capa en donde buscar.." data-live-search="true" data-live-search-placeholder="Buscar capa.." data-live-search-style="contains">
                            <?php foreach($cbCapas as $key => $value): ?>
                                <optgroup label="<?php echo $key; ?>">
                                    <?php foreach($value as $index => $capa): ?>
                                        <option data-tokens="<?php echo $key; ?>" value="<?php echo $capa['capa']; ?>"><?php echo $capa['capa']; ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                        
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
              <span class="nav-link">UNIR CONDICIONES MEDIANTE:</span>
            </li>
            <li class="nav-item">
              <label class="nav-link">
                <span class="menu-title">Rbtns..</span>
                <i class="fas fa-fw fa-filter"></i>
              </label>
            </li>
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
            <!-- poner las mismas opciones que el dropdown -->

            <!-- <li class="nav-item nav-category">
              <span class="nav-link">UI FEATURES</span>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Basic UI Elements</span>
                <i class="icon-layers menu-icon"></i>
              </a>
              <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="pages/ui-features/buttons.html">Buttons</a></li>
                  <li class="nav-item"> <a class="nav-link" href="pages/ui-features/typography.html">Typography</a></li>
                </ul>
              </div>
            </li> -->

          </ul>
        </nav>
        <!-- partial -->
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin">
              <div class="card overflow-hidden dashboard-curved-chart">
                <div class="card-body mx-3">
                  <h2 class="card-title border-bottom-none">Mapa cartográfico de Colima, Col.</h2>
                </div>
              </div>
            </div>
          </div>
          <!-- row ends -->
        </div>
        <!-- content-wrapper ends -->
    <?php $this->load->view('sections/footer'); ?>

        </footer>
        <!-- partial -->
      </div>
      <!-- row-offcanvas ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->



<script>

          // Fill 2nd combobox (search columns) depending on the layer selected on the 1st combobox
          $("#cbCapas").on("change", function () {
            document.getElementById("divValores").innerHTML = '<label class="nav-link"><span class="menu-title">Seleccione un campo..</span><i class="fas fa-fw fa-filter"></i></label>';
            switchSelectCapa();
        });

                // Fill 3rd combobox (values) depending on the column-to-search selected on the 2nd combobox
                $(document).on("change", "#cbCampos", function() {
            switchSelectCampo();
        });
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
                divCampos.innerHTML = '<select class="selectpicker show-tick" id="cbCampos" name="cbCampos" title="Campo a filtrar.."><optgroup label="' + header + '">'+ campos + '</select>';
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
                    divValores.innerHTML = '<select class="selectpicker show-tick" id="cbValores" name="cbValores" title="Valor a filtrar.." data-dropup-auto="false" data-live-search="true" data-live-search-placeholder="Buscar valor.." data-live-search-style="contains"><optgroup label="' + header + '">'+ valores + '</select>';
                    $('#cbValores').selectpicker({
                        style: 'btn-info',
                        size: 6
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
</body>

</html>
