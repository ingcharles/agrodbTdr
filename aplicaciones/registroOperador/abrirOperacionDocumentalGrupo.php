<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';
require_once '../../clases/ControladorVigenciaDocumentos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cr = new ControladorRegistroOperador();
$cvd = new ControladorVigenciaDocumentos();
$cac = new ControladorAdministrarCatalogos();

$operaciones = ($_POST['elementos']==''?$_POST['id']:$_POST['elementos']);
$idGrupoOperaciones = explode(",",($_POST['elementos']==''?$_POST['id']:$_POST['elementos']));

$identificadorInspctor =  $_SESSION['usuario'];

$qOperadorSitio = $cr->obtenerOperadorSitioInspeccion($conexion,$operaciones);
$operadorSitio = pg_fetch_assoc($qOperadorSitio);

$qOperacion=$cr->abrirOperacionXid($conexion, $operaciones);
$operacion = pg_fetch_assoc($qOperacion);

$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
$idSolicitud = $operacion['id_operacion'];															  
$idHistoricoOperacion = $operacion['id_historial_operacion'];
$idVigenciaDocumento = $operacion['id_vigencia_documento'];

$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
$historialOperacion = pg_fetch_assoc($qHistorialOperacion);

$nombreOpcion=$_POST['opcion'];

$contador = 0;
$banderaProductoCertificado = false;
$bandera = false;
$banderaValidacionAI = "";
$banderaImportador = false;
$formularioIndustriaLactea = "";
$formularioLaboratorio = "";

$idflujoOPeracion = pg_fetch_assoc($cr->obtenerIdFlujoXOperacion($conexion, $operaciones));
$idFlujoActual = pg_fetch_assoc($cr->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOPeracion['id_flujo_operacion'], 'documental'));

if($idFlujoActual['estado_alterno']!= ''){
	$subsanacion = '<option value="'.$idFlujoActual['estado_alterno'].'">Subsanación</option>';
}

$qcodigoTipoOperacion= $cc->obtenerCodigoTipoOperacion($conexion, $operaciones);
$opcionArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
$idArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');

$qRepresentante = $cr->consultarDatosRepresentanteTecnicoPorOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);
$formularioPlanificacionInspeccion="";  

