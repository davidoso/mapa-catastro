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
            /* VALORES DE COLUMNAS CON NOMBRES FIJOS, COMUNES O CON MISMO NOMBRE EN EL FRONTEND Y LA BD */
            case "(SIN FILTROS)":
                return "1 = 1";
                break;
            case "NOMBRE":
                return "nombre like '%" . $value . "%'";
                break;

			/* VALORES DE CAMPOS DE CATÁLOGOS. Es necesario que la tabla, id y descripcion sigan la nomenclatura
			base, e.g. cond_fisica: ct_cond_fisica (tabla), id_cond_fisica (id) y cond_fisica (descripcion) */
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
		//$arrQueryBuilder = array(); // Outputs n sql queries after a succesful AJAX call (testing purposes)

		if($booleanOp === "OR") {
			$returnData = array();

			for($i = 0; $i < count($tableData); $i++) {
				// DB table name
				$from = $this->switchTable('capa', $tableData[$i]->capa);
				// One user-added condition for each row in the datatable, despite a layer having +1 queries
				$cond = $this->switchColumn($tableData[$i]->campo, $tableData[$i]->valor);
				// All elements must be inside the drawn polygon area in the map
				$where = "ST_INTERSECTS(ST_GeomFromText('Polygon((" . $this->pointsArrayToString($pointsArray) . "))'), ST_GeomFromText( CONCAT('POINT(', CONVERT(coord_x, CHAR(20)), ' ', CONVERT(coord_y, CHAR(20)), ')') )) AND (" . $cond . ")";

				$this->db->select('count(id) AS totalByRow');
				$this->db->from($from);
				$this->db->where($where);

				//$stmt = $this->db->get_compiled_select(); // Save generated sql query (testing purposes)
				//array_push($arrQueryBuilder, $stmt);
				$queryResult = $this->db->get()->row_array()['totalByRow'];
                array_push($returnData, $queryResult);
			}
		} // if($booleanOp === "OR")
		else {

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
			$this->db->where($where);

			//$stmt = $this->db->get_compiled_select(); // Save generated sql query (testing purposes)
			//array_push($arrQueryBuilder, $stmt);
			$queryResult = $this->db->get()->result_array();
			$returnData = array_merge($returnData, $queryResult);
		}

		return $returnData;
	}
}
?>