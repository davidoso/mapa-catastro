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

        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin">
              <div class="card overflow-hidden dashboard-curved-chart">
                <div class="card-body mx-3">
                  <h2 class="card-title border-bottom-none">Mapa cartográfico de Colima, Col.</h2>
                  <div id="map" class="map"></div>
                  <div id="mouse-position" class="text-success" align="right"></div>
                </div>
              </div>
            </div>
          </div> <!-- row ends -->
        </div> <!-- content-wrapper ends -->

        <?php $this->load->view('sections/footer'); ?>

      </div> <!-- row-offcanvas ends -->
    </div> <!-- page-body-wrapper ends -->
  </div> <!-- container-scroller -->


<!-- <script type="text/javascript" src="js/map/map.js"></script> -->
<script type="text/javascript" src="js/filter/filter.js"></script>
<script type="text/javascript" src="js/filter/map_dec2utm.js"></script>
<script type="text/javascript" src="js/filter/map_utm2dec.js"></script>

<!-- <X?php $this->load->view('modals/modal_help'); ?>
<X?php $this->load->view('modals/modal_warning'); ?>
<X?php $this->load->view('modals/modal_warning2'); ?>
<X?php $this->load->view('modals/modal_marker'); ?> -->

</body>
</html>