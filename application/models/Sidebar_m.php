<?php
class Sidebar_m extends CI_Model {

	public function getCapas()
	{
		// Alternativa escribiendo query
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
		$this->db->join('ctrl_campos_a_filtrar AS CF', 'SC.id_capa = CF.id_capa');
		$this->db->where('SC.capa', $capa);
		$this->db->group_by('CF.campo_frontend');
		$this->db->order_by('CF.campo_frontend');
		$query = $this->db->get();

		return $query->result();
	}

	public function getValores()
	{
		$capa = $this->input->get('capa');
		
		$campo = $this->input->get('campo');
		//borrar
		$this->db->distinct();
		$this->db->select("SC.nombre_tabla AS 'nombre_tabla', NC.columna_bd AS 'columna_bd'");
		$this->db->from('ctrl_select_capas AS SC');
		$this->db->join('ctrl_campos_a_filtrar AS CF', 'SC.id_capa = CF.id_capa');
		$this->db->join('ctrl_nombre_columnas AS NC', 'CF.campo_frontend = NC.columna_frontend');
		$this->db->where('SC.capa', $capa);
		$this->db->where('CF.campo_frontend', $campo);

		$query = $this->db->get()->row_array();
		$nombre_tabla = $query['nombre_tabla'];
		$columna_bd = $query['columna_bd'];

		switch($campo) {
			case "POBLACIÓN":
			if($capa == "CARACTERÍSTICAS ECONÓMICAS") {
				$result = array(
					array ("valor" => "POBLACIÓN FEMENINA ECONÓMICAMENTE ACTIVA"
					),
					array ("valor" => "POBLACIÓN MASCULINA ECONÓMICAMENTE ACTIVA"
					),
					array ("valor" => "POBLACIÓN NO ECONÓMICAMENTE ACTIVA"
					),
					array ("valor" => "POBLACIÓN DESOCUPADA"
					),
				);
			}
			if($capa == "CARACTERÍSTICAS EDUCATIVAS") {
				$result = array(
					array ("valor" => "POBLACIÓN DE 3 A 5 ESCOLARIZADA"
					),
					array ("valor" => "POBLACIÓN DE 6 A 11 ESCOLARIZADA"
					),
					array ("valor" => "POBLACIÓN DE 12 A 14 ESCOLARIZADA"
					),
					array ("valor" => "POBLACIÓN DE 12 A 14 NO ESCOLARIZADA"
					),
					array ("valor" => "POBLACIÓN DE 15 O MÁS AÑOS ANALFABETA"
					),
					array ("valor" => "POBLACIÓN DE 15 O MÁS AÑOS SIN ESCOLARIDAD"
					),
					array ("valor" => "POBLACIÓN DE 15 O MÁS AÑOS CON ESCOLARIDAD INCOMPLETA"
					),
					array ("valor" => "POBLACIÓN DE 15 O MÁS AÑOS CON ESCOLARIDAD COMPLETA"
					),
				);
			}
			if($capa == "POBLACIÓN CON DISCAPACIDAD") {
				$result = array(
					array ("valor" => "POBLACIÓN TOTAL CON DISCAPACIDAD"
					),
					array ("valor" => "POBLACIÓN DE 0 A 14 CON DISCAPACIDAD"
					),
					array ("valor" => "POBLACIÓN DE 15 A 59 CON DISCAPACIDAD"
					),
					array ("valor" => "POBLACIÓN DE 60 O MÁS  CON DISCAPACIDAD"
					),
				);
			}
			if($capa == "POBLACIÓN TOTAL") {
				$result = array(
					array ("valor" => "POBLACIÓN DE 3 A 5"
					),
					array ("valor" => "POBLACIÓN DE 6 A 11"
					),
					array ("valor" => "POBLACIÓN DE 10 A 14"
					),
					array ("valor" => "POBLACIÓN DE 0 A 14"
					),
					array ("valor" => "POBLACIÓN DE 15 A 29"
					),
					array ("valor" => "POBLACIÓN DE 15 A 64"
					),
					array ("valor" => "POBLACIÓN DE 18 O MÁS"
					),
					array ("valor" => "POBLACIÓN DE 65 O MÁS"
					),
					array ("valor" => "POBLACIÓN DE 70 O MÁS"
					),
					array ("valor" => "POBLACIÓN FEMENINA"
					),
					array ("valor" => "POBLACIÓN DE 15 A 49 FEMENINA"
					),
					array ("valor" => "POBLACIÓN MASCULINA"
					),
				);
			}
			if($capa == "SITUACIÓN CONYUGAL") {
				$result = array(
					array ("valor" => "POBLACIÓN SOLTERA O NUNCA UNIDA"
					),
					array ("valor" => "POBLACIÓN CASADA O UNIDA"
					),
					array ("valor" => "POBLACIÓN QUE ESTUVO CASADA O UNIDA"
					),
				);
			}
			return ($result);
			
			case "COLONIA":
				$this->db->select("DISTINCT(UPPER(C.colonia)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_colonia AS C', 'T.id_colonia = C.id_colonia');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();
			
			case "COLOR":
				$this->db->select("DISTINCT(UPPER(C.color)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_color AS C', 'T.id_color = C.id_color');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();

			case "MATERIAL":
				$this->db->select("DISTINCT(UPPER(C.material)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_material AS C', 'T.id_material = C.id_material');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();

			case "CONDICIÓN FÍSICA":
				$this->db->select("DISTINCT(UPPER(C.cond_fisica)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_cond_fisica AS C', 'T.id_cond_fisica = C.id_cond_fisica');
				$query = $this->db->get();
				return $query->result();

			case "CONDICIÓN FÍSICA ELEMENTO":
				$this->db->select("DISTINCT(UPPER(C.cond_fisica)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_cond_fisica AS C', 'T.id_cond_fisica_ele = C.id_cond_fisica');
				$query = $this->db->get();
				return $query->result();
			
			case "CONDICIÓN FÍSICA ESTRUCTURA":
				$this->db->select("DISTINCT(UPPER(C.cond_fisica)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_cond_fisica AS C', 'T.id_cond_fisica_estructura = C.id_cond_fisica');
				$query = $this->db->get();
				return $query->result();

			case "GIRO COMERCIAL":
				$this->db->select("DISTINCT(UPPER(L.giro_comercial)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('comercio_tbl_giros_comerciales_licencias AS L', 'T.id = L.id_giro_comercial');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();

			case "NOMBRE COMERCIAL":
				$this->db->select("DISTINCT(UPPER(L.nombre_comercial)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('comercio_tbl_giros_comerciales_licencias AS L', 'T.id = L.id_giro_comercial');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();

			case "MERCADO":
				$this->db->select("DISTINCT(UPPER(L.nombre)) AS 'valor'");
				$this->db->from('comercio_tbl_locatarios_mercados AS T');
				$this->db->join('comercio_tbl_mercados AS L', 'T.id_mercado = L.id');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();

			case "TIANGUIS":
				$this->db->select("DISTINCT(UPPER(L.nombre)) AS 'valor'");
				$this->db->from('comercio_tbl_tianguistas AS T');
				$this->db->join('comercio_tbl_tianguis AS L', 'T.id_tianguis = L.id');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();
			
			case "NIVEL ESCOLAR":
				$this->db->select("DISTINCT(UPPER(C.nivel_escolar)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_nivel_escolar AS C', 'T.id_nivel_escolar = C.id_nivel_escolar');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();	
				
			case "JURISDICCIÓN":
				$this->db->select("DISTINCT(UPPER(C.jurisdiccion)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_jurisdiccion AS C', 'T.id_jurisdiccion = C.id_jurisdiccion');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();
				
			case "TIPO ":
				$this->db->select("DISTINCT(UPPER(C.tipo_escultura)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_tipo_escultura AS C', 'T.id_tipo_escultura = C.id_tipo_escultura');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();

			case "TIPO   ":
				$this->db->select("DISTINCT(UPPER(C.tipologia)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_tipologia AS C', 'T.id_tipologia = C.id_tipologia');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();

			case "TIPO DE RIEGO":
				$this->db->select("DISTINCT(UPPER(C.tipo_riego)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_tipo_riego AS C', 'T.id_tipo_riego = C.id_tipo_riego');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();
			
			case "TIPO DE HOSPITAL":
				$this->db->select("DISTINCT(UPPER(C.tipo_hospital)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_tipo_hospital AS C', 'T.id_tipo_hospital = C.id_tipo_hospital');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();

			case "TIPO DE MATERIAL":
				$this->db->select("DISTINCT(UPPER(C.material)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_material AS C', 'T.id_material = C.id_material');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();

			case "TIPO DE COLUMNA":
				$this->db->select("DISTINCT(UPPER(C.tipo_co)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_tipo_co AS C', 'T.id_tipo_co = C.id_tipo_co');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();

			case "TIPO DE ESTRUCTURA":
				$this->db->select("DISTINCT(UPPER(C.tipo_estructura)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_tipo_estructura AS C', 'T.id_tipo_estructura = C.id_tipo_estructura');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();

			case "TIPO DE LUZ":
				$this->db->select("DISTINCT(UPPER(C.tipo_luz)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_tipo_luz AS C', 'T.id_tipo_luz = C.id_tipo_luz');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();

			case "TIPO DE SEMÁFORO":
				$this->db->select("DISTINCT(UPPER(C.tipo_semaforo)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_tipo_semaforo AS C', 'T.id_tipo_semaforo = C.id_tipo_semaforo');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();
			
			case "DEPENDENCIA":
				$this->db->select("DISTINCT(UPPER(C.dependencia)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_dependencia AS C', 'T.id_dependencia = C.id_dependencia');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();

			case "ESPACIO DEPORTIVO":
				$this->db->select("DISTINCT(UPPER(C.espacio_deportivo)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_espacio_deportivo AS C', 'T.id_espacio_deportivo = C.id_espacio_deportivo');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();

			case "CLASIFICACIÓN":
				$this->db->select("DISTINCT(UPPER(C.clasificacion)) AS 'valor'");
				$this->db->from($nombre_tabla . ' AS T');
				$this->db->join('ct_clasificacion AS C', 'T.id_clasificacion = C.id_clasificacion');
				$this->db->order_by(1);
				$query = $this->db->get();
				return $query->result();			
				
			case "INCIDENCIAS":
				$result = array(
					array ("valor" => "AFECTADOS"
					),
					array ("valor" => "FALLECIDOS"
					),
					array ("valor" => "LESIONADOS"
					),
				);
				return ($result);

			case "HOGAR JEFATURA":
				$result = array(
					array ("valor" => "TOTAL DE HOGARES"
					),
					array ("valor" => "HOGARES CON JEFATURA FEMENINA"
					),
					array ("valor" => "HOGARES CON JEFATURA MASCULINA"
					),
				);
				return ($result);

			case "RELIGIÓN":
				$result = array(
					array ("valor" => "POBLACIÓN CATÓLICA"
					),
					array ("valor" => "PROTESTANTES, EVANGÉLICAS Y BÍBLICAS DIFERENTES DE EVANGÉLICAS"
					),
					array ("valor" => "POBLACIÓN CON OTRAS RELIGIONES"
					),
					array ("valor" => "POBLACIÓN SIN RELIGIÓN"
					),
				);
				return ($result);

			case "SERVICIOS MÉDICOS":
				$result = array(
					array ("valor" => "POBLACIÓN DERECHOHABIENTE A SERVICIOS DE SALUD"
					),
					array ("valor" => "POBLACIÓN DERECHOHABIENTE DEL IMSS"
					),
					array ("valor" => "POBLACIÓN DERECHOHABIENTE DEL ISSSTE"
					),
					array ("valor" => "POBLACIÓN DERECHOHABIENTE DEL ISSSTE ESTATAL"
					),
					array ("valor" => "POBLACIÓN DERECHOHABIENTE POR EL SEGURO POPULAR"
					),
				);
				return ($result);

				case "VIVIENDAS":
					$result = array(
						array ("valor" => "OCUPANTES EN VIVIENDAS PARTICULARES HABITADAS"
						),
						array ("valor" => "VIVIENDAS PARTICULARES HABITADAS CON PISO DE TIERRA"
						),
						array ("valor" => "VIVIENDAS PARTICULARES HABITADAS CON UN DORMITORIO"
						),
						array ("valor" => "VIVIENDAS PARTICULARES HABITADAS CON DOS DORMITORIOS Y MÁS"
						),
						array ("valor" => "VIVIENDAS PARTICULARES HABITADAS CON UN SOLO CUARTO"
						),
						array ("valor" => "VIVIENDAS PARTICULARES HABITADAS QUE NO DISPONEN DE LUZ ELÉCTRICA"
						),
					);
				return ($result);

			case "TODOS":	
				$result = array(
					array ("valor" => "TODOS"
					)
				);
				return ($result);

			default:
				$queryException = $this->valueExceptions($capa, $campo, $nombre_tabla);
				if(!$queryException) {
					$this->db->select('DISTINCT(UPPER(' . $columna_bd . ')) AS valor');
					//$this->db->where($columna_bd.' is not null');
					$this->db->order_by(1);
					$query = $this->db->get($nombre_tabla);

					return $query->result();
				}
				else {
					return $queryException;
				}
		}
	}

	private function valueExceptions($capa, $campo, $nombre_tabla)
	{
		$exception = false;
		switch($capa) {
			/*case "LUMINARIAS":
				if($campo == "FUENTE") {
					$this->db->select("DISTINCT(UPPER(IFNULL(f_secundaria, 'SIN FUENTE'))) AS valor");
					$this->db->order_by(1);
					$exception = true;
				}
				break;*/
			case "TELÉFONOS PÚBLICOS":
				if($campo == "FUNCIONA") {
					$this->db->select('DISTINCT(UPPER(funciona)) AS valor');
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

	/* VERSIÓN BETA: FUNCIONES INNECESARIAS DESPÚES DE MEJORAR EL CÓDIGO CON LLAMADAS AJAX
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