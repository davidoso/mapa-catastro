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
            case "COLONIA":
				return "id_colonia = " . $this->getCatalogId("colonia", $value);
			case "COLOR":
				return "id_color = " . $this->getCatalogId("color", $value);
			case "CONDICIÓN FÍSICA":
				return "id_cond_fisica = " . $this->getCatalogId("cond_fisica", $value);
			case "NIVEL ESCOLAR":
				return "id_nivel_escolar = " . $this->getCatalogId("nivel_escolar", $value);
			case "TIPO ":
				return "id_tipo_escultura = " . $this->getCatalogId("tipo_escultura", $value);
			case "TIPO   ":
				return "id_tipologia = " . $this->getCatalogId("tipologia", $value);
			case "TIPO DE RIEGO":
				return "id_tipo_riego = " . $this->getCatalogId("tipo_riego", $value);
			case "TIPO DE HOSPITAL":
				return "id_tipo_hospital = " . $this->getCatalogId("tipo_hospital", $value);
			case "TIPO DE MATERIAL":
				return "id_material = " . $this->getCatalogId("material", $value);

			case "TIPO DE SEMÁFORO":
				return "id_tipo_semaforo = " . $this->getCatalogId("tipo_semaforo", $value);
			case "TIPO DE LUZ":
				return "id_tipo_luz = " . $this->getCatalogId("tipo_luz", $value);
			case "TIPO DE COLUMNA":
				return "id_tipo_co = " . $this->getCatalogId("tipo_co", $value);
			case "TIPO DE ESTRUCTURA":
				return "id_tipo_estructura = " . $this->getCatalogId("tipo_estructura", $value);
			case "CONDICIÓN FÍSICA ELEMENTO":
				return "id_cond_fisica_ele = " . $this->getCatalogId("cond_fisica", $value);
			case "CONDICIÓN FÍSICA ESTRUCTURA":
				return "id_cond_fisica_estructura = " . $this->getCatalogId("cond_fisica", $value);

			case "DEPENDENCIA":
				return "id_dependencia = " . $this->getCatalogId("dependencia", $value);
			case "JURISDICCIÓN":
				return "id_jurisdiccion = " . $this->getCatalogId("jurisdiccion", $value);
			case "ESPACIO DEPORTIVO":
				return "id_espacio_deportivo= " . $this->getCatalogId("espacio_deportivo", $value);
			case "CLASIFICACIÓN":
                return "id_clasificacion = " . $this->getCatalogId("clasificacion", $value);
			// BUSQUEDAS ESPECIALES
			case "INCIDENCIAS":
				return $this->getCatalogIdEsp($value)."!=0";
			case "POBLACIÓN":
				return $this->getCatalogIdEsp($value)."!=0";
			case "HOGAR JEFATURA":
				return $this->getCatalogIdEsp($value)."!=0";
			case "RELIGIÓN":
				return $this->getCatalogIdEsp($value)."!=0";
			case "SERVICIOS MÉDICOS":
				return $this->getCatalogIdEsp($value)."!=0";
			case "VIVIENDAS":
				return $this->getCatalogIdEsp($value)."!=0";
			 
			
             /* VALORES DE CAMPOS CON FORMATOS ESPECÍFICOS O NULOS PRESENTADOS CON UN ALIAS  */
            /*case "FUENTE": // Fuente secundaria de una luminaria
                if($value === "SIN FUENTE") {
                    return "f_secundaria IS NULL";
                }
                else {
                    return "f_secundaria = '" . $value . "'";
                }
			*/
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
	private function getCatalogIdEsp($value)
	{
			$this->db->select('columna_bd');
			$this->db->from('ctrl_nombre_columnas_especial');
			$this->db->where('columna_frontend', "$value");
			$query = $this->db->get()->row_array();
	
			return $query['columna_bd'];

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
				return array("comercio_tbl_giros_comerciales_licencias AS L", "BT.id = L.id_giro_comercial");
			case "LOCATARIOS MERCADOS":
				return array("comercio_tbl_mercados AS M", "BT.id_mercado = M.id");
			case "TIANGUISTAS":
				return array("comercio_tbl_tianguis AS T", "BT.id_tianguis = T.id");					
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
			$select = "BT.id, BT.coord_x AS longitude, BT.coord_y AS latitude, lower(tr('" . $arrLayers[$i] . "', 'ÁÉÍÓÚÑ ', 'AEIOUN_')) AS layer";
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
		$colonia_and_cond_fisica_and_material =
			" AS BT JOIN ct_colonia USING (id_colonia) JOIN ct_cond_fisica USING (id_cond_fisica) JOIN ct_material USING (id_material)";
		$colonia =
			" AS BT JOIN ct_colonia USING (id_colonia)";
		$colonias =
			" JOIN ct_colonia USING (id_colonia)";
		$nivel_escolar =
			" AS BT JOIN ct_nivel_escolar USING (id_nivel_escolar)";
		$tipo_ =
			" AS BT JOIN ct_tipo_escultura USING (id_tipo_escultura)";
		$jurisdiccion_and_colonia_and_tipo_and_cond_fisica =
			" AS BT JOIN ct_jurisdiccion USING (id_jurisdiccion) JOIN ct_colonia USING (id_colonia) JOIN ct_tipo_escultura USING (id_tipo_escultura) JOIN ct_cond_fisica USING (id_cond_fisica)";
		$espacio_deportivo =
			" AS BT JOIN ct_espacio_deportivo USING (id_espacio_deportivo)";
		$espacio_deportivo_and_colonia =
			" AS BT JOIN ct_colonia USING (id_colonia) JOIN ct_espacio_deportivo USING (id_espacio_deportivo)";
		$cond_fisica_and_espacio_deportivo =
			" AS BT JOIN ct_cond_fisica USING (id_cond_fisica) JOIN ct_espacio_deportivo USING (id_espacio_deportivo)";
		$tipologia_and_tipo_riego_and_colonia =
			" AS BT JOIN ct_tipologia USING (id_tipologia) JOIN ct_tipo_riego USING (id_tipo_riego) JOIN ct_colonia USING (id_colonia)";
		$tipo_hospital_and_dependencia =
			" AS BT JOIN ct_tipo_hospital USING (id_tipo_hospital) JOIN ct_dependencia USING (id_dependencia)";
		$clasificacion_and_colonia =
			" AS BT JOIN ct_clasificacion USING (id_clasificacion) JOIN ct_colonia USING (id_colonia)";
		$material =
			" AS BT JOIN ct_material USING (id_material)";
		$cond_fisica_and_material_and_color =
			" AS BT JOIN ct_cond_fisica USING (id_cond_fisica) JOIN ct_material USING (id_material) JOIN ct_color USING (id_color)";
		$semaforos =
			" AS BT JOIN ct_cond_fisica AS CON ON BT.id_cond_fisica = CON.id_cond_fisica  JOIN ct_cond_fisica as CE on BT.id_cond_fisica_ele = CE.id_cond_fisica JOIN ct_cond_fisica as CEE on BT.id_cond_fisica_estructura = CEE.id_cond_fisica JOIN ct_tipo_estructura USING (id_tipo_estructura) JOIN ct_color USING (id_color) JOIN ct_tipo_co USING (id_tipo_co) JOIN ct_tipo_luz USING (id_tipo_luz) JOIN ct_tipo_semaforo USING (id_tipo_semaforo)";
        switch($marcador) {
			/* CAPAS QUE TIENEN LLAVES FORÁNEAS A CATÁLOGOS */
			case "topes":
				return $baseTable . $cond_fisica_and_material_and_color;
			case "semaforos":
				return $baseTable . $semaforos;
			case "vialidades":
				return $baseTable . $material;
			case "numeros_oficiales":
				return $baseTable . $colonia;
			case "antenas_de_telecomunicacion":
				return $baseTable . $colonia;
			case "sismo_2003":
				return $baseTable . $clasificacion_and_colonia;
			case "refugios_temporales":
				return $baseTable . $colonia;
			case "hospitales":
				return $baseTable . $tipo_hospital_and_dependencia;
			case "parques_y_jardines":
				return $baseTable .$tipologia_and_tipo_riego_and_colonia;
			case "canchas":
				return $baseTable . $cond_fisica_and_espacio_deportivo;
			case "activacion_fisica":
				return $baseTable . $espacio_deportivo_and_colonia;
			case "postes":
				return $baseTable . $cond_fisica_and_material;
			case "luminarias":
				return $baseTable .$colonia_and_cond_fisica_and_material;
            case "panteon_municipal":
                return $baseTable . $cond_fisica_and_material;
			case "telefonos_publicos":
			case "arboles":
				return $baseTable . $cond_fisica;
			case "esculturas":
				return $baseTable . $jurisdiccion_and_colonia_and_tipo_and_cond_fisica;
			case "areas_verdes":
				return $baseTable . $colonia;
			case "escuelas":
				return $baseTable . $nivel_escolar;
			case "tianguis":
				return $baseTable . $colonia;
			case "infraestructura_deportiva":
				return $baseTable . $colonia;
			case "mercados":
				return $baseTable . $colonia;

			/* CAPAS QUE REQUIEREN UN JOIN CON OTRAS TABLAS QUE NO SON CATÁLOGOS */
			case "giros_comerciales":
				return $baseTable . " AS BT JOIN comercio_tbl_giros_comerciales_licencias AS L ON BT.id = L.id_giro_comercial " . $colonias;
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
		$coordinates_ =
            "BT.coord_y AS 'LATITUD', BT.coord_x AS 'LONGITUD' "; // Latitude appears first on GPS coordinates for table without other data
        /* ALIAS DE VALORES DE CATÁLOGOS */
        $cond_fisica =
            "cond_fisica AS 'CONDICIÓN FÍSICA'";
        $cond_fisica_and_material =
			"material AS 'MATERIAL', cond_fisica AS 'CONDICIÓN FÍSICA'";
		$cond_fisica_and_material_and_colonia =
			"material AS 'MATERIAL', cond_fisica AS 'CONDICIÓN FÍSICA' , colonia AS 'COLONIA'";
		$colonia =
			"colonia AS 'COLONIA'";	
		$espacio_deportivo =
			"espacio_deportivo AS 'ESPACIO DEPORTIVO'";	
		$nivel_escolar =
			"nivel_escolar AS 'NIVEL ESCOLAR'";
		$tipo_ =
			"tipo_escultura AS 'TIPO DE ESCULTURA'";
		$jurisdiccion =
            "jurisdiccion AS 'JURISDICCIÓN'";
		$espacio_deportivo_and_colonia =
			"colonia AS 'COLONIA', espacio_deportivo AS 'ESPACIO DEPORTIVO'";
		$espacio_deportivo_and_colonia =
			"espacio_deportivo AS 'ESPACIO DEPORTIVO', colonia AS 'COLONIA'";
		$tipologia =
			"tipologia AS 'TIPO'";
		$tipo_riego =
			"tipo_riego AS 'TIPO DE RIEGO'";
		$tipo_hospital_and_dependencia=
			"tipo_hospital AS 'TIPO DE HOSPITAL', dependencia AS 'DEPENDENCIA'";
		$clasificacion =
			"clasificacion AS 'CLASIFICACIÓN'";
		$material =
			"material AS 'MATERIAL'";
		$cond_fisica_and_material_and_color =
			"material AS 'MATERIAL', cond_fisica AS 'CONDICIÓN FÍSICA', color AS 'COLOR'";
		$tipo_semaforo =
			"tipo_semaforo AS 'TIPO DE SEMÁFORO'";
		$tipo_luz =
			"tipo_luz AS 'TIPO DE LUZ'";
		$tipo_co =
			"tipo_co AS 'TIPO DE COLUMNA'";
		$tipo_estructura =
			"tipo_estructura AS 'TIPO DE ESTRUCTURA'";
		$color =
			"color AS 'COLOR'";
        switch($marcador) {
			
			/* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: CATASTRO */
			case "condominios":
				return $coordinates . "nombre AS 'NOMBRE'";
			case "construcciones":
				return "BT.coord_y AS 'LATITUD', BT.coord_x AS 'LONGITUD'";
			case "manzanas":
				return $coordinates . "numero AS 'NO.'";
			case "zonas_catastrales":
				return $coordinates . "nombre AS 'NOMBRE'";

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
                return $coordinates . "f_primaria AS 'FUENTE PRIMARIA', f_secundaria AS 'FUENTE SECUNDARIA', tipo AS 'TIPO DE POSTE', " . $cond_fisica_and_material_and_colonia . ", calle AS 'CALLE'";

            /* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: INAH */
            case "monumentos_historicos":
                return $coordinates . "clave_catastral AS 'CLAVE CATASTRAL', ficha AS 'FICHA', epoca AS 'ÉPOCA', genero_arquitectonico AS 'GÉNERO ARQUITECTÓNICO', ubicacion AS 'UBICACIÓN'";

			/* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: COMERCIO */
			case "giros_comerciales":
				return $coordinates . "clave_catastral AS 'CLAVE CATASTRAL',  localidad AS 'LOCALIDAD', " . $colonia;
			case "plazas_comerciales":
				return $coordinates . "nombre AS 'NOMBRE'";
			case "mercados":
				return $coordinates . "nombre AS 'NOMBRE'," . $colonia . ",calle AS 'CALLE', propietario AS 'PROPIETARIO', tipo_predio AS 'PREDIO'";
			case "tianguis":
				return $coordinates . "nombre AS 'TIANGUIS'," . $colonia .", calle AS 'CALLE',  CONCAT(dia, ' ', horario) AS 'HORARIO', area AS 'ÁREA EN M2'";
			case "locatarios_mercados":
				return $coordinates . "M.nombre AS 'MERCADO', giro AS 'GIRO', local_ AS 'NO. DE LOCAL', observaciones AS 'OBSERVACIONES'";
			case "tianguistas":
				return $coordinates . "T.nombre AS 'TIANGUIS', giro AS 'GIRO', metros AS 'ÁREA EN M2', union_ AS 'UNIÓN'";

			/* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: SALUD */
			case "hospitales":
				return $coordinates . "nombre AS 'HOSPITAL',". $tipo_hospital_and_dependencia;

			/* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: DEPORTES */
			case "activacion_fisica":
				return $coordinates . "clave_catastral AS 'CLAVE CATASTRAL', area AS 'ÁREA EN M2', centro_deportivo AS 'CENTRO DEPORTIVO', " .$espacio_deportivo_and_colonia. ", ubicacion AS 'UBICACIÓN', programa AS 'PROGRAMA', promotor AS 'PROMOTOR', num_personas AS 'NO. DE PERSONAS'";
			case "canchas":
				return $coordinates . "techada AS 'TECHADA' , " . $cond_fisica_and_espacio_deportivo;
			case "infraestructura_deportiva":
				return $coordinates . "clave_catastral AS 'CLAVE CATASTRAL', localidad  AS 'LOCALIDAD' , propietario AS 'PROPIETARIO' , estado_uso AS 'ESTADO DE USO' , silla_ruedas AS 'ACCESIBILIDAD DE SILLA DE RUEDAS' , alimentos AS 'ALIMENTOS' , ancho AS 'ANCHO', largo AS 'LARGO' , " . $colonia . ", calle AS 'CALLE', codigo_postal AS 'CÓDIGO POSTAL' , referencia_instalacion  AS 'REFERENCIA' ";
			/* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: ECOLOGÍA */
			case "arboles":
				return $coordinates . "especie AS 'ESPECIE', alto_m AS 'ALTURA', ancho_m AS 'ANCHURA', solicito AS 'SERVICIO SOLICITADO', autoriza as 'RESOLUCIÓN', DATE_FORMAT(fecha_reso, '%d-%m-%Y') AS 'FECHA DE RESOLUCIÓN', reforestacion AS 'FUE REFORESTADO', numero AS 'NO. DE ÁRBOL', " . $cond_fisica;
			case "cauces_de_agua":
				return $coordinates . "nombre AS 'NOMBRE'";
			case "cuerpos_de_agua":
				return $coordinates . "tipo AS 'TIPO' , area AS 'ÁREA EN M2'";
			/* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: REGISTRO CIVIL */
			case "panteon_municipal":
				return $coordinates . "clave_catastral AS 'CLAVE CATASTRAL', num_manzana AS 'NO. DE MANZANA', num_lote AS 'NO. DE LOTE', seccion AS 'NO. DE SECCIÓN', seccion_calle AS 'NO. DE SECCIÓN EN CALLE', calle AS 'NO. DE CALLE', numero AS 'NO.', capacidad AS 'CAPACIDAD', " . $cond_fisica_and_material . ", observaciones AS 'OBSERVACIONES'";
			
			/* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: PATRIMONIO */
			case "parques_y_jardines":
				return $coordinates . "nombre AS 'NOMBRE'," . $colonia .", calle AS 'CALLE'," .  $tipologia . ", bancas AS 'TOTAL DE BANCAS', arboles AS 'TOTAL DE ÁRBOLES', juegos AS 'TOTAL DE JUEGOS', fuentes AS 'TOTAL DE FUENTES'," . $tipo_riego;
			
			case "areas_verdes":
				return $coordinates . "calle AS 'CALLE'," . $colonia . ", num_oficial AS 'NO. OFICIAL', tipo_predio AS 'TIPO DE PREDIO', superficie AS 'SUPERFICIE', descripcion AS 'DESCRIPCIÓN'";
			case "escuelas":
				return $coordinates . "clave_catastral AS 'CLAVE CATASTRAL', cct_tm AS 'CLAVE TURNO MATUTINO', nombre_tm AS 'NOMBRE TURNO MATUTINO',  cct_tv AS 'CLAVE TURNO VESPERTINO', nombre_tv AS 'NOMBRE TURNO VESPERTINO', cct_tn AS 'CLAVE TURNO NOCTURNO', nombre_tn AS 'NOMBRE TURNO NOCTURNO', calle AS 'DOMICILIO', zona_escolar AS 'ZONA ESCOLAR', " . $nivel_escolar . ", alumnos_tm AS 'TOTAL DE ALUMNOS TURNO MATUTINO', alumnos_m_tm AS 'TOTAL DE ALUMNAS TURNO MATUTINO', alumnos_h_tm AS 'TOTAL DE ALUMNOS TURNO MATUTINO', grupos_tm AS 'TOTAL DE GRUPOS TURNO MATUTINO', personal_tm AS 'TOTAL DE PERSONAL TURNO MATUTINO', alumnos_tv AS 'TOTAL ALUMNOS TURNO VESPERTINO', alumnos_m_tv AS 'TOTAL ALUMNAS TURNO VESPERTINO', alumnos_h_tv AS 'TOTAL ALUMNOS TURNO VESPERTINO',  grupos_tv AS 'TOTAL GRUPOS TURNO VESPERTINO', personal_tv AS 'TOTAL PERSONAL TURNO VESPERTINO', alumnos_tn AS 'TOTAL ALUMNOS TURNO NOCTURNO', alumnos_m_tn AS 'TOTAL ALUMNAS TURNO NOCTURNO', alumnos_h_tn AS 'TOTAL ALUMNOS TURNO NOCTURNO', grupos_tn AS 'TOTAL GRUPOS TURNO NOCTURNO', personal_tn AS 'TOTAL PERSONAL TURNO NOCTURNO'";
			case "esculturas":
				return $coordinates . "nombre AS 'NOMBRE', autor AS 'AUTOR', anio AS 'AÑO', ancho_m AS 'ANCHO', largo_m AS 'LARGO', alto_m AS 'ALTO'," . $tipo_. "," . $cond_fisica . ",". $jurisdiccion . ",". $colonia . ", calle AS 'CALLE', apropiada AS 'APROPIADA', protegida AS 'PROTEGIDA'" ;
			/* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: VIALIDADES */
			case "accidentes":
				return $coordinates . "fecha AS 'FECHA' , hora AS 'HORA (HH:MM:SS)' , afectados AS 'AFECTADOS' , lesionados AS 'LESIONADOS' , muertos AS 'FALLECIDOS'";
			case "multas":
				return $coordinates . "descripcion AS 'DESCRIPCIÓN' ,  DATE_FORMAT(fecha_ini, '%d-%m-%Y') AS 'FECHA'";
			case "paradas_de_camion":
				return $coordinates_; 
			case "rutas_de_camion":
				return $coordinates . "nombre AS 'NOMBRE'";
			case "topes":
				return $coordinates . $cond_fisica_and_material_and_color . ", DATE_FORMAT(fecha_mant, '%d-%m-%Y') AS 'FECHA'";
			case "semaforos":
				return $coordinates . $tipo_semaforo . "," . $tipo_estructura . "," . $tipo_co . "," . $tipo_luz . ", num_cabeza AS 'NO. CABEZAS', DATE_FORMAT(fecha_mant, '%d-%m-%Y') AS 'FECHA' ," . $color . ", CON.cond_fisica AS 'CONDICIÓN FÍSICA' , CE.cond_fisica AS 'CONDICIÓN FÍSICA ELEMENTO', CEE.cond_fisica AS 'CONDICIÓN FÍSICA ESTRUCTURA'";
			/* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: INEGI */
			case "caracteristicas_economicas":
				return $coordinates . "cvegeo AS 'CVEGEO', pob_eco_act_fem AS 'POBLACIÓN FEMENINA ECONÓMICAMENTE ACTIVA', pob_eco_act_masc AS 'POBLACIÓN MASCULINA ECONÓMICAMENTE ACTIVA', pob_no_eco_act AS 'POBLACIÓN NO ECONÓMICAMENTE ACTIVA', pob_desocupada AS 'POBLACIÓN DESOCUPADA'" ;
			case "caracteristicas_educativas":
				return $coordinates . "cvegeo AS 'CVEGEO', pob_3a5 AS 'POBLACIÓN DE 3 A 5 ESCOLARIZADA', pob_6a11 AS 'POBLACIÓN DE 6 A 11 ESCOLARIZADA', pob_12a14 AS 'POBLACIÓN DE 12 A 14 ESCOLARIZADA', pob_12a14_no_esc AS 'POBLACIÓN DE 12 A 14 NO ESCOLARIZADA', pob_15mas_an AS 'POBLACIÓN DE 15 O MÁS AÑOS ANALFABETA', pob_15mas_sin_esc AS 'POBLACIÓN DE 15 O MÁS AÑOS SIN ESCOLARIDAD', pob_15mas_esc_incompl AS 'POBLACIÓN DE 15 O MÁS AÑOS CON ESCOLARIDAD INCOMPLETA', pob_15mas_esc_compl AS 'POBLACIÓN DE 15 O MÁS AÑOS CON ESCOLARIDAD COMPLETA'";
			case "hogares_censales":
				return $coordinates . "cvegeo AS 'CVEGEO' , hogar_total AS 'TOTAL DE HOGARES' , hogar_jefatura_fem AS 'HOGARES CON JEFATURA FEMENINA' , hogar_jefatura_masc AS 'HOGARES CON JEFATURA MASCULINA'";
			case "poblacion_con_discapacidad":
				return $coordinates . "cvegeo AS 'CVEGEO', pob_discapacidad AS 'POBLACIÓN TOTAL CON DISCAPACIDAD', pob_0a14 AS 'POBLACIÓN DE 0 A 14 CON DISCAPACIDA', pob_15a59 AS 'POBLACIÓN DE 15 A 59 CON DISCAPACIDAD', pob_60mas AS 'POBLACIÓN DE 60 O MÁS CON DISCAPACIDAD'" ;
			case "poblacion_total":
				return $coordinates . "cvegeo AS 'CVEGEO', pob_3a5 AS 'POBLACIÓN DE 3 A 5', pob_6a11 AS 'POBLACIÓN DE 6 A 11', pob_12a14 AS 'POBLACIÓN DE 10 A 14', pob_0a14 AS 'POBLACIÓN DE 0 A 14', pob_15a29 AS 'POBLACIÓN DE 15 A 29', pob_15a64 AS 'POBLACIÓN DE 15 A 64', pob_18mas AS 'POBLACIÓN DE 18 O MÁS', pob_65mas AS 'POBLACIÓN DE 65 O MÁS', pob_70mas AS 'POBLACIÓN DE 70 O MÁS', pob_fem AS 'POBLACIÓN FEMENINA', pob_fem_15a49 AS 'POBLACIÓN DE 15 A 49 FEMENINA', pob_masc AS 'POBLACIÓN MASCULINA'";
			case "religion":
				return $coordinates . "cvegeo AS 'CVEGEO', catolica AS 'POBLACIÓN CATÓLICA', protes_evan_biblicas AS 'PROTESTANTES, EVANGÉLICAS Y BÍBLICAS DIFERENTES DE EVANGÉLICAS', otra_religion AS 'POBLACIÓN CON OTRAS RELIGIONES', sin_religion AS 'POBLACIÓN SIN RELIGIÓN'" ;
			case "servicios_de_salud":
				return $coordinates . "cvegeo AS 'CVEGEO', dhss AS 'POBLACIÓN DERECHOHABIENTE A SERVICIOS DE SALUD', dhsss AS 'POBLACIÓN DERECHOHABIENTE DEL IMSS', dh_imss AS 'POBLACIÓN DERECHOHABIENTE DEL ISSSTE', dh_issste AS 'POBLACIÓN DERECHOHABIENTE DEL ISSSTE ESTATAL', dh_sp_smng AS 'POBLACIÓN DERECHOHABIENTE POR EL SEGURO POPULAR'";
			case "situacion_conyugal":
				return $coordinates . "cvegeo AS 'CVEGEO', pob_soltera_nunca_unida AS 'POBLACIÓN SOLTERA O NUNCA UNIDA', pob_casada_unida AS 'POBLACIÓN CASADA O UNIDA', pob_estuvo_casada_unida AS 'POBLACIÓN QUE ESTUVO CASADA O UNIDA'" ;
			case "viviendas":
				return $coordinates . "cvegeo AS 'CVEGEO', total_viviendas AS 'TOTAL DE VIVIENDAS', total_viv_habitadas AS 'TOTAL DE VIVIENDAS HABITADAS', total_viv_particulares AS 'TOTAL DE VIVIENDAS PARTICULARES', ocupantes_viv_particulares AS 'OCUPANTES EN VIVIENDAS PARTICULARES HABITADAS', viv_piso_tierra AS 'VIVIENDAS PARTICULARES HABITADAS CON PISO DE TIERRA', viv_1_dormitorio AS 'VIVIENDAS PARTICULARES HABITADAS CON UN DORMITORIO', viv_2_dormitorio_mas AS 'VIVIENDAS PARTICULARES HABITADAS CON DOS DORMITORIOS Y MÁS', viv_1_cuarto AS 'VIVIENDAS PARTICULARES HABITADAS CON UN SOLO CUARTO', viv_sin_luz AS 'VIVIENDAS PARTICULARES HABITADAS QUE NO DISPONEN DE LUZ ELÉCTRICA'" ;
			
			case "atlas_de_riesgo":
				return $coordinates . "cauce_agua AS 'CAUCE DE AGUA' ,detalles AS 'DETALLES' , fenomeno AS 'FENÓMENO'  , r_p_v_e_a AS 'RPVEA' , fuente AS 'FUENTE', metodologia AS 'METODOLOGÍA', tipo AS 'TIPO', fen_clasi AS 'CLASIFICACIÓN DE FENÓMENO', perio_ret AS 'PERIODO DE RETENCIÓN'";
			case "refugios_temporales":
				return $coordinates . "nombre AS 'NOMBRE', propietario AS 'PROPIETARIO', area AS 'ÁREA EN M2', construccion AS 'CONSTRUCCIÓN', capacidad AS 'CAPACIDAD'," . $colonia .", referencia AS 'REFERENCIA'";
			case "sismo_2003":
				return $coordinates .  $colonia .", calle AS 'CALLE', num_oficial AS 'NO.', area AS 'ÁREA EN M2', folio AS 'FOLIO', " . $clasificacion;
			/* ALIAS DE VALORES PARA CAPAS DE LA CARPETA: URBANO */
			case "antenas_de_telecomunicacion":
				return $coordinates . "clave_catastral AS 'CLAVE CATASTRAL', obra AS 'ANTENA', tipo_de_obra AS 'TIPO DE OBRA', fecha_ini AS 'FECHA' , " . $colonia . " , referencia AS 'REFERENCIA', num_oficial AS 'NO.'";
			case "camellones":
				return $coordinates . "calle AS 'CALLE', area AS 'ÁREA EN M2'";
			case "glorietas":
				return $coordinates . "nombre AS 'NOMBRE', monumento AS 'MONUMENTO', equipo AS 'EQUIPO' , area AS 'ÁREA EN M2' , referencia AS 'REFERENCIA'";
			case "licencias_de_construccion":
				return $coordinates_;
			case "numeros_oficiales":
				return $coordinates . "num_oficial AS 'NO.', " . $colonia;
			case "vialidades":
				return $coordinates .  $material . ", calle AS 'CALLE'" ;
			
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
		//cambio modal
		 $manualQuery = "SELECT " . $select . " FROM " . $from . " WHERE " . $where;
		//$manualQuery = "SELECT * FROM " . $from . " WHERE " . $where;
		
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

	public function getMapData()
	{
		$tableData = json_decode($_POST['tableData']);		// 3-column table queries (layer, column and value)
		$pointsArray = json_decode($_POST['pointsArray']);	// Polygon coordinates in the map
		$booleanOp = $_POST['booleanOp'];					// OR or AND operator to join WHERE clauses
		$unwanted_array = array('Á'=>'a','É'=>'e','Í'=>'i','Ó'=>'o','Ú'=>'u',' '=>'_');
		
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
			$table = '';
			$table = $arrLayers[$i];
			$table = strtolower(strtr( $table, $unwanted_array));
			$array = [
				"CAPA" => $table
			];
			if($table=="giros_comerciales"){
				$baseTable = $this->switchTable("marcador", $table);
				$select = "BT.coord_y AS 'LATITUD', BT.coord_x AS 'LONGITUD', nombre_comercial AS 'NOMBRE', clave_catastral AS 'CLAVE CATASTRAL',  localidad AS 'LOCALIDAD', colonia AS 'COLONIA' ";
			}else{
				$select = $this->switchColumnSelectedMarker($table);
			}
			$from = $this->switchTableSelectedMarker($table);
			//$from = str_replace("`","",$from);
			//$select = "SELECT " . $select;
			//$from = $this->switchTable('capa', $arrLayers[$i]) . " AS BT";
			//print_r($select.$from);
			$where = "ST_INTERSECTS(ST_GeomFromText('Polygon((" . $this->pointsArrayToString($pointsArray) . "))'), ST_GeomFromText( CONCAT('POINT(', CONVERT(BT.coord_x, CHAR(20)), ' ', CONVERT(BT.coord_y, CHAR(20)), ')') )) AND (?";
		
			
			for($j = 0; $j < count($jaggedArrayByLayer[$i]); $j++) {
				$dtRow = $jaggedArrayByLayer[$i][$j];
				$cond = $this->switchColumn($tableData[$dtRow]->campo, $tableData[$dtRow]->valor);
				//print_r($cond);
			
				if($where[-1] == "?") { // ? symbol is a flag that tells whether a condition has been added
					$where = substr($where, 0, -1);		// Remove ? symbol
					$where = $where . $cond;			// Concatenate first condition
					
				}
				else { // Concatenate remaining conditions with either OR or AND depending on the selected rbtn
					$where = $where . ' ' . $booleanOp . ' ' . $cond;
				}
			}

			$where = $where . ")"; // Close user-added conditions
			//print_r($select.$from.$where);

			//print_r($select.$from.$where);
			$this->db->select($select);
			$this->db->from($from);
			$this->db->where($where);
		
			/* Some layers have columns from another table (not the base table in ctrl_select_capas)
			that is not a catalog and thus require an explicit inner join */
            /*$arrJoinCondition = $this->getJoinCondition($arrLayers[$i]);
			if($arrJoinCondition !== NULL)
				$this->db->join($arrJoinCondition[0], $arrJoinCondition[1]);
				$this->db->where($where);
			*/
			//$stmt = $this->db->get_compiled_select(); // Save generated sql query (testing purposes)
            //array_push($arrQueryBuilder, $stmt);
                            
			$queryResult = $this->db->get()->result_array();
			//$queryResult = 
			//print_r($queryResult);
			$returnData = array_merge($returnData, $queryResult);

		}
		return $returnData;
	}

	
}
?>