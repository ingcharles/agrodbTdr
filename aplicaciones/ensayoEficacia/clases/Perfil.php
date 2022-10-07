<?php

class Perfil{
	private $perfiles=array();
	private $esOperador=false;
	private $esAnalistaCentral=false;
	private $esAnalistaDistrital=false;
	private $esDirectorTipoA=false;
	private $esDirector=false;
	private $esCoordinador=false;
	private $esSupervisor=false;
	private $esOrganismo=false;


	public function __construct($perfiles) {
		$this->perfiles=$perfiles;
		foreach ($this->perfiles as $key=>$item){
			if($item['codificacion_perfil']=='PFL_REGIST_OPERA')
				$this->esOperador=true;
			if($item['codificacion_perfil']=='PFL_EE_ARIA')
				$this->esAnalistaCentral=true;
			if($item['codificacion_perfil']=='PFL_EE_ADTA')
				$this->esAnalistaDistrital=true;
			if($item['codificacion_perfil']=='PFL_EE_DDTA')
				$this->esDirectorTipoA=true;
			if($item['codificacion_perfil']=='PFL_EE_DRIA')
				$this->esDirector=true;
			if($item['codificacion_perfil']=='PFL_EE_CRIA')
				$this->esCoordinador=true;
			if($item['codificacion_perfil']=='PFL_EE_SE')
				$this->esSupervisor=true;
			if($item['codificacion_perfil']=='PFL_EE_OI')
				$this->esOrganismo=true;

		}
   }

	public function __destruct() {
		$this->perfiles=array();
	}

	public function EsOperador(){
		return $this->esOperador;
	}

	public function EsAnalistaCentral(){
		return $this->esAnalistaCentral;
	}

	public function EsAnalistaDistrital(){
		return $this->esAnalistaDistrital;
	}
	public function EsDirectorTipoA(){
		return $this->esDirectorTipoA;
	}
	public function EsDirector(){
		return $this->esDirector;
	}
	public function EsCoordinador(){
		return $this->esCoordinador;
	}
	public function EsSupervisor(){
		return $this->esSupervisor;
	}
	public function EsOrganismoInspeccion(){
		return $this->esOrganismo;
	}
	

	public function tieneEstePerfil($perfil){
		foreach ($this->perfiles as $key=>$item){
			if($item['codificacion_perfil']==$perfil)
				return true;
		}
		return false;
	}

}