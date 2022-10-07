<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacion.php';

$conexion = new Conexion();
$va = new ControladorVacunacion();

$idVacunacion=$_POST['id'];
$qVacunacion = $va->abrirVacunacion($conexion, $idVacunacion);
$filaVacuna = pg_fetch_assoc($qVacunacion);

//$controlAnulacionTransaciones = 0;
$banderaTransacion=false;
$banderaCaducado=false;
$banderaAnulado=false;

if($filaVacuna['estado']=='caducado' ){
	$banderaCaducado=true;
}elseif($filaVacuna['estado']=='anulado' ){
	$anulacionVacunacion = pg_fetch_assoc($va->buscarCertificadoAnuladoVacunacion($conexion, $idVacunacion));
	
	$banderaAnulado=true;
}else{
	$qDetalleVacunacion=$va->abrirDetalleVacunacion($conexion, $idVacunacion);
	while($filaDetalle=pg_fetch_assoc($qDetalleVacunacion)){
		$qDetalleIdentificadores=$va->abrirDetalleVacunacionIdentificadores($conexion, $filaDetalle['id_detalle_vacunacion']);
		while($filaIdentificadores=pg_fetch_assoc($qDetalleIdentificadores)){
			$qResultadoMovilizacion=$va->verificarFechaRegistroIdentificadorMovilizacion($conexion, $filaIdentificadores['identificador']);
			if(pg_num_rows($qResultadoMovilizacion)>0){
				if($filaVacuna['fecha_vacunacion']<=pg_fetch_result($qResultadoMovilizacion, 0, 'fecha_registro') && pg_fetch_result($qResultadoMovilizacion, 0, 'fecha_registro')<=$filaVacuna['fecha_vencimiento']){
					$banderaTransacion=true;
					//$identificadoresMovilizados.=$filaIdentificadores['identificador'].', ';
				}
			}
		}
	}
	
}


?>

