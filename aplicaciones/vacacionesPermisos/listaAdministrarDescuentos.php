<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorVacaciones.php';


$conexion = new Conexion();	
$cv = new ControladorVacaciones();

$contador = 0;

$res = $cv->buscarExcelDescuentos($conexion, $_POST['mesH'], $_POST['añoH']);
	
while($fila = pg_fetch_assoc($res)){

$contenido ='<article
	    		id="'.$fila['id_excel_descuento'].'"
				class="item"
				data-rutaAplicacion="vacacionesPermisos"
				data-opcion="listaAdministrarFuncionarios" 
	    		ondragstart="drag(event)"
				draggable="true"
				data-destino="aprobado">
				<span class="ordinal">'.++$contador.'</span>
			
				<span>'.$fila['mes_descuento'].'</span>
				<aside><strong>Año: </strong>'	.$fila['anio_descuento'].'</aside>
			</article>';


?>
	<script type="text/javascript">
					var contenido = <?php echo json_encode($contenido);?>;
					
					$("#aprobado div.elementos").append(contenido);
	</script>
	<?php	
		}				
	?>

<header>
	<h1>Descuento Funcionarios</h1>
	<nav>
		<?php			
			
		    $ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);			
			while($fila = pg_fetch_assoc($res)){				
				echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
					  >'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';							
			}
		?>
	</nav>
	</header>
	<header>
	<nav>
	
	<form id="nuevoFiltroDescuentos" data-rutaAplicacion="vacacionesPermisos" data-opcion="listaAdministrarDescuentos" data-destino="areaTrabajo #listadoItems">
		<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
		
		<table class="filtro" style='width: 325px;'>
			<tbody>
				<tr>
					<th colspan="4">Consultar Descuentos:</th>
				</tr>								
				<tr>
					<td>* Mes:</td>
					<td><select style='width:100%' name="mesH" id="mesH" >
					<option value="" >Mes...</option>
					<?php
						$meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio',
							'Agosto','Septiembre','Octubre','Noviembre','Diciembre');										
						for ($i=0; $i<sizeof($meses); $i++){
							echo '<option value="'.$meses[$i].'">'. $meses[$i] . '</option>';
						}		   					
					?>
				</select></td>
					
				</tr>
				<tr>
					<td >* Año:</td>
					<td ><select style='width:100%' name="anoH" id="anoH" >
					<option value="" >Año...</option>
					<?php
					for($i=2016;$i<=2050;$i++){
					   	echo '<option  value="' . $i . '">'.$i. '</option>';
					}		    
					?>
				</select></td>		
				</tr>				
				<tr>
					<td colspan="4" style='text-align:center'><button>Consultar</button></td>	
				</tr>
				<tr>
					<td colspan="4" style='text-align:center' id="mensajeError">
				</tr>
			</tbody>
		</table>
	</form>
	</nav>	
	
</header>
<div id="aprobado">	
		<div class="elementos"></div>
	</div>

<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("lista");
		$("#listadoItems").addClass("comunes");
		
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');										
			
	});

	$("#fechaInicio").datepicker({
	      changeMonth: true,
	      changeYear: true
	});
  
	$("#fechaFin").datepicker({
	      changeMonth: true,
	      changeYear: true
	});
	
	$("#nuevoFiltroDescuentos").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if($("#anoH").val()=="" && $("#mesH").val()=="" ){	
			error = true;	
			$("#mensajeError").html("Por favor ingrese al menos un campo que contiene (*) para realizar la consulta").addClass('alerta');		
		}

		if(!error){ 
			$("#mensajeError").html('');   
			abrir($(this),event,false);
		}	
	});
</script>

