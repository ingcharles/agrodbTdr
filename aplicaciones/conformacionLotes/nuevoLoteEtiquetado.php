<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';

$conexion = new Conexion();
$cl = new ControladorLotes();
$ca = new ControladorAdministrarCaracteristicas();
$usuario=$_SESSION['usuario'];
$fecha_registro = date('Y-m-d');

?>

<div id="estado"></div>
<header>
	<h1>Nueva Generación de Etiquetado</h1>

</header>

<form id="nuevoLoteEtiquetado" data-rutaAplicacion="conformacionLotes">
	<input type="hidden" id="opcion" value="" name="opcion">
	<fieldset>
		<legend>Datos de ingreso:</legend>
		<div data-linea="1">
			<div id="dRegistroConformados">			
				<?php 
				echo '<table style="width:100%" id="tablaListaLotes" >				
				<thead>
				<tr>
					<th>Seleccionar</th>
					<th>ID</th>
					<th>Lote Nro.</th>
					<th>Código Lote</th>
					<th>Fecha Conformación</th>
					
					<th>Producto</th>					
					<th>Cantidad</th>
					
					<th>pais</th>
					<th>tipo_pordu</th>					
					<th>exportador</th>
					<th>idp</th>
				</tr>
				</thead>
				<tbody>
				<tr></tr>';
				
				    $conexion->ejecutarConsulta("begin;");
			
				    $resProducto = $cl->obtenerProductosLotes($conexion, $usuario);
				    
				    while ($fila=pg_fetch_assoc($resProducto)){
				        $productoLote.=$fila['id_producto'].",";
				    }
				    
				    $trimProductos = rtrim($productoLote,",");
				    
					$registro = $cl->obtenerLotes($conexion,$usuario);
					
					
					$res=$ca->obtenerFormulario($conexion, "nuevoProductoProveedor");
					$formulario=pg_fetch_assoc($res);					
					
					$valor=pg_num_rows($resProducto);		
										   
					if($valor>0){		
					
					while ($fila = pg_fetch_assoc($registro)){
					
						echo '<tr style="text-align:center">'.
								"<td>
								<input type=radio name=select value=male onchange='conformarEtiqueta()'>
			 					</td>".
							 	"<td style=text-align:center>".$fila['id_lote']."</td>".
							 	"<td style=text-align:center>".$fila['numero_lote']."</td>".
							 	"<td style=text-align:center>".$fila['codigo_lote']."</td>".
							 	"<td style=text-align:center>".date('Y-m-d',strtotime($fila['fecha_conformacion']))."</td>".
							 	//"<td style=text-align:center>".$fila['id_variedad']."</td>".
							 	"<td style=text-align:center>".$fila['producto']."</td>".
							 	//"<td style=text-align:center>".$fila['variedad']."</td>".//////////////
								"<td style=text-align:center>".$fila['cantidad']."</td>".
								//"<td style=text-align:center>".$fila['tipo']."</td>".
								"<td style=text-align:center>".$fila['pais']."</td>".
								"<td style=text-align:center>".$fila['tipo_producto']."</td>".
								//"<td style=text-align:center>".$fila['producto']."</td>".								
								"<td style=text-align:center>".$fila['nombre_exportador']."</td>".
								"<td style=text-align:center>".$fila['id_producto']."</td>
					 		</tr>";			
					}
					
					}
					
				echo '</tbody>
				</table>';
				
				$conexion->ejecutarConsulta("commit;");
				
				?>	
			
				<div style="text-align:center" id="mensajeErrorConformarLote"></div>
			</div>			
		</div>		
	</fieldset>
	
	<fieldset>
			<legend>Generar Etiqueta:</legend>
			<div data-linea="1">
				<label for="numeroLote">Lote Número</label>
				<input type="text" id="numeroLote" name="numeroLote" disabled="disabled">				
				<input type="hidden" id="serieLote" name="serieLote" >
		
			</div>
			<div data-linea="1">
				<label for="loteCodigo">Código Lote:</label>
				<input type="text" id="loteCodigo" name="loteCodigo" disabled="disabled">
			</div>
			
			<div data-linea="2">
				<label for="fechaEtiqueta"> Etiquetado:</label>
				<input type="text" id="fechaEtiqueta" name="fechaEtiqueta" disabled="disabled">
			</div>
			
			<div data-linea="2">
				<label for="cantidad">Cantidad Lote:</label>
				<input type="text" id="cantidad" name="cantidad" disabled="disabled">
			</div>
			
			<div data-linea="3">
				<label for="peso" id="lbPeso">Peso:</label>
				<!-- <input type="text" onPaste="var e=this; setTimeout(function(){alert(e.value);}, 4);" id="peso" name="peso" disabled="disabled" -->
				<input type="text" id="peso" name="peso" disabled="disabled">
			</div>
			
			<div data-linea="3">
				<label for="nroEtiqueta" id="lbEtiqueta">Etiquetas por Lote:</label>
				<input type="text" id="nroEtiqueta" name="nroEtiqueta" readonly>
			</div>
			
			<div id="contenedorPlantilla" style="width:100%">
				<!-- div data-linea="4" id="divTamanioEtiqueta" >
    				<label for="tamanioEtiqueta" >Tamaño hoja:</label>
    				<select id="tamanioEtiqueta" name="tamanioEtiqueta" >
    					<option value="A4">Impresión Hoja A4</option>
    					<option value="Etiqueta">Impresión etiqueta zebra</option>
    				</select>				
				</div -->
			</div>
			
			
			
			<div id="mensajeError" style="width:100%; margin-top:10px; text-align: center;"></div>
			
			
			
				
	</fieldset>
	<input type="hidden" id="usuario" name="usuario" value=<?php echo $usuario?>>
	<input type="hidden" id="idLote" name="idLote">
	<input type="hidden" id="tipo" name="tipo">
	<input type="hidden" id="pais" name="pais">
	<input type="hidden" id="tipoProducto" name="tipoProducto">
	<input type="hidden" id="idProducto" name="idProducto">	
	<input type="hidden" id="producto" name="producto">
	<input type="hidden" id="exportador" name="exportador">
	<input type="hidden" id="variedad" name="variedad">	
	<button type="submit" id="generarEtiqueta" class="guardar" disabled="disabled">Generar Etiqueta</button>
