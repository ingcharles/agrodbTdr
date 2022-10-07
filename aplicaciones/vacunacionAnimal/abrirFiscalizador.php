<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$contador = 0;
$itemsFiltro[] = array();

$conexion = new Conexion();
$vc = new ControladorVacunacionAnimal();
$idVacunaAnimal=$_POST['id'];
$qvacuna = $vc->listaVacunacionAnimalFiltro($conexion, $idVacunaAnimal);
$vacuna = pg_fetch_assoc($qvacuna);

$qDetalleVacuna = $vc-> listaFiltroDetalleVacunacion($conexion,$idVacunaAnimal);
$qarete = $vc->listaDetalleArete ($conexion, $idVacunaAnimal);

$controlFiscalizacion = 0;
$Control = $vc-> buscarFiscalizacion($conexion, $_POST['id']);
if(pg_num_rows($Control)!=0){
	$controlFiscalizacion = 1;
	$fiscalizacion = pg_fetch_assoc($Control);
}

?>
<style type="text/css">
#tablaVacunaAnimal td, #tablaVacunaAnimal th 
{
font-size:1em;
border:1px solid rgba(0,0,0,.1);
padding:3px 7px 2px 7px;
}
</style>
<header>
	<h1>Resgistro de fiscalización</h1>
</header>
<form id='nuevoFiscalizacion' data-rutaAplicacion='vacunacionAnimal' data-opcion='guardarNuevoFiscalizador' data-accionEnExito="ACTUALIZAR">
<input type="hidden" name="id_vacuna_animal" value="<?php echo $_POST['id'];?>" />	
<input type="hidden" id="usuario_responsable" name="usuario_responsable" value="<?php echo $_SESSION['usuario'];?>" />  


<div id='fiscalizacion'>
    <fieldset>
		<legend>Información </legend>
			<div data-linea="1">
				<label>N° Certificado</label>	
				<input type="text" id="nombreEspecieValorada" name="nombreEspecieValorada" value="<?php echo $vacuna['numero_certificado'];?>" disabled="disabled"/>													  
			</div>	
			<div data-linea="2">
				<label>Operador vacunador</label> 
				<input type="text" id="nombreOperadorVacunador" name="nombreOperadorVacunador" value="<?php echo $vacuna['nombre_administrador'];?>" disabled="disabled"/>
			</div>
			<div data-linea="3">
			<label>Provincia distribución</label> 
				<input type="text" id="provincia_distribucion" name="provincia_distribucion" value="<?php echo $vacuna['provincia_distribuidor'];?>" disabled="disabled"/>
			</div>			
			<div data-linea="3">
			    <label>Punto de distribución</label> 
				<input type="text" id="pto_distribucion" name="pto_distribucion" value="<?php echo $vacuna['nombre_distribuidor'];?>" disabled="disabled"/>
			</div>			
			<div data-linea="4">
			    <label>Laboratorio</label> 
				<input type="text" id="laboratorio" name="laboratorio" value="<?php echo $vacuna['nombre_laboratorio'];?>" disabled="disabled"/>
			</div>
			<div data-linea="4">
				<label>Lote</label> 
				<input type="text" id="lote" name="lote" value="<?php echo $vacuna['numero_lote'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="5">
			    <label>Identificador</label> 
				<input type="text" id="identificador_vacunador" name="identificador_vacunador" value="<?php echo $vacuna['identificador_vacunador'];?>" disabled="disabled"/>
			</div>
			<div data-linea="5">
				<label>Nombre del vacunador</label> 
				<input type="text" id="nombre_vacunador" name="nombre_vacunador" value="<?php echo $vacuna['nombre_vacunador'];?>" disabled="disabled"/>			
			</div>					
			<div data-linea="6">
			    <label>F.Vacunación</label> 
				<input type="text" id="fecha_vacunacion" name="fecha_vacunacion" value="<?php echo $vacuna['fecha_vacunacion'];?>" disabled="disabled"/>
			</div>
			<div data-linea="6">
				<label>F.Vencimiento</label> 
				<input type="text" id="fecha_vencimiento" name="fecha_vencimiento" value="<?php echo $vacuna['fecha_vencimiento'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="7">
			    <label>Nombre del sitio</label> 
				<input type="text" id="nombre_sitio" name="nombre_sitio" value="<?php echo $vacuna['nombre_sitio'];?>" disabled="disabled"/>
			</div>
			<div data-linea="7">
				<label>F.Registro</label> 
				<input type="text" id="fecha_registro" name="fecha_registro" value="<?php echo $vacuna['fecha_vencimiento'];?>" disabled="disabled"/>			
			</div>	
			<div data-linea="8">
				<label>Estado certificado</label> 
				<input type="text" id="estado_certificado" name="estado_certificado" value="<?php echo $vacuna['estado_vacunacion'];?>" disabled="disabled"/>			
			</div>
							
	</fieldset>
	<fieldset>
		<legend>Detalle animales</legend>
		<table id="tablaVacunaAnimal">
				<thead>
					<tr>
						<th>#</th>
						<th>Animal</th>
						<th>Existentes</th>
						<th>Vacunados</th>									
						<th>Observación</th>					
					</tr>
				</thead>
					<?php 
					$i=1;
					foreach ($qDetalleVacuna as $detalleVacuna){
						echo  '<tr>
		                           <td>'.$i.'</th>
								   <td>'.$detalleVacuna['nombre_producto'].' </td>
								   <td>'.$detalleVacuna['existente'].'</td>
								   <td>'.$detalleVacuna['vacunado'].'</td>
								   <td>'.$detalleVacuna['observacion'].'</td>
		                       </tr>';				
						$i++;
					}	
				    ?>				
			  </table>	
	</fieldset>
	<div id="estado"></div>	
	<fieldset>
		<legend>Detalle de las serie de aretes</legend>
	    <div data-linea="8">			   	
		    <textarea name="serie_arete1" rows="3" cols="100" disabled="disabled"><?php 
		    while($fila = pg_fetch_assoc($qarete)){
				$var1=$var1 ==''?$fila['serie']:$var1 .'; '. $fila['serie'];
			 }
			echo $var1;
		    ?></textarea>		    				
		</div>		
	</fieldset>
