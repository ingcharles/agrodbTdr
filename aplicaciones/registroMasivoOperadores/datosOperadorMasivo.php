<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

$identificador=htmlspecialchars ($_POST['id'],ENT_NOQUOTES,'UTF-8');
$datosOperador = pg_fetch_assoc($cro->buscarOperador($conexion, $identificador));

$cantonesT = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$parroquiasT = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');

?>

<header>
	<h1>Datos del Operador</h1>
</header>
<form id="datosOperadorSitio" data-rutaAplicacion="registroMasivoOperadores"  data-accionEnExito="ACTUALIZAR">
	<input type="hidden" value="0" id="opcion" name="opcion" />
	<input type="hidden" value="0" id="nombreProvinciaExistente" name="nombreProvinciaExistente" />
	<input type="hidden" id="idFlujo" name="idFlujo" value="0">
	
	<div id="estado"></div>
	
	<fieldset>
		<legend>Información del operador</legend>
		<div data-linea="1">
				<label >Identificación</label> 
					<input value="<?php echo $datosOperador['identificador'];?>" name="identificador" type="text" id="identificador" readonly="readonly" maxlength="13" data-er="^([a-zA-Z0-9_]{10,13})+$" />
			</div>
			<div data-linea="2">
				<label for="razonSocial" >Razón social</label> 
					<input value="<?php echo $datosOperador['razon_social'];?>" name="razon" type="text" id="razon" placeholder="Nombre de la empresa" class="cuadroTextoCompleto" readonly maxlength="250" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü&. ]+$" />
			</div>
			<div data-linea="3">
				<label >Nombres</label> 
					<input value="<?php echo $datosOperador['nombre_representante'];?>" name="nombreLegal" type="text" id="nombreLegal" placeholder="Nombres" maxlength="200" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" />
			</div>
			<div data-linea="3"> 
			<label >Apellidos</label> 
					<input value="<?php echo $datosOperador['apellido_representante'];?>" name="apellidoLegal" type="text" id="apellidoLegal" placeholder="Apellidos" maxlength="250" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" />
			</div>
	</fieldset>
	
	<fieldset>
		<legend>Agregar sitio</legend>
		<div data-linea="1">
			<label>Sitios</label>
			<select id="sitio" name="sitio">
				<option value="0">Seleccione....</option>
				<option value="nuevoSitio">Crear nuevo sitio</option>
				<?php 
				$resultadoSitio = $cro->listarSitios($conexion, $identificador);
				while($fila = pg_fetch_assoc($resultadoSitio)){
						echo '<option value="' . $fila['id_sitio'] . '">' . $fila['nombre_lugar'] . '</option>';
					}
				?>
			</select> 
		</div>
	</fieldset>
	
	<fieldset id="fsSitio">
		<legend>Información del sitio</legend>		
			<div data-linea="1">
				<label>Nombre del sitio</label> 
					<input type="text" id="nombreSitio" name="nombreSitio" placeholder="Ej: Hacienda La Rosa" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" />
			</div>
			
			<div data-linea="2">
				<label>Provincia</label>
				<select id="provincia" name="provincia">
					<option value="">Provincia....</option>
					<?php 
						$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
						foreach ($provincias as $provincia){
							echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
						}
					?>
				</select> 
			</div>
			
			<div data-linea="2">
				<label>Cantón</label>
				<select id="canton" name="canton" disabled="disabled">
					<option value="">Cantón....</option>
				</select>
			</div>
			
			<div data-linea="2">	
				<label>Parroquia</label>
				<select id="parroquia" name="parroquia" disabled="disabled">
					<option value="">Parroquia....</option>
				</select>
			</div>
			
			<div data-linea="3">
				<label>Dirección</label> 
				<input type="text" id="direccion" name="direccion" placeholder="Ej: Santa Rosa" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" />
			</div>
			
			<div data-linea="4">
				<label>Teléfono</label> 
				<input name="telefono" type="text" id="telefono" placeholder="Ej: (02)375-7549" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15" /> 
			</div>
						
			<div data-linea="4">
				<label>Celular</label> 
				<input name="celular" type="text" id="celular" placeholder="Ej: (09)9759-7899" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" data-inputmask="'mask': '(99) 9999-9999'" size="15" /> 
			</div>
			
			<div data-linea="5">	
			<label>Latitud</label>
				<input type="text" id="latitud" name="latitud" placeholder="785535.2557038852" maxlength="20"/>
			</div>
			
			<div data-linea="5">
			<label>Longitud</label>
				<input type="text" id="longitud" name="longitud" placeholder="2431.0911238068647" maxlength="20"/>
			</div>	
			
			<div data-linea="5">
			<label>Zona</label>
				<input type="text" id="zona" name="zona" placeholder="17" maxlength="2"/>
			</div>
	</fieldset>
	<div id="resultadoSitio"></div>		
	
	
	<fieldset id="fsArea">
		<legend>Agregar operación</legend>
		<div data-linea="1">
			<label>Área temática</label> 
			<select id="areaOperacion" name="areaOperacion">
				<option value="">Seleccione...</option>
				<?php 
					$tipoOperacionAreas = $cro->listarTipoOperacionArea($conexion);
					foreach ($tipoOperacionAreas as $tipoOperacionArea){
						echo '<option  value="' . $tipoOperacionArea['id_area'] . '">' . $tipoOperacionArea['area_operacion'] . '</option>';
					}
				?>
			</select> 
		</div>
		<div id="resultadoTipoOperacion" data-linea="2"></div>
		<div id="resultadoTipoProducto" data-linea="3"></div>
		<div id="resultadoSubTipoProducto" data-linea="4"></div>
		<div id="resultadoProducto" data-linea="5"></div>
		<button type="button"class="mas" id="agregarProducto">Agregar producto</button>	
	</fieldset>
	
	<fieldset id="fsProductos">
		<legend>Productos agregados</legend>
		<table id="detalleProductos" style="width: 100%">
			<thead>
				<tr>
					<th>Tipo producto</th>
					<th>Subtipo producto</th>
					<th>Producto</th>
					<th>Opción</th>
				</tr>
			</thead>			
			<tbody>
			</tbody>
		</table>
	</fieldset>

	<div>
		<button id="btnOperacion" type="submit" class="guardar">Guardar Operación</button>
	</div>
