<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacion.php';
require_once '../../clases/ControladorAplicaciones.php';

$conexion = new Conexion();	
$va = new ControladorVacunacion();

$identificadorUsuario=$_SESSION['usuario'];
$filaTipoUsuario=pg_fetch_assoc($va->obtenerTipoUsuario($conexion, $identificadorUsuario));

$banderaSolicitante = false;
$identificadorSolicitante = "";
$banderaValidarAcciones = false;

switch ($filaTipoUsuario['codificacion_perfil']){
    
    case 'PFL_USUAR_INT':
        
        $qResultadoUsuarioTecnico = $va->verificarTecnicoAgrocalidad($conexion, $identificadorUsuario);
        
        if(pg_num_rows($qResultadoUsuarioTecnico) > 0){
            //echo "<br/> Es técnico con ficha  -> vacuna libre<br/>";
        }else{
            //echo "<br/> Es técnico sin ficha-> bloqueo vacunacion<br/>";
            //$banderaValidarAcciones = true;
        }
        
        break;
        
    case 'PFL_USUAR_EXT':
        
        $qOperacionesEmpresaUsuario = $va->obtenerOperacionesEmpresaUsuario($conexion, $identificadorUsuario, "('digitadorVacunacion')", "('OPT', 'OPI')");
        $operacionesEmpresaUsuario = pg_fetch_assoc($qOperacionesEmpresaUsuario);
        
        $codigoTipoOperacion = $operacionesEmpresaUsuario['codigo_tipo_operacion'];
        $identificadorEmpresa = $operacionesEmpresaUsuario['identificador_empresa'];
        
        if(stristr($codigoTipoOperacion, 'OPT') == true){
            //echo "<br/> Es empleado traspatio-> vacuna libre<br/>";
        }else if(stristr($codigoTipoOperacion, 'OPI') == true){
            //echo "<br/> Es empleado industrial-> vacuna de la empresa<br/>";
            $banderaSolicitante = true;
            $identificadorSolicitante = $identificadorEmpresa;
        }else{
            //echo "<br/> No es empleado-> se bloquea la vacunacion<br/>";
            $banderaValidarAcciones = true;
        }
        
        break;
        
}

$contador = 0;
$itemsFiltrados[] = array();
$res = $va->listaAnularVacunacion($conexion, $_POST['identificadorSolicitanteH'],$_POST['nombreSitioH'], $_POST['identificadorDigitadorH'], $_POST['numeroCertificadoH'],$_POST['fechaInicio'],$_POST['fechaFin'], $_SESSION['usuario']);
	
while($fila = pg_fetch_assoc($res)){
	$itemsFiltrados[] = array('<tr
								id="'.$fila['id_vacunacion'].'"
								class="item"
								data-rutaAplicacion="vacunacion"
								data-opcion="abrirAnularVacunacion"
								ondragstart="drag(event)"
								draggable="true"
								data-destino="detalleItem">
								<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
								<td>'.$fila['numero_certificado'].'</td>
								<td>'.$fila['identificador'].' - '.$fila['nombre_operador'].'</td>
								<td>'.$fila['nombre_sitio'].'</td>
								<td>'.$fila['provincia'].'</td>
								<td>'.$fila['estado'].'</td>
							</tr>');
}

?>
<header>
	<h1>Anular Certificado Digital</h1>
	<nav>
		<?php			
			
		    $ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);			
			
			while($fila = pg_fetch_assoc($res)){		
			    
			    $validacion = 1;
			    
			    if($banderaValidarAcciones){
			        $validacion = ($fila['estilo']!= "_nuevo" && $fila['estilo']!= "_eliminar");
			    }
			    
			    if($validacion){	
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
	<nav>
	<form id="nuevoFiltroVacunacion" data-rutaAplicacion="vacunacion" data-opcion="listaAnularVacunacion" data-destino="areaTrabajo #listadoItems">
		<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
		
		<table class="filtro" style='width: 100%;'>
			<tbody>
				<tr>
					<th colspan="4">Consultar Vacunación:</th>
				</tr>
				<tr>	
					<td align="left">* Identificación Operador:</td>
					<td><input id="identificadorSolicitanteH" name="identificadorSolicitanteH" type="text" style='width: 99%;' maxlength="13" <?php if ($banderaSolicitante){ ?> value="<?php echo $identificadorSolicitante; ?>" readonly="readonly" <?php }?> /></td>
					<td align="left">* Nombre Sitio:</td>
					<td><input id="nombreSitioH" name="nombreSitioH" type="text"  style='width: 99%;' maxlength="200"/></td>		
				</tr>
				<tr>
					<td align="left">* Identificación Digitador:</td>
					<td><input id="identificadorDigitadorH" name="identificadorDigitadorH" type="text" style='width: 99%;' maxlength="13" /></td>	
					<td align="left">* N° Certificado:</td>
					<td><input id="numeroCertificadoH" name="numeroCertificadoH" type="text" style='width: 99%;' maxlength="20"/></td>
				</tr>		
				<tr>
					<td align="left">Fecha Inicio:</td>
					<td><input type="text" id="fechaInicio" name="fechaInicio" readonly="readonly" style='width: 98%;' /></td>
					<td align="left">Fecha Fin:</td>
					<td><input type="text"  id="fechaFin" name="fechaFin" readonly="readonly" style='width: 98%;' /></td>
				</tr>
				<tr>
					<td colspan="4" style='text-align:center'><button> Consultar Vacunación</button></td>	
				</tr>
				<tr>
					<td colspan="4" style='text-align:center' id="mensajeError"></td>
				</tr>
			</tbody>
		</table>
	</form>
	</nav>
</header>
 <div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>N° Certificado</th>
			<th>Operador</th>
			<th>Sitio</th>				
			<th>Provincia</th>
			<th>Estado</th>							
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<script>	

	banderaValidarAcciones = <?php echo json_encode($banderaValidarAcciones);?>

	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
					
	});

	$("#fechaInicio").datepicker({
	      changeMonth: true,
	      changeYear: true
	});
  
	$("#fechaFin").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      maxDate:"0"
	});
	
	$("#_eliminar").click(function(event){
		if($("#cantidadItemsSeleccionados").text()>1){
				alert('Por favor seleccione un registro de vacunacion a la vez');
				return false;
			}
	});
	
	$("#nuevoFiltroVacunacion").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if ($("#nombreSitioH").val().length < 3 && $("#identificadorSolicitanteH").val()=="" && $("#numeroCertificadoH").val()=="" && $("#identificadorDigitadorH").val()==""  ) {
			error = true;
	    	$("#mensajeError").html("Ingrese al menos 3 letras para buscar las coincidencias.").addClass('alerta');
	    }
	    
		if($("#identificadorSolicitanteH").val()==""  && $("#nombreSitioH").val()==""  && $("#identificadorDigitadorH").val()=="" && $("#numeroCertificadoH").val()==""  ){	
			 error = true;	
			 $("#mensajeError").html("Por favor ingrese al menos un campo que contiene (*) para realizar la consulta").addClass('alerta');	
		}
		
		if(!error){
			abrir($(this),event,false);
		}	
	});
</script>