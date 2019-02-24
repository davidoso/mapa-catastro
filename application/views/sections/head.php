<title>CATASTRO | MAPA</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<base href="<?php echo base_url();?>">
<!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/favicons/map.ico">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/my-styles.css">
<!--===============================================================================================-->
    <!-- Bootstrap select stylesheet downloaded from: -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css"> -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap-select.min.css">
<!--===============================================================================================-->
    <!-- OpenLayers stylesheet (features zoom levels and default controls) downloaded from: -->
	<!-- <link rel="stylesheet" type="text/css" href="https://openlayers.org/en/v4.6.4/css/ol.css" -->
	<link rel="stylesheet" type="text/css" href="css/ol.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap4.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/jquery.toast.min.css">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/fontawesome-free/css/all.min.css">
<!--===============================================================================================-->
    <!-- jQuery version downloaded from: -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/popper.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
<!--===============================================================================================-->
    <!-- Bootstrap select JavaScript library downloaded from: -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script> -->
    <script type="text/javascript" src="js/bootstrap-select.min.js"></script>
<!--===============================================================================================-->
    <script type="text/javascript" src="js/off-canvas.js"></script>
    <script type="text/javascript" src="js/misc.js"></script>
<!--===============================================================================================-->
	<!-- JavaScript library to convert from decimal coordinates to utm and viceversa -->
	<!-- NOTE: proj4.js did not work so map_dec2utm.js y map_utm2dc.js scripts were used instead -->
	<!-- <script type="text/javascript" src="js/map/proj4.js"></script> -->
<!--===============================================================================================-->
    <!-- OpenLayers JavaScript library v4.6.4 downloaded from: -->
	<!-- <script type="text/javascript" src="https://openlayers.org/en/v4.6.4/build/ol.js"></script> -->
	<script type="text/javascript" src="js/map/ol.js"></script>
<!--===============================================================================================-->
	<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="js/dataTables.bootstrap4.min.js"></script>
<!--===============================================================================================-->
    <script type="text/javascript" src="js/jquery.toast.min.js"></script>
    <script type="text/javascript" src="js/my-toastnotification.js"></script>
<!--===============================================================================================-->
    <script type="text/javascript" src="js/sweetalert.min.js"></script>
    <script type="text/javascript" src="js/my-sweetalert.js"></script>
<!--===============================================================================================-->
    <script>
        $.fn.selectpicker.Constructor.BootstrapVersion = '4';
        $.fn.selectpicker.Constructor.DEFAULTS.dropupAuto = false;
        $(document).ready(function() {
            $('.selectpicker').selectpicker({
            style: 'btn-info',
            size: 10
            /*iconBase: 'fa'
            tickIcon: 'fa-check'*/
            });
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>