----------------------------
CONFIG. DE XAMPP REQUERIDA
----------------------------
php.ini en carpeta xampp/php:
max_execution_time=90


--------------------------------
¿CÓMO CAMBIAR/AGREGAR FILTROS?
--------------------------------
PASO 1: Modificar 3 tablas en MySQL para agregar los filtros en la vista filter.php
	1. ctrl_select_capas: para llenar optgroup (carpeta) y options (capa) del select cbCapas
	2. ctrl_campos_a_filtrar: para llenar options (campo) del select cbCampos con los campos a filtrar por capa
	3. ctrl_nombre_columnas: para obtener el nombre de las columnas en la bd (cuando se pulsa el botón CONSULTAR y se llama por ajax el archivo mapFilterSwitchColumn.php) dependiendo los campos a filtrar especificados en ctrl_campos_a_filtrar
	* NOTA: comprobar el nombre con el que aparecerán los campos por capa en cbCampos ejecutando la vista en la bd v_campos_a_filtrar_por_capa. El nombre exacto debe escribirse en los case de la función switchSelect2()

PASO 2: Modificar switch en las funciones de los siguientes 3 códigos php:
	1. Función switchSelect2() para agregar campos/valores en el frontend en filter.php
	2. Función switchColumn() mapFilterSwitchColumn.php
	3. Funciones switchTableMarker() y switchColumnMarker() en mapSelectedMarkerSwitchs.php
	* NOTA: los case en mapSelectedMarkerSwitchs.php son IGUALES que los nombres del marcador de la capa localizados en images/mapMarkers

PASO 3: Modificar controlador map_c.php (ver ejemplos comentados con "Llenar selects de la capa <nombre_de_la_capa>") y modelo map_m.php para llenar el select cbFiltros (se encuentra en macro_valor_select.php) de valores según el campo seleccionado en cbCampos
	* NOTA: este paso sólo es necesario si el campo tiene una serie de valores predefinidos, e.g. material, cond_fisica o empresa