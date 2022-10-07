<?php

 /**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores 
 *
 * @property  AGROCALIDAD
 * @author    Carlos Anchundia
 * @date      2021-03-17
 * @uses      BaseControlador
 * @package   ProcesosAdministrativosJuridico
 * @subpackage Controladores
 */
namespace Agrodb\ProcesosAdministrativosJuridico\Controladores;

session_start();

use Agrodb\Core\Comun;
use Agrodb\ProcesosAdministrativosJuridico\Modelos\TecnicoProvinciaLogicaNegocio;
use Agrodb\ProcesosAdministrativosJuridico\Modelos\ModeloAdministrativoLogicaNegocio;
 
 
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
	public function filtroActos(){
	    
	    $this->panelBusqueda = '<table class="filtro" style="width: 300px;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Búsqueda:</th>
	                                                </tr>
	                                                <tr  style="width: 100%;">
	                            						<td >No.Proceso:</td>
	                            						<td colspan="3" style="width: 30%;">
	                            							<input id="numero_proceso" type="text" name="numero_proceso" value="" >
	                            						</td>
	        
	                            					</tr>
	                                                <tr  >
	                            						<td >Área Técnica: </td>
	                            				       <td><select style="width: 100%;" id="area_tecnica" name="area_tecnica"><option value="">Seleccione...</option>'.$this->comboAreaTecnica().'</select></td>
                        					     	</tr>
                                                    <tr  style="width: 100%;">
	                            						<td >Fecha de Creación: </td>
	                            						<td colspan="3">
	                            							<input id="fecha_creacion" type="text" name="fecha_creacion" value="" readonly>
	                            						</td>
	                            					</tr>
                                                   <tr >
                        						      <td>Provincia:</td>
                        						      <td><select style="width: 100%;" id="provinciab" name="provinciab"><option value="">Seleccione...</option>'.$this->comboProvinciasEc().'</select></td>
                        					       </tr>
                            						<td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
	}
	public function filtroConsulta(){
	    
	    $this->panelBusqueda = '<table class="filtro" style="width: 300px;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Búsqueda:</th>
	                                                </tr>
	                                                <tr  style="width: 100%;">
	                            						<td >No.Proceso:</td>
	                            						<td colspan="3" style="width: 30%;">
	                            							<input id="numero_proceso" type="text" name="numero_proceso" value="" >
	                            						</td>
	        
	                            					</tr>
                                                   <tr >
                        						      <td>Provincia:</td>
                        						      <td><select style="width: 100%;" id="provinciab" name="provinciab"><option value="">Seleccione...</option>'.$this->comboProvinciasEc().'</select></td>
                        					       </tr>
                                                    <tr  style="width: 100%;">
	                            						<td >Fecha de Creación: </td>
	                            						<td colspan="3">
	                            							<input id="fecha_creacion" type="text" name="fecha_creacion" value="" readonly>
	                            						</td>
	                            					</tr>
                            						<td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
	}
	public function filtroReporte(){
	    
	    $this->panelBusqueda = '<table class="filtro" style="width: 300px;">
                                            	<tbody>
	                                                <tr>
	                                                    <th colspan="4">Búsqueda:</th>
	                                                </tr>
                                                    <tr >
                        						      <td>Provincia:</td>
                        						      <td><select style="width: 100%;" id="provinciab" name="provinciab"><option value="">Seleccione...</option>'.$this->comboProvinciasEc().'</select></td>
                        					       </tr>
                                                    <tr  style="width: 100%;">
	                            						<td >Fecha Desde: </td>
	                            						<td colspan="3" style="width: 30%;">
	                            							<input id="fecha_desde" type="text" name="fecha_desde" value="" readonly>
	                            						</td>
	                            					</tr>
                                                    <tr  style="width: 100%;">
	                            						<td >Fecha Hasta: </td>
	                            						<td colspan="3" style="width: 30%;">
	                            							<input id="fecha_hasta" type="text" name="fecha_hasta" value="" readonly>
	                            						</td>
	                            					</tr>
	                                                <tr  >
	                            						<td >Área Técnica: </td>
	                            				       <td><select style="width: 100%;" id="area_tecnica" name="area_tecnica"><option value="">Seleccione...</option>'.$this->comboAreaTecnica().'</select></td>
                        					     	</tr>
                                                    
                                                  
                            						<td colspan="4" style="text-align: end;">
                            							<button id="btnFiltrar">Filtrar lista</button>
                            						</td>
                            					</tr>
                            				</tbody>
                            			</table>';
	}
	/**
	 * Combo de datos área tematicas
	 *
	 * @param type $respuesta
	 * @return string
	 */
	public function comboAreaTecnica($valor = null)
	{
	    $combo = "";
// 	    $opt = array(
// 	        'Coordinación de Sanidad Animal' => 'S.A-Coordinación de Sanidad Animal',
// 	        'Coordinación de Sanidad Vegetal' => 'S.V-Coordinación de Sanidad Vegetal',
// 	        'Coordinación Inocuidad de Alimento' => 'I.A-Coordinación Inocuidad de Alimento',
// 	        'Coordinación de Registro de Insumos Agropecuarios' => 'R.I.A-Coordinación de Registro de Insumos Agropecuarios'
// 	    );
	    $opt = array(
	        'Unidad Técnica de Sanidad Animal' => 'S.A-Unidad Técnica de Sanidad Animal',
	        'Unidad Técnica de Sanidad Vegetal' => 'S.V-Unidad Técnica de Sanidad Vegetal',
	        'Unidad Técnica Inocuidad de Alimentos' => 'I.A-Unidad Técnica Inocuidad de Alimento',
	        'Unidad Técnica de Registro de Insumos Agropecuarios' => 'R.I.A-Unidad Técnica de Registro de Insumos Agropecuarios'
	    );
	    //$combo .= '<option value="" >Seleccione....</option>';
	    foreach ($opt as $value => $item) {
	        if (ucfirst($valor) == ucfirst($value)) {
	            $combo .= '<option value="' . $item . '" selected>' . $value . '</option>';
	        } else {
	            $combo .= '<option value="' . $item . '">' . $value . '</option>';
	        }
	    }
	    return $combo;
	}
	
	/**
	 * Combo de datos de tecnicos por provincia
	 *
	 * @param type $respuesta
	 * @return string
	 */
	public function comboProvinciaTecnico($item = null)
	{
	    $lnegocioTecnicoProvincia = new TecnicoProvinciaLogicaNegocio();
	    $arrayParametros = array('identificador' => $_SESSION['usuario'], 'estadoP' => 'creado', 'estadoS' => 'creado' );
	    $consulta = $lnegocioTecnicoProvincia->buscarTecnicoProvincia($arrayParametros);
	    $combo = '<option value="" >Seleccione....</option>';
	    foreach ($consulta as $value) {
	        if (ucfirst($item) == ucfirst($value['provincia'])) {
	            $combo .= '<option value="' . $value['codigo_vue'] . '-'.$value['provincia'].'" selected>' . $value['provincia'] . '</option>';
	        } else {
	            $combo .= '<option value="' . $value['codigo_vue'] . '-'.$value['provincia'].'">' . $value['provincia'] . '</option>';
	        }
	    }
	    return $combo;
	}
	
	/**
	 * Combo de datos de tecnicos por provincia
	 *
	 * @param type $respuesta
	 * @return string
	 */
	public function comboModeloAdministrativo($estado = 'creado',$idModelo=null)
	{
	    $lnegocioModeloAdministrativo = new ModeloAdministrativoLogicaNegocio();
	    $consulta = $lnegocioModeloAdministrativo->buscarLista("estado in ('".$estado."') order by orden asc");
	    $combo = '<option value="" >Seleccione....</option>';
	    foreach ($consulta as $value) {
	        if ($value['id_modelo_administrativo'] == $idModelo) {
	            $combo .= '<option value="' . $value['id_modelo_administrativo'] .'" selected>' . $value['nombre_modelo'] . '</option>';
	        } else {
	            $combo .= '<option value="' . $value['id_modelo_administrativo'] .'">' . $value['nombre_modelo'] . '</option>';
	        }
	    }
	    return $combo;
	}
	/**
	 * Combo de datos detalle sanción
	 *
	 */
	public function comboDetalleSancion($dato = null)
	{
	    $combo = "";
	    $opt = array(
	        'Absuelto' => 'Absuelto',
	        'Sancionado' => 'Sancionado'
	    );
	    //$combo .= '<option value="" >Seleccione....</option>';
	    foreach ($opt as $value => $item) {
	        if (ucfirst($dato) == ucfirst($value)) {
	            $combo .= '<option value="' . $item . '" selected>' . $value . '</option>';
	        } else {
	            $combo .= '<option value="' . $item . '">' . $value . '</option>';
	        }
	    }
	    return $combo;
	}
	
	
}
