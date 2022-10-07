<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionProductos.php';
require_once '../../clases/ControladorAplicaciones.php';
$conexion = new Conexion();
$cmp = new ControladorMovilizacionProductos();
$identificadorUsuario=$_SESSION['usuario'];
$filaTipoUsuario=pg_fetch_assoc($cmp->obtenerTipoUsuario($conexion, $identificadorUsuario));

$banderaSolicitante = false;
$identificadorSolicitante = "";
$banderaValidarAcciones = false;

switch ($filaTipoUsuario['codificacion_perfil']){
    
    
    case 'PFL_USUAR_EXT':
        
        $qOperacionesEmpresaUsuario = $cmp->obtenerOperacionesEmpresaUsuario($conexion, $identificadorUsuario, "('digitadorMovilizacion')", "('PROSA', 'COMSA', 'OPISA', 'FERSA')");
                
        if(pg_num_rows($qOperacionesEmpresaUsuario) > 0){
            
            $operacionesEmpresaUsuario = pg_fetch_assoc($qOperacionesEmpresaUsuario);
            $identificadorEmpresa = $operacionesEmpresaUsuario['identificador_empresa'];
            
            //echo "<br/> Es empleado de movilizacion-> moviliza de la empresa<br/>";
            $banderaSolicitante = true;
            $identificadorSolicitante = $identificadorEmpresa;      
            
        }else{
            
            $qOperacionesUsuario = $cmp->obtenerOperacionesUsuario($conexion, $identificadorUsuario, "('PRO', 'COM', 'OPI', 'FER')");
            
            if(pg_num_rows($qOperacionesUsuario) > 0){                
                //echo "<br/> Es operador-> moviliza sus propios cerdos<br/>";
                $banderaSolicitante = true;
                $identificadorSolicitante = $identificadorUsuario;               
            }else{                
                //echo "<br/> No es empleado ni tiene operacion-> se bloquea la movilizacion<br/>";
                $banderaValidarAcciones = true;                
            }
        
        } 
        
    break;
    
}

$contador = 0;
$itemsFiltrados[] = array();
$res = $cmp->listaMovilizacionProducto($conexion, $_POST['identificadorOperadorH'],$_POST['nombreOperadorH'], $_POST['nombreSitioH'], $_POST['numeroCertificadoH'],$_POST['fechaInicio'],$_POST['fechaFin'], $_SESSION['usuario'],$_POST['identificadorProductoUnico']);
	
while($fila = pg_fetch_assoc($res)){

	$itemsFiltrados[] = array('<tr
								id="'.$fila['id_movilizacion'].'"
								class="item"
								data-rutaAplicacion="movilizacionProducto"
								data-opcion="abrirMovilizacionProducto"
								ondragstart="drag(event)"
								draggable="true"
								data-destino="detalleItem">
								<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
								<td>'.$fila['numero_certificado'].'</td>
								<td>'.$fila['sitio_origen'].'</td>
								<td>'.$fila['sitio_destino'].'</td>
								<td>'.$fila['estado'].'</td>
							</tr>');
}

?>
<header>
<h1>Solicitud de Certificación Sanitaria de Movilización</h1> 
	<nav>
		<?php			
		    $ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);			
			
			while($fila = pg_fetch_assoc($res)){	
			    
			    $validacion = 1;
			    
			    if($banderaValidarAcciones){				        
                    $validacion = ($fila['estilo']!= "_nuevo");			        
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
</header>
	<header>
		<nav>
			<form id="filtrarMovilizacionProducto" data-rutaAplicacion="movilizacionProducto" data-opcion="listaMovilizacionProducto" data-destino="areaTrabajo #listadoItems" >
				<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
				<input type="hidden" id="identificadorResponsableH" name="identificadorResponsableH" value="<?php echo $_SESSION['usuario']; ?>" />

				<table class="filtro" style='width: 100%;' >
					<tbody>
					<tr>
						<th colspan="4">Consultar Certificado de Movilización</th>					
					</tr>
					<tr>
						<td align="left">* Identificación Operador:</td>
						<td><input id="identificadorOperadorH" type="text" name="identificadorOperadorH" maxlength="13" <?php if ($banderaSolicitante){ ?> value="<?php echo $identificadorSolicitante; ?>" readonly="readonly" <?php }?> /></td>
						<td align="left">* Nombre Operador:</td>
						<td><input id="nombreOperadorH" type="text" name="nombreOperadorH"></td>
					</tr>
					<tr>
						<td align="left">* Nombre Sitio:</td>
						<td><input id="nombreSitioH" type="text" name="nombreSitioH"></td>
						<td align="left">* N° Certificado:</td>
						<td colspan="3" ><input id="numeroCertificadoH" type="text" name="numeroCertificadoH"></td>
					</tr>
					<tr>
						<td align="left">* Identificador producto:</td>
						<td colspan="3"><input name="identificadorProductoUnico" id="identificadorProductoUnico"  type="text"  maxlength="18" style="width: 100%"/></td>		
					</tr>
					<tr>
						<td align="left">Fecha Inicio:</td>
						<td><input id="fechaInicio" type="text" name="fechaInicio"></td>
						<td align="left">Fecha Fin:</td>
						<td><input id="fechaFin" type="text" name="fechaFin"></td>					
					</tr>		
					<tr>
					<td colspan="4" style='text-align:center'><button>Consultar</button></td>	
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
				<th>Sitio Origen</th>			
				<th>Sitio Destino</th>		
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
		//$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
		
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);	
	});

	$("#ventanaAplicacion").on("click", "#opcionesAplicacion a", function(e) {
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');								
	});
	
	$("#fechaInicio").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      maxDate:"0"
	});
  
	$("#fechaFin").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      maxDate:"0"
	});

	$("#filtrarMovilizacionProducto").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if ($("#nombreOperadorH").val().length < 3 && $("#identificadorOperadorH").val()=="" && $("#numeroCertificadoH").val()=="" && $("#nombreSitioH").val()=="" && $("#identificadorProductoUnico").val()=="" ) {
			error = true;
	    	$("#mensajeError").html("Por favor ingrese al menos 3 letras para buscar las coincidencias.").addClass('alerta');
	    }
	    
		if($("#identificadorOperadorH").val()==""  && $("#nombreOperadorH").val()==""  && $("#nombreSitioH").val()==0 && $("#numeroCertificadoH").val()=="" && $("#identificadorProductoUnico").val()=="" ){	
			 error = true;	
				$("#mensajeError").html("Por favor ingrese al menos un campo que contiene (*) para realizar la consulta").addClass('alerta');
		}
		
		if(!error){
			abrir($(this),event,false);
		}	
	});

	if(banderaValidarAcciones){
		$("input").attr("disabled", true);
		$("button").attr("disabled", true);
		$("select").attr("disabled", true);		
	}
			
</script>