<?php
class Map_m extends CI_Model {

	private function switchTable($whereColumn, $whereValue)
	{
		$this->db->select('nombre_tabla');
		$this->db->from('ctrl_select_capas');
		$this->db->where($whereColumn, $whereValue);
		$query = $this->db->get()->row_array();

		return $query['nombre_tabla'];
	}

	private function switchColumn($column, $value)
	{
        switch($column) {
			/* CONDICIÓN QUE SIEMPRE ES VERDADERA PARA TRAER TODOS LOS ELEMENTOS DE UNA CAPA */
			case "(SIN FILTROS)":
                return "1 = 1";
				break;

			/* COLUMNAS CON VALORES NO PREDEFINIDOS QUE SE INGRESAN MEDIANTE UN INPUT DE TEXTO.
			NOTA: ESTAS COLUMNAS NO ESTÁN EN ctrl_nombre_columnas */
            case "NOMBRE":
                return "nombre like '%" . $value . "%'";
                break;
            case "NOMBRE COMERCIAL":
                return "nombre_comercial like '%" . $value . "%'";
                break;

			/* VALORES DE CAMPOS DE CATÁLOGOS. Es necesario que la tabla, id y descripción sigan la nomenclatura
			base, e.g. cond_fisica: ct_cond_fisica (tabla), id_cond_fisica (id) y cond_fisica (descripción) */
            case "MATERIAL":
                return "id_material = " . $this->getCatalogId("material", $value);
                break;
            case "CONDICIÓN FÍSICA":
                return "id_cond_fisica = " . $this->getCatalogId("cond_fisica", $value);
                break;

             /* VALORES DE CAMPOS CON FORMATOS ESPECÍFICOS O NULOS PRESENTADOS CON UN ALIAS  */
            case "FUENTE": // Fuente secundaria de una luminaria
                if($value === "SIN FUENTE") {
                    return "f_secundaria IS NULL";
                }
                else {
                    return "f_secundaria = '" . $value . "'";
                }
                break;

            /* CUALQUIER OTRA COLUMNA EN ctrl_nombre_columnas */
            default:
            	return $this->getNormalColumn($column) . " = '" . $value . "'";
        }
    }

	private function getNormalColumn($column)
	{
		$this->db->select('columna_bd');
		$this->db->from('ctrl_nombre_columnas');
		$this->db->where('columna_frontend', $column);
		$query = $this->db->get()->row_array();

		return $query['columna_bd'];
	}

	private function getCatalogId($columnBaseName, $value)
	{
		$id = "id_" . $columnBaseName . " AS 'id'";
		$table = "ct_" . $columnBaseName;

		$this->db->select($id);
		$this->db->from($table);
		$this->db->where($columnBaseName, $value);
		$query = $this->db->get()->row_array();

		return $query['id'];
	}

	private function pointsArrayToString($pointsArray)
	{
        $polygonCoordinates = "";
        for($i = 0; $i < count($pointsArray); $i++) {
            if(($i + 1) % 2 == 0) {
                $polygonCoordinates = $polygonCoordinates . $pointsArray[$i] . ', ';
            }
            else {
                $polygonCoordinates = $polygonCoordinates . $pointsArray[$i] . ' ';
            }
		}

    	return substr($polygonCoordinates, 0, -2); // Drop last comma and whitespace
    }

