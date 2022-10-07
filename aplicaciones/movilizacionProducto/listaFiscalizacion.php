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
    
    case 'PFL_USUAR_INT':
	case 'PFL_USUAR_CIV_PR':						   
        
        $qResultadoUsuarioTecnico = $cmp->verificarTecnicoAgrocalidad($conexion, $identificadorUsuario);
        
        if(pg_num_rows($qResultadoUsuarioTecnico) > 0){
            //echo "<br/> Es técnico con ficha  -> moviliza libre<br/>";
        }else{
            //echo "<br/> Es técnico sin ficha-> bloqueo movilizacion<br/>";
            //$banderaValidarAcciones = true;
        }
        
    break;
        
    case 'PFL_USUAR_EXT':
        
        $qOperacionesEmpresaUsuario = $cmp->obtenerOperacionesEmpresaUsuario($conexion, $identificadorUsuario, "('digitadorFaenador')", "('FAEAI')"/*"('PRO', 'COM', 'OPI', 'FER')"*/);
        
        if(pg_num_rows($qOperacionesEmpresaUsuario) > 0){
            
            $operacionesEmpresaUsuario = pg_fetch_assoc($qOperacionesEmpresaUsuario);
            $identificadorEmpresa = $operacionesEmpresaUsuario['identificador_empresa'];
            
            //echo "<br/> Es empleado de movilizacion-> moviliza de la empresa<br/>";
            $banderaSolicitante = true;
            $identificadorSolicitante = $identificadorEmpresa;
            
        }else{
           
            //TODO: Verificar quienes fiscalizan 
            $qOperacionesUsuario = $cmp->obtenerOperacionesUsuarioFiscalizacion($conexion, $identificadorUsuario, "('FAEAI')" /*"('SAPRO', 'SACOM', 'SAOPI', 'SAFER', 'AIFAE')"*/);
            
            if(pg_num_rows($qOperacionesUsuario) > 0){
                //echo "<br/> Es operador-> moviliza sus propios cerdos<br/>";
                $banderaSolicitante = true;
                $identificadorSolicitante = $identificadorUsuario;
          
            }else{
            
                $qRol =  $cmp->obtenerOperacionesEmpresaUsuario($conexion, $identificadorUsuario, "('digitadorMovilizacion')", "('FERSA')"/*"('PRO', 'COM', 'OPI', 'FER')"*/);
                if(pg_num_rows($qRol) > 0 ){
                    $banderaSolicitante = true;
                    $identificadorSolicitante = pg_fetch_assoc($qRol)['identificador_empresa'];
                }else{
                    $banderaValidarAcciones = true;
                }
                
              
            }
            
        }
        
    break;
        
}

$contador = 0;
$itemsFiltrados[] = array();
$res = $cmp->listaCertificadosMovilizacionFiscalizacion($conexion, $_POST['identificadorOperadorH'],$_POST['nombreOperadorH'], $_POST['nombreSitioH'], $_POST['numeroCertificadoH'],$_POST['fechaInicio'],$_POST['fechaFin'], $_SESSION['usuario'],$filaTipoUsuario['codificacion_perfil'],$_POST['identificadorProductoUnico'],$_POST['datos']);
while($fila = pg_fetch_assoc($res)){
	
	if($fila['estado_fiscalizacion']=="No fiscalizado")
			$claseColor='claseColor';
		else
			$claseColor='';
	
	$itemsFiltrados[] = array('<tr
								id="'.$fila['id_movilizacion'].'"
								class="item '.$claseColor.'"
								data-rutaAplicacion="movilizacionProducto"
								data-opcion="abrirFiscalizacion"
								ondragstart="drag(event)"
								draggable="true"
								data-destino="detalleItem">
								<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
								<td>'.$fila['numero_certificado'].'</td>
								<td>'.$fila['sitio_origen'].'</td>
								<td>'.$fila['sitio_destino'].'</td>
								<td>'.$fila['estado'].'</td>
								<td>'.$fila['estado_fiscalizacion'].'</td>
							</tr>');
}

?>
<header>
<h1>Fiscalización de Certificado de Movilización</h1> 
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
			<form id="filtrarMovilizacionProducto" data-rutaAplicacion="movilizacionProducto" data-opcion="listaFiscalizacion" data-destino="areaTrabajo #listadoItems" >
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
					<?php if($filaTipoUsuario['codificacion_perfil']=='PFL_USUAR_INT' || $filaTipoUsuario['codificacion_perfil']=='PFL_USUAR_CIV_PR'){ ?>
					<tr>
						<td align="left">Origen:</td>
						<td align="left"><input name="datos" id="origen" type="radio" value="origen" checked="checked"/></td>		
						<td align="left">Destino:</td>
						<td align="left"><input name="datos" id="destino" type="radio" value="destino"/></td>		
					</tr>
					<?php } ?>
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
				<th>Estado Fiscalización</th>						
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

		colors = ['#ef3e56', '#c7c7c7' ];
		var i = 0;
		animate_loop = function() {      
		$('.claseColor').animate({backgroundColor:colors[(i++)%colors.length]
			}, 700, function(){
				animate_loop();
			});
		};
		animate_loop();		
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