</form>

<script type="text/javascript">
var array_canton= <?php echo json_encode($cantonesT); ?>;
var array_parroquia= <?php echo json_encode($parroquiasT);?>;

	$(document).ready(function(){
		$("#latitud").numeric();
		$("#longitud").numeric();
		$("#zona").numeric();
		distribuirLineas();
		construirValidador();
		$('#fsSitio').hide();
		$('#fsArea').hide();
		$('#fsProductos').hide();
		$('#btnOperacion').hide(); 
		$('#fsSitiosExistentes').hide();
	});

	$("#provincia").change(function(){
    	scanton ='0';
		scanton = '<option value="">Cantón...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provincia").val()==array_canton[i]['padre']){
		    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			    }
	   		}
	    $('#canton').html(scanton);
	    $("#canton").removeAttr("disabled");
	});

    $("#canton").change(function(){
		sparroquia ='0';
		sparroquia = '<option value="">Parroquia...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#canton").val()==array_parroquia[i]['padre']){
		    	sparroquia += '<option value="'+array_parroquia[i]['codigo']+'">'+array_parroquia[i]['nombre']+'</option>';
			    } 
	    	}
	    $('#parroquia').html(sparroquia);
		$("#parroquia").removeAttr("disabled");
	});
	
	$("#sitio").change(function(event){
		if($("#sitio").val() != 0){	
			if($("#sitio").val() != 'nuevoSitio'){  
				 $('#datosOperadorSitio').attr('data-opcion','accionesOperadorMasivo');
	    		 $('#datosOperadorSitio').attr('data-destino','resultadoSitio');
	    		 $('#opcion').val('sitio');		
	    		 abrir($("#datosOperadorSitio"),event,false);
	    		 $('#fsSitiosExistentes').show();	
	    		 $('#fsSitio').hide();
	    		 $('#fsProductos').show();
			}else{ 
				$('#fsSitiosExistentes').hide();
				$("#fsSitio").show();
				$('#fsProductos').show();
			}
			$('#btnOperacion').show();	
			$("#fsArea").show();
			$("#areaOperacion").val(0);
			$("#resultadoTipoOperacion").html('');
			$("#resultadoTipoProducto").html('');
			$("#resultadoSubTipoProducto").html('');
			$("#resultadoProducto").html('');
			distribuirLineas();		
		}else{
			$('#fsSitiosExistentes').hide();
			$("#fsArea").hide();
			$("#fsSitio").hide();
			$('#btnOperacion').hide(); 
		}
	});	

	$("#areaOperacion").change(function(event){
		if($("#areaOperacion").val() != 0){	
			$("#resultadoTipoOperacion").html('');
	 		$("#resultadoTipoProducto").html('');
	 		$("#resultadoSubTipoProducto").html('');
	 		$("#resultadoProducto").html('');	 		 		 
			$('#datosOperadorSitio').attr('data-opcion','accionesOperadorMasivo');
    		$('#datosOperadorSitio').attr('data-destino','resultadoTipoOperacion');
    		$('#opcion').val('tipoOperacion');		
    		abrir($("#datosOperadorSitio"),event,false);    		 		
		}				 	
	 });

	$("#datosOperadorSitio").submit(function(event){
		$('#datosOperadorSitio').attr('data-opcion','actualizarDatosOperadorMasivo');
		event.preventDefault();
		chequearCampos(this);
	});
	
	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		 if($("#sitio").val() == 'nuevoSitio'){
			if(!$.trim($("#direccion").val()) || !esCampoValido("#direccion")){
				error = true;
				$("#direccion").addClass("alertaCombo");
			}

			if(!$.trim($("#nombreSitio").val())){
				error = true;
				$("#nombreSitio").addClass("alertaCombo");
			}	
			 
			if(!$.trim($("#provincia").val())){
				error = true;
				$("#provincia").addClass("alertaCombo");
			}
			
			if(!$.trim($("#canton").val())){
				error = true;
				$("#canton").addClass("alertaCombo");
			}

			if(!$.trim($("#parroquia").val())){
				error = true;
				$("#parroquia").addClass("alertaCombo");
			}

			if(!$.trim($("#telefono").val()) || !esCampoValido("#telefono")){
				error = true;
				$("#telefono").addClass("alertaCombo");
			}

			if(!$.trim($("#celular").val()) || !esCampoValido("#celular")){
				error = true;
				$("#celular").addClass("alertaCombo");
			}
		}

		if($("#sitio").val() == '0'){
			error = true;
			$("#sitio").addClass("alertaCombo");
		}
				
		if(!$.trim($("#nombreLegal").val()) || !esCampoValido("#nombreLegal")){
			error = true;
			$("#nombreLegal").addClass("alertaCombo");
		}

		if(!$.trim($("#apellidoLegal").val()) || !esCampoValido("#apellidoLegal")){
			error = true;
			$("#apellidoLegal").addClass("alertaCombo");
		}
		
		if(!$.trim($("#areaOperacion").val())) {
			error = true;
			$("#areaOperacion").addClass("alertaCombo");
		}

		if(!$.trim($("#tipoProducto").val())) {
			error = true;
			$("#tipoProducto").addClass("alertaCombo");
		}

		if(!$.trim($("#subTipoProducto").val())) {
			error = true;
			$("#subTipoProducto").addClass("alertaCombo");
		}

		if(!$.trim($("#tipoOperacion").val())) {
			error = true;
			$("#tipoOperacion").addClass("alertaCombo");
		}

		if (error)
			$("#estado").html("Por favor ingrese o revise el formato toda información.").addClass('alerta');
		else
			ejecutarJson(form);
		
	 }

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	//Funcion para agregar puertos de país de destino//
    $("#agregarProducto").click(function(event) {
    	event.preventDefault();
       	mostrarMensaje("", "");
    	
    	$(".alertaCombo").removeClass("alertaCombo");
    	var error = false;

    	if($("#areaOperacion").val() == ""){
			error = true;
			$("#areaOperacion").addClass("alertaCombo");
		}
    	
    	if($("#tipoOperacion").val() == ""){
			error = true;
			$("#tipoOperacion").addClass("alertaCombo");
		}

    	if($("#tipoProducto").val() == ""){
			error = true;
			$("#tipoProducto").addClass("alertaCombo");
		}

    	if($("#subTipoProducto").val() == ""){
			error = true;
			$("#subTipoProducto").addClass("alertaCombo");
		}

		if(!error){

			if($("#areaOperacion").val() != "" && $("#tipoOperacion").val() != "" && $("#tipoProducto").val() != "" && $("#subTipoProducto").val() != ""){

                $('input[name="producto[]"]:checked').each(function() {

                	$('#areaOperacion option:not(:selected)').attr('disabled',true);
                	$('#tipoOperacion option:not(:selected)').attr('disabled',true);
                    
                	var tipoProducto = $("#tipoProducto option:selected").text();
                	var subtipoProducto = $("#subTipoProducto option:selected").text();
                	var nombreProducto = $(this).attr('data-nombreProducto');
                    var codigoDetalleProductos = 'r_' + $(this).val();
                	var cadena = '';
                	
                	if($("#detalleProductos tbody #" + codigoDetalleProductos.replace(/ /g,'')).length == 0){
                
                		cadena = "<tr id='" + codigoDetalleProductos.replace(/ /g,'') + "'>" +
                		"<td>" + tipoProducto +
                		"</td>" +
                		"<td>" + subtipoProducto +
                		"</td>" +
                		"<td>" + nombreProducto +
                		"<input name='iProducto[]' value='" + $(this).val() + "' type='hidden'>" +
                		"</td>" +
                		"<td>" +
                		"<button type='button' onclick='quitarDetalleProductos(" + codigoDetalleProductos.replace(/ /g,'') + ")' class='menos'>Quitar</button>" +
                		"</td>" +				
                		"</tr>"
                
                		$("#detalleProductos tbody").append(cadena);
                		
                	}               
                });  		
			}
		}
    });

  //Funcion que quita una fila de la tabla exportadores productos
    function quitarDetalleProductos(fila){
		$("#detalleProductos tbody tr").eq($(fila).index()).remove();		
		if($('#detalleProductos tbody tr').length == 0) {	
		   	$('#areaOperacion option:not(:selected)').attr('disabled',false);
		   	$('#tipoOperacion option:not(:selected)').attr('disabled',false);
		}
	}
	
</script>