	// This function must not be edited. All the map queries must be added/changed in switchColumn()
	public function getMapTotals()
	{
		$tableData = json_decode($_POST['tableData']);		// 3-column table queries (layer, column and value)
		$pointsArray = json_decode($_POST['pointsArray']);	// Polygon coordinates in the map
		$booleanOp = $_POST['booleanOp'];					// OR or AND operator to join WHERE clauses
		$returnData = array();
		//$arrQueryBuilder = array(); // Outputs n sql queries after a succesful AJAX call (testing purposes)

		if($booleanOp === "OR") {
			for($i = 0; $i < count($tableData); $i++) {
				// DB table name
				$from = $this->switchTable('capa', $tableData[$i]->capa);
				// One user-added condition for each row in the datatable, despite a layer having +1 queries
				$cond = $this->switchColumn($tableData[$i]->campo, $tableData[$i]->valor);
				// All elements must be inside the drawn polygon area in the map
				$where = "ST_INTERSECTS(ST_GeomFromText('Polygon((" . $this->pointsArrayToString($pointsArray) . "))'), ST_GeomFromText( CONCAT('POINT(', CONVERT(coord_x, CHAR(20)), ' ', CONVERT(coord_y, CHAR(20)), ')') )) AND (" . $cond . ")";

				$this->db->select('count(id) AS totalByRow');
				$this->db->from($from);
				// Layers that have columns from +1 table that are not catalogs and thus require an inner join
				if($tableData[$i]->capa == "GIROS COMERCIALES") {
					$this->db->join('comercio_tbl_giros_comerciales_licencias AS L', 'comercio_tbl_giros_comerciales.id = L.id_giro_comercial');
				}
				$this->db->where($where);

				//$stmt = $this->db->get_compiled_select(); // Save generated sql query (testing purposes)
				//array_push($arrQueryBuilder, $stmt);
				$queryResult = $this->db->get()->row_array()['totalByRow'];
                array_push($returnData, $queryResult);
			}
		} // if($booleanOp === "OR")
		else {
			// Same required array variables as getMapMarkers()
			$arrLayers = array();
			$jaggedArrayByLayer = array();
			/* Saves the total amount for each layer and at the end loops over the tableData to output the
			total for each row. With AND operator all the conditions have the same total */
			$arrTotalByLayer = array();

			for($dtRow = 0; $dtRow < count($tableData); $dtRow++) {
				if(!in_array($tableData[$dtRow]->capa, $arrLayers))
					array_push($arrLayers, $tableData[$dtRow]->capa);
			}

			for($i = 0; $i < count($arrLayers); $i++) {
				$jaggedArrayByLayer[$i] = array();
			}

			for($dtRow = 0; $dtRow < count($tableData); $dtRow++) {
				$i = array_search($tableData[$dtRow]->capa, $arrLayers);
				array_push($jaggedArrayByLayer[$i], $dtRow);
			}

			for($i = 0; $i < count($arrLayers); $i++) {
				$allLayerCond = "?"; // Concatenates one or more single conditions (datatable rows) by layer

				for($j = 0; $j < count($jaggedArrayByLayer[$i]); $j++) {
					$dtRow = $jaggedArrayByLayer[$i][$j];
					$singleCond = $this->switchColumn($tableData[$dtRow]->campo, $tableData[$dtRow]->valor);
					if($allLayerCond == "?") // ? symbol is a flag that tells whether a condition has been added
						$allLayerCond = $singleCond;
					else
						$allLayerCond = $allLayerCond . ' AND ' . $singleCond; // AND is $booleanOp
				}

				// DB table name
				$from = $this->switchTable('capa', $arrLayers[$i]);
				// All elements must be inside the drawn polygon area in the map
				$where = "ST_INTERSECTS(ST_GeomFromText('Polygon((" . $this->pointsArrayToString($pointsArray) . "))'), ST_GeomFromText( CONCAT('POINT(', CONVERT(coord_x, CHAR(20)), ' ', CONVERT(coord_y, CHAR(20)), ')') )) AND (" . $allLayerCond . ")";

				$this->db->select('count(id) AS totalByLayer');
				$this->db->from($from);
				// Layers that have columns from +1 table that are not catalogs and thus require an inner join
				if($arrLayers[$i] == "GIROS COMERCIALES") {
					$this->db->join('comercio_tbl_giros_comerciales_licencias AS L', 'comercio_tbl_giros_comerciales.id = L.id_giro_comercial');
				}
				$this->db->where($where);

				//$stmt = $this->db->get_compiled_select(); // Save generated sql query (testing purposes)
				//array_push($arrQueryBuilder, $stmt);
				$queryResult = $this->db->get()->row_array()['totalByLayer'];
                array_push($arrTotalByLayer, $queryResult);
			}

			for($dtRow = 0; $dtRow < count($tableData); $dtRow++) { // Output the total for each row
				$i = array_search($tableData[$dtRow]->capa, $arrLayers);
				array_push($returnData, $arrTotalByLayer[$i]);
			}
		} // else($booleanOp === "OR"), i.e. AND

		return $returnData;
	}

