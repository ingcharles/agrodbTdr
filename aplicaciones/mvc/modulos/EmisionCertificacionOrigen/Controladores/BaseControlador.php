<?php

 /**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores 
 *
 * @property  AGROCALIDAD
 * @author    Carlos Anchundia
 * @date      2020-09-18
 * @uses      BaseControlador
 * @package   EmisionCertificacionOrigen
 * @subpackage Controladores
 */
namespace Agrodb\EmisionCertificacionOrigen\Controladores;

session_start();

use Agrodb\Core\Comun;
use Agrodb\EmisionCertificacionOrigen\Modelos\RegistroProduccionLogicaNegocio;
use Agrodb\EmisionCertificacionOrigen\Modelos\EmisionCertificadoLogicaNegocio;
use Agrodb\Catalogos\Modelos\LocalizacionLogicaNegocio;
 
 
class BaseControlador extends Comun
{
	public $itemsFiltrados = array();
	public $codigoJS = null;
	public $panelBusqueda = null;

	/**
	* Constructor
	*/
	function __construct() {
		parent::usuarioActivo();
		//Si se requiere agregar código concatenar la nueva cadena con  ejemplo $this->codigoJS.=alert('hola');
		$this->codigoJS = \Agrodb\Core\Mensajes::limpiar();
	}
	public function crearTabla() {
		$tabla = "//No existen datos para mostrar...";
		if (count($this->itemsFiltrados) > 0) {
			$tabla = '$(document).ready(function() {
			construirPaginacion($("#paginacion"),' . json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE) . ');
			$("#listadoItems").removeClass("comunes");
			});';
		}

	return $tabla;
	}
	public function filtroOperaciones(){
	   
	    $this->panelBusqueda = '<table class="filtro" style="width: 100%;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Buscar producción:</th>
	                                                </tr>
	                                                <tr  style="width: 100%;">
	                            						<td >Fecha inicio:</td>
	                            						<td colspan="3">
	                            							<input id="fechaInicio" type="text" name="fechaInicio" value="" readonly>
	                            						</td>
	        
	                            					</tr>
	                                                <tr  style="width: 100%;">
	                            						<td >Fecha fin: </td>
	                            						<td colspan="3">
	                            							<input id="fechaFin" type="text" name="fechaFin" value="" readonly>
	                            						</td>
	                            					</tr>
                            						<td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
	}
	
	public function filtroEmision(){
	    
	    $this->panelBusqueda = '<table class="filtro" style="width: 100%;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Consultar certificación sanitaria de origen y movilización:</th>
	                                                </tr>
	                                                <tr  style="width: 100%;">
	                            						<td >* Nombre Sitio:</td>
	                            						<td colspan="3">
                                                            <select id="nombreSitio" name="nombreSitio" style="width: 75%;">
                                                                    '.$this->comboSitio().'
                                                            </select>
	                            						</td>
	        
	                            					</tr>
	                                                <tr  style="width: 100%;">
	                            						<td >* N° certificado: </td>
	                            						<td colspan="3">
	                            							<input id="numCertificado" type="text" name="numCertificado" value="" maxlength="15">
	                            						</td>
	                            					</tr>
                                                    <tr  style="width: 100%;">
	                            						<td >* Estado:</td>
	                            						<td colspan="3">
                                                            <select id="estadoEmision" name="estadoEmision" style="width: 75%;">
                                                                    '.$this->comboEstado().'
                                                            </select>
	                            						</td>
	        
	                            					</tr>
                                                    <tr  style="width: 100%;">
	                            						<td >Fecha inicio:</td>
	                            						<td colspan="3">
	                            							<input id="fechaInicio" type="text" name="fechaInicio" value="" readonly>
	                            						</td>
	        
	                            					</tr>
                                                    <tr  style="width: 100%;">
	                            						<td >Fecha fin:</td>
	                            						<td colspan="3">
	                            							<input id="fechaFin" type="text" name="fechaFin" value="" readonly>
	                            						</td>
	        
	                            					</tr>
                            						<td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
	}
	

	public function comboEspecie($especie=null){
	    $lNegocioRegistroProduccion = new RegistroProduccionLogicaNegocio();
	    $arrayParametros = array('identificador_operador'=>$_SESSION['usuario']);
	    $verificar = $lNegocioRegistroProduccion->buscarSitioFaenamiento($arrayParametros);
	    $combo = '<option value="">Seleccionar....</option>';
	    if(is_array($verificar['especie'])){
    	    foreach ($verificar['especie'] as $item) {
    	        if($especie == $item){
    	            $combo .= '<option value="' . $item . '" selected>' . $item. '</option>';
    	        }else{
    	            $combo .= '<option value="' . $item . '" >' . $item. '</option>';
    	        }
    	    }
	    }else{
	        if($verificar['especie'] != ''){
    	        if($especie == $verificar['especie']){
    	            $combo .= '<option value="' . $verificar['especie'] . '" selected>' . $verificar['especie']. '</option>';
    	        }else{
    	            $combo .= '<option value="' . $verificar['especie'] . '" >' . $verificar['especie']. '</option>';
    	        }
	        }
	    }
	    return $combo;
	}
	public function comboSitio($sitio=null){
	    $lNegocioEmisionCertificado = new EmisionCertificadoLogicaNegocio();
	    $arrayParametros = array('identificador_operador' => $_SESSION['usuario'], 'id_area_tipo_operacion' =>'AI', 'codigo' => 'FAE');
	    $verificar = $lNegocioEmisionCertificado->buscarCentroFaenamiento($arrayParametros);
	    $combo = '<option value="">Seleccionar....</option>';
	    foreach ($verificar as $item) {
	        if($sitio == $item['id_sitio']){
	            $combo .= '<option value="' . $item['id_sitio'] . '-'.$item['criterio_funcionamiento'].'-'.$item['provincia'].'-'.$item['id_centro_faenamiento'].'" selected>' . $item['nombre_lugar']. '</option>';
	        }else{
	            $combo .= '<option value="' . $item['id_sitio'] . '-'.$item['criterio_funcionamiento'].'-'.$item['provincia'].'-'.$item['id_centro_faenamiento'].'" >' .  $item['nombre_lugar']. '</option>';
	        }
	        
	    }
	    return $combo;
	}
	
	//$arrayParametros = array('id_sitio' => $idSitio,'id_area_tipo_operacion' =>'AI', 'codigo' => 'FAE');
	public function comboNumeros($maximo,$valor=null){
	    
	    $combo = '<option value="">Seleccionar....</option>';
	    for ($i=1; $i<=$maximo; $i++ ){
	        if($valor == $i){
	            $combo .= '<option value="' . $i . '" selected>' . $i. '</option>';
	        }else{
	            $combo .= '<option value="' . $i . '" >' . $i. '</option>';
	        }
	    }
	    return $combo;
	}
	/******combo canal productos
	 * */
	public function comboNumerosCanal($numero,$idProducto,$item=null){
	    
	    $combo = '<option value="">Seleccionar....</option>';
	    for ($i=1; $i<=$numero; $i++ ){
	        $num = str_pad($i, 3, "0", STR_PAD_LEFT);
	        if($item == $i){
	            $combo .= '<option value="'.$idProducto.'-' . $num . '" selected>' . $num. '</option>';
	        }else{
	            $combo .= '<option value="'.$idProducto.'-' . $num . '" >' . $num. '</option>';
	        }
	    }
	    return $combo;
	}
	/**
	 * Combo de opcion si o no
	 */
	public function comboOpcion($opcion = null)
	{
	    $array = array("Si","No");
	    $combo = '<option value="">Seleccionar....</option>';
	    foreach ($array as $item)
	    {
	        if ($opcion == $item)
	        {
	            $combo .= '<option value="' . $item . '" selected>' . $item. '</option>';
	        } else
	        {
	            $combo .= '<option value="' . $item . '">' . $item . '</option>';
	        }
	    }
	    return $combo;
	}
	/**
	 * Combo 
	 */
	public function comboProdMovilizar($opcion = null)
	{
	    $array = array("Canal","Subproductos","Canal con subproductos");
	    $combo = '<option value="">Seleccionar....</option>';
	    foreach ($array as $item)
	    {
	        if ($opcion == $item)
	        {
	            $combo .= '<option value="' . $item . '" selected>' . $item. '</option>';
	        } else
	        {
	            $combo .= '<option value="' . $item . '">' . $item . '</option>';
	        }
	    }
	    return $combo;
	}
	
	/**
	 * Combo
	 */
	public function comboTipoProductoMov($opcion = null)
	{
	    $array = array("Canales sin restricción de uso","Canales para uso industrial");
	    $combo = '<option value="">Seleccionar....</option>';
	    foreach ($array as $item)
	    {
	        if ($opcion == $item)
	        {
	            $combo .= '<option value="' . $item . '" selected>' . $item. '</option>';
	        } else
	        {
	            $combo .= '<option value="' . $item . '">' . $item . '</option>';
	        }
	    }
	    return $combo;
	}
	/**
	 * Combo
	 */
	public function comboTipoMovCanal($opcion = null)
	{
	    $array = array("Entera","Media","Cuarto");
	    $combo = '<option value="">Seleccionar....</option>';
	    foreach ($array as $item)
	    {
	        if ($opcion == $item)
	        {
	            $combo .= '<option value="' . $item . '" selected>' . $item. '</option>';
	        } else
	        {
	            $combo .= '<option value="' . $item . '">' . $item . '</option>';
	        }
	    }
	    return $combo;
	}
	/**
	 * Combo
	 */
	public function comboDestino($opcion = null)
	{
	    $array = array("Un destino","Varios destinos");
	    $combo = '<option value="">Seleccionar....</option>';
	    foreach ($array as $item)
	    {
	        if ($opcion == $item)
	        {
	            $combo .= '<option value="' . $item . '" selected>' . $item. '</option>';
	        } else
	        {
	            $combo .= '<option value="' . $item . '">' . $item . '</option>';
	        }
	    }
	    return $combo;
	}
	/**
	 * Combo
	 */
	public function comboEstado($opcion = null)
	{
	    $array = array("Vigente","Caducado");
	    $combo = '<option value="">Seleccionar....</option>';
	    foreach ($array as $item)
	    {
	        if ($opcion == $item)
	        {
	            $combo .= '<option value="' . $item . '" selected>' . $item. '</option>';
	        } else
	        {
	            $combo .= '<option value="' . $item . '">' . $item . '</option>';
	        }
	    }
	    return $combo;
	}
	
	public function comboProvinciaCf($provincia = null){
	    $lNegocioEmisionCertificado = new EmisionCertificadoLogicaNegocio();
	    $arrayParametros = array('identificador_operador' => $_SESSION['usuario'], 'id_area_tipo_operacion' =>'AI', 'codigo' => 'FAE');
	    $verificar = $lNegocioEmisionCertificado->buscarCentroFaenamiento($arrayParametros);
	    $valoresProvincia=array();
	    foreach ($verificar as $item) {
	        $valoresProvincia[] = $item['provincia'];
	    }
	    $valoresProvincia = array_unique($valoresProvincia);
	    
	    $combo = '<option value="">Seleccionar....</option>';
	    foreach ($valoresProvincia as $item) {
	        if($provincia == $item){
	            $combo .= '<option value="' . $item .'"-"' . $item .'" selected>' . $item. '</option>';
	        }else{
	            $combo .= '<option value="' . $item .'-' . $item .'">'.  $item. '</option>';
	        }
	        
	    }
	    return $combo;
	}
	public function comboSitioCf($arrayParametros,$idSitio = null){
	    $lNegocioEmisionCertificado = new EmisionCertificadoLogicaNegocio();
	    $verificar = $lNegocioEmisionCertificado->buscarCentroFaenamientoSitio($arrayParametros);
	    $combo = '<option value="">Seleccionar....</option>';
	    foreach ($verificar as $item) {
	        if($idSitio == $item['id_sitio']){
	            $combo .= '<option value="' . $item['id_sitio'] .'-'.$item['provincia'].'" selected>' . $item['nombre_lugar']. '</option>';
	        }else{
	            $combo .= '<option value="' . $item['id_sitio'] .'-'.$item['provincia'].'" >' .  $item['nombre_lugar']. '</option>';
	        }
	    }
	    return $combo;
	}
	public function comboAreaCf($arrayParametros,$provincia = null){
	    $lNegocioEmisionCertificado = new EmisionCertificadoLogicaNegocio();
	    $verificar = $lNegocioEmisionCertificado->buscarCentroFaenamiento($arrayParametros);
	    $combo = '<option value="">Seleccionar....</option>';
	    foreach ($verificar as $item) {
	        if($provincia == $item['provincia']){
	            $combo .= '<option value="' . $item['id_area'] .'-'.$item['provincia'].'-'.$item['id_centro_faenamiento'].'-'.$item['codigo'].'-'.$item['criterio_funcionamiento'].'-'.$item['canton'].'" selected>' . $item['nombre_area']. '</option>';
	        }else{
	            $combo .= '<option value="' . $item['id_area'] .'-'.$item['provincia'].'-'.$item['id_centro_faenamiento'].'-'.$item['codigo'].'-'.$item['criterio_funcionamiento'].'-'.$item['canton'].'" >' .  $item['nombre_area']. '</option>';
	        }
	    }
	    return $combo;
	}
	public function comboEspecieCf($arrayParametros,$especie=null){
	    $lNegocioRegistroProduccion = new RegistroProduccionLogicaNegocio();
	    $verificar = $lNegocioRegistroProduccion->buscarSitioFaenamiento($arrayParametros);
	    
	    $combo = '<option value="">Seleccionar....</option>';
	    if(is_array($verificar['especie'])){
	        foreach ($verificar['especie'] as $item) {
	            if($especie == $item){
	                $combo .= '<option value="' . $item . '" selected>' . $item. '</option>';
	            }else{
	                $combo .= '<option value="' . $item . '" >' . $item. '</option>';
	            }
	        }
	    }else{
	        if($verificar['especie'] != ''){
	            if($especie == $verificar['especie']){
	                $combo .= '<option value="' . $verificar['especie'] . '" selected>' . $verificar['especie']. '</option>';
	            }else{
	                $combo .= '<option value="' . $verificar['especie'] . '" >' . $verificar['especie']. '</option>';
	            }
	        }
	    }
	    return $combo;
	}
	
	public function fecha($ciudad, $opt, $fecha)
	{
	    $date = new \DateTime($fecha);
	    $meses = array(
	        "Enero",
	        "Febrero",
	        "Marzo",
	        "Abril",
	        "Mayo",
	        "Junio",
	        "Julio",
	        "Agosto",
	        "Septiembre",
	        "Octubre",
	        "Noviembre",
	        "Diciembre"
	    );
	    $dias = array(
	        "lunes",
	        "martes",
	        "miércoles",
	        "jueves",
	        "viernes",
	        "sábado",
	        "domingo"
	    );
	    if ($opt == 1) {
	        $fechaFinal = $ciudad . ', ' . $date->format('d') . " de " . $meses[$date->format('n') - 1] . " del " . $date->format('Y') . ' ';
	    } else if ($opt == 2) {
	        $fechaFinal = $date->format('d') . " de " . $meses[$date->format('n') - 1] . " del " . $date->format('Y') . ' ';
	    }else if ($opt == 3) {
	        $fechaFinal = $dias[$date->format('N')-1].', '.$date->format('d') . " de " . $meses[$date->format('n') - 1] . " del " . $date->format('Y') . ' '.$date->format('H:i');
	    }
	    
	    return $fechaFinal;
	}
	
	/**
	 * Consulta las Cantones y construye el combo
	 *
	 * @param Integer $idLocalizacion
	 * @return string
	 */
	public function comboCantonesECO($idProvincia, $idLocalizacion = null)
	{ 
	    $localizacion = new LocalizacionLogicaNegocio();
	    $cantones = '<option value="">Seleccione...</option>';
	    $combo = $localizacion->buscarCantones($idProvincia);
	    foreach ($combo as $item)
	    {
	        if ($idLocalizacion == $item['id_localizacion'])
	        {
	            $cantones .= '<option value="' . $item->id_localizacion . '" data-nombre="'.$item->nombre.'" selected>' . $item->nombre . '</option>';
	        } else
	        {
	            $cantones .= '<option value="' . $item->id_localizacion . '" data-nombre="'.$item->nombre.'">' . $item->nombre . '</option>';
	        }
	    }
	    return $cantones;
	}
	
	
	
	/**
	 * Consulta las Parroquias y construye el combo
	 *
	 * @param Integer $idLocalizacion
	 * @return string
	 */
	public function comboParroquiasECO($idCanton, $idLocalizacion = null)
	{
	    $localizacion = new LocalizacionLogicaNegocio();
	    $parroquias = '<option value="">Seleccione...</option>';
	    $combo = $localizacion->buscarParroquias($idCanton);
	    foreach ($combo as $item)
	    {
	        if ($idLocalizacion == $item['id_localizacion'])
	        {
	            $parroquias .= '<option value="' . $item->id_localizacion . '" data-nombre="'.$item->nombre.'" selected>' . $item->nombre . '</option>';
	        } else
	        {
	            $parroquias .= '<option value="' . $item->id_localizacion . '" data-nombre="'.$item->nombre.'">' . $item->nombre . '</option>';
	        }
	    }
	    return $parroquias;
	}
}
