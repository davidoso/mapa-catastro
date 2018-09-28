<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mapa_controller extends CI_Controller {

	// Mostrar página inicial/home de los filtros y el mapa
	public function index()
	{
		$this->load->model('mapa_model');

		// Llenar select cbCapas
		// https://stackoverflow.com/questions/23691396/building-a-select-with-optgroup-from-a-sql-query
		$db_capas = $this->mapa_model->getCapas();
		$cbCapas = array();
		foreach($db_capas as $key => $value) {
    		$cbCapas[$value['carpeta']][] = array(
        		'capa' => $value['capa']
    		);
		}
		$data['cbCapas'] = $cbCapas;

		// Llenar select cbCampos (campos a filtrar según capa)
		$db_campos_ = $this->mapa_model->getCampos();
		$cbCampos = array();
		foreach($db_campos_ as $key => $value) {
    		$cbCampos[$value['capa']][] = array(
        		'campo' => $value['campo']
    		);
		}
		$data['cbCampos'] = $cbCampos;

		// Llenar selects de la capa Bancos
		$data['bancos_servicio'] = $this->mapa_model->getBancoServicio();

		// Llenar selects de la capa Postes
		$data['postes_empresa'] = $this->mapa_model->getEmpresa("generales_tbl_postes");
		$data['postes_material'] = $this->mapa_model->getMaterial("generales_tbl_postes");
		$data['postes_cond_fisica'] = $this->mapa_model->getCondFisica("generales_tbl_postes");

		// Llenar selects de la capa Teléfonos
		$data['telefonos_tipo'] = $this->mapa_model->getTelefonoCampo("tipo");
		$data['telefonos_funciona'] = $this->mapa_model->getTelefonoCampo("funciona");
		$data['telefonos_empresa'] = $this->mapa_model->getEmpresa("generales_tbl_telefonos_publicos");
		$data['telefonos_cond_fisica'] = $this->mapa_model->getCondFisica("generales_tbl_telefonos_publicos");

		// Llenar selects de la capa Luminarias
		$data['luminarias_fuente'] = $this->mapa_model->getLuminariaFuente("generales_tbl_luminarias");
		$data['luminarias_material'] = $this->mapa_model->getMaterial("generales_tbl_luminarias");
		$data['luminarias_cond_fisica'] = $this->mapa_model->getCondFisica("generales_tbl_luminarias");

		// Llenar selects de la capa Monumentos
		$data['monumentos_epoca'] = $this->mapa_model->getMonumentoCampo("epoca");
		$data['monumentos_genero_arquitectonico'] = $this->mapa_model->getMonumentoCampo("genero_arquitectonico");

		// Llenar selects de la capa Panteón
		$data['panteon_material'] = $this->mapa_model->getMaterial("registro_civil_tbl_panteon_municipal");
		$data['panteon_cond_fisica'] = $this->mapa_model->getCondFisica("registro_civil_tbl_panteon_municipal");


		// Cargar página inicial/home de los filtros y el mapa
		$this->load->view('filter', $data);
	}
}
?>