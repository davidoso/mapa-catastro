  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<base href="<?php echo base_url();?>">
  <title>Stellar Admin</title>

	<link rel="icon" type="image/png" href="images/favicons/map.ico"/>
  <link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-select.min.css">

  <!-- endinject -->
  <link href="fonts/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- plugins:js -->
    <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-select.min.js"></script>

  <script src="js/off-canvas.js"></script>
  <script src="js/misc.js"></script>

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
			$('[data-toggle="tooltip"]').tooltip();
    </script>

    <style>
      .bootstrap-select {
        width: 90% !important;
      }

      .filter-option-inner-inner, .bs-caret {
        font-size: 1em !important;
        }

        .dropdown-menu {
            font-size: 1em !important;
        }
        .dropdown-menu.show {
    min-width: 250px !important;
}

        	.btn-flex {
  display: flex;
  align-items: stretch;
  align-content: stretch;
}

  .btn-flex .btn:first-child {
    flex-grow: 1;
    text-align: left;
  }

  #dropdown-options {
    transform: translate3d(0px, 34px, 0px) !important;
    min-width: 0px !important;
  }

  #dropdown-options li {
	display:inline-block;
	vertical-align: top;
	
  float: Left;
	}
      </style>