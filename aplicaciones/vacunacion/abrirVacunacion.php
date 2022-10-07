<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$va = new ControladorVacunacion();
$cc = new ControladorCatalogos();

$idVacunacion=$_POST['id'];

$qVacunacion = $va->abrirVacunacion($conexion, $idVacunacion);
$filaVacuna = pg_fetch_assoc($qVacunacion);

$qDetalleVacunacion=$va->abrirDetalleVacunacion($conexion, $idVacunacion);

$qTipoVacuna = $cc->listaTipoVacuna($conexion);
while($filaTipoVacuna = pg_fetch_assoc($qTipoVacuna)){
	$tipoVacuna[]= array('id_tipo_vacuna'=>$filaTipoVacuna['id_tipo_vacuna'], 'nombre_vacuna'=>$filaTipoVacuna['nombre_vacuna'], 'id_especie'=>$filaTipoVacuna['id_especie']);
}
$laboratorios = $cc->listaLaboratoriosVacuna($conexion);
$lotes = $cc->listaLotes($conexion, "in ('activo', 'inactivo')");

$filaTipoUsuarioDistribuidor=pg_fetch_assoc($va->obtenerTipoUsuario($conexion, $filaVacuna['identificador_distribuidor']));

$filaTipoUsuarioVacunador=pg_fetch_assoc($va->obtenerTipoUsuario($conexion, $filaVacuna['identificador_vacunador']));

?>

<header>
	<h1>Registro de Vacunación</h1>
