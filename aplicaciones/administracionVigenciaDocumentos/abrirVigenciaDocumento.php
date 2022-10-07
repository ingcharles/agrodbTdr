<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVigenciaDocumentos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cvd = new ControladorVigenciaDocumentos();

$idVigenciaDocumento = $_POST['id'];

$qCabeceraVigencia = $cvd->obtenerCabeceraVigenciaDocumentoPorIdVigencia($conexion, $idVigenciaDocumento);
$cabeceraVigencia = pg_fetch_assoc($qCabeceraVigencia);

$tipoOperacion = $cc -> obtenerTiposOperacionPorIdAreaTematica($conexion, $cabeceraVigencia['area_tematica_vigencia_documento']);

$qVigenciaDeclarada = $cvd->obtenerVigenciaDeclaradaPorIdVigencia($conexion, $idVigenciaDocumento);

$bandera = true;

if($cabeceraVigencia['nivel_lista']=="operacion"){
	
	$bandera = false;
	
}else{	

	$qDetalleVigenciaDocumento = $cvd->obtenerDetalleVigenciaDocumentoPorIdVigencia($conexion, $idVigenciaDocumento);
	$detalleVigenciaDocumento= pg_fetch_assoc($qDetalleVigenciaDocumento);
	
	$qDetalleVigencia = $cvd->obtenerDetalleVigenciaDocumentoPorIdVigenciaPorIdSubtipoProducto($conexion, $idVigenciaDocumento, $detalleVigenciaDocumento['id_subtipo_producto']);
	
	$tipoProducto = $cc->listarTipoProductosXarea($conexion, $cabeceraVigencia['area_tematica_vigencia_documento']);
	
	$qSubtipoProducto = $cc->listarSubProductos($conexion);
	
	while($fila = pg_fetch_assoc($qSubtipoProducto)){
		$subtipoProducto[]= array('idSubtipoProducto'=>$fila['id_subtipo_producto'], 'nombre'=>$fila['nombre'], 'idTipoProducto'=>$fila['id_tipo_producto']);
	}

}

?>


<header>
	<h1>Detalle de vigencia de documento</h1>
</header>

<div id="estado"></div>
		
<form id="nuevoVigenciaDocumento" data-rutaAplicacion="administracionVigenciaDocumentos" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" >

<input type="hidden" id="opcion" name="opcion" value="">

<input type="hidden" id="idVigenciaDocumento" name="idVigenciaDocumento" value="<?php echo $idVigenciaDocumento; ?>">
<div id="datosVigencia">
	<input type="hidden" id="nombreVigenciaAntiguo" name="nombreVigenciaAntiguo" value="<?php echo $cabeceraVigencia['nombre_vigencia_documento']; ?>">
	<input type="hidden" id="tipoOperacionAntiguo" name="tipoOperacionAntiguo" value="<?php echo $cabeceraVigencia['id_tipo_operacion']; ?>">
</div>

