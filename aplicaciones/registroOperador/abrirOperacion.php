<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorFinanciero.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cr = new ControladorRegistroOperador();
$cf = new ControladorFinanciero();
$crs = new ControladorRevisionSolicitudesVUE();

$qSolicitud = $cr->abrirOperacion($conexion, $_SESSION['usuario'], $_POST['id']);

$qVariedades = $cr->variedadesXOperacionesXProductos($conexion, $_POST['id']);

$productos = $cr->obtenerProductosPorIdOperadorTipoOperacionHistorico($conexion, $qSolicitud[0]['idOperadorTipoOperacion'], $qSolicitud[0]['idHistorialOperacion'], $qSolicitud[0]['estado']);

$qcodigoTipoOperacion= $cc->obtenerCodigoTipoOperacion($conexion, $_POST['id']);
$opcionArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
$idArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');
$idOperadorTipoOperacion = $qSolicitud[0]['idOperadorTipoOperacion'];																	 

$usuario = $_SESSION['usuario'];

//Obtener monto a pagar
if($qSolicitud[0]['estado']=='verificacion'){
    $qMonto = $crs->buscarIdImposicionTasaXSolicitud($conexion, $_POST['id'], 'Operadores', 'Financiero');
    $qOrdenPago = $cf->obtenerOrdenPagoPorIdentificadorSolicitud($conexion, $_POST['id'], 'Operadores');
}

$qRepresentante = $cr->consultarDatosRepresentanteTecnicoPorOperadorTipoOperacionHistorico($conexion, $qSolicitud[0]['idOperadorTipoOperacion'], $qSolicitud[0]['idHistorialOperacion']);

$date = new DateTime($qSolicitud[0]['fechaAprobacion']);

$imprimirPrveedoresOrganicos = false;
$consultaDocumentoCGRIA = false;
$documentoOperador = false;
$formularioLaboratorio = '';

?>

<header>
	<h1>Solicitud Operador</h1>
</header>
	
		<fieldset id="resultado">
			<legend>Resultado de Inspección</legend>
			<div data-linea="1">
				<label>Resultado: </label> <?php echo $qSolicitud[0]['estado']; ?> <br/>
			</div>
			<div data-linea="2">
				<label>Observaciones: </label> <?php echo $qSolicitud[0]['observacion']; ?> <br/>
			</div>
			<div data-linea="3">
				<label>Fecha inicio vigencia: </label> <?php echo $date->format('Y-m-d H:i:s'); ?> <br/>
			</div>
			<?php 
   	 			if($qSolicitud[0]['fechaFinalizacion'] != ''){
    			 	echo '<div data-linea="4">
      				<label>Fecha finalización vigencia: </label>' . date('Y-m-d H:i:s', strtotime($qSolicitud[0]['fechaFinalizacion'])) .'</div>';
			    }
			  ?>
	</fieldset>

	<?php