</header>
<form id='actualizarVacunacion' data-rutaAplicacion='vacunacion' data-opcion='actualizarVacunacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">	
	<div id="estado"></div>
	<input type="hidden" id="identificacionVacunador" name="identificacionVacunador" value="<?php echo $filaVacuna['identificador_vacunador'];?>" />													  	
	<input type="hidden" id="identificacionDistribuidor" name="identificacionDistribuidor" value="<?php echo $filaVacuna['identificador_distribuidor'];?>" />
	<input type="hidden" id="idVacunacion" name="idVacunacion" value="<?php echo $filaVacuna['id_vacunacion'];?>" />													  	
	<?php if($filaVacuna['estado']=='vigente'){?>
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	<?php } ?>
	<fieldset>
		<legend>Datos Certificado de Vacunación </legend>
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
				<select id="vacunador" name="vacunador" disabled="disabled" >	
				<?php
					switch($filaTipoUsuarioVacunador['codificacion_perfil']) {
						case 'PFL_USUAR_INT':
							$qResultadoUsuarioTecnico=$va->listarTecnicosVacunadores($conexion);
							while ($filas = pg_fetch_assoc($qResultadoUsuarioTecnico)){
								echo '<option value="' . $filas['identificador'] . '">' . $filas['nombres'] .' - '. $filas['identificador'] . '</option>';
							}
						break;
						case 'PFL_USUAR_EXT':						
							$filaIdEmpresaVacunador=pg_fetch_assoc($va->buscarEmpresasXidentificador($conexion, $filaVacuna['identificador_operador_vacunacion']));				
			 				$vacunadores=$va->listarVacunadoresEmpresa($conexion,$filaIdEmpresaVacunador['id_empresa']);
							while ($fila= pg_fetch_assoc($vacunadores)){
								if($fila['estado']=='activo'){
									echo '<option value="'. $fila['identificador'] .'" >'. $fila['nombres'] . ' - ' . $fila['identificador'] .'</option>';
								}else{
								 	if($fila['identificador']==$filaVacuna['identificador_vacunador'])
									echo '<option value="'. $fila['identificador'] .'" disabled >'. $fila['nombres'] . ' - ' . $fila['identificador'] .'</option>';
								}	
							}
						break;
					}
				?>
				</select>
			</div>
			<div data-linea="4">
				<label>Distribuidor: </label> 
				<select id="distribuidor" name="distribuidor" disabled="disabled" >			
					<?php
					switch($filaTipoUsuarioDistribuidor['codificacion_perfil']) {
						case 'PFL_USUAR_INT':
							$qResultadoUsuarioTecnicoDistribuidor=$va->listarTecnicosDistribuidores($conexion);
							while ($filas = pg_fetch_assoc($qResultadoUsuarioTecnicoDistribuidor)){
								echo '<option value="' . $filas['identificador'] . '">' . $filas['nombres'] . ' - ' . $filas['identificador'] . '</option>';
							}
						break;
						case 'PFL_USUAR_EXT':
							$filaIdEmpresaDistribuidor=pg_fetch_assoc($va->buscarEmpresasXidentificador($conexion, $filaVacuna['identificador_operador_vacunacion']));
							$distribuidores=$va->listarDistribuidoresEmpresa($conexion,$filaIdEmpresaDistribuidor['id_empresa']);
							while ($fila= pg_fetch_assoc($distribuidores)){
								if($fila['estado']=='activo'){
									echo '<option value="'. $fila['identificador'] .'" >'. $fila['nombres'] . ' - ' . $fila['identificador'] .'</option>';
								}else{
									if($fila['identificador']==$filaVacuna['identificador_distribuidor'])
										echo '<option value="'. $fila['identificador'] .'" disabled >'. $fila['nombres'] . ' - ' . $fila['identificador'] .'</option>';
								}
							}
						break;
					}
					?>
				</select>
			</div>
			<div data-linea="5">
				<label>Tipo Vacuna: </label>
				<select id="tipoVacuna" name="tipoVacuna" disabled="disabled" >
				</select> 
			</div>
			<div data-linea="5">
				<label>Laboratorio: </label>
				<select id="laboratorio" name="laboratorio" disabled="disabled" >
				</select>
			</div>
			<div data-linea="6">
				<label>Lote Vacuna: </label>
				<select id="loteVacuna" name="loteVacuna" disabled="disabled">
				</select>
			</div>		
			<div data-linea="6">
			    <label>Fecha de Vacunación: </label> 
				<input type="text" id="fechaVacunacion" name="fechaVacunacion" value="<?php echo date("d/m/Y", strtotime($filaVacuna['fecha_vacunacion'])) ;?>" disabled="disabled" readonly />
			</div>
			<div data-linea="7">
				<label>Fecha de Vencimiento: </label> 
				<input type="text" id="fechaVencimiento" name="fechaVencimiento" value="<?php echo date("d/m/Y",strtotime($filaVacuna['fecha_vencimiento']));?>" disabled="disabled"/>			
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
				<input type="text" id="estadoCertificado" name="estadoCertificado" value="<?php echo $filaVacuna['estado'];?>" disabled="disabled"/>
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
					<th title="Número de lote">N° Lote</th>
					<th title="Unidad comercial">U.Comercial</th>
					<th>Identificador Producto</th>				
				</tr>
			</thead>
			<?php 
			
			while($filaDetalleVacuna=pg_fetch_assoc($qDetalleVacunacion)){
				echo '<tr>
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
var array_tipoVacuna= <?php echo json_encode($tipoVacuna); ?>;
var array_laboratorio= <?php echo json_encode($laboratorios); ?>;
var array_lote= <?php echo json_encode($lotes); ?>;

$(document).ready(function(){
	distribuirLineas();
	
	cargarValorDefecto("vacunador","<?php echo $filaVacuna['identificador_vacunador'];?>");
	cargarValorDefecto("distribuidor","<?php echo $filaVacuna['identificador_distribuidor'];?>");
	distribuirLineas();
	
	$("#fechaVacunacion").datepicker({
		changeMonth: true,
		changeYear: true,
		maxDate:"0"
	});	

	
	tipoVacuna = '';
    for(var i=0;i<array_tipoVacuna.length;i++){
	    if (<?php echo $filaVacuna['id_especie']; ?>==array_tipoVacuna[i]['id_especie']){
	    	tipoVacuna += '<option value="'+array_tipoVacuna[i]['id_tipo_vacuna']+'">'+array_tipoVacuna[i]['nombre_vacuna']+'</option>';
		    }
   	}
    $('#tipoVacuna').html(tipoVacuna);
    cargarValorDefecto("tipoVacuna","<?php echo $filaVacuna['id_tipo_vacuna'];?>");
    
	laboratorio = '';
    for(var i=0;i<array_laboratorio.length;i++){
	    if (<?php echo $filaVacuna['id_especie']; ?>==array_laboratorio[i]['id_especie']){
	    	laboratorio += '<option value="'+array_laboratorio[i]['id_laboratorio']+'">'+array_laboratorio[i]['nombre_laboratorio']+'</option>';
		    }
   	}
    $('#laboratorio').html(laboratorio);
    cargarValorDefecto("laboratorio","<?php echo $filaVacuna['id_laboratorio'];?>");

	loteVacuna = '<option value="0">Seleccione...</option>';
    for(var i=0;i<array_lote.length;i++){
	    if ($("#laboratorio").val()==array_lote[i]['id_laboratorio']){
	    	loteVacuna += '<option value="'+array_lote[i]['id_lote']+'">'+array_lote[i]['numero_lote']+'</option>';
		    } 
    	}
    $('#loteVacuna').html(loteVacuna);
    cargarValorDefecto("loteVacuna","<?php echo $filaVacuna['id_lote_vacuna'];?>");

	
});


$("#vacunador").change(function(event){
	$("#identificacionVacunador").val($("#vacunador").val());
});

$("#distribuidor").change(function(event){
	$("#identificacionDistribuidor").val($("#distribuidor").val());
});
$("#fechaVacunacion").change(function(event){
	var splitFecha = $("#fechaVacunacion").val().split("/");
	var fechaFormateada = new Date(splitFecha[2], splitFecha[1], splitFecha[0]);
	
	var fechaMasMeses = new Date(new Date(fechaFormateada).setMonth(fechaFormateada.getMonth()+6));
	var fechaMes=fechaMasMeses.getMonth();

	fechaAno=fechaMasMeses.getFullYear();
	if(fechaMes==0){
		fechaMes=12;
		fechaAno=fechaMasMeses.getFullYear()-1;
	}
	
	fechaDay= ('0' + fechaMasMeses.getDate()).slice(-2);
	fechaMes= ('0' + (fechaMes)).slice(-2);
	
	$("#fechaVencimiento").val(fechaDay+"/"+fechaMes+"/"+fechaAno);
});

$("#modificar").click(function(){
	$("#tipoVacuna").removeAttr("disabled");
	$("#laboratorio").removeAttr("disabled");
	$("#loteVacuna").removeAttr("disabled");
	$("#vacunador").removeAttr("disabled");
	$("#distribuidor").removeAttr("disabled");
	$("#fechaVacunacion").removeAttr("disabled");
	$("#fechaVencimiento").removeAttr("disabled");
	$("#fechaVencimiento").attr("readOnly",true);
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
});

$("#laboratorio").change(function(event){            	    
	slote ='';
	slote ='<option value="0">Seleccione...</option>';
	for(var i=0;i<array_lote.length;i++){	
		if ($("#laboratorio").val()==array_lote[i]['id_laboratorio'])																				  
			slote += '<option value="'+array_lote[i]['id_lote']+'">'+ array_lote[i]['numero_lote']+'</option>';
	}	   
    $('#loteVacuna').html(slote);
    $("#loteVacuna").removeAttr("disabled");
});

$("#actualizarVacunacion").submit(function(event){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;
	event.preventDefault();
	if($("#loteVacuna").val() == 0  ){	
		error = true;		
		$("#loteVacuna").addClass("alertaCombo");
		$("#estado").html('Por favor seleccione el lote de vacuna.').addClass("alerta");
	}

	if (!error){
		ejecutarJson($(this));	
	}	
});
</script>