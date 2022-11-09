<?php

 /**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores 
 *
 * @property  AGROCALIDAD
 * @author    Carlos Anchundia
 * @date      2022-10-22
 * @uses      BaseControlador
 * @package   VacacionesPermisos
 * @subpackage Controladores
 */
namespace Agrodb\VacacionesPermisos\Controladores;

session_start();

use Agrodb\Core\Comun;
use Agrodb\VacacionesPermisos\Modelos\CronogramaVacacionesLogicaNegocio;

class BaseControlador extends Comun
{
	public $itemsFiltrados = array();
	public $codigoJS = null;

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

	public function construirDatosGeneralesCronogramaVacaciones() {
		
		$cronogramaVacacionesLogicaNegocio = new CronogramaVacacionesLogicaNegocio();
		$qDatosFuncionario = $cronogramaVacacionesLogicaNegocio->obtenerDatosEmpleadoFechaIngresoInstitucion($this->identificador);

		$nombre = $qDatosFuncionario->current()->nombre;
		$fechaIngreso = $qDatosFuncionario->current()->fecha_ingreso_institucion;
		$unidadAdministrativa = $qDatosFuncionario->current()->nombre_unidad_administrativa;
		$gestionAdministrativa = $qDatosFuncionario->current()->nombre_gestion_administrativa;
		$idPuesto = $qDatosFuncionario->current()->id_puesto;
		$puestoInstitucional = $qDatosFuncionario->current()->puesto_institucional;
		
		$qSaldoFuncionario = $cronogramaVacacionesLogicaNegocio->consultarSaldoFuncionario($this->identificador);
		
		if($qSaldoFuncionario->count()){
			$minutos = $qSaldoFuncionario->current()->minutos_disponibles;
		}else{
			$minutos = 0;
		}
		
		$qSaldoFuncionarioNuevo = $cronogramaVacacionesLogicaNegocio->consultarSaldoFuncionarioNuevo($this->identificador);
		
		if($qSaldoFuncionarioNuevo->count()){
			$minutosNuevo = $qSaldoFuncionarioNuevo->current()->minutos_disponibles;
		}else{
			$minutosNuevo = 0;
		}

		$minutos = $minutos + $minutosNuevo;

		$diasDisponibles = $cronogramaVacacionesLogicaNegocio->devolverFormatoDiasDisponibles($minutos);

		$datos = '
		
		<input type="hidden" name="fecha_ingreso_institucion" id="fecha_ingreso_institucion" value="' . $fechaIngreso . '"/>
		<input type="hidden" name="id_puesto" id="id_puesto" value="' . $idPuesto . '"/>

		<fieldset>
		<legend>Cronograma de planificación</legend>
		<input type="hidden" name="id_puesto" id="id_puesto" value="' . $idPuesto . '" />"
		<div data-linea="1">
			<label for="identificador">Identificador: </label>' 
			. $this->identificador .
		'</div>	
		
		<div data-linea="2">
			<label for="identificador">Apellidos y nombres: </label>'
			 . $nombre .
		'</div>	

		<div data-linea="3">
			<label for="fecha_ingreso_institucion">Fecha de ingreso: </label>'
			. $fechaIngreso . 
		'</div>				

		<div data-linea="4">
			<label for="unidad_administrativa">Unidad administrativa: </label>'
			. $unidadAdministrativa .
		'</div>

		<div data-linea="5">
			<label for="gestion_administrativa">Gestión administrativa: </label>'
			. $gestionAdministrativa .
		'</div>

		<div data-linea="6">
			<label for="puesto_institucional">Puesto institucional: </label>'
			. $puestoInstitucional .
		'</div>
		<hr/>
		<div data-linea="7">
			<label for="dias_disponibles">Días disponibles: </label>'
			. $diasDisponibles .
		'</div>
		</fieldset>';		

		return $datos;

	}


}
