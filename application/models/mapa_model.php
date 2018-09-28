<?php
class Mapa_model extends CI_Model {

	public function getCapas()
	{
		$query =
		"SELECT carpeta, capa FROM
		ctrl_select_capas ORDER BY 1, 2";

		$query = $this->db->query($query);
        return $query->result_array();
	}

	public function getCampos()
	{
		$query =
		"SELECT SC.capa, CF.campo_frontend AS 'campo' FROM
		ctrl_select_capas SC
		JOIN ctrl_campos_a_filtrar CF ON SC.id_capa = CF.id_capa ORDER BY SC.carpeta, SC.capa";

		$query = $this->db->query($query);
        return $query->result_array();
	}

	public function getMaterial($dbTable)
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
		$query =
		"SELECT DISTINCT(C.cond_fisica) FROM
		dbTable T
		JOIN ct_cond_fisica C ON T.id_cond_fisica = C.id_cond_fisica";

		$query = str_replace("dbTable", $dbTable, $query);
		$query = $this->db->query($query);
        return $query->result_array();
	}

	public function getEmpresa($dbTable)
	{
		$query =
		"SELECT DISTINCT(empresa_responsable)
		FROM dbTable ORDER BY 1";

		$query = str_replace("dbTable", $dbTable, $query);
		$query = $this->db->query($query);
        return $query->result_array();
	}

	public function getBancoServicio()
	{
		$query =
		"SELECT DISTINCT(tipo)
		FROM generales_tbl_bancos ORDER BY 1";

		$query = $this->db->query($query);
        return $query->result_array();
	}

	public function getTelefonoCampo($campo)
	{
		$query =
		"SELECT DISTINCT(campo)
		FROM generales_tbl_telefonos_publicos";

		$query = str_replace("campo", $campo, $query);
		$query = $this->db->query($query);
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
		$query =
		"SELECT DISTINCT(campo)
		FROM inah_tbl_monumentos_historicos ORDER BY 1";

		$query = str_replace("campo", $campo, $query);
		$query = $this->db->query($query);
        return $query->result_array();
	}
}
?>