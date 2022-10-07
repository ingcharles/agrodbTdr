<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Agrodb\Programas\Modelos;

/**
 * Description of AccionesLogicaNegocio
 *
 * @author Alvaro Sanchez
 */
use Agrodb\Laboratorios\Modelos\LaboratoriosModelo;
use Agrodb\Programas\Modelos\AccionesModelos;
use Agrodb\Auditoria\Modelos\IngresoAplicacionLogicaNegocio;

class AccionesLogicaNegocio implements IModelo{

	private $modelo = null;

	function __construct(){
		$this->modelo = new AccionesModelos();
	}

	public function borrar($id){
	}

	public function buscar($id){
	}

	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
	}

	public function buscarTodo(){
	}

	public function guardar(LaboratoriosModelo $tabla){
	}

	public function obtenerAccionesPermitidas($idOpcion, $idUsuario){
		
		$consulta = "select a.id_accion,
                   			a.pagina,
							a.estilo,
							a.descripcion,
							apl.ruta,
							op.ruta_mvc
					  from  
							g_programas.aplicaciones apl,
							g_programas.opciones op,
							g_programas.acciones a,
						    g_programas.acciones_perfiles ap,
							g_usuario.usuarios_perfiles up   
					where 
							apl.id_aplicacion = op.id_aplicacion and
							op.id_opcion = a.id_opcion and 
							a.id_accion = ap.id_accion and 
							ap.id_perfil = up.id_perfil and
							up.identificador = '" . $idUsuario . "' and
							a.id_opcion = " . $idOpcion . " order by a.orden;";
		
		$lNegocioIngresoAplicacion = new IngresoAplicacionLogicaNegocio();
		
		$datos = array('identificador' => $idUsuario, 'id_acceso' => $idOpcion, 'tipo_acceso' => 'id_opcion');
		
		$lNegocioIngresoAplicacion->guardar($datos);
		
		return $this->modelo->ejecutarConsulta($consulta);
	}
}
