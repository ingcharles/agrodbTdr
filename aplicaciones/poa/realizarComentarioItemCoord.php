<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';
$conexion = new Conexion();
$cpoa1 = new ControladorPAPP();

$datos=explode( '_', $_POST['id'] );
$id_item=$datos[0];
$estado=$datos[1];

$datos = $cpoa1->obtenerDatosPOA($conexion, $id_item);
$fila = pg_fetch_assoc($datos);

$datosProvincia = $cpoa1->obtenerNombreArea($conexion, $_SESSION['usuario']);
$fila2 = pg_fetch_assoc($datosProvincia);

if($fila['observaciones']!=null){
	$observacion = $fila['observaciones'];
}else{
	$observacion = '';
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Revisar Item Proforma</h1>
	</header>

	<div id="estado"></div>
	
	<form id="devolverPOARevisar" data-rutaAplicacion="poa" data-opcion="enviarPOARevisar" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR"> <!--  data-accionEnExito="#ventanaAplicacion #filtrar" -->
		 
		 <input type="hidden" name="idPlanta" value="<?php echo $id_item;?>"/>
		
		<!-- fieldset>
			<legend>Estructura/Area:</legend>
			<label>< ?php echo $fila2['nombre'];?> </label>
		</fieldset-->
	
		<fieldset>
			<legend>Información</legend>
			<div data-linea="1">
				<label>Objetivo: </label>
				<?php echo $fila['objetivo'];?>
			</div>
			<div data-linea="2">
				<label>Proceso: </label>
				<?php echo $fila['proceso'];?>
			</div>
			<div data-linea="3">
				<label>Subproceso: </label>
				<?php echo $fila['subproceso'];?>
			</div>
			<!-- div data-linea="4">
				<label>Objetivo operativo: </label>
				< ?php echo $fila['componente'];?>
			</div-->
			<div data-linea="5">
				<label>Actividad: </label>
				<?php echo $fila['actividad'];?>
			</div>
			<!-- div data-linea="6">
				<label>Indicador: </label>
				< ?php echo $fila['indicador'];?>
			</div>
			<div data-linea="7">
				<label>Meta Trimestre 1: </label>
				< ?php echo $fila['meta1'];?>
			</div>
			<div data-linea="7">
				<label>Meta Trimestre 2: </label>
				< ?php echo $fila['meta2'];?>
			</div>
			<div data-linea="8">
				<label>Meta Trimestre 3: </label>
				< ?php echo $fila['meta3'];?>
			</div>
			<div data-linea="8">
				<label>Meta Trimestre 4: </label>
				< ?php echo $fila['meta4'];?>
			</div>
			<div data-linea="9" id="total">
				<label>Meta de las actividades: </label>
				< ?php 
				$total=$fila['meta1']+$fila['meta2']+$fila['meta3']+$fila['meta4'];
	
				echo $total;?>
			</div>
			
			<div data-linea="9" id="lineaBase">
				<label>Línea Base: </label>
				< ?php echo $fila['linea_base'];?>
			</div>
			
			<div data-linea="10" id="metodoCalculo">
				<label>Método de Cálculo: </label>
				< ?php echo $fila['metodo_calculo'];?>
			</div-->
		</fieldset>


		<fieldset id="fs_detalle">
			<legend>Observaciones</legend>
				<div data-linea="7" id="ingresoObservacion">
					<input type="text" id="observacion" name="observacion" <?php if($estado==4) echo "disabled='disabled'"; ?> />
				</div>
				
				<div data-linea="8" id="detalleObservacion">
					<?php echo $fila['observaciones']; ?>
				</div>
		</fieldset>
		
		<button id="botonEnviarObservacion" type="submit" class="guardar" <?php if($estado==4) echo "disabled='disabled'"; ?>>Devolver Proforma</button>

	</form>

</body>

<script type="text/javascript">
var linea_base= <?php echo json_encode($fila['linea_base']); ?>;
//var acum = < ?php echo json_encode($fila['meta1']+$fila['meta2']+$fila['meta3']+$fila['meta4']); ?>;
var observacion= <?php echo json_encode($observacion); ?>;
var estado= <?php echo json_encode($fila['estado']); ?>;

$(document).ready(function(){
	distribuirLineas();	
	construirValidador();

	/*if( acum > linea_base){
    	$("#lineaBase").addClass('exito');
    	$("#metodoCalculo").addClass('exito');
    	$("#total").addClass('exito');
    }else if( acum < linea_base){
    	$("#lineaBase").addClass('alerta');
    	$("#metodoCalculo").addClass('alerta');
    	$("#total").addClass('alerta');
    }else if( acum == linea_base){
    	$("#lineaBase").addClass('advertencia');
    	$("#metodoCalculo").addClass('advertencia');
    	$("#total").addClass('advertencia');
    }*/

    if(observacion != ''){
    	$("#ingresoObservacion").hide();
    	$("#botonEnviarObservacion").hide();

    	$("#detalleObservacion").show();
    }else{
    	$("#ingresoObservacion").show();
    	$("#botonEnviarObservacion").show();

    	$("#detalleObservacion").hide();
    }

    if(estado == 1){
    	$("#ingresoObservacion").hide();
    	$("#botonEnviarObservacion").hide();

    	$("#fs_detalle").hide();
    }
});
	   
$("#devolverPOARevisar").submit(function(event){
	event.preventDefault();
	chequearCampos(this);
/*	if(($("#estado p").html())=="La actividad ha sido actualizado satisfactoriamente")
		$("#presupuestos").append("<tr id='t_"+< ?php echo $id_item;?>+"_"+$("textarea#codigoItem").val().replace(/ /g,'')+"'><td><form id='f_"+< ?php echo $id_item;?>+"_"+$("textarea#codigoItem").val().replace(/ /g,'')+"' data-rutaAplicacion='poa' data-opcion='quitarItemPresupuesto'><button type='submit' class='menos'>Quitar</button>"+$("textarea#codigoItem").val()+" "+$("textarea#detalle_gasto").val()+" "+$("#total").val()+"<input name='id_item_planta' value='"+< ?php echo $id_item;?>+"' type='hidden'><input name='codigo_item' value='"+$("textarea#codigoItem").val()+"' type='hidden'></form></td></tr>");*/	
});

function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}

function chequearCampos(form){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if(!$.trim($("#observacion").val()) || !esCampoValido("#observacion")){
		error = true;
		$("#observacion").addClass("alertaCombo");
	}
	
	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{
		ejecutarJson(form);
		$("#_actualizar").click();			
	}
}
</script>