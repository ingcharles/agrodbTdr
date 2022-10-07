<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVigenciaDocumentos.php';
require_once '../../clases/ControladorProgramasControlOficial.php';		

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();
$cvd = new ControladorVigenciaDocumentos();
$cac = new ControladorAdministrarCatalogos();

$operaciones = ($_POST['elementos']==''?$_POST['id']:$_POST['elementos']);
$idGrupoOperaciones = explode(",",($_POST['elementos']==''?$_POST['id']:$_POST['elementos']));

$identificadorInspctor =  $_SESSION['usuario'];

$qOperadorSitio = $cr->obtenerOperadorSitioInspeccion($conexion,$operaciones);
$operadorSitio = pg_fetch_assoc($qOperadorSitio);

$existePredio = false;	

///////////////////////////////////////////////////////////
/*foreach ($idGrupoOperaciones as $idSolicitud){
	$qSitio[]  = pg_fetch_result($cr->obtenerOperadorSitioInspeccion($conexion, $idSolicitud), 0, 'id_sitio');	
}
$sitio = array_unique($qSitio);*/

$fecha1= date('Y-m-d - H-i-s');
$fecha = str_replace(' ', '', $fecha1);

$qOperacion=$cr->abrirOperacionXid($conexion, $operaciones);
$operacion = pg_fetch_assoc($qOperacion);

$qTipoOperacion = $cc->obtenerDatosTipoOperacion($conexion, $operacion['id_tipo_operacion']);
$tipoOperacion = pg_fetch_assoc($qTipoOperacion);

$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
$idHistoricoOperacion = $operacion['id_historial_operacion'];

$idVigenciaDocumento = $operacion['id_vigencia_documento'];//CAMBIADO

$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
$historialOperacion = pg_fetch_assoc($qHistorialOperacion);

$productos = $cr->obtenerProductosPorIdOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $operacion['estado'], $idVigenciaDocumento);//TODO:CAMBIADO
$contador = 0;

$qRepresentante = $cr->consultarDatosRepresentanteTecnicoPorOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion']);

$qVigenciaDocumento = $cvd->obtenerVigenciaDeclaradaPorIdVigenciaXEtapaVigencia($conexion, $idVigenciaDocumento, 'inspeccion');

$bandera = false;
$procesoSanidadAnimal = false;
$procesoPecuarioExportacion = false;
$procesoFeriaExposicion = false;
$procesoMercanciaPecuaria = false;
$validacionSubtipoProducto = array();
$formularioCentroPecuario = '';
$formularioFeriaExposicion = '';
$comboRequiereAprobacion = '';
$comboTiempoAprobacionTemporal = '';


$idflujoOPeracion = pg_fetch_assoc($cr->obtenerIdFlujoXOperacion($conexion, $operaciones));
$idFlujoActual = pg_fetch_assoc($cr->obtenerEstadoActualFlujoOperacion($conexion, $idflujoOPeracion['id_flujo_operacion'], 'inspeccion'));

if($idFlujoActual['estado_alterno']!= ''){
	$subsanacion = '<option value="'.$idFlujoActual['estado_alterno'].'">Subsanación</option>';
}

