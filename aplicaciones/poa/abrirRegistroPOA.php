<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';
$conexion = new Conexion();
$cpoa1 = new ControladorPAPP();

$datosProceso = $cpoa1->obtenerDatosPOA($conexion, $_POST['id']);
$fila = pg_fetch_assoc($datosProceso);


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header>
		<h1>Actualizar Registro Proforma</h1>
	</header>
	<!-- form id="actualizarItemPOA" data-rutaAplicacion="poa"
		data-opcion="actualizarPlantaPOA" data-destino="detalleItem"
		data-accionEnExito="ACTUALIZAR"-->
		<div id="estado"></div>
		<input type="hidden" name="idItem" value="<?php echo $_POST['id'];?>"/>

		<fieldset>

			<legend>Objetivo Estratégico</legend>
			<div data-linea="1">
				<?php echo $fila['objetivo'];?>
			</div>
		</fieldset>

		<fieldset>

			<legend>Procesos/Proyectos</legend>
			<div data-linea="1">
				<?php echo $fila['proceso'];?>
			</div>


		</fieldset>

		<fieldset>

			<legend>Subprocesos</legend>
			<div data-linea="1">
				<?php echo $fila['subproceso'];?>
			</div>

		</fieldset>
		<!-- fieldset>

			<legend>Objetivo operativo</legend>
			<div data-linea="1">
				< ?php echo $fila['componente'];?>
			</div>


		</fieldset-->


		<fieldset>

			<legend>Proyectos y Actividades</legend>
			<div data-linea="1">
				<?php echo $fila['actividad'];?>
			</div>
			<div data-linea="2">
				<?php echo $fila['detalle_actividad'];?>
			</div>


		</fieldset>

		<!-- fieldset>

			<legend>Indicadores</legend>
			<div data-linea="1">
				< ?php echo $fila['indicador'];?>
			</div>

			<div data-linea="2">
				<label>Línea Base: </label>< ?php echo $fila['linea_base'];?>
			</div>
			
			<div data-linea="3">
				<label>Método de Cálculo: </label>< ?php echo $fila['metodo_calculo'];?>
			</div>
		</fieldset>

		<fieldset>
			<legend>Programación de Metas Trimestral</legend>
			<table>
				<tr>
					<td><label>Trimestre I: </label></td>
					<td><input type="text" id="meta1" type="text" name="meta1" placeholder="0" data-er="^[0-9]+$"
						value="< ?php echo $fila['meta1'];?>" < ?php
		if($fila['estado']>=2 )
			echo 'disabled="disabled"'; ?> ></td>
				</tr>
				<tr>
					<td><label>Trimestre II: </label></td>
					<td><input class="numeric" id="meta2" type="text" name="meta2" placeholder="0" data-er="^[0-9]+$"
						value="< ?php echo $fila['meta2'];?>" < ?php
		if($fila['estado']>=2 )
			echo 'disabled="disabled"'; ?> /></td>
				</tr>
				<tr>
					<td><label>Trimestre III: </label></td>
					<td><input class="numeric" id="meta3" type="text" name="meta3" placeholder="0" data-er="^[0-9]+$"
						 value="< ?php echo $fila['meta3'];?>" < ?php
		if($fila['estado']>=2 )
			echo 'disabled="disabled"'; ?> /></td>
				</tr>
				<tr>
					<td><label>Trimestre IV: </label></td>
					<td><input class="numeric" id="meta4" type="text" name="meta4" placeholder="0" data-er="^[0-9]+$"
						 value="< ?php echo $fila['meta4'];?>" < ?php
		if($fila['estado']>=2 )
			echo 'disabled="disabled"'; ?> /></td>
				</tr>
				<tr>
					<td><label>Meta de los Proyectos: </label></td>
					<td><input class="numeric" id="total" type="text" name="total"
						disabled="disabled"
						value="< ?php 
			$total=$fila['meta1']+$fila['meta2']+$fila['meta3']+$fila['meta4'];
			echo $total;?>"></td>
				</tr>
			</table>

		</fieldset-->
		
		<fieldset>
			<legend>Observaciones</legend>
				<div data-linea="1">
					<input type="text" disabled="disabled" value="<?php echo $fila['observaciones']; ?>" />
				</div>
		</fieldset>


		<!-- button type="submit" class="guardar" < ?php
		if($fila['estado']>=2 )
			echo 'disabled="disabled"'; ?>>Actualizar</button>

	</form-->
</body>


<script type="text/javascript">
var linea_base= <?php echo json_encode($fila['linea_base']); ?>;
    
	/*$("#actualizarItemPOA").submit(function(event){
		event.preventDefault();
		chequearCampos(this);
	});*/

	$(document).ready(function(){
		distribuirLineas();
		construirValidador();
		
		/*var acum=0;
		
		  $("input").focus(function(){
		    $(this).css("background-color","#cccccc");
		  });
		  $("input").blur(function(){
		    $(this).css("background-color","#ffffff");
		    acum=Number($("#meta1").val())+Number($("#meta2").val())+Number($("#meta3").val())+Number($("#meta4").val());

		    if( acum > linea_base){
		    	$("#total").css("background-color","#30C951");
		    }else if( acum < linea_base){
		    	$("#total").css("background-color","#C4313D");
		    }else if( acum == linea_base){
		    	$("#total").css("background-color","#F2EF38");
		    }

		    $("#total").val(acum);
		       
		   });	*/

		});

	/*function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#meta1").val()) || !esCampoValido("#meta1")){
			error = true;
			$("#meta1").addClass("alertaCombo");
		}

		if(!$.trim($("#meta2").val()) || !esCampoValido("#meta2")){
			error = true;
			$("#meta2").addClass("alertaCombo");
		}

		if(!$.trim($("#meta3").val()) || !esCampoValido("#meta3")){
			error = true;
			$("#meta3").addClass("alertaCombo");
		}

		if(!$.trim($("#meta4").val()) || !esCampoValido("#meta4")){
			error = true;
			$("#meta4").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}*/
	
	
</script>
