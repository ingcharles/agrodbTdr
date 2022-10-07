<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatastroProducto.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();	
//$_SESSION['LAST_ACTIVITY']=time();
$conexion->verificarSesion();
$cc = new ControladorCatalogos();
$cp = new ControladorCatastroProducto();

$identificadorUsuario = $_SESSION['usuario'];
$filaTipoUsuario = pg_fetch_assoc($cp->obtenerTipoUsuario($conexion, $identificadorUsuario));

$banderaSolicitante = false;
$identificadorSolicitante = "";
$banderaValidarAcciones = false;

switch ($filaTipoUsuario['codificacion_perfil']){
    
    case 'PFL_USUAR_INT':
        
        $qResultadoUsuarioTecnico = $cp->verificarTecnicoAgrocalidad($conexion, $identificadorUsuario);
        
        if(pg_num_rows($qResultadoUsuarioTecnico) > 0){
            //echo "<br/> Es técnico con ficha -> catastro libre<br/>";
        }else{
            //echo "<br/> Es técnico sin ficha-> bloqueo catastro<br/>";
            //$banderaValidarAcciones = true;
        }
        
    break;
        
    case 'PFL_USUAR_EXT':
        
        $qOperacionesEmpresaUsuario = $cp->obtenerOperacionesEmpresaUsuario($conexion, $identificadorUsuario, "('digitadorVacunacion')", "('OPT')");
        $operacionesEmpresaUsuario = pg_fetch_assoc($qOperacionesEmpresaUsuario);
        
        $codigoTipoOperacion = $operacionesEmpresaUsuario['codigo_tipo_operacion'];
        
        if(stristr($codigoTipoOperacion, 'OPT') == true){
            //echo "<br/> Es empleado traspatio-> catastra libre cualquier categoria<br/>";
        }else{
            
            $qResultadoEmpresaOperador = $cp->consultarEmpresaPorOperacion($conexion, "('OPI', 'FER')" , $identificadorUsuario);
            $resultadoEmpresaOperador = pg_fetch_assoc($qResultadoEmpresaOperador);
            
            $identificadorEmpresa = $resultadoEmpresaOperador['identificador_empresa'];
            
            if(pg_num_rows($qResultadoEmpresaOperador) > 0){
                //echo "<br/> Es empleado -> catastra de la empresa solo lechones y lechonas<br/>";
                $banderaSolicitante = true;
                $identificadorSolicitante = $identificadorEmpresa;
            }else{
                
                $qOperacionesUsuario = $cp->obtenerOperacionesUsuario($conexion, $identificadorUsuario, "( 'OPI', 'PRO', 'COM')");
                
                if(pg_num_rows($qOperacionesUsuario) > 0){
                    //echo "<br/> Es operador-> catastra sus propios cerdos solo lechones y lechonas<br/>";
                    $banderaSolicitante = true;
                    $identificadorSolicitante = $identificadorUsuario;
                }else{
                    //echo "<br/> No es empleado-> se bloquea el catastro<br/>";
                    $banderaValidarAcciones = true;
                }
                
            }
            
        }
        
    break;
        
}

$contador = 0;
$itemsFiltrados[] = array();
$res = $cp->consultaCatastroIndividual($conexion, $_POST['identificadorSolicitanteH'], $_POST['nombreOperadorSolicitante'], $_POST['nombreSitio'], $_POST['provincia'], $_POST['fechaInicio'], $_POST['fechaFin'], $_SESSION['usuario'], $_POST['identificadorProductoUnico'], $_POST['bNumeroLote']);
	
