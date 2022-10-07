<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	
	$idSubtipoProducto = $_POST['idSubtipoProducto'];
	$areaSubProducto = $_POST['areaSubProducto'];
	$identificador = $_SESSION['usuario'];									   
	
	$conexion = new Conexion();
	$cr = new ControladorRequisitos();	
	$cc = new ControladorCatalogos();
	$cop = new ControladorRegistroOperador();
	
	$subtipoProducto = pg_fetch_assoc($cr->abrirSubtipoProducto($conexion, $idSubtipoProducto));
	$productos = $cr->listaProductos($conexion, $idSubtipoProducto, '1,2,3,4');

	$unidades = $cc->listarUnidadesMedida($conexion);
			
	while($fila = pg_fetch_assoc($unidades)){
		$unidad[]= array('identificador'=>$fila['id_unidad_medida'], 'codigo'=>$fila['codigo'], 'nombre'=>$fila['nombre'], 'tipo'=>$fila['tipo_unidad']);
	}
	
	$declaracionVenta = $cc->listarDeclaracionVenta($conexion);
	
	while($fila = pg_fetch_assoc($declaracionVenta)){
	    $declaracion[]= array('identificador'=>$fila['id_declaracion_venta'], 'nombre'=>$fila['declaracion_venta']);
	}
?>

	<header>
		<h1>Detalle de subtipo de producto plaguicida</h1>
	</header>
	<div id="estado"></div>
	<form id="regresar" data-rutaAplicacion="registroProducto" data-opcion="abrirTipoProductoPlaguicida" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $subtipoProducto['id_tipo_producto'];?>"/>
		<button class="regresar">Regresar a Tipo de Producto Plaguicida</button>
	</form>
	
	<form id="actualizarSubTipoProducto" data-rutaAplicacion="registroProducto" data-opcion="modificarSubtipoProducto" >
		<input type="hidden" id="idSubtipoProducto" name="idSubtipoProducto" value="<?php echo $subtipoProducto['id_subtipo_producto'];?>">
		<fieldset>
			<legend>Subtipo de Producto</legend>	
			<div data-linea="1">
				<label for="nombreSubtipoProducto" >Nombre</label>
				<input id="nombreSubtipoProducto" name="nombreSubtipoProducto" type="text" value="<?php echo $subtipoProducto['nombre'];?>" required="required" disabled="disabled"/>
				
				<button id="modificar" type="button" class="editar">Editar</button>
				<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
			</div>

		</fieldset>
	</form>	
	
	<form id="nuevoProducto" data-rutaAplicacion="registroProducto" data-opcion="guardarNuevoProductoPlaguicida" >			
		<input id="idTipoProducto" name="idTipoProducto" type="hidden" value="<?php echo $subtipoProducto['id_tipo_producto'];?>" />
		<input id="idSubtipoProducto" name="idSubtipoProducto" type="hidden" value="<?php echo $subtipoProducto['id_subtipo_producto'];?>" />
		<input id="nombreSubtipoProducto" name="nombreSubtipoProducto" type="hidden" value="<?php echo $subtipoProducto['nombre'];?>" />
		<input id="area" name="area" type="hidden" value="<?php echo $areaSubProducto;?>" />
		<input type="hidden" id="opcion" name="opcion" value="0">
		<input id="identificadorCreacion" name="identificadorCreacion" type="hidden" value="<?php echo $identificador;?>" />																									 
				
		<fieldset>
			<legend>Producto</legend>	
			<div data-linea="1">
				<label for="nombreProducto">Nombre: </label>
				<input name="nombreProducto" id="nombreProducto" type="text" maxlength="256" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" required="required" />
			</div>
			
			<div data-linea="1">
				<label for="numeroRegistro">Número de registro: </label>
				<input name="numeroRegistro" id="numeroRegistro" type="text" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü -\/]+$" required="required" />
			</div>
			
			<div id="div2" data-linea="2">
					<label>RUC: </label> 
					<input type="text" id="txtClienteBusqueda" name="txtClienteBusqueda" maxlength="13" data-er="^[0-9]+$" required="required"/>							
			</div>
			
			<div id="res_cliente" data-linea="9">			
				
			</div>
			
			<div data-linea="3">
				<label>Fecha de registro: </label> 
				<input type="text"	id="fecha_registro" name="fecha_registro" required="required" readonly="readonly"/> 
			</div>
			
			<div data-linea="3">
				<label id="lDeclaracionVenta">Declaración de venta</label> 
								<select id="idDeclaracionVenta" name="idDeclaracionVenta">
									<option value="0">Seleccione....</option>
									<?php 
										for($i=0;$i<count($declaracion);$i++)
													echo '<option value="' . $declaracion[$i]['identificador'] . '" >'. $declaracion[$i]['nombre'] .'</option>';
									?>
								</select>
		
								<input type="hidden" id="declaracionVenta" name="declaracionVenta" />
			</div>						
		</fieldset>	

		<fieldset>
			 <legend>Características</legend>
				<div data-linea="4">
					<label for="formulacion">Formulación: </label>
					<select id="formulacion" name="formulacion" required>
						<option value="" selected="selected">Formulación....</option>
							<?php 
								$formulaciones = $cr->listarFormulacion($conexion,$areaSubProducto);
						
								while ($fila = pg_fetch_assoc($formulaciones)) {
									echo '<option value="' . $fila['id_formulacion'] . '" >'. $fila['formulacion'] .'</option>';
								}
							?>
					</select>
				</div>
				<input name="nombreFormulacion" id="nombreFormulacion" type="hidden" />
				
				<div data-linea="4">
					<label>Categoría toxicológica: </label> 
					<select id="caToxicologica" name="caToxicologica">
						<option value="" selected="selected">Categoría....</option>
						<?php 
							$categoriaToxicologica = $cr->listarCategoriaToxicologica($conexion,$areaSubProducto);
							while ($fila = pg_fetch_assoc($categoriaToxicologica)){
								 echo '<option value="' . $fila['id_categoria_toxicologica'] . '" data-periodo= "' . $fila['periodo_reingreso'] . '" >'. $fila['categoria_toxicologica'] .'</option>';
							 }
						?>
					</select>
					<input name="nombreCategoria" id="nombreCategoria" type="hidden" />
				</div>
				
				<div data-linea="5">
					<label for="periodoReingreso">Período de reingreso: </label>
					<input type="text" id="periodoReingreso" name="periodoReingreso" maxlength="2048" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü -\/]+$"/>    														
				</div>
				
				<div data-linea="6">
					<label for="estabilidad">Estabilidad: </label>
					<input type="text" name="estabilidad" id="estabilidad" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü -\/]+$"/>
				</div>
				
				<div data-linea="7">
					<label for="observaciones">Observaciones: </label>
					<input type="text" name="observaciones" id="observaciones" maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü -\/]+$"/>
				</div>

			<div data-linea="8">
				<label for="documento">Documento: </label>
				<input type="file" class="archivo" name="informe" accept="application/pdf"/>
				<input type="hidden" class="rutaArchivo" name="archivo" value="0"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/registroProducto/producto" >Subir archivo</button>
			</div>    						
		</fieldset>				
							
		<div>
			<button type="submit" class="mas">Añadir producto</button>
		</div>
	</form>
				
	<fieldset>
		<legend>Productos</legend>
		<table id="productos">
			<?php 
				while ($producto = pg_fetch_assoc($productos)){
					echo $cr->imprimirLineaProducto($producto['id_producto'], $producto['nombre_comun'], $idSubtipoProducto, $areaSubProducto, 'registroProducto', 'Plaguicida');
				}
			?>
		</table>
	</fieldset>