    // This function must not be edited. All the map queries must be added/changed in switchColumn()
	public function getMapMarkers()
	{
		$tableData = json_decode($_POST['tableData']);		// 3-column table queries (layer, column and value)
		$pointsArray = json_decode($_POST['pointsArray']);	// Polygon coordinates in the map
		$booleanOp = $_POST['booleanOp'];					// OR or AND operator to join WHERE clauses

		/* Since queries are not sorted by layer in the datatable (tabla.data() saves the rows the way they were
		added, despite colum sorting), $arrLayers saves the layer frontend-names. These will be converted to
		database table-names later */
		$arrLayers = array();
		$jaggedArrayByLayer = array();
		$returnData = array();
		//$arrQueryBuilder = array(); // Outputs n sql queries after a succesful AJAX call (testing purposes)

		for($dtRow = 0; $dtRow < count($tableData); $dtRow++) {
			if(!in_array($tableData[$dtRow]->capa, $arrLayers))
				array_push($arrLayers, $tableData[$dtRow]->capa);
		}

		for($i = 0; $i < count($arrLayers); $i++) {
			$jaggedArrayByLayer[$i] = array();
		}

		for($dtRow = 0; $dtRow < count($tableData); $dtRow++) {
			$i = array_search($tableData[$dtRow]->capa, $arrLayers);
			array_push($jaggedArrayByLayer[$i], $dtRow);
		}

		for($i = 0; $i < count($arrLayers); $i++) {
			// Map marker id
			$select = "coord_x AS longitude, coord_y AS latitude, lower(tr('" . $arrLayers[$i] . "', 'ÁÉÍÓÚÑ ', 'AEIOUN_')) AS layer";
			// DB table name
			$from = $this->switchTable('capa', $arrLayers[$i]);
			// All elements must be inside the drawn polygon area in the map
			$where = "ST_INTERSECTS(ST_GeomFromText('Polygon((" . $this->pointsArrayToString($pointsArray) . "))'), ST_GeomFromText( CONCAT('POINT(', CONVERT(coord_x, CHAR(20)), ' ', CONVERT(coord_y, CHAR(20)), ')') )) AND (?";

			for($j = 0; $j < count($jaggedArrayByLayer[$i]); $j++) {
				$dtRow = $jaggedArrayByLayer[$i][$j];
				$cond = $this->switchColumn($tableData[$dtRow]->campo, $tableData[$dtRow]->valor);

				if($where[-1] == "?") { // ? symbol is a flag that tells whether a condition has been added
					$where = substr($where, 0, -1);		// Remove ? symbol
					$where = $where . $cond;			// Concatenate first condition
				}
				else { // Concatenate remaining conditions with either OR or AND depending on the selected rbtn
					$where = $where . ' ' . $booleanOp . ' ' . $cond;
				}
			}

			$where = $where . ")"; // Close user-added conditions
			$this->db->select($select);
			$this->db->from($from);
			// Layers that have columns from +1 table that are not catalogs and thus require an inner join
			if($arrLayers[$i] == "GIROS COMERCIALES") {
				$this->db->join('comercio_tbl_giros_comerciales_licencias AS L', 'comercio_tbl_giros_comerciales.id = L.id_giro_comercial');
			}
			$this->db->where($where);

			//$stmt = $this->db->get_compiled_select(); // Save generated sql query (testing purposes)
			//array_push($arrQueryBuilder, $stmt);
			$queryResult = $this->db->get()->result_array();
			$returnData = array_merge($returnData, $queryResult);
		}

		return $returnData;
	}

