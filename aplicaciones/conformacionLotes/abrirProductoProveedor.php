<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';

$conexion = new Conexion();
$cl = new ControladorLotes();
$cc = new ControladorCatalogos();
$ca = new ControladorAdministrarCaracteristicas();
$idRegistro= $_POST['id'];
$res = $cl->ObtenerRegistro($conexion,$_POST['id']);
$filaRegistro = pg_fetch_assoc($res);
?>

<header>
<?php 
	if($filaRegistro['estado']=='1'){
		echo "<h1>Editar Registro de Ingreso</h1>";		
	} else{
		echo "<h1>Ver Registro de Ingreso</h1>";
	}
?>
	
</header>

<div id="estado"></div>
<form id="modificarProductoProveedor" data-rutaAplicacion="conformacionLotes" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="opcion" value="" name="opcion">
	<input type="hidden" id="idRegistro" value=<?php echo $_POST['id']?> name="idRegistro">
	<input type="hidden" id="usuario" value=<?php echo $filaRegistro['identificador_operador'];?> name="usuario">
	<input type="hidden" id="areaProveedor" value="<?php echo $filaRegistro['area_proveedor'];?>" name="areaProveedor">
	<input type="hidden" id="idAreaProveedor" value="<?php echo $filaRegistro['id_area_proveedor'];?>" name="idAreaProveedor">
	 
	<p>
	
	<?php 
	if($filaRegistro['estado']=='1'){
		echo "<button id=modificar type=button class=editar>Modificar</button> ";
		echo "<button id=actualizar type=submit class=guardar disabled=disabled>Actualizar</button>";		
	}
	?>
		
		
	</p>
	<fieldset>
		<legend>Ingreso de Proveedores</legend>
		<div data-linea="1">
			<label for="codigoIngreso">Código de ingreso: </label> 
			
			<input type="text" id="codigoIngreso" name="codigoIngreso" value="<?php echo $filaRegistro['codigo_registro']?>" readOnly disabled>
			
		</div>
		<div data-linea="1"> 
			<label for="fechaIngresos">Fecha de ingreso: </label>			
			<input type="text" id="fechaIngresos" name="fechaIngresos" value=<?php echo $filaRegistro['fecha_ingreso']?> readOnly disabled>
			<input type="hidden" id="nFecha" name="nFecha" value="<?php echo $filaRegistro['fecha_ingreso']?>" readOnly>
		</div>
		<div data-linea="2">
			<label for="productos">Producto: </label>
			<select id="productos" name="productos" disabled>
					<option value="">Seleccione....</option>
					<?php 
					   $res= $cl->listarProductosTrazabilidadTodos($conexion);
    					while ($produFila = pg_fetch_assoc($res)){
    					    echo '<option value="' . $produFila['id_producto'] . '">' . $produFila['nombre_comun'].'</option>';
    					}
					?>					
			</select>
			<input type="hidden" id="nproducto" name="nproducto" value="<?php echo $filaRegistro['nombre_producto']?>"  >			
		</div>		
		<div data-linea="3" id="resultadoProveedor" >		
			<label for="proveedores">Nombre del Proveedor: </label>
			<select id="proveedores" name="proveedores" disabled>
					<option value="">Seleccione....</option>
					<?php
						$producto = $cl->listarProveedoresPorProducto($conexion, $filaRegistro['id_producto'],$_SESSION['usuario']);
						while($fila=pg_fetch_assoc($producto)){
							echo '<option value="' . $fila['identificador_proveedor'] .'">' . $fila['nombre_proveedor'] . '</option>';
						}
					?>										
			</select>
			<input type="hidden" id="nproveedor" name="nproveedor"  value="<?php echo $filaRegistro['nombre_proveedor']?>"  >
		</div>
		<div data-linea="4" > 
			<label for="identificacionProveedor">Identificación del Proveedor: </label>
			<input type="text" id="identificacionProveedor" name="identificacionProveedor" value="<?php echo $filaRegistro['identificador_proveedor']?>" readOnly disabled>
		</div>
		
		<div data-linea="5" id="resultadoCantidad">
		  
			<label for="cantidad">Cant. a registrar: </label>			
			<input type="text" id="cantidad" name="cantidad" value="<?php echo $filaRegistro['cantidad']?>" disabled>
		</div>
		
		<div data-linea="5"> 
			<label for="unidad">Unidad: </label>
			<select id="unidad" name="unidad" disabled>
				<option value="">Seleccione....</option>
				<?php
					$res= $cc->listarUnidadesMedida($conexion);
					while($fila=pg_fetch_assoc($res)){
						echo '<option value="' . $fila['id_unidad_medida'] . '" >'. $fila['nombre'] .'</option>';
					}
					
				?>
			</select>
			<div id="resultadoUnidad" >
				<input type="hidden" name="nUnidad" id="nUnidad" value="<?php echo $filaRegistro['nombre_unidad']?>">
			</div>
		</div>
	</fieldset>
	
	
	<?php
	
	  $res=$ca->obtenerFormulario($conexion, "nuevoProductoProveedor");
	  $formulario = pg_fetch_assoc($res);		  
	  $totalFormulario = pg_num_rows($res);
	  
	  if($totalFormulario>0){
	  
    	  $caracteristicas= $ca->mostrarCaracteristicasGuardadas($conexion, $idRegistro, $formulario['id_formulario']);
    	  $totalCaracteristicas= pg_num_rows($caracteristicas);
    	  
    	  if ($totalCaracteristicas>0){    	      
    	      
    	      echo '<fieldset>
    	              <legend>Caracteristícas adicionales</legend>';		      
    	      
    	      while ($fila=pg_fetch_assoc($caracteristicas)){
    	          $con+=1;
    	          echo '<div data-linea="'.$con.'">';
    	          echo '<label>'.$fila['etiqueta'].': </label>';		          		          
    	          echo '<input type="text" id="identificacionProveedor" name="identificacionProveedor" value="'.$fila['nombre'].'" readOnly disabled>';
                  echo '</div>';
    	      }
    	  }    	  
    	  echo '</fieldset>';
	  
	  }
	  
	
	if($filaRegistro['estado']=='1'){
	
		echo'<fieldset id="ingresoDivision">
			<legend>División de ingresos</legend>
			<div data-linea="1">
				<label>Ingrese el número de veces que desea dividir el registro</label>
				<input type="text" id="division" name="division" onkeypress="soloNumeros()" style="width:20% !important; ">
				<input type="hidden" id="originalDivision" name="originalDivision" onkeypress="soloNumeros()" onkeypress="soloNumeros()" readOnly>
			</div>		
			<div style="text-align:center;width:100%">
				<button class="mas" id="aregarDivision">Agregar</button>			
			</div>
			<div id="resultadoDivision" style="width:100%">
				
			</div>
		</fieldset>';
	}
	?>
		
	