switch ($idArea){
    
    case 'AI':
        
        $identificadorArea = pg_fetch_result($cr->obtenerAreaXIdOperacion($conexion, $operaciones), 0, 'id_area');
        
        $qUnidadMedida = $cc->listarUnidadesMedida($conexion);
        $qLaboratoriosLeche = $cac -> listarItemsPorCodigo($conexion, 'COD-LABOR-IA','1');
        switch ($opcionArea){
            case 'ACO':
                
                //obtener datos centro acopio x id sitio y tipo operacion e id_operador_tipo_operacion
                
                $qCentroAcopio = $cr->listarCentroAcopioXIdAreaXidTipoOperacion($conexion, $identificadorArea, $operacion['id_tipo_operacion'], $idOperadorTipoOperacion, 'activo');
                $centroAcopio = pg_fetch_assoc($qCentroAcopio);
                
                $bandera = true;
                
                $formularioAcopiadorLeche = "";
                
                $formularioAcopiadorLeche .= '<form id="declararInformacionCentroAcopio" data-rutaAplicacion="registroOperador">
				    <input type="hidden" class="idCentroAcopio" name="idCentroAcopio" value="' . $centroAcopio['id_centro_acopio'] . '" />
				    <input type="hidden" class="idArea" name="idArea" value="' . $identificadorArea . '" />
				    <input type="hidden" class="idTipoOperacion" name="idTipoOperacion" value="' . $operacion['id_tipo_operacion'] . '" />
				        <fieldset>
				        <legend>Información del Centro de Acopio</legend>
				        <div data-linea="1">
				        <label>*Capacidad Instalada: </label><input type="text" id="capacidadInstalada" name="capacidadInstalada" value="' . $centroAcopio['capacidad_instalada'] . '" disabled="disabled" />
				            </div>
				            <div data-linea="1">
				            <label for="unidadMedida">*Unidad: </label>
				            <select id="unidadMedida" name="unidadMedida" disabled="disabled" >
				            <option value="">Seleccione...</option>';
                
                while ($unidadMedida = pg_fetch_assoc($qUnidadMedida)) {
                    $formularioAcopiadorLeche .= '<option value="' . $unidadMedida['codigo'] . '">' . $unidadMedida['nombre'] . '</option>';
                }
                
                $formularioAcopiadorLeche .= '</select>
							</div>
							<div data-linea="2">
								<label>*Número de trabajadores: </label><input type="text" id="numeroTrabajadores" name="numeroTrabajadores" value="' . $centroAcopio['numero_trabajadores'] . '" disabled="disabled" />
							</div>
							<div data-linea="3">
								<label for="laboratorio">*Laboratorio Legalmente Constituido: </label>
					            <select id="laboratorio" name="laboratorio" disabled="disabled" >
					            <option value="">Seleccione...</option>';
                while ($laboratorio = pg_fetch_assoc($qLaboratoriosLeche)) {
                    $formularioAcopiadorLeche .= '<option value="' . $laboratorio['id_item'] . '">' . $laboratorio['nombre'] . '</option>';
                }
                
                $formularioAcopiadorLeche .= '</select>
							</div>
							<div data-linea="4">
								<label>*Número de proveedores: </label><input type="text" id="numeroProveedores" name="numeroProveedores" value="' . $centroAcopio['numero_proveedores'] . '" disabled="disabled" />
							</div>
							<div data-linea="4">
								<label for="perteneceMag">*Pertenece al MAG: </label>
					            <select id="perteneceMag" name="perteneceMag" disabled="disabled" >
					            <option value="">Seleccione...</option>
					               <option value="SI">SI</option>
					                <option value="NO">NO</option>
					            </select>
							</div>
							<div data-linea="5">
								<label>Horario de recepción matutina:</label> <input type="text" id="horaRecoleccionManiana" name="horaRecoleccionManiana" data-inputmask="mask: 99:99" value="' . $centroAcopio['hora_recoleccion_maniana'] . '" disabled="disabled" />
							</div>
							<div data-linea="5">
								<label>Horario de recepción vespertina:</label> <input type="text" id="horaRecoleccionTarde" name="horaRecoleccionTarde" data-inputmask="mask: 99:99" value="' . $centroAcopio['hora_recoleccion_tarde'] . '" disabled="disabled" />
							</div>
								    
						</fieldset>
						<div>
							<button id="modificar" type="button" class="editar">Modificar</button>
							<button id="actualizar" type="submit" class="guardar" disabled="disabled" disabled="disabled">Actualizar</button>
						</div>
								    
					</form>';
                
            break;
                
            case 'MDT':
               
                $qTipoTanque = $cac -> listarItemsPorCodigo($conexion, 'COD-TANQU-IA','1');
                
                //obtener recoleccion x id sitio y tipo operacion e id_operador_tipo_operacion
                
                $qDatosVehiculo = $cr->listarDatosVehiculoXIdAreaXidTipoOperacion($conexion, $identificadorArea, $operacion['id_tipo_operacion'], $idOperadorTipoOperacion, 'activo');
                $datosVehiculo = pg_fetch_assoc($qDatosVehiculo);
                
                $bandera = true;
                
                $formularioRecolectorLeche .= "";
                
                $formularioRecolectorLeche .= '<form id="declararDatosVehiculo" data-rutaAplicacion="registroOperador" >
				<input type="hidden" class="idDatoVehiculo" name="idDatoVehiculo" value="' . $datosVehiculo['id_dato_vehiculo'] . '" />
				    <input type="hidden" class="idArea" name="idArea" value="' . $identificadorArea . '" />
                    <input type="hidden" id="opcion" name="opcion" value="" />
				    <input type="hidden" class="idTipoOperacion" name="idTipoOperacion" value="' . $operacion['id_tipo_operacion'] . '" />
				        <fieldset>
				        <legend>Datos del Vehículo</legend>
				        <div data-linea="1"><label for="placa">*Placa: </label>
						'.$datosVehiculo['placa_vehiculo'];
		   
			 $formularioRecolectorLeche .= '</div>
					 <div data-linea="1">
						 <label for="marca">*Marca: </label>
						 '.$datosVehiculo['nombre_marca_vehiculo'];
			 
			 $formularioRecolectorLeche .= '</div>
					 <div  data-linea="2">
						 <label for="modelo">*Modelo: </label>
					  '.$datosVehiculo['nombre_modelo_vehiculo'];
		   
			 $formularioRecolectorLeche .= '</div>
					 <div data-linea="2">
						 <label for="clase">*Clase: </label>
						 '.$datosVehiculo['nombre_clase_vehiculo'];
			 
			 $formularioRecolectorLeche .= '</div>
					 <div  data-linea="3">
						 <label for="color">*Color: </label>
						 '.$datosVehiculo['nombre_tipo_vehiculo'];
			 $formularioRecolectorLeche .= '</div>
						 <div id="tipo" data-linea="3">
							 <label for="tipo">*Tipo: </label>
							 '.$datosVehiculo['nombre_tipo_vehiculo'];
			 
		  
	 
			 while ($tipoTanque = pg_fetch_assoc($qTipoTanque)) {
				 if($datosVehiculo['id_tipo_tanque_vehiculo'] == $tipoTanque['id_item'])
				 $valortipotanque=$tipoTanque['nombre'].'<br>';
				// $formularioRecolectorLeche .= '<option value="' . $tipoTanque['id_item'] . '">' . $tipoTanque['nombre'] . '</option>';
			 }
 
			 $formularioRecolectorLeche .= '</div>
			 <div id="tipoTanque" data-linea="4">
				 <label for="tipoTanque">*tipoTanque: </label>
				 '.$valortipotanque;
 
			$formularioRecolectorLeche .= '</select>
					 </div>
					 <div data-linea="4">
						 <label for="anio">*Año: </label>
						 '.$datosVehiculo['anio_vehiculo'].'
									 
					 </div>
					 <div data-linea="5">
						 <label>*Capacidad instalada: </label>
						 <input	type="text" id="capacidadInstalada" name="capacidadInstalada" value="'.$datosVehiculo['capacidad_vehiculo'].'" disabled />
					 </div>
					 <div data-linea="5">
						 <label for="unidadMedidaVehiculo">*Unidad: </label>
						 <select id="unidadMedidaVehiculo" name="unidadMedidaVehiculo" disabled >
						 <option value="">Seleccione...</option>';
                while ($unidadMedida = pg_fetch_assoc($qUnidadMedida)) {
                    $formularioRecolectorLeche .= '<option value="' . $unidadMedida['codigo'] . '">' . $unidadMedida['nombre'] . '</option>';
                }
                $formularioRecolectorLeche .= '</select>
                		</div>
                		<div data-linea="6">
                			<label>*Recolección - Hora Inicio:</label> <input type="time" id="horaInicioRecoleccion" name="horaInicioRecoleccion" placeholder="06:30" data-inputmask="mask: 99:99" value="'.$datosVehiculo['hora_inicio_recoleccion'].'" disabled />
                		</div>
                		<div data-linea="6">
                			<label>*Recolección - Hora Fin:</label> <input type="time" id="horaFinRecoleccion" name="horaFinRecoleccion" placeholder="08:30" data-inputmask="mask: 99:99" value="'.$datosVehiculo['hora_fin_recoleccion'].'" disabled />
                		</div>
                	</fieldset>
                			    
                		<div>
						<button id="modificar" type="button" class="editar">Modificar</button>
						<button id="actualizar" type="submit" class="guardar" disabled="disabled" disabled="disabled">Actualizar</button>
						</div>
                			    
					</form>';
                
                break;
            case 'MDC':
                
                
                $qTipoTanque = $cac -> listarItemsPorCodigo($conexion, 'COD-TANQU-IA','1');
                
                //obtener recoleccion x id sitio y tipo operacion e id_operador_tipo_operacion
                
                $qDatosVehiculo = $cr->listarDatosVehiculoXIdAreaXidTipoOperacion($conexion, $identificadorArea, $operacion['id_tipo_operacion'], $idOperadorTipoOperacion, 'activo');
                $datosVehiculo = pg_fetch_assoc($qDatosVehiculo);
                
                $bandera = true;
                
                $formularioRecolectorLeche .= "";
                
                $formularioRecolectorLeche .= '<form id="declararDatosVehiculo" data-rutaAplicacion="registroOperador" >
				<input type="hidden" class="idDatoVehiculo" name="idDatoVehiculo" value="' . $datosVehiculo['id_dato_vehiculo'] . '" />
				    <input type="hidden" class="idArea" name="idArea" value="' . $identificadorArea . '" />
                    <input type="hidden" id="opcion" name="opcion" value="" />
                    <input type="hidden" name="carnicos" id="carnicos" value="ok"/>
                    <input type="hidden" name="servicio" id="servicio" value="' . $datosVehiculo['servicio'] . '"/>
				    <input type="hidden" class="idTipoOperacion" name="idTipoOperacion" value="' . $operacion['id_tipo_operacion'] . '" />
				        <fieldset>
				        <legend>Datos del Vehículo</legend>';
			   
				      
               
				$formularioRecolectorLeche .= '
                		<div data-linea="1">
                			<label>Registro de contenedor incluido la placa del vehículo: </label>'.$datosVehiculo['registro_contenedor_vehiculo']; 

               $formularioRecolectorLeche .= '</div>
                		<div data-linea="2">
                			<label>Placa: </label>'.$datosVehiculo['placa_vehiculo'];
                            
                $formularioRecolectorLeche .= '</div>
                		<div  data-linea="2">
                			<label for="marca">Marca: </label>'.$datosVehiculo['nombre_marca_vehiculo'];
 
                $formularioRecolectorLeche .= '</div>
                		<div  data-linea="3">
                			<label for="modelo">Modelo: </label>
							'.$datosVehiculo['nombre_modelo_vehiculo'];

                $formularioRecolectorLeche .= '</div>
                       <div data-linea="3">
                			<label for="clase">Clase: </label>'.$datosVehiculo['nombre_clase_vehiculo'];
                            
				$formularioRecolectorLeche .= '</div>
							<div data-linea="4">
								<label for="color">Color: </label>'.$datosVehiculo['nombre_color_vehiculo'];

				$formularioRecolectorLeche .= '</div>
								<div data-linea="4">
									<label for="tipo">Tipo: </label>'.$datosVehiculo['nombre_tipo_vehiculo'];

				$formularioRecolectorLeche .= '</div>
									<div data-linea="5">
										<label for="servicio">Servicio: </label>'.$datosVehiculo['servicio'];
	

                $formularioRecolectorLeche .= '</div>
                		
                        <input type="hidden" id="tipoTanque" name="tipoTanque"  value="'.$datosVehiculo['id_tipo_tanque_vehiculo'].'"/>
                		<div data-linea="5">
                			<label>*Capacidad instalada: </label>
                			<input	type="text" id="capacidadInstalada" name="capacidadInstalada" value="'.$datosVehiculo['capacidad_vehiculo'].'" disabled />
                		</div>
                		<div data-linea="6">
                			<label for="unidadMedidaVehiculo">*Unidad: </label>
                            <select id="unidadMedidaVehiculo" name="unidadMedidaVehiculo" disabled >
                            <option value="">Seleccione...</option>';
                            while ($unidadMedida = pg_fetch_assoc($qUnidadMedida)) {
                                $formularioRecolectorLeche .= '<option value="' . $unidadMedida['codigo'] . '">' . $unidadMedida['nombre'] . '</option>';
                            }
                $formularioRecolectorLeche .= '</select>
                		</div>
                		
                		<div data-linea="6">
                			<label>*Tipo de contenedor: </label>
                			<input	type="text" id="tipoContenedor" name="tipoContenedor" value="'.$datosVehiculo['tipo_contenedor'].'" disabled />
                		</div>
                        <div data-linea="7">
                			<label>*Características del contenedor: </label>
                			<input	type="text" id="caracteristicaContenedor" name="caracteristicaContenedor" value="'.$datosVehiculo['caracteristica_contenedor'].'" disabled />
                		</div>             		
                	</fieldset>
                			    
                		<div>
						<button id="modificar" type="button" class="editar">Modificar</button>
						<button id="actualizar" type="submit" class="guardar" disabled="disabled" disabled="disabled">Actualizar</button>
						</div>
                			    
					</form>';
           break;
                
            case 'INL':
            	$formularioIndustriaLactea = '<fieldset>
												<legend>Vigencia documento</legend>
												<div data-linea="1">
													<label for="marca">Fecha caducidad: </label>
													<input type="text" id="fechaCaducidad" name="fechaCaducidad" readonly/>
												</div>
											</fieldset>';
            break;
        }
	break;
    case 'IAV':
    case 'IAP':
    case 'IAF':
    case 'CGRIA':
    	switch ($opcionArea){
    		case 'ALM':
    			$fechaInicio = pg_fetch_result($cr->obtenerMinimoFechaPorIdentificador($conexion, 'Almacenista', $operadorSitio['id_sitio'], $operacion['identificador_operador']),0,'fecha_aprobacion');
    			setlocale(LC_ALL,"es_ES","esp");
    			$fechaInicio = ($fechaInicio == '' ? date("Y-m-d"): $fechaInicio);
    			$fechaInicio = strftime("%d de %B de %Y", strtotime($fechaInicio));
    			break;
    		default:
    			$fechaInicio = pg_fetch_result($cr->obtenerMinimoFechaPorIdentificador($conexion, 'Empresas', '0', $operacion['identificador_operador']),0,'fecha_aprobacion');
    			setlocale(LC_ALL,"es_ES","esp");
    			$fechaInicio = ($fechaInicio == '' ? date("Y-m-d"): $fechaInicio);
    			$fechaInicio = strftime("%d de %B de %Y", strtotime($fechaInicio));
    	}
    	break;
		
// control cambios material reproductivo
	case 'SA':

		switch ($opcionArea){

			case 'PMR':
			case 'CPM':
			case 'DMR':
			case 'AMR':
			case 'OEC':			

				$formularioPlanificacionInspeccion='<fieldset>
				<legend>Planificación inspección</legend>
				<div data-linea="1">
						<label>Nombre del técnico que realiza la inspección</label>
						<input type="text" id="tecnicoInspeccion" name="tecnicoInspeccion" '. ($opcionArea=='OEC'?'value="'.$_SESSION['datosUsuario'].'" readonly="readonly"':'') . '/>
				</div>
				<div data-linea="2">
						<label>Fecha de inspección</label>
						<input type="text" id="fechaInspeccion" name="fechaInspeccion" readonly/>
				</div>
				<div data-linea="3">
						<label>Hora de inspección</label>
						<input type="text" id="horaInspeccion" name="horaInspeccion" placeholder="10:30" data-inputmask="'."'".'mask'."'".': '."'".'99:99'."'".'"/>
				</div>
			</fieldset>';

			break;
		}			
			
	break;

// fin control cambios material reproductivo

	case 'LT':
	
		switch ($opcionArea){
			case 'LDI':
			case 'LDA':
			case 'LDE':
			
			$banderaProductosLaboratorios = true;
				
			$tipoProducto = $cc->listarTipoProductosXarea($conexion, $idArea);
			
			$formularioLaboratorio .= '
			<input type="hidden" id="idOperacion" name="idOperacion" value="' . $operaciones . '" />
			<input type="hidden" id="nombreSubtipoProducto" name="nombreSubtipoProducto" />
			<input type="hidden" id="nombreTipoProducto" name="nombreTipoProducto" />
			<input type="hidden" id="nombreProducto" name="nombreProducto" />
			<input type="hidden" id="opcion" name="opcion" />
			<fieldset>
				<legend>Análisis acreditados</legend>
				<div data-linea="1">
					<label for="tipoProducto">Tipo de producto: </label>
					<select id="tipoProducto" name="tipoProducto">
						<option value="">Seleccione...</option>';
			while ($dTipoProducto = pg_fetch_assoc($tipoProducto)) {
				$formularioLaboratorio .= '<option value="' . $dTipoProducto['id_tipo_producto'] . '">' . $dTipoProducto['nombre'] . '</option>';
			}

			$tablaFormularioLaboratorio='

			<table id="analisisLaboratorio" style="width:100%">
			<thead>
				<tr>
					<th>Producto</th>
					<th>Parámetro</th>
					<th>Método</th>
					<th>Rango</th>
					<th>Acción</th>
				</tr>
			</thead>
			<tbody>';
			$res = $cr->obtenerProductosLaboratorios($conexion,$idOperadorTipoOperacion,$idSolicitud);
						   
			while($fila = pg_fetch_assoc($res)){
				
			$contenido .= '<td>'.$fila['nombre_comun'].'</td>
					<td>'.$fila['nombre_parametro'].'</td>
					<td>'.$fila['nombre_metodo'].'</td>
					<td>'.$fila['descripcion_rango'].'</td>
					<td class="borrar">											
					<button type="button" class="icono" onclick="eliminarProducto('.$fila['id_operacion'].','.$fila['id_operacion_parametro_laboratorio'].','.$fila['id_tipo_operacion'].')"></button>
					</td>
					</tr>';																															  
			}
			$tablaFormularioLaboratorio.=$contenido.'
			</tbody>
			</table>';

			$formularioLaboratorio .= '</select>
					</div>
					<div id="dSubtipoProducto" data-linea="2"></div>
					
					<div id="dProducto" data-linea="3"></div>
					<div id="dParametro" data-linea="4"></div>
					<button id="agregarLaboratorio" type="button" class="mas">Agregar</button>
				</fieldset>
				<fieldset>
					<legend>Análisis acreditados agregados</legend>
					<div data-linea="1" id="analisisLaboratorioAgregados">
						'.$tablaFormularioLaboratorio.'
					</div>
				</fieldset>
				<fieldset>
					<legend>Exoneración de pago</legend>
					<div data-linea="1">
						<label for="exoneracionPago">Exoneración de pago: </label>
						<select id="exoneracionPago" name="exoneracionPago">
							<option value="">Seleccione...</option>
							<option value="SI">SI</option>
							<option value="NO">NO</option>
						</select>
					</div>
					<div data-linea="2">
						<input type="file" id="fPago" class="archivo" name="informe" accept="application/pdf"/>
						<input type="hidden" id="rutaPago" class="rutaArchivo" name="rutaPago" value="0"/>
						<div class="estadoCarga">En espera de archivo... (Tamaño máximo '.ini_get('upload_max_filesize').'B)</div>
						<button type="button" class="archivoPago adjunto" data-rutaCarga="aplicaciones/registroOperador/laboratorios/exoneracionPago" >Subir archivo</button>
					</div>
				</fieldset>
				<fieldset>
					<legend>Sanción</legend>
					<div data-linea="1">
						<label for="sancion">Tiene sanción: </label>
						<select id="sancion" name="sancion">
							<option value="">Seleccione...</option>
							<option value="SI">SI</option>
							<option value="NO">NO</option>
						</select>
					</div>
					<div data-linea="2">
						<input type="file" id="fSancion" class="archivo" name="informe" accept="application/pdf"/>
						<input type="hidden" id="rutaSancion" class="rutaArchivo" name="rutaSancion" value="0"/>
						<div class="estadoCarga">En espera de archivo... (Tamaño máximo '.ini_get('upload_max_filesize').'B)</div>
						<button type="button" class="archivoSancion adjunto" data-rutaCarga="aplicaciones/registroOperador/laboratorios/sancion" >Subir archivo</button>
					</div>
				</fieldset>';
				
				$formularioCodigoLaboratorio = '<fieldset>
					<legend>Certificado de acreditación</legend>
						<div data-linea="1">
							<label>Certificado de acreditación SAE No.</label>
							<input type="text" id="certificadoSae" name="certificadoSae"/>
						</div>
					</fieldset>';
				
			break;
		}
	
	break;
}

