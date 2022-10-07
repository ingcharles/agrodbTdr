<?php 
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorCatalogos.php';
    
    $conexion = new Conexion();
    $cc = new ControladorCatalogos();
    
    $cultivo = pg_fetch_assoc($cc->abrirCultivo($conexion, $_POST['id']));
?>

<header>
	<h1>Cultivo</h1>
</header>

	<div id="estado"></div>
	
<form id="formulario" data-rutaAplicacion="registroProducto" data-opcion="actualizarCultivo" data-accionEnExito="ACTUALIZAR">
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>

    <fieldset>
    		<legend>Cultivo</legend>
    			<input type="hidden" name="idCultivo" value="<?php echo $cultivo['id_cultivo'];?>" />
    				
    			<div data-linea="1">
    					<label for="area">Área</label>
    						<select id="area" name="area" disabled="disabled">
    								<option value="" selected="selected">Seleccione una dirección...</option>
    								<option value="IAP">Registro de insumos agrícolas</option>
    								<option value="IAV">Registro de insumos pecuarios</option>
    								<option value="IAF">Registro de insumos fertilizantes</option>								
    								<option value="IAPA">Registro de insumos para plantas de autoconsumo</option>
    						</select>
    			</div>
    		
        		<label>Plaga Nombre común</label>
        		<div data-linea="2">
        			<textarea id="nombreComun" name="nombreComun" disabled="disabled" ><?php echo $cultivo['nombre_comun_cultivo'];?></textarea>
        		</div>
        
        		<label>Plaga Nombre científico</label>
        		<div data-linea="3">
        			<textarea id="nombreCientifico" name="nombreCientifico" disabled="disabled" ><?php echo $cultivo['nombre_cientifico_cultivo'];?></textarea>
        		</div>
    		
    </fieldset>	
</form>

<script type="text/javascript">
    var area = <?php echo json_encode($cultivo['id_area']);?>;
    
    $('document').ready(function(){
    	cargarValorDefecto("area","<?php echo $cultivo['id_area'];?>");
        distribuirLineas();
    });	
    
    $("#modificar").click(function(){
    	$("select").removeAttr("disabled");
    	$("textarea").removeAttr("disabled");
    	$("#actualizar").removeAttr("disabled");
    	$(this).attr('disabled','disabled');
    });
    
    $("#formulario").submit(function(event){
    
    	event.preventDefault();
    
    	$(".alertaCombo").removeClass("alertaCombo");
    	var error = false;
    
    	if($("#area").val()==""){
    		error = true;
    		$("#area").addClass("alertaCombo");
    	}
    
    	if($("#nombreCientifico").val()==""){
    		error = true;
    		$("#nombreCientifico").addClass("alertaCombo");
    	}
    
		if($.trim($("#nombreComun").val())==""){
			error = true;
			$("#nombreComun").addClass("alertaCombo");
		}
    
    	if (!error){
    		ejecutarJson($(this));
    	}else{
    		$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
    	}
    });

</script>