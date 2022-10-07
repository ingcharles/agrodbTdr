<?php

 /**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores 
 *
 * @property  AGROCALIDAD
 * @author    Carlos Anchundia
 * @date      2020-03-16
 * @uses      BaseControlador
 * @package   HistoriasClinicas
 * @subpackage Controladores
 */
namespace Agrodb\HistoriasClinicas\Controladores;

session_start();

use Agrodb\Core\Comun;
use Agrodb\HistoriasClinicas\Modelos\HistoriaClinicaLogicaNegocio;
 
 
class BaseControlador extends Comun
{
	public $itemsFiltrados = array();
	public $codigoJS = null;
	public $panelBusqueda = null;
	public $perfilUsuario = null;

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
	public function filtroHistorias(){
		$this->panelBusqueda = '<table class="filtro" style="width: 100%;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Buscar por:</th>
	                                                </tr>
	                            					<tr  style="width: 100%;">
									                <td ><input class="radioOpt" value="ci" checked name="tipo" type="radio" id="ci" onclick="verificar(id);"><span>CI</span></td >
								                    <td ><input class="radioOpt" value="pasaporte" name="tipo" type="radio" id="pasaporte" onclick="verificar(id);"><span>Pasaporte</span></td >
								 					<td ><input class="radioOpt" value="apellido" name="tipo" type="radio" id="apellido" onclick="verificar(id);"><span>Apellidos</span></td >
	                            					</tr>
     
	                                                <tr  style="width: 100%;">
	                            						<td >CI / Pasaporte / Apellidos: </td>
	                            						<td colspan="3">
	                            							<input id="identificadorFiltro" type="text" name="identificadorFiltro" value="" >
	                            						</td>
													
	                            					</tr>
	                            								
                            						<td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
	}
	/**
	 * Consulta los meses
	 */
	public function comboMeses($mes = null)
	{
	    $meses = array(
	         array("01","Enero"),
	         array("02","Febrero"),
	         array("03","Marzo"),
	         array("04","Abril"),
	         array("05","Mayo"),
	         array("06","Junio"),
	         array("07","Julio"),
	         array("08","Agosto"),
	         array("09","Septiembre"),
	         array("10","Octubre"),
	         array("11","Noviembre"),
	         array("12","Diciembre"));
	    
	    $list = '<option value="">Seleccionar....</option>';
	    foreach ($meses as $item)
	    {
	        if ($mes == $item[0])
	        {
	            $list .= '<option value="' . $item[0] . '" selected>' . $item[1]. '</option>';
	        } else
	        {
	            $list .= '<option value="' . $item[0] . '">' . $item[1] . '</option>';
	        }
	    }
	    return $list;
	}
	/**
	 * Consulta los meses
	 */
	public function comboAnios($anioBusqu = null)
	{
	    $anio = date('Y');
	    $list = '<option value="">Seleccionar....</option>';
	    for($i=2000; $i<= $anio; $i++){
	        if ($i == $anioBusqu)
	        {
	            $list .= '<option value="' . $i . '" selected>' . $i. '</option>';
	        } else
	        {
	            $list .= '<option value="' . $i . '">' . $i . '</option>';
	        }
	    }
	    return $list;
	}
	