//-- Material Reproductivo
	$verificacion=pg_numrows($cr->obtenerResultadoRevision($conexion,$qSolicitud[0]['idOperadorTipoOperacion']));
	if($verificacion>0){
		$qPlanificacionInspeccion=pg_fetch_assoc($cr->obtenerPlanificacionInspeccion($conexion,$qSolicitud[0]['idOperadorTipoOperacion']));
		if ($qPlanificacionInspeccion['id_planificacion']!=''){
			echo '<fieldset>
			<legend>Planificación inspección</legend>
			<div data-linea="1">
					<label>Nombre del técnico que realiza la inspección</label>
					<input type="text" id="tecnicoInspeccion" name="tecnicoInspeccion" value="'.$qPlanificacionInspeccion['nombre_tecnico'].'" readonly disabled/>
			</div>
			<div data-linea="2">
					<label>Fecha de inspección</label>
					<input type="text" id="fechaInspeccion" name="fechaInspeccion" value="'.$qPlanificacionInspeccion['fecha_inspeccion'].'" readonly disabled/>
			</div>
			<div data-linea="3">
					<label>Hora de inspección</label>
					<input type="text" id="horaInspeccion" name="horaInspeccion" placeholder="10:30" value="'.$qPlanificacionInspeccion['hora_inspeccion'].'" data-inputmask="'."'".'mask'."'".': '."'".'99:99'."'".'" readonly disabled/>						
			</div>
			</fieldset>';
		}
	}

	?>
		
	<fieldset>
			<legend>Datos del sitio y área</legend>
					
				<div data-linea="5">
					<label>Nombre del sitio: </label> <?php echo $qSolicitud[0]['nombreSitio']; ?>
				</div>
				<div data-linea="5">
					<label>Provincia: </label> <?php echo $qSolicitud[0]['provincia']; ?>
				</div>
				<div data-linea="6">
					<label>Cantón: </label> <?php echo $qSolicitud[0]['canton']; ?>
				</div>
				<div data-linea="6">
					<label>Parroquia: </label> <?php echo $qSolicitud[0]['parroquia']; ?>
				</div>
				<div data-linea="7">
					<label>Dirección: </label> <?php echo $qSolicitud[0]['direccionSitio']; ?>
				</div>
				<div data-linea="8">
					<label>Referencia: </label> <?php echo $qSolicitud[0]['referencia']; ?>
				</div>
				<hr>
	<?php 

	$i=40;

	foreach ($qSolicitud as $solicitud){

		echo ($i!=40?'<hr>':'');

		echo '
				<div data-linea='.$i.'>
					<label>Nombre del área: </label> ' . $solicitud['nombreArea'] . ' 
				</div>
				<div data-linea='.$i.'>
					<label>Código del área: </label> ' . $solicitud['codificacionArea'] . ' 
				</div>
				<div data-linea='.++$i.'>
					<label>Tipo de área: </label> ' . $solicitud['tipoArea'] . ' 
				</div>
				
				<div data-linea='.$i++.'>
					<label>Superficie utilizada: </label> ' . $solicitud['superficieArea'] . ' 
				</div>';
				if($solicitud['estadoArea']!=''){
						echo '
								<div data-linea='.$i.'>
									<label>Estado: </label> ' . $solicitud['estadoArea'] . ' 
								</div>
								<div data-linea='.++$i.'>	
									<label>Observación: </label> ' . $solicitud['observacionArea'] . '
								</div>';
						$var = $solicitud['informe'];
						if(!in_array($var, array("0", ""))){
								echo '<div data-linea='.++$i.'>
									<label>Informe: </label> 
									<a href="'.$solicitud['informe'].'" target="_blank">Descargar informe</a> 
								</div>';
							}
						}
			
			$i++;
	}	
	?>	
	</fieldset>
	
	<?php switch ($idArea){

		case 'AI':
			
			switch ($opcionArea){
				case 'ACO':
				
                $qCentroAcopio = $cr->obtenerDatosCentroAcopioArea($conexion, $qSolicitud[0]['idOperadorTipoOperacion']);
				$centroAcopio = pg_fetch_assoc($qCentroAcopio);
				
				?>
				
					<fieldset>
							<legend>Información del Centro de Acopio</legend>
							
							<div data-linea="1">
								<label>Capacidad Instalada: </label><?php echo ($centroAcopio['capacidad_instalada'] != "") ? $centroAcopio['capacidad_instalada'] : "S/N"; ?>
							</div>
							<div data-linea="1">
								<label>Unidad de medida: </label><?php echo ($centroAcopio['nombre'] != "") ? $centroAcopio['nombre'] : "S/N"; ?>
							</div>
							<div data-linea="2">
								<label>Número de trabajadores: </label><?php echo ($centroAcopio['numero_trabajadores'] != "") ? $centroAcopio['numero_trabajadores'] : "S/N"; ?>
							</div>
							<div data-linea="2">
								<label>Laboratorio: </label><?php echo ($centroAcopio['nombre_laboratorio_leche'] != "") ? $centroAcopio['nombre_laboratorio_leche'] : "S/N"; ?>
							</div>
							<div data-linea="4">
								<label>Número de proveedores: </label><?php echo ($centroAcopio['numero_proveedores'] != "") ? $centroAcopio['numero_proveedores'] : "S/N"; ?>
							</div>	
							<div data-linea="4">
								<label>Pertenece al MAG: </label><?php echo ($centroAcopio['pertenece_mag'] != "") ? $centroAcopio['pertenece_mag'] : "S/N"; ?>
							</div>	
							<div data-linea="5">
								<label>Horario de recepción matutina: </label><?php echo ($centroAcopio['hora_recoleccion_maniana'] != "") ? $centroAcopio['hora_recoleccion_maniana'] : "HH:NR"; ?>
							</div>
							<div data-linea="5">
								<label>Horario de recepción vespertina: </label><?php echo ($centroAcopio['hora_recoleccion_tarde'] != "") ? $centroAcopio['hora_recoleccion_tarde'] : "HH:NR"; ?>
							</div>
						</fieldset>	
				<?php
				break;
				case 'MDT': 
				
                $qDatosVehiculo = $cr->obtenerDatosVehiculoArea($conexion, $qSolicitud[0]['idOperadorTipoOperacion']);
				$datosVehiculo = pg_fetch_assoc($qDatosVehiculo);
				?>				
					<fieldset>
							<legend>Datos del Vehículo</legend>	
							<div data-linea="1">
								<label>Marca: </label><?php echo ($datosVehiculo['nombre_marca_vehiculo'] != "") ? $datosVehiculo['nombre_marca_vehiculo'] : "S/N"; ?>
							</div>
							<div data-linea="1">
								<label>Modelo: </label><?php echo ($datosVehiculo['nombre_modelo_vehiculo'] != "") ? $datosVehiculo['nombre_modelo_vehiculo'] : "S/N"; ?>
							</div>
							<div data-linea="2">
								<label>Tipo: </label><?php echo ($datosVehiculo['nombre_tipo_vehiculo'] != "") ? $datosVehiculo['nombre_tipo_vehiculo'] : "S/N"; ?>
							</div>
							<div data-linea="2">
								<label>Color: </label><?php echo ($datosVehiculo['nombre_color_vehiculo'] != "") ? $datosVehiculo['nombre_color_vehiculo'] : "S/N"; ?>
							</div>
							<div data-linea="3">
								<label>Clase: </label><?php echo ($datosVehiculo['nombre_clase_vehiculo'] != "") ? $datosVehiculo['nombre_clase_vehiculo'] : "S/N"; ?>
							</div>		
							<div data-linea="3">
								<label>Placa: </label><?php echo ($datosVehiculo['placa_vehiculo'] != "") ? $datosVehiculo['placa_vehiculo'] : "S/N"; ?>
							</div>
							<div data-linea="4">
					            <label>Tipo de tanque: </label><?php echo ($datosVehiculo['nombre_tipo_tanque_vehiculo'] != "") ? $datosVehiculo['nombre_tipo_tanque_vehiculo'] : "S/N"; ?>
							</div>
							<div data-linea="4">
								<label>Año:  </label><?php echo ($datosVehiculo['anio_vehiculo'] != "") ? $datosVehiculo['anio_vehiculo'] : "S/N"; ?>
							</div>
							<div data-linea="5">
								<label>Capacidad instalada: </label><?php echo ($datosVehiculo['capacidad_vehiculo'] != "") ? $datosVehiculo['capacidad_vehiculo'] : "S/N"; ?>
							</div>
							<div data-linea="5">
								<label>Unidad: </label><?php echo ($datosVehiculo['nombre'] != "") ? $datosVehiculo['nombre'] : "S/N"; ?>
							</div>	
							<div data-linea="6">
								<label>Hora de inicio de recolección: </label><?php echo ($datosVehiculo['hora_inicio_recoleccion'] != "") ? $datosVehiculo['hora_inicio_recoleccion'] : "HH:NR"; ?>
							</div>	
							<div data-linea="6">
								<label>Hora de fin de recolección: </label><?php echo ($datosVehiculo['hora_fin_recoleccion'] != "") ? $datosVehiculo['hora_fin_recoleccion'] : "HH:NR"; ?>
							</div>
						</fieldset>
					<?php
			break;
            case 'MDC':
                
                $qDatosVehiculo = $cr->obtenerDatosVehiculoArea($conexion, $qSolicitud[0]['idOperadorTipoOperacion']);
                $datosVehiculo = pg_fetch_assoc($qDatosVehiculo);
                ?>
					<fieldset>
							<legend>Datos del Vehículo</legend>	
							<div data-linea="1">
								<label>Registro de contenedor incluido la placa del vehículo: </label><?php echo ($datosVehiculo['registro_contenedor_vehiculo'] )  ?>
							</div>
							<div data-linea="2">
								<label>Placa: </label><?php echo ($datosVehiculo['placa_vehiculo'] != "") ? $datosVehiculo['placa_vehiculo'] : "S/N"; ?>
							</div>
							<div data-linea="2">
								<label>Marca: </label><?php echo ($datosVehiculo['nombre_marca_vehiculo'] != "") ? $datosVehiculo['nombre_marca_vehiculo'] : "S/N"; ?>
							</div>
							<div data-linea="3">
								<label>Modelo: </label><?php echo ($datosVehiculo['nombre_modelo_vehiculo'] != "") ? $datosVehiculo['nombre_modelo_vehiculo'] : "S/N"; ?>
							</div>
							<div data-linea="3">
								<label>Año:  </label><?php echo ($datosVehiculo['anio_vehiculo'] != "") ? $datosVehiculo['anio_vehiculo'] : "S/N"; ?>
							</div>
							<div data-linea="4">
								<label>Color: </label><?php echo ($datosVehiculo['nombre_color_vehiculo'] != "") ? $datosVehiculo['nombre_color_vehiculo'] : "S/N"; ?>
							</div>
							<div data-linea="4">
								<label>Clase: </label><?php echo ($datosVehiculo['nombre_clase_vehiculo'] != "") ? $datosVehiculo['nombre_clase_vehiculo'] : "S/N"; ?>
							</div>
							<div data-linea="5">
								<label>Servicio: </label><?php echo ($datosVehiculo['servicio'] != "") ? $datosVehiculo['servicio'] : "S/N"; ?>
							</div>
							<div data-linea="5">
								<label>Tipo: </label><?php echo ($datosVehiculo['nombre_tipo_vehiculo'] != "") ? $datosVehiculo['nombre_tipo_vehiculo'] : "S/N"; ?>
							</div>
							<div data-linea="6">
					            <label>Capacidad instalada: </label><?php echo ($datosVehiculo['capacidad_vehiculo'] != "") ? $datosVehiculo['capacidad_vehiculo'] : "S/N"; ?>
							</div>
							<div data-linea="6">
								<label>Unidad: </label><?php echo ($datosVehiculo['nombre'] != "") ? $datosVehiculo['nombre'] : "S/N"; ?>
							</div>	
							<div data-linea="7">
								<label>Tipo de contenedor: </label><?php echo ($datosVehiculo['tipo_contenedor'] != "") ? $datosVehiculo['tipo_contenedor'] : "S/N"; ?>
							</div>	
							<div data-linea="7">
								<label>Características del contenedor: </label><?php echo ($datosVehiculo['caracteristica_contenedor'] != "") ? $datosVehiculo['caracteristica_contenedor'] : "S/N"; ?>
							</div>
						</fieldset>
					<?php
			break;
            case 'PRO':
			case 'REC':
            case 'COM':
            case 'PRC':
                
                $seccionOrganicos = "";  
                $seccionOrganicosProveedoresProcesador = ""; 
                
                $qDatosOrganicos = $cr->obtenerDatosRegistroOrganicos($conexion, $qSolicitud[0]['idOperadorTipoOperacion'], $qSolicitud[0]['idHistorialOperacion'], $qSolicitud[0]['estado']);
                
                if(pg_num_rows($qDatosOrganicos)!= 0){
                    
                    $qCodigoPOA = $cr-> obtenerCodigoPoaOperador($conexion, $_SESSION['usuario'], $qSolicitud[0]['idTipoOperacion']);
					
					if(pg_num_rows($qCodigoPOA) != 0){
						
						$codigo = pg_fetch_assoc($qCodigoPOA);
						
						$codigoPOA = $codigo['codigo_poa'];
						$subCodigoPOA = $codigo['subcodigo_poa'];
						$rutaPoa = $codigo['ruta_poa'];
						
						if($opcionArea != 'COM'){
											
						    $contadorAgencia = 0;
						    $seccionOrganicos .= '<div data-linea="9">
										<table style="width: 100%">
										<thead><tr><th>#</th><th>Nombre agencia</th> <th>Tipo transición</th><th>Tipo producción</th><th>Producto</th></tr></thead>
										<tbody>';
							while($fila = pg_fetch_assoc($qDatosOrganicos)){
								$seccionOrganicos .= '<tr>
																	<td>'.++$contadorAgencia.'</td>'.
                                                                    '<td>'.$fila['nombre_agencia_certificadora'].'</td>'.
																	'<td>'.$fila['nombre_tipo_transicion'].'</td>'.
																	'<td>'.$fila['nombre_tipo_produccion'].'</td>'.
																	'<td>'.$fila['nombre_producto'].'</td></tr>';
							}						
									
						}else{
							
							$fila = pg_fetch_assoc($qDatosOrganicos);						
							$nombreAgenciaCertificadora = '<div data-linea="11">
																<label>Agencia certificadora: </label>' . $fila['nombre_agencia_certificadora']. '
															<div>';
															
						}
						
						$seccionOrganicos .= '</tbody>
										</table>
									</div>' . $nombreAgenciaCertificadora . '
									<div data-linea="11">
											<label>Código POA: </label>' . $codigoPOA . '
									</div>
									<div data-linea="12">
									<label>Subcódigo POA: </label>' . $subCodigoPOA . '
									</div>';
						
						if($opcionArea == "COM"){
							
						    $proveedores = $cr->obtenerProveedoresPorIdOperadorTipoOperacionHistorico($conexion, $qSolicitud[0]['idOperadorTipoOperacion'], $qSolicitud[0]['idHistorialOperacion'], $opcionArea, $qSolicitud[0]['estado']);
							
							$seccionOrganicosProveedoresProcesador .= '<table style="width: 100%">
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
							
							$contadorProductos = 0;
							
							while ($fila = pg_fetch_assoc($proveedores)){
								$imprimirPrveedoresOrganicos = true;
								$seccionOrganicosProveedoresProcesador .= '<tr><td>'.++$contadorProductos.'</td><td>'.$fila['nombre_proveedor'].'</td><td>'.$fila['codigo_poa'].'</td><td>'.$fila['nombre'].'</td><td>'.$fila['nombre_producto'].'</td><td>'.$fila['nombre_tipo_transicion'].'</td></tr>';
							}
							
						}else if($opcionArea == "PRC"){
												   
						    $proveedores = $cr->obtenerProveedoresPorIdOperadorTipoOperacionHistorico($conexion, $qSolicitud[0]['idOperadorTipoOperacion'], $qSolicitud[0]['idHistorialOperacion'], $opcionArea, $qSolicitud[0]['estado']);
							
							$seccionOrganicosProveedoresProcesador .= '<table style="width: 100%">
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
							
							$contadorProductos = 0;
							
							while ($fila = pg_fetch_assoc($proveedores)){
								$imprimirPrveedoresOrganicos = true;
								$seccionOrganicosProveedoresProcesador .= '<tr><td>'.++$contadorProductos.'</td><td>'.$fila['nombre_proveedor'].'</td><td>'.$fila['codigo_poa'].'</td><td>'.$fila['nombre'].'</td><td>'.$fila['nombre_producto'].'</td><td>'.$fila['nombre_tipo_transicion'].'</td></tr>';
							}
							
						}
									
						if($qSolicitud[0]['estado'] == "registrado"){
						
    						$seccionOrganicos .= '<div data-linea="13">
    								<label>Ver Certificado POA:</label>
    									<a href="' . $rutaPoa . '" target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar certificado de Registro de Operador Orgánico</a>
    							</div>';
    					}
						
					}else{
                        $seccionOrganicos .= '<div data-linea="13">
                				<label>Ver Certificado POA:</label> Usted no posee operaciones en estado aprobado.
                			</div>';
                    }
                    
                }
                
                break;
			
			}
		break;
		case 'IAP':
		case 'IAV':
		case 'IAF':
		case 'CGRIA':
			$consultaDocumentoCGRIA = true;
			switch ($opcionArea){
				case 'ALM':
					$qDocumentosOperador = $cr->obtenerDocumentoGeneradoPorSecuencialIdentificadorOperadorTipoDocumento($conexion, $qSolicitud[0]['idSitio'], $qSolicitud[0]['identificador'], 'riaAlmacenistas');
				break;
				default:
					$qDocumentosOperador = $cr->obtenerDocumentoGeneradoPorSecuencialIdentificadorOperadorTipoDocumento($conexion, '1', $qSolicitud[0]['identificador'], 'riaEmpresas');
			}
			
			if(pg_num_rows($qDocumentosOperador)!= 0){
				$documentoOperador = true;
			}
		break;
		case 'SV':
			switch ($opcionArea) {
				case 'PRP':
				case 'VVE':
				case 'MIM':
				case 'ALM':
					$res = $cr->obtenerOperacionesXIdOperadorTipoOperacion($conexion, $idOperadorTipoOperacion, "'registrado','registradoObservacion','porCaducar'");					
					if(pg_num_rows($res) == 0){
						$consultaDocumentoCGRIA = true;
					}
				break;
			}
		break;  
		case 'LT':
		switch ($opcionArea) {
			case 'LDI':
			case 'LDA':
			case 'LDE':
			
			$seccionLaboratorios = "";

				$seccionLaboratorios .= '<fieldset >
				<legend>Análisis acreditados</legend>
					<table style="width: 100%">
					<thead>
					<tr>
						<th>#</th>
						<th>Producto</th>
						<th>Parámetro</th>
						<th>Método</th>
						<th>Rango</th>
					</tr>
				</thead>
						<tbody>';

				$item = 1;
				$listaParametros = $cr->obtenerParamtrosLaboratorioOperaciones($conexion, $idOperadorTipoOperacion);

				while ($fila = pg_fetch_assoc($listaParametros)) {
					$seccionLaboratorios .= '<tr>' .
						'<td>' . $item++ . '</td>' .
						'<td>' . $fila['nombre_producto'] . '</td>' .
						'<td>' . $fila['nombre_parametro'] . '</td>' .
						'<td>' . $fila['nombre_metodo'] . '</td>' .
						'<td>' . $fila['descripcion_rango'] . '</td>' .
						'</tr>';
				}

				$seccionLaboratorios .= '</tbody>
				</table>
				</fieldset>';
			break;
		}
	break;   
	}

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
								</tr>
							</thead>
							<tbody>';

			while ($fila = pg_fetch_assoc($qRepresentante)) {

				echo '<tr>
						<td>'.$fila['identificacion_representante'].'</td>
						<td>'.$fila['nombre_representante'].'</td>
						<td>'.$fila['titulo_academico'].'</td>
						<td>'.($fila['id_area_representante'] =='SA'? 'Sanidad Animal': ($fila['id_area_representante'] =='SV'? 'Sanidad Vegetal': ($fila['id_area_representante'] =='IAV'? 'Pecuarios': ($fila['id_area_representante'] =='IAP'? 'Agrícolas': ($fila['id_area_representante'] =='IAF'? 'Fertilizantes': 'N/A'))))).'</td>
					</tr>';
			}

			echo '</tbody>
				</table>
			</fieldset>';
	}