while($fila = pg_fetch_assoc($res)){
	
	if($_POST['identificadorProductoUnico'] == ''){
		$cantidad = $fila['cantidad'];
	}else{
		$qCantidadDetalleCatastro=$cp->cantidadDetalleCatastro($conexion, $fila['id_catastro']);
		$cantidad = pg_fetch_result($qCantidadDetalleCatastro, 0, 'cantidad');
	}
	
	$itemsFiltrados[] = array('<tr
		id="'.$fila['id_catastro'].'"
		class="item"
		data-rutaAplicacion="catastroProducto"
		data-opcion="abrirCatastroIndividual"
		ondragstart="drag(event)"
		draggable="true"
		data-destino="detalleItem">
		<td style="white-space:nowrap;"><b>'.++$contador.'</b></td>
		<td>'.$fila['lugar'].'</td>
		<td>'.$fila['operador'].'</td>
		<td>'.$fila['producto'].'</td>
		<td>'.$cantidad.'</td>
		<td>'.$fila['fecha_registro'].'</td>	
		</tr>');
}

?>

<header>
	<h1>Administrar catastro</h1>
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
	
	<form id="nuevoFiltroCatastro" data-rutaAplicacion="catastroProducto" data-opcion="listaCatastroIndividual" data-destino="areaTrabajo #listadoItems">
		<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
		<input type="hidden" id="identificadorResponsableH" name="identificadorResponsableH" value="<?php echo $_SESSION['usuario']; ?>" />
		
		<table class="filtro" >
			<tbody>
				<tr>
					<th colspan="4">Consultar Catastro:</th>
				</tr>
				<tr>
					<td align="left">* Identificación Operador:</td>
					<td><input id="identificadorSolicitanteH" name="identificadorSolicitanteH" type="text" maxlength="13" <?php if ($banderaSolicitante){ ?> value="<?php echo $identificadorSolicitante; ?>" readonly="readonly" <?php }?> /></td>
					<td align="left">* Nombre Operador:</td>
					<td><input name="nombreOperadorSolicitante" id="nombreOperadorSolicitante"  type="text"  maxlength="200" /></td>		
				</tr>
				
				<tr>
					<td align="left">* Nombre del Sitio:</td>
					<td><input name="nombreSitio" id="nombreSitio" type="text" maxlength="200" /></td>
					<td align="left">Provincia:</td>
					<td>
					<select id="provincia" name="provincia" style="width:99%" >
						<option value="" >Seleccione...</option>
						<?php 
							$qProvincias = $cc->listarLocalizacion($conexion, "PROVINCIAS");
							while($fila = pg_fetch_assoc($qProvincias)){
								echo '<option value="' . $fila['nombre'] . '">' . $fila['nombre'] . '</option>';
							}
						?>		
					</select>
					</td>
				</tr>
				<tr>
					<td align="left">* Identificador producto:</td>
					<td><input name="identificadorProductoUnico" id="identificadorProductoUnico"  type="text"  maxlength="18" style="width: 100%"/></td>		
					<td align="left">N° Lote:</td>
					<td><input name="bNumeroLote" id="bNumeroLote" type="text"  maxlength="18" style="width: 100%"/></td>		
				</tr>
				<tr>
					<td align="left">Fecha Inicio:</td>
					<td><input type="text" name="fechaInicio" id="fechaInicio" /></td>
					<td align="left">Fecha Fin:</td>
					<td><input type="text" name="fechaFin" id="fechaFin" /></td>
				</tr>
				<tr>
					<td colspan="4" style='text-align:center'><button>Consultar Catastro</button></td>	
				</tr>
				<tr>
					<td colspan="4" style='text-align:center' id="mensajeError">
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
			<th>Sitio/Área</th>	
			<th>Operador</th>
			<th>Producto</th>
			<th>Cantidad</th>
			<th title="Fecha de registro">F.Registro</th>
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
	      changeYear: true
	});

	$("#_eliminar").click(function(event){
		$("#mensajeError").html("");
		if($("#cantidadItemsSeleccionados").text()>1){	
			$("#mensajeError").html("Por favor seleccione un registro de catastro a la vez.").addClass('alerta');
				return false;
			}
		if($("#cantidadItemsSeleccionados").text()==0){
			$("#mensajeError").html("Por favor seleccione un registro de catastro a eliminar.").addClass('alerta');
			return false;
		}
	});
	
	$("#nuevoFiltroCatastro").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#identificadorSolicitanteH").val()=="" && $("#nombreSitio").val()==""  && $("#nombreOperadorSolicitante").val().length<3 && $("#identificadorProductoUnico").val()==""){	
			 error = true;		
		 	$("#mensajeError").html("Por favor ingrese al menos 3 letras para buscar las coincidencias.").addClass('alerta');
		}

		if($("#identificadorSolicitanteH").val()=="" && $("#nombreOperadorSolicitante").val()=="" && $("#nombreSitio").val().length<3 && $("#identificadorProductoUnico").val()==""){	
			 error = true;		
		 	$("#mensajeError").html("Por favor ingrese al menos 3 letras para buscar las coincidencias.").addClass('alerta');
		}
		
		if($("#identificadorSolicitanteH").val()=="" && $("#nombreOperadorSolicitante").val()=="" && $("#nombreSitio").val()==""  && $("#identificadorProductoUnico").val()=="" ){	
			error = true;	
			$("#mensajeError").html("Por favor ingrese al menos un campo que contiene (*) para realizar la consulta").addClass('alerta');		
		}

		if(!error){ 
			$("#mensajeError").html('');   
			abrir($(this),event,false);
		}	
	});

	if(banderaValidarAcciones){
		$("input").attr("disabled", true);
		$("button").attr("disabled", true);
		$("select").attr("disabled", true);		
	}
	
</script>