?>

<header>
	<h1>Solicitud Operador</h1>
</header>
<div id="estado"></div>


	<fieldset>
		<legend>Datos operador</legend>
		<div data-linea="1">
			<label>Número de identificación: </label> <?php echo $operadorSitio['identificador']; ?> <br />
		</div>
		<div data-linea="2">
			<label>Razón social: </label> <?php echo $operadorSitio['nombre_operador']; ?> 
		</div>		
		<hr/>		
		<div data-linea="'3">
			<label>Nombre sitio: </label><?php echo $operadorSitio['nombre_lugar']; ?> 
		</div>		
		<div data-linea="4">
			<label>Provincia: </label><?php echo $operadorSitio['provincia']; ?> 
		</div>
		<div data-linea="4">
			<label>Canton: </label><?php echo $operadorSitio['canton']; ?> 
		</div>
		<div data-linea="4">
			<label>Parroquia: </label><?php echo $operadorSitio['parroquia']; ?> 
		</div>
		<div data-linea="5">
			<label>Dirección: </label><?php echo $operadorSitio['direccion']; ?> 
		</div>

	</fieldset>	
	
	<?php 

	echo $formularioAcopiadorLeche; //Imprime formulario en caso de ser del área de Inocuidad de los alimentos ACOPIADOR DE LECHE CRUDA
	echo $formularioRecolectorLeche; //Imprime formulario en caso de ser del área de Inocuidad de los alimentos RECOLECTOR
	
	?>
	
	<form id="evaluarSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarDocumentosSolicitud">
		<input type="hidden" name="inspector" value="<?php echo $identificadorInspctor;?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $operaciones;?>"/>
		<input type="hidden" name="tipoSolicitud" value="Operadores"/>
		<input type="hidden" name="tipoInspector" value="Documental"/>
		<input type="hidden" name="identificadorOperador" value="<?php echo $operadorSitio['identificador'];?>"/>
		<input type="hidden" name="tipoElemento" value="Producto"/>
		<input type="hidden" name="nombreOpcion" value="<?php echo $nombreOpcion;?>"/>
		<input type="hidden" name="idOperadorTipoOperacion" value="<?php echo $idOperadorTipoOperacion;?>"/>
		<input type="hidden" name="idHistoricoOperacion" value="<?php echo $idHistoricoOperacion;?>"/>
		<input type="hidden" name="codigoProvinciaSitio" value="<?php echo $operadorSitio['codigo_provincia'];?>"/>
		<input type="hidden" name="fechaInicio" value="<?php echo $fechaInicio;?>"/>
		<input type="hidden" name="opcionArea" value="<?php echo $opcionArea;?>"/>
		<input type="hidden" name="provinciaSitio" value="<?php echo $operadorSitio['provincia'];?>"/>
	
	<fieldset>
		<legend>Operación, área</legend>
	<?php 

	$contador = 40;	
	$contadorProductos = 0;
	
	foreach ($idGrupoOperaciones as $solicitud){
		$registros = array();
		
		if($idArea=='AI'){
			
			$banderaMostrarAsignacion = true;
		    $banderaAsigacionProducto = false;
		    $seccionAgenciaCertificadora .= "";
		    
		    switch ($opcionArea){
		        
		        case 'PRO':
				case 'REC':

					$productos = $cr->obtenerProductosOrganicosPorIdOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $operacion['estado']);
		            
		            $qDatosSitioMiembroAsociacion = $cr->obtenerSitioMiembroAsociacionXidSitio($conexion, $operadorSitio['id_sitio']);
		            
					//$codigoMag = pg_fetch_result($qDatosSitioMiembroAsociacion, 0, 'codigo_magap');
		            
		            if(pg_num_rows($qDatosSitioMiembroAsociacion) > 0){	
		            	
		            	$datosSitioMiembroAsociacion = pg_fetch_assoc($qDatosSitioMiembroAsociacion);
		            	$qAgenciasMiembroAsociacion = $cr->obtenerAgenciasMiembroAsociacion($conexion, $datosSitioMiembroAsociacion['identificador_miembro_asociacion'], $datosSitioMiembroAsociacion['identificador_asociacion']);
		            	
		            	$seccionAgenciaCertificadora .= '<fieldset>
                    		<legend>Datos del miembro de asociación</legend>
                    		<div data-linea="6">
                    			<label>Identificador dueño sitio: </label>'. $datosSitioMiembroAsociacion['identificador_miembro_asociacion'] . '
                    		</div>
                    		<div data-linea="7">
                    			<label>Nombre dueño sitio: </label>' . $datosSitioMiembroAsociacion['nombre_miembro'] . '
                    		</div>';
		            	
		            	if(pg_num_rows($qAgenciasMiembroAsociacion)>0){
		            		
		            		$seccionAgenciaCertificadora .= '<table style="width: 100%"><thead><tr><th colspan="2">Asociaciones a las que pertence el miembro</th></tr><tr>
                    						<th>Identificador asociación</th><th>Nombre asociación</th></tr></thead>';
		            		while($agenciasMiembroAsociacion = pg_fetch_assoc($qAgenciasMiembroAsociacion)){
		            			$seccionAgenciaCertificadora .= '<tr><td>'.$agenciasMiembroAsociacion['identificador_asociacion'].'</td><td>'.$agenciasMiembroAsociacion['nombre_operador'].'</td><tr>';
		            		}
		            		$seccionAgenciaCertificadora .= '</table>';
		            	}
		            	
		            	$seccionAgenciaCertificadora .= '</fieldset>';
		            	
		            }
		            
		            $cabeceraTablaProductos = '<th>Superficie (ha)</th><th>Rendimiento (t/año)</th>';
		            
		        break;
		        
		        case 'COM':
		        case 'PRC':		     
		            $proveedores = $cr->obtenerProveedoresPorIdOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $opcionArea, $operacion['estado']);
		            $banderaProductoCertificado = true;
					
				if(pg_num_rows($proveedores) > 0){
						
		            if($opcionArea == "COM"){
		                 $banderaValidacionAI = 'COMERCIALIZADOR';
						 $productoCOM = $cr->obtenerProductosAreaSitioPorIdOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $operacion['estado']);		                 

		                 if(pg_num_rows($productoCOM) == 0){
		                     $banderaMostrarAsignacion = false;
						}else{
		                     $banderaAsigacionProducto = true;
		                 }
						 
		            }else if($opcionArea == "PRC"){
						$productoPRC = $cr->obtenerProductosAreaSitioPorIdOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $operacion['estado']);
		            
		                  if(pg_num_rows($productoPRC) == 0){
		                      $banderaMostrarAsignacion = false;
						}else{
		                      $banderaAsigacionProducto = true;
		                  }
	  
		            }
		            
		            $seccionProveedores .= '<fieldset id="datosProveedor">
												<legend>Proveedores y Productos</legend>
												<table style="width: 100%">
												<thead>
													<tr>
														<th>#</th>
														<th>Proveedor</th>
														<th>Códio POA</th>
														<th>Subtipo producto</th>
														<th>Producto</th>
														<th>Estatus</th>
													</tr>
												</thead>
												<tbody>';

		            while ($fila = pg_fetch_assoc($proveedores)){
		                $seccionProveedores .= '<tr><td>'.++$contadorProductos.'</td><td>'.$fila['nombre_proveedor'].'</td><td>'.$fila['codigo_poa'].'</td><td>'.$fila['nombre'].'</td><td>'.$fila['nombre_producto'].'</td><td>'.$fila['nombre_tipo_transicion'].'</td></tr>';
		            }
		            
		            $seccionProveedores .= '</tbody></table></fieldset>';
					
		        }
				
		        break;
		        
		    }

		    if($opcionArea == "PRO" || $opcionArea == "REC" || $opcionArea == "PRC" || $opcionArea == "COM"){
		    	
		    	$seccionProductos = "";
				$banderaProductoCertificado = true;	
												  
				if($opcionArea == "PRO" || $opcionArea == "REC"){
					
					if(pg_num_rows($productos) > 0){
						
						$banderaAsigacionProducto = true;
						
						while ($fila = pg_fetch_assoc($productos)){
							$seccionProductos .= '<tr><td>'.++$contadorProductos.'</td><td>'.$fila['nombre_tipo'].'</td><td>'.$fila['nombre_subtipo'].'</td><td>'.$fila['nombre_comun'].'</td><td>'.$fila['superficie_miembro'].'</td><td>'.$fila['rendimiento'].'</td></tr>';
							
							$productoCertificado[] = array('nombre'=>$operadorSitio['nombre_lugar'].'-'.$fila['nombre_comun'], 'idProducto'=>$fila['id_producto'], 'codigoSitio'=>$operadorSitio['id_sitio'], 'idOperacion'=>$fila['id_operacion'] );
						}
						
					}else{

							$banderaMostrarAsignacion = false;	
												
						}

				}
				
				if($banderaMostrarAsignacion){
					
					$qTipoProduccion = $cr->obtenerTipoProduccion($conexion);
					$qTipoTransicion = $cr->obtenerTipoTransicion($conexion);
					$qAgenciaCertificadora = $cr->obtenerAgenciaCertificadora($conexion);
				
					$seccionAgenciaCertificadora .= '<fieldset>
								<legend>Datos de agencia certificadora</legend>
								<div data-linea="7">
									<label id="lTipoProduccion">Producción: </label>
										<select id="tipoProduccion" name="tipoProduccion">
											<option value="0">Seleccione...</option>';
					
					while ($fila = pg_fetch_assoc($qTipoProduccion)){
						$seccionAgenciaCertificadora .= '<option value="'.$fila['id_tipo_produccion']. '">'. $fila['nombre_tipo_produccion'] .'</option>';
					}
					
					$seccionAgenciaCertificadora .= '</select>
								</div>
								<div data-linea="7">
									<label id="lTipoTransicion">Estatus: </label>
										<select id="tipoTransicion" name="tipoTransicion">
											<option value="0">Seleccione...</option>';
					
					while ($fila = pg_fetch_assoc($qTipoTransicion)){
						$seccionAgenciaCertificadora .= '<option value="'.$fila['id_tipo_transicion']. '">'. $fila['nombre_tipo_transicion'] .'</option>';
					}
					
					$seccionAgenciaCertificadora .= '</select>
								</div>
								<div data-linea="8">
									<label>Agencia certificadora: <?php echo $opcionArea; ?></label>
										<select id="agenciaCertificadora" name="agenciaCertificadora">
											<option value="0">Seleccione...</option>';
					
					while ($fila = pg_fetch_assoc($qAgenciaCertificadora)){
						$seccionAgenciaCertificadora .= '<option value="'.$fila['id_agencia_certificadora']. '">'. $fila['nombre_agencia_certificadora'] .'</option>';
					}
					
					$seccionAgenciaCertificadora .= '</select>
								</div>
								<div data-linea="9">
									<label id="lProductoCertificado">Producto: </label>
										<select id="productoCertificado" name="productoCertificado">
											<option value="">Seleccione...</option>';
					
					if($opcionArea == "PRO" || $opcionArea == "REC" || $opcionArea == "COM"){
						
						if(is_array($productoCertificado) && count($productoCertificado) > 0){
							
						foreach ($productoCertificado as $producto){
							$seccionAgenciaCertificadora .= '<option value="'.$producto['idProducto']. '" data-sitio="'.$producto['codigoSitio'].'" data-idOperacion="'.$producto['idOperacion'].'">'. $producto['nombre'] .'</option>';
						}
					}
					
					
					}else if($opcionArea == "PRC") {
						
						while($producto = pg_fetch_assoc($productoPRC)){
							$seccionAgenciaCertificadora .= '<option value="'.$producto['id_producto']. '" data-sitio="'.$producto['id_sitio'].'" data-idOperacion="'.$producto['id_operacion'].'">'. $producto['nombre_producto'] .'</option>';
						}
						
					}
					
					$seccionAgenciaCertificadora .= '</select>
								</div>
								<div data-linea="10" class="info"></div>
								<button type="button" onclick="agregarItem()" class="mas" id="agregarAgencia">Agregar agencia</button>';
					
					if($banderaValidacionAI != 'COMERCIALIZADOR'){
						
						$seccionAgenciaCertificadora .= '<table id="tablaDetalle" style="width:100%;">
										<thead>
											<tr>
												<th>Producción</th>
												<th>Transición</th>
												<th>Agencia</th>
												<th>Producto</th>
												<th>Opción</th>
											<tr>
										</thead>
								
											<tbody id="detalles">
											</tbody>
									 </table>';
					}else if($banderaValidacionAI == 'COMERCIALIZADOR'){
						
						$proveedoresImportacion = $cr->obtenerProveedoresImportacionXIdOperadorTipoOperacionXidHistorialOperacion($conexion, $idOperadorTipoOperacion, $idHistoricoOperacion);
						if(pg_num_rows($proveedoresImportacion)>0){
							$banderaImportador = true;
						}
						
						$seccionAgenciaCertificadora .= '<div data-linea="11">
										<label>Alcance: </label>
									</div>
									<div data-linea="11">
										<input type="checkbox" name="nacional" value="nacional" id="nacional" >Nacional
									</div>
									<div data-linea="11">
										<input type="checkbox" name="importador" value="importador" id="importador" onclick="javascript: return false;" >Importador
									</div>
									<div data-linea="11">
										<input type="checkbox" name="exportador" value="exportador" id="exportador" >Exportador
									</div>';
						
					}
					
					$seccionAgenciaCertificadora .= '</fieldset>';
					
					if($banderaValidacionAI == 'COMERCIALIZADOR'){
						$seccionAgenciaCertificadora .= '<fieldset>
										<legend>Mercado Destino</legend>
										<div data-linea="12">
											<label>Mercado: </label>
												<select id="mercadoDestino" name="mercadoDestino">
													<option value="">Seleccione....</option>';
						
						$qPaises = $cc->listarLocalizacion($conexion, "PAIS");
						
						while($paises = pg_fetch_assoc($qPaises)){
							$seccionAgenciaCertificadora .= '<option value="'. $paises['id_localizacion'] .'">'. $paises['nombre'] .'</option>';
						}
						
						$seccionAgenciaCertificadora .= '</select>
										</div>
										<button type="button" onclick="agregarMercadoDestino()" class="mas" id="agregarAgencia">Agregar</button>
									 
											<table id="tablaMercadoDestino" style="width:100%;">
												<thead>
													<tr>
														<th>Mercado</th>
														<th>Opciones</th>
													<tr>
												</thead>
									 
													<tbody id="detallesMercado">
													</tbody>
											</table>
									</fieldset>';
					}
				
				}else{

					$seccionAgenciaCertificadora .= '<fieldset>
					<legend>Datos de agencia certificadora</legend>
								<div data-linea="7">
									El área ya posee sus productos con asignación
								</div>
								</fieldset>';

				}
				
			}else{
				$productos = $cr->obtenerProductosPorIdOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $operacion['estado'], $idVigenciaDocumento);
			}
		
		}else{	
			$productos = $cr->obtenerProductosPorIdOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $operacion['estado'], $idVigenciaDocumento);
		}			
		
		$qAreasOperador = $cr->obtenerOperadorOperacionAreaInspeccion($conexion, $solicitud);
		
		while($areaOperacion = pg_fetch_assoc($qAreasOperador)){
			$registros[] = array('nombreArea' => $areaOperacion['nombre_area'], 'tipoArea' => $areaOperacion['tipo_area'], 'nombreOperacion' => $areaOperacion['nombre_operacion'], 
								'idArea' => $areaOperacion['id_area'], 'superficieUtilizada' => $areaOperacion['superficie_utilizada'], 'idOperacion' => $areaOperacion['id_operacion']);
		}
		
		$qDocumentosAdjuntos = $cr->obtenerDocumentosAdjuntoXoperacion($conexion, $solicitud);
		$documentoAdjunto = (pg_num_rows($qDocumentosAdjuntos)!= 0 ? true : false);
		
		$qVigenciaDocumento = $cvd->obtenerVigenciaDeclaradaPorIdVigenciaXEtapaVigencia($conexion, $idVigenciaDocumento, 'documental');
				
		echo'
		<div data-linea="'.$contador++.'">
			<label>Tipo operación: </label> ' . $registros[0]['nombreOperacion'] . '
		</div>';
		
		echo '<div data-linea="'.++$contador.'">
			<label>Nombre área: </label></div>';
		
		$areaImpreso = '';
		foreach ($registros as $areas){
			//Información de tamaño de áreas
			$qUnidadMedida = $cc->obtenerUnidadMedidaAreas($conexion, $areas['idArea']);
			$unidadMedida = pg_fetch_result($qUnidadMedida, 0, 'unidad_medida');
			
			echo '<div data-linea="'.++$contador.'">
					'.$areas['nombreArea'].' ('. $areas['superficieUtilizada'].' '.$unidadMedida .')
						<input type="hidden" name="idAreas[]" value="'.$areas['idArea'].'"/>
						<input type="hidden" name="idOperaciones[]" value="'.$areas['idOperacion'].'"/>
				</div>';			
		}
			
		if($documentoAdjunto){
			$resultadoSubsanacion = $documentoAdjunto;
			echo '<div data-linea="'.++$contador.'">
						<label>Documentos adjuntos: </label></div>';
			
			while ($documento = pg_fetch_assoc($qDocumentosAdjuntos)){
				echo '<div data-linea="'.++$contador.'"><label>'.$documento['titulo'].'.-  </label><a href="'.$documento['ruta_documento'].'" target="_blank">'.$documento['descripcion'].'</a></div>';
			}
		}
	}

	?>	
	</fieldset>
   
	<?php 
		if(pg_num_rows($qRepresentante) != 0){

			echo '<fieldset>
					<legend>Representante técnico</legend>
						<table style="width: 100%">
							<thead>
								<tr>
									<th>Identificación</th>
									<th>Nombre</th>
									<th>Título</th>
									<th>Área</th>
									<th>Nro. Registro Senescyt</th>
								</tr>
							</thead>
							<tbody>';

			while ($fila = pg_fetch_assoc($qRepresentante)) {

				echo '<tr>
						<td>'.$fila['identificacion_representante'].'</td>
						<td>'.$fila['nombre_representante'].'</td>
						<td>'.$fila['titulo_academico'].'</td>
						<td>'.($fila['id_area_representante'] =='SA'? 'Sanidad Animal': ($fila['id_area_representante'] =='SV'? 'Sanidad Vegetal': ($fila['id_area_representante'] =='IAV'? 'Pecuarios': ($fila['id_area_representante'] =='IAP'? 'Agrícolas': ($fila['id_area_representante'] =='IAF'? 'Fertilizantes': 'N/A'))))).'</td>
						<td>'.$fila['numero_registro_titulo'].'</td>
					</tr>';
			}

			echo '</tbody>
				</table>
			</fieldset>';
		}
	?>
	
	<?php 
	if((pg_num_rows($productos)!= 0) && (!$banderaProductosLaboratorios)){ ?>
	<fieldset id="datosProducto">
		<legend>Productos</legend>
		<table style="width: 100%">
			<thead>
				<tr>
					<th>#</th>
					<th>Tipo producto</th>
					<th>Subtipo producto</th>
					<th>Producto</th>
					<?php 
					echo $cabeceraTablaProductos;
					?>
				</tr>
			</thead>
			<tbody>
			<?php
			
			if(!$banderaProductoCertificado){
			    while ($fila = pg_fetch_assoc($productos)){
			        echo '<tr><td>'.++$contadorProductos.'</td><td>'.$fila['nombre_tipo'].'</td><td>'.$fila['nombre_subtipo'].'</td><td>'.$fila['nombre_comun'].'</td></tr>';			      
			    }
			}else{
			    echo $seccionProductos;
			}

			?>
			</tbody>
		</table>
	</fieldset>
	
	<?php 
	}
	
	echo $seccionProveedores; //Imprime la sección de proveedores en caso de ser del área de Inociudad COMERCIALIZADOR ó PROCESADOR orgánico
	
	echo $seccionAgenciaCertificadora;//Imprime la sección de agencia certificadora en caso de ser del área de Inociudad PRODUCTOR ó PROCESADOR orgánico
	
	echo $formularioIndustriaLactea;//Imprime la sección de vigencia de documetno en caso de ser del área de Inociudad INDUSTRIA LACTEA
	
	echo $formularioLaboratorio; //Imprime la seccion de registro de productos para operaciones de laboratorio
	?>
	<fieldset>
		<legend>Resultado de Revisión Documental</legend>
				
			<div data-linea="6">
				<label>Resultado</label>
					<select id="resultadoDocumento" name="resultadoDocumento">
						<option value="">Seleccione....</option>
						<?php 
							while($vigenciaDocumento = pg_fetch_assoc($qVigenciaDocumento)){

									if($vigenciaDocumento['valor_tiempo_vigencia_declarada'] == 1){
										switch ($vigenciaDocumento['tipo_tiempo_vigencia_declarada']){
									
											case 'anio':
												$tipoTiempo = 'año';
												break;
									
											case 'mes':
												$tipoTiempo = 'mes';
												break;
									
											case 'dia':
												$tipoTiempo = 'día';
												break;
									
										}
									}elseif($vigenciaDocumento['valor_tiempo_vigencia_declarada'] > 1){
										switch ($vigenciaDocumento['tipo_tiempo_vigencia_declarada']){
												
											case 'anio':
												$tipoTiempo = 'años';
												break;
									
											case 'mes':
												$tipoTiempo = 'meses';
												break;
									
											case 'dia':
												$tipoTiempo = 'días';
												break;
									
										}
									}
														
								echo '<option value="'.$vigenciaDocumento['id_vigencia_declarada'].'" data-resultado ="registrado">Aprobado por '.$vigenciaDocumento['valor_tiempo_vigencia_declarada'].' '.$tipoTiempo.'</option>';
							}
						
							if(pg_num_rows($qVigenciaDocumento) == 0){?>
								<option value="registrado">Aprobar revisión documental</option>
							<?php }?>
								<option value="noHabilitado">No habilitado</option>
								<?php echo $subsanacion;?>
					</select>
			</div>	
			<div data-linea="2">
				<label>Observaciones</label>
					<input type="text" id="observacionDocumento" name="observacionDocumento" maxlength="500"/>
			</div>
			
	</fieldset>
	
	<?php
	//control cambios material reproductivo
	echo $formularioPlanificacionInspeccion; //imprime formulario de planificacion de inspeccion
	echo $formularioCodigoLaboratorio; //Imprime formulario de laboratorios
	//fin cambios material reproductivo
	?>
		
		<button type="submit" class="guardar">Enviar resultado</button>
	</form>	
   <div id="cargarMensajeTemporal"></div>
