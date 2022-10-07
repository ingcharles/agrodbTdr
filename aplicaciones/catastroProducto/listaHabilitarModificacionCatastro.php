<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatastroProducto.php';

$conexion = new Conexion();
$conexion->verificarSesion();
$cp = new ControladorCatastroProducto();

?>
<header>
	<h1>Habilitar modificación catastro</h1>

	<nav>
        <?php
        
        $ca = new ControladorAplicaciones();
        $res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
        
        while ($fila = pg_fetch_assoc($res)) {
        
            echo '<a href="#"
        						id="' . $fila['estilo'] . '"
        						data-destino="detalleItem"
        						data-opcion="' . $fila['pagina'] . '"
        						data-rutaAplicacion="' . $fila['ruta'] . '"
        					  >' . (($fila['estilo'] == '_seleccionar') ? '<div id="cantidadItemsSeleccionados">0</div>' : '') . $fila['descripcion'] . '</a>';
        }
        
        $contador = 0;
        $itemsFiltrados[] = array();
        $res = $cp->buscarOperadorModificacionIdentificador($conexion, $_POST['identificadorSolicitanteH']);
        
        while ($fila = pg_fetch_assoc($res)) {
        
            $itemsFiltrados[] = array(
                '<tr
                    id="' . $fila['id_modificacion_identificador'] . '"
                    class="item"
                    data-rutaAplicacion="catastroProducto"
                    data-opcion="abrirHabilitarModificacionCatastro"
                    ondragstart="drag(event)"
                    draggable="true"
                    data-destino="detalleItem">
                    <td style="white-space:nowrap;"><b>' . ++$contador . '</b></td>
                    <td>' . $fila['identificador_operador'] . '</td>
                    <td>' . $fila['nombre_operador'] . '</td>
                    <td>' . $fila['habilitar_modificacion_identificador'] . '</td>
                 </tr>'
            );
        }
        
        ?>
	</nav>

	<nav>
		<form id="filtroModificacionCatastro" data-rutaAplicacion="catastroProducto" data-opcion="listaHabilitarModificacionCatastro" data-destino="areaTrabajo #listadoItems">
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			<table class="filtro">
				<tbody>
					<tr>
						<th colspan="2">Consultar habilitar modificación catastro:</th>
					</tr>
					<tr>
						<td align="left">Identificador Operador:</td>
						<td><input id="identificadorSolicitanteH" name="identificadorSolicitanteH" type="text" maxlength="13" /></td>						
					</tr>
					<tr>
						<td colspan="2" style='text-align: center'><button>Consultar</button></td>
					</tr>
					<tr>
						<td colspan="2" style='text-align: center' id="mensajeError">					
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
			<th>Identificador operador</th>
			<th>Nombre operador</th>
			<th>Modificar catastro</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<script>

	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');
	});

	$("#filtroModificacionCatastro").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#identificadorSolicitanteH").val() == ""){	
			 error = true;		
		 	$("#mensajeError").html("Por favor ingrese un identificador.").addClass('alerta');
		}

		if(!error){ 
			$("#mensajeError").html('');   
			abrir($(this),event,false);
		}	
	});

</script>