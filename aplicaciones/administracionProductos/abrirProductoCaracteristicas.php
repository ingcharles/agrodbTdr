<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';


$conexion = new Conexion();
$cc = new ControladorCatalogos();
$usuario = $_SESSION['usuario'];
$cac = new ControladorAdministrarCaracteristicas();
$cat = new ControladorAdministrarCatalogos();
$idProducto= $_POST['id'];
$usuario=$_SESSION['usuario'];
?>

<header>
	<h1>Modificar Características del Producto</h1>
</header>

<div id="estado"></div>

<form id="abrirCaracteristica" data-rutaAplicacion="administracionProductos" >
	<input type="hidden" id="opcion" value="" name="opcion">
	<input type="hidden" id="usuario" value=<?php echo $usuario;?> name="usuario">
	<input type="hidden" id="id" name="id" value="<?php echo $idProducto?>">
	<fieldset>
		<?php
		$res=$cc->obtenerTipoSubtipoProductoOperacionMasivo($conexion, $idProducto);
		$fila=pg_fetch_assoc($res);
		switch ($fila['id_area']){
			case'SV':
				$area="Sanidad Vegetal";
			break;
			
			case'SA':
				$area="Sanidad Animal";
			break;
			
			case'LT':
				$area="Laboratorios";
			break;
			
			case'AI':
				$area="Inocuidad de los Alimentos";
			break;
		}
		?>
		<legend>Información del Producto</legend>
		<div data-linea="1">
			<label>Área:</label>
			<input type="text" value="<?php echo $area?>" disabled>
		</div>
		<div data-linea="2" id="resultadoTipoProducto">
			<label>Tipo Producto:</label>
			<input type="text" value="<?php echo $fila['nombretipoproducto']?>" disabled>
		</div>
		<div data-linea="3" id="resultadoSubTipoProducto">
			<label>Subtipo Producto:</label>
			<input type="text" value="<?php echo $fila['nombresubtipoproducto']?>" disabled>
		</div>
		<div data-linea="4" id="resultadoProducto">
			<label>Producto:</label>
			<input type="text" value="<?php echo $fila['nombre_comun']?>" disabled>
		</div>
	</fieldset>
	
	
	<fieldset>
		<legend>Parámetros</legend>
		<div data-linea="1" >
		<label for="cbFormulario">Formulario:</label>
			<select id="cbFormulario" name="cbFormulario">
						<option value="">Seleccione....</option>
						<?php 
						$res=$cac->listarFormualrios($conexion);
							while ($fila=pg_fetch_assoc($res)){
								echo '<option value="'.$fila['id_formulario'].'">'.$fila['nombre_formulario'].'</option>';								
							}
						?>
			</select>
		</div>		
		<div data-linea="3" >
			<label for="txtEtiqueta">Etiqueta</label>
			<input type="text" id="txtEtiqueta" name="txtEtiqueta">
		</div>
		<div data-linea="4" >
			<label for="cbTipoElemento">Tipo Elemento:</label>
			<select id="cbTipoElemento" name="cbTipoElemento">
					<option value="">Seleccione....</option>
					<option value="CB">ComboBox</option>									
			</select>
		</div>
		<div data-linea="5" >
		<label for="cbCatalogo">Seleccionar Catálogo:</label>
			<select id="cbCatalogo" name="cbCatalogo">
						<option value="">Seleccione....</option>
						<?php 
						$res=$cat->listarCatalogos($conexion,'',1);
							while ($fila=pg_fetch_assoc($res)){
								echo '<option value="'.$fila['id_catalogo_negocios'].'">'.$fila['nombre'].'</option>';								
							}
						?>
			</select>
		</div>
		<div data-linea="6">
			<table id="tbItems" style="width:100%; text-align:center;">
				<thead>
					<tr>
						<th style="width: 10%;">#</th>
						<th style="width: 90%;">Ítem</th>						
					</tr>
				</thead>
				<tbody id="cuerpoItems">
				</tbody>
			</table>
		</div>	
	</fieldset>
	
	<input type="hidden" id="nFormulario" name="nFormulario">
	<input type="hidden" id="nCatalogo" name="nCatalogo">
	
	<button id="btnAgregarItem" class="mas" type="submit"> Agregar</button>
	
	</form>
	
	<form id="nuevo"></form>
	
	<fieldset>
		<div data-linea="1">
			<table id="tbCaracteristicas" style="width:100%; text-align:center;">
				<thead>
					<tr>
						<th >#</th>
						<th >Etiqueta</th>
						<th >Formulario</th>						
						<th>Catálogo</th>
						<th>Tipo Elemento</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody id="cuerpoItems">
					<?php
					$res= $cac->listarCaracteristicasXProducto($conexion, $idProducto);
					while ($fila=pg_fetch_assoc($res)){
						
						
						$con+=1;
						switch ($fila['estado']){
							case '1':
								$estado="activo";
								break;
								
							case '2':
								$estado="inactivo";
								break;
								
							default:
								$estado="inactivo";
								break;
						}
						
						echo $cac->imprimirElemento($fila['id_elemento'], $fila['id_producto'],$con, $fila["etiqueta"], $fila["nombre_formulario"], $fila["catalogo"], $fila["tipo"], $estado);
			
					}
					
					?>
				</tbody>
			</table>
		</div>	
	</fieldset>
	
	<!-- button type="submit" class="guardar" id="btnGuardar">Guardar Ingreso</button-->


