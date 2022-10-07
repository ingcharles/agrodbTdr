<?php 

	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$idSubtipoProducto = $_POST['idSubtipoProducto'];
	$areaSubProducto = $_POST['areaSubProducto'];
	$identificador = $_SESSION['usuario'];
	
	$conexion = new Conexion();
	$cr = new ControladorRequisitos();	
	$cc = new ControladorCatalogos();
	
	$subtipoProducto = pg_fetch_assoc($cr->abrirSubtipoProducto($conexion, $idSubtipoProducto));
	$productos = $cr->listaProductos($conexion, $idSubtipoProducto);
	//$areaSubproducto = pg_fetch_assoc($cr->obtenerSubProductoXarea($conexion,$idSubtipoProducto));
	
	$unidades = $cc->listarUnidadesMedida($conexion);
	
	$fecha1= date('Y-m-d - H-i-s');
	$fecha = str_replace(' ', '', $fecha1);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Detalle de subtipo de producto</h1>
	</header>
	<div id="estado"></div>
	<form id="regresar" data-rutaAplicacion="administracionProductos" data-opcion="abrirTipoProducto" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $subtipoProducto['id_tipo_producto'];?>"/>
		<button class="regresar">Regresar a Tipo de Producto</button>
	</form>
	
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="actualizarSubTipoProducto" data-rutaAplicacion="administracionProductos" data-opcion="modificarSubtipoProducto" >
					<input type="hidden" id="idSubtipoProducto" name="idSubtipoProducto" value="<?php echo $subtipoProducto['id_subtipo_producto'];?>">
					<input type="hidden" id="areaSubtipo" name="areaSubtipo" value="<?php echo $areaSubProducto;?>">
					<fieldset>
						<legend>Subtipo de Producto</legend>	
						<div data-linea="1">
							<label for="nombreSubtipoProducto" >Nombre</label>
							<input id="nombreSubtipoProducto" name="nombreSubtipoProducto" type="text" value="<?php echo $subtipoProducto['nombre'];?>" required="required" disabled="disabled"/>
						</div>
						
						<button id="modificar" type="button" class="editar">Editar</button>
						<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
						

					</fieldset>
				</form>	
				
				<form id="nuevoProducto" data-rutaAplicacion="administracionProductos" data-opcion="guardarNuevoProducto" >
				
					<input id="idSubtipoProducto" name="idSubtipoProducto" type="hidden" value="<?php echo $subtipoProducto['id_subtipo_producto'];?>" />
					<input id="idTipoProducto" name="idTipoProducto" type="hidden" value="<?php echo $subtipoProducto['id_tipo_producto'];?>" />
					<input id="area" name="area" type="hidden" value="<?php echo $areaSubProducto;?>" />
					<input id="identificadorCreacion" name="identificadorCreacion" type="hidden" value="<?php echo $identificador;?>" />
							
					<fieldset>
						<legend>Productos</legend>	
						<div data-linea="1">
							<label for="nombreProducto">Nombre común</label>
							<input name="nombreProducto" id="nombreProducto" type="text"  required="required" />
						</div>
						
						<div data-linea="1">
							<label for="nombreCientifico">Nombre científico</label>
							<input name="nombreCientifico" id="nombreCientifico" type="text" />
						</div>
						
						<div data-linea="2">
							<label for="partidaArancelaria">Partida Arancelaria</label>
							<input name="partidaArancelaria" id="partidaArancelaria" type="text"  data-er="^[0-9]{10}+$" data-inputmask="'mask': '9999999999'"/>
						</div>
						
						<div data-linea="2">
							<label>Unidad de Medida</label> 
								<select id="unidadMedida" name="unidadMedida" required>
									<option value="" selected="selected">Unidad....</option>
									<?php 
										while ($fila = pg_fetch_assoc($unidades)){
													echo '<option value="' . $fila['codigo'] . '" >'. $fila['nombre'] .'</option>';
										}
									?>
							</select>
						</div>
						
							
						<div data-linea="3">
						<label>Pertenece a programa</label>
						<select id="pertenecePrograma" name="pertenecePrograma" required>
									<option value="" selected="selected">Seleccione....</option>
									<option value="SI">SI</option>
									<option value="NO" >NO</option>
								
							</select>
						</div>
						
						<?php
						
						if($areaSubProducto=="SV"){
							echo "<div data-linea=3>
									<label>Trazabilidad</label>
										<select id=trazabilidad name=trazabilidad required>
											<option value=NO>NO</option>
											<option value=SI>SI</option>
										</select>
								</div>";
						
							echo "<div data-linea=4>
									<label>Movilización</label>
										<select id=movilizacion name=movilizacion required>
											<option value=NO>NO</option>
											<option value=SI>SI</option>
										</select>
								</div>";
							
							echo "<div data-linea='4'>
									<label for='clasificacionProductoSV'>Clasificación</label>
									<select id='clasificacionProductoSV' name='clasificacionProductoSV'>
											<option value=''>Seleccione....</option>
											<option value='ornamentales'>Ornamentales</option>
											<option value='musaceas'>Musaceas</option>
											<option value='otros' selected='selected'>Otros</option>
									</select>
								</div>";
						}
						
						?>			
						
						
						<!-- div data-linea="2">
							<label for="codigoProducto">Código</label>
							<input name="codigoProducto" id="codigoProducto" type="text"  required="required" />
						</div-->
						
						<!-- div data-linea="3">
							<label for="subcodigoProducto">Subcódigo</label>
							<input name="subcodigoProducto" id="subcodigoProducto" type="text"  required="required" />
						</div-->
						
						<div id="productoInocuidad">
							<div data-linea="5">
								<label for="composicion">Composición</label>
								<input name="composicion" id="composicion" type="text" />
							</div>
							<div data-linea="6">
								<label for="formulacion">Formulación</label>
								<input name="formulacion" id="formulacion" type="text" />
							</div>
						</div>

						<div data-linea="7">
						<label>N° piezas</label>
						<select id="numPiezas" name="numPiezas" required>
									<option value="" selected="selected">Seleccione....</option>
									<option value="0">N/A</option>
									<option value="1" >1 pieza</option>
									<option value="2" >2 piezas</option>
									<option value="4" >4 piezas</option>
							</select>
						</div>
						<div data-linea="8">
							<!--label for="documento">Documento</label>
							<input type="file" name="documento" id="documento" /-->
						
							<input type="file" class="archivo" name="informe" accept="application/pdf"/>
							<input type="hidden" class="rutaArchivo" name="archivo" value="0"/>
							<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
							<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/administracionProductos/producto" >Subir archivo</button>
							<input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha;?> "/>							
							<button type="submit" class="mas">Añadir producto</button>
						</div>
					</fieldset>
				</form>
				<fieldset>
					<legend>Productos</legend>
					<table id="productos">
						<?php 
							while ($producto = pg_fetch_assoc($productos)){
								echo $cr->imprimirLineaProducto($producto['id_producto'], $producto['nombre_comun'], $idSubtipoProducto, $areaSubProducto, 'administracionProductos');
							}
						?>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