</div>		
<div id='fiscalizacion_vista'>	
	<fieldset>
		<legend>Información de la fiscalización </legend>		
		<div data-linea="10">
			<label>N° de fiscalización : </label>
			<input type="text" id="num_fiscalizacion" name="num_fiscalizacion" value="<?php echo $fiscalizacion['num_fiscalizacion'];?>" disabled="disabled"/>			
		</div>
		<div data-linea="11">
			<label>Fecha fiscalización : </label>
			<input type="text" id="f_fiscalizacion" name="f_fiscalizacion" value="<?php echo $fiscalizacion['fecha_fiscalizacion'];?>" disabled="disabled"/>			
		</div>
		<div data-linea="11">
			<label>Respuesta :</label>
			<input type="text" id="f_respuesta" name="f_respuesta" value="<?php echo $fiscalizacion['estado'];?>" disabled="disabled"/>						
		</div>
		<div data-linea="12">	
			<label>Observación :</label> 					
		</div>
		<div data-linea="13">			   	
		    <textarea id="f_observacion" name="f_observacion" rows="3" cols="100" disabled="disabled"><?php echo $fiscalizacion['observacion'];?></textarea>	    				
		</div>				
	</fieldset>   	
</div>
<div id='fiscalizacion_nuevo'>
	<fieldset>
		<legend>Nueva fiscalización</legend>
	    <div data-linea="10">			
			<label>Fecha fiscalización</label>				
			<input type="text" id="fecha_fiscalizacion" name="fecha_fiscalizacion" />
		</div>		
	    <table>													
		<tr>
			<td>
				<label>Respuesta</label>
			</td>									
			<td>
				<input type="radio" name="estadoFiscalizacion" value="positivo" id="estados1" >Positivo
				<input type="radio" name="estadoFiscalizacion" value="negativo"  id="estados2" >Negativo
				
			</td>		
		</tr>
	    </table>		
		<div data-linea="11">	
			<label>Observación :</label> 					
		</div>
		<div data-linea="12">			   	
		    <textarea id="observacion" name="observacion" placeholder="Ej: Observacion" rows="3" cols="100" maxlength="256"></textarea>		   	    			
		</div>				
	</fieldset>   	
		<button id="btn_guardar" type="submit" name="btn_guardar" >Guardar fiscalizador</button>		
</div>
</form>		
<script type="text/javascript">
		
	var ControlFiscalizacion = <?php echo json_encode($controlFiscalizacion); ?>;
	
	$(document).ready(function(){
		distribuirLineas();
	
		if(ControlFiscalizacion == 1){
			$("#fiscalizacion_vista").show();
			$("#fiscalizacion_nuevo").hide();			
		}else{
			$("#fiscalizacion_vista").hide();
			$("#fiscalizacion_nuevo").show();			
		}
			
		$("#fecha_fiscalizacion").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
				
	});
	
	//Para guardar la fiscalización
	$("#nuevoFiscalizacion").submit(function(event){
	  	event.preventDefault();
	 	chequearOperadorVacunacion(this);				 	
	});




	function chequearOperadorVacunacion(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("input:radio[name=estadoFiscalizacion]:checked").val() == null){
			error = true;
			
			$("#estados1").addClass("alertaCombo");
			$("#estados2").addClass("alertaCombo");
			
		}
		
		if($("#fecha_fiscalizacion").val()==0){
			error = true;
			$("#fecha_fiscalizacion").addClass("alertaCombo");
		}

		
		

		if($("#observacion").val()==0){
			error = true;
			$("#observacion").addClass("alertaCombo");
		}
		
				
		if (error){
			$("#estado").html("Por favor llene todos los campos.").addClass('alerta');
			
		}else{             
			      
			//$("#estado").html("").removeClass('alerta'); 
			ejecutarJson(form);
		}

	}
	
</script>

