<?php
session_start();

class Protocolo{
	private $protocolo=array();

	function __construct() {
	}
	function __construct1($datoProtocolo) {
		$this->protocolo=$datoProtocolo;
	}

	public function ObtenerComposicion($ias){
		$iaNombre='';
		$pos=1;
		foreach ($ias as $key=>$item){

			if(array_key_exists("codigo",$item))
				$iaNombre=$iaNombre.' + '.$item['ingrediente_activo'].' '.$item['concentracion'].strtolower($item['codigo']);
			else if(array_key_exists("unidad_medida",$item))
				$iaNombre=$iaNombre.' + '.$item['ingrediente_activo'].' '.$item['concentracion'].strtolower($item['unidad_medida']);
			$pos++;
		}
		if(strlen($iaNombre)>2)
			$iaNombre=substr($iaNombre,3);
		return $iaNombre;
	}

	public function ObtenerCodigoFormulacion($catalogoFormulacion,$clave){
		$sformula='';
		foreach ($catalogoFormulacion as $key=>$item){
			if($clave== $item['id_formulacion']){

				$sformula=$item['sigla'];
				break;
			}
		}
		return $sformula;
	}

	public function ObenerNombresDeChecked($catalogoCodigos,$clavesSeparadasPorComas){
		$s='';
		foreach ($catalogoCodigos as $key=>$item){
			if(substr_count($clavesSeparadasPorComas, $item['codigo']) > 0)
				$s=$s.', '.$item['nombre'];
		}
		if(strlen($s)>2)
			$s=substr($s,2);
		return $s;
	}


}


?>