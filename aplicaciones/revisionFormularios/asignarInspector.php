<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';



//Controladores por solicitud
require_once '../../clases/ControladorClv.php';
require_once '../../clases/ControladorFitosanitario.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorTramitesInocuidad.php';
require_once '../../clases/ControladorCertificadoCalidad.php';
require_once '../../clases/ControladorDestinacionAduanera.php';
require_once '../../clases/ControladorZoosanitarioExportacion.php';
require_once '../../clases/ControladorImportacionesFertilizantes.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';
require_once '../../clases/ControladorTransitoInternacional.php';																					  


$conexion = new Conexion();
$cc = new ControladorCatalogos();
$ca = new ControladorAreas();
$cu = new ControladorUsuarios();
$crs = new ControladorRevisionSolicitudesVUE();

$operacion=explode(",",$_POST['elementos']);

//Formulario y tipo de inspeccion
$formulario=explode("-",$_POST['id']);
$tipoSolicitud = $formulario[0];
$procesoRevision = $formulario[1];

$usuarioPorArea = true;
$usuarioProvincia = false;

$provincia = $_SESSION['nombreProvincia'];

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<h1>Asignar técnico para inspección <?php echo $procesoRevision;?></h1>
</header>

<div id="estado"></div>

	<p>Las <b>solicitudes</b> a ser asignadas son: </p>
 
        <?php
			if($operacion[0]!=null){
				
				//Guardar resultado solicitud (cambio de estado)
				switch ($tipoSolicitud){
					
				case 'operadoresSV' :
						$cr = new ControladorRegistroOperador();
						
						for ($i = 0; $i < count ($operacion); $i++) {
							
							$solicitud = pg_fetch_assoc($cr->obtenerOperadorProductoOperacion($conexion, $operacion[$i]));
							$area = $cr->abrirOperacionAreasAsignacion($conexion, $operacion[$i]);
							
							echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_operacion'].'</legend>
										
										<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['nombre_operador']. '<br/>
										</div>
										<div data-linea="3">
											<label>Tipo operación: </label>'.$solicitud['nombre_tipo_operacion']. '<br/>
										</div>
										<div data-linea="4">
											<label>Área operación: </label>'.$area. '<br/>
										</div>
										<div data-linea="5">
											<label>Tipo producto: </label>'.$solicitud['nombre_tipo']. '<br/>
										</div>
										<div data-linea="6">
											<label>Subtipo producto: </label>'.$solicitud['nombre_subtipo']. '<br/>
										</div>
										<div data-linea="7">
											<label>Producto: </label>'.$solicitud['nombre_producto']. '<br/>
										</div>
										<div data-linea="8">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>
										
								</fieldset>';								
						}
						
						$nombreSolicitud = 'Registro de operador';
						$perfil = 'PFL_REV_OPER_SV';
						$formularioGeneral = 'Operadores';
					break;
					
				case 'operadoresSA' :
				    $cr = new ControladorRegistroOperador();
				    
				    for ($i = 0; $i < count ($operacion); $i++) {
				        
				        $solicitud = pg_fetch_assoc($cr->obtenerOperadorProductoOperacion($conexion, $operacion[$i]));
				        $area = $cr->abrirOperacionAreasAsignacion($conexion, $operacion[$i]);
				        
				        echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_operacion'].'</legend>
											    
										<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['nombre_operador']. '<br/>
										</div>
										<div data-linea="3">
											<label>Tipo operación: </label>'.$solicitud['nombre_tipo_operacion']. '<br/>
										</div>
										<div data-linea="4">
											<label>Área operación: </label>'.$area. '<br/>
										</div>
										<div data-linea="5">
											<label>Tipo producto: </label>'.$solicitud['nombre_tipo']. '<br/>
										</div>
										<div data-linea="6">
											<label>Subtipo producto: </label>'.$solicitud['nombre_subtipo']. '<br/>
										</div>
										<div data-linea="7">
											<label>Producto: </label>'.$solicitud['nombre_producto']. '<br/>
										</div>
										<div data-linea="8">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>
						    
								</fieldset>';
				    }
				    
				    $nombreSolicitud = 'Registro de operador';
				    $perfil = 'PFL_REV_OPER_SA';
				    $formularioGeneral = 'Operadores';
				    break;
				    
				case 'operadoresAGR' :
				    $cr = new ControladorRegistroOperador();
				    
				    for ($i = 0; $i < count ($operacion); $i++) {
				        
				        $solicitud = pg_fetch_assoc($cr->obtenerOperadorProductoOperacion($conexion, $operacion[$i]));
				        $area = $cr->abrirOperacionAreasAsignacion($conexion, $operacion[$i]);
				        
				        echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_operacion'].'</legend>
											    
										<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['nombre_operador']. '<br/>
										</div>
										<div data-linea="3">
											<label>Tipo operación: </label>'.$solicitud['nombre_tipo_operacion']. '<br/>
										</div>
										<div data-linea="4">
											<label>Área operación: </label>'.$area. '<br/>
										</div>
										<div data-linea="5">
											<label>Tipo producto: </label>'.$solicitud['nombre_tipo']. '<br/>
										</div>
										<div data-linea="6">
											<label>Subtipo producto: </label>'.$solicitud['nombre_subtipo']. '<br/>
										</div>
										<div data-linea="7">
											<label>Producto: </label>'.$solicitud['nombre_producto']. '<br/>
										</div>
										<div data-linea="8">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>
				        
								</fieldset>';
				    }
				    
				    $nombreSolicitud = 'Registro de operador';
				    $perfil = 'PFL_REV_OPER_AGR';
				    $formularioGeneral = 'Operadores';
				    break;
				    
				case 'operadoresFER' :
				    $cr = new ControladorRegistroOperador();
				    
				    for ($i = 0; $i < count ($operacion); $i++) {
				        
				        $solicitud = pg_fetch_assoc($cr->obtenerOperadorProductoOperacion($conexion, $operacion[$i]));
				        $area = $cr->abrirOperacionAreasAsignacion($conexion, $operacion[$i]);
				        
				        echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_operacion'].'</legend>
											    
										<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['nombre_operador']. '<br/>
										</div>
										<div data-linea="3">
											<label>Tipo operación: </label>'.$solicitud['nombre_tipo_operacion']. '<br/>
										</div>
										<div data-linea="4">
											<label>Área operación: </label>'.$area. '<br/>
										</div>
										<div data-linea="5">
											<label>Tipo producto: </label>'.$solicitud['nombre_tipo']. '<br/>
										</div>
										<div data-linea="6">
											<label>Subtipo producto: </label>'.$solicitud['nombre_subtipo']. '<br/>
										</div>
										<div data-linea="7">
											<label>Producto: </label>'.$solicitud['nombre_producto']. '<br/>
										</div>
										<div data-linea="8">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>
				        
								</fieldset>';
				    }
				    
				    $nombreSolicitud = 'Registro de operador';
				    $perfil = 'PFL_REV_OPER_FER';
				    $formularioGeneral = 'Operadores';
				    break;
				    
				case 'operadoresALM':
					$cr = new ControladorRegistroOperador();
					
					for ($i = 0; $i < count ($operacion); $i++) {
						
						$solicitud = pg_fetch_assoc($cr->obtenerOperadorProductoOperacion($conexion, $operacion[$i]));
						$area = $cr->abrirOperacionAreasAsignacion($conexion, $operacion[$i]);
						
						echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_operacion'].'</legend>
											
										<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['nombre_operador']. '<br/>
										</div>
										<div data-linea="3">
											<label>Tipo operación: </label>'.$solicitud['nombre_tipo_operacion']. '<br/>
										</div>
										<div data-linea="4">
											<label>Área operación: </label>'.$area. '<br/>
										</div>
										<div data-linea="5">
											<label>Tipo producto: </label>'.$solicitud['nombre_tipo']. '<br/>
										</div>
										<div data-linea="6">
											<label>Subtipo producto: </label>'.$solicitud['nombre_subtipo']. '<br/>
										</div>
										<div data-linea="7">
											<label>Producto: </label>'.$solicitud['nombre_producto']. '<br/>
										</div>
										<div data-linea="8">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>
				        	
								</fieldset>';
					}
					
					$nombreSolicitud = 'Registro de operador';
					$formularioGeneral = 'Operadores';
					$perfil = 'PFL_REV_OPER_ALM';
				break;
				    
				case 'operadoresPEC' :
				    $cr = new ControladorRegistroOperador();
				    
				    for ($i = 0; $i < count ($operacion); $i++) {
				        
				        $solicitud = pg_fetch_assoc($cr->obtenerOperadorProductoOperacion($conexion, $operacion[$i]));
				        $area = $cr->abrirOperacionAreasAsignacion($conexion, $operacion[$i]);
				        
				        echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_operacion'].'</legend>
											    
										<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['nombre_operador']. '<br/>
										</div>
										<div data-linea="3">
											<label>Tipo operación: </label>'.$solicitud['nombre_tipo_operacion']. '<br/>
										</div>
										<div data-linea="4">
											<label>Área operación: </label>'.$area. '<br/>
										</div>
										<div data-linea="5">
											<label>Tipo producto: </label>'.$solicitud['nombre_tipo']. '<br/>
										</div>
										<div data-linea="6">
											<label>Subtipo producto: </label>'.$solicitud['nombre_subtipo']. '<br/>
										</div>
										<div data-linea="7">
											<label>Producto: </label>'.$solicitud['nombre_producto']. '<br/>
										</div>
										<div data-linea="8">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>
				        
								</fieldset>';
				    }
				    
				    $nombreSolicitud = 'Registro de operador';
				    $perfil = 'PFL_REV_OPER_PEC';
				    $formularioGeneral = 'Operadores';
				    break;
					
					
					case 'operadoresLT' :
						$cr = new ControladorRegistroOperador();
					
						for ($i = 0; $i < count ($operacion); $i++) {
								
							$solicitud = pg_fetch_assoc($cr->obtenerOperadorProductoOperacion($conexion, $operacion[$i]));
							$area = $cr->abrirOperacionAreasAsignacion($conexion, $operacion[$i]);
								
							echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_operacion'].'</legend>
					
										<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['nombre_operador']. '<br/>
										</div>
										<div data-linea="3">
											<label>Tipo operación: </label>'.$solicitud['nombre_tipo_operacion']. '<br/>
										</div>
										<div data-linea="4">
											<label>Área operación: </label>'.$area. '<br/>
										</div>
										<div data-linea="5">
											<label>Tipo producto: </label>'.$solicitud['nombre_tipo']. '<br/>
										</div>
										<div data-linea="6">
											<label>Subtipo producto: </label>'.$solicitud['nombre_subtipo']. '<br/>
										</div>
										<div data-linea="7">
											<label>Producto: </label>'.$solicitud['nombre_producto']. '<br/>
										</div>
										<div data-linea="8">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>
					
								</fieldset>';
						}
						
						
						$idLaboratorio = pg_fetch_assoc($cc->buscarIdLaboratoriosDiagnosticoXprovincia($conexion, $provincia));
						$provincia = $cc->obtenerProvinciasXIdLaboratorioDIagnostico($conexion, $idLaboratorio['id_laboratorio_diagnostico']);
					
						$nombreSolicitud = 'Registro de Operador';
						$perfil = 'PFL_REV_OPER_LAB';
						$formularioGeneral = 'Operadores';
						$usuarioPorArea = false;
						$usuarioProvincia = true;
						
					break;
						
					case 'operadoresAI' :
							$cr = new ControladorRegistroOperador();
								
							for ($i = 0; $i < count ($operacion); $i++) {
						
								$solicitud = pg_fetch_assoc($cr->obtenerOperadorProductoOperacion($conexion, $operacion[$i]));
								$area = $cr->abrirOperacionAreasAsignacion($conexion, $operacion[$i]);
						
								echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_operacion'].'</legend>
			
										<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['nombre_operador']. '<br/>
										</div>
										<div data-linea="3">
											<label>Tipo operación: </label>'.$solicitud['nombre_tipo_operacion']. '<br/>
										</div>
										<div data-linea="4">
											<label>Área operación: </label>'.$area. '<br/>
										</div>
										<div data-linea="5">
											<label>Tipo producto: </label>'.$solicitud['nombre_tipo']. '<br/>
										</div>
										<div data-linea="6">
											<label>Subtipo producto: </label>'.$solicitud['nombre_subtipo']. '<br/>
										</div>
										<div data-linea="7">
											<label>Producto: </label>'.$solicitud['nombre_producto']. '<br/>
										</div>
										<div data-linea="8">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>
			
								</fieldset>';
							}
								
							$nombreSolicitud = 'Registro de Operador';
							$perfil = 'PFL_REV_OPER_AI';
							$formularioGeneral = 'Operadores';
							break;
				
					case 'Importación sanidad vegetal' : 
						$ci = new ControladorImportaciones();
						
						for ($i = 0; $i < count ($operacion); $i++) {
							$solicitud = pg_fetch_assoc($ci->abrirImportacionAsignacion($conexion, $operacion[$i]));
							$producto = $ci->abrirImportacionProductosAsignacion($conexion, $operacion[$i]);
							
							echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_solicitud'].'</legend>
											
											<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['nombre_operador']. '<br/>
										</div>
										<div data-linea="5">
											<label>Producto: </label>'.$producto. '<br/>
										</div>
										<div data-linea="6">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>			
													
								</fieldset>';	
							
						}
						
						//$nombreSolicitud = $tipoSolicitud;
						$nombreSolicitud = 'Importación';
						$perfil ='PFL_REV_IMP_SV';
						$tipoSolicitud = 'Importación';
						$formularioGeneral = $tipoSolicitud;
					break;
					
					
					case 'Importación sanidad animal' : 
						$ci = new ControladorImportaciones();
					
						for ($i = 0; $i < count ($operacion); $i++) {
							$solicitud = pg_fetch_assoc($ci->abrirImportacionAsignacion($conexion, $operacion[$i]));
							$producto = $ci->abrirImportacionProductosAsignacion($conexion, $operacion[$i]);
							
							echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_solicitud'].'</legend>
						
											<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['nombre_operador']. '<br/>
										</div>
										<div data-linea="5">
											<label>Producto: </label>'.$producto. '<br/>
										</div>
										<div data-linea="6">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>
							
								</fieldset>';
						}
					
						//$nombreSolicitud = $tipoSolicitud;
						$nombreSolicitud = 'Importación';
						$perfil ='PFL_REV_IMP_SA';
						$tipoSolicitud = 'Importación';
						$formularioGeneral = $tipoSolicitud;
					break;
					
					
					case 'Importación plaguicidas' : 
						$ci = new ControladorImportaciones();
					
						for ($i = 0; $i < count ($operacion); $i++) {
							$solicitud = pg_fetch_assoc($ci->abrirImportacionAsignacion($conexion, $operacion[$i]));
							$producto = $ci->abrirImportacionProductosAsignacion($conexion, $operacion[$i]);
							
							echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_solicitud'].'</legend>
							
											<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['nombre_operador']. '<br/>
										</div>
										<div data-linea="5">
											<label>Producto: </label>'.$producto. '<br/>
										</div>
										<div data-linea="6">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>
				
								</fieldset>';
						}
					
						//$nombreSolicitud = $tipoSolicitud;
						$nombreSolicitud = 'Importación';
						$perfil ='PFL_REV_IMP_IAP';
						$tipoSolicitud = 'Importación';
						$formularioGeneral = $tipoSolicitud;
					break;
					
					
					case 'Importación veterinarios' : 
						$ci = new ControladorImportaciones();
					
						for ($i = 0; $i < count ($operacion); $i++) {
							$solicitud = pg_fetch_assoc($ci->abrirImportacionAsignacion($conexion, $operacion[$i]));
							$producto = $ci->abrirImportacionProductosAsignacion($conexion, $operacion[$i]);
							
							echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_solicitud'].'</legend>
				
											<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['nombre_operador']. '<br/>
										</div>
										<div data-linea="5">
											<label>Producto: </label>'.$producto. '<br/>
										</div>
										<div data-linea="6">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>
							
								</fieldset>';
						}
					
						//$nombreSolicitud = $tipoSolicitud;
						$nombreSolicitud = 'Importación';
						$perfil ='PFL_REV_IMP_IAV';
						$tipoSolicitud = 'Importación';
						$formularioGeneral = $tipoSolicitud;
					break;
					
					case 'Importación fertilizantes' :
						$ci = new ControladorImportaciones();
						
						for ($i = 0; $i < count ($operacion); $i++) {
							$solicitud = pg_fetch_assoc($ci->abrirImportacionAsignacion($conexion, $operacion[$i]));
							$producto = $ci->abrirImportacionProductosAsignacion($conexion, $operacion[$i]);
							
							echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_solicitud'].'</legend>
							
											<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['nombre_operador']. '<br/>
										</div>
										<div data-linea="5">
											<label>Producto: </label>'.$producto. '<br/>
										</div>
										<div data-linea="6">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>
											
								</fieldset>';
						}
						
						//$nombreSolicitud = $tipoSolicitud;
						$nombreSolicitud = 'Importación';
						$perfil ='PFL_INS_IMP_FER';
						$tipoSolicitud = 'Importación';
						$formularioGeneral = $tipoSolicitud;
					break;
					
					case 'importacionMuestras' :
						$cif = new ControladorImportacionesFertilizantes();
						
						$usuarioPorArea = false;
						
						for ($i = 0; $i < count ($operacion); $i++) {
							$solicitud = pg_fetch_assoc($cif->abrirImportacionFertilizantes($conexion, $operacion[$i]));
							
							echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_importacion_fertilizantes'].'</legend>
							
											<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['razon_social']. '<br/>
										</div>
										<div data-linea="5">
											<label>Producto: </label>'.$solicitud['nombre_comercial_producto']. '<br/>
										</div>
										<div data-linea="6">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>
												
								</fieldset>';
						}
						
						//$nombreSolicitud = $tipoSolicitud;
						$nombreSolicitud = 'Importación de muestras';
						$perfil ='PFL_INS_IMP_MUE';
						$tipoSolicitud = $tipoSolicitud;
						$formularioGeneral = $tipoSolicitud;
					break;
					
				
					case 'DDA' :
						$cd = new ControladorDestinacionAduanera();
						
						for ($i = 0; $i < count ($operacion); $i++) {
							$solicitud = pg_fetch_assoc($cd->abrirDDAAsignacion($conexion, $operacion[$i]));
							$producto = $cd->abrirDDAProductosAsignacion($conexion, $operacion[$i]);
							
							echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_solicitud'].'</legend>
							
											<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['nombre_operador']. '<br/>
										</div>
										<div data-linea="5">
											<label>Producto: </label>'.$producto. '<br/>
										</div>
										<div data-linea="6">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>
				
								</fieldset>';
						}
						
						$nombreSolicitud = $tipoSolicitud;
						$perfil = 'PFL_INSDO_DESAD';
						$formularioGeneral = $tipoSolicitud;
					break;
					
				
					case 'Fitosanitario' :
						$cf = new ControladorFitosanitario();
						
						for ($i = 0; $i < count ($operacion); $i++) {
							$solicitud = pg_fetch_assoc($cf->abrirFitoAsignacion($conexion, $operacion[$i]));
							$producto = $cf->abrirFitoProductosAsignacion($conexion, $operacion[$i]);
							
							
							echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_solicitud'].'</legend>
				
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['razon_social']. '<br/>
										</div>
									
										<div data-linea="5">
											<label>Producto: </label>'.$producto. '<br/>
										</div>
										<div data-linea="6">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>
							
								</fieldset>';
						}
						
						$nombreSolicitud = $tipoSolicitud;
						$perfil ='PFL_REV_FIT_EXP';
						$formularioGeneral = $tipoSolicitud;
					break;
					
				
					case 'Zoosanitario' :
						$cz = new ControladorZoosanitarioExportacion();
						
						for ($i = 0; $i < count ($operacion); $i++) {
							$solicitud = pg_fetch_assoc($cz->abrirZooAsignacion($conexion, $operacion[$i]));
							$producto = $cz->abrirZooProductosAsignacion($conexion, $operacion[$i]);
							
							
							echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_solicitud'].'</legend>
				
											<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['nombre_operador']. '<br/>
										</div>
										<div data-linea="5">
											<label>Producto: </label>'.$producto. '<br/>
										</div>
										<div data-linea="6">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>
							
								</fieldset>';
						}
						
						$nombreSolicitud = $tipoSolicitud;
						$perfil = 'PFL_REV_ZOO_EXP';
						$formularioGeneral = $tipoSolicitud;
					break;
					
				
					case 'CLV' :
						$cl = new ControladorClv();
						
						for ($i = 0; $i < count ($operacion); $i++) {
							$solicitud = pg_fetch_assoc($cl->abrirClvAsignacion($conexion, $operacion[$i]));
							$producto = $cl->abrirClvProductosAsignacion($conexion, $operacion[$i]);
							
							echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_solicitud'].'</legend>
				
											<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['nombre_operador']. '<br/>
										</div>
										<div data-linea="5">
											<label>Producto: </label>'.$producto. '<br/>
										</div>
										<div data-linea="6">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>
							
								</fieldset>';
						}
						
						$nombreSolicitud = $tipoSolicitud;
						$perfil = 'PFL_REV_CLV';
						$formularioGeneral = $tipoSolicitud;
					break;
					
					case 'tramitesInocuidad':
						
						$cti = new ControladorTramitesInocuidad();
						$cr = new ControladorRegistroOperador();
						
						for ($i = 0; $i < count ($operacion); $i++) {
							$tramite = pg_fetch_assoc($cti->obtenerTramiteInocuidad($conexion, $operacion[$i]));
							$operador = pg_fetch_assoc($cr->buscarOperador($conexion, $tramite['identificador_operador']));
							$producto = pg_fetch_assoc($cc ->obtenerTipoSubtipoXProductos($conexion, $tramite['id_producto']));
							
							echo '
									<fieldset>
										<legend>Hoja de ruta N° </label>' .$tramite['id_tramite'].'</legend>
			
											<div data-linea="1">
												<label>Identificación: </label> '. $operador['identificador'].'
											</div>
											
											<div data-linea="2">
												<label>Razón social: </label> '. ($operador['razon_social']==''?$operador['apellido_representante'].' '.$operador['nombre_representante']:$operador['razon_social']).'
											</div>

											<hr/>
											
											<div data-linea="3">
												<label>Tipo producto: </label> '. $producto['nombre_tipo'].' 
											</div>
											
											<div data-linea="4">
												<label>Subtipo producto: </label> '.$producto['nombre_subtipo'].'
											</div>
											
											<div data-linea="5">
												<label>Producto: </label> '. $tramite['nombre_producto'].'
											</div>
			
											<hr/>
											<div data-linea="6">
												<label>Tipo tramite: </label>'. $tramite['nombre_tipo_tramite'].'
											</div>
											
											<div data-linea="7">
												<label>Observación: </label>'. ($tramite['observacion']==''?'Sin observación':$tramite['observacion']).'
											</div>
			
										</fieldset>
									
									';
						}
						
						$nombreSolicitud = $tipoSolicitud;
						$perfil = 'Inspector de tramites de inocuidad de los alimentos';
						$formularioGeneral = $tipoSolicitud;
					break;
					
					case 'certificadoCalidad':
						
						$cca = new ControladorCertificadoCalidad();
						
						$qCertificadoCalidad = $cca->obtenerSolicitudCertificadoCalidadXGrupoLotes($conexion, $_POST['elementos']);
						
						while ($certificadoCalidad = pg_fetch_assoc($qCertificadoCalidad)){
						
							echo'<fieldset>
									<legend>Solicitud # '.$certificadoCalidad['id_certificado_calidad'].' </legend>
											
									<div data-linea="1">
										<label>Datos exportador </label>
									</div>
							
									<div data-linea="2">
										<label>Identificación: </label> '. $certificadoCalidad['identificador_exportador'].'
									</div>
											
									<div data-linea="3">
										<label>Razón social: </label> '. $certificadoCalidad['razon_social_exportador'].'
									</div>
											
									<hr/>
											
									<div data-linea="4">
										<label>Datos del importador</label>
									</div>
							
									<div data-linea="5">
										<label>Nombre: </label> '. $certificadoCalidad['nombre_importador'].'
									</div>
											
									<div data-linea="6">
										<label>Dirección: </label> '. $certificadoCalidad['direccion_importador'].'
									</div>
											
									<hr/>
											
									<div data-linea="7">
										<label>Datos generales de exportación</label>
									</div>
											
									<div data-linea="8">
										<label>Fecha de embarque: </label> '. date('j/n/Y',strtotime($certificadoCalidad['fecha_embarque'])).'
									</div>
											
									<div data-linea="8">
										<label>Número de viaje: </label> '. $certificadoCalidad['numero_viaje'].'
									</div>
											
									<div data-linea="9">
										<label>País de embarque: </label> '. $certificadoCalidad['nombre_pais_embarque'].'
									</div>
											
									<div data-linea="9">
										<label>Puerto embarque: </label> '. $certificadoCalidad['nombre_puerto_embarque'].'
									</div>
											
									<div data-linea="10">
										<label>Medio de transporte: </label> '. $certificadoCalidad['nombre_medio_transporte'].'
									</div>
											
									<div data-linea="11">
										<label>País de destino: </label> '. $certificadoCalidad['nombre_pais_destino'].'
									</div>
											
									<div data-linea="11">
										<label>Puerto de destino: </label> '. $certificadoCalidad['nombre_puerto_destino'].'
									</div>';
											
									$lugarCertificadoCalidad = $cca->obtenerLugarXGrupoLotes($conexion,  $_POST['elementos'], $certificadoCalidad['id_certificado_calidad']);
								
									$i = 20;
								
									while ($lugarCertificado = pg_fetch_assoc($lugarCertificadoCalidad)){
											
										echo '
											<hr/>
												<div data-linea='.++$i.'>
													<label class="mayusculas">Lugar de inspección '.$lugarCertificado['nombre_area_operacion'].'</label>
												</div>
											<hr/>
								
												<div data-linea='.++$i.'>
													<label>Nombre provincia: </label> '. $lugarCertificado['nombre_provincia'].'
												</div>
								
												<div data-linea='.$i.'>
													<label>Fecha de inspección: </label> '.  date('j/n/Y',strtotime($lugarCertificado['solicitud_fecha_inspeccion'])).'
												</div>';
											
													$loteCertificadoInspeccion = $cca->obtenerLoteCertificadoCalidad($conexion,  $_POST['elementos'], $lugarCertificado['id_lugar_inspeccion']);
											
													$cantidadRegistros = pg_num_rows($loteCertificadoInspeccion);
											
													$aux = 0;
													while($loteCertificado = pg_fetch_assoc($loteCertificadoInspeccion)){
											
														$aux++;
														echo '
												<div data-linea='.++$i.'>
													<label>Nombre producto: '.$loteCertificado['nombre_producto'].'</label>
												</div>
											
												<div data-linea='.++$i.'>
													<label>Número lote: </label> '. $loteCertificado['numero_lote'].'
												</div>
									
												<div data-linea='.$i.'>
													<label>Valor FOB: </label> '. $loteCertificado['valor_fob'].'
												</div>
												<div data-linea='.++$i.'>
													<label>Peso neto: </label> '. $loteCertificado['peso_neto'] .' '.$loteCertificado['unidad_peso_neto'].'
												</div>
												<div data-linea='.$i.'>
													<label>Peso bruto: </label> '. $loteCertificado['peso_bruto'].' '.$loteCertificado['unidad_peso_bruto'].'
												</div>
												<div data-linea='.++$i.'>
													<label>Variedad: </label> '. $loteCertificado['nombre_variedad_producto'].'
												</div>
												<div data-linea='.$i.'>
													<label>Calidad: </label> '. $loteCertificado['nombre_calidad_producto'].'
												</div>';
											
														if($cantidadRegistros != $aux){
															echo '<hr/>';
														}
													}
												}
											
												echo'</fieldset>';
											}
						
						$nombreSolicitud = $tipoSolicitud;
						$perfil = 'Inspector certificado calidad';
						$formularioGeneral = $tipoSolicitud;
					break;
					
					case 'mercanciasSinValorComercialExportacion':
						
						$cme = new ControladorMercanciasSinValorComercial();
						
						for ($i = 0; $i < count ($operacion); $i++) {

							$solicitud = pg_fetch_assoc($cme->abrirSolicitudAsignacion($conexion, $operacion[$i]));
							$producto = $cme->obtenerSolicitudProductosAsignacion($conexion, $operacion[$i]);

							echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_solicitud'].'</legend>

											<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador_operador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['nombre_operador']. '<br/>
										</div>										
										<div data-linea="3">
											<label>Estado: </label>'.$solicitud['estado_solicitud']. '<br/>
										</div>
				
								</fieldset>';
							
							$res=$cme->obtenerDetalleSolicitud($conexion, $operacion[$i]);
							$fila=null;

							$contador=0;
							while($fila=pg_fetch_assoc($res)){
								$contador+=1;
								echo '<fieldset id="datosProducto">	<legend>Datos del Producto '.$contador.' :</legend>'.
										'<div data-linea="1"><label>Tipo de producto: </label>'.$fila['nombre_tipo'].'</div>'.
										'<div data-linea="2"><label>Subtipo: </label>'.$fila['nombre_subtipo'].'</div>'.
										'<div data-linea="2"><label>Producto: </label>'.$fila['nombre_comun'].'</div>'.
										'<div data-linea="3"><label>Sexo: </label>'.$fila['sexo_completo'].'</div>'.
										'<div data-linea="3"><label>Edad: </label>'.$fila['edad'].'</div>'.
										'<div data-linea="4"><label>Color: </label>'.$fila['color'].'</div>'.
										'<div data-linea="4"><label>Raza: </label>'.$fila['raza'].'</div>'.
										'<div data-linea="5"><label>Identificación: </label>'.$fila['identificacion_producto'].'</div>'.
									'</fieldset>';
									}
						}
						
						$nombreSolicitud = $tipoSolicitud;
						$perfil = 'PFL_ME_VA_IN_EXP';
						$formularioGeneral = $tipoSolicitud;
					break;
					
					case 'mercanciasSinValorComercialImportacion':
						
						$cme = new ControladorMercanciasSinValorComercial();
						for ($i = 0; $i < count ($operacion); $i++) {

							$solicitud = pg_fetch_assoc($cme->abrirSolicitudAsignacion($conexion, $operacion[$i]));
							$producto = $cme->obtenerSolicitudProductosAsignacion($conexion, $operacion[$i]);

							echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_solicitud'].'</legend>

											<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador_operador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['nombre_operador']. '<br/>
										</div>									
										<div data-linea="3">
											<label>Estado: </label>'.$solicitud['estado_solicitud']. '<br/>
										</div>
				
								</fieldset>';
							
							$res=$cme->obtenerDetalleSolicitud($conexion, $operacion[$i]);

									$contador=0;
									while($fila=pg_fetch_assoc($res)){
										$contador+=1;
										echo '<fieldset id="datosProducto">	<legend>Datos del Producto '.$contador.' :</legend>'.
												'<div data-linea="1"><label>Tipo de producto: </label>'.$fila['nombre_tipo'].'</div>'.
												'<div data-linea="2"><label>Subtipo: </label>'.$fila['nombre_subtipo'].'</div>'.
												'<div data-linea="2"><label>Producto: </label>'.$fila['nombre_comun'].'</div>'.
												'<div data-linea="3"><label>Sexo: </label>'.$fila['sexo_completo'].'</div>'.
												'<div data-linea="3"><label>Edad: </label>'.$fila['edad'].'</div>'.
												'<div data-linea="4"><label>Color: </label>'.$fila['color'].'</div>'.
												'<div data-linea="4"><label>Raza: </label>'.$fila['raza'].'</div>'.
												'<div data-linea="5"><label>Identificación: </label>'.$fila['identificacion_producto'].'</div>'.
												'</fieldset>';
									}
						}
						
						$nombreSolicitud = $tipoSolicitud;
						$perfil = 'PFL_ME_VA_IN_IMP';
						$formularioGeneral = $tipoSolicitud;
					break;
					
					case 'transitoInternacional' :
					    $cti = new ControladorTransitoInternacional();
					    
					    $usuarioPorArea = false;
					    
					    for ($i = 0; $i < count ($operacion); $i++) {
					        $solicitud = pg_fetch_assoc($cti->abrirTransitoInternacional($conexion, $operacion[$i]));
					        
					        echo '<fieldset>
										<legend>Solicitud N° </label>' .$solicitud['id_importacion_fertilizantes'].'</legend>
										    
											<div data-linea="1">
											<label>Identificador operador: </label>'.$solicitud['identificador']. '<br/>
										</div>
										<div data-linea="2">
											<label>Razón social: </label>'.$solicitud['razon_social']. '<br/>
										</div>
										<div data-linea="5">
											<label>Producto: </label>'.$solicitud['nombre_comercial_producto']. '<br/>
										</div>
										<div data-linea="6">
											<label>Estado: </label>'.$solicitud['estado']. '<br/>
										</div>
												    
								</fieldset>';
					        
					        $res=$cti->abrirTransitoInternacionalProductos($conexion, $operacion[$i]);
					        $fila=null;
					        
					        $contador=0;
					        while($fila=pg_fetch_assoc($res)){
					            $contador+=1;
					            echo '<fieldset id="datosProducto">	<legend>Datos del Producto '.$contador.' :</legend>'.
									            '<div data-linea="1"><label>Nombre del producto: </label>'.$fila['nombre_tipo'].'</div>'.
									            '<div data-linea="2"><label>Código del producto: </label>'.$fila['nombre_subtipo'].'</div>'.
									            '<div data-linea="2"><label>Subpartida arancelaria: </label>'.$fila['nombre_comun'].'</div>'.
									            '<div data-linea="3"><label>Cantidad de producto: </label>'.$fila['sexo_completo'].'</div>'.
									            '<div data-linea="3"><label>Peso: </label>'.$fila['edad'].'</div>'.
									            '</fieldset>';
					        }
					    }
					   					    
					    $nombreSolicitud = 'Tránsito Internacional';
					    $perfil ='PFL_INS_TRAN_INT';
					    $tipoSolicitud = $tipoSolicitud;
					    $formularioGeneral = $tipoSolicitud;
					    break;
					    
					default :
						echo 'Formulario desconocido';
					break;
					
				}
			}
		?>

