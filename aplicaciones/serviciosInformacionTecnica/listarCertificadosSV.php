<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAdministrarCatalogos.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorServiciosInformacionTecnica.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	
	
	$conexion = new Conexion();
	$controladorCatalogos = new ControladorCatalogos();
	$controladorInformacion= new ControladorServiciosInformacionTecnica();
	$usuario=$_SESSION['usuario'];
	
	?>
	
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<?php
    $registros = $controladorInformacion->listarCertificadosXPais($conexion,$_POST['paisFiltro']);
?>

<header>
		<h1>Consulta de Certificados</h1>
		<nav>
		<form id="filtrar" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="listarCertificadosSV" data-destino="areaTrabajo #listadoItems">
		<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
		<table class="filtro" style='width: 400px;'>
			<tr>
				<th colspan="3">Buscar Certificado:</th>
			</tr>
			<tr>
				<td>País: </td>
				<td>
    				<select id="paisFiltro" name="paisFiltro">
        				<option value=>Seleccione....</option>
                    	<?php 
                    	$pais = $controladorInformacion->listarCertificadosXPais($conexion, NULL, 'localizacion');
                    	
                    	while($fila=pg_fetch_assoc($pais)){
                    	   echo '<option value="' . $fila['id_localizacion'] .'">' . $fila['pais'] . '</option>';
                    	}
                    	?>
    				</select>
				</td>
			</tr>
			
			<tr>						
				<td colspan="3"> <button  type="submit" id='buscar'>Buscar</button></td>
			</tr>
			<tr>
				<td colspan="4" style='text-align:center' id="mensajeError"></td>
			</tr>
		</table>
		</form>	
		</nav>
</header>

<header>	
		<nav>

		<?php 
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $usuario);
			
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
	
	<div id="catalogos">
		<h2>Certificados Activos</h2>
		<div class="elementos"></div>
	</div>
	
	<?php  		

	$contador = 0;
	while($fila = pg_fetch_assoc($registros)){	
		
		$contenido = '<article
						id="'.$fila['id_certificado_cabecera'].'"
						class="item"
						data-rutaAplicacion="serviciosInformacionTecnica"
						data-opcion="abrirCertificadoSV"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem"
						style="'.($fila['estado']== '1'?'background-color: #7D97EB;':'background-color: #EA8F7D;').'">
					<span class="ordinal">'.++$contador.'</span>
					<span>'.(strlen($fila['nombre'])>50?(substr($fila['nombre'],0,50).'...'):(strlen($fila['nombre'])>0?$fila['nombre']:'Sin asunto')).'</span>
					<aside><small>'.$fila['pais'].'</small></aside>
				</article>';		
		?>
				<script type="text/javascript">
					var contenido = <?php echo json_encode($contenido);?>;					
					$("#catalogos div.elementos").append(contenido);
				</script>
				<?php					
	}
	?>
	
	
	
</body>
<script>
	$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');
		$("#catalogos div> article").length == 0 ? $("#catalogos").remove():"";		
	});

	$("#_inactivar").click(function(e){		
		if($("#cantidadItemsSeleccionados").text()<1){
			$("#mensajeError").html("Por favor seleccione un registro para inactivar.").addClass('alerta');			
			return false;
		}
	});

	$("#filtrar").submit(function(event){
		event.preventDefault();		
		var error = false;	

		if(!error){	
			abrir($('#filtrar'),event, false);
		}
	});
	
</script>
</html>