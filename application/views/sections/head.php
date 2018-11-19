<title>CATASTRO | MAPA</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<base href="<?php echo base_url();?>">
<!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/favicons/map.ico"/>
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<!--===============================================================================================-->
	<!-- Bootstrap select stylesheet (contains zoom levels and default controls) downloaded from: -->
	<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css"> -->
	<link rel="stylesheet" type="text/css" href="css/bootstrap-select.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/icomoon.css">
	<link rel="stylesheet" type="text/css" href="css/animate-custom.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/my-styles.css">
<!--===============================================================================================-->
	<!-- JavaScript library to convert from decimal coordinates to utm and viceversa -->
	<!-- NOTE: proj4.js did not work so map_dec2utm y map_utm2dc scripts were used instead -->
	<!-- <script type="text/javascript" src="js/map/proj4.js"></script> -->

	<!-- OpenLayers stylesheet (contains zoom levels and default controls) downloaded from: -->
	<!-- <link rel="stylesheet" type="text/css" href="https://openlayers.org/en/v4.6.4/css/ol.css" -->
	<link rel="stylesheet" type="text/css" href="css/ol.css">

	<!-- jQuery version downloaded from: -->
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
	<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
	<!-- Bootstrap JavaScript library downloaded from: -->
	<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<!-- Bootstrap select JavaScript library downloaded from: -->
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script> -->
	<script type="text/javascript" src="js/bootstrap-select.min.js"></script>

	<!-- OpenLayers JavaScript library v4.6.5 downloaded from: -->
	<!-- <script type="text/javascript" src="https://openlayers.org/en/v4.6.4/build/ol.js"></script> -->
	<script type="text/javascript" src="js/map/ol.js"></script>

	<script>
		$(document).ready(function() {
			$('.selectpicker').selectpicker({
				style: 'btn-info',
				size: 10
			});
		});
	</script>