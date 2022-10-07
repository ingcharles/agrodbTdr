<?php

class Flujo{
	private $tipo_documento;
	private $id_flujo_documento;
	private $selector;
	private $selector_valor='';
	private $condicion='';
	private $id_flujo;
	private $id_fase=-1;
	private $id_fase_siguiente=-1;
	private $estado="";
	private $perfil_actual='';
	private $perfil_siguiente='';
	private $plazo=0;
	private $plazo_n=0;

	private $plazo_condicion='';
	private $plazo_a=0;

	private $flujos=array();

	function __construct()
	{
		$a = func_get_args();
		$i = func_num_args();
		if (method_exists($this,$f='__construct'.$i)) {
			call_user_func_array(array($this,$f),$a);
		}
	}

	public function __construct4($flujosOperadores,$tipo_documento,$selector,$id_flujo) {
		$this->flujos=$flujosOperadores;
		$this->tipo_documento=$tipo_documento;
		$this->selector=$selector;
		$this->id_flujo=$id_flujo;
	}

	public function __construct2($flujosOperadores,$id_flujo_documento) {
		$this->flujos=$flujosOperadores;
		$this->id_flujo_documento=$id_flujo_documento;
		$flujo=array();
		foreach($this->flujos as $key=>$valor){
			if($valor['id_flujo_documento']==$id_flujo_documento)
			{
				$flujo=$valor;
			}
		}
		if($flujo!=null){
			$this->tipo_documento=$flujo['tipo_documento'];
			$this->selector=$flujo['selector'];
			$this->id_flujo=$flujo['id_flujo'];
			$this->id_fase=$flujo['id_fase'];
			$this->condicion=$flujo['condicion'];
			$this->selector_valor=$flujo['selector_valor'];
			$this->id_fase_siguiente=$flujo['id_fase_siguiente'];
			$this->estado=$flujo['estado'];
			$this->perfil_actual=$flujo['perfil'];
			$this->perfil_siguiente=$flujo['perfil_siguiente'];
			$this->plazo=$flujo['plazo'];
			$this->plazo_n=$flujo['plazo_n'];
			$this->plazo_a=$flujo['plazo_a'];

			$this->plazo_condicion=$flujo['plazo_condicion'];
		}
   }

	public function __destruct() {
		$this->id_fase=-1;
		$this->flujos=array();
	}


	public function InicializarFlujo($selector_valor,$condicion='',$id_fase=1){
		if($this->flujos==null || sizeof($this->flujos)==0)
			return null;
		$this->condicion=$condicion;
		$this->selector_valor=$selector_valor;
		$this->id_fase=$id_fase;
		foreach($this->flujos as $key=>$value){
			$cond=$value['condicion'];
			if($cond==null)
				$cond='';
			if($this->tipo_documento==$value['tipo_documento'] && $this->selector==$value['selector'] && $this->id_flujo==$value['id_flujo'] && $value['selector_valor']==$selector_valor  && $condicion==$cond && $id_fase==$value['id_fase']){
				$this->estado=$value['estado'];
				$this->id_flujo_documento=$value['id_flujo_documento'];
				$this->id_fase_siguiente=$value['id_fase_siguiente'];
				$this->perfil_siguiente=$value['perfil_siguiente'];
				$this->perfil_actual=$value['perfil'];
				$this->plazo=$value['plazo'];
				$this->plazo_n=$value['plazo_n'];
				$this->plazo_a=$value['plazo_a'];

				$this->plazo_condicion=$value['plazo_condicion'];
			}
		}
		return $this;
	}

