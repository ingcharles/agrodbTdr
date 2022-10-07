<?php 
  session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRegistroOperador.php';
    require_once '../../clases/ControladorCatalogos.php';
	
    $conexion = new Conexion();
    $cr = new ControladorRegistroOperador();
    $cc = new ControladorCatalogos();

	$idOperacion=$_POST['id'];
	$mostrarTipoProducto = true;
	
	$qOperacion=$cr->abrirOperacionXid($conexion, $idOperacion);
	$operacion = pg_fetch_assoc($qOperacion);
	
	$qTipoOperacion = $cc->obtenerDatosTipoOperacion($conexion, $operacion['id_tipo_operacion']);
	$tipoOperacion = pg_fetch_assoc($qTipoOperacion);
	
	$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
	
	$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
	$historialOperacion = pg_fetch_assoc($qHistorialOperacion);
	
	$listaRepresentante = $cr->obtenerRepresentanteTecnicoPorIdOperacionIdOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $idOperacion, $tipoOperacion['id_area']);
	
	$opcionArea = '<option value="">Seleccione...</option>';
	
	switch ($tipoOperacion['id_area']){
		case 'SV':
			$area = "('".$tipoOperacion['id_area']."')";
			$opcionArea .= '<option value="SV" selected="selected">Sanidad vegetal</option>';
			$mostrarTipoProducto = true;
		break;
		case 'SA':
			$area = "('".$tipoOperacion['id_area']."')";
			$opcionArea .= '<option value="SA" selected="selected">Sanidad animal</option>';
			$mostrarTipoProducto = true;
		break;
		case 'IAV':
			$area = "('".$tipoOperacion['id_area']."')";
			$opcionArea .= '<option value="IAV" selected="selected">Registros de insumos pecuarios</option>';
			$mostrarTipoProducto = false;
		break;
		case 'IAP':
			$area = "('".$tipoOperacion['id_area']."')";
			$opcionArea .= '<option value="IAP" selected="selected">Registros de insumos agrícolas</option>';
			$mostrarTipoProducto = false;
		break;
		case 'IAF':
			$area ="('".$tipoOperacion['id_area']."')";
			$opcionArea .= '<option value="IAF" selected="selected">Registros de insumos fertilizantes</option>';
			$mostrarTipoProducto = false;
		break;
		case 'CGRIA':
			$area = "('IAV','IAP','IAF')";
			$opcionArea .= '<option value="IAV">Registros de insumos pecuarios</option>
							<option value="IAP">Registros de insumos agrícolas</option>
							<option value="IAF">Registros de insumos fertilizantes</option>';
			$mostrarTipoProducto = false;
		break;
	}
	
	$datoObservacion = '<fieldset id= "fObservaciones"><legend>Novedades presentadas en la operación.</legend>';

	if($operacion['estado'] == 'subsanacionRepresentanteTecnico' && ($operacion['estado_anterior'] == 'inspeccion' || $operacion['estado_anterior'] == 'documental' || $operacion['estado_anterior'] == 'asignadoInspeccion' || $operacion['estado_anterior'] == 'asignadoDocumental')){
		$datoObservacion .='<label class = "observacionDocumento">Observación: </label><div>'. $operacion['observacion'].'</div>';
	}else if($operacion['estado'] == ' subsanacionRepresentanteTecnico' && $operacion['estado_anterior'] == 'registrado'){
		$datoObservacion .='<label class = "observacionDocumento">Operación en nuevo proceso de verificación.</div><hr>';
	}
	
	$datoObservacion .='</fieldset>';

?>

<header>
	<h1>Nuevo Representante Técnico</h1>
</header>

<form id='nuevoRegistro' data-rutaAplicacion='registroOperador' data-opcion ='guardarNuevoRepresentanteTecnico'>

	<input type="hidden" id="idOperacion" name="idOperacion" value="<?php echo $idOperacion;?>"/>
	<input type="hidden" id="idOperadorTipoOperacion" name="idOperadorTipoOperacion" value="<?php echo $idOperadorTipoOperacion;?>"/>
	<input type="hidden" id="idHistorialOperacion" name="idHistorialOperacion" value="<?php echo $historialOperacion['id_historial_operacion'];?>"/>
	<input type="hidden" id="idAreaOperacion" name="idAreaOperacion" value="<?php echo $tipoOperacion['id_area'];?>">
	
	<?php echo $datoObservacion;?>

	<fieldset id="datosConsultaWebServices">
		<legend>Representate Técnico</legend>
		
			<div data-linea="1">
			<label>Área temática: </label>
				<select id="idArea" name="idArea" required="required">
					<?php echo $opcionArea;?>
				</select>
			</div>
			<?php if($mostrarTipoProducto){
					echo '<div data-linea="2">
						<label>Tipo de Producto: </label>
						<select id="tipoProducto" name="tipoProducto">
							<option value="">Seleccione...</option>';
							$qTipoProducto= $cc->listarTipoProductosXareas($conexion,"in  $area");
							while ($fila = pg_fetch_assoc($qTipoProducto)){
								$opcionesTipoProducto[] =  '<option data-area="'.$fila['id_area']. '"  value="'.$fila['id_tipo_producto']. '" >'. $fila['nombre'] .'</option>';
							}
					echo '</select>
						<input type="hidden" id="nombreTipoProducto" name="nombreTipoProducto" />
					</div>';
				}
			?>

			<div data-linea="3" >
				<label>Identificación: </label>
				<input type="text" id="numero" name="numero" required="required" maxlength="10"/>
				<input type="hidden" id="clasificacion" name="clasificacion" value="Senecyt"/>
			</div>

			<div data-linea="4" >
				<label>Nombre Completo: </label>
				<input type="text" id="nombreTecnico" name="nombreTecnico" maxlength="256" required="required" readonly="readonly"/>
			</div>

			<div data-linea="5">
				<label>Título: </label>
				<select id="tituloTecnico" name="tituloTecnico" required="required">
				</select>
				<input type="hidden" id="nombreTituloTecnico" name="nombreTituloTecnico" data-resetear='no'/>
			</div>

			<div data-linea="6">
				<label>Número registro: </label>
				<input type="text" id="numeroRegistro" name="numeroRegistro" readonly="readonly" required="required"/>
			</div>

			<div data-linea="7">
				<button type="submit" class="mas">Agregar</button>
			</div>

	</fieldset>
	<div id="mensajeCargando"></div>