<script>

	var area= <?php echo json_encode($areaSubProducto); ?>;
	var array_unidades= <?php echo json_encode($unidad); ?>;
	var valorSubtipoProducto = <?php echo json_encode($subtipoProducto['nombre']);?>;

	$('document').ready(function(){
		construirValidador();
		distribuirLineas();
		acciones("#nuevoProducto","#productos");
	});

	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$(this).attr("disabled","disabled");
	});

	$("#actualizarSubTipoProducto").submit(function(event){
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		
		if($.trim($("#nombreSubtipoProducto").val())=="" ){
			error = true;
			$("#nombreSubtipoProducto").addClass("alertaCombo");
		}	
		
		if (!error){
			ejecutarJson($(this));
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#fecha_registro").datepicker({
	    changeMonth: true,
	    changeYear: true
	});	

	$('button.subirArchivo').click(function (event) {
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF' || $("#nombreProducto").val() != "") {

            subirArchivo(
                archivo
                , ($("#nombreProducto").val()+$("#idSubtipoProducto").val()).replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-')
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });	

	$('#formulacion').change(function(event){
		if($("#formulacion").val() != ""){
			$("#nombreFormulacion").val($("#formulacion option:selected").text());
		}
	});
	
	$("#caToxicologica").change(function(){
	    $("#nombreCategoria").val($("#caToxicologica option:selected").text());
	 });

	$("#txtClienteBusqueda").change(function(event){
		if($("#txtClienteBusqueda").val() != ''){
			 $('#nuevoProducto').attr('data-opcion','accionesCliente');
			 $('#nuevoProducto').attr('data-destino','res_cliente');
			 $('#opcion').val('clientePlaguicida');
			 abrir($("#nuevoProducto"),event,false); 
			 $('#nuevoProducto').attr('data-opcion','guardarNuevoProductoPlaguicida');
			 $('#nuevoProducto').attr('data-destino','');
			 distribuirLineas();
		}else{
			$("#txtClienteBusqueda").addClass("alertaCombo");
			$("#razonSocial").val("");
			$("#estado").html("Por ingrese un número de RUC o razón social.").addClass("alerta");
			distribuirLineas();
		}	 
	});

	$('#idDeclaracionVenta').change(function(event){
		if($("#idDeclaracionVenta").val() != "0"){
			$("#declaracionVenta").val($("#idDeclaracionVenta option:selected").text());
		}else{
			$("#declaracionVenta").val('');
		}
	});	
</script>
</html>
