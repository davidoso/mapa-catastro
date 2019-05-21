<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class App_c extends CI_Controller {

	// Constructor para cargar el modelo principal
	public function __construct()
	{
		parent::__construct();
		$this->load->model('sidebar_m', 'sm');
	}

	// Mostrar página inicial/home de las consultas dinámicas y el mapa
	public function index()
	{
		// Llenar select #cbCapas
		// https://stackoverflow.com/questions/23691396/building-a-select-with-optgroup-from-a-sql-query
		$db_capas = $this->sm->getCapas();
		$cbCapas = array();
		foreach($db_capas as $key => $value) {
    		$cbCapas[$value['carpeta']][] = array(
        		'capa' => $value['capa']
    		);
		}
		$data['cbCapas'] = $cbCapas;

		/* VERSIÓN BETA: FUNCIONES INNECESARIAS DESPÚES DE MEJORAR EL CÓDIGO CON LLAMADAS AJAX
		getCampos() y getValores() reemplazan las funciones JavaScript switchselect1() y switchSelect2()
		encontradas en filter.php de la carpeta views/beta
		// Llenar selects de la capa Bancos
		$data['bancos_servicio'] = $this->m->getBancoServicio();

		// Llenar selects de la capa Postes
		$data['postes_empresa'] = $this->m->getEmpresa("generales_tbl_postes");
		$data['postes_material'] = $this->m->getMaterial("generales_tbl_postes");
		$data['postes_cond_fisica'] = $this->m->getCondFisica("generales_tbl_postes");

		// Llenar selects de la capa Teléfonos
		$data['telefonos_tipo'] = $this->m->getTelefonoCampo("tipo");
		$data['telefonos_funciona'] = $this->m->getTelefonoCampo("funciona");
		$data['telefonos_empresa'] = $this->m->getEmpresa("generales_tbl_telefonos_publicos");
		$data['telefonos_cond_fisica'] = $this->m->getCondFisica("generales_tbl_telefonos_publicos");

		// Llenar selects de la capa Luminarias
		$data['luminarias_fuente'] = $this->m->getLuminariaFuente("generales_tbl_luminarias");
		$data['luminarias_material'] = $this->m->getMaterial("generales_tbl_luminarias");
		$data['luminarias_cond_fisica'] = $this->m->getCondFisica("generales_tbl_luminarias");

		// Llenar selects de la capa Monumentos
		$data['monumentos_epoca'] = $this->m->getMonumentoCampo("epoca");
		$data['monumentos_genero_arquitectonico'] = $this->m->getMonumentoCampo("genero_arquitectonico");

		// Llenar selects de la capa Panteón
		$data['panteon_material'] = $this->m->getMaterial("registro_civil_tbl_panteon_municipal");
		$data['panteon_cond_fisica'] = $this->m->getCondFisica("registro_civil_tbl_panteon_municipal");*/


		// Cargar página inicial/home de los filtros y el mapa
		$this->load->view('filter', $data);
	}

	public function getCampos()
	{
		// Llenar select #cbCampos (campos a filtrar según capa)
		$campos = $this->sm->getCampos();
		echo json_encode($campos);
	}

	public function getValores()
	{
		/* Llenar select #cbValores (valores a filtrar según campo de la capa). Sólo aplica para campos con un
		rango de valores preestablecidos, como material, cond_fisica o empresa */
		$valores = $this->sm->getValores();
		echo json_encode($valores);
	}

	public function getMapTotals()
	{
		$this->load->model('map_m', 'm');
		$totals = $this->m->getMapTotals();
		echo json_encode($totals);
	}

	public function getMapMarkers()
	{
		$this->load->model('map_m', 'm');
		$markers = $this->m->getMapMarkers();
		echo json_encode($markers);
	}

	public function getMapSelectedMarker()
	{
		$this->load->model('map_m', 'm');
		$selectedMarker = $this->m->getMapSelectedMarker();
		echo json_encode($selectedMarker);
	}
	public function getMapMarkersData()
	{
		$this->load->model('map_m', 'm');
		$table = $this->m->getMapData();
		echo json_encode($table);
	}
}
?>