<fieldset>
<legend>Datos Generales</legend>

	<div data-linea="1">
		<label for="nombreVihencia">*Nombre vigencia: </label><input type="text" id="nombreVigencia" name="nombreVigencia" value="<?php echo $cabeceraVigencia['nombre_vigencia_documento'];?>"  disabled="disabled" />
	</div>
	<div data-linea="2">
		<label for="etapaVigencia">*Etapa de uso: </label>
		<select id="etapaVigencia" name="etapaVigencia" disabled="disabled" >
			<option value="">Seleccione....</option>
			<option value="documental">Documental</option>
			<option value="inspeccion">Inspección</option>
			<option value="cargarRespaldo">Cargar Convenio</option>
		</select>
	</div>
	<hr/>
	<div data-linea="3">			
		<label for="tipoDocumento">*Tipo de Documento: </label>
		<select id="tipoDocumento" name="tipoDocumento" disabled="disabled" > 
			<option value="">Seleccione....</option>
			<option value="CRO">Certificado de registro de operador</option>
			<option value="PF">Permiso de funcionamiento</option>
		</select>
	</div>
	<div data-linea="4">			
		<label for="area">*Área Temática: </label>
			<select id="area" name="area" disabled="disabled" >
				<option value="">Seleccione....</option>
				<option value="SV">Sanidad vegetal</option>
				<option value="SA">Sanidad animal</option>
				<option value="IAP">Registros de insumos agrícolas</option>
				<option value="IAV">Registros de insumos pecuarios</option>
				<option value="IAF">Registros de insumos fertilizantes</option>
				<option value="IAPA">Registro de insumos para plantas de autoconsumo</option>
				<option value="AI">Inocuidad de los alimentos</option>
				<option value="LT">Laboratorios</option>		
			</select>
	</div>
	<div data-linea="5" id="resultadoArea">			
		<label for="tipoOperacion">*Operación: </label>
		<select id="tipoOperacion" name="tipoOperacion" disabled="disabled" class="itemsRadio">
			<option value="">Seleccione....</option>
				<?php 
					while ($fila = pg_fetch_assoc($tipoOperacion)){
						$opcionesTipoOperacion[] =  '<option value="'.$fila['id_tipo_operacion']. '" >'. $fila['nombre'] .'</option>';
					}
				?>
		</select>
	</div>	
	
		
	<?php if ($bandera){?>
		
		<div data-linea="6" id="resultadoTipoOperacion">			
			<label for="tipoProducto">*Tipo Producto: </label>
			<select id="tipoProducto" name="tipoProducto" disabled="disabled" >
				<option value="">Seleccione....</option>
					<?php 
						while ($fila = pg_fetch_assoc($tipoProducto)){
							$opcionesTipoProducto[] =  '<option value="'.$fila['id_tipo_producto']. '" >'. $fila['nombre'] .'</option>';
						}
					?>
			</select>
		</div>	

		<div data-linea="7" id="resultadoTipoProducto">			
			<label for="subTipoProducto">*Subtipo Producto: </label>
			<select id="subTipoProducto" name="subTipoProducto" disabled="disabled" >
			
			</select>
		</div>	
		<hr/>
		<div data-linea="8" id="resultadoSubtipoProducto">
		<?php 
				echo '<label>Seleccione uno o varios Productos</label>
			
							<div class="seleccionTemporal">
								<input class="seleccionTemporal"  id = "cTemporal" type = "checkbox" disabled="disabled" />
						    	<label >Seleccionar todos </label>
							</div>
			
						<hr>
					 <div><table style="border-collapse: initial;"><tr>';
				$agregarDiv = 0;
				$cantidadLinea = 0;
				
				while ($fila = pg_fetch_assoc($qDetalleVigencia)){
	
					if($fila['seleccion'] == 'SI'){
	
						echo '<td><input id="'.$fila['id_producto'].'" type="checkbox" name="producto[]" class="productoActivar" data-resetear="no" value="'.$fila['id_producto'].'" checked disabled="disabled" />
						 	<label for="'.$fila['id_producto'].'">'.$fila['nombre_comun'].'</label></td>';		
					}else{
						
						echo '<td><input id="'.$fila['id_producto'].'" type="checkbox" name="producto[]" class="productoActivar" data-resetear="no" value="'.$fila['id_producto'].'" disabled="disabled" />
						 	<label for="'.$fila['id_producto'].'">'.$fila['nombre_comun'].'</label></td>';
					}	
						$agregarDiv++;
						
						if(($agregarDiv % 3) == 0){
							echo '</tr><tr>';
							$cantidadLinea++;
						}
							
						if($cantidadLinea == 9){
							echo '<script type="text/javascript">$("#contenedorProducto").css({"height": "250px", "overflow": "auto"}); </script>';
						}					
					}
	
				echo '</tr></table></div>';
			
	}else{?>
		<div data-linea="6" id="resultadoTipoOperacion">
		</div>
		<div data-linea="7" id="resultadoTipoProducto">		
		</div>
		<div data-linea="9" id="resultadoSubtipoProducto">
		</div>
	<?php }
		?>
		
		</div>
		<div id="popup" style="display: none;">
    		<div class="content-popup">
		           <h5 class="aviso">Confirmación de configuración</h5>
		           <label><br/>Por favor confirme si los datos configurados son correctos, y dar click a confirmar. </label>
		           <div class="botonesAviso">
		           		<button type="submit" >Confirmar</button>
		           		<button id="close" type="button" >Cancelar</button>
		           </div>
	    	</div>    
		</div>
	
</fieldset>

<div>
	<button id="modificar" type="button" class="editar">Modificar</button>
	<button id="actualizar" type="submit" class="guardar" disabled="disabled" disabled="disabled">Actualizar</button>
</div>

</form>