<?php
if($usuarioPorArea){
	$area = pg_fetch_assoc($cu->obtenerAreaUsuario($conexion, $_SESSION['usuario']));
	
	if($area['categoria_area'] == '5'){
		$areaSubproceso = $ca->buscarAreasSubprocesos($conexion, $area['id_area_padre']);
		
		while ($fila = pg_fetch_assoc($areaSubproceso)){
			$areaBusqueda .= "'".$fila['id_area']."',";
		}
		
		$areaBusqueda .= "'".$area['id_area_padre']."',";
		$areaBusqueda = "(".rtrim($areaBusqueda,',').")";
		
	}else if($area['categoria_area'] == '4'){
		$areaSubproceso = $ca->buscarAreasSubprocesos($conexion, $area['id_area']);
		
		while ($fila = pg_fetch_assoc($areaSubproceso)){
			$areaBusqueda .= "'".$fila['id_area']."',";
		}
		
		$areaBusqueda .= "'".$area['id_area']."',";
		$areaBusqueda = "(".rtrim($areaBusqueda,',').")";
		
	}else{
		$areaBusqueda = "('No definido')";
		$advertencia = true;
	}
	
	//echo $area['id_area'];
	
	$usuario = $cu->obtenerUsuariosXareaPerfil($conexion, $areaBusqueda, $perfil);
}else{
	if($usuarioProvincia){
		$usuario = $cu->obtenerUsuariosPorCodigoPerfilProvinciaContrato($conexion, $perfil, "(".mb_strtoupper($provincia).")");
	}else{
		$usuario = $cu->obtenerUsuariosPorCodigoPerfil($conexion, $perfil);
	}
}
	
	
?>

