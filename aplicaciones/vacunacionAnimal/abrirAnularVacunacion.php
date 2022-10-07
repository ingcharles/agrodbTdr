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

//controlar a que no anulen documentos movilizados
$controlAnulacionMovilizados = 0;
$ControlCatastroMovilizado = $vc-> buscarAnularMovilizados($conexion, $vacuna['id_vacuna_animal']);
if(pg_num_rows($ControlCatastroMovilizado)!=0){
	$controlAnulacionMovilizados = 1;	
}
else
{	
	$controlAnulacionVacunacion = 0;
	$Control = $vc-> buscarAnularVacunacion($conexion, $vacuna['id_vacuna_animal']);
	if(pg_num_rows($Control)!=0){
		$controlAnulacionVacunacion = 1;
		$anulacionVacunacion = pg_fetch_assoc($Control);
	}
}
//controlar a que no anulen documentos movilizados

?>
<style type="text/css">
#tablaVacunaAnimal td, #tablaVacunaAnimal th 
{
font-size:1em;
border:1px solid rgba(0,0,0,.1);
padding:3px 7px 2px 7px;
}
</style>
<form id='anularVacunacion' data-rutaAplicacion='vacunacionAnimal' data-opcion='guardarAnularVacunacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
<input type="hidden" name="id_vacuna_animal" value="<?php echo $_POST['id'];?>" />	
<input type="hidden" id="usuario_responsable" name="usuario_responsable" value="<?php echo $_SESSION['usuario'];?>" />
<input type="hidden" id="numero_documento" name="numero_documento" value="<?php echo $vacuna['numero_certificado'];?>" />  

<div id='anular_catastro'>
	<fieldset>
		<legend>Certificado de vacunación</legend>
	   	 <div data-linea="1">
			<label>Motivo anulación :</label> 					
		</div>
		<div data-linea="2">			   	
		    <textarea id="observacionCV" name="observacionCV" rows="3" cols="100" disabled="disabled">No se puede anular el certificado de vacunación, ya tiene transacciones de movilización !!! </textarea>		   	    			
		</div>				
	</fieldset>   				
</div>
<div id='anular_vista'>	
	<fieldset>
		<legend>Información de la anulación de certificado de vacunación</legend>		
		<div data-linea="1">
			<label>N° certificado de vacunación : </label>
			<input type="text" id="numero_certificado" name="numero_certificado" value="<?php echo $anulacionVacunacion['num_certificado'];?>" disabled="disabled"/>			
		</div>
		<div data-linea="2">
			<label>Fecha anulación : </label>
			<input type="text" id="f_anulacion" name="f_anulacion" value="<?php echo $anulacionVacunacion['fecha_anulacion'];?>" disabled="disabled"/>			
		</div>
		<div data-linea="3">
			<label>Respuesta :</label>
			<input type="text" id="f_respuesta" name="f_respuesta" value="<?php echo $anulacionVacunacion['estado_vacunacion'];?>" disabled="disabled"/>						
		</div>
		<div data-linea="4">	
			<label>Observación :</label> 								   	
		    <input id="f_observacion" name="f_observacion" disabled="disabled" value="<?php echo $anulacionVacunacion['observacion'];?>" /> 	    				
		</div>				
	</fieldset>   	
</div>
<div id='anular_nuevo'>
	<button id="btn_guardar" type="button" name="btn_guardar" >Guardar anulación certificado de vacunación</button>
	<fieldset>
		<legend>Anular certificado de vacunación</legend>
	    <div data-linea="1">			
			<label>Tipo anulación</label>				
			<select id="cmbTipoAnulacion" name="cmbTipoAnulacion">
				<option value="0">Seleccione...</option>					
				<option value="anulado">Certificado anulado</option>
			</select>
		</div>		
		<div data-linea="2">	
			<label>Motivo anulación :</label> 								   	
		   	<select id="observacion" name="observacion">
				<option value="0">Seleccione...</option>
				<option value="Error digitación">Error digitación</option>					
				<option value="Error registro de cédula/ruc y/o número de certificado">Error registro de cédula/ruc y/o número de certificado</option>
				<option value="Irregularidades presentadas">Irregularidades presentadas	</option>
			</select>
		</div>			
	</fieldset>   				
