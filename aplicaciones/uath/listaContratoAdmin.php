<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorEmpleados.php';
require_once '../../clases/ControladorAreas.php';

$identificadorTH=$_SESSION['usuario'];
//$identificadorTH='0922357991';

if($identificadorTH==''){
	$usuario=0;
}else{
	$usuario=1;
}


$conexion = new Conexion();
$car = new ControladorAreas();
	
$identificador='0';
$nombres='';
$apellido='';


if ($_POST['nombres']!=''){
	$nombres=$_POST['nombres'];
	$identificador='';
} 
	
if($_POST['apellido']!='' ){
	$apellido=$_POST['apellido'];
	$identificador='';
}

if($_POST['identificador']!=''){
	$_SESSION['usuario_seleccionado']=$_POST['identificador'];
	$identificador=$_POST['identificador'];	
}else{
	//$identificador=$_SESSION['usuario_seleccionado'];
	unset($_SESSION['usuario_seleccionado']);
}
	?>
	
<header>
		
			<h1>Contratos</h1>
			<nav>
				<form id="filtrar" data-rutaAplicacion="uath" data-opcion="listaContratoAdmin" data-destino="areaTrabajo #listadoItems" >
				<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
				
				<?php if($identificadorTH != ''){ 
				
					echo '
					<table class="filtro" style="width: 400px;" >
					<tbody>
					<tr>
					<th colspan="3">Buscar Funcionario:</th>
					</tr>
					<tr>
					<td>Número de Cédula:</td>
					<td> <input id="identificador" type="text" name="identificador" maxlength="10" value="'.  $_POST['identificador'] .'">	</td>
					
					</tr>
						
					<tr>
					<td>Apellido:</td>
					<td> <input id="apellido" type="text" name="apellido" maxlength="128" value="'. $_POST['apellido'] .'">	</td>
					
					</tr>
						
					<tr>
					<td>Nombre:</td>
					<td> <input id="nombres" type="text" name="nombres" maxlength="128" value="'. $_POST['nombres'] .'">	</td>
					
					</tr>
						
						
						
						
					<tr>
					<td id="mensajeError"></td>
					<td colspan="5"> <button id="buscar">Buscar</button>	</td>
					</tr>
					</tbody>
					</table> ';
				 }?>
				</form>
			</nav>
			</header>
			<header>
			<nav>
				<?php 
					
						$conexion = new Conexion();
						$ca = new ControladorAplicaciones();
						$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $identificadorTH);
					
						if($identificadorTH != ''){ 
							while($fila = pg_fetch_assoc($res))
							{
								echo '<a href="#"
										id="' . $fila['estilo'] . '"
										data-destino="detalleItem"
										data-opcion="' . $fila['pagina'] . '"
										data-rutaAplicacion="' . $fila['ruta'] . '"
										>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
								
							}
						}
					
				?>
				</nav>
			</header>
			
			<div id="estadoSesion"></div>
			
		<table id="listaEmpleadoContrato">
			<thead>
				<tr>
					<th>#</th>
					<th>Funcionario/Tipo Contrato</th>
					<th>Presupuesto</th>
					<th>Fechas</th>
					<th>Estado</th>
		
				</tr>
			</thead>
		<?php
			 
			$cd = new ControladorCatastro();
			$ce = new ControladorEmpleados();
				
			if($identificador!='0'){
									
				$tipoUsuario = pg_fetch_result($cd->filtroObtenerDatosFuncionario($conexion, $identificador), 0, 'tipo_empleado');

				if($tipoUsuario == 'Interno'){
					
					$qContrato = $cd->obtenerContratosXUsuario($conexion, $identificador, $nombres, $apellido);

					$contador = 0;
					if(pg_num_rows($qContrato) == 0){
						echo 'El usuario no tiene registrado contratos en Agrocalidad.';
					}else{
						$areaUsuarioTH = pg_fetch_assoc($car->areaUsuario($conexion, $identificadorTH));
						$areaUsuarioContrato = pg_fetch_assoc($car->areaUsuario($conexion, $identificador));
					
						///////////////////////////////////
					
					
						if($areaUsuarioTH['clasificacion']=='Planta Central'){
							while($contrato = pg_fetch_assoc($qContrato)){
					
								echo '<tr 	id="'.$contrato['id_datos_contrato'].'"
																class="item"
																data-rutaAplicacion="uath"
																data-opcion="modificarContrato"
																ondragstart="drag(event)"
																draggable="true"
																data-destino="detalleItem"
																>
															<td>'.++$contador.'</td>
															<td style="white-space:nowrap;">'.$contrato['apellido'].' '.$contrato['nombre'].'<br/><b>'.$contrato['tipo_contrato'].'</b></td>
															<td>'.$contrato['presupuesto'].'</td>
															<td> Desde: '.$contrato['fecha_inicio'].'<br/> Hasta: '. $contrato['fecha_fin'].'</td>
															<td>'.($contrato['estado']==1 ? '<span class= "exito"> Vigente </span>' : ($contrato['estado']==2?'<span class= "advertencia"> Caducado </span>': ($contrato['estado']==3?'<span class= "alerta"> Finalizado </span>': '<span class= "advertencia"> Inactivo </span>'))).'</td>
					
														</tr>';
							}
					
						}else if($areaUsuarioTH['clasificacion']=='Unidad'){
								
							$zonaTH = pg_fetch_assoc($car->buscarArea($conexion, $areaUsuarioTH['id_area_padre']));
							$listaZonas = $car->buscarOficinaTecnicaXArea($conexion, $zonaTH['zona_area']);
								
							while($area = pg_fetch_assoc($listaZonas)){
								$zonasFuncionarios[] = $area['id_area'];
					
								$listaAreas = $car->buscarAreasYSubprocesos($conexion, $area['id_area']);
					
								while($areaGestiones = pg_fetch_assoc($listaAreas)){
									$zonasFuncionarios[] = $areaGestiones['id_area'];
								}
							}
								
							if (in_array($areaUsuarioContrato['id_area'], $zonasFuncionarios)) {
								while($contrato = pg_fetch_assoc($qContrato)){
										
									echo '<tr 	id="'.$contrato['id_datos_contrato'].'"
																	class="item"
																	data-rutaAplicacion="uath"
																	data-opcion="modificarContrato"
																	ondragstart="drag(event)"
																	draggable="true"
																	data-destino="detalleItem"
																	>
																<td>'.++$contador.'</td>
																<td style="white-space:nowrap;">'.$contrato['apellido'].' '.$contrato['nombre'].'<br/><b>'.$contrato['tipo_contrato'].'</b></td>
																<td>'.$contrato['presupuesto'].'</td>
																<td> Desde: '.$contrato['fecha_inicio'].'<br/> Hasta: '. $contrato['fecha_fin'].'</td>
																<td>'.($contrato['estado']==1 ? '<span class= "exito"> Vigente </span>' : ($contrato['estado']==2?'<span class= "advertencia"> Caducado </span>': ($contrato['estado']==3?'<span class= "alerta"> Finalizado </span>': '<span class= "advertencia"> Inactivo </span>'))).'</td>
											
															</tr>';
								}
							}
						}
					}

				}else{
					
					$qContrato = $cd->obtenerContratosXUsuarioExterno($conexion, $identificador, $nombres, $apellido);
					
					while($contrato = pg_fetch_assoc($qContrato)){
							
						echo '<tr 	id="'.$contrato['id_datos_contrato'].'"
																class="item"
																data-rutaAplicacion="uath"
																data-opcion="modificarContrato"
																ondragstart="drag(event)"
																draggable="true"
																data-destino="detalleItem"
																>
															<td>'.++$contador.'</td>
															<td style="white-space:nowrap;">'.$contrato['apellido'].' '.$contrato['nombre'].'<br/><b>'.$contrato['tipo_contrato'].'</b></td>
															<td>'.$contrato['presupuesto'].'</td>
															<td> Desde: '.$contrato['fecha_inicio'].'<br/> Hasta: '. $contrato['fecha_fin'].'</td>
															<td>'.($contrato['estado']==1 ? '<span class= "exito"> Vigente </span>' : ($contrato['estado']==2?'<span class= "advertencia"> Caducado </span>': ($contrato['estado']==3?'<span class= "alerta"> Finalizado </span>': '<span class= "advertencia"> Inactivo </span>'))).'</td>
			
														</tr>';
					}
					

				}
			}	
				
		?>
		</table>

<script>
var usuario = <?php echo json_encode($usuario); ?>;
			
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$('#identificador').ForceNumericOnly();
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un contrato para revisarlo.</div>');

		if(usuario == '0'){
			$("#estadoSesion").html("Su sesión ha expirado, por favor ingrese nuevamente al Sistema GUIA.").addClass("alerta");
		}
	});


		
	
$("#filtrar").submit(function(event){
		event.preventDefault();
		
		if($('#identificador').val().length<10 && $('#nombres').val().length==0 && $('#apellido').val().length==0)
		{		
			$('#mensajeError').html('<span class="alerta">La cédula ingresada no es válida!');
			
		}

		else if($('#identificador').val().length==10 || $('#nombres').val().length!=0 || $('#apellido').val().length!=0)
		{		
			abrir($('#filtrar'),event, false);

		}
		
	});
	
	

	
	
	
	</script>