<form id="abrirDetalleVigenciaDocumento" data-rutaAplicacion="administracionVigenciaDocumentos" data-opcion="guardarVigenciaDeclarada" >	
	<input type="hidden" id="idVigenciaDocumento" name="idVigenciaDocumento" value="<?php echo $idVigenciaDocumento; ?>">
	<fieldset>	
		<legend>Declarar vigencia de documento</legend>			
		<div data-linea="7">			
			<label for="valorTiempoVigencia">*Vigencia: </label> <input type="text" id="valorTiempoVigencia" name="valorTiempoVigencia" onkeypress="soloNumeros()">		
		</div>		
		<div data-linea="7" id="">	
			<select id="tipoTiempoVigencia" name="tipoTiempoVigencia" >
				<option value="" selected="selected">Seleccione....</option>
			</select>		
		</div>
		<hr/>	
		<div data-linea="8">	
		<label>*Observación: </label><input type="text" id="observacionVigencia" name="observacionVigencia" >	
		</div>	
		<button type="submit" class="mas">Añadir vigencia</button>		
	</fieldset>
</form>

<fieldset id="anadirDeclararVigenciaDocumento">
	<legend>Vigencias declaradas</legend>
		<table id="codigoDeclararVigencia">
			<thead><tr><th>Vigencia</th><th>Observación</th><th colspan="3">Opciones</th></tr></thead>
				<?php 
				
					$contador = 0;
					while ($vigenciaDeclarada = pg_fetch_assoc($qVigenciaDeclarada)){
						echo $cvd -> imprimirLineaDeclararVigenciaDocumento($idVigenciaDocumento, $vigenciaDeclarada['id_vigencia_declarada'], $vigenciaDeclarada['valor_tiempo_vigencia_declarada'], $vigenciaDeclarada['tipo_tiempo_vigencia_declarada'], $vigenciaDeclarada['observacion_vigencia_declarada'], $vigenciaDeclarada['estado_vigencia_declarada']);
					}
				?>
		</table>
</fieldset>


<script type="text/javascript">

var array_comboTipoOperacion = <?php echo json_encode($opcionesTipoOperacion);?>;
var array_comboTipoProducto = <?php echo json_encode($opcionesTipoProducto);?>;
var array_comboSubTipoProducto = <?php echo json_encode($subtipoProducto);?>;
var bandera = <?php echo json_encode($bandera);?>;


$(document).ready(function(){

	cargarValorDefecto("tipoDocumento","<?php echo $cabeceraVigencia['tipo_documento'];?>");
	cargarValorDefecto("area","<?php echo $cabeceraVigencia['area_tematica_vigencia_documento'];?>");
	cargarValorDefecto("etapaVigencia","<?php echo $cabeceraVigencia['etapa_vigencia'];?>");

	acciones('#abrirDetalleVigenciaDocumento', '#codigoDeclararVigencia', null, null, new exitoDeclararVigencia(), null, null, new verificarInputsDeclararVigencia());
	
	var contadorUno = 0;
	var contadorDos = 0;

	distribuirLineas();
	
	for(var i=0; i<array_comboTipoOperacion.length; i++){
		 $('#tipoOperacion').append(array_comboTipoOperacion[i]);
  	}

	cargarValorDefecto("tipoOperacion","<?php echo $cabeceraVigencia['id_tipo_operacion'];?>");
  	
	if(bandera){		
		
		for(var i=0; i<array_comboTipoProducto.length; i++){
			 $('#tipoProducto').append(array_comboTipoProducto[i]);
	   	}
	
		cargarValorDefecto("tipoProducto","<?php echo $detalleVigenciaDocumento['id_tipo_producto'];?>");
		
	
		sSubTipoProducto = '<option value="">Seleccione....</option>';
		
		for(var i=0; i<array_comboSubTipoProducto.length; i++){
			
			if(array_comboSubTipoProducto[i]['idTipoProducto'] == $('#tipoProducto').val()){
				sSubTipoProducto += '<option value="'+array_comboSubTipoProducto[i]['idSubtipoProducto']+'">'+array_comboSubTipoProducto[i]['nombre']+'</option>';
			}
	
			 $('#subTipoProducto').html(sSubTipoProducto);
			 cargarValorDefecto("subTipoProducto","<?php echo $detalleVigenciaDocumento['id_subtipo_producto'];?>");
	   	}		
		
		$('input[name="producto[]"]').each(function() {
			contadorUno++;			
				if( $(this).is(':checked') ) {
			        contadorDos++;
			}
				
		    if(contadorUno == contadorDos){
		    	$('#cTemporal').prop( "checked", true );
			}else{
				$('#cTemporal').prop( "checked", false );
			}
		});	
	
	}
    
});