</form>

<fieldset>
	<legend>Representates técnicos asignados.</legend>
	<table id="registros" style="width:100%;">
		<thead>	
			<tr>
				<th>Identificación</th>
				<th>Nombre</th>
				<th>Título</th>
				<th>Número registro</th>
				<th>Tipo Producto</th>
				<th></th>
			</tr>
		</thead>
		<?php
			while ($representanteTecnico = pg_fetch_assoc($listaRepresentante)){
				echo $cr->imprimirLineaRepresentanteTecnico($representanteTecnico['id_detalle_representante_tecnico'], $representanteTecnico['identificacion_representante'], $representanteTecnico['nombre_representante'], $representanteTecnico['titulo_academico'], $representanteTecnico['numero_registro_titulo'], ($representanteTecnico['nombre'] == ''? 'N/A': $representanteTecnico['nombre']));
			}
		?>
	</table>
</fieldset>

<form id='nuevoRepresentante' data-rutaAplicacion='registroOperador' data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="idOperacion" name="idOperacion" value="<?php echo $idOperacion;?>"/>
	<input type="hidden" id="idOperadorTipoOperacion" name="idOperadorTipoOperacion" value="<?php echo $idOperadorTipoOperacion;?>"/>
	<input type="hidden" id="idHistorialOperacion" name="idHistorialOperacion" value="<?php echo $historialOperacion['id_historial_operacion'];?>"/>
	<input type="hidden" id="idAreaOperacion" name="idAreaOperacion" value="<?php echo $tipoOperacion['id_area'];?>">
	<button type="submit" id="btnGuardar"  name="btnGuardar" class="guardar" >Guardar</button>
</form>

<script type="text/javascript">	

	var array_comboTipoProducto = <?php echo json_encode($opcionesTipoProducto);?>;
	var verificar_producto = <?php echo json_encode($mostrarTipoProducto);?>;
	
	$(document).ready(function(event){
		distribuirLineas();
		if(verificar_producto){
			for(var i=0; i<array_comboTipoProducto.length; i++){
				 $('#tipoProducto').append(array_comboTipoProducto[i]);
		   }
		}
		acciones();
		$("#fObservaciones").hide();
		if($(".observacionDocumento").length != 0){
			$("#fObservaciones").show();
		}
	});

	$("#nuevoRepresentante").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if ($('#registros >tbody >tr').length == 0){
			 error = true;	
			 $("#registros").addClass("alertaCombo");
			 $("#estado").html('Por favor agregre al menos un representante técnico.').addClass("alerta");
		}

		if (!error){
			$("#estado").html("").removeClass('alerta');
			$("#nuevoRepresentante").attr('data-destino', 'detalleItem'); 
			$('#nuevoRepresentante').attr('data-opcion','actualizarEstadoOperacionRepresentanteTecnico');
			ejecutarJson("#nuevoRepresentante");	
		}	
	});

	$("#numero").change(function(event){

		event.preventDefault();
		var $botones = $("form").find("button[type='submit']"),
    	serializedData = $("#datosConsultaWebServices").serialize(),
    	url = "aplicaciones/general/consultaWebServices.php";
		
    	$botones.attr("disabled", "disabled");
    	$('#nombreTecnico').val('');
	    $('#tituloTecnico').html('<option value="">Seleccione...</option>');
	     resultado = $.ajax({
		    url: url,
		    type: "post",
		    data: serializedData,
		    dataType: "json",
		    async:   true,
		    beforeSend: function(){
		    	$("#estado").html('').removeClass();
		    	$("#mensajeCargando").html("<div id='cargando'>Cargando...</div>").fadeIn();
			},
			
		    success: function(msg){
		    	if(msg.estado=="exito"){
			    	$(msg.valores).each(function(i){
			    		$('#nombreTecnico').val(this.titulo.nombres);
			    		$('#tituloTecnico').append("<option value="+i+" data-numero= "+this.titulo.numeroRegistro+">"+this.titulo.nombreTitulo+"</option>");
				    });	
		    	}else{
		    		mostrarMensaje(msg.mensaje,"FALLO");
			    }
		   },
		    error: function(jqXHR, textStatus, errorThrown){
		    	$("#cargando").delay("slow").fadeOut();
		    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
		    },
	        complete: function(){
	        	$("#cargando").delay("slow").fadeOut();
	        	$botones.removeAttr("disabled");	
	        }
		});

	});

	$("#tituloTecnico").change(function(event){
		$("#nombreTituloTecnico").val($("#tituloTecnico option:selected").text());
		$("#numeroRegistro").val($("#tituloTecnico option:selected").attr('data-numero'));
	});

	$("#tipoProducto").change(function(){
		$("#nombreTipoProducto").val($("#tipoProducto option:selected").text());
	});
	
</script>	