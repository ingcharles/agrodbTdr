<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorEmpleados.php';
require_once '../../clases/ControladorAreas.php';

$identificadorTH=$_SESSION['usuario'];

if($identificadorTH==''){
	$usuario=0;
}else{
	$usuario=1;
}

$conexion = new Conexion();

$car = new ControladorAreas();
	
$identificador='';
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

if($_POST['identificador']!='')
{
	$_SESSION['usuario_seleccionado']=$_POST['identificador'];
	$identificador=$_POST['identificador'];
	
}
else 
{
	unset($_SESSION['usuario_seleccionado']);
}
	?>
	
<header>
		
			<h1>Responsables RRHH</h1>
			<nav>
				<form id="filtrar" data-rutaAplicacion="uath" data-opcion="listarResponsablesRRHH" data-destino="areaTrabajo #listadoItems" >
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
			
		<table >
			<thead>
				<tr>
					<th>#</th>
					<th>Funcionario</th>
					<th>Zona</th>
					<th>Estado</th>
		
				</tr>
			</thead>
			<?php 
				$cd = new ControladorCatastro();
				
				$consulta = $cd->obtenerResponsablesRRHH($conexion, $nombre, $apellido, $identificador,'');
				$contador = 0;
				
				while($result = pg_fetch_assoc($consulta)){
					echo '<tr 	id="'.$result['id_encargo_recursos_humanos'].'"
					class="item"
					data-rutaAplicacion="uath"
					data-opcion="modificarResponsableRRHH"
					ondragstart="drag(event)"
					draggable="true"
					data-destino="detalleItem"
					>
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$result['servidor'].'</b></td>
					<td>'.ucfirst($result['zona']).'</td>
					<td>'.ucfirst($result['estado']).'</td>
					</tr>';
				}
					
			?>
		</table>

<script>
var usuario = <?php echo json_encode($usuario); ?>;
			
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$('#identificador').ForceNumericOnly();
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui para modificarlo.</div>');

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
