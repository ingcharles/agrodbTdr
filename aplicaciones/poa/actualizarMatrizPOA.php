<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$fecha = getdate();

$conexion = new Conexion();
$cpoa1 = new ControladorPAPP();

$datos=explode( '_', $_POST['id'] );
$id_item=$datos[0];
$estado=$datos[1];

$datosProceso = $cpoa1->obtenerDatosPOA($conexion, $id_item);
$fila = pg_fetch_assoc($datosProceso);

$datosArea = $cpoa1->obtenerNombreArea($conexion, $_SESSION['usuario']);
$fila2 = pg_fetch_assoc($datosArea);

$datosPresupuesto = $cpoa1->obtenerPresupuestoTrimestral($conexion, $id_item);
$fila3 = pg_fetch_assoc($datosPresupuesto);

if($estado==2){
	$datosMatriz = $cpoa1->obtenerDatosMatriz($conexion, $id_item);
	$fila4 = pg_fetch_assoc($datosMatriz);
}


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<header>
		<h1>Registro Matriz PAPP</h1>
	</header>
	<form id="nuevoItemPOA" data-rutaAplicacion="poa" data-opcion="guardarMatrizPOA" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR"> <!-- #ventanaAplicacion #filtrar -->
	    
	    <div id="estado"></div>
		
		<input type="hidden" name="idItem" value="<?php echo $id_item;?>"/>
		<input type="hidden" name="estructura" value="<?php echo $fila2['nombre'];?>"/>

		<!--fieldset>
			<legend>Área</legend>
				<div data-linea="1">
					<label>< ?php echo $fila2['nombre'];?></label>
				</div>
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
					<label>Línea Base: </label>
					< ?php echo $fila['linea_base'];?>
				</div>
				<div data-linea="8">
					<label>Método de Cálculo: </label>
					< ?php echo $fila['metodo_calculo'];?>
				</div>
				<div data-linea="9">
					<label>Meta de las actividades: </label>
					< ?php 
					$total=$fila['meta1']+$fila['meta2']+$fila['meta3']+$fila['meta4'];
		
					echo $total;?>
				</div-->
		</fieldset>

		
		<!-- fieldset>
			<legend>Programación Trimestral</legend>
			<table>
				<tr>
					<td><label>Trimestre I: </label></td>
					<td><input class="numeric" id="meta1" type="text" size="5"
						name="meta1" disabled="disabled"
						value="< ?php echo $fila['meta1'];?>"></td>
				</tr>
				<tr>
					<td><label>Trimestre II: </label></td>
					<td><input class="numeric" id="meta2" type="text" name="meta2"
						disabled="disabled" value="< ?php echo $fila['meta2'];?>" /></td>
				</tr>
				<tr>
					<td><label>Trimestre III: </label></td>
					<td><input class="numeric" id="meta3" type="text" name="meta3"
						disabled="disabled" value="< ?php echo $fila['meta3'];?>" /></td>
				</tr>
				<tr>
					<td><label>Trimestre IV: </label></td>
					<td><input class="numeric" id="meta4" type="text" name="meta4"
						disabled="disabled" value="< ?php echo $fila['meta4'];?>" /></td>
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
			<legend>Programación de gasto</legend>
			<table>
				<tr>
					<td><label>Trimestre I: </label></td>
					<td><input class="numeric" id="progra_1" type="text" readonly="readonly"
						name="progra_1" value="<?php echo $fila3['trim1'];?>" /></td>
				</tr>
				<tr>
					<td><label>Trimestre II: </label></td>
					<td><input class="numeric" id="progra_2" type="text" readonly="readonly"
						name="progra_2" value="<?php echo $fila3['trim2'];?>" /></td>
				</tr>
				<tr>
					<td><label>Trimestre III: </label></td>
					<td><input class="numeric" id="progra_3" type="text" readonly="readonly"
						name="progra_3" value="<?php echo $fila3['trim3'];?>" /></td>
				</tr>
				<tr>
					<td><label>Trimestre IV: </label></td>
					<td><input class="numeric" id="progra_4" type="text" readonly="readonly"
						name="progra_4" value="<?php echo $fila3['trim4'];?>" /></td>
				</tr>
				<tr>
					<td><label>Presupuesto: </label></td>
					<td><input class="numeric" id="totalPresupuesto" type="text"
						name="totalPresupuesto" disabled="disabled" value="<?php echo ($fila3['trim1']+$fila3['trim2']+$fila3['trim3']+$fila3['trim4']);?>" /></td>
				</tr>

			</table>
		</fieldset>
		
		<fieldset>
			<legend>Cobertura Territorial</legend>
				<div data-linea="1">
					<label>Nivel de cobertura: </label>
					<select id="coberturaTerritorial" name="coberturaTerritorial">
						<option value="" >Seleccione...</option>
						<option value="NACIONAL" >NACIONAL</option>
						<option value="PROVINCIAL">PROVINCIAL</option>
						<option value="CANTONAL">CANTONAL</option>
						<option value="PARROQUIAL">PARROQUIAL</option>
						<option value="ZONAL">ZONAL</option>
					</select>
					<input id="territorio" type="hidden" name="territorio" value="<?php echo $fila4['cobertura'];?>" />
				</div>
		</fieldset>

		<fieldset>
			<legend>Cobertura Social</legend>
				<div data-linea="1">
					<label>Número de beneficiados: </label> 
						<input class="numeric"	id="beneficiados" type="text" name="beneficiados" 
							<?php 
								if($estado==2)
									echo "disabled='disabled'"; 
							?> 
							value="<?php echo $fila4['numero_beneficiados'];?>" placeholder="0" data-er="^[0-9]+$"/> 
				</div>
				
				<div data-linea="2">			
					<label>Descripción de la población objetivo: </label>
						<input type="text" id="descripcionPoblacion" name="descripcionPoblacion" <?php if($estado==2)
						echo "disabled='disabled'";  ?> value="<?php echo $fila4['descripcion_poblacion_objetivo'];?>" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
				</div>
				
				<div data-linea="3">
					<label>Responsable: </label>
					
					<?php 
						if($estado!=2){
							echo '<select id="responsable" name="responsable">
										<option value="" >Seleccione...</option>';
								
								if($fila2['id_area_padre'] == 'DE'){
									$area = $fila2['id_area'];
								}else{
									$area = $fila2['id_area_padre'];
								}
								
								$responsable = $cpoa1->listarNombreResponsable($conexion, $area);
																
								while($fila5 = pg_fetch_assoc($responsable)){
					              echo '<option value="' . $fila5['identificador'] . '">' . $fila5['nombre_apellido'] .'</option>';	
								}
							echo '</select>';
						}else{
		             		echo '<input id="nombreResponsable" name="nombreResponsable" disabled="disabled" value="'.$fila4['responsable_subproceso'].'" />';
						}
					?>
					<input type="hidden" id="responsableProceso" name="responsableProceso" value=""/>
				</div>	
			
				<div data-linea="4">
					<label>Medios de Verificación: </label>		
						<input type="text" id="mediosVerificacion" name="mediosVerificacion" <?php if($estado==2)
						echo "disabled='disabled'"; ?> value="<?php echo $fila4['medios_verificacion'];?>" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
				
		</fieldset>
        
		<button type="submit" class="guardar" <?php if($estado==2)
			echo "disabled='disabled'"; ?>>Registrar</button>

	</form>