<form id='asignarInspector' data-rutaAplicacion='revisionFormularios' data-opcion='guardarNuevoInspector' data-destino="detalleItem">	
	<?php 
		for ($i = 0; $i < count ($operacion); $i++) {
			echo'<input type="hidden" name="id[]" value="'.$operacion[$i].'"/>';
		}
	?>
	
	<input type="hidden" id="tipoSolicitud" name="tipoSolicitud" value="<?php echo $tipoSolicitud;?>" />
	<input type="hidden" id="procesoRevision" name="procesoRevision" value="<?php echo $procesoRevision;?>" />
	<input type="hidden" id="formularioGeneral" name="formularioGeneral" value="<?php echo $formularioGeneral;?>" />
	
	<fieldset>
		<legend>Inspectores</legend>
		<input type="hidden" id="idCoordinador" name="idCoordinador" value="<?php echo $_SESSION['usuario'];?>" />

		<div data-linea="5">	
			<label>Inspector</label>
				<select id="inspector" name="inspector" >
					<option value="" selected="selected">Seleccione un inspector....</option>
					<?php 
						while($fila = pg_fetch_assoc($usuario)){
								echo '<option value="' . $fila['identificador'] . '">' . $fila['apellido'].' ' . $fila['nombre']. '</option>';
							}
					?>
				</select>
		 </div>
		
	<button id="detalle" type="submit" class="guardar" >Agregar funcionario</button>
	</fieldset>