	public function BuscarFaseSiguiente($condicion=null){
		if($this->flujos==null || sizeof($this->flujos)==0)
			return null;
		foreach($this->flujos as $key=>$value){
			//Encuentra la fase siguiene

			if($this->tipo_documento==$value['tipo_documento'] && $this->selector==$value['selector'] && $this->id_flujo==$value['id_flujo'] && $value['selector_valor']==$this->selector_valor && $condicion==$value['condicion'] && $this->id_fase_siguiente==$value['id_fase']){
				$flujoNuevo=new Flujo($this->flujos,$this->tipo_documento,$this->selector,$this->id_flujo);
				$flujoNuevo->id_fase=$value['id_fase'];
				$flujoNuevo->id_fase_siguiente=$value['id_fase_siguiente'];
				$flujoNuevo->estado=$value['estado'];
				$flujoNuevo->id_flujo_documento=$value['id_flujo_documento'];
				$flujoNuevo->condicion=$value['condicion'];
				$flujoNuevo->selector_valor=$this->selector_valor;//$value['selector_valor'];
				$flujoNuevo->perfil_siguiente=$value['perfil_siguiente'];
				$flujoNuevo->perfil_actual=$value['perfil'];
				$flujoNuevo->plazo=$value['plazo'];
				$flujoNuevo->plazo_n=$value['plazo_n'];
				$flujoNuevo->plazo_a=$value['plazo_a'];

				$flujoNuevo->plazo_condicion=$value['plazo_condicion'];
				return $flujoNuevo;
			}
		}
		return null;
	}

	public function BuscarFaseSiguienteConCondicion($condicion,$condicionSiguiente,$tipoDocumento=null,$nuevoSelectorValor=null){
		if($this->flujos==null || sizeof($this->flujos)==0)
			return null;
		foreach($this->flujos as $key=>$value){
			//Encuentra la fase real según la condicion
			if($this->tipo_documento==$value['tipo_documento'] && $this->selector==$value['selector'] && $this->id_flujo==$value['id_flujo'] && $this->selector_valor==$value['selector_valor'] && $condicion==$value['condicion'] && $this->id_fase==$value['id_fase'] ){
				
				$this->id_fase=$value['id_fase'];
				$this->id_fase_siguiente=$value['id_fase_siguiente'];
				$this->estado=$value['estado'];
				$this->id_flujo_documento=$value['id_flujo_documento'];
				$this->condicion=$value['condicion'];
				
				$this->perfil_siguiente=$value['perfil_siguiente'];
				$this->perfil_actual=$value['perfil'];
				$this->plazo=$value['plazo'];
				$this->plazo_n=$value['plazo_n'];
				$this->plazo_a=$value['plazo_a'];

				$this->plazo_condicion=$value['plazo_condicion'];
				break;
			}
		}
		if($tipoDocumento!==null)
			$this->tipo_documento=$tipoDocumento;
		if(!is_null($nuevoSelectorValor))
			$this->selector_valor=$nuevoSelectorValor;
		return $this->BuscarFaseSiguiente($condicionSiguiente);
	}



	public function FaseActual(){
		return $this->id_fase;
	}

	public function FaseSiguiente(){
		return $this->id_fase_siguiente;
	}

	public function EstadoActual(){
		return $this->estado;
	}

	public function Flujo_documento(){
		return $this->id_flujo_documento;
	}

	public function TipoDocumento(){
		return $this->tipo_documento;
	}

	public function PerfilSiguiente(){
		return $this->perfil_siguiente;
	}

	public function PerfilActual(){
		return $this->perfil_actual;
	}

	public function Plazo(){
		return $this->plazo;
	}
	public function PlazoExtendido(){
		return $this->plazo_n;
	}

	public function PlazoAlterno(){
		return $this->plazo_a;
	}

	public function CambiarSelector($nuevoSelector){
		$this->selector=$nuevoSelector;
	}
	
	public function CambiarSelectorValor($nuevoValor){
		$this->selector_valor=$nuevoValor;
	}

	public function Condicion(){
		return $this->condicion;
	}

	public function getSelector(){
		return $this->selector;
	}

	public function getPlazoCondicion(){
		return $this->plazo_condicion;
	}

}