<script type="text/javascript">

$("document").ready(function(event){
	distribuirLineas();
	construirValidador();
	acciones("#nuevo","#tbCaracteristicas");
	//acciones("NULL","#tbCaracteristicas");
	
});

$("#cbFormulario").change(function(event){
	$("#nFormulario").val($("#cbFormulario option:selected").text());
});

$("#cbCatalogo").change(function(event){
	event.preventDefault();	
	event.stopImmediatePropagation();
	$("#nCatalogo").val($("#cbCatalogo option:selected").text());
	$('#abrirCaracteristica').attr('data-opcion','comboEditarCaracteristicas');
	$('#abrirCaracteristica').attr('data-destino','cuerpoItems');
	$('#opcion').val('items');
	abrir($("#abrirCaracteristica"),event,false);	
});

function delFilaActual(r){
	var i = r.parentNode.parentNode.rowIndex;		    	    
    var table = document.getElementById('tbItems');
    table.deleteRow(i);

    var filas = table.rows.length;
    
	if(filas == 1){
 		$("#estado").html(""); 		
 		$("#btnGuardar").attr("disabled",true);
 		//$("#seccionDocumentos").hide("200"); 		
	}
	enumerar(r);
}

function enumerar(){
	//var i = e.parentNode.parentNode.rowIndex;		    	    
    //var tabla = document.getElementById('tbCaracteristicas');
    con=0;   
    $("#tbCaracteristicas tbody tr").each(function(row){        
    	con+=1;    	
    	$(this).find('td').eq(0).html(con);
    	console.log(con);    	
    });
}


$("#abrirCaracteristica").submit(function(event){
	event.preventDefault();	
	$(".alertaCombo").removeClass("alertaCombo");
	$(".alerta").removeClass("alerta");
	error=false;
	if($("#cbFormulario").val()==""){
		error=true;
		$("#cbFormulario").addClass("alertaCombo");
	}
	
	if($("#txtEtiqueta").val()==""){
		error=true;
		$("#txtEtiqueta").addClass("alertaCombo");
	}
	if($("#cbTipoElemento").val()==""){
		error=true;
		$("#cbTipoElemento").addClass("alertaCombo");
	}
	if($("#cbCatalogo").val()==""){
		error=true;
		$("#cbCatalogo").addClass("alertaCombo");
	}

	if(!error){

    	var data = $("#abrirCaracteristica").serialize();
        $.ajax({
            type: "POST",
            data: data,        
            url: "aplicaciones/administracionProductos/agregarCarateristica.php",
            dataType: "json",
            success: function(msg) {           
            	if(msg.estado=="exito"){ 
        			$("#tbCaracteristicas tbody").append(msg.mensaje);        			
        			$("#estado").html(msg).removeClass("alerta");
        			$("#estado").html("Nuevo registro agregado").addClass("exito");   
            		enumerar();
            	} else{
            		mostrarMensaje(msg.mensaje,"FALLO");
            	}
                	                       
            },
            error: function(msg){            
            	$("#estado").html(msg).addClass("alerta");          
            }
        });	
	} else{		
		mostrarMensaje("Por favor revise los campos obligatorios","FALLO");
	}
});

	
	
</script>
