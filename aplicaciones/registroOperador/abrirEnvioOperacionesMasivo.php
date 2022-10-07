<?php

session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php'; 

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

if($_POST['id'] == '_agrupar'){
	$idArr=explode(",",$_POST['elementos']);
}

$identificadorOperador=$_SESSION['usuario'];

$qsitios=$cro->listarSitios($conexion, $identificadorOperador);

$idGrupoOperaciones = explode(",",($_POST['elementos']==''?$_POST['id']:$_POST['elementos']));

?>


<header>
	<h1>Envio Operaciones Masivo</h1>
</header>
	<div id="estado"></div>
	<form id='nuevaSolicitud' data-rutaAplicacion='registroOperador' data-opcion='guardarNuevoEnvioOperacionesMasivo' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" name="identificador" id="identificador" value="<?php echo $identificadorOperador ?>" /> 
	<input type="hidden" name="areaProducto" id="areaProducto" /> 
	<input type="hidden" name="opcion"id="opcion" /> 
	<input type="hidden" name="idTipoOperacion"	id="idTipoOperacion" /> 
	<input type="hidden" name="idSitio"	id="idSitio" />
	<input type="hidden" id="idFlujo" name="idFlujo" />
	
	<fieldset>
		<legend>Información del producto</legend>
		<?php 
		$contador = 1;
		$bandera = 0;
		foreach ($idGrupoOperaciones as $solicitud){

		//$registros = array();
		$qProductos = $cc->obtenerTipoSubtipoProductoOperacionMasivo($conexion, $solicitud);

		while($areaOperacion = pg_fetch_assoc($qProductos)){

			$registros[] = array(idTipoProducto => $areaOperacion['id_tipo_producto'],nombreTipoProducto => $areaOperacion['nombretipoproducto'], 
					idSubtipoProducto => $areaOperacion['id_subtipo_producto'], nombreSubtipoProducto => $areaOperacion['nombresubtipoproducto'], 
					idProducto => $areaOperacion['id_producto'], nombreProducto => $areaOperacion['nombre_comun'], idArea => $areaOperacion['id_area']);

		}
				
		echo ($contador!=1?'<hr>':'');

		echo'
		
			<div data-linea="'.$contador++.'">
			<label>Tipo de producto: </label>' . $registros[$bandera]['nombreTipoProducto'] . '
			</div>	
		
			<div data-linea="'.$contador++.'">
			<label>Subtipo producto: </label>' . $registros[$bandera]['nombreSubtipoProducto'] . '
			</div>
				
			<div data-linea="'.$contador++.'">
			<label>Producto: </label>' . $registros[$bandera]['nombreProducto'] . '
			</div>';
			
		$bandera++;
			
	}
	
	$tipoArea= $registros[0]['idArea'];
	?>
	
	<input type="hidden" name="registro" value="<?php echo base64_encode(serialize($registros)); ?>"/>


	</fieldset>

	<fieldset>
		<legend>Información general</legend>
		<div data-linea="1">
			<label>Sitio:</label> <select id="sitio" name="sitio">
				<option value="0">Seleccione...</option>
				<?php 
				while ($fila = pg_fetch_assoc($qsitios)){
					    		echo '<option value="'.$fila['id_sitio'].'">'. $fila['nombre_lugar'] .'</option>';
					    	}
					    	?>
			</select>
		</div>
		<div id="operacion" data-linea="2"></div>
		<div id="area" data-linea="4"></div>
	</fieldset>

	<div>
		<button id="enviarSolicitud" type="submit" class="guardar">Enviar solicitud</button> 
	</div>

</form>

<script type="text/javascript">	
						
var array_productos= <?php echo json_encode($idArr); ?>;
var nombreArea=<?php echo json_encode($tipoArea); ?>;

$(document).ready(function(){		
	distribuirLineas(); 
	 $('#btnGuardar').hide();
	if(array_productos == ''){
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione uno o varios productos y a continuación presione el boton agrupar.</div>');
	}
});


$("#sitio").change(function(event){
	 $('#nuevaSolicitud').attr('data-opcion','combosOperador');
	 $('#nuevaSolicitud').attr('data-destino','operacion');
	 $('#opcion').val('operaciones');	
	 $('#areaProducto').val(nombreArea);	
	 $("#idSitio").val($("#sitio option:selected").val());	
	 abrir($("#nuevaSolicitud"),event,false);	
});


$("#nuevaSolicitud").submit(function(event){
      event.preventDefault();

      $(".alertaCombo").removeClass("alertaCombo");
	  	var error = false;
	
			if($("#sitio").val()=="0"){	
				error = true;		
				$("#sitio").addClass("alertaCombo");
			}
	
			if($("#tipoOperacion").val()==""){	
				error = true;		
				$("#tipoOperacion").addClass("alertaCombo");
			}
	
			if($(".areas").val()==""){	
				error = true;		
				$(".areas").addClass("alertaCombo");
			}

			if (error == true){
				$("#estado").html("Por favor, llene todos los campos.").addClass('alerta');
			}
			else{
				$('#nuevaSolicitud').attr('data-opcion','guardarNuevoEnvioOperacionesMasivo');
				ejecutarJson($(this));                             
			}
});

</script>
