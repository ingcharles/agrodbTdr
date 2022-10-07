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
	$productos = $cr->listaProductos($conexion, $idSubtipoProducto, '1');/*,2,3,4*/

	$unidades = $cc->listarUnidadesMedida($conexion);
			
	while($fila = pg_fetch_assoc($unidades)){
		$unidad[]= array('identificador'=>$fila['id_unidad_medida'], 'codigo'=>$fila['codigo'], 'nombre'=>$fila['nombre'], 'tipo'=>$fila['tipo_unidad']);
	}
	
	$declaracionVenta = $cc->listarDeclaracionVenta($conexion);
	
	while($fila = pg_fetch_assoc($declaracionVenta)){
	    $declaracion[]= array('identificador'=>$fila['id_declaracion_venta'], 'nombre'=>$fila['declaracion_venta']);
	}
?>

<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
</head>

<body>
	<header>
		<h1>Detalle de Subtipo de Producto Materia Prima</h1>
	</header>
	<div id="estado"></div>
	<form id="regresar" data-rutaAplicacion="registroProducto" data-opcion="abrirTipoProductoMateriaPrima" data-destino="detalleItem">
		<input type="hidden" name="id" value="<?php echo $subtipoProducto['id_tipo_producto'];?>"/>
		
		<button class="regresar">Regresar a Tipo de Producto</button>
	</form>
	
	<table class="soloImpresion">
		<tr>
			<td>
				<form id="actualizarSubTipoProducto" data-rutaAplicacion="registroProducto" data-opcion="modificarSubtipoProducto" >
					<input type="hidden" id="idSubtipoProducto" name="idSubtipoProducto" value="<?php echo $subtipoProducto['id_subtipo_producto'];?>">
					<fieldset>
						<legend>Subtipo de Producto</legend>	
						<div data-linea="1">
							<label for="nombreSubtipoProducto" >Nombre</label>
							<input id="nombreSubtipoProducto" name="nombreSubtipoProducto" type="text" value="<?php echo $subtipoProducto['nombre'];?>" required="required" disabled="disabled"/>
							
							<!-- <button id="modificar" type="button" class="editar">Editar</button>
							<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button> -->
						</div>

					</fieldset>
				</form>	
				
				<form id="nuevoProducto" data-rutaAplicacion="registroProducto" data-opcion="guardarNuevoProductoMateriaPrima" >
					
					
					<input id="idSubtipoProducto" name="idSubtipoProducto" type="hidden" value="<?php echo $subtipoProducto['id_subtipo_producto'];?>" />
					<input id="idTipoProducto" name="idTipoProducto" type="hidden" value="<?php echo $subtipoProducto['id_tipo_producto'];?>" />
					<input id="nombreSubtipoProducto" name="nombreSubtipoProducto" type="hidden" value="<?php echo $subtipoProducto['nombre'];?>" />
					<input id="area" name="area" type="hidden" value="<?php echo $areaSubProducto;?>" />
					<input type="hidden" id="opcion" name="opcion" value="0">
					<input id="identificadorCreacion" name="identificadorCreacion" type="hidden" value="<?php echo $identificador;?>" />																									 
							
					<fieldset>
						<legend>Producto</legend>	
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
								<select id="unidadMedida" name="unidadMedida">
									<option value="" selected="selected">Unidad....</option>
									<?php 
										for($i=0;$i<count($unidad);$i++)
													echo '<option value="' . $unidad[$i]['codigo'] . '" >'. $unidad[$i]['nombre'] .'</option>';
									?>
								</select>
						</div>
						
						<div data-linea="3">
							<label for="numeroRegistro">Número de registro</label>
							<input name="numeroRegistro" id="numeroRegistro" type="text" />
						</div>
						
						<div data-linea="4">
							<label>Fecha de registro</label> 
							<input type="text"	id="fecha_registro" name="fecha_registro" required="required"/> 
						</div>
						<div data-linea="5">
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
						
						<div id="div6" data-linea="6">
								<label>RUC Empresa</label> 
								<input type="text" id="txtClienteBusqueda" name="txtClienteBusqueda" maxlength="13"/>							
						</div>
						<div id="res_cliente" data-linea="7"></div>
											
						<div data-linea="8">
							<label for="observaciones">Observaciones/Modificaciones:</label>
							<input name="observaciones" id="observaciones" type="text" />
						</div>

						<div data-linea="9">
							<label for="documento">Documento</label>
							<input type="file" class="archivo" name="informe" accept="application/pdf"/>
						<input type="hidden" class="rutaArchivo" name="archivo" value="0"/>
						<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
						<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/registroProducto/producto" >Subir archivo</button>
						</div>
						
				</fieldset>
				<fieldset>
						 <legend>Caracteristicas</legend>
							<div data-linea="1">
								<label for="formulacion">Formulación</label>
								<select id="formulacion" name="formulacion">
									<option value>Formulación....</option>
										<?php 
											$formulaciones = $cr->listarFormulacion($conexion,$areaSubProducto);
									
											while ($fila = pg_fetch_assoc($formulaciones)) {
												echo '<option value="' . $fila['id_formulacion'] . '" >'. $fila['formulacion'] .'</option>';
											}
										?>
								</select>
							</div>
							<input name="nombreFormulacion" id="nombreFormulacion" type="hidden" />
										 
						<div data-linea="2">
							<label for="dosis">Dosis</label>
							<input name="dosis" id="dosis" type="text" />
						</div>
						
						<div data-linea="2">
							<label>Unidad de Dosis</label> 
								<select id="unidadMedidaDosis" name="unidadMedidaDosis" >
								</select>
						</div>
						
						<div data-linea="3">
							<label>Categoría toxicológica</label> 
								<select id="caToxicologica" name="caToxicologica" >
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
										
						<div data-linea="3">
							<label for="periodoReingreso">Período de reingreso/vida útil</label>
							<input id="periodoReingreso" name="periodoReingreso"  />
														
						</div>
						
						<div data-linea="4">
							<label for="periodoCarencia">Período carencia/retiro</label>
							<input name="periodoCarencia" id="periodoCarencia" type="text" />
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
							    if($areaSubProducto =='IAP'){
							        $TipoMateriaPrima = 'PlaguicidaMateriaPrima';
							    }else{
							        $TipoMateriaPrima = 'MateriaPrima';
							    }
							    
								echo $cr->imprimirLineaProducto($producto['id_producto'], $producto['nombre_comun'], $idSubtipoProducto, $areaSubProducto, 'registroProducto',$TipoMateriaPrima);
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
	var array_unidades= <?php echo json_encode($unidad); ?>;
	var valorSubtipoProducto = <?php echo json_encode($subtipoProducto['nombre']);?>;

	$("#fecha_registro").datepicker({
	    changeMonth: true,
	    changeYear: true
	  });

	$('document').ready(function(){
		//$('#productoInocuidad').hide();
		construirValidador();

		/*if(area == 'IAP' || area == 'IAV' || area == 'IAF' || area == 'IAPA'){
			$('#productoInocuidad').show();
			$('#composicion').attr("required", "required");
			$('#formulacion').attr("required", "required");
			$('#fecha_registro').attr("required", "required");
		}
		if(area == 'IAP'){
			$('#lDeclaracionVenta').hide();
			$('#declaracionVenta').hide();
		}*/

		 distribuirLineas();
		 		
		sunidadMedida ='0';
		sunidadMedida = '<option value="">Unidad...</option>';
	    for(var i=0;i<array_unidades.length;i++){
		    if ($("#unidadMedida").val()!=array_unidades[i]['identificador']){
		    	sunidadMedida += '<option value="'+array_unidades[i]['codigo']+'">'+array_unidades[i]['nombre']+'</option>';
			    }
	   		}
	    $('#unidadMedidaDosis').html(sunidadMedida);
	    $('#unidadMedidaDosis').removeAttr("disabled");	

	    /*if(valorSubtipoProducto.toUpperCase() == 'MATERIAS PRIMAS' || valorSubtipoProducto.toUpperCase() == 'INGREDIENTE ACTIVO GRADO TÉCNICO'){
	    	 $('#numeroRegistro').removeAttr("required");	
	    	 $('#fecha_registro').removeAttr("required");	
	    	 $('#txtClienteBusqueda').removeAttr("required");	
	    	 $('#formulacion').removeAttr("required");
		}  */ 
    	
	});

	/*$('#documento').change(function(event){
		if($("#nombreProducto").val() != ""){
			subirArchivo('documento',$("#nombreProducto").val().replace(/ /g,''),'aplicaciones/registroProducto/producto', 'archivo');
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
	    if(area == 'IAF'){
			$('#periodoReingreso').val($("#caToxicologica option:selected").attr('data-periodo'));
			$('#periodoReingreso').attr("readonly", "readonly");
			$('#periodoReingreso').attr("required", "required");
		}else if (area == 'IAV'){
			$('#periodoReingreso').removeAttr("readonly");
			$('#periodoReingreso').attr("required", "required");
		}
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

	acciones("#nuevoProducto","#productos");

	/*$("#txtClienteBusqueda").change(function(event){
		if($("#txtClienteBusqueda").val() != ''){
			 $('#nuevoProducto').attr('data-opcion','accionesCliente');
			 $('#nuevoProducto').attr('data-destino','res_cliente');
			 $('#opcion').val('clientePlaguicida');
			 abrir($("#nuevoProducto"),event,false); 
			 distribuirLineas();
			 $('#nuevoProducto').attr('data-opcion','guardarNuevoProducto');
			 $('#nuevoProducto').attr('data-destino','');
		}else{
			$("#txtClienteBusqueda").addClass("alertaCombo");
			$("#estado").html("Porfavor ingrese un número de RUC o razón social.").addClass("alerta");
		}	 
	});*/
	
	$('#idDeclaracionVenta').change(function(event){
		if($("#idDeclaracionVenta").val() != "0"){
			$("#declaracionVenta").val($("#idDeclaracionVenta option:selected").text());
		}else{
			$("#declaracionVenta").val('');
		}
	});	
</script>
</html>