</form>


<script type="text/javascript">

$("document").ready(function(){
	distribuirLineas();	
	cargarValorDefecto("productos","<?php echo $filaRegistro['id_producto'];?>");
	cargarValorDefecto("proveedores","<?php echo $filaRegistro['identificador_proveedor'];?>");
	cargarValorDefecto("variedad","<?php echo $filaRegistro['id_variedad'];?>");
	cargarValorDefecto("unidad","<?php echo $filaRegistro['id_unidad'];?>");
	$("#cantidad").numeric();
	$("#cantidad").attr('maxlength','10');		
});

$('#cantidad').keyup(function(e){	
    if($(this).val().indexOf('.')!=-1){         
        if($(this).val().split(".")[1].length > 2){      
            this.value = this.value.substring(0,this.value.length-1);                        
        }  
     } 
});

$("#division").on("keypress keyup blur",function (event) {
    $(this).val($(this).val().replace(/[^\d].+/, ""));        
     if ((event.which < 48 || event.which > 57)) {
         if(event.which != 8)        	 
         event.preventDefault();
     }
});

$("#unidad").change(function(event){
	$("#modificarProductoProveedor").attr('data-destino', 'resultadoUnidad');
    $("#modificarProductoProveedor").attr('data-opcion', 'comboProveedor');
	$("#opcion").val("unidad");
	abrir($("#modificarProductoProveedor"), event, false);	
});

function soloNumeros(){			 
	if ((event.keyCode < 48) || (event.keyCode > 57))
		event.returnValue = false;	
}

$("#cancelar").click(function(event){
	event.preventDefault();
	$("#ingresoDivision").hide("200");
});

$("#aregarDivision").click(function(event){
	event.preventDefault();
	var error=false;
	$("#modificar").attr("disabled",true);

	
	if($.trim($("#detalleItem #division").val()) == ""){
		error=true;
		$("#detalleItem #division").addClass("alertaCombo");
		$("#estado").html("Ingrese el número de veces que desea divir el registro").addClass("alerta");
	}

	if(!error){2.
		$("#originalDivision").val($("#division").val());
		$("#modificarProductoProveedor").attr('data-destino','resultadoDivision');
		$("#modificarProductoProveedor").attr('data-opcion', 'comboProveedor');
		$("#opcion").val('division');
		$("#cantidad").removeAttr('disabled');
		$("#codigoIngreso").removeAttr('disabled');
		
		abrir($("#modificarProductoProveedor"),event,false);
	}
	
	$("#cantidad").attr('disabled',true);
	$("#codigoIngreso").attr('disabled',true);
});

$("#modificar").click(function(){
	$("#cantidad").removeAttr("disabled");
	$("#unidad").removeAttr("disabled");
	$("#aregarDivision").attr("disabled",true);	
	$("#cantidad").focus();
	var val = $("#cantidad").val();
	$("#cantidad").val("");
	$("#cantidad").val(val);
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
});

$("#modificarProductoProveedor").submit(function(e) {
    e.preventDefault();
    $(".alertaCombo").removeClass("alertaCombo");
    var error = false;  

	valor = $("#detalleItem #cantidad").val();
    
    if (isNaN (valor)) {
        error = true;        
        $("#detalleItem #cantidad").addClass("alertaCombo");
        $("#estado").html("La cantidad a registrar debe ser un valor numérico sin caracteres.").addClass('alerta');
    }

    if (valor <= 0) {
        error = true;        
        $("#detalleItem #cantidad").addClass("alertaCombo");
        $("#estado").html("La cantidad a registrar debe ser mayor a 0").addClass('alerta');
    } 

    if (valor > 999999.99) {
        error = true;
        $("#detalleItem #cantidad").addClass("alertaCombo");
        $("#estado").html("La cantidad a registrar no puede ser mayor a 999999.99").addClass('alerta');
    }

    if ($.trim($("#detalleItem #cantidad").val()) == "" ) {
        error = true;
        $("#detalleItem #cantidad").addClass("alertaCombo");
        $("#estado").html("Por favor revise los datos obligatorios.").addClass("alerta");
    } 

    if ($.trim($("#detalleItem #unidad").val()) == "" ) {
        error = true;
        $("#detalleItem #unidad").addClass("alertaCombo");
        $("#estado").html("Por favor revise los datos obligatorios.").addClass("alerta");
    } 
    
    if (!error){
    	$("#modificarProductoProveedor").attr('data-destino', 'detalleItem');    	
        $("#modificarProductoProveedor").attr('data-opcion', 'actualizarRegistro');        
        ejecutarJson($(this));
    }
});



</script>