<script type="text/javascript">

var idOperadorTipoOperacion = <?php echo json_encode($idOperadorTipoOperacion); ?>;
var idSolicitud = <?php echo json_encode($idSolicitud); ?>;								   
var banderaProductoCertificado = <?php echo json_encode($banderaProductoCertificado); ?>;

var banderaValidacionAI = <?php echo json_encode($banderaValidacionAI); ?>;
var banderaImportador = <?php echo json_encode($banderaImportador); ?>;

var arrayTabla = [];
var bandera = <?php echo json_encode($bandera); ?>;

// >> Material reproductivo
var area = <?php echo json_encode($idArea); ?>;
var opcionArea = <?php echo json_encode($opcionArea); ?>;
// << Fin material reproductivo

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();

		$("#fechaInspeccion").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',  
			minDate: '0'
        });	   

		if (bandera){

			$("#capacidadInstalada").numeric();
			$("#numeroTrabajadores").numeric();
			$("#numeroProveedores").numeric();
			
			$("#iInspeccion").prop('checked', true);
			$("#iPostRegistro").attr("disabled", true);
			
			cargarValorDefecto("marca","<?php echo $datosVehiculo['id_marca_vehiculo'];?>");
			cargarValorDefecto("modelo","<?php echo $datosVehiculo['id_modelo_vehiculo'];?>");
			cargarValorDefecto("tipo","<?php echo $datosVehiculo['id_tipo_vehiculo'];?>");
			cargarValorDefecto("color","<?php echo $datosVehiculo['id_color_vehiculo'];?>");
			cargarValorDefecto("clase","<?php echo $datosVehiculo['id_clase_vehiculo'];?>");
			cargarValorDefecto("tipoTanque","<?php echo $datosVehiculo['id_tipo_tanque_vehiculo'];?>");
			cargarValorDefecto("unidadMedidaVehiculo","<?php echo $datosVehiculo['codigo_unidad_medida'];?>");

			cargarValorDefecto("unidadMedida","<?php echo $centroAcopio['codigo_unidad_medida'];?>");
			cargarValorDefecto("laboratorio","<?php echo $centroAcopio['id_laboratorio_leche'];?>");
			cargarValorDefecto("tiempoRecoleccion","<?php echo $centroAcopio['tiempo_recoleccion'];?>");
			cargarValorDefecto("perteneceMag","<?php echo $centroAcopio['pertenece_mag'];?>");
		}

		if(banderaValidacionAI == 'COMERCIALIZADOR'){
			$('#tipoProduccion').hide();
			$('#tipoTransicion').hide();
			$('#productoCertificado').hide();
			$('#lTipoProduccion').hide();
			$('#lTipoTransicion').hide();
			$('#lProductoCertificado').hide();
			$('#agregarAgencia').hide();

			if(banderaImportador){
				$('#importador').prop('checked', true);	
				$(':checkbox[readonly=readonly]').click(function(){
				     return false;
				});
			}else if (!banderaImportador){
				$("#importador").attr("disabled", true);
			}
		}

		$("#fechaCaducidad").datepicker({
		    changeMonth: true,
		    changeYear: true,
		    minDate: 1
		});

		// >> Material reproductivo
			
		$("#resultadoDocumento").change(function(){

			if (area == 'SA'){
				if ($(this).val()=="registrado"){
					$("#tecnicoInspeccion").removeAttr("disabled");
					$("#fechaInspeccion").removeAttr("disabled");
					$("#horaInspeccion").removeAttr("disabled");
				} else{
					$("#tecnicoInspeccion").val("").attr("disabled", true).removeClass("alertaCombo");
					$("#fechaInspeccion").val("").attr("disabled", true).removeClass("alertaCombo");
					$("#horaInspeccion").val("").attr("disabled", true).removeClass("alertaCombo");
				}
			
			}
		});

		// << Fin material reproductivo
		

	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
		$("#iPostRegistro").attr("disabled", true);
	});
	
	$("#evaluarSolicitud").submit(function(event){
		event.preventDefault();
		chequearCamposInspeccion(this);
	});

	$("#horaInicioRecoleccion").change(function(){

		$("#horaInicioRecoleccion").removeClass('alertaCombo');
			
			var horaNueva = $("#horaInicioRecoleccion").val().replace(/\_/g, "0");
			$("#horaInicioRecoleccion").val(horaNueva);
			
			var hora = $("#horaInicioRecoleccion").val().substring(0,2);
			var minuto = $("#horaInicioRecoleccion").val().substring(3,5);
			
			if(parseInt(hora)>=1 && parseInt(hora)<25){
				if(parseInt(minuto)>=0 && parseInt(minuto)<60){
					if(parseInt(hora)==24){
						minuto = '00';
						$("#horaInicioRecoleccion").val('24:00');
					}			

				}else{
					$("#horaInicioRecoleccion").addClass('alertaCombo');
					$("#horaInicioRecoleccion").val('');
					$("#estado").html("Los minutos ingresados están incorrectos, por favor actualice la información").addClass('alerta');
				}
			}else{
				$("#horaInicioRecoleccion").addClass('alertaCombo');
				$("#horaInicioRecoleccion").val('');
				$("#estado").html("La hora ingresada está fuera de rango").addClass('alerta');
			}

	});

	$("#horaFinRecoleccion").change(function(){

		$("#horaFinRecoleccion").removeClass('alertaCombo');
			
			var horaNueva = $("#horaFinRecoleccion").val().replace(/\_/g, "0");
			$("#horaFinRecoleccion").val(horaNueva);
			
			var hora = $("#horaFinRecoleccion").val().substring(0,2);
			var minuto = $("#horaFinRecoleccion").val().substring(3,5);
			
			if(parseInt(hora)>=1 && parseInt(hora)<25){
				if(parseInt(minuto)>=0 && parseInt(minuto)<60){
					if(parseInt(hora)==24){
						minuto = '00';
						$("#horaFinRecoleccion").val('24:00');
					}			

				}else{
					$("#horaFinRecoleccion").addClass('alertaCombo');
					$("#horaFinRecoleccion").val('');
					$("#estado").html("Los minutos ingresados están incorrectos, por favor actualice la información").addClass('alerta');
				}
			}else{
				$("#horaFinRecoleccion").addClass('alertaCombo');
				$("#horaFinRecoleccion").val('');
				$("#estado").html("La hora ingresada está fuera de rango").addClass('alerta');
			}

	});

	$("#horaRecoleccionManiana").change(function(){

		$("#horaRecoleccionManiana").removeClass('alertaCombo');
			
			var horaNueva = $("#horaRecoleccionManiana").val().replace(/\_/g, "0");
			$("#horaRecoleccionManiana").val(horaNueva);
			
			var hora = $("#horaRecoleccionManiana").val().substring(0,2);
			var minuto = $("#horaRecoleccionManiana").val().substring(3,5);
			
			if(parseInt(hora)>=1 && parseInt(hora)<25){
				if(parseInt(minuto)>=0 && parseInt(minuto)<60){
					if(parseInt(hora)==24){
						minuto = '00';
						$("#horaRecoleccionManiana").val('24:00');
					}			

				}else{
					$("#horaRecoleccionManiana").addClass('alertaCombo');
					$("#horaRecoleccionManiana").val('');
					$("#estado").html("Los minutos ingresados están incorrectos, por favor actualice la información").addClass('alerta');
				}
			}else{
				$("#horaRecoleccionManiana").addClass('alertaCombo');
				$("#horaRecoleccionManiana").val('');
				$("#estado").html("La hora ingresada está fuera de rango").addClass('alerta');
			}

	});

	$("#horaRecoleccionTarde").change(function(){

		$("#horaRecoleccionTarde").removeClass('alertaCombo');
			
			var horaNueva = $("#horaRecoleccionTarde").val().replace(/\_/g, "0");
			$("#horaRecoleccionTarde").val(horaNueva);
			
			var hora = $("#horaRecoleccionTarde").val().substring(0,2);
			var minuto = $("#horaRecoleccionTarde").val().substring(3,5);
			
			if(parseInt(hora)>=1 && parseInt(hora)<25){
				if(parseInt(minuto)>=0 && parseInt(minuto)<60){
					if(parseInt(hora)==24){
						minuto = '00';
						$("#horaFinRecoleccion").val('24:00');
					}			

				}else{
					$("#horaRecoleccionTarde").addClass('alertaCombo');
					$("#horaRecoleccionTarde").val('');
					$("#estado").html("Los minutos ingresados están incorrectos, por favor actualice la información").addClass('alerta');
				}
			}else{
				$("#horaRecoleccionTarde").addClass('alertaCombo');
				$("#horaRecoleccionTarde").val('');
				$("#estado").html("La hora ingresada está fuera de rango").addClass('alerta');
			}

	});
			
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCamposInspeccion(form){
		var pCombo;
		var pTabla;
		var productoAgencia = "";			
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(banderaProductoCertificado){

			contador_combo = $('#productoCertificado option').size()-1;
			contador_tabla = $("#tablaDetalle tbody tr").length;
			
			banderaAsigacionProducto = <?php echo json_encode($banderaAsigacionProducto); ?>;
			
			if(banderaAsigacionProducto){
				
				if($("#resultadoDocumento").val() == 'registrado'){
						 
					if(banderaValidacionAI != 'COMERCIALIZADOR'){
					
						if(contador_combo != contador_tabla){
							error = true;
							productoAgencia = " Debe asignar una agencia a cada producto inspeccionado.";
							$("#productoCertificado").addClass("alertaCombo");
						}
						
					}else{

						contador_tabla_mercado = $("#tablaMercadoDestino tbody tr").length;
				   
						if(!$.trim($("#agenciaCertificadora").val()) || $("#agenciaCertificadora").val()=="0"){
							error = true;
							productoAgencia = " Debe seleccionar una agencia certificadora.";
							$("#agenciaCertificadora").addClass("alertaCombo");
						}
	 
						if(contador_tabla_mercado == 0){
							error = true;
							productoAgencia = " Debe seleccionar al menos un mercado de destino.";
							$("#mercadoDestino").addClass("alertaCombo");
						}
						
						if(!$("#nacional").is(':checked') && !$("#importador").is(':checked') && !$("#exportador").is(':checked')){

							error = true;
							$("#nacional").addClass("alertaCombo");
							$("#importador").addClass("alertaCombo");
							$("#exportador").addClass("alertaCombo");
						}

						if($("#mercadoDestino").val()==""){
							error = true;
							$("#mercadoDestino").addClass("alertaCombo");
						}

					}    			
					
				}
				
			}
			
		}
		
		if(!$.trim($("#resultadoDocumento").val()) || !esCampoValido("#resultadoDocumento")){
			error = true;
			$("#resultadoDocumento").addClass("alertaCombo");
		}
	
		if($("#resultadoDocumento").val() == 'noHabilitado' || $("#resultadoDocumento").val() == 'subsanacion'){
			if(!$.trim($("#observacionDocumento").val()) || !esCampoValido("#observacionDocumento")){
				error = true;
				$("#observacionDocumento").addClass("alertaCombo");
			}
		}				

		// >> Material reproductivo		
	
		if (area == 'SA'){			

			if(opcionArea=='PMR' || opcionArea=='CPM' || opcionArea=='DMR' || opcionArea=='AMR' || opcionArea=='OEC'){
				
				if($("#resultadoDocumento").val()=='registrado'){

					if($("#tecnicoInspeccion").val() == ''){
						error = true;
						$("#tecnicoInspeccion").addClass("alertaCombo");
					}

					if($("#fechaInspeccion").val() == ''){
						error = true;
						$("#fechaInspeccion").addClass("alertaCombo");
					}

					if($("#horaInspeccion").val() == ''){
						error = true;
						$("#horaInspeccion").addClass("alertaCombo");
					}
				}	
			}
		}

		if (area == 'LT'){
 
			if(opcionArea=='LDI' || opcionArea=='LDA' || opcionArea=='LDE'){
				
				if($("#resultadoDocumento").val()=='registrado'){

					if($("#analisisLaboratorio >tbody >tr").length ==0){
						error = true;
						$("#analisisLaboratorio").addClass("alertaCombo");
					}

					if($("#certificadoSae").val() == ''){
						error = true;
						$("#certificadoSae").addClass("alertaCombo");
					}

					if($("#exoneracionPago").val() == ''){
						error = true;
						$("#exoneracionPago").addClass("alertaCombo");
					}else{
						if($("#exoneracionPago").val() == 'SI'){
							if($("#rutaPago").val() == '0'){
								error = true;
								$("#fPago").addClass("alertaCombo");
							}
						}
					}

					if($("#sancion").val() == ''){
						error = true;
						$("#sancion").addClass("alertaCombo");
					}else{
						if($("#sancion").val() == 'SI'){
							if($("#rutaSancion").val() == '0'){
								error = true;
								$("#fSancion").addClass("alertaCombo");
							}
						}
					}
				}	
			}
		}

		// << Fin material reproductivo
				
		if (error){
			$("#estado").html("Por favor revise la información ingresada."+productoAgencia).addClass('alerta');
		}else{
			$("#evaluarSolicitud").attr('data-accionEnExito', 'ACTUALIZAR');
			ejecutarJson(form);
		}
	}


	function agregarItem(){		

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

	   		if(!$.trim($("#tipoProduccion").val()) || $("#tipoProduccion").val()=="0"){
    			error = true;
    			$("#tipoProduccion").addClass("alertaCombo");
    		}
    
    		if(!$.trim($("#tipoTransicion").val()) || $("#tipoTransicion").val()=="0"){
    			error = true;
    			$("#tipoTransicion").addClass("alertaCombo");
    		}    

    		if(!$.trim($("#agenciaCertificadora").val()) || $("#agenciaCertificadora").val()=="0"){
				error = true;
				$("#agenciaCertificadora").addClass("alertaCombo");
			}
    		
    		if(!$.trim($("#productoCertificado").val()) || $("#productoCertificado").val()=="0"){
    			error = true;
    			$("#productoCertificado").addClass("alertaCombo");
    		}
    		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{

			if($("#agenciaCertificadora").val()!="" && $("#agenciaCertificadora").val()!="0"){
				
				if($("#detalles #r_"+$("#agenciaCertificadora").val()+$("#productoCertificado").val()+$("#productoCertificado option:selected").attr('data-sitio')).length==0){

					$("#estado").html("").removeClass('alerta');
				
					$("#detalles").append("<tr id='r_"+$("#agenciaCertificadora").val()+$("#productoCertificado").val()+$("#productoCertificado option:selected").attr('data-sitio')+"'>"+
							"<td>"+$("#tipoProduccion  option:selected").text()+"<input id='idTipoProduccion' name='idTipoProduccion[]' value='"+$("#tipoProduccion").val()+"' type='hidden'></td>"+
							"<td>"+$("#tipoTransicion  option:selected").text()+"<input id='idTipoTransicion' name='idTipoTransicion[]' value='"+$("#tipoTransicion").val()+"' type='hidden'></td>"+
							"<td>"+$("#agenciaCertificadora  option:selected").text()+"<input id='idAgencia' name='idAgencia[]' value='"+$("#agenciaCertificadora").val()+"' type='hidden'></td>"+
							"<td>"+$("#productoCertificado  option:selected").text()+"<input id='idProducto' name='idProducto[]' value='"+$("#productoCertificado").val()+"' type='hidden'>"+
							"<input id='idOperacion' name='idOperacion[]' value='"+$("#productoCertificado option:selected").attr('data-idOperacion')+"' type='hidden'></td>"+
							"<td><button type='button' onclick='quitarItem(\"#r_"+$("#agenciaCertificadora").val()+$("#productoCertificado").val()+$("#productoCertificado option:selected").attr('data-sitio')+"\","+$("#productoCertificado option:selected").attr('data-idOperacion')+")' class='menos'>Quitar</button></td>"+
							"</tr>");
						
					arrayTabla.push($("#productoCertificado").val());						

					$("#tablaDetalle tbody tr").each(function(){

						valorTabla = $(this).find("input[id='idOperacion']").val();
				    		
						$("#productoCertificado option").each(function() {
							
						    if ($(this).attr('data-idOperacion') == valorTabla){
						        $(this).prop('disabled', true);
						    }
						});

					});
					
				}else{
					
					$("#estado").html("No se permite seleccionar el mismo producto en la misma agencia certificadora.").addClass('alerta');
					
				}
			}
		}
	}

	function quitarItem(fila, valor){

		$("#detalles tr").eq($(fila).index()).remove();
		$("#productoCertificado option").each(function() {
			
		    if ($(this).attr('data-idOperacion') == valor){
		        $(this).prop('disabled', false);
		    }
		});
		
		distribuirLineas();
	}

	function agregarMercadoDestino(){		

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

	   		if($("#mercadoDestino").val()==""){
    			error = true;
    			$("#mercadoDestino").addClass("alertaCombo");
    		}
        		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{

			if($("#mercadoDestino").val()!=""){
				
				if($("#detallesMercado #r_"+$("#mercadoDestino").val()).length==0){

					$("#estado").html("").removeClass('alerta');
				
					$("#detallesMercado").append("<tr id='r_"+$("#mercadoDestino").val()+"'>"+
							"<td>"+$("#mercadoDestino  option:selected").text()+"<input id='idMercadoDestino' name='idMercadoDestino[]' value='"+$("#mercadoDestino").val()+"' type='hidden'></td>"+
							"<td><button type='button' onclick='quitarItemMercadoDestino(\"#r_"+$("#mercadoDestino").val()+"\")' class='menos'>Quitar</button></td>"+
							"</tr>");					
										
				}else{
					
					$("#estado").html("No se permite seleccionar el mismo mercado de destino.").addClass('alerta');
					
				}
			}
		}
	}

	function quitarItemMercadoDestino(fila){
		$("#detallesMercado tr").eq($(fila).index()).remove();
	}

	

	$("#declararDatosVehiculo").submit(function(event){
		event.preventDefault();
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;	

		

		if(!$.trim($("#capacidadInstalada").val())){
			error = true;
			$("#capacidadInstalada").addClass("alertaCombo");
		}

		if(!$.trim($("#unidadMedidaVehiculo").val())){
			error = true;
			$("#unidadMedida").addClass("alertaCombo");
		}

		if(opcionArea == 'MDC'){

    		if(!$.trim($("#tipoContenedor").val())){ 
    			error = true;
    			$("#tipoContenedor").addClass("alertaCombo");
    		}	
    		if(!$.trim($("#caracteristicaContenedor").val())){ 
    			error = true;
    			$("#caracteristicaContenedor").addClass("alertaCombo");
    		}
    		
		}else{
    		if(!$.trim($("#horaInicioRecoleccion").val())){ 
    			error = true;
    			$("#horaInicioRecoleccion").addClass("alertaCombo");
    		}
			
    		if(!$.trim($("#horaFinRecoleccion").val())){ 
    			error = true;
    			$("#horaFinRecoleccion").addClass("alertaCombo");
    		}
			error = controlarHoras($("#horaInicioRecoleccion").val(),$("#horaFinRecoleccion").val());
		}
		if (!error){
			$('#declararDatosVehiculo').attr('data-opcion','actualizarDatosVehiculo');  
			ejecutarJson($(this)); 
		}else{
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}
		
	 });

	
	$("#declararInformacionCentroAcopio").submit(function(event){
		event.preventDefault();
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;	
		var errorTipo = false;

		if(!$.trim($("#capacidadInstalada").val())){
			error = true;
			$("#capacidadInstalada").addClass("alertaCombo");
		}

		if(!$.trim($("#unidadMedida").val())){
			error = true;
			$("#unidadMedida").addClass("alertaCombo");
		}

		if(!$.trim($("#numeroTrabajadores").val())){
			error = true;
			$("#numeroTrabajadores").addClass("alertaCombo");
		}

		if(!$.trim($("#laboratorio").val())){
			error = true;
			$("#laboratorio").addClass("alertaCombo");
		}
		
		if(!$.trim($("#numeroProveedores").val())){
			error = true;
			$("#numeroProveedores").addClass("alertaCombo");
		}

		if($("#horaRecoleccionManiana").val() == "" && $("#horaRecoleccionTarde").val()==""){	
			error = true;	
			errorTipo = true; 
			$("#horaRecoleccionManiana").addClass("alertaCombo");
			$("#horaRecoleccionTarde").addClass("alertaCombo");
		}
		
		if (!error){
			$('#declararInformacionCentroAcopio').attr('data-opcion','actualizarInformacionCentroAcopio');  
			ejecutarJson($(this)); 
		}else{
			if (errorTipo){
				$("#estado").html("Por favor registre al menos un horario.").addClass("alerta");
			}else{
				$("#estado").html("Por favor revise el formato de la información ingresada.").addClass("alerta");
			}
		}
		
	 });	


	/***
	  * validación por placa vehicular
	  */

	$("#tipoProducto").change(function(event){
			$("#estado").html("").removeClass("alerta");
			$(".alertaCombo").removeClass("alertaCombo");
			$("#evaluarSolicitud").attr('data-rutaaplicacion', 'registroOperador');
	 		$("#evaluarSolicitud").attr('data-destino','dSubtipoProducto');
	 		$("#evaluarSolicitud").attr('data-opcion', 'combosOperador');
	 		$("#opcion").val('subTipoProducto');
	 		if($("#tipoProducto").val() == ''){
	 			$("#tipoProducto").addClass("alertaCombo");
				$("#estado").html("Por favor seleccione un tipo de producto.").addClass("alerta");
			}else{
				event.stopImmediatePropagation();
	 	 		abrir($("#evaluarSolicitud"),event,false);
	 	 		$("#evaluarSolicitud").removeAttr('data-destino');
	 	 		$("#evaluarSolicitud").attr('data-rutaaplicacion', 'revisionFormularios');
	 	 		$("#evaluarSolicitud").attr('data-opcion', 'evaluarDocumentosSolicitud');
	 	 		$("#nombreTipoProducto").val($("#tipoProducto option:selected").text());
			}
	});

	$("#agregarLaboratorio").click(function(){
		$("#evaluarSolicitud").attr('data-rutaaplicacion', 'registroOperador');
 		$("#evaluarSolicitud").attr('data-opcion', 'guardarNuevoProducto');
 		$("#opcion").val('productoLaboratorio');
 		if($('#contenedorProducto input:checkbox:checked').length < 1){
			$("#estado").html("Seleccione uno o más parámetros, métodos y rangos").addClass("alerta");
		}else{
			var respuesta = JSON.parse(ejecutarJson($("#evaluarSolicitud")).responseText);
			if (respuesta.estado === 'exito') {
				mostrarMensaje("Registro ingresado con exito","EXITO");
				actualizarTablaProductoLaboratorio(idOperadorTipoOperacion,idSolicitud); 
			}else{
				mostrarMensaje(respuesta.mensaje,"FALLO");
			}
			$("#evaluarSolicitud").removeAttr('data-destino');
 	 		$("#evaluarSolicitud").attr('data-rutaaplicacion', 'revisionFormularios');
 	 		$("#evaluarSolicitud").attr('data-opcion', 'evaluarDocumentosSolicitud');
	 	}
	});

	$('button.archivoPago, button.archivoSancion').click(function (event) {
		numero = Math.floor(Math.random()*100000000);
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , $("[name=idSolicitud]").val()+'_'+numero
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });

	function eliminarProducto(idSolicitud,idParamatroLaboratorio,idTipoOperacion){
		$.ajax({
  		url: "aplicaciones/registroOperador/eliminarProducto.php",
  		data: {"idSolicitud":idSolicitud,
			"idParamatroLaboratorio":idParamatroLaboratorio,
			"idTipoOperacion":idTipoOperacion},
  		type: "post",
  		success: function(data) {
			//para obtener los datos desde otros archivos
			actualizarTablaProductoLaboratorio(idOperadorTipoOperacion,idSolicitud);
  			},
  		error:function (xhr, ajaxOptions, thrownError) {
    	
  		}
		 });
	}

	function actualizarTablaProductoLaboratorio(idOperadorTipoOperacion,idSolicitud){

		$.ajax({
  			url: "aplicaciones/registroOperador/listarProductoLaboratorio.php",
  			data: {"idOperadorTipoOperacion":idOperadorTipoOperacion,
				"idSolicitud":idSolicitud},
  			type: "post",
  			success: function(data) {

				$('#analisisLaboratorioAgregados').html(data);
  			},
  			error:function (xhr, ajaxOptions, thrownError) {
    	
			}
		});
	}
	
	function controlarHoras(horaInicio, horaFin){
		if(horaFin > horaInicio){
            error=false;
        }else{
         $("#horaFinRecoleccion").addClass('alertaCombo');
         $("#estado").html("Los minutos ingresados están incorrectos, por favor actualice la información").addClass('alerta'); 
		 alert("La hora fin no puede ser menor o igual que la de inicio.");
		 error = true;
        }
		return error;
	}
</script>