<form id='guardarAnularVacunacion' data-rutaAplicacion='vacunacion' data-opcion='guardarNuevoAnularVacunacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>
	<input type="hidden" name="idVacunacion" value="<?php echo $idVacunacion;?>" />	
	<input type="hidden" name="idEspecie" value="<?php echo $filaVacuna['id_especie'];?>" />	
	<input type="hidden" name="numeroCertificadoVacunacion" value="<?php echo $filaVacuna['numero_certificado'];?>" />	
	<header>
			<h1>Registro de vacunación</h1>
		</header>
	<div id='vistaCertificadoTransacciones'>
		
		<fieldset>
			<legend>Información del estado del certificado de vacunación</legend>
			<div data-line="1" >
				<label>Estado: </label> <?php echo $filaVacuna['estado'];?>
			</div>	
			<div data-line="2" >
				<label >Motivo: </label> El certificado no puede ser anulado porque se han movilizado productos existentes del certificado.
			</div>	
			<!--  <div data-line="1" >
				<label id="nEliminar" class="alerta">No se puede anular el certificado de vacunación porque se han movilizados los siguientes identificadores: < ?php echo rtrim($identificadoresMovilizados,', ');?></label>
			</div>	-->	
		</fieldset>  	
	</div>
	<div id='vistaCaducado'>
		
		<fieldset>
			<legend>Información del estado del certificado de vacunación</legend>
			<div data-line="1" >
				<label>Estado: </label> <?php echo $filaVacuna['estado'];?>
			</div>	
			<div data-line="2" >
				<label >Motivo: </label> El certificado no puede ser anulado porque el tiempo de vigencia de vacuna ha caducado.
			</div>			
		</fieldset>   				
	</div>
	<div id='vistaCertificadoAnulado'>
		
		<fieldset>
			<legend>Información del estado del certificado de vacunación</legend>		
			<div data-linea="1">
				<label>Número certificado: </label>
				<input type="text" id="numeroCertificadoAnulado" name="numeroCertificadoAnulado" value="<?php echo $anulacionVacunacion['numero_certificado'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="1">
				<label>Estado certificado : </label>
				<input type="text" id="estadoCertificadoAnulado" name="estadoCertificadoAnulado" value="<?php echo $anulacionVacunacion['estado'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="2">
				<label>Motivo Anulacion :</label>
				<input type="text" id="motivoCertificadoAnulado" name="motivoCertificadoAnulado" value="<?php echo $anulacionVacunacion['motivo_anulacion'];?>" disabled="disabled"/>						
			</div>
			<div data-linea="3">	
				<label>Usuario anulación :</label> 								   	
			    <input type="text" id="usuarioCertificadoAnulado" name="usuarioCertificadoAnulado" disabled="disabled" value="<?php echo $anulacionVacunacion['usuario_anulacion'];?>" /> 	    				
			</div>
			<div data-linea="3">
				<label>Fecha anulación : </label>
				<input type="text" id="fechaCertificadoAnulado" name="fechaCertificadoAnulado" value="<?php echo $anulacionVacunacion['fecha_anulacion'];?>" disabled="disabled"/>			
			</div>	
		</fieldset>   	
	</div>
	<div id='vistaNuevaAnulacion'>
	
		<fieldset>
			<legend>Anular certificado de vacunación</legend>
			<div data-linea="1">	
				<label>Motivo anulación :</label> 								   	
			   	<select id="motivoAnulacion" name="motivoAnulacion">
					<option value="0">Seleccione...</option>			
					<option value="Error registro de cédula/ruc">Error registro de cédula/ruc</option>		
				</select>
			</div>
		</fieldset>
		<p data-linea="2" style="text-align: center">
			<button id="guardarAnularVacunacion" type="submit" name="guardarAnularVacunacion" >Anular vacunación</button>
		</p>	   				
	</div>
	    <fieldset>
			<legend>Datos Certificado de Vacunación</legend>
			<div data-linea="1">
				<label>Especie: </label>	
				<input type="text" id="nombreEspecie" name="nombreEspecie" value="<?php echo $filaVacuna['nombre_especie'];?>" disabled="disabled"/>													  
			</div>	
			<div data-linea="1">
				<label>N° Certificado: </label>
				<input type="text" id="numeroCertificado" name="numeroCertificado" value="<?php echo $filaVacuna['numero_certificado'];?>" disabled="disabled"/>													  
			</div>
			<div data-linea="2">
				<label>Identificación Operador: </label> 
				<input type="text" id="identificacionOperador" name="identificacionOperador" value="<?php echo $filaVacuna['identificador_operador'];?>" disabled="disabled"/>
			</div> 
			<div data-linea="2">
				<label>Nombre Operador: </label> 
				<input type="text" id="nombreOperador" name="nombreOperador" value="<?php echo $filaVacuna['nombre_operador'];?>" disabled="disabled"/>
			</div>
			<div data-linea="3">
				<label>Nombre del Sitio: </label> 
				<input type="text" id="nombreSitio" name="nombreSitio" value="<?php echo $filaVacuna['nombre_sitio'];?>" disabled="disabled"/>
			</div> 
			<div data-linea="3">
				<label>Operador Vacunación: </label> 
				<input type="text" id="operadorVacunacion" name="operadorVacunacion" value="<?php echo $filaVacuna['nombre_administrador'];?>" disabled="disabled"/>
			</div>
			<div data-linea="4" >
				<label>Vacunador: </label> 
					<input type="text" id="vacunador" name="vacunador" value="<?php echo $filaVacuna['nombre_vacunador'];?>" disabled="disabled"/>
			</div>
			<div data-linea="4">
				<label>Distribuidor: </label> 
					<input type="text" id="distribuidor" name="distribuidor" value="<?php echo $filaVacuna['nombre_distribuidor'];?>" disabled="disabled"/>
			</div>
			<div data-linea="5">
				<label>Tipo Vacuna: </label>
					<input type="text" id="tipoVacuna" name="tipoVacuna" value="<?php echo $filaVacuna['nombre_vacuna'];?>" disabled="disabled"/>
			</div>
			<div data-linea="5">
				<label>Laboratorio: </label>
					<input type="text" id="laboratorio" name="laboratorio" value="<?php echo $filaVacuna['nombre_laboratorio'];?>" disabled="disabled"/>
			</div>
			<div data-linea="6">
				<label>Lote Vacuna: </label>
					<input type="text" id="loteVacuna" name="loteVacuna" value="<?php echo $filaVacuna['numero_lote'];?>" disabled="disabled"/>
			</div>		
			<div data-linea="6">
			    <label>Fecha de Vacunación: </label> 
				<input type="text" id="fechaVacunacion" name="fechaVacunacion" value="<?php echo date("d/m/Y", strtotime($filaVacuna['fecha_vacunacion'])) ;?>" disabled="disabled" readonly />
			</div>
			<div data-linea="7">
				<label>Fecha de Vencimiento: </label> 
				<input type="text" id="fechaVencimiento" name="fechaVencimiento" value="<?php echo $filaVacuna['fecha_vencimiento'];?>" disabled="disabled"/>			
			</div>			
			<div data-linea="7">
				<label>Fecha de Registro: </label> 
				<input type="text" id="fechaRegistro" name="fechaRegistro" value="<?php echo $filaVacuna['fecha_registro'];?>" disabled="disabled"/>			
			</div>
			<div data-linea="8">
			    <label>Cantidad Vacunada: </label> 
				<input type="text" id="totalVacuna" name="totalVacuna" value="<?php echo $filaVacuna['total_vacunado'];?>" disabled="disabled"/>
			</div>	
			<div data-linea="8">
			    <label>Costo de Vacuna: </label> 
				<input type="text" id="costoVacuna" name="costoVacuna" value="<?php echo $filaVacuna['costo_vacuna'];?>" disabled="disabled"/>
			</div>							
			<div data-linea="9">
			    <label>Total Costo Vacuna: </label> 
				<input type="text" id="totalVacuna" name="totalVacuna" value="<?php echo $filaVacuna['costo_total_vacuna'];?>" disabled="disabled"/>
			</div>	
			<div data-linea="9">
			    <label>Estado: </label> 
				<input type="text" id="estadoVacuna" name="estadoVacuna" value="<?php echo $filaVacuna['estado'];?>" disabled="disabled"/>
			</div>						
		</fieldset>
		<fieldset>
			<legend>Detalle de Productos Vacunados</legend>
			<table id="tablaVacunaAnimal">
				<thead>
					<tr>
						<th>Operación</th>
						<th>Área</th>
						<th>Producto</th>
						<th>Cantidad</th>									
						<th title="Número de lote">No.Lote</th>
						<th title="Unidad comercial">U.Comercial</th>
						<th>Identificador Producto</th>					
					</tr>
				</thead>
					<?php 
					$i=1;
					$qDetalleVacunacionD=$va->abrirDetalleVacunacion($conexion, $idVacunacion);
					while($filaDetalleVacuna=pg_fetch_assoc($qDetalleVacunacionD)){
						echo  '<tr>
								   <td>'.$filaDetalleVacuna['tipo_operacion'].' </td>
								   <td>'.$filaDetalleVacuna['area'].' </td>
								   <td>'.$filaDetalleVacuna['producto_subtipo'].'-'.$filaDetalleVacuna['producto'].' </td>
								   <td>'.$filaDetalleVacuna['cantidad'].'</td>
								   <td>'.$filaDetalleVacuna['numero_lote'].'</td>
								   <td>'.$filaDetalleVacuna['unidad_comercial'].'</td><td>';
						$qDetalleVacunacionIdentificadores=$va->abrirDetalleVacunacionIdentificadores($conexion, $filaDetalleVacuna['id_detalle_vacunacion']);
						while($filaDetalleVacunaIdentificadores=pg_fetch_assoc($qDetalleVacunacionIdentificadores)){
							echo $filaDetalleVacunaIdentificadores['identificador'].' <br>';
						}
						echo '</td> </tr>';		
						$i++;
					}	
				    ?>				
		      </table>	
		</fieldset>
