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

			/* COLUMNAS CON VALORES NO PREDEFINIDOS QUE SE INGRESAN MEDIANTE UN INPUT DE TEXTO.
			NOTA: ESTAS COLUMNAS NO ESTÁN EN ctrl_nombre_columnas */
			case "TODOS":
                return "1 = 1";
            case "NOMBRE":
                return "nombre like '%" . $value . "%'";
            case "NOMBRE COMERCIAL":
                return "nombre_comercial like '%" . $value . "%'";

			/* VALORES DE CAMPOS DE CATÁLOGOS. Es necesario que la tabla, id y descripción sigan la nomenclatura
			base, e.g. cond_fisica: ct_cond_fisica (tabla), id_cond_fisica (id) y cond_fisica (descripción) */
            case "MATERIAL":
                return "id_material = " . $this->getCatalogId("material", $value);
            case "CONDICIÓN FÍSICA":
                return "id_cond_fisica = " . $this->getCatalogId("cond_fisica", $value);

             /* VALORES DE CAMPOS CON FORMATOS ESPECÍFICOS O NULOS PRESENTADOS CON UN ALIAS  */
            case "FUENTE": // Fuente secundaria de una luminaria
                if($value === "SIN FUENTE") {
                    return "f_secundaria IS NULL";
                }
                else {
                    return "f_secundaria = '" . $value . "'";
                }

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

	private function getJoinCondition($baseTable)
	{
		switch($baseTable) {		// Base table alias is already BT when this function is called
			case "GIROS COMERCIALES":
				return array('comercio_tbl_giros_comerciales_licencias AS L', 'BT.id = L.id_giro_comercial');
			case "LOCATARIOS MERCADOS":
				return array('comercio_tbl_mercados AS M', 'BT.id_mercado = M.id');
			case "TIANGUISTAS":
				return array('comercio_tbl_tianguis AS T', 'BT.id_tianguis = T.id');
			default:				// Any other case will return null so no join condition will be added
				return null;
		}
	}

	// This function must not be edited. All the map queries must be added/changed in switchColumn()
	public function getMapTotals()
	{
		$tableData = json_decode($_POST['tableData']);		// 3-column table queries (layer, column and value)
		$pointsArray = json_decode($_POST['pointsArray']);	// Polygon coordinates in the map
		$booleanOp = $_POST['booleanOp'];					// OR or AND operator to join WHERE clauses
		$returnData = array();
		//$arrQueryBuilder = array(); // Output n sql queries after a succesful AJAX call (testing purposes)

		if($booleanOp === "OR") {
			for($i = 0; $i < count($tableData); $i++) {
				// DB table name alias is BT
				$from = $this->switchTable('capa', $tableData[$i]->capa) . " AS BT";
				// One user-added condition for each row in the datatable, despite a layer having +1 queries
				$cond = $this->switchColumn($tableData[$i]->campo, $tableData[$i]->valor);
				// All elements must be inside the drawn polygon area in the map
				$where = "ST_INTERSECTS(ST_GeomFromText('Polygon((" . $this->pointsArrayToString($pointsArray) . "))'), ST_GeomFromText( CONCAT('POINT(', CONVERT(BT.coord_x, CHAR(20)), ' ', CONVERT(BT.coord_y, CHAR(20)), ')') )) AND (" . $cond . ")";
				
					$this->db->select('count(BT.id) AS totalByRow');
					$this->db->from($from);
				
				
				/* Some layers have columns from another table (not the base table in ctrl_select_capas)
				that is not a catalog and thus require an explicit inner join */
				$arrJoinCondition = $this->getJoinCondition($tableData[$i]->capa);
				if($arrJoinCondition !== NULL)
					$this->db->join($arrJoinCondition[0], $arrJoinCondition[1]);
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
			/* Save the total amount for each layer and at the end loops over the tableData to output the
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
				$allLayerCond = "?"; // Concatenate one or more single conditions (datatable rows) by layer

				for($j = 0; $j < count($jaggedArrayByLayer[$i]); $j++) {
					$dtRow = $jaggedArrayByLayer[$i][$j];
					$singleCond = $this->switchColumn($tableData[$dtRow]->campo, $tableData[$dtRow]->valor);
					if($allLayerCond == "?") // ? symbol is a flag that shows whether a condition has been added
						$allLayerCond = $singleCond;
					else
						$allLayerCond = $allLayerCond . ' AND ' . $singleCond; // AND is $booleanOp
				}

				// DB table name alias is BT
				$from = $this->switchTable('capa', $arrLayers[$i]) . " AS BT";
				// All elements must be inside the drawn polygon area in the map
				$where = "ST_INTERSECTS(ST_GeomFromText('Polygon((" . $this->pointsArrayToString($pointsArray) . "))'), ST_GeomFromText( CONCAT('POINT(', CONVERT(BT.coord_x, CHAR(20)), ' ', CONVERT(BT.coord_y, CHAR(20)), ')') )) AND (" . $allLayerCond . ")";

				$this->db->select('count(BT.id) AS totalByLayer');
				$this->db->from($from);
				/* Some layers have columns from another table (not the base table in ctrl_select_capas)
				that is not a catalog and thus require an explicit inner join */
				$arrJoinCondition = $this->getJoinCondition($arrLayers[$i]);
				if($arrJoinCondition !== NULL)
					$this->db->join($arrJoinCondition[0], $arrJoinCondition[1]);
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
		//$arrQueryBuilder = array(); // Output n sql queries after a succesful AJAX call (testing purposes)

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
			$select = "BT.coord_x AS longitude, BT.coord_y AS latitude, lower(tr('" . $arrLayers[$i] . "', 'ÁÉÍÓÚÑ ', 'AEIOUN_')) AS layer";
			// DB table name alias is BT
			$from = $this->switchTable('capa', $arrLayers[$i]) . " AS BT";
			// All elements must be inside the drawn polygon area in the map
			$where = "ST_INTERSECTS(ST_GeomFromText('Polygon((" . $this->pointsArrayToString($pointsArray) . "))'), ST_GeomFromText( CONCAT('POINT(', CONVERT(BT.coord_x, CHAR(20)), ' ', CONVERT(BT.coord_y, CHAR(20)), ')') )) AND (?";

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
			/* Some layers have columns from another table (not the base table in ctrl_select_capas)
			that is not a catalog and thus require an explicit inner join */
			$arrJoinCondition = $this->getJoinCondition($arrLayers[$i]);
			if($arrJoinCondition !== NULL)
				$this->db->join($arrJoinCondition[0], $arrJoinCondition[1]);
			$this->db->where($where);

			//$stmt = $this->db->get_compiled_select(); // Save generated sql query (testing purposes)
			//array_push($arrQueryBuilder, $stmt);
			$queryResult = $this->db->get()->result_array();
			$returnData = array_merge($returnData, $queryResult);
		}

		return $returnData;
	}

	private function switchTableSelectedMarker($marcador)
	{
		/* NOMBRE DE LA TABLA COMO APARECE EN ctrl_select_capas */
		$baseTable = $this->switchTable("marcador", $marcador);

		/* CATÁLOGOS A UNIR CON LA TABLA DE LA CAPA DADO QUE ÉSTA TRAE LA LLAVE FORÁNEA PERO NO EL NOMBRE.
		NOTA: LA TABLA BASE SIEMPRE LLEVA EL ALIAS BT QUE SE NECESITA PARA EL WHERE EN getMapSelectedMarker() */
		$cond_fisica =
			" AS BT JOIN ct_cond_fisica USING (id_cond_fisica)";
		$cond_fisica_and_material =
			" AS BT JOIN ct_cond_fisica USING (id_cond_fisica) JOIN ct_material USING (id_material)";

        switch($marcador) {
            /* CAPAS QUE TIENEN LLAVES FORÁNEAS A CATÁLOGOS */
            case "postes":
            case "luminarias":
            case "panteon_municipal":
                return $baseTable . $cond_fisica_and_material;
			case "telefonos_publicos":
			case "arboles":
                return $baseTable . $cond_fisica;

			/* CAPAS QUE REQUIEREN UN JOIN CON OTRAS TABLAS QUE NO SON CATÁLOGOS */
			case "giros_comerciales":
				return $baseTable . " AS BT JOIN comercio_tbl_giros_comerciales_licencias AS L ON BT.id = L.id_giro_comercial";
			case "locatarios_mercados":
				return $baseTable . " AS BT JOIN comercio_tbl_mercados AS M ON BT.id_mercado = M.id";
			case "tianguistas":
				return $baseTable . " AS BT JOIN comercio_tbl_tianguis AS T ON BT.id_tianguis = T.id";

            /* CUALQUIER OTRA CAPA SIN CATÁLOGOS */
            default:
                return $baseTable . " AS BT";
        }
	}

	private function switchColumnSelectedMarker($marcador)
	{
        /* LAS COORDENADAS APARECEN EN CUALQUIER CAPA */
        $coordinates =
            "BT.coord_y AS 'LATITUD', BT.coord_x AS 'LONGITUD', "; // Latitude appears first on GPS coordinates

        /* ALIAS DE VALORES DE CATÁLOGOS */
        $cond_fisica =
            "cond_fisica AS 'CONDICIÓN FÍSICA'";
        $cond_fisica_and_material =
            "material AS 'MATERIAL', cond_fisica AS 'CONDICIÓN FÍSICA'";

        switch($marcador) {
            /* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: GENERALES */
            case "bancos":
                return $coordinates . "nombre AS 'BANCO', tipo AS 'SERVICIO'";
            case "hoteles":
                return $coordinates . "nombre AS 'HOTEL'";
            case "telefonos_publicos":
                return $coordinates . "empresa_responsable AS 'EMPRESA', tipo AS 'TIPO', funciona AS 'FUNCIONA (SI/NO)', modalidad AS 'MODALIDAD', " . $cond_fisica;
            case "postes":
                return $coordinates . "empresa_responsable AS 'EMPRESA', f_primaria AS 'FUENTE PRIMARIA', f_secundaria AS 'FUENTE SECUNDARIA', " . $cond_fisica_and_material;
            case "luminarias":
                return $coordinates . "f_primaria AS 'FUENTE PRIMARIA', f_secundaria AS 'FUENTE SECUNDARIA', tipo AS 'TIPO DE POSTE', " . $cond_fisica_and_material . ", calle AS 'CALLE', colonia AS 'COLONIA'";

            /* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: INAH */
            case "monumentos_historicos":
                return $coordinates . "clave_catastral AS 'CLAVE CATASTRAL', ficha AS 'FICHA', epoca AS 'ÉPOCA', genero_arquitectonico AS 'GÉNERO ARQUITECTÓNICO', ubicacion AS 'UBICACIÓN'";

			/* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: COMERCIO */
			case "giros_comerciales":
				return $coordinates . "clave_catastral AS 'CLAVE CATASTRAL', colonia AS 'COLONIA', localidad AS 'LOCALIDAD'";
			case "plazas_comerciales":
				return $coordinates . "nombre AS 'PLAZA'";
			case "mercados":
				return $coordinates . "nombre AS 'MERCADO', calle AS 'CALLE', colonia AS 'COLONIA', propietario AS 'PROPIETARIO', tipo_predio AS 'PREDIO'";
			case "tianguis":
				return $coordinates . "nombre AS 'TIANGUIS', calle AS 'CALLE', colonia AS 'COLONIA', CONCAT(dia, ' ', horario) AS 'HORARIO', area AS 'ÁREA EN M2'";
			case "locatarios_mercados":
				return $coordinates . "M.nombre AS 'MERCADO', giro AS 'GIRO', local_ AS 'NO. DE LOCAL', observaciones AS 'OBSERVACIONES'";
			case "tianguistas":
				return $coordinates . "T.nombre AS 'TIANGUIS', giro AS 'GIRO', area AS 'ÁREA EN M2', union_ AS 'UNIÓN'";

			/* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: SALUD */
			case "hospitales":
				return $coordinates . "nombre AS 'HOSPITAL', dependencia AS 'DEPENDENCIA', tipo_hospital AS 'CLASIFICACIÓN'";

			/* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: ECOLOGÍA */
			case "arboles":
				return $coordinates . "especie AS 'ESPECIE', alto_m AS 'ALTURA', ancho_m AS 'ANCHURA', solicito AS 'SERVICIO SOLICITADO', autoriza as 'RESOLUCIÓN', DATE_FORMAT(fecha_reso, '%d/%m/%y') AS 'FECHA DE RESOLUCIÓN', reforestacion AS 'FUE REFORESTADO', numero AS 'NO. DE ÁRBOL', " . $cond_fisica;

			/* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: REGISTRO CIVIL */
			case "panteon_municipal":
				return $coordinates . "clave_catastral AS 'CLAVE CATASTRAL', num_manzana AS 'NO. DE MANZANA', num_lote AS 'NO. DE LOTE', seccion AS 'NO. DE SECCIÓN', seccion_calle AS 'NO. DE SECCIÓN EN CALLE', calle AS 'NO. DE CALLE', numero AS 'NO.', capacidad AS 'CAPACIDAD', " . $cond_fisica_and_material . ", observaciones AS 'OBSERVACIONES'";
        }
	}

	// Location is in comercio_tbl_giros_comerciales and business in comercio_tbl_giros_comerciales_licencias
	private function getBusiness($coord_x, $coord_y)
	{
		$this->db->select("id AS 'id_giro_comercial'");
		$this->db->where( array('coord_x' => $coord_x, 'coord_y' => $coord_y) );
		$location = $this->db->get('comercio_tbl_giros_comerciales')->row_array();
		$id_giro_comercial = $location['id_giro_comercial'];

		$this->db->select("nombre_comercial AS 'NEGOCIO', giro_comercial AS 'GIRO', licencia AS 'LICENCIA'");
		$this->db->where('id_giro_comercial', $id_giro_comercial);
		$this->db->order_by(2);
		$bizArray = $this->db->get('comercio_tbl_giros_comerciales_licencias')->result_array();

        return $bizArray;
	}

	public function getMapSelectedMarker()
	{
		$markerData = json_decode($_POST['markerData']); // 3-column map marker (coord_x, coord_y and marker name)

		// Select columns depending on layer selected
		$select = $this->switchColumnSelectedMarker($markerData->marcador);
		// DB table name
		$from = $this->switchTableSelectedMarker($markerData->marcador);
		// The element coordinates must be the ones from the selected marker
		$where = "BT.coord_x = " . $markerData->coord_x . " AND BT.coord_y = " . $markerData->coord_y;

		$manualQuery = "SELECT " . $select . " FROM " . $from . " WHERE " . $where;

		$queryResult = $this->db->query($manualQuery)->row_array();

        $jsonTable = '';
		foreach($queryResult as $col => $value) {
        	$jsonTable = $jsonTable . '<tr><td class="marker-table-td-col">' . $col . '</td>' .
                '<td class="marker-table-td-value">' . $value . '</td></tr> ';
		}

		// This is a special case because several business and stores might be in the same location
		if($markerData->marcador == "giros_comerciales") {
			$bizArray = $this->getBusiness($markerData->coord_x, $markerData->coord_y);
			foreach($bizArray as $i => $biz) {
				$biz_num = $i + 1;
				$jsonTable = $jsonTable . '<tr><td class="marker-table-td-col">NOMBRE DEL NEGOCIO ' . $biz_num .
					'</td>' . '<td class="marker-table-td-value">' . $biz['NEGOCIO'] . '</td></tr> ';
				/* Remove the first element from the single-array so the index number only appears on NOMBRE DEL
				NEGOCIO # instead of all the three labels (NOMBER DEL NEGOCIO #, GIRO #, LICENCIA #) */
				array_shift($biz);
				foreach($biz as $col => $value) {
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