?>

	<fieldset>
			<legend>Datos de la operación </legend>
			
			<input type="hidden" id="idSolicitud" name="idSolicitud" value=<?php echo $qSolicitud[0]['idSolicitud']; ?> />
			<div data-linea="4">
				<label>Operación: </label> <?php echo $qSolicitud[0]['tipoOperacion']; ?> <br/>
			</div>
			<?php 
   	 			if($qSolicitud[0]['nombrePais'] != ''){
    			 	echo '<div data-linea="4">
      				<label>País: </label>' .  $qSolicitud[0]['nombrePais'] .'</div>';
			    }
			    
			    if(pg_num_rows($productos)!= 0){
			        
			    	if($imprimirPrveedoresOrganicos){
			            echo $seccionOrganicosProveedoresProcesador;
			        }else{
			  ?>
			  
			<table style="width: 100%">
			<thead>
				<tr>
					<th>#</th>
					<th>Tipo producto</th>
					<th>Subtipo producto</th>
					<th>Producto</th>
					<th>Código</th> 
				</tr>
			</thead>
			<tbody>
			<?php
			$contadorProducto = 0;
			
			while ($fila = pg_fetch_assoc($productos)){
				echo '<tr><td>'.++$contadorProducto.'</td>
                          <td>'.$fila['nombre_tipo'].'</td>
                          <td>'.$fila['nombre_subtipo'].'</td>
                          <td>'.$fila['nombre_comun'].'</td>
						  <td>'.$fila['id_operacion'].'</td></tr>';
			}				
			
			?>			
			</tbody>
		</table>
		
		<?php }}?>
			
			<div data-linea="8">
				
			<?php 
				if (pg_num_rows($qVariedades)>=1)
				echo '<label>Variedad de producto: </label>';
				
				$myString='';
				
				while($variedades = pg_fetch_assoc($qVariedades)){
					$myString .= '</label>'.$variedades['nombre'].', ';
				}
				$myString =trim($myString,', ');
				echo $myString;
			?>
			</div>

			<?php 
				
			     echo $seccionOrganicos; // Línea que imprime datos de operaciones orgánicas en caso de existir
			
				if($qSolicitud[0]['estado']=='verificacion'){
					echo '<div data-linea="7">
						<label>Monto a pagar:</label> <span class="alerta">$ '.pg_fetch_result($qMonto, 0, 'monto').'</span>
                        </br><a href="'.pg_fetch_result($qOrdenPago, 0, 'orden_pago').'" target="_blank" class="archivo_cargado" id="archivo_cargado">Orden de pago: </a>
					</div>';
				}
			?>
	</fieldset>
	<?php
	switch ($idArea) {

		case 'LT':

			switch ($opcionArea) {
				case 'LDI':
				case 'LDA':
				case 'LDE':

					echo '<fieldset >
					<legend>Análisis acreditados</legend>
						<table style="width: 100%">
						<thead>
						<tr>
							<th>#</th>
							<th>Matriz</th>
							<th>Parámetro</th>
							<th>Método</th>
							<th>Rango</th>
						</tr>
					</thead>
							<tbody>';

					$item = 1;
					$listaParametros = $cr->obtenerProductosLaboratorios($conexion, $idOperadorTipoOperacion);

					while ($fila = pg_fetch_assoc($listaParametros)) {


						echo '<tr>' .
							'<td>' . $item++ . '</td>' .
							'<td>' . $fila['nombre_comun'] . '</td>' .
							'<td>' . $fila['nombre_parametro'] . '</td>' .
							'<td>' . $fila['nombre_metodo'] . '</td>' .
							'<td>' . $fila['descripcion_rango'] . '</td>' .
							'</tr>';
					}

					echo '</tbody>
				</table>
			</fieldset>';


					break;
			}
			break;
			
	}

	//----------------------------------------------DOCUMENTOS GENERADOS PROCESO INSPECCION-----------------------------------------------------------------------------------------------

	if(!$consultaDocumentoCGRIA){
		$qDocumentosOperador = $cr->obtenerDocumentoGeneradoInspeccionPorIdentificador($conexion, $qSolicitud[0]['idSolicitud']);
		if(pg_num_rows($qDocumentosOperador)!= 0){
			$documentoOperador = true;
		}else{
			$qDocumentosOperador = $cr->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, $qSolicitud[0]['idOperadorTipoOperacion']);
			if(pg_num_rows($qDocumentosOperador)!= 0){
				$documentoOperador = true;
			}else{
				$documentoOperador = false;
			}
		}
	}
	
	$numeroDocumento = 1;
	if($documentoOperador){
		echo'<div>
		<fieldset>
			<legend>Documentos generados</legend>
				<table>';
				echo '<div data-linea="'.++$contador.'">
					</div>';
	
		while ($documentoOperador = pg_fetch_assoc($qDocumentosOperador)){
			echo '<div data-linea="'.++$contador.'"><label>'.$numeroDocumento++.'.-  </label><a href="'.$documentoOperador['ruta_archivo'].'" target="_blank" class="archivo_cargado">'.$documentoOperador['nombre'].'</a></div>';
		}
		echo '</table>
		</fieldset>
		</div>';
	}
	
	
	$condicion = $cr->obtenerCondicionTipoOperacion($conexion, $qSolicitud[0]['idTipoOperacion'], 'actualizarOperacion');