</form>		
<script type="text/javascript">
	var controlTransaccionVacunacion = <?php echo json_encode($banderaTransacion); ?>;
	var controlCaducadoVacunacion = <?php echo json_encode($banderaCaducado); ?>;
	var controlAnulacionVacunacion = <?php echo json_encode($banderaAnulado); ?>;
	$(document).ready(function(){
		distribuirLineas();

		if(controlTransaccionVacunacion){
			$("#vistaNuevaAnulacion").hide();
			$("#vistaCertificadoAnulado").hide();
			$("#vistaCaducado").hide();
		}else if(controlCaducadoVacunacion){
			$("#vistaNuevaAnulacion").hide();
			$("#vistaCertificadoAnulado").hide();
			$("#vistaCertificadoTransacciones").hide();
		}else if (controlAnulacionVacunacion){
			$("#vistaNuevaAnulacion").hide();
			$("#vistaCaducado").hide();
			$("#vistaCertificadoTransacciones").hide();	
		}else{
			$("#vistaCertificadoTransacciones").hide();
			$("#vistaCertificadoAnulado").hide();
			$("#vistaCaducado").hide();
		}
	});
	
	$("#guardarAnularVacunacion").submit(function(event){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		event.preventDefault();
		if($("#motivoAnulacion").val() == 0  ){	
			error = true;		
			$("#motivoAnulacion").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione el motivo de la anulación.').addClass("alerta");
		}

		if (!error){   
			ejecutarJson($(this));	
		}	
	});
</script>