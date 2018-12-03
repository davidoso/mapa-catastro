<?php
class Map_m extends CI_Model {

	public function switchTable($whereColumn, $whereValue)
	{
		$this->db->select('nombre_tabla');
		$this->db->from('ctrl_select_capas');
		$this->db->where($whereColumn, $whereValue);
		$query = $this->db->get()->row_array();

		return $query['nombre_tabla'];
	}

	public function getMapTotals()
	{
		$tableData = json_decode($_POST['tableData']);		// 3-column table filters (layer, field and value)
		$pointsArray = json_decode($_POST['pointsArray']);	// Polygon coordinates in the map
		$booleanOp = $_POST['booleanOp'];					// OR or AND operator to join WHERE clauses

		return $tableData;
	}
}
?>