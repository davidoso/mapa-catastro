<?php
class Map_m extends CI_Model {

	public function getCapas()
	{
		/*$query =
		"SELECT carpeta, capa FROM
		ctrl_select_capas ORDER BY 1, 2";

		$query = $this->db->query($query);*/

		$this->db->select('carpeta, capa');
		$this->db->order_by('carpeta, capa');

		$query = $this->db->get('ctrl_select_capas');
        return $query->result_array();
	}

	public function getCampos()
	{
		$capa = $this->input->get('capa');

		$this->db->select("CF.campo_frontend AS 'campo'");
		$this->db->from('ctrl_select_capas AS SC');
		$this->db->join('ctrl_campos_a_filtrar AS CF','SC.id_capa = CF.id_capa');
		$this->db->where('SC.capa', $capa);
		$this->db->order_by('SC.carpeta, SC.capa');

		$query = $this->db->get();
		return $query->result();
	}

	public function getValores()
	{
		$capa = $this->input->get('capa');
		$campo = $this->input->get('campo');

		//switch($campo) {
			//default:
			$this->db->select("SC.nombre_tabla AS 'nombre_tabla', NC.columna_bd AS 'columna_bd'");
			$this->db->from('ctrl_select_capas AS SC');
			$this->db->join('ctrl_campos_a_filtrar AS CF','SC.id_capa = CF.id_capa');
			$this->db->join('ctrl_nombre_columnas AS NC','CF.campo_frontend = NC.columna_frontend');
			$this->db->where('SC.capa', $capa);
			$this->db->where('CF.campo_frontend', $campo);
		//}
		$query = $this->db->get()->row_array();
		$nombre_tabla = $query['nombre_tabla'];
		$columna_bd = $query['columna_bd'];

		$queryException = $this->valueExceptions($capa, $campo, $nombre_tabla);
		if(!$queryException) {
			$this->db->select('DISTINCT(UPPER(' . $columna_bd . ')) AS valor');
			$this->db->order_by(1);
			$query = $this->db->get($nombre_tabla);

			return $query->result();
		}
		else {
			return $queryException;
		}
	}

	private function valueExceptions($capa, $campo, $nombre_tabla)
	{
		$exception = false;
		switch($capa) {
			case "LUMINARIAS":
				if($campo == "FUENTE") {
					$this->db->select("DISTINCT(UPPER(IFNULL(f_secundaria, 'SIN FUENTE'))) AS valor");
					$exception = true;
				}
				break;
			case "TELÉFONOS PÚBLICOS":
				if($campo == "FUNCIONA") {
					$this->db->select('DISTINCT(UPPER(funciona)) AS valor');
					$this->db->order_by(1);
					$exception = true;
				}
				break;
		}

		if($exception) {
			$query = $this->db->get($nombre_tabla);
			return $query->result();
		}
		else {
			return false;
		}
	}

	/*public function getCampos()
	{
		$query =
		"SELECT SC.capa, CF.campo_frontend AS 'campo' FROM
		ctrl_select_capas SC
		JOIN ctrl_campos_a_filtrar CF ON SC.id_capa = CF.id_capa ORDER BY SC.carpeta, SC.capa";

		$query = $this->db->query($query);
		return $query->result_array();
	}*/

	/*public function getMaterial($dbTable)
	{
		$query =
		"SELECT DISTINCT(C.material) FROM
		dbTable T
		JOIN ct_material C ON T.id_material = C.id_material ORDER BY 1";

		$query = str_replace("dbTable", $dbTable, $query);
		$query = $this->db->query($query);
		return $query->result_array();
	}

	public function getCondFisica($dbTable)
	{
		$query = str_replace("dbTable", $dbTable, $query);
		$query = $this->db->query($query);
        return $query->result_array();
	}

	public function getEmpresa($dbTable)
	{
		$this->db->select('DISTINCT(empresa_responsable)');
		$this->db->order_by(1);
		$query = $this->db->get($dbTable);
		return $query->result_array();
	}

	public function getBancoServicio()
	{
		$this->db->select('DISTINCT(tipo)');
		$this->db->order_by(1);
		$query = $this->db->get('generales_tbl_bancos');
        return $query->result_array();
	}

	public function getTelefonoCampo($campo)
	{
		$this->db->select('DISTINCT(' . $campo . ')');
		$query = $this->db->get('generales_tbl_telefonos_publicos');
		return $query->result_array();
	}

	public function getLuminariaFuente()
	{
		$query =
		"SELECT DISTINCT(IFNULL(f_secundaria, 'SIN FUENTE'))
		FROM generales_tbl_luminarias ORDER BY 1";

		$query = $this->db->query($query);
        return $query->result_array();
	}

	public function getMonumentoCampo($campo)
	{
		$this->db->select('DISTINCT(' . $campo . ')');
		$this->db->order_by(1);
		$query = $this->db->get('inah_tbl_monumentos_historicos');
        return $query->result_array();
	}*/
}
?>