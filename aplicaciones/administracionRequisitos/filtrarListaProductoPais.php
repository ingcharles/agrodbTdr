<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorUsuarios.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$cr = new ControladorRequisitos();
	$cu = new ControladorUsuarios();
		
	$arrayPerfil=array('PFL_SANID_ANIMA','PFL_SANID_VEGET','PFL_LABORATORIO','PFL_INOCU_ALIME','PFL_INSUM_PLAGU','PFL_INSUM_VETER','PFL_INSUM_PRO_AU');
	$banderaPerfil=false;
	foreach ($arrayPerfil as $codificacionPerfil ){

		$qVerificarUsuario=$cu->verificarUsuario($conexion, $_SESSION['usuario'],$codificacionPerfil);
		if(pg_num_rows($qVerificarUsuario)>0){
			$banderaPerfil=true;
			switch ($codificacionPerfil){
				case 'PFL_SANID_ANIMA':		
					$areaTematica.= "'SA',";
					break;
						
				case 'PFL_SANID_VEGET':
					$areaTematica.="'SV',";
					break;
						
				case 'PFL_LABORATORIO':
					$areaTematica.="'LT',";
				break;
					
				case 'PFL_INOCU_ALIME':
					$areaTematica.="'AI',";
				break;
	
				case 'PFL_INSUM_PLAGU':
					$areaTematica.="'IAP',";
					break;
	
				case 'PFL_INSUM_VETER':
					$areaTematica.="'IAV',";
					break;
					
				case 'PFL_INSUM_FERTIL':
					$areaTematica.="'IAF',";
				break;
				
				case 'PFL_INSUM_PRO_AU':
					$areaTematica.="'IAPA',";
				break;
			}
		}
	}
	
	if(!$banderaPerfil)
		$areaTematica="''";


?>


<header>
	<h1>Requistos producto país</h1>
	
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
	
	<?php 
		$tipoProdcuto = $cr->obtenerTipoProductoXrequisitoProductoPais($conexion,"(" . rtrim ( $areaTematica, ',' ) . ")");
	   	if(!$banderaPerfil){ echo '<pre><center><label class="alerta">El técnico aún no tiene asignado ningún perfil</label></center></pre>';}else{?>
			<nav style="width: 78%;">
			<form id="flitrarOpcionProducto" data-rutaAplicacion="administracionRequisitos" data-opcion="listaProductoPais" data-destino="contenedor">
				<input type="hidden" id="opcion" name="opcion" />
		
				<table class="filtro">
					<tr>
						<td>
							<label>Tipo producto: </label>
							<select id="fTipoProducto" name="fTipoProducto" style="width: 74%;">
								<option value="">Seleccione una opción</option>
								<?php 
									while ($fila = pg_fetch_assoc($tipoProdcuto)){
										echo '<option value="'.$fila['id_tipo_producto'].'">'.$fila['nombre'].'</option>';
									}
								?>
							</select>
							<input id="fNombreTipoProducto" name="fNombreTipoProducto" type="hidden"/>
						</td>	
					</tr>
					<tr id="tSubTipoProducto"></tr>		
					<tr id="tProducto"></tr>
					<tr>
						<td colspan="5"><button>Filtrar lista</button></td>
					</tr>
				</table>
				</form>	
			</nav>
	<?php } ?>	
	

</header>
<div id="contenedor"></div>
<script>

	$("#fTipoProducto").change(function (event) {
		$("#flitrarOpcionProducto").attr('data-opcion', 'combosProducto');
	    $("#flitrarOpcionProducto").attr('data-destino', 'tSubTipoProducto');
	    $("#opcion").val('subTipoProducto');
	    $("#fNombreTipoProducto").val($("#fTipoProducto  option:selected").text());
	    abrir($("#flitrarOpcionProducto"), event, false); //Se ejecuta ajax, busqueda de sub tipo producto
	});

	$("#flitrarOpcionProducto").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		if ($('#fTipoProducto').val()==""){
			error = true;	
			$("#fTipoProducto").addClass("alertaCombo");
		} 

		if (!error){
			$("#flitrarOpcionProducto").attr('data-opcion', 'listaProductoPais');
		    $("#flitrarOpcionProducto").attr('data-destino', 'contenedor');
			abrir($(this),event,false);
		}
	});
						
</script>