	/**
	 * Consulta naturaleza lesion
	 */
	public function comboNaturalezaLesion($naturalezaLesion = null)
	{
	    $array = array(
	        "Accidente",
	        "Incidente");
	    $list = '<option value="">Seleccionar....</option>';
	    foreach ($array as $item)
	    {
	        if ($naturalezaLesion == $item)
	        {
	            $list .= '<option value="' . $item . '" selected>' . $item. '</option>';
	        } else
	        {
	            $list .= '<option value="' . $item . '">' . $item . '</option>';
	        }
	    }
	    return $list;
	}
	/**
	 * Combo de opcion si o no
	 */
	public function comboOpcion($opcion = null)
	{
	    $array = array(
	        "Si",
	        "No");
	    $list = '<option value="">Seleccionar....</option>';
	    foreach ($array as $item)
	    {
	        if ($opcion == $item)
	        {
	            $list .= '<option value="' . $item . '" selected>' . $item. '</option>';
	        } else
	        {
	            $list .= '<option value="' . $item . '">' . $item . '</option>';
	        }
	    }
	    return $list;
	}
	/**
	 * combo ciclo mestrual
	 */
	public function comboCicloMestrual($cicloMestrual = null)
	{
	    $array = array(
	        "Normal",
	        "Anormal");
	    $list = '<option value="">Seleccionar....</option>';
	    foreach ($array as $item)
	    {
	        if ($cicloMestrual == $item)
	        {
	            $list .= '<option value="' . $item . '" selected>' . $item. '</option>';
	        } else
	        {
	            $list .= '<option value="' . $item . '">' . $item . '</option>';
	        }
	    }
	    return $list;
	}
	
	/**
	 * Combo de numeros
	 */
	public function comboNumeros($num,$ini,$valor = null)
	{
	    if($valor == null){
	        $valor=0;
	    }
	    $list = '<option value="">Seleccionar....</option>';
	    for($i=$ini; $i<=$num; $i++){
	        if ($valor == $i)
	        {
	            $list .= '<option value="' . $i . '" selected>' . $i. '</option>';
	        } else
	        {
	            $list .= '<option value="' . $i . '">' . $i . '</option>';
	        }
	    }
	    return $list;
	}
	/**
	 * Combo de alcohol
	 */
	public function comboAlcohol($valor = null)
	{
	    $valores = array('Diario','Semanal','Quinsenal','Mensual','Ocasional');
	    $combo = '<option value="">Seleccionar....</option>';
	    foreach ($valores as $item)
	    {
	        if ($valor == $item)
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
	 * Combo de tabaco
	 */
	public function comboTabaco($valor = null)
	{
	    $valores = array('1 - 5','6 - 10','11 - 15','16 - 20','Más de 20');
	    $combo = '<option value="">Seleccionar....</option>';
	    foreach ($valores as $item)
	    {
	        if ($valor == $item)
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
	 * Combo de sustancias
	 */
	public function comboFrecuencia($valor = null)
	{
	    $valores = array('Muy rara vez','A diario','Una vez por semana','Una vez por mes');
	    $combo = '<option value="">Seleccionar....</option>';
	    foreach ($valores as $item)
	    {
	        if ($valor == $item)
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
	 * Combo de actividad
	 */
	public function comboActividad($valor = null)
	{
	    $valores = array('Física','Cultural','Familiar');
	    $combo = '<option value="">Seleccionar....</option>';
	    foreach ($valores as $item)
	    {
	        if ($valor == $item)
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
	 * Combo de actividad
	 */
	public function comboEstadoClinico($valor = null)
	{
	    $valores = array('Normal','Anormal');
	    $combo = '<option value="">Seleccionar....</option>';
	    foreach ($valores as $item)
	    {
	        if ($valor == $item)
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
	 * Combo de actividad
	 */
	public function comboTipoDocumento($valor = null)
	{
	    $valores = array('Certificado de Aptitud de Ingreso','Certificado de Aptitud de Egreso','Informe Médico');
	    $combo = '<option value="">Seleccionar....</option>';
	    foreach ($valores as $item)
	    {
	        if ($valor == $item)
	        {
	            $combo .= '<option value="' . $item . '" selected>' . $item. '</option>';
	        } else
	        {
	            $combo .= '<option value="' . $item . '">' . $item . '</option>';
	        }
	    }
	    return $combo;
	}
	public function perfilUsuario(){
	    $lNegocioHistoriaClinica = new HistoriaClinicaLogicaNegocio();
	    $consulta = $lNegocioHistoriaClinica->verificarPerfil($_SESSION['usuario']);
	    $this->perfilUsuario = $consulta->current()->codificacion_perfil;
	}
}
