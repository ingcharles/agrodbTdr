<?php

 /**
 * Controlador Base
 *
 * Este archivo contiene métodos comunes para todos los controladores 
 *
 * @property  AGROCALIDAD
 * @author    Carlos Anchundia
 * @date      2020-08-04
 * @uses      BaseControlador
 * @package   CertificadoFitosanitario
 * @subpackage Controladores
 */
namespace Agrodb\CertificadoFitosanitario\Controladores;

session_start();

use Agrodb\Core\Comun;
use Agrodb\CertificadoFitosanitario\Modelos\CertificadoFitosanitarioLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\PuertosDestinoLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\PaisesPuertosTransitoLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\ExportadoresProductosLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\DocumentosAdjuntosLogicaNegocio;
use Agrodb\Catalogos\Modelos\UnidadesMedidasCfeLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;
 
class BaseControlador extends Comun
{
	public $itemsFiltrados = array();
	public $codigoJS = null;
	public $datosGeneralesCertificadoFitosanitario = null;
	public $paisPuertosDestino = null;
	public $paisesPuertosTransito = null;
	public $exportadoresProductos = null;
	public $exportadoresProductosRevisiones = null;
	public $documentosAdjuntos = null;
	public $detalleAnulaReemplaza = null;

	/**
	* Constructor
	*/
	function __construct($destinoUrl = true) {
	    if($destinoUrl){
	        parent::usuarioActivo();
	    }
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
	
	/////////////////////////////////////////////////////////////////////////////////////////////
	//////////////
	////////////////////////////////////////////////////////////////////////////////////////////
	
	/**
	 * Método para mostrar la informacion general del certificado fitosanitario
	 */
	public function construirDatosGeneralesCertificadoFitosanitario($arrayParametros, $proceso, $procesoReimpresion)
	{

	    $qDatosGeneralesCertificadoFitosanitario = new CertificadoFitosanitarioLogicaNegocio();
	    
	    $datosGeneralesCertificadoFitosanitario = $qDatosGeneralesCertificadoFitosanitario->buscar($arrayParametros['id_certificado_fitosanitario']);
	    
	    $this->datosGeneralesCertificadoFitosanitario = '<div data-linea="1">
                                                			<label for="codigo_certificado">Código Certificado: </label>' .
                                                			$datosGeneralesCertificadoFitosanitario->getCodigoCertificado()
                                                			. '</div>
                                                		<div data-linea="2">
                                                			<label for="tipo_certificado">Tipo de Solicitud: </label>' .
                                                			ucfirst($datosGeneralesCertificadoFitosanitario->getTipoCertificado())
                                                			. '</div>
                                                		<div data-linea="2">
                                                			<label for="idioma_certificado">Idioma: </label>' .
                                                			$datosGeneralesCertificadoFitosanitario->getNombreIdioma()
                                                			. '</div>
                                                		<hr/>
                                                		<div data-linea="3">
                                                			<label for="producto_organico">Producto Orgánico: </label>' .
                                                			$datosGeneralesCertificadoFitosanitario->getProductoOrganico()
                                                			. '</div>
                                                		<div data-linea="3">
                                                			<label for="id_provincia_origen">Provincia Origen: </label>' .
                                                			$datosGeneralesCertificadoFitosanitario->getNombreProvinciaOrigen()
                                                			. '</div>
                                                		<div data-linea="4">
                                                			<label for="id_medio_transporte">Medio de Transporte: </label>' .
                                                			$datosGeneralesCertificadoFitosanitario->getNombreMedioTransporte()
                                                			. '</div>
                                                		<div data-linea="4">
                                                			<label for="id_puerto_embarque">Puerto de Embarque: </label>' .
                                                			$datosGeneralesCertificadoFitosanitario->getNombrePuertoEmbarque()
                                                			. '</div>
                                                		<div data-linea="5">
                                                			<label for="fecha_embarque">Fecha de Embarque: </label>' .
                                                			date("Y-m-d", strtotime($datosGeneralesCertificadoFitosanitario->getFechaEmbarque()))
                                                			. '</div>
                                                		<div data-linea="5">
                                                			<label for="numero_viaje">Número de Viaje: </label>' .
                                                			($datosGeneralesCertificadoFitosanitario->getNumeroViaje() != "" ? $datosGeneralesCertificadoFitosanitario->getNumeroViaje() : "N/A")
                                                			. '</div>
                                                		<div data-linea="6">
                                                			<label for="nombre_marca">Nombre de Marcas: </label>' .
                                                			$datosGeneralesCertificadoFitosanitario->getNombreMarca()
                                                			. '</div>
                                                		<div data-linea="7">
                                                            <label for="informacion_adicional">Información Adicional del Envío: </label>';
                                                			$this->datosGeneralesCertificadoFitosanitario .= ($procesoReimpresion) ? '<input type="text" id="informacion_adicional" name="informacion_adicional" value="' . $datosGeneralesCertificadoFitosanitario->getInformacionAdicional() . '"
                                                			placeholder="Ingrese información adicional" maxlength="1025" />' : ((!$proceso) ? $datosGeneralesCertificadoFitosanitario->getInformacionAdicional() : '<input type="text" id="informacion_adicional_m" name="informacion_adicional_m" value="' . $datosGeneralesCertificadoFitosanitario->getInformacionAdicional() . '"
                                                			placeholder="Ingrese información adicional" maxlength="1025" />');
                                                		$this->datosGeneralesCertificadoFitosanitario .= '</div>
                                                		<hr/>
                                                		<div data-linea="8">
                                                			<label for="nombre_consignatario">Nombre del Consignatario: </label>';
                                                		$this->datosGeneralesCertificadoFitosanitario .= ($procesoReimpresion) ? '<input type="text" id="nombre_consignatario" name="nombre_consignatario" value="' . $datosGeneralesCertificadoFitosanitario->getNombreConsignatario() . '"
                                                			placeholder="Ejm: Juan Adrés Torres Mejía" maxlength="200" />' : ((!$proceso) ? $datosGeneralesCertificadoFitosanitario->getNombreConsignatario() : '<input type="text" id="nombre_consignatario_m" name="nombre_consignatario_m" value="' . $datosGeneralesCertificadoFitosanitario->getNombreConsignatario() . '"
                                                			placeholder="Ejm: Juan Adrés Torres Mejía" maxlength="200" />');
                                                		$this->datosGeneralesCertificadoFitosanitario .= '</div>
                                                		<div data-linea="9">
                                                			<label for="direccion_consignatario">Dirección del Consignatario: </label>';
                                                		$this->datosGeneralesCertificadoFitosanitario .= ($procesoReimpresion) ? '<input type="text" id="direccion_consignatario" name="direccion_consignatario" value="' . $datosGeneralesCertificadoFitosanitario->getDireccionConsignatario() . '"
                                                			placeholder="Ejm: Av. Vicente Rocafuerte" maxlength="200" />' : ((!$proceso) ? $datosGeneralesCertificadoFitosanitario->getDireccionConsignatario() : '<input type="text" id="direccion_consignatario_m" name="direccion_consignatario_m" value="' . $datosGeneralesCertificadoFitosanitario->getDireccionConsignatario() . '"
                                                			placeholder="Ejm: Av. Vicente Rocafuerte" maxlength="200" />');
                                                		$this->datosGeneralesCertificadoFitosanitario .= '</div>';
	}
	
	/**
	 * Método para listar puertos de destino del certificado fitosanitario
	 */
	public function construirDetallePuertoPaisDestino($arrayParametros, $nombrePaisDestino, $proceso)
	{
	    
	    $qPaisPuertosDestino = new PuertosDestinoLogicaNegocio();
	    
	    $paisPuertosDestino = $qPaisPuertosDestino->buscarLista($arrayParametros);
	    
	    $th = "";
	    $td = "";
	    
	    if($proceso){$th = '<th>Opción</th>';}
	    
	    $this->paisPuertosDestino = '<table id="detallePuertoPaisDestino" style="width: 100%">
    			<thead>
    				<tr>
    					<th>País</th>
    					<th>Puerto</th>'
        	        . $th .
        	        '</tr>
    			</thead>
    			<tbody>';
	        
	        foreach ($paisPuertosDestino as $item) {
	            
	            if($proceso){$td = '<td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarDetallePuertosDestino(' . $item['id_puerto_destino'] . '); return false;"/></td>';}
	            
	            $this->paisPuertosDestino .='
                        <tr id="' . $item['id_puerto_destino'] . '">
                            <td>' . ($nombrePaisDestino != '' ? $nombrePaisDestino : ''). '</td>
                            <td>' . ($item['nombre_puerto_pais_destino'] != '' ? $item['nombre_puerto_pais_destino'] : '') . '</td>'
                                . $td .
                                '</tr>';
	        }
	        
	        $this->paisPuertosDestino .= '</tbody>
    	</table>';
	        
	        $this->paisPuertosDestino;
	}
	
	/**
	 * Método para listar paises y puertos de transito del certificado fitosanitario
	 */
	public function construirDetallePaisPuertosTransito($arrayParametros, $proceso)
	{
	    
	    $qPaisesPuertosTransito = new PaisesPuertosTransitoLogicaNegocio();
	    $paisesPuertosTransito = $qPaisesPuertosTransito->buscarLista($arrayParametros);
	    	    
	    $th = "";
	    $td = "";
	    
	    if($proceso){$th = '<th>Opción</th>';}
	    
	    $this->paisesPuertosTransito = '<table id="detallePaisPuertoTransito" style="width: 100%">
    		<thead>
    			<tr>
    				<th>País</th>
    				<th>Puerto</th>
    				<th>Medio de Transporte</th>'
	        . $th .
	        '</tr>
    		</thead>
    		<tbody>';
	        
	        foreach ($paisesPuertosTransito as $item) {
	            
	            if($proceso){$td = '<td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarDetallePaisPuertosTransito(' . $item['id_pais_puerto_transito'] . '); return false;"/></td>';}
	            
	            $this->paisesPuertosTransito .='
                        <tr id="' . $item['id_pais_puerto_transito'] . '">
                            <td>' . ($item['nombre_pais_transito'] != '' ? $item['nombre_pais_transito'] : ''). '</td>
                            <td>' . ($item['nombre_puerto_transito'] != '' ? $item['nombre_puerto_transito'] : '') . '</td>
                            <td>' . ($item['nombre_medio_transporte_transito'] != '' ? $item['nombre_medio_transporte_transito'] : '') . '</td>'
                                . $td .
                                '</tr>';
                                
	        }
	        
	        $this->paisesPuertosTransito .= '
    		</tbody>
    	</table>';
	        
	        $this->paisesPuertosTransito;
	}
	
	/**
	 * Método para listar los exportadores y productos del certificado fitosanitario
	 */
	public function construirDetalleExportadoresProductos($arrayParametros, $proceso)
	{
	    
	    $qExportadoresProductos = new ExportadoresProductosLogicaNegocio();
	    $qUnidadesMedida = new UnidadesMedidasCfeLogicaNegocio();
	    $qDatosSitio = new OperadoresLogicaNegocio();
	    $exportadoresProductos = $qExportadoresProductos->buscarLista($arrayParametros);
	    
	    $th = "";
	    
	    if($proceso){$th = '<th>Opción</th>';}
	    
	    $this->exportadoresProductos = '<table id="detalleExportadoresProductos" style="width: 100%">
    			<thead>
    				<tr>
    					<th>Identificador</th>
    					<th>Razón Social</th>
    					<th>Producto</th>
                        <th>Código Orgánico</th>
    					<th>Cantidad Comercial</th>
    					<th>Peso Bruto</th>
    					<th>Peso Neto</th>
                        <th>Centro de Acopio</th>
    					<th>Inspección</th>
    					<th>Estado</th>
    					<th>Observación</th>'
	        . $th .
	        '<th></th></tr>
    			</thead>
    			<tbody>';
	        
	        foreach ($exportadoresProductos as $item) {
	            
	            $banderaEditar = true;
	            $tdBorrar = "";
	            $onclikBorrar = "";
	            $banderaMostrar = true;
	            
	            switch($item['estado_exportador_producto']){
	                
	                case 'ConfirmarInspeccion':
	                    $estadoCertificado = "Confirmar inspección";
	                    break;
	                case 'documental':
	                    $estadoCertificado = "Revisión documental";
	                    break;
	                case 'inspeccion':
	                    $estadoCertificado = "Inspección";
	                    break;
	                case 'verificacion':
	                    $estadoCertificado = "Verificación";
	                    break;
	                case 'pago':
	                    $estadoCertificado = "Pago";
	                    break;
	                case 'generarOrden':
	                    $estadoCertificado = "Por generar orden";
	                    break;
	                case 'PorReemplazar':
	                    $estadoCertificado = "Por reemplazar";
	                    break;
	                default:
	                    $estadoCertificado = $item['estado_exportador_producto'];
	            }
	            
	            if($item['estado_exportador_producto'] == "Rechazado" || $item['estado_exportador_producto'] == "InspeccionAprobada"){
	                $banderaEditar = false;
	            }else if($item['estado_exportador_producto'] == "Creado" || $item['estado_exportador_producto'] == "Subsanacion"){
                    $onclikBorrar = "fn_eliminarDetalleExportadoresProductos('" . $item['id_exportador_producto'] . "'); return false;";
	            }else{
	                $banderaEditar = false;
	            }
	            
	            if($proceso){
	                if($banderaEditar){
	                    $classE = "icono";
	                    $tdBorrar = '<td class="borrar"><button type="button" name="eliminar" class="' . $classE . '" onclick="'.  $onclikBorrar . '"/></td>';
	                }else{
	                    $tdBorrar = '<td></td>';
	                }
	            }
	            
	            if($banderaMostrar){
	                	                	                
	                $cantidadComercial = $qUnidadesMedida->buscar($item['id_unidad_cantidad_comercial']);
	                $codigoCantidadComercial = $cantidadComercial->getCodigo();
	                
	                if(isset($item['id_unidad_peso_bruto'])){
	                    $pesoBruto = $qUnidadesMedida->buscar($item['id_unidad_peso_bruto']);
	                    $codigoPesoBruto = $pesoBruto->getCodigo();
	                }
	                
	                $pesoNeto = $qUnidadesMedida->buscar($item['id_unidad_peso_neto']);
	                $codigoPesoNeto = $pesoNeto->getCodigo();
	                	                
	                if(isset($item['id_area'])){
	                    $datosSitio = $qDatosSitio->obtenerDatosSitioPorIdArea($item['id_area']);
	                    $nombreSitio = $datosSitio->current()->nombre_lugar;
	                    $nombreProvincia = $datosSitio->current()->provincia;
	                }else{
	                    $nombreSitio = 'N/A';
	                    $nombreProvincia = 'N/A';
	                }
	                
	            
	            $this->exportadoresProductos .='
                        <tr id="' . $item['id_exportador_producto'] . '">
                            <td>' . ($item['identificador_exportador'] != '' ? $item['identificador_exportador'] : ''). '</td>
                            <td>' . ($item['razon_social_exportador'] != '' ? $item['razon_social_exportador'] : '') . '</td>
                            <td>' . ($item['nombre_producto'] != '' ? $item['nombre_producto'] : '') . '</td>
							<td>' . ($item['certificacion_organica'] != '' ? $item['certificacion_organica'] : 'N/A') . '</td>
                            <td align="center">' . ($banderaEditar && $proceso ? ($item['cantidad_comercial'] != '' ? '<input name="cantidad_comercial" id="cantidad_comercial'. $item['id_exportador_producto'] .'" value="' . $item['cantidad_comercial'] . '" type="text" class="validacionProducto" size="2" onchange="verificarCantidades(' . $item['id_exportador_producto'] . ', this.value, this.id, this.name)">' : '') : $item['cantidad_comercial'] . ' ' . $codigoCantidadComercial ) . '</td>
                            <td align="center">' . ($banderaEditar && $proceso ? ($item['peso_bruto'] != '' ? '<input name="peso_bruto" id="peso_bruto'. $item['id_exportador_producto'] .'" value="' . $item['peso_bruto'] . '" type="text" class="validacionProducto" size="2" onchange="verificarCantidades(' . $item['id_exportador_producto'] . ', this.value, this.id, this.name)">' : 'N/A') : ($item['peso_bruto'] != ''? $item['peso_bruto'] . ' ' . $codigoPesoBruto :'N/A')) . '</td>
							<td align="center">' . ($banderaEditar && $proceso ? ($item['peso_neto'] != '' ? '<input name="peso_neto" id="peso_neto'. $item['id_exportador_producto'] . '" value="' . $item['peso_neto'] . '" type="text" class="validacionProducto" size="2" onchange="verificarCantidades(' . $item['id_exportador_producto'] . ', this.value, this.id, this.name)">' : '') : $item['peso_neto'] . ' ' . $codigoPesoNeto) . '</td>
                            <td>' . ($item['nombre_area'] != '' ? $item['nombre_area'] . ' ' . $item['codigo_centro_acopio'] : 'N/A') . '</td>
                            <td>' . ($item['fecha_inspeccion'] != '' ? $item['fecha_inspeccion'] : 'N/A') . ' ' . ($item['hora_inspeccion'] != '' ? $item['hora_inspeccion'] : 'N/A') . '</td>
                            <td>' . ($estadoCertificado != '' ? $estadoCertificado : 'N/A') . '</td>
                            <td>' . ($item['observacion_revision'] != '' ? $item['observacion_revision'] : 'N/A') . '</td>'
                                . $tdBorrar .
                            '<td><button id="' . $item['id_exportador_producto'] . '" onclick="adicionalProducto(this.id)">Ver más</button></td>
                            </tr>
                            <tr id="resultadoInformacionProducto'.$item['id_exportador_producto'].'" style="display:none;">
                            <td colspan="5">
                                <label>Tipo tratamiento: </label>' . ($item['nombre_tipo_tratamiento'] != "" ? $item['nombre_tipo_tratamiento'] : 'N/A') .
                                '</br><label>Duración tratamiento: </label>' . ($item['duracion_tratamiento'] != "" ? $item['duracion_tratamiento'] . ' ' . $item['nombre_unidad_duracion'] : 'N/A') .
                                '</br><label>Fecha tratamiento: </label>' . ($item['fecha_tratamiento'] != "" ? date('Y-m-d',strtotime($item['fecha_tratamiento'])) : 'N/A') .
                                '</br><label>Producto químico: </label>' . ($item['producto_quimico'] != "" ? $item['producto_quimico'] : 'N/A') .
                                '</td>
                            <td colspan="4">
                                <label>Tratamiento: </label>' . ($item['nombre_tratamiento'] != "" ? $item['nombre_tipo_tratamiento'] : 'N/A') .
                                '</br><label>Temperatura: </label>' . ($item['temperatura_tratamiento'] != "" ? $item['temperatura_tratamiento'] . ' ' . $item['nombre_unidad_temperatura'] : 'N/A') .
                                '</br>'.
                                '</br><label>Concentración: </label>' . ($item['concentracion_tratamiento'] != "" ? $item['concentracion_tratamiento'] . ' ' . $item['nombre_unidad_concentracion'] : 'N/A') .
                                '</td>
                            </tr>
                            <tr id="resultadoInformacionProductoSitio'.$item['id_exportador_producto'].'" style="display:none;">
                                <td colspan="4">
                                <label>Sitio: </label>'
                                . $nombreSitio . 
                                '</br><label>Provincia: </label>' . $nombreProvincia .
                                '</td>
                            </tr>';
	            
	           }
	           
	        }
	        
	        $this->exportadoresProductos .= '</tbody>
    		</table>';
	        
	        $this->exportadoresProductos;
	}
	
	/**
	 * Método para contruir el detalle de documentos
	 */
	public function construirDetalleDocumentosAdjuntos($arrayParametros, $proceso)
	{
	    
	    $idSolicitud = $arrayParametros['id_certificado_fitosanitario'];
	    $estadoCertificado = $arrayParametros['estado_certificado'];
	    
	    if($estadoCertificado == "Aprobado"){
            $tipo = "'Documento de respaldo', 'Certificado Fitosanitario', 'Anexo Certificado'";
	    }else{
	        $tipo = "'Documento de respaldo'";
	    }
	    
	    $arrayParametros = array(
	        'id_solicitud' => $idSolicitud,
	        'tipo_adjunto' => $tipo
	    );
	    
	    $qDocumentosAdjuntos = new DocumentosAdjuntosLogicaNegocio();
	    $documentosAdjuntos = $qDocumentosAdjuntos->obtenerDocumentosAdjuntosXSolicitud($arrayParametros);
	    
	    $td = "";
	    $th = "";
	    
	    $this->documentosAdjuntos = '';
	    
	    if($proceso){$th = '<th>Opción</th>';}
	    
	    $this->documentosAdjuntos = '<table id="documentoAdjunto" style="width: 100%;">
                                    <thead>
    								    <tr>
    										<th>Nombre</th>
    										<th colspan="2">Enlace</th>'
	                                        . $th . '
    									</tr>
                                    </thead>
                                    <tbody>';
	        
	        foreach ($documentosAdjuntos as $item) {
	            
				$rutaAdjunto = "";
				$rutaEnlaceAdjunto = "";
				
	            if($proceso){$td = '<td class="borrar"><button type="button" name="eliminar" class="icono" onclick="fn_eliminarDocumentoAdjunto(' . $item['id_documento_adjunto'] . ')"></button></td>';}
	            
				if($item['ruta_adjunto'] != "" || $item['ruta_adjunto'] != null){
					$rutaAdjunto = '<a href="' . $item['ruta_adjunto'] . '" target="_blank">Archivo</a>';
				}
				
				if($item['ruta_enlace_adjunto'] != "" || $item['ruta_enlace_adjunto'] != null){
					$rutaEnlaceAdjunto = '<a href="' . $item['ruta_enlace_adjunto'] . '" target="_blank">Archivo</a>';
				}							
  
								
	            $this->documentosAdjuntos .= '<tr id="' . $item['id_documento_adjunto'] . '">
        									<td>' . $item['tipo_adjunto'] . '</td>
        									<td>' .
        										$rutaAdjunto
        									. '</td>
											<td>' .
        										$rutaEnlaceAdjunto
        									. '</td>'
                                            . $td . '
        						 		   </tr>';
	        }
	        
	        $this->documentosAdjuntos .= '</tbody></table>';
	        
	        $this->documentosAdjuntos;
	        
	}
	
	/**
	 * Método para listar los exportadores y productos de la solicitud
	 */
	public function construirDetalleExportadoresProductosRevisiones($arrayParametros)
	{
	    $qExportadoresProductos = new ExportadoresProductosLogicaNegocio();
	    $qUnidadesMedida = new UnidadesMedidasCfeLogicaNegocio();
	    $qDatosSitio = new OperadoresLogicaNegocio();
	    
	    $listaDestalles = $qExportadoresProductos->obtenerExportadoresProductosPorIdSolicitud($arrayParametros);
	        
	    $this->exportadoresProductosRevisiones = '<table style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Identificador</th>
                                                <th>Razón Social</th>
                                                <th>Producto</th>
                                                <th>Código Orgánico</th>
                                                <th>Cantidad Comercial</th>
                                                <th>Peso Bruto</th>
                                                <th>Peso Neto</th>
                                                <th>Centro de Acopio</th>
                                                <th></th>
                                            </tr>
                                        </thead>';
	   
	    foreach ($listaDestalles as $fila) {
	        
	        $cantidadComercial = $qUnidadesMedida->buscar($fila['id_unidad_cantidad_comercial']);
	        $codigoCantidadComercial = $cantidadComercial->getCodigo();
	        
	        if(isset($fila['id_unidad_peso_bruto'])){
    	        $pesoBruto = $qUnidadesMedida->buscar($fila['id_unidad_peso_bruto']);
    	        $codigoPesoBruto = $pesoBruto->getCodigo();
	        }
	        
	        $pesoNeto = $qUnidadesMedida->buscar($fila['id_unidad_peso_neto']);
	        $codigoPesoNeto = $pesoNeto->getCodigo();
	        
	        if(isset($fila['id_area'])){
	            $datosSitio = $qDatosSitio->obtenerDatosSitioPorIdArea($fila['id_area']);
	            $nombreSitio = $datosSitio->current()->nombre_lugar;
	            $nombreProvincia = $datosSitio->current()->provincia;
	        }else{
	            $nombreSitio = 'N/A';
	            $nombreProvincia = 'N/A';
	        }
	        	        
	        $this->exportadoresProductosRevisiones .=
	        '<tr>
                        <td>' . ($fila['identificador_exportador'] != '' ? $fila['identificador_exportador'] : '') . '</td>
                        <td>' . ($fila['razon_social_exportador'] != '' ? $fila['razon_social_exportador'] : '') . '</td>
                        <td>' . ($fila['nombre_subtipo_producto'] != '' ? $fila['nombre_subtipo_producto'] : '') . ' - ' . ($fila['nombre_producto'] != '' ? $fila['nombre_producto'] : '') . '</td>
                        <td>' . ($fila['certificacion_organica'] != '' ? $fila['certificacion_organica'] : 'N/A') . '</td>
                        <td>' . ($fila['cantidad_comercial'] != '' ? $fila['cantidad_comercial'] . ' ' .$codigoCantidadComercial : '') . '</td>
                        <td>' . ($fila['peso_bruto'] != '' ? $fila['peso_bruto'] . ' ' . $codigoPesoBruto : 'N/A') . '</td>
                        <td>' . ($fila['peso_neto'] != '' ? $fila['peso_neto'] . ' ' . $codigoPesoNeto : '') . '</td>
                        <td>' . ($fila['nombre_area'] != '' ? $fila['nombre_area'] . ' ' .$fila['codigo_centro_acopio'] : 'N/A') . '</td>
                        <td><button id="' . $fila['id_exportador_producto'] .'" onclick="adicionalProducto(this.id)">Ver más</button></td>
                    </tr>
                    <tr id="resultadoInformacionProducto'.$fila['id_exportador_producto'].'" style="display:none;">
                        <td colspan="5">
                            <label>Tipo tratamiento: </label>' . ($fila['nombre_tipo_tratamiento'] != "" ? $fila['nombre_tipo_tratamiento'] : 'N/A') .
                            '</br><label>Duración tratamiento: </label>' . ($fila['duracion_tratamiento'] != "" ? $fila['duracion_tratamiento'] . ' ' . $fila['nombre_unidad_duracion'] : 'N/A') .
                            '</br><label>Fecha tratamiento: </label>' . ($fila['fecha_tratamiento'] != "" ? date('Y-m-d',strtotime($fila['fecha_tratamiento'])) : 'N/A') .
                            '</br><label>Producto químico: </label>' . ($fila['producto_quimico'] != "" ? $fila['producto_quimico'] : 'N/A') .
                            '</td>
                        <td colspan="4">
                            <label>Tratamiento: </label>' . ($fila['nombre_tratamiento'] != "" ? $fila['nombre_tipo_tratamiento'] : 'N/A') .
                            '</br><label>Temperatura: </label>' . ($fila['temperatura_tratamiento'] != "" ? $fila['temperatura_tratamiento'] . ' ' . $fila['nombre_unidad_temperatura'] : 'N/A') .
                            '</br>'.
                            '</br><label>Concentración: </label>' . ($fila['concentracion_tratamiento'] != "" ? $fila['concentracion_tratamiento'] . ' ' . $fila['nombre_unidad_concentracion'] : 'N/A') .
                            '</td>
                    </tr>
                    <tr id="resultadoInformacionProductoSitio'.$fila['id_exportador_producto'].'" style="display:none;">
                        <td colspan="4">
                        <label>Sitio: </label>'
                        . $nombreSitio . 
                        '</br><label>Provincia: </label>' . $nombreProvincia .
                        '</td>
                    </tr>';
	    }
	    
	    $this->exportadoresProductosRevisiones .= '</table>';
	    
	    return $this->exportadoresProductosRevisiones;
	}
	
	/**
	 * Método para visualizar el motivo de anulad y remmplaza de una solicitud
	 */
	public function construirDetalleAnulaReemplaza($arrayParametros)
	{

	    $idCertificadoReemplazo = $arrayParametros['id_certificado_reemplazo'];
	    $motivoReemplazo = $arrayParametros['motivo_reemplazo'];
	    
	    $qCertificadoFitosanitarioReemplazo = new CertificadoFitosanitarioLogicaNegocio();
	    
	    $certificadoFitosanitarioReemplazo = $qCertificadoFitosanitarioReemplazo->buscar($idCertificadoReemplazo);
	    
	    $codigoCertificado = $certificadoFitosanitarioReemplazo->getCodigoCertificado();
	    
	    $this->detalleAnulaReemplaza .= '<fieldset>
                                <legend>Anula y Reemplaza</legend>
                                <div data-linea="1">
                                    <label for="motivo_reemplazo">Motivo de Anula y Reemplaza: </label>' . $motivoReemplazo . '
                                </div>
                                <div data-linea="2">
                                    Esta solicitud es para un reemplazo del certificado ' . $codigoCertificado . '
                                </div>
                                </fieldset>';
	    
	    return $this->detalleAnulaReemplaza;
	    
	}
		
}
