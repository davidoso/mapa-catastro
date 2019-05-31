<!DOCTYPE html>
<html lang="es">
<head>
  <?php $this->load->view('sections/head'); ?>
</head>
<body>
  <div class="container-scroller">
    <?php $this->load->view('sections/navbar'); ?>

    <div class="container-fluid page-body-wrapper">
      <div class="row row-offcanvas row-offcanvas-right">
        <?php $this->load->view('sections/sidebar'); ?>

        <div class="content-wrapper" style="padding-botton: 0%;">
          <div class="row ">
            <div class="col-lg-12 grid-margin">
              <div class="card overflow-hidden dashboard-curved-chart new-view">
                <div class="card-body mx-3">
                  <div class="table-responsive"> 
                    <table class="table table-striped table-bordered" id="myDataTable" width="100%" cellspacing="0">
                      <thead class="tbl-blue-th">
                        <tr>
                          <th id="icono" width="1%">ICONO</th>
                          <th>CAPA</th>
                          <th>CAMPO</th>
                          <th>VALOR</th>
                          <th>TOTAL</th>
                          <!--<th width="6%"><i style="color:#28a745;" class="fas fa-fw fa-plus" title="Mostrar los resultados"></i></th>-->
                          <th width="6%"><i style="color:#ffc107;" class="fas fa-fw fa-pencil-alt" title="Editar consulta"></i></th>
                          <th width="6%"><i style="color:#dc3545;" class="fas fa-fw fa-trash" title="Eliminar consulta"></i></th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table> 
                  </div> <!-- table-responsive ends -->
                  
                    <button id="boton-mapa" class="btn btn-dark btn-sm orange" onclick="myFunctionMap()">Mapa</button>
                    <button id="boton-tabla" class="btn btn-dark btn-sm" onclick="myFunctionTable()">Datos</button>
                    <button  style="visibility: hidden; " class="btn btn-dark btn-sm color-exportar" id="exportar-boton"><a id="exportar" class="exportar">Exportar datos a CSV</a></button>
                    
                  <div id="map" class="map"></div>
                  <div id="mouse-position" class="text-purple-darken" align="right"></div>
                  
                  <div id="map-table" class="table-responsive" style="display:none;" class="map-table">
                  <div align="center"><h1>No hay datos que mostrar<h1></div>
                  </div>    
                </div> <!-- card-body mx-3 ends -->
              </div>
            </div>
          </div> <!-- row ends -->
        </div> <!-- content-wrapper ends -->

        <?php $this->load->view('modals/map_marker'); ?>
        <?php $this->load->view('modals/help'); ?>
        <?php $this->load->view('sections/footer'); ?>

      </div> <!-- row-offcanvas ends -->
    </div> <!-- page-body-wrapper ends -->
  </div> <!-- container-scroller -->
 
<!-- Custom JavaScript for this project -->
<script type="text/javascript" src="js/map/map.js"></script>
<script type="text/javascript" src="js/filter/filter.js"></script>
<script type="text/javascript" src="js/filter/map_dec2utm.js"></script>
<script type="text/javascript" src="js/filter/map_utm2dec.js"></script>
<script type="text/javascript" src="js/filter/search_datatable.js"></script>
<script>

$(document).ready(

  function(){
     $('#myDataSetTable').DataTable(); 
    }

  );
	 


function myFunctionMap() {
  var x = document.getElementById("map");
  var y = document.getElementById("map-table");
  var z = document.getElementById("mouse-position");

  var m = document.getElementById("boton-mapa");
  var t = document.getElementById("boton-tabla");
    m.style.color ="orange";
    t.style.color ="white";

    x.style.display = "block";
    z.style.display = "block";
    y.style.display = "none";
}

function myFunctionTable() {
  var x = document.getElementById("map");
  var y = document.getElementById("map-table");
  var z = document.getElementById("mouse-position");

  var m = document.getElementById("boton-mapa");
  var t = document.getElementById("boton-tabla");
    t.style.color ="orange";
    m.style.color ="white";

    y.style.display = "block";
    z.style.display = "none";
    x.style.display = "none";
}



</script>
</body>
</html>