	private function switchTableSelectedMarker($marcador) {
		/* NOMBRE DE LA TABLA COMO APARECE EN ctrl_select_capas */
		$tableBaseName = $this->switchTable("marcador", $marcador);

		/* CATÁLOGOS A UNIR CON LA TABLA DE LA CAPA DADO QUE ÉSTA TRAE LA LLAVE FORÁNEA PERO NO EL NOMBRE.
		NOTA: HAY UN ESPACIO QUE SEPARA EL NOMBRE DE LA TABLA Y LA PALABRA 'JOIN' */
		$cond_fisica =
			" JOIN ct_cond_fisica USING (id_cond_fisica)";
		$cond_fisica_and_material =
			" JOIN ct_cond_fisica USING (id_cond_fisica) JOIN ct_material USING (id_material)";

        switch($marcador) {
            /* CAPAS QUE TIENEN LLAVES FORÁNEAS A CATÁLOGOS */
            case "postes":
            case "luminarias":
            case "panteon_municipal":
                return $tableBaseName . $cond_fisica_and_material;
                break;
            case "telefonos_publicos":
                return $tableBaseName . $cond_fisica;
				break;

			/* CAPAS QUE REQUIEREN UN JOIN CON OTRAS TABLAS QUE NO SON CATÁLOGOS */
			case "giros_comerciales":
				return $tableBaseName . " AS GC JOIN comercio_tbl_giros_comerciales_licencias AS L ON GC.id = L.id_giro_comercial";
				break;

            /* CUALQUIER OTRA CAPA SIN CATÁLOGOS */
            default:
                return $tableBaseName;
        }
	}

	private function switchColumnSelectedMarker($marcador) {
        /* LAS COORDENADAS APARECEN EN CUALQUIER CAPA */
        $coordinates =
            "coord_y AS 'LATITUD', coord_x AS 'LONGITUD', "; // Latitude appears first on GPS coordinates

        /* ALIAS DE VALORES DE CATÁLOGOS */
        $cond_fisica =
            "cond_fisica AS 'CONDICIÓN FÍSICA'";
        $cond_fisica_and_material =
            "material AS 'MATERIAL', cond_fisica AS 'CONDICIÓN FÍSICA'";

        switch($marcador) {
            /* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: GENERALES */
            case "bancos":
                return $coordinates . "nombre AS 'BANCO', tipo AS 'SERVICIO'";
                break;
            case "hoteles":
                return $coordinates . "nombre AS 'HOTEL'";
                break;
            case "telefonos_publicos":
                return $coordinates . "empresa_responsable AS 'EMPRESA', tipo AS 'TIPO', funciona AS 'FUNCIONA (SI/NO)', modalidad AS 'MODALIDAD', " . $cond_fisica;
                break;
            case "postes":
                return $coordinates . "empresa_responsable AS 'EMPRESA', f_primaria AS 'FUENTE PRIMARIA', f_secundaria AS 'FUENTE SECUNDARIA', " . $cond_fisica_and_material;
                break;
            case "luminarias":
                return $coordinates . "f_primaria AS 'FUENTE PRIMARIA', f_secundaria AS 'FUENTE SECUNDARIA', tipo AS 'TIPO DE POSTE', " . $cond_fisica_and_material . ", calle AS 'CALLE', colonia AS 'COLONIA'";
                break;

            /* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: INAH */
            case "monumentos_historicos":
                return $coordinates . "clave_catastral AS 'CLAVE CATASTRAL', ficha AS 'FICHA', epoca AS 'ÉPOCA', genero_arquitectonico AS 'GÉNERO ARQUITECTÓNICO', ubicacion AS 'UBICACIÓN'";
                break;

			/* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: COMERCIO */
			case "giros_comerciales":
				return $coordinates . "clave_catastral AS 'CLAVE CATASTRAL', colonia AS 'COLONIA', localidad AS 'LOCALIDAD'";
				break;
			case "plazas_comerciales":
				return $coordinates . "nombre AS 'PLAZA'";
				break;

			/* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: SALUD */
			case "hospitales":
				return $coordinates . "nombre AS 'HOSPITAL', dependencia AS 'DEPENDENCIA', tipo_hospital AS 'CLASIFICACIÓN'";
				break;

			/* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: REGISTRO CIVIL */
			case "panteon_municipal":
				return $coordinates . "clave_catastral AS 'CLAVE CATASTRAL', num_manzana AS 'NO. DE MANZANA', num_lote AS 'NO. DE LOTE', seccion AS 'NO. DE SECCIÓN', seccion_calle AS 'NO. DE SECCIÓN EN CALLE', calle AS 'NO. DE CALLE', numero AS 'NO.', capacidad AS 'CAPACIDAD', " . $cond_fisica_and_material . ", observaciones AS 'OBSERVACIONES'";
				break;
        }
	}