</form>
<style>

.alertaCombo2{
color: red;
font-weight: 550;
border: 1px solid rgba(215, 0, 0, 0.75);
box-shadow:inset 0px 0px 2px 0px rgba(255, 0, 0, 0.75);*/
width:35%;
}
</style>
<script type="text/javascript">

$("document").ready(function(e){

	$("#peso").attr('maxlength','8');
	
	$("#peso").keyup(function(event){

		if($(this).val().indexOf('.')!=-1){         
	        if($(this).val().split(".")[1].length > 2){  
	            this.value = this.value.substring(0,this.value.length-1);                        
	        }  
	     }	   
		numeroEtiquetas();
		
	});	

	
	$("#peso").mouseover(function(event){	
		numeroEtiquetas();
	});	

	$("#generarEtiqueta").mouseover(function(event){	
		numeroEtiquetas();
	});	


	$("#tablaListaLotes").mouseover(function(event){	
		numeroEtiquetas();
	});	
	
	distribuirLineas();

	$("#divTamanioEtiqueta").hide();
	
});


function conformarEtiqueta(){
	
	$('#tablaListaLotes input:radio:checked').each(function(){
		
		$("#fechaEtiqueta").val("<?php echo $fecha_registro;?>");
		
		$("#peso").numeric();
		$("#peso").removeAttr("disabled","disabled");
		$("#peso").focus();
		$("#generarEtiqueta").removeAttr("disabled","disabled");

		var array = $(this).parent().siblings().map(function(){        
		      return $(this).text().trim();
		 }).get(); 

		$("#numeroLote").val(array[1]);
		$("#loteCodigo").val(array[2]);
		$("#cantidad").val(array[5]);
		$("#idLote").val(array[0]);		
		//$("#tipo").val(array[7]);
		$("#pais").val(array[6]);
		$("#tipoProducto").val(array[7]);
		$("#producto").val(array[4]);
		$("#exportador").val(array[8]);
		$("#idProducto").val(array[9]);	

		$("#peso").val("").removeAttr("readonly");
		$("#lbPeso").html("Peso:");

		numeroEtiquetas();

	    var str =$("#producto").val();
		var producto;
		var index;

		var data ="producto="+$("#idProducto").val()+"&nPorducto="+str;		
	    $.ajax({
	        type: "POST",
	        data: data,        
	        url: "aplicaciones/conformacionLotes/comprobarEtiqueta.php",
	        dataType: "json",
	        success: function(msg) {
	        	if(msg.estado=="exito"){
	        		$(msg.mensaje).each(function(i){
		        		$("#contenedorPlantilla").html(this.contenido);		
		        		distribuirLineas();
		        		$("#estado").html("");
	        		});	
	        	} else{
	        		mostrarMensaje(msg.mensaje,"FALLO");
	        		$("#generarEtiqueta").attr("disabled",true)
	        		$("#peso").attr("disabled",true)
	        		$("#nroEtiqueta").attr("disabled",true)
	        		$("#contenedorPlantilla").html("");
	        	}
	            	                       
	        },
	        error: function(msg){            
	        	$("#estado").html(msg).addClass("alerta");          
	        }
	    });
	    
	});	
}