</form>

<fieldset>	
	<legend>Inspectores asignados</legend>
		<table>
			<thead>
				<tr>
					<th></th>
					<th># Solicitud</th>
					<th>Tipo Inspección</th>
					<th colspan="2">Inspectores asignados</th>
				<tr>
			</thead>
			<tbody id="opcion_inspector">
				<?php
					if($operacion[0]!=null){
						for ($i = 0; $i < count ($operacion); $i++) {	
						    $res = $crs->listarInspectoresAsignados($conexion, $operacion[$i], $formularioGeneral, $procesoRevision);
							while($fila = pg_fetch_assoc($res)){
								
								echo "<tr id='r_".$fila['identificador_inspector'].$fila['id_solicitud']."'>
										<td> 
											<form id='f_".$fila['identificador_inspector'].$fila['id_solicitud']."' data-rutaAplicacion='revisionFormularios' data-opcion='quitarInspector'>
												<button type='submit' class='menos'>Quitar</button>
												<input name='idSolicitud' value='".$fila['id_solicitud'] ."' type='hidden'>
												<input name='idInspector' value='".$fila['identificador_inspector'] ."' type='hidden'>
												<input name='formularioGeneral' value='".$formularioGeneral ."' type='hidden'>
                                                <input name='tipoSolicitud' value='".$tipoSolicitud ."' type='hidden'>
												<input name='procesoRevision' value='".$procesoRevision ."' type='hidden'>
											</form>
										</td>
										<td>".$fila['id_solicitud']."</td>
										<td>".$procesoRevision."</td>
										<td>".$fila['apellido'].", ".$fila['nombre']."</td>
									</tr>";
							}
						} 
					}
				?>
			</tbody>
			
		</table>
	</fieldset>
	
	 	
	
	