	// Location is in comercio_tbl_giros_comerciales and trade(s) in comercio_tbl_giros_comerciales_licencias
	private function getTrades($coord_x, $coord_y) {
		$this->db->select("id AS 'id_giro_comercial'");
		$this->db->where( array('coord_x' => $coord_x, 'coord_y' => $coord_y) );
		$location = $this->db->get('comercio_tbl_giros_comerciales')->row_array();
		$id_giro_comercial = $location['id_giro_comercial'];

		$this->db->select("nombre_comercial AS 'NEGOCIO', giro_comercial AS 'GIRO', licencia AS 'LICENCIA'");
		$this->db->where('id_giro_comercial', $id_giro_comercial);
		$this->db->order_by(2);
		$tradesArray = $this->db->get('comercio_tbl_giros_comerciales_licencias')->result_array();

        return $tradesArray;
	}

	public function getMapSelectedMarker()
	{
		$markerData = json_decode($_POST['markerData']); // 3-column map marker (coord_x, coord_y and marker name)

		// Select columns depending on layer selected
		$select = $this->switchColumnSelectedMarker($markerData->marcador);
		// DB table name
		$from = $this->switchTableSelectedMarker($markerData->marcador);
		// The element coordinates must be the ones from the selected marker
		$where = "coord_x = " . $markerData->coord_x . " AND coord_y = " . $markerData->coord_y;

		$manualQuery = "SELECT " . $select . " FROM " . $from . " WHERE " . $where;

		$queryResult = $this->db->query($manualQuery)->row_array();

        $jsonTable = '';
		foreach($queryResult as $col => $value) {
        	$jsonTable = $jsonTable . '<tr><td class="marker-table-td-col">' . $col . '</td>' .
                '<td class="marker-table-td-value">' . $value . '</td></tr> ';
		}

		// This is a special case because several trades and stores might be in the same place
		if($markerData->marcador == "giros_comerciales") {
			$tradesArray = $this->getTrades($markerData->coord_x, $markerData->coord_y);
			foreach($tradesArray as $i => $trade) {
				$trade_num = $i + 1;
				$jsonTable = $jsonTable . '<tr><td class="marker-table-td-col">NEGOCIO ' . $trade_num . '</td>' .
					'<td class="marker-table-td-value">' . $trade['NEGOCIO'] . '</td></tr> ';
				/* Remove the first element from the single-array trade so the index number only appears on
				NEGOCIO # instead of all the three labels (NEGOCIO #, GIRO #, LICENCIA #) */
				array_shift($trade);
				foreach($trade as $col => $value) {
					$jsonTable = $jsonTable . '<tr><td class="marker-table-td-col">' . $col . '</td>' .
						'<td class="marker-table-td-value">' . $value . '</td></tr> ';
				}
			}
		}

		//return $manualQuery; // Return generated sql query (testing purposes)
		return $jsonTable;
	}
}
?>