</body>
<script>

	var area= <?php echo json_encode($areaSubProducto); ?>;
						
	$('document').ready(function(){
		cargarValorDefecto("clasificacionSubtipoSV","<?php echo $subtipoProducto['clasificacion'];?>");
		$('#productoInocuidad').hide();
		construirValidador();

		if(area == 'IAP' || area == 'IAV' || area == 'IAF' || area == 'IAPA'){
			$('#productoInocuidad').show();
			$('#composicion').attr("required", "required");
			$('#formulacion').attr("required", "required");
		}
		
		distribuirLineas();
	});

	/*$('#documento').change(function(event){
		if($("#nombreProducto").val() != ""){
			subirArchivo('documento',$("#nombreProducto").val().replace(/ /g,''),'aplicaciones/administracionProductos/producto', 'archivo');
		}else{
			alert("Por favor ingrese el nombre del producto para subir el documento.");
			$("#documento").val("");
		}
	});*/

	$('button.subirArchivo').click(function (event) {
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , $("#nombreProducto").val().replace(/ /g,'')+$('#fecha').val().replace(/ /g,'')
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });

	/*$('#partidaArancelaria').change(function(event){

		$("#nuevoProducto").attr('data-opcion','consultarCodigoProducto');
		$("#nuevoProducto").attr('data-destino','dCodigoProducto');
		abrir($("#nuevoProducto"),event,false);
		
	});*/

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

	acciones("#nuevoProducto","#productos");
	
</script>
</html>