function soloNumeros() { 
	if ((event.keyCode < 48) || (event.keyCode > 57))
		event.returnValue = false;	
}


$('#modificar').click(function(event){
	$("input").removeAttr("disabled");
	$("select").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
	if(!bandera){
		$("#tipoOperacion").trigger("change");
	}
});


$("#tipoOperacion").on("change",function (event){
	$("#resultadoTipoOperacion").show();	
	$("#productosAgregados").hide();	
	$("#resultadoSubtipoProducto").hide();	
	$("#subTipoProducto").val("");
	$("#nuevoVigenciaDocumento").attr('data-opcion','accionesVigenciaDocumento');
	$("#nuevoVigenciaDocumento").attr('data-destino','resultadoTipoOperacion');
	$('#opcion').val('tipoOperacion');	
	event.stopImmediatePropagation();		
	abrir($("#nuevoVigenciaDocumento"),event,false);
});


$('#valorTiempoVigencia').change(function(event){
	
	if($('#valorTiempoVigencia').val() == 0){
		$('#tipoTiempoVigencia').html("");
		$("#tipoTiempoVigencia").append("<option value=''>Seleccione...</option>");
	}else if($('#valorTiempoVigencia').val() == 1){
		$('#tipoTiempoVigencia').html("");
		$("#tipoTiempoVigencia").append("<option value=''>Seleccione...</option>");
		$("#tipoTiempoVigencia").append("<option value='anio'>Año</option>");
		$("#tipoTiempoVigencia").append("<option value='mes'>Mes</option>");
		$("#tipoTiempoVigencia").append("<option value='dia'>Día</option>");
	}else if($('#valorTiempoVigencia').val() > 1){
		$('#tipoTiempoVigencia').html("");
		$("#tipoTiempoVigencia").append("<option value=''>Seleccione...</option>");
		$("#tipoTiempoVigencia").append("<option value='anio'>Años</option>");
		$("#tipoTiempoVigencia").append("<option value='mes'>Meses</option>");
		$("#tipoTiempoVigencia").append("<option value='dia'>Días</option>");
	}
	
});


$('#valorNotificacionVigencia').change(function(event){
	
	if($('#valorNotificacionVigencia').val() == 0){
		$('#tipoNotificacionVigencia').html("");
		$("#tipoNotificacionVigencia").append("<option value=''>Seleccione...</option>");
	}else if($('#valorNotificacionVigencia').val() == 1){
		$('#tipoNotificacionVigencia').html("");
		$("#tipoNotificacionVigencia").append("<option value=''>Seleccione...</option>");
		$("#tipoNotificacionVigencia").append("<option value='anio'>Año</option>");
		$("#tipoNotificacionVigencia").append("<option value='mes'>Mes</option>");
		$("#tipoNotificacionVigencia").append("<option value='dia'>Día</option>");
	}else if($('#valorNotificacionVigencia').val() > 1){
		$('#tipoNotificacionVigencia').html("");
		$("#tipoNotificacionVigencia").append("<option value=''>Seleccione...</option>");
		$("#tipoNotificacionVigencia").append("<option value='anio'>Años</option>");
		$("#tipoNotificacionVigencia").append("<option value='mes'>Meses</option>");
		$("#tipoNotificacionVigencia").append("<option value='dia'>Días</option>");
	}
	
});


$("#tipoProducto").change(function(){	

	subTipo = '<option value="">Seleccione....</option>';
	for(var i=0; i<array_comboSubTipoProducto.length; i++){
	    if (array_comboSubTipoProducto[i]['idTipoProducto'] == $("#tipoProducto").val()){
	    	subTipo += '<option value="'+array_comboSubTipoProducto[i]['idSubtipoProducto']+'">'+array_comboSubTipoProducto[i]['nombre']+'</option>';
		    } 
    	}
    $('#subTipoProducto').html(subTipo);
});


$('#subTipoProducto').change(function(event){

	if($("#subTipoProducto").val() != ''){		
		$("#agregar").show();
		$("#productosAgregados").show();	
		$("#resultadoSubtipoProducto").show();			
		$("#nuevoVigenciaDocumento").attr('data-opcion','accionesVigenciaDocumento');
		$("#nuevoVigenciaDocumento").attr('data-destino','resultadoSubtipoProducto');
		$('#opcion').val('subtipoProducto');
		event.stopImmediatePropagation();
		abrir($("#nuevoVigenciaDocumento"),event,false);
	}else if($("#subTipoProducto").val() == '') {
		$("#productosAgregados").hide();	
		$("#resultadoSubtipoProducto").hide();	
	}
});


$("#area").change(function(event){
	$("#productosAgregados").hide();	
	$("#resultadoSubtipoProducto").hide();	
	$("#tipoProducto").val("");	
	$("#subTipoProducto").val("");		
	$("#nuevoVigenciaDocumento").attr('data-opcion','accionesVigenciaDocumento');
	$("#nuevoVigenciaDocumento").attr('data-destino','resultadoArea');
	$('#opcion').val('area');
	abrir($("#nuevoVigenciaDocumento"),event,false);		
});


$('#actualizar').click(function(){
	$('#popup').fadeIn('slow');
	$('.popup-overlay').fadeIn('slow');
	$('.popup-overlay').height($(window).height());
	return false;
});


$('#close').click(function(){
    $('#popup').fadeOut('slow');
    $('.popup-overlay').fadeOut('slow');
    return false;
});


$("#nuevoVigenciaDocumento").submit(function(event){
	event.preventDefault();
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;	

	if ($("#nombreVigencia").val() == "") {
		$("#nombreVigencia").addClass("alertaCombo");
		error = true;
	}

	if ($("#etapaVigencia").val() == "") {
		$("#etapaVigencia").addClass("alertaCombo");
		error = true;
	}
	
	if ($("#tipoDocumento").val() == "") {
		$("#tipoDocumento").addClass("alertaCombo");
		error = true;
	} 

	if ($("#area").val() == "") {
		$("#area").addClass("alertaCombo");
		error = true;
	} 

	if ($("#tipoOperacion").val() == "") {
		$("#tipoOperacion").addClass("alertaCombo");
		error = true;
	} 
	
	if (!error){

		var datosVigencia = $("#nombreVigencia").val()+'-'+$("#tipoOperacion").val();
		
		var data ="opcionCargar="+'datosAnteriores'+'&datosVigencia='+datosVigencia;
	    $.ajax({        
	        type: "POST",
	        data: data,        
	        url: "aplicaciones/administracionVigenciaDocumentos/cargarDatosAnteriores.php",
	        success: function(data) {   
	        	$("#datosVigencia").html(data);
	        }
	    });
		
	    $('#popup').fadeOut('slow');
	    $('.popup-overlay').fadeOut('slow');

		$('#nuevoVigenciaDocumento').attr('data-opcion','modificarVigenciaDocumento');  
		ejecutarJson($(this)); 
	}else{
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}
	
 });

$("#cTemporal").click(function(e){
	if($('#cTemporal').is(':checked')){			
		$('.productoActivar').prop('checked', true);
	}else{
		$('.productoActivar').prop('checked', false);
	}
});


$(".productoActivar").click(function(e){
	if(!$('#productoActivar').is(':checked')){
		$('#cTemporal').prop('checked', false);
	}
});


function verificarInputsDeclararVigencia() {

	this.ejecutar = function () {
        var error = false;
        $(".alertaCombo").removeClass("alertaCombo");

        if ($("#valorTiempoVigencia").val() == "") {
            $("#valorTiempoVigencia").addClass("alertaCombo");
            error = true;
        }
        if ($("#tipoTiempoVigencia").val() == "") {
            $("#tipoTiempoVigencia").addClass("alertaCombo");
            error = true;
        }
        if ($("#valorNotificacionVigencia").val() == "") {
            $("#valorNotificacionVigencia").addClass("alertaCombo");
            error = true;
        }
        if ($("#tipoNotificacionVigencia").val() == "") {
            $("#tipoNotificacionVigencia").addClass("alertaCombo");
            error = true;
        }
        if ($("#observacionVigencia").val() == "") {
            $("#observacionVigencia").addClass("alertaCombo");
            error = true;
        }
                      
        return !error;

    };

	this.mensajeError = function () {			
		mostrarMensaje("Revise los datos del formulario", "FALLO");			
	}
}

function exitoDeclararVigencia() {
    this.ejecutar = function (msg) {
        mostrarMensaje("Nuevo registro agregado", "EXITO");
        var fila = msg.mensaje;
        $("#valorTiempoVigencia").val("");
        $("#tipoTiempoVigencia").val("");
        $("#valorNotificacionVigencia").val("");
        $("#tipoNotificacionVigencia").val("");
        $("#observacionVigencia").val("");
        $("#codigoDeclararVigencia").append(fila);
    };
}

</script>
