<?php

/**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores
 *
 * @property AGROCALIDAD
 * @author Carlos Anchundia
 * @date      2021-10-18
 * @uses BaseControlador
 * @package RegistroControlDocumentos
 * @subpackage Controladores
 */
namespace Agrodb\RegistroControlDocumentos\Controladores;

session_start();

use Agrodb\Core\Comun;
use Agrodb\RegistroControlDocumentos\Modelos\RegistroSgcLogicaNegocio;
use Agrodb\RegistroControlDocumentos\Modelos\DetalleRegistroSgcLogicaNegocio;
use Agrodb\RegistroControlDocumentos\Modelos\DocumentoAdjuntoLogicaNegocio;
use Agrodb\RegistroControlDocumentos\Modelos\DetalleDestinatarioLogicaNegocio;
use Agrodb\RegistroControlDocumentos\Modelos\TecnicoLogicaNegocio;
use Agrodb\RegistroControlDocumentos\Modelos\DetalleTecnicoLogicaNegocio;
use Agrodb\RegistroControlDocumentos\Modelos\DetalleSocializacionLogicaNegocio;

class BaseControlador extends Comun{

	public $itemsFiltrados = array();

	public $codigoJS = null;

	public $panelBusqueda = null;

	/**
	 * Constructor
	 */
	function __construct(){
		parent::usuarioActivo();
		// Si se requiere agregar código concatenar la nueva cadena con ejemplo $this->codigoJS.=alert('hola');
		$this->codigoJS = \Agrodb\Core\Mensajes::limpiar();
	}