</div>
<div id='anular'>
    <fieldset>
		<legend>Información del certificado de vacunación</legend>
			<div data-linea="1">
				<label>N° Certificado de vacunación : </label>	
				<input type="text" id="nombreEspecieValorada" name="nombreEspecieValorada" value="<?php echo $vacuna['numero_certificado'];?>" disabled="disabled"/>													  
			</div>	
			<div data-linea="1">
				<label>Operador vacunador</label> 
				<input type="text" id="nombreOperadorVacunador" name="nombreOperadorVacunador" value="<?php echo $vacuna['nombre_administrador'];?>" disabled="disabled"/>
			</div>
			<div data-linea="2">
			<label>Provincia distribución</label> 
				<input type="text" id="provincia_distribucion" name="provincia_distribucion" value="<?php echo $vacuna['provincia_distribuidor'];?>" disabled="disabled"/>
			</div>			
			<div data-linea="2">
			    <label>Punto de distribución</label> 
				<input type="text" id="pto_distribucion" name="pto_distribucion" value="<?php echo $vacuna['nombre_distribuidor'];?>" disabled="disabled"/>
			</div>			
			<div data-linea="3">
			    <label>Laboratorio</label> 
				<input type="text" id="laboratorio" name="laboratorio" value="<?php echo $vacuna['nombre_laboratorio'];?>" disabled="disabled"/>
			</div>
			<div data-linea="3">
				<label>Lote</label> 
				<input type="text" id="lote" name="lote" value="<?php echo $vacuna['numero_lote'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="4">
			    <label>Identificador</label> 
				<input type="text" id="identificador_vacunador" name="identificador_vacunador" value="<?php echo $vacuna['identificador_vacunador'];?>" disabled="disabled"/>
			</div>
			<div data-linea="4">
				<label>Vacunador</label> 
				<input type="text" id="nombre_vacunador" name="nombre_vacunador" value="<?php echo $vacuna['nombre_vacunador'];?>" disabled="disabled"/>			
			</div>					
			<div data-linea="5">
			    <label>F.Vacunación</label> 
				<input type="text" id="fecha_vacunacion" name="fecha_vacunacion" value="<?php echo $vacuna['fecha_vacunacion'];?>" disabled="disabled"/>
			</div>
			<div data-linea="5">
				<label>F.Vencimiento</label> 
				<input type="text" id="fecha_vencimiento" name="fecha_vencimiento" value="<?php echo $vacuna['fecha_vencimiento'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="6">
			    <label>Nombre del sitio</label> 
				<input type="text" id="nombre_sitio" name="nombre_sitio" value="<?php echo $vacuna['nombre_sitio'];?>" disabled="disabled"/>
			</div>
			<div data-linea="6">
				<label>F.Registro</label> 
				<input type="text" id="fecha_registro" name="fecha_registro" value="<?php echo $vacuna['fecha_vencimiento'];?>" disabled="disabled"/>			
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


</form>		
<script type="text/javascript">
		
	var ControlAnulacionVacunacion = <?php echo json_encode($controlAnulacionVacunacion); ?>;
	var ControlAnulacionMovilizacion = <?php echo json_encode($controlAnulacionMovilizados); ?>;
	
	$(document).ready(function(){
		distribuirLineas();

		if(ControlAnulacionMovilizacion == 1)
		{
			$("#anular_catastro").show();
			$("#anular_vista").hide();
			$("#anular_nuevo").hide();
			$("#anular").show();			
		}	
		else
		{
			if(ControlAnulacionVacunacion == 1){
				$("#anular_vista").show();
				$("#anular_nuevo").hide();
				$("#anular_catastro").hide();						
			}else{
				
				$("#anular_vista").hide();
				$("#anular_nuevo").show();
				$("#anular_catastro").hide();				
			}
		}
		$("#observacion").attr('disabled','disabled');
		
	});
	$("#cmbTipoAnulacion").change(function(){ 
		if ($("#cmbTipoAnulacion").val() !='0'){	       
	    	
	    	$('#observacion').removeAttr("disabled");
	    	
		}
	});
	
	//Para guardar la fiscalización
	$("#btn_guardar").click(function(event){
		 $('#anularVacunacion').attr('data-opcion','guardarAnularVacunacion');
		 $('#anularVacunacion').attr('data-destino','res_sitio');
	     event.preventDefault();	
		 abrir($("#anularVacunacion"),event,false); //Se ejecuta ajax, busqueda de sitio	
		 abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);			 		 	
	});
	
</script>