$qcodigoTipoOperacion= $cc->obtenerCodigoTipoOperacion($conexion, $operaciones);
$opcionArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
$idArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');

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
				        <div data-linea="1">
                			<label for="marca">*Marca: </label>
                           '.$datosVehiculo['nombre_marca_vehiculo'];
              


                $formularioRecolectorLeche .= '</select>
                	    </div>
                		<div id="resultadoMarca" data-linea="1">
                			<label for="modelo">*Modelo: </label>
                            '.$datosVehiculo['nombre_modelo_vehiculo'];
                
                $formularioRecolectorLeche .= '</select>
                		</div>
                		<div id="resultadoModelo" data-linea="2">
                			<label for="clase">*Clase: </label>
                         '.$datosVehiculo['nombre_clase_vehiculo'];
              
                $formularioRecolectorLeche .= '</select>
		                </div>
                		<div data-linea="2">
                			<label for="color">*Color: </label>
							'.$datosVehiculo['nombre_color_vehiculo'];
                
                $formularioRecolectorLeche .= '</select>
                		</div>
                		<div id="resultadoClase" data-linea="3">
                			<label for="tipo">*Tipo: </label>
							'.$datosVehiculo['nombre_tipo_vehiculo'];
				$formularioRecolectorLeche .= '</select>
							</div>
							<div id="placa" data-linea="3">
								<label for="placa">*Placa: </label>
								'.$datosVehiculo['placa_vehiculo'];
				
             
        
                while ($tipoTanque = pg_fetch_assoc($qTipoTanque)) {
					if($datosVehiculo['id_tipo_tanque_vehiculo'] == $tipoTanque['id_item'])
					$valortipotanque=$tipoTanque['nombre'].'<br>';
                   // $formularioRecolectorLeche .= '<option value="' . $tipoTanque['id_item'] . '">' . $tipoTanque['nombre'] . '</option>';
                }

				$formularioRecolectorLeche .= '</select>
				</div>
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

						$formularioRecolectorLeche .= '</select>
								</div>
							
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
		}
		
	break;
	
	case 'SA':
		
		switch ($opcionArea){
			case 'COM':
			case 'IND':
			case 'PRO':
				$procesoSanidadAnimal = true;
				$procesoPecuarioExportacion = true;
				$formularioCentroPecuario ='<fieldset>
				        						<legend>Datos adicionales del sitio</legend>
													<div data-linea="1">
														<label>Tipo sitio</label>
														<select id="tipoSitio" name="tipoSitio">
															<option value="">Seleccione....</option>
															<option value="Centro">Centro</option>
															<option value="Establecimiento">Establecimiento</option>
														</select>
													</div>
													<div data-linea="2">
														<label>Coordenada X:</label> 
														<input type="text" id="coordenadax" name="coordenadax" value="' . $operadorSitio['longitud'] . '"/>
													</div>
													<div data-linea="2">
														<label>Coordenada Y:</label> 
														<input type="text" id="coordenaday" name="coordenaday" value="' . $operadorSitio['latitud'] . '"/>
													</div>
													<div data-linea="2">
														<label>Zona:</label> 
														<input type="text" id="zona" name="zona" value="' . $operadorSitio['zona'] . '"/>
													</div>
											</fieldset>';
			break;
			case 'FEA':
				$procesoSanidadAnimal = true;
				$procesoFeriaExposicion = true;
				$formularioFeriaExposicion = '<div data-linea="5" class="mostrarElemento">
												<label>Días de permiso</label>
												<select id="cantidadDias" name="cantidadDias">
													<option value="">Seleccione....</option>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
													<option value="6">6</option>
													<option value="7">7</option>
													<option value="8">8</option>
													<option value="9">9</option>
													<option value="10">10</option>
													<option value="11">11</option>
													<option value="12">12</option>
													<option value="13">13</option>
													<option value="14">14</option>
													<option value="15">15</option>
												</select></div>
												<div data-linea="5" class="mostrarElemento">
													<label>Fecha del evento</label><input type="text" name="fechaEvento" id="fechaEvento" readonly="readonly"/>
												</div>';
			break;
			case 'CPE':
			case 'EPE':
				$procesoMercanciaPecuaria = true;
				$formularioCentroPecuario ='<fieldset>
				        						<legend>Datos adicionales del sitio</legend>
													<div data-linea="2">
														<label>Coordenada X:</label>
														<input type="text" id="coordenadax" name="coordenadax" value="' . $operadorSitio['longitud'] . '"/>
													</div>
													<div data-linea="2">
														<label>Coordenada Y:</label>
														<input type="text" id="coordenaday" name="coordenaday" value="' . $operadorSitio['latitud'] . '"/>
													</div>
													<div data-linea="2">
														<label>Zona:</label>
														<input type="text" id="zona" name="zona" value="' . $operadorSitio['zona'] . '"/>
													</div>
											</fieldset>';
				
				$formularioMercanciaPecuaria = '<fieldset>
													<legend>Productos a exportar</legend>
												
													<div data-linea="1">
														<table id="productosExportar" style="width: 100%">
															<thead>
																<tr>
																	<th>Producto</th>
																	<th>Páis de destino</th>
																	<th>Uso destinado</th>
																</tr>
															</thead>
															<tbody>';
																	$qProductos = $cr->obtenerRegistroMercanciasPecuaria($conexion, $operacion['identificador_operador'], $idOperadorTipoOperacion);
																	while ($fila = pg_fetch_assoc($qProductos)){
																		$formularioMercanciaPecuaria .= '<tr>' .
																											'<td>'.$fila['nombre_producto'].'</td>'.
																											'<td>'.$fila['nombre_pais'].'</td>'.
																											'<td>'.$fila['uso'].'</td>'.
																										'</tr>';
																	}
				$formularioMercanciaPecuaria .=				'</tbody>
														</table>
													</div>
												</fieldset>';
				
			break;
			
			case 'PRA':
			case 'SEA':
			case 'POA':
			    
			    $qInformacionColmenar = $cr->listarInformacionColmenar($conexion, $operacion['id_operacion'], $idOperadorTipoOperacion);
			    $informacionColmenar = pg_fetch_assoc($qInformacionColmenar);
			    			    
			    $procesoSanidadAnimalApicola = true;
			    			    
			    $formularioInformacionColmenar ='<form id="declararInformacionColmenar" data-rutaAplicacion="registroOperador" data-opcion="actualizarDeclararInformacionColmenar" >
                                                 <input type="hidden" class="idDatoColmenar" name="idDatoColmenar" value="' . $informacionColmenar['id_dato_colmenar'] . '" />
                                        	     <input type="hidden" class="idSitio" name="idSitio" value="' . $informacionColmenar['id_sitio'] . '" />
                                        	        <fieldset>
                                                		<legend>Información de los colmenares</legend>	
                                                        <div data-linea="1">			
                                                			<label>Localización de los colmenares: </label>' . $informacionColmenar['nombre_lugar'] . '
                                                		</div>
                                                        <br></br>
                                                        <div data-linea="2">			
                                                			<label>Lat. UTM: </label><input type="text" name="latitud" id="latitud" value="' . $informacionColmenar['latitud'] . '" disabled="disabled" >
                                                		</div>
                                                        <div data-linea="2">			
                                                			<label>Lon. UTM: </label><input type="text" name="longitud" id="longitud" value="' . $informacionColmenar['longitud'] . '" disabled="disabled" >
                                                		</div>
                                                        <div data-linea="2">			
                                                			<label>Zona: </label><input type="text" name="zona" id="zona" value="' . $informacionColmenar['zona'] . '" disabled="disabled" >
                                                		</div>
                                                        <hr/>
                                                		<div data-linea="3">			
                                                			<label>¿El oper. es dueño del Sitio? </label>
                                                		</div>
                                                		<div data-linea="3">			
                                                			<label>SI </label> <input type="radio" id="duenioSitioSi" name="duenioSitio" value="SI" checked disabled="disabled" >
                                                		</div>
                                                		<div data-linea="3">			
                                                			<label>NO </label> <input type="radio" id="duenioSitioNo" name="duenioSitio" value="NO" disabled="disabled" >
                                                		</div>
                                                		<hr/>
                                                		<div data-linea="4">			
                                                			<label>Número de colmenares: </label> <input type="number" id="numeroColmenares" name="numeroColmenares" min="1" onkeypress="ValidaSoloNumeros()" onpaste="return false" value ="' . $informacionColmenar['numero_colmenar'] . '" disabled="disabled" >
                                                		</div>
                                                		<div data-linea="5">			
                                                			<label>Número promedio de colmenas: </label> <input type="number" id="numeroPromedioColmenas" name="numeroPromedioColmenas" min="1" onkeypress="ValidaSoloNumeros()" onpaste="return false" value="' . $informacionColmenar['numero_promedio_colmenas'] . '" disabled="disabled" >
                                                		</div>
                                                    </fieldset>                                            	
                                                    <div>
                                						<button id="modificar" type="button" class="editar">Modificar</button>
                                						<button id="actualizar" type="submit" class="guardar" disabled="disabled" disabled="disabled">Actualizar</button>
                                					</div>                                                    
                                                </form>';
		
			break;

			// control cambios material reproductivo
			case 'PMR':
			case 'CPM':
			case 'DMR':
			case 'AMR':		
				
				$resultado=pg_fetch_assoc($cr->obtenerPlanificacionInspeccion($conexion,$idOperadorTipoOperacion));
				$inspeccionMaterialPrpagativo=pg_num_rows($cr->obtenerResultadoRevision($conexion,$idOperadorTipoOperacion));
				
				$formularioPlanificacionInspeccion='<fieldset>
				<legend>Planificación inspección</legend>
				<div data-linea="1">
						<label>Nombre del técnico que realiza la inspección</label>
						<input type="text" id="tecnicoInspeccion" name="tecnicoInspeccion" value="'.$resultado['nombre_tecnico'].'" />
				</div>
				<div data-linea="2">
						<label>Fecha de inspección</label>
						<input type="text" id="fechaInspeccion" name="fechaInspeccion" value="'.$resultado['fecha_inspeccion'].'" readonly disabled/>
				</div>
				<div data-linea="3">
						<label>Hora de inspección</label>
						<input type="text" id="horaInspeccion" name="horaInspeccion" placeholder="10:30" value="'.$resultado['hora_inspeccion'].'" data-inputmask="'."'".'mask'."'".': '."'".'99:99'."'".'" readonly disabled/>						
				</div>
				</fieldset>';

				if($inspeccionMaterialPrpagativo==0){
					$comboRequiereAprobacion='<div data-linea="2" class="mostrarElementoRequiereAprobacion">
					<label>Requiere aprobación</label>
					<select id="requiereAprobacion" name="requiereAprobacion">
						<option value="">Seleccione....</option>						
						<option value="si">Si</option>
						<option value="no">No</option>						
					</select></div>
					';					
				
					$comboTiempoAprobacionTemporal='<div data-linea="3" class="mostrarElementoTiempoAprobacion">
					<label>Tiempo aprobación temporal</label>
					<select id="tiempoAprobacion" name="tiempoAprobacion">		
						<option value="">Seleccione....</option>				
						<option value="3">Aprobado bajo condición por 3 meses</option>
						<option value="6">Aprobado bajo condición por 6 meses</option>						
					</select></div>
					';					
				}

			break;		
			// fin control cambios material reproductivo
			
			case 'OEC':
			    
			    $cpco = new ControladorProgramasControlOficial();
			    
			    $resultado=pg_fetch_assoc($cr->obtenerPlanificacionInspeccion($conexion,$idOperadorTipoOperacion));
			    $inspeccionMaterialPrpagativo=pg_num_rows($cr->obtenerResultadoRevision($conexion,$idOperadorTipoOperacion));
			    
			    $formularioPlanificacionInspeccion='<fieldset>
                                        				<legend>Planificación inspección</legend>
                                            				<div data-linea="1">
                                            						<label>Nombre del técnico que realiza la inspección</label>
                                            						<input type="text" id="tecnicoInspeccion" name="tecnicoInspeccion" value="'.$resultado['nombre_tecnico'].'" '. ($opcionArea=='OEC'?'readonly="readonly" disabled':'') . '/>
                                            				</div>
                                            				<div data-linea="2">
                                            						<label>Fecha de inspección</label>
                                            						<input type="text" id="fechaInspeccion" name="fechaInspeccion" value="'.$resultado['fecha_inspeccion'].'" readonly disabled/>
                                            				</div>
                                            				<div data-linea="3">
                                            						<label>Hora de inspección</label>
                                            						<input type="text" id="horaInspeccion" name="horaInspeccion" placeholder="10:30" value="'.$resultado['hora_inspeccion'].'" data-inputmask="'."'".'mask'."'".': '."'".'99:99'."'".'" readonly disabled/>
                                            				</div>
                                    				</fieldset>';
			    
			    //Verificar si existe este registro en el módulo de programas de control oficial, predio de équidos
			    $predio =$cpco->buscarCatastroPredioEquidosRegistroOperador($conexion, $operaciones, $opcionArea);
			    
			    if(pg_num_rows($predio) > 0){
			        $existePredio = true;
			    }else{
			        $existePredio = false;
			    }
			    
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

		<div data-linea="4">
			<label>Nombre sitio: </label> <?php echo $operadorSitio['nombre_lugar']; ?> 
		</div>
		
		<div data-linea="5">
			<label>Provincia: </label> <?php echo $operadorSitio['provincia']; ?> 
		</div>

		<div data-linea="5">
			<label>Canton: </label> <?php echo $operadorSitio['canton']; ?> <br />
		</div>

		<div data-linea="5">
			<label>Parroquia: </label> <?php echo $operadorSitio['parroquia']; ?> <br />
		</div>

		<div data-linea="6">
			<label>Dirección: </label> <?php echo $operadorSitio['direccion']; ?> <br />
		</div>
		
	</fieldset>
	
	<?php 

	echo $formularioAcopiadorLeche;
	echo $formularioRecolectorLeche;
	echo $formularioInformacionColmenar;
	
	?>

	<form id="evaluarSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarElementosSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $identificadorInspctor;?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $operaciones;?>"/>
		<input type="hidden" name="tipoSolicitud" value="Operadores"/>
		<input type="hidden" name="tipoInspector" value="Técnico"/>
		<input type="hidden" name="identificadorOperador" value="<?php echo $operadorSitio['identificador'];?>"/> <!-- USUARIO OPERADOR -->
		<!--input type="hidden" name="idVue" value="< ?php echo $qSolicitud[0]['idVue'];?>"/-->
		<input type="hidden" name="tipoElemento" value="Área"/>
		<input type="hidden" name="nombreOpcion" value="<?php echo $nombreOpcion;?>"/>
		<input type="hidden" name="idOperadorTipoOperacion" value="<?php echo $idOperadorTipoOperacion;?>"/>
		<input type="hidden" name="idHistoricoOperacion" value="<?php echo $idHistoricoOperacion;?>"/>
		<input type="hidden" name="codigoProvinciaSitio" value="<?php echo $operadorSitio['codigo_provincia'];?>"/>
		<input type="hidden" name="idSitio" value="<?php echo $operadorSitio['id_sitio'];?>"/>
		<input type="hidden" name="provinciaSitio" value="<?php echo $operadorSitio['provincia'];?>"/>
		<input type="hidden" name="fechaInicio" value="<?php echo $fechaInicio;?>"/>

	<fieldset>
		<legend>Operación, área</legend>
	
	<?php 
	$contador = 40;	
	foreach ($idGrupoOperaciones as $solicitud){
		$registros = array();
		$qAreasOperador = $cr->obtenerOperadorOperacionAreaInspeccion($conexion, $solicitud);
		
		while($areaOperacion = pg_fetch_assoc($qAreasOperador)){
			$registros[] = array('nombreArea' => $areaOperacion['nombre_area'], 'tipoArea' => $areaOperacion['tipo_area'], 'nombreOperacion' => $areaOperacion['nombre_operacion'], 
								'idArea' => $areaOperacion['id_area'], 'superficieUtilizada' => $areaOperacion['superficie_utilizada'], 'idOperacion' => $areaOperacion['id_operacion']);
		}
		
		$qDocumentosAdjuntos = $cr->obtenerDocumentosAdjuntoXoperacion($conexion, $solicitud);
		$documentoAdjunto = (pg_num_rows($qDocumentosAdjuntos)!= 0 ? true : false);
		
		echo ($contador!=40?'<hr>':'');
		
		echo'
		<div data-linea="'.$contador.'">
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
					<label>'.$areas['nombreArea'].' ('. $areas['superficieUtilizada'].' '.$unidadMedida .')</label> 
						<input type="text" name="observacionAreas[]" placeholder="Observación"/>
						<input type="hidden" name="idAreas[]" value="'.$areas['idArea'].'"/>
						<input type="hidden" name="idOperaciones[]" value="'.$areas['idOperacion'].'"/>
				</div>';			
		}
			
		if($bandera == false){
		
			if($documentoAdjunto){
		
				echo '<div data-linea="'.++$contador.'">
							<label>Documentos adjuntos: </label></div>';
				
				while ($documento = pg_fetch_assoc($qDocumentosAdjuntos)){
					echo '<div data-linea="'.++$contador.'"><label>'.$documento['titulo'].'.-  </label><a href="'.$documento['ruta_documento'].'">'.$documento['descripcion'].'</a></div>';
				}
			}
		
		}	
			
		$contador++;
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
	$contadoProducto = 0;
	if(pg_num_rows($productos)!= 0){?>
	<fieldset id="datosProducto">
		<legend>Productos</legend>
				
		<table style="width: 100%">
			<thead>
				<tr>
					<th>#</th>
					<th>Tipo producto</th>
					<th>Subtipo producto</th>
					<th>Producto</th>
				</tr>
			</thead>
			<tbody>
			<?php
				while ($fila = pg_fetch_assoc($productos)){
					$validacionSubtipoProducto[] = $fila['codificacion_subtipo_producto'];
					echo '<tr><td>'.++$contadoProducto.'</td><td>'.$fila['nombre_tipo'].'</td><td>'.$fila['nombre_subtipo'].'</td><td>'.$fila['nombre_comun'].'</td></tr>';
				}
				
				$validacionSubtipoProducto = array_unique($validacionSubtipoProducto);
				
			?>
			</tbody>
		</table>
		
		<input type='hidden' name='validacionSubtipoProducto' value='<?php echo serialize($validacionSubtipoProducto);?>'/>
		
	</fieldset>
	<?php }?>	
	
	<?php 
	echo $formularioPlanificacionInspeccion; //control cambios material reproductivo (imprime formulario de planificacion de inspeccion)
	echo $formularioMercanciaPecuaria; 	
	?>
	
	<fieldset>
		<legend>Informe de revisión</legend>
		
		<div id="clasificacion">
			
			<input type="radio" name="clasificacion" id="iPostRegistro" value="postregistro">
			<label for="iPostRegistro">Post registro</label><br/>
			<input type="radio" name="clasificacion" id="iInspeccion" value="inspeccion">
			<label for="iInspeccion">Inspección</label><br/>

		</div>
		
		<p class="nota">Seleccione el tipo de inspección</p>
		
		<div id="subirInforme">
			<!-- input type="file" name="informe" id='informe' />
			<input type="hidden" id="archivo" name="archivo" value="0"/-->
			<label>Cargar informe:</label>	
			<input type="file" class="archivo" name="informe" accept="application/pdf"/>
			<input type="hidden" id="rutaArchivo" class="rutaArchivo" name="archivo" value="0"/>
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
			<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/registroOperador/informeOperacion" >Subir archivo</button>		
			<input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha;?> "/>
		</div>
						
	</fieldset>
	
	<?php echo $formularioCentroPecuario;?>
		
	<fieldset>
		<legend>Resultado de Revisión</legend>
				
			<div data-linea="1">
				<label>Resultado</label>
					<select id="resultado" name="resultado">
						<option value="">Seleccione....</option>
						<?php 
							while($vigenciaDocumento = pg_fetch_assoc($qVigenciaDocumento)){//TODO:CAMBIADO

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
								<option value="registrado" data-resultado ="registrado">Registrado</option>
							<?php }?>
								<option value="noHabilitado">No habilitado</option>
								<?php echo $subsanacion;?>
					</select>
			</div>
			<?php echo $formularioFeriaExposicion;
			// materila reproductivo
			echo $comboRequiereAprobacion;
			echo $comboTiempoAprobacionTemporal;
			//fin material reproductivo
			?>
			<div data-linea="4">
				<label>Observaciones</label>
					<input type="text" id="observacion" name="observacion" maxlength="500"/>
			</div>
	</fieldset>
		
		<button id="guardarInspeccion" type="submit" class="guardar">Enviar resultado</button>
	</form>	
	

<script type="text/javascript">

var identificador_inspector = <?php echo json_encode($identificadorInspctor); ?>;

var bandera = <?php echo json_encode($bandera); ?>;
var proceso_sa = <?php echo json_encode($procesoSanidadAnimal); ?>;
var proceso_pecuario = <?php echo json_encode($procesoPecuarioExportacion); ?>;
var proceso_mercancia = <?php echo json_encode($procesoMercanciaPecuaria); ?>;
var proceso_feria = <?php echo json_encode($procesoFeriaExposicion);?>;
var proceso_apicola = <?php echo json_encode($procesoSanidadAnimalApicola); ?>;

var idArea = <?php echo json_encode($idArea); ?>;
var opcionArea = <?php echo json_encode($opcionArea); ?>;

var existePredio = <?php echo json_encode($existePredio); ?>;

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();

		$("#fechaEvento").datepicker({
		      changeMonth: true,
		      changeYear: true,
		      minDate: 0,
		      maxDate: "+1M"
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

		$('.mostrarElemento').hide();
		$('.mostrarElementoRequiereAprobacion').hide(); // material reproductivo
		$('.mostrarElementoTiempoAprobacion').hide(); // material reproductivo

		if(proceso_apicola){
			$("#latitud").numeric();
			$("#longitud").numeric();
			$("#zona").numeric();
			$('input:radio[name="duenioSitio"]').filter('[value="<?php echo $informacionColmenar['duenio_sitio_colmenar']; ?>"]').prop("checked", true);		
		}
		
		if(opcionArea == 'OEC'){
			if(existePredio == true){
				$('#guardarInspeccion').removeAttr('disabled');
			}else{
				$('#guardarInspeccion').attr('disabled','disabled');
				alert('No existe un registro de este predio en el módulo de Programas de Control Oficial. Por favor debe registrar la información del operador para poder continuar con la aprobación.');
			}
		}		
	});

	// material reproductivo
	$("#requiereAprobacion").change(function(){
		if($(this).val()=='si'){
			$(".mostrarElementoTiempoAprobacion").show();
		} else{
			$(".mostrarElementoTiempoAprobacion").hide();
		}
		
	});
	// fin material reproductivo

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
		if(bandera){		
			$("#iPostRegistro").attr("disabled", true);
		}
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
	
    $('button.subirArchivo').click(function (event) {
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        numero = Math.floor(Math.random()*100000000);	

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , identificador_inspector+ "_" +numero+$('#fecha').val().replace(/ /g,'')
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, $("#no"))
            );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });

	
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCamposInspeccion(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("input:radio[name=clasificacion]:checked").val() == null){
			error = true;
			$("#clasificacion label").addClass("alertaCombo");
		}
		
		if($("#iInspeccion:checked").val() == "inspeccion"){
			if($("#rutaArchivo").val() == 0){
				error = true;
				$("input[name='informe']").addClass("alertaCombo");
			}			
		}
		
		
		if(!$.trim($("#resultado").val()) || !esCampoValido("#resultado")){
			error = true;
			$("#resultado").addClass("alertaCombo");
		}

		if($("#resultado option:selected").attr('data-resultado') == 'registrado' && proceso_sa){

			if(proceso_pecuario){
				
				if(!$.trim($("#tipoSitio").val())){
					error = true;
					$("#tipoSitio").addClass("alertaCombo");
				}

				if(!$.trim($("#coordenadax").val())){
					error = true;
					$("#coordenadax").addClass("alertaCombo");
				}

				if(!$.trim($("#coordenaday").val())){
					error = true;
					$("#coordenaday").addClass("alertaCombo");
				}

				if(!$.trim($("#zona").val())){
					error = true;
					$("#zona").addClass("alertaCombo");
				}
			}

			if(proceso_mercancia){

				if(!$.trim($("#coordenadax").val())){
					error = true;
					$("#coordenadax").addClass("alertaCombo");
				}

				if(!$.trim($("#coordenaday").val())){
					error = true;
					$("#coordenaday").addClass("alertaCombo");
				}

				if(!$.trim($("#zona").val())){
					error = true;
					$("#zona").addClass("alertaCombo");
				}
			}

			if(proceso_feria){

				if(!$.trim($("#cantidadDias").val())){
					error = true;
					$("#cantidadDias").addClass("alertaCombo");
				}

				if(!$.trim($("#fechaEvento").val())){
					error = true;
					$("#fechaEvento").addClass("alertaCombo");
				}
			}
		}
	
		if($("#resultado").val() == 'noHabilitado' || $("#resultado").val() == 'subsanacion'){
			if(!$.trim($("#observacion").val()) || !esCampoValido("#observacion")){
				error = true;
				$("#observacion").addClass("alertaCombo");
			}
		}

		// material reproductivo
		if($("#tecnicoInspeccion").length>0){
			if($("#tecnicoInspeccion").val() == ''){
				error = true;
				$("#tecnicoInspeccion").addClass("alertaCombo");
			}
		}
		
		if(idArea=='SA'){
			switch(opcionArea){
				case 'PMR':
				case 'CPM':
				case 'DMR':
				case 'AMR':	

				if($("#resultado").val()=="subsanacionRepresentanteTecnico"){
					if($("#requiereAprobacion").val() == ''){
						error = true;
						$("#requiereAprobacion").addClass("alertaCombo");
					}

					if($("#requiereAprobacion").val() == 'si'){
						if($("#tiempoAprobacion").val() == ''){
							error = true;
							$("#tiempoAprobacion").addClass("alertaCombo");
						}
					}
				}
				
				break;
			}
		}
		// fin material reproductivo
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
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

		if($("#horaRecoleccionManiana").val() == ""){	
			error = true;	
			errorTipo = true; 
			$("#horaRecoleccionManiana").addClass("alertaCombo");
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
	 
	 $("#resultado").change(function(){
		if($("#resultado option:selected").attr('data-resultado') == 'registrado' && proceso_sa){
			$('.mostrarElemento').show();
			distribuirLineas();
		}else{
			$('.mostrarElemento').hide();
		}

		// material reproductivo
		if(idArea=='SA'){
			switch(opcionArea){
				case 'PMR':
				case 'CPM':
				case 'DMR':
				case 'AMR':	
					if($("#resultado").val()=='subsanacionRepresentanteTecnico'){
						$(".mostrarElementoRequiereAprobacion").show();
					} else{
						$('.mostrarElementoRequiereAprobacion').hide();
						$('.mostrarElementoTiempoAprobacion').hide();
					}
				break;
			}
		}
		//fin material reproductivo
	});	 

	function ValidaSoloNumeros() {
		 if ((event.keyCode < 48) || (event.keyCode > 57))
		  event.returnValue = false;
	}
	 
    $("#declararInformacionColmenar").submit(function(event){
        
    	event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;		

		if($.trim($("#numeroColmenares").val()) == "" || $("#numeroColmenares").val() == 0){
			error = true;
			$("#numeroColmenares").addClass("alertaCombo");
		}	

		if($.trim($("#numeroPromedioColmenas").val()) == "" || $("#numeroPromedioColmenas").val() == 0){
			error = true;
			$("#numeroPromedioColmenas").addClass("alertaCombo");
		}		

		if($.trim($("#latitud").val()) == "" || $("#latitud").val() == 0){
			error = true;
			$("#latitud").addClass("alertaCombo");
		}	

		if($.trim($("#longitud").val()) == "" || $("#longitud").val() == 0){
			error = true;
			$("#longitud").addClass("alertaCombo");
		}	

		if($.trim($("#zona").val()) == "" || $("#zona").val() == 0){
			error = true;
			$("#zona").addClass("alertaCombo");
		}		
    	
    	if (!error){
    		$('#declararInformacionColmenar').attr('data-opcion','actualizarDeclararInformacionColmenar');  
    		ejecutarJson($(this)); 
    	}else{
    		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
    	}
    	
     });
	/***
	  * validación por placa vehicular
	  */
	  $("#placa").blur(function(event){

		    event.stopImmediatePropagation();
			$(".alertaCombo").removeClass("alertaCombo");
			var error = true;
			
		   if(!$.trim($("#placa").val())  ){
			    error = false;
			   $('#servicio').val('');
			}
			if (error){
				var placa = $("#placa").val().replace('-','');
				placa = placa.toUpperCase();
	         	$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
	          	$.post("aplicaciones/general/consultaWebServices.php", 
	                      {
	          		        clasificacion:"AntMatriculaLicencia",
	                  		numero:placa
	                      }, function (data) {
	                      	$("#cargarMensajeTemporal").html("");
	                      	    if (data.estado === 'exito') {
	                      	    	$("#servicio").val(data.valores.tipo_Servicio);
	                              } else {
	                              	mostrarMensaje(data.mensaje, "FALLO");
	                              	$('#servicio').val('');
	                              	$("#placa").addClass("alertaCombo");
	                		    	$('#placa').attr('placeholder',$("#placa").val());
	                		    	$("#placa").val('');
	                              }
	                  }, 'json');
			}
	 });
	 
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