</body>

<script type="text/javascript">
	var array_operacion= <?php echo json_encode($operacion); ?>;
	var array_inspector= <?php echo json_encode($inspector); ?>;
	var tipoSolicitud= <?php echo json_encode($tipoSolicitud); ?>;
	var procesoRevision= <?php echo json_encode($procesoRevision); ?>;
	var formularioGeneral= <?php echo json_encode($formularioGeneral); ?>;

	$(document).ready(function(){
		distribuirLineas();
		if(array_operacion == ''){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione una o varias operaciones y a continuación presione el botón asignar inspector.</div>');
		}
		construirValidador();
	});
	
	$("#asignarInspector").submit(function(event){
		event.preventDefault();
		chequearCamposInspector(this);
	});

	$("#opcion_inspector").on("submit","form",function(event){
		event.preventDefault();
		ejecutarJson($(this));
		if($("#estado").html()=='El inspector ha sido eliminado satisfactoriamente.' || $("#estado").html()=='Debe asignar la solicitud a un nuevo inspector.'){
			//alert('hola');
			var texto=$(this).attr('id').substring(2);
			texto=texto.replace(/ /g,'');
			texto="#r_"+texto;
			$("#opcion_inspector tr").eq($(texto).index()).remove();
		}	
	});


	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCamposInspector(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#inspector").val()) || !esCampoValido("#inspector")){
			error = true;
			$("#inspector").addClass("alertaCombo");
		}

		if (error == true){
			$("#estado").html("Por favor ingrese la información solicitada.").addClass('alerta');
		}else{
			$("#estado").html("").removeClass('alerta');
			
			for(var i=0;i<array_operacion.length;i++){
				if ($("#opcion_inspector #r_"+$("#inspector").val()+array_operacion[i]).length!=0 || $("table > #opcion_inspector >tr").length >0){
			       	error=true;
					break;
		   		}
			}

			if (!error){
				for(var i=0;i<array_operacion.length;i++){
					if ($("#opcion_inspector #r_"+$("#inspector").val()+array_operacion[i]).length==0){
				       	$("#opcion_inspector").append("<tr id='r_"+$("#inspector").val()+array_operacion[i]+"'><td><form id='f_"+$("#inspector").val()+array_operacion[i]+"' data-rutaAplicacion='revisionFormularios' data-opcion='quitarInspector'><button type='submit' class='menos'>Quitar</button><input name='idSolicitud' value='"+array_operacion[i]+"' type='hidden'><input name='idInspector' value='"+$("#inspector").val()+"' type='hidden'><input name='tipoSolicitud' value='"+tipoSolicitud+"' type='hidden'><input name='formularioGeneral' value='"+formularioGeneral +"' type='hidden'><input name='procesoRevision' value='"+procesoRevision+"' type='hidden'></form></td><td>"+array_operacion[i]+"</td><td>"+procesoRevision+"</td><td>"+$("#inspector  option:selected").text()+"</td></tr>");
			   		}
				}
				ejecutarJson(form);
			}else{
				$("#estado").html("Solo se permite asignar un inspector.").addClass('alerta');
			}
		}
	}
</script>

</html>