function numeroEtiquetas(){
	
	var valor =$("#producto").val();
	var producto;
	var index;	
	if(valor!=""){
		if ($("#peso").val() != "" ) {
			var valor = parseFloat($("#nuevoLoteEtiquetado #peso").val());		
			var peso=valor;
			var cantidad=parseFloat($("#cantidad").val());
			if (!isNaN (peso)) {
				if(peso<=0){
					$("#nroEtiqueta").val("0");
				} else{
					
					if(peso<=cantidad){
					var total= parseInt(cantidad/peso);			
					$("#nroEtiqueta").val(total);
					} else{
						$("#nroEtiqueta").val("0");				
					}
				}
			}		
	        	        
	    } else{
		    $("#nroEtiqueta").val("");
	    }
		
	}
}

	$("#nuevoLoteEtiquetado").submit(function(event){
		event.preventDefault();		 		
				
	    $(".alertaCombo2").removeClass("alertaCombo2");
	    var error = false;
		if ($.trim($("#nuevoLoteEtiquetado #peso").val()) == "" ) {
			error = true;
	        $("#nuevoLoteEtiquetado #peso").addClass("alertaCombo2");
	        mostrarMensaje("Por favor revise los campos obligatorios.","FALLO");
		} else{
			var peso= parseFloat($("#peso").val());
			var cantidad= parseFloat($("#cantidad").val());
	
			if (peso > cantidad) {
				error = true;			
		        $("#nuevoLoteEtiquetado #peso").addClass("alertaCombo2");
		        $("#estado").html("El Peso por saco no puede ser mayor a la Cantidad del Lote.").addClass('alerta');	       
			}
	
			var valor = $("#detalleItem #peso").val();
		    
		    if (isNaN (valor)) {
		        error = true;        
		        $("#detalleItem #peso").addClass("alertaCombo");
		        $("#estado").html("El peso del saco debe ser un valor numérico sin caracteres ni letras.").addClass('alerta');
		    }
	
		    if (valor <= 0) {
		        error = true;        
		        $("#detalleItem #peso").addClass("alertaCombo");
		        $("#estado").html("El peso debe ser mayor a 0").addClass('alerta');
		    } 


		    var str =$("#producto").val();
			var producto;
			var index;
			if(str!=""){
				producto = str.toLowerCase();
				index = producto.indexOf("cacao");
				if(index>=0){
					if (valor > 999.99) {
				        error = true;
				        $("#detalleItem #peso").addClass("alertaCombo");
				        $("#estado").html("La cantidad a registrar no puede ser mayor a 999.99").addClass('alerta');
				    }
				}
			}
	
		    
		} 

		if (!error){
			$("#numeroLote").removeAttr("disabled","disabled");
			$("#loteCodigo").removeAttr("disabled","disabled");
			$("#fechaEtiqueta").removeAttr("disabled","disabled");
			$("#cantidad").removeAttr("disabled","disabled");
			$("#nroEtiqueta").removeAttr("disabled","disabled");
			
			
	    	$("#nuevoLoteEtiquetado").attr('data-destino', 'detalleItem');    	
	        $("#nuevoLoteEtiquetado").attr('data-opcion', 'guardarNuevoLoteEtiquetado');
	        $("#opcion").val("guardarEtiqueta");	        	        
	        abrir($("#nuevoLoteEtiquetado"), event, false);
	        
	    }
	    
	});

	
	
</script>