	public function crearTabla(){
		$tabla = "//No existen datos para mostrar...";
		if (count($this->itemsFiltrados) > 0){
			$tabla = '$(document).ready(function() {
			construirPaginacion($("#paginacion"),' . json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE) . ');
			$("#listadoItems").removeClass("comunes");
			});';
		}

		return $tabla;
	}

	public function filtroRegistroDocumentos(){
		$this->panelBusqueda = '<table class="filtro" style="width: 300px;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Buscar registro:</th>
	                                                </tr>
	                                                <tr  >
	                            						<td >No. Memorando:</td>
	                            						<td colspan="3" >
	                            							<input style="width: 100%;" id="numero_memorando_busq" type="text" name="numero_memorando__busq" value="" >
	                            						</td>
	                            					</tr>
 													<tr  style="width: 100%;">
	                            						<td >No. GLPI:</td>
	                            						<td colspan="3" >
	                            							<input style="width: 100%;" id="numero_glpi_busq" type="text" name="numero_glpi_busq" value="" >
	                            						</td>
			
	                            					</tr>
                                                    <tr  style="width: 100%;">
	                            						<td >Fecha aprob. desde: </td>
	                            						<td >
	                            							<input id="fecha_aprobacion_desde" type="text" name="fecha_aprobacion_desde" value="" readonly>
	                            						</td>
	                            						<td >Fecha aprob. hasta: </td>
	                            						<td >
	                            							<input id="fecha_aprobacion_hasta" type="text" name="fecha_aprobacion_hasta" value="" readonly>
	                            						</td>
	                            					</tr>
                                                    <tr  style="width: 100%;">
	                            						<td >Fecha notif. desde: </td>
	                            						<td >
	                            							<input id="fecha_notificacion_desde" type="text" name="fecha_notificacion_desde" value="" readonly>
	                            						</td>
	                            						<td >Fecha notif. hasta: </td>
	                            						<td >
	                            							<input id="fecha_notificacion_hasta" type="text" name="fecha_notificacion_hasta" value="" readonly>
	                            						</td>
	                            					</tr>
													<tr  style="width: 100%;">
	                            						<td >Coordinación / Dirección solicitante: </td>
	                            						<td colspan="3"><select style="width: 100%;" id="coordinacion_busq" name="coordinacion_busq">' . $this->comboAreas('DE') . '</select></td>
	                            			
	                            					</tr>
                                                   <tr  style="width: 100%;">
                        						      <td>Formato:</td>
                        						      <td ><select style="width: 100%;" id="formato_busq" name="formato_busq">' . $this->comboFormato() . '</select></td>
			
                        						      <td>Estado registro:</td>
                        					          <td ><select style="width: 100%;" id="estado_registro_busq" name="estado_registro_busq">' . $this->comboEstado() . '</select></td>
			                                          </tr>
                            						<td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
	}

	public function filtroRevisarRegistroDocuementos(){
		$this->panelBusqueda = '<table class="filtro" style="width: 300px;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Buscar registro:</th>
	                                                </tr>
	                                                <tr  >
	                            						<td >No. Memorando:</td>
	                            						<td colspan="3" >
	                            							<input style="width: 100%;" id="numero_memorando_busq" type="text" name="numero_memorando_busq" value="" >
	                            						</td>
	                            					</tr>
 													<tr  style="width: 100%;">
	                            						<td >No. GLPI:</td>
	                            						<td colspan="3" >
	                            							<input style="width: 100%;" id="numero_glpi_busq" type="text" name="numero_glpi_busq" value="" >
	                            						</td>
			
	                            					</tr>
                                                    <tr  style="width: 100%;">
	                            						<td >Fecha aprob. desde: </td>
	                            						<td >
	                            							<input id="fecha_aprobacion_desde" type="text" name="fecha_aprobacion_desde" value="" readonly>
	                            						</td>
	                            						<td >Fecha aprob. hasta: </td>
	                            						<td >
	                            							<input id="fecha_aprobacion_hasta" type="text" name="fecha_aprobacion_hasta" value="" readonly>
	                            						</td>
	                            					</tr>
                                                    <tr  style="width: 100%;">
	                            						<td >Fecha notif. desde: </td>
	                            						<td >
	                            							<input id="fecha_notificacion_desde" type="text" name="fecha_notificacion_desde" value="" readonly>
	                            						</td>
	                            						<td >Fecha notif. hasta: </td>
	                            						<td >
	                            							<input id="fecha_notificacion_hasta" type="text" name="fecha_notificacion_hasta" value="" readonly>
	                            						</td>
	                            					</tr>
													<tr  style="width: 100%;">
	                            						<td >Coordinación / Dirección solicitante: </td>
	                            						<td colspan="3"><select style="width: 100%;" id="coordinacion_busq" name="coordinacion_busq">' . $this->comboAreas('DE') . '<option value="Todas">Todas</option></select></td>
	                            					</tr>
                                                   <tr  style="width: 100%;">
                        						      <td>Formato:</td>
                        						      <td><select style="width: 100%;" id="formato_busq" name="formato_busq">' . $this->comboFormato() . '</select></td>
			
                        						      <td>Estado socialización:</td>
                        						      <td><select style="width: 100%;" id="estadoSocializar" name="estadoSocializar">' . $this->comboEstadoSocializar() . '</select></td>
                        					       </tr>
                            						<td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
	}

	public function filtroReporteRegistrosSgc(){
		$this->panelBusqueda = '<table class="filtro" style="width: 300px;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Buscar registro:</th>
	                                                </tr>
	                                                
                                                    <tr  style="width: 100%;">
	                            						<td >Fecha aprob. desde: </td>
	                            						<td >
	                            							<input id="fecha_aprobacion_desde" type="text" name="fecha_aprobacion_desde" value="" readonly>
	                            						</td>
	                            						<td >Fecha aprob. hasta: </td>
	                            						<td >
	                            							<input id="fecha_aprobacion_hasta" type="text" name="fecha_aprobacion_hasta" value="" readonly>
	                            						</td>
	                            					</tr>
                                                    <tr  style="width: 100%;">
                        						      <td>Formato:</td>
                        						         <td colspan="3"><select style="width: 100%;" id="formato_busq" name="formato_busq">' . $this->comboFormato() . '</select></td>
			                                         </tr>
													<tr  style="width: 100%;">
	                            						<td >Coordinación / Dirección solicitante: </td>
	                            							<td colspan="3"><select style="width: 100%;" id="coordinacion_busq" name="coordinacion_busq">' . $this->comboAreas('DE') . '<option value="Todas">Todas</option></select></td>
	                            						</td>
	                            					</tr>
                                                    <tr  style="width: 100%;">
	                            						<td >Coordinación / Dirección notificada: </td>
	                            						<td colspan="3">
	                            							<select style="width: 100%;" id="coordinacion_dest_busq" name="coordinacion_dest_busq">' . $this->comboAreas('DE') . '<option value="Todas">Todas</option></select></td>
	                            						</td>
	                            					</tr>
                                                   <tr  style="width: 100%;">
                        						      <td >Estado socialización:</td>
                        						      <td colspan="3"><select style="width: 100%;" id="estadoSocializar" name="estadoSocializar">' . $this->comboEstadoSocializar() . '</select></td>
                        					       </tr>
                                                   <td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
	}

	public function filtroReportesDocumentosSgc(){
		$this->panelBusqueda = '<table class="filtro" style="width: 300px;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Buscar registro:</th>
	                                                </tr>
			
                                                    <tr  style="width: 100%;">
	                            						<td >Fecha aprob. desde: </td>
	                            						<td >
	                            							<input id="fecha_aprobacion_desde" type="text" name="fecha_aprobacion_desde" value="" readonly>
	                            						</td>
	                            						<td >Fecha aprob. hasta: </td>
	                            						<td >
	                            							<input id="fecha_aprobacion_hasta" type="text" name="fecha_aprobacion_hasta" value="" readonly>
	                            						</td>
	                            					</tr>
                                                    <tr  style="width: 100%;">
                        						      <td>Formato:</td>
                        						        <td colspan="3"><select style="width: 100%;" id="formato_busq" name="formato_busq">' . $this->comboFormato() . '</select></td>
			                                        </tr>
													<tr  style="width: 100%;">
	                            						<td >Coordinación / Dirección solicitante: </td>
	                            						<td colspan="3">
	                            						<select style="width: 100%;" id="coordinacion_busq" name="coordinacion_busq">' . $this->comboAreas('DE') . '<option value="Todas">Todas</option></select></td>
	                            						</td>
	                            					</tr>
                                                    <tr  style="width: 100%;">
	                            						<td >Subproceso: </td>
	                            						<td colspan="3">
	                            						    <select style="width: 100%;" id="subproceso_busq" name="subproceso_busq"><option value="">Seleccione...</option></select></td>
	                            						</td>
	                            					</tr>
                                                   <tr  style="width: 100%;">
                        						      <td >Estado registro:</td>
                        						      <td colspan="3"><select style="width: 100%;" id="estado_registro_busq" name="estado_registro_busq">' . $this->comboEstado() . '</select></td>
                        					       </tr>
                                                   <tr  style="width: 100%;">
                        						      <td >Socializar:</td>
                        						      <td colspan="3"><select style="width: 100%;" id="socializar_busq" name="socializar_busq">' . $this->comboSiNo() . '</select></td>
                        					       </tr>
                            						<td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
	}

	public function comboAreas($areaPadre, $idArea = NULL){
		$lNegocioRegistroSgc = new RegistroSgcLogicaNegocio();
		$consulta = $lNegocioRegistroSgc->buscarSubProcesoEstructura($areaPadre);
		$combo = '<option value="">Seleccionar....</option>';
		foreach ($consulta as $item){
			if ($idArea == $item['id_area']){
				$combo .= '<option value="' . $item['id_area'] . '" selected>' . $item['nombre'] . '</option>';
			}else{
				$combo .= '<option value="' . $item['id_area'] . '">' . $item['nombre'] . '</option>';
			}
		}
		return $combo;
	}

	/**
	 * Combo
	 */
	public function comboFormato($opcion = null){
		$array = array(
			"Matriz",
			"Flujograma",
			"Formato",
			"Manual",
			"Resolución");
		$combo = '<option value="">Seleccionar....</option>';
		foreach ($array as $item){
			if ($opcion == $item){
				$combo .= '<option value="' . $item . '" selected>' . $item . '</option>';
			}else{
				$combo .= '<option value="' . $item . '">' . $item . '</option>';
			}
		}
		return $combo;
	}

	/**
	 * Combo
	 */
	public function comboEstadoSocializar($opcion = null){
		$array = array(
			"Atendido",
			"No atendido",
			"Todos");
		$combo = '<option value="">Seleccionar....</option>';
		foreach ($array as $item){
			if ($opcion == $item){
				$combo .= '<option value="' . $item . '" selected>' . $item . '</option>';
			}else{
				$combo .= '<option value="' . $item . '">' . $item . '</option>';
			}
		}
		return $combo;
	}

	public function comboNumeros($maximo, $valor = null){
		$combo = '<option>Seleccionar....</option>';
		for ($i = 0; $i <= $maximo; $i ++){
			if ($valor == $i){
				$combo .= '<option value="' . $i . '" selected>' . $i . '</option>';
			}else{
				$combo .= '<option value="' . $i . '" >' . $i . '</option>';
			}
		}
		return $combo;
	}

	/**
	 * Combo
	 */
	public function comboEstado($opcion = null){
		$array = array(
			"Vigente",
			"Obsoleto");
		$combo = '<option value="">Seleccionar....</option>';
		foreach ($array as $item){
			if ($opcion == $item){
				$combo .= '<option value="' . $item . '" selected>' . $item . '</option>';
			}else{
				$combo .= '<option value="' . $item . '">' . $item . '</option>';
			}
		}
		return $combo;
	}

	/**
	 * Combo
	 */
	public function comboSocializar($opcion = null){
		$array = array(
			"Si",
			"No");
		$combo = '<option value="">Seleccionar....</option>';
		foreach ($array as $item){
			if ($opcion == $item){
				$combo .= '<option value="' . $item . '" selected>' . $item . '</option>';
			}else{
				$combo .= '<option value="' . $item . '">' . $item . '</option>';
			}
		}
		return $combo;
	}

	/**
	 *
	 * @return string
	 */
	public function listarEnlaces($idRegistroSgc, $opt = 'Si'){
		$html = $datos = '';
		if ($idRegistroSgc != ''){
			$lNegocioDetalleRegistroSgc = new DetalleRegistroSgcLogicaNegocio();
			$consulta = $lNegocioDetalleRegistroSgc->buscarLista("id_registro_sgc=" . $idRegistroSgc . " and estado='creado' order by 1");
			if ($consulta->count()){
				$contador = 0;
				foreach ($consulta as $item){

					$datos .= '<tr>';
					$datos .= '<td>' . ++ $contador . '</td>';
					$datos .= '<td align="center"><a href=' . $item->enlace_socializar . ' target="_blank" class="archivo_cargado" id="archivo_cargado">' . $item->enlace_socializar . '</a></td>';
					if ($opt == 'Si'){
						$datos .= '<td><button class="bEliminar icono" onclick="eliminarEnlace(' . $item->id_detalle_registro_sgc . '); return false; "></button></td>';
					}else{
						$datos .= '<td></td>';
					}
					$datos .= '</tr>';
				}
				$html = '
        				<table style="width:100%">
        					<thead><tr>
        						<th>No.</th>
        						<th>Enlace</th>
                                <th></th>
        						</tr></thead>
        					<tbody>' . $datos . '</tbody>
        				</table>';
			}
		}
		return $html;
	}

	/**
	 *
	 * @return string
	 */
	public function listarDocumentos($idRegistroSgc){
		$html = $datos = '';
		if ($idRegistroSgc != ''){
			$lNegocioDocumentoAdjunto = new DocumentoAdjuntoLogicaNegocio();
			$consulta = $lNegocioDocumentoAdjunto->buscarLista("id_registro_sgc=" . $idRegistroSgc . " and estado='creado' order by 1");
			if ($consulta->count()){
				foreach ($consulta as $item){
					$datos .= '<tr>';
					$datos .= '<td><a href=' . $item['ruta_archivo'] . ' target="_blank" class="archivo_cargado" id="archivo_cargado">' . $item['nombre_archivo'] . '</a></td>';
					$datos .= '</tr>';
				}
				$html = '<legend>Documentos Adjuntos</legend>
        				<table style="width:100%">
        					<tbody>' . $datos . '</tbody>
        				</table>';
			}
		}
		return $html;
	}

	/**
	 *
	 * @return string
	 */
	public function listarDocumentoSocializar($idDetalleDestinatario, $opt = 'si'){
		$html = $datos = '';
		if ($idDetalleDestinatario != ''){
			$lNegocioDetalleSocializar = new DetalleSocializacionLogicaNegocio();
			$consulta = $lNegocioDetalleSocializar->buscarLista("id_detalle_destinatario=" . $idDetalleDestinatario . "  order by 1");
			if ($consulta->count()){
				if ($opt == 'si'){
					$datos .= '<legend>Evidencia Adjunta</legend>';
				}
				foreach ($consulta as $item){
					if ($item['documento_socializar'] != ''){
						$datos .= '<tr>';
						$datos .= '<td><a href=' . $item['documento_socializar'] . ' target="_blank" class="archivo_cargado" id="archivo_cargado">' . $item['nombre_socializar'] . '</a></td>';
						$datos .= '</tr>';
					}
				}
				$html = '
        				<table style="width:100%">
        					<tbody>' . $datos . '</tbody>
        				</table>';
			}
		}
		return $html;
	}

	/**
	 *
	 * @return string
	 */
	public function comboTecnicos($opcion = null){
		$lNegocioRegistroSgc = new RegistroSgcLogicaNegocio();

		$combo = '';
		$consulta = $lNegocioRegistroSgc->buscarFuncionarioSocializar();
		$combo = '<option value="">Seleccionar....</option>';
		foreach ($consulta as $item){
			if ($opcion == $item->identificador){
				$combo .= '<option value=' . $item->identificador . ' selected>' . $item->funcionario . '</option>';
			}else{
				$combo .= '<option value=' . $item->identificador . ' >' . $item->funcionario . '</option>';
			}
		}
		return $combo;
	}

	/**
	 * Combo
	 */
	public function comboDestinatario($opcion = null){
		$array = array(
			"Coordinadores y Directores Generales",
			"Directores Distritales y Articulación Territorial (A)",
			"Directores Distritales tipo B",
			"Jefes de servicio de Sanidad Agropecuaria");
		$combo = '<option value="">Seleccionar....</option>';
		foreach ($array as $item){
			if ($opcion == $item){
				$combo .= '<option value="' . $item . '" selected>' . $item . '</option>';
			}else{
				$combo .= '<option value="' . $item . '">' . $item . '</option>';
			}
		}
		return $combo;
	}

	/**
	 *
	 * @return string
	 */
	public function listarDestinatarios($destinatario){
		$html = $datos = '';
		$arrayParametros = array();
		$lNegocioRegistroSgc = new RegistroSgcLogicaNegocio();
		switch ($destinatario) {
			case 'Coordinadores y Directores Generales':
				$arrayParametros = array(
					'clasificacion' => 'Planta Central',
					'area' => 'DE');
			break;
			case 'Directores Distritales y Articulación Territorial (A)':
				$arrayParametros = array(
					'clasificacion' => 'Dirección Distrital A');
			break;
			case 'Directores Distritales tipo B':
				$arrayParametros = array(
					'clasificacion' => 'Dirección Distrital B',
					'tipoB' => 'Si');
			break;
			case 'Jefes de servicio de Sanidad Agropecuaria':
				$arrayParametros = array(
					'clasificacion' => 'Dirección Distrital B',
					'jefatura' => 'Si');
			break;
		}
		$consulta = $lNegocioRegistroSgc->filtroObtenerFuncionarios($arrayParametros);
		if ($consulta->count()){
			foreach ($consulta as $item){
				$datos .= '<tr>';
				$parametros = $item['identificador'] . '-' . $item['nombre'] . '-' . $item['area'] . '-' . $item['nombrearea'];
				$datos .= '<td><input type="checkbox" id="' . $item['identificador'] . '" value="' . $parametros . '" name="check[]" onclick="verificarDestinatario(id);"/> </td>';
				$datos .= '<td>' . $item['nombrearea'] . ' - ' . $item['nombre'] . '</td>';
				$datos .= '</tr>';
			}
			$html = '
        				<table style="width:100%"><thead>
							<th><input type="checkbox" id="seleccionarItem" value="" name="seleccionarItem" onclick="selecionarTodos(id);"/> </th>
                            <th style="text-align: left;">Seleccionar todos</th></thead>
        					<tbody>' . $datos . '</tbody>
        				</table>';
		}

		return $html;
	}

	/**
	 *
	 * @return string
	 */
	public function listarDestinatariosRegistrados($idRegistroSgc, $opt = 'Si'){
		$html = $datos = '';
		if ($idRegistroSgc != ''){
			$lNegocioDetalleDestinatario = new DetalleDestinatarioLogicaNegocio();

			$consulta = $lNegocioDetalleDestinatario->buscarLista("id_registro_sgc=" . $idRegistroSgc);
			if ($consulta->count()){
				$contador = 0;
				foreach ($consulta as $item){
					$datos .= '<tr>';
					$datos .= '<td>' . ++ $contador . '</td>';
					$datos .= '<td>' . $item->nombre_area . ' - ' . $item->nombre . '</td>';
					if ($opt == 'Si'){
						$datos .= '<td><button class="bEliminar icono" onclick="eliminarDestinatario(' . $item->id_detalle_destinatario . '); return false; "></button></td>';
					}else{
						$datos .= '<td></td>';
					}
					$datos .= '</tr>';
				}
				$valor = '<th></th>';
				if ($opt == 'Si'){
					$valor = '<th>Acción</th>';
				}
				$html = '   <strong>Destinatarios agregados:</strong>
        				<table style="width:100%">
							<thead><tr>
							<th>No.</th>
								<th>Coordinación / Dirección</th>' . $valor . '
								</tr></thead>
							<tbody>' . $datos . '</tbody>
        				</table>';
			}
		}
		return $html;
	}

	/**
	 *
	 * @return string
	 */
	public function listarTecnicoRegistrado($idDetalleDestinatario, $opt = 'Si'){
		$html = $datos = '';
		if ($idDetalleDestinatario != ''){
			$lNegocioRegistroSgc = new RegistroSgcLogicaNegocio();
			$arrayParametros = array(
				'identifcador_asignante' => $_SESSION['usuario'],
				'id_detalle_destinatario' => $idDetalleDestinatario);
			$consulta = $lNegocioRegistroSgc->filtrarTecnicoRegistrado($arrayParametros);
			if ($consulta->count()){
				foreach ($consulta as $item){
					$datos .= '<tr>';
					$datos .= '<td>' . $item->numero_memorando . '</td>';
					$datos .= '<td>' . $item->nombre . '</td>';
					if ($opt == 'Si'){
						$datos .= '<td><button class="bEliminar icono" onclick="eliminarTecnico(' . $item->id_detalle_socializacion . '); return false; "></button></td>';
					}else{
						$datos .= '<td></td>';
					}
					$datos .= '</tr>';
				}
				$valor = '<th></th>';
				if ($opt == 'Si'){
					$valor = '<th>Acción</th>';
				}
				$html = '   
        				<table style="width:100%">
							<thead><tr>
							<th>No. Memorando</th>
								<th>Técnico asignado</th>' . $valor . '
								</tr></thead>
							<tbody>' . $datos . '</tbody>
        				</table>';
			}
		}
		return $html;
	}

	/**
	 *
	 * @return string
	 */
	public function listarTecnicoRegistradoRevisar($idDetalleDestinatario, $opt = 'Si'){
		$html = $datos = '';
		if ($idDetalleDestinatario != ''){
			$lNegocioRegistroSgc = new RegistroSgcLogicaNegocio();

			$arrayParametros = array(
				'id_detalle_destinatario' => $idDetalleDestinatario);
			$consulta = $lNegocioRegistroSgc->filtrarTecnicoRegistrado($arrayParametros);

			if ($consulta->count()){
				foreach ($consulta as $item){
					$datos .= '<tr>';
					$datos .= '<td>' . $item->numero_memorando . '</td>';
					$datos .= '<td>' . $item->nombre . '</td>';
					if ($opt == 'Si'){
						$datos .= '<td><button class="bEliminar icono" onclick="eliminarTecnico(' . $item->id_detalle_socializacion . '); return false; "></button></td>';
					}else{
						$datos .= '<td></td>';
					}
					$datos .= '</tr>';
				}
				$valor = '<th></th>';
				if ($opt == 'Si'){
					$valor = '<th>Acción</th>';
				}
				$html = '
        				<table style="width:100%">
							<thead><tr>
							<th>No. Memorando</th>
								<th>Técnico asignado</th>' . $valor . '
								</tr></thead>
							<tbody>' . $datos . '</tbody>
        				</table>';
			}
		}
		return $html;
	}
}