//---------------------------------------------------------------------------------------------------------------------------------------------
	if(pg_num_rows($condicion) != 0 && $qSolicitud[0]['estado'] == 'registrado'){
	?>


	<form id="actualizarOperacion" data-rutaAplicacion="registroOperador" data-opcion="iniciarProcesoActualizacion" data-accionEnExito="ACTUALIZAR" >
		<input type="hidden" name="idSolicitud" value="<?php echo $_POST['id'];?>"/>
		<input type="hidden" name="idTipoOperacion" value="<?php echo $qSolicitud[0]['idTipoOperacion'];?>"/>
		<input type="hidden" name="idOperadorTipoOperacion" value="<?php echo $qSolicitud[0]['idOperadorTipoOperacion'];?>"/>
		<input type="hidden" name="idHistorialOperacion" value="<?php echo $qSolicitud[0]['idHistorialOperacion'];?>"/>
		<button id="enviarSolicitud" type="submit" class="guardar">Iniciar proceso actualización</button>
	</form>
	
	<?php 
	   }
	?>

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();
		$("#resultado").hide();	
		//cambio de rechazado a noHabilitado
		if (<?php echo '"'.$qSolicitud[0]['estado'].'"';?> == "registrado" || <?php echo '"'.$qSolicitud[0]['estado'].'"';?> == "noHabilitado" || <?php echo '"'.$qSolicitud[0]['estado'].'"';?> == "rechazado" || <?php echo '"'.$qSolicitud[0]['estado'].'"';?> == "subsanacion" || <?php echo '"'.$qSolicitud[0]['estado'].'"';?> == "subsanacionRepresentanteTecnico" || <?php echo '"'.$qSolicitud[0]['estado'].'"';?> == "subsanacionProducto" || <?php echo '"'.$qSolicitud[0]['estado'].'"';?> == "registradoObservacion"){
			$("#resultado").show();
		}
	});

	$("#actualizarOperacion").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});
</script>