</body>


<script type="text/javascript">

    
	$("#nuevoItemPOA").submit(function(event){
		event.preventDefault();
		chequearCampos(this);
	});

	$(document).ready(function(){
		distribuirLineas();	
		construirValidador();
		
		var acum=0;
	    
		  $("input").focus(function(){
		    $(this).css("background-color","#cccccc");
		  });
		  $("input").blur(function(){
		    $(this).css("background-color","#ffffff");		    	       
		   });	
		  acum=Number($("#progra_1").val())+Number($("#progra_2").val())+Number($("#progra_3").val())+Number($("#progra_4").val());
		  $("#totalPresupuesto").val(acum);
		  $("#coberturaTerritorial").val($("#territorio").val());
		  $("#responsable").val($("#nombreResponsable").val());
		  	
	});

	$("#responsable").change(function() {
		var mitexto = $("#responsable option:selected").text();
		$("#responsableProceso").val(mitexto);
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#coberturaTerritorial").val()) || !esCampoValido("#coberturaTerritorial")){
			error = true;
			$("#coberturaTerritorial").addClass("alertaCombo");
		}
		
		if(!$.trim($("#beneficiados").val()) || !esCampoValido("#beneficiados")){
			error = true;
			$("#beneficiados").addClass("alertaCombo");
		}
		
		if(!$.trim($("#descripcionPoblacion").val()) || !esCampoValido("#descripcionPoblacion")){
			error = true;
			$("#descripcionPoblacion").addClass("alertaCombo");
		}
		
		if(!$.trim($("#responsable").val()) || !esCampoValido("#responsable")){
			error = true;
			$("#responsable").addClass("alertaCombo");
		}

		if(!$.trim($("#mediosVerificacion").val()) || !esCampoValido("#mediosVerificacion")){
			error = true;
			$("#mediosVerificacion").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
			$("#_actualizar").click();	
		}
	}
</script>