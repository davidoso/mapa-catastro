<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Map_c extends CI_Controller {

	// Constructor para cargar el modelo
	public function __construct(){
		parent::__construct();
		$this->load->model('map_m', 'm');
	}

	// Mostrar página inicial/home de los filtros y el mapa
	public function index()
	{
		// Llenar select cbCapas
		// https://stackoverflow.com/questions/23691396/building-a-select-with-optgroup-from-a-sql-query
		$db_capas = $this->m->getCapas();
		$cbCapas = array();
		foreach($db_capas as $key => $value) {
    		$cbCapas[$value['carpeta']][] = array(
        		'capa' => $value['capa']
    		);
		}
		$data['cbCapas'] = $cbCapas;

		// Llenar selects de la capa Bancos
		/*$data['bancos_servicio'] = $this->m->getBancoServicio();

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
		// Llenar select cbCampos (campos a filtrar según capa)
		$campos = $this->m->getCampos();
		echo json_encode($campos);
	}

	public function getValores()
	{
		/* Llenar select cbValores (valores a filtrar según campo de la capa). Sólo aplica para campos con un
		rango de valores preestablecidos, como Material, Cond_Física o Empresa */
		$valores = $this->m->getValores();
		echo json_encode($valores);
	}
}
?>