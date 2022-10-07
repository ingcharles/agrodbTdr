<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

$identificador = $_SESSION['usuario'];

$tipoProducto = $cc->listarTipoProductos($conexion);

$tipoTramite = $cc->listarTiposTramites($conexion, "'IAP','IAV'");

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<header>
    <h1>Nuevo tramite</h1>
</header>

<div id="estado"></div>

<form id='nuevoTramite' data-rutaAplicacion='tramitesInocuidad' data-opcion='guardarTramite' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">

	<input type="hidden" id="opcion" name="opcion" /> 
	<input type="hidden" id="identificadorUsuario" name="identificadorUsuario" value= "<?php echo $identificador;?>" />

    <fieldset>
        <legend>Datos de usuario</legend>

        <div data-linea="1">
            <label>Cédula/RUC</label>
            <input type="text" id="identificadorOperador" name="identificadorOperador" data-er="[0-9]" />
        </div>
        
        <div data-linea="2">			
			<div id="dNombreOperador"></div>
		</div>
        
    </fieldset>

    <fieldset>
			<legend>Datos de producto</legend>
			
			<div data-linea="1">			
				<label>Tipo de producto</label> 
				<select id="tipoProducto" name="tipoProducto">
					<option value="">Tipo de producto....</option>
						<?php 
							while ($fila = pg_fetch_assoc($tipoProducto)){
								if($fila['id_area']=='IAP' || $fila['id_area']=='IAV'){
									$opcionesTipoProducto[] =  '<option value="'.$fila['id_tipo_producto']. '" data-grupo="'. $fila['id_area'] . '">'. $fila['nombre'] .'</option>';	
								}
								
							}
						?>
				</select>
			</div>
			
			<div data-linea="2">			
				<div id="dSubTipoProducto"></div>
			</div>
			
			<div data-linea="3">
				<div id="dProducto"></div>			
			</div>			
	</fieldset>


    <fieldset>
        <legend>Datos de tramite</legend>

        <div data-linea="1">
            <label>Tipo de tramite</label>
            <select id="tipoTramite" name="tipoTramite">
            	<option value="" selected="selected">Seleccione...</option>
            <?php 
	            while ($fila = pg_fetch_assoc($tipoTramite)){
	            	echo '<option value="'.$fila['id_tipo_tramite'].'">'.$fila['nombre'].'</option>';
	            }
            ?>
            </select>
            <input type="hidden" id="nombreTipoTramite" name="nombreTipoTramite"/>
        </div>

        <div data-linea="2">
            <label>Observación</label>
            	 <input type="text" id="observacion" name="observacion" />
        </div>

    </fieldset>

    <button type="submit" class="guardar">Guardar solicitud</button>
</form>
</body>

<script type="text/javascript">

	var array_opcionesTipoProducto = <?php echo json_encode($opcionesTipoProducto);?>;

    $(document).ready(function () {
        distribuirLineas();
        construirValidador();  

        for(var i=0; i<array_opcionesTipoProducto.length; i++){
			 $('#tipoProducto').append(array_opcionesTipoProducto[i]);
	    }      
    });


    $("#tipoProducto").change(function (event) {    	
    	$("#nuevoTramite").attr('data-opcion', 'combosTramites');
    	$("#nuevoTramite").attr('data-destino', 'dSubTipoProducto');
    	$("#opcion").val('subTipoProducto');
    	abrir($("#nuevoTramite"), event, false); //Se ejecuta ajax, busqueda de sub tipo producto
	});
	
    $("#identificadorOperador").change(function (event) {

    	$(".alertaCombo").removeClass("alertaCombo");

    	if(esCampoValido("#identificadorOperador")){
			$("#nuevoTramite").attr('data-opcion', 'combosTramites');
		    $("#nuevoTramite").attr('data-destino', 'dNombreOperador');
		    $("#opcion").val('operador');
		    abrir($("#nuevoTramite"), event, false); //Se ejecuta ajax, busqueda de sub tipo producto
    	}else{
    		$("#identificadorOperador").addClass("alertaCombo");
        	$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
        }
        
	});

    $("#tipoTramite").change(function(event){
		$("#nombreTipoTramite").val($("#tipoTramite  option:selected").text());
	 });

    function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

    $("#nuevoTramite").submit(function(event){
		event.preventDefault();	

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#identificadorOperador").val()) || !esCampoValido("#identificadorOperador")){
			error = true;
			$("#identificadorOperador").addClass("alertaCombo");
		}
		
		if(!$.trim($("#tipoTramite").val())){
			error = true;
			$("#tipoTramite").addClass("alertaCombo");
		}

		if(!$.trim($("#tipoProducto").val())){
			error = true;
			$("#tipoProducto").addClass("alertaCombo");
		}

		if(!$.trim($("#subtipoProducto").val())){
			error = true;
			$("#subtipoProducto").addClass("alertaCombo");
		}

		if(!$.trim($("#producto").val())){
			error = true;
			$("#producto").addClass("alertaCombo");
		}

		if($("#operador").val() == '0'){
			error = true;
			$("#lOperador").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			$("#nuevoTramite").attr('data-opcion', 'guardarTramite');
		    $("#nuevoTramite").attr('data-destino', 'detalleItem');
		    abrir($(this),event,false);	
		}
    });

	
    
    
</script>
</html>

