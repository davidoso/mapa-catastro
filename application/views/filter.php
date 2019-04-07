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
          <div class="row">
            <div class="col-lg-12 grid-margin">
              <div class="card overflow-hidden dashboard-curved-chart">
                <div class="card-body mx-3">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="myDataTable" width="100%" cellspacing="0">
                      <thead class="tbl-blue-th">
                        <tr>
                          <th width="26%">CAPA</th>
                          <th width="23%">CAMPO</th>
                          <th width="23%">VALOR</th>
                          <th width="10%">TOTAL</th>
                          <th width="6%"><i style="color: #28a745;" class="fas fa-fw fa-plus" title="Mostrar los resultados"></i></th>
                          <th width="6%"><i style="color:#ffc107;" class="fas fa-fw fa-pencil-alt" title="Editar consulta"></i></th>
                          <th width="6%"><i style="color:#dc3545;" class="fas fa-fw fa-trash" title="Eliminar consulta"></i></th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div> <!-- table-responsive ends -->

                  <div id="map" class="map"></div>
                  <div id="mouse-position" class="text-purple-darken" align="right"></div>
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
</body>
</html>