<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$ce = new ControladorMercanciasSinValorComercial();

$opcion= htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

$codigoTipoProducto = htmlspecialchars ($_POST['tipoProducto'],ENT_NOQUOTES,'UTF-8');
$codigoSubTipoProducto = htmlspecialchars ($_POST['subTipoProducto'],ENT_NOQUOTES,'UTF-8');

$identificacion = $_POST['idNuevo'];


switch ($opcion){
	case 'subtipo':
		$subTipoProducto = $cc->listarSubTipoProductoXtipoProducto($conexion, $codigoTipoProducto);
			
		echo '<label for="subTipoProducto">* Subtipo:</label>
			  <select id="subTipoProducto" name="subTipoProducto">
			  	<option value="">Seleccione</option>';
		while ($fila = pg_fetch_assoc($subTipoProducto)){
			echo '<option value="'.$fila['id_subtipo_producto'].'">'.$fila['nombre'].'</option>';
		}
		echo '</select><input type="hidden" id="nombreSubTipoProducto" name="nombreSubTipoProducto">';
	break;

	case 'producto':
		$producto = $cc->listarProductoXsubTipoProducto($conexion, $codigoSubTipoProducto);
		
		echo '<label for="producto">* Producto:</label>
			  <select id="producto" name="producto">
			  	<option value="">Seleccione</option>';
		while ($fila = pg_fetch_assoc($producto)){
			echo '<option value="'.$fila['id_producto'].'">'.$fila['nombre_comun'].'</option>';
		}
		echo '</select><input type="hidden" id="nombreProducto" name="nombreProducto">';
	break;
	
	case 'verificarRegistro':
		$ce->comprobarCodigo($conexion,$identificacion);
	break;
}

?>

<script type="text/javascript">

$("document").ready(function(){
	distribuirLineas();
});

$("#subTipoProducto").change(function(event){
	$("#datosProducto .alertaCombo").removeClass("alertaCombo");
	event.stopImmediatePropagation();

	if($("#subTipoProducto").val()!=""){
		$("#estado").html("");
		$("#nuevoRegistro").attr('data-destino', 'resultadoProducto');
	    $("#nuevoRegistro").attr('data-opcion', 'comboExportacion');
		$("#opcion").val("producto");
		$("#nombreSubTipoProducto").val($("#subTipoProducto option:selected").text());
		abrir($("#nuevoRegistro"), event, false);
	} 
});

$("#producto").change(function(event){
	$("#nuevoRegistro").attr('data-opcion', 'guardarNuevoProducto');
	$("#nuevoRegistro").removeAttr('data-destino');
	$("#nombreProducto").val($("#producto option:selected").text());
});

function agregar(){

	$("#datosProducto .alertaCombo").removeClass("alertaCombo");
	var error = false;

	var idTipoPorducto=$("#tipoProducto").val();
    var nTipoPorducto=$("#tipoProducto option:selected").text();
    var idSubtipoPorducto=$("#subTipoProducto").val();
    var nSubtipoPorducto=$("#subTipoProducto option:selected").text();
    var idProducto=$("#producto").val();
    var nProducto=$("#producto option:selected").text(); 
    var sexo=$("#sexo").val();
    var raza=$("#raza").val();
    var edad=$("#edad").val();
    var color=$("#color").val();
    var identicacionProducto=$("#identificacionProducto").val();
    var identificadorProductoReplace = $("#identificacionProducto").val().replace("/","_");

	if($("#subTipoProducto").val()==""){
		error=true;
		$("#subTipoProducto").addClass("alertaCombo");
	}

	if($("#producto").val()==""){
		error=true;
		$("#producto").addClass("alertaCombo");
	}

	if($("#sexo").val()==""){
		error=true;
		$("#sexo").addClass("alertaCombo");
	}

	if($("#raza").val()==""){
		error=true;
		$("#raza").addClass("alertaCombo");
	}

	if($("#edad").val()==""){
		error=true;
		$("#edad").addClass("alertaCombo");
	}

	if($("#color").val()==""){
		error=true;
		$("#color").addClass("alertaCombo");
	}

	if($("#identificacionProducto").val()==""){
		error=true;
		$("#identificacionProducto").addClass("alertaCombo");
	}

	if(!error){
		if($("#bodyTablaProductos #r_"+idProducto+identificadorProductoReplace).length==0){
				var cadena= '<tr id="r_'+idProducto+identificadorProductoReplace+'"><td><input type="hidden" name="didTipoProducto[]" value="'+idTipoPorducto+'" style="width:30px">'+
				'<input type="hidden" name="dnTipoProducto[]" value="'+nTipoPorducto+'" style="width:30px">'+
				'<input type="hidden" name="didSubtipoProducto[]" value="'+idSubtipoPorducto+'" style="width:30px">'+
				'<input type="hidden" name="dnSubtipoProducto[]" value="'+nSubtipoPorducto+'" style="width:30px">'+
				'<input type="hidden" id="didProducto" name="didProducto[]" value="'+idProducto+'" style="width:30px">'+
				'<input type="hidden" name="dnProducto[]" value="'+nProducto+'" style="width:30px">'+
				'<input type="hidden" name="dsexo[]" value="'+sexo+'" style="width:30px">'+
				'<input type="hidden" name="draza[]" value="'+raza+'" style="width:30px">'+
				'<input type="hidden" name="dedad[]" value="'+edad+'" style="width:30px">'+
				'<input type="hidden" name="dcolor[]" value="'+color+'" style="width:30px">'+
				'<input type="hidden" id="didentificacionProducto" name="didentificacionProducto[]" value="'+identicacionProducto+'" style="width:30px">'
				+nTipoPorducto+'</td>'+
				'<td>'+nProducto+'</td><td>'+identicacionProducto+'</td>'+
				'<td class="borrar"><button class="icono" onClick="delFilaActual(this);return false"></td></tr>';
			$("#bodyTablaProductos").append(cadena);
			$("#estado").html("");
			limpiarMascota();
		}else{
			$("#estado").html("No es posible agregar un mismo producto con el mismo identificador.").addClass("alerta");
		}
	}else{
		$("#estado").html("Por favor verificar los campos obligatorios.").addClass("alerta");
	}
}

function delFilaActual(r){
	var i = r.parentNode.parentNode.rowIndex;
    var table = document.getElementById('tablaProductos');
    table.deleteRow(i);

    var rowCount = table.rows.length;

	if(rowCount == 1){
 		$("#estado").html("");
 		$("#valorTipo").val("");
 		$(".alertaCombo").removeClass("alertaCombo");
	}
}
</script>