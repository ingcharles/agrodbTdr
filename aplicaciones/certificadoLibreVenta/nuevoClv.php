<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorClv.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();
$clv = new ControladorClv();

$fecha1= date('Y-m-d - H-i-s');
$fecha = str_replace(' ', '', $fecha1);

$productosInocuidad = $cc->listarProductosInocuidad($conexion);


?>
<header>
	<h1>Nuevo Certificado de Libre Venta</h1>
</header>

	<form id='nuevoClv' data-rutaAplicacion='certificadoLibreVenta' data-opcion='guardarNuevoClv' data-accionEnExito="ACTUALIZAR">
	
	<div id="estado"></div>
	
	
	
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $_SESSION['usuario']?>" />
	<input type=hidden id="fecha" name="fecha" value="<?php echo $fecha;?>" />
	
		
		
		<fieldset>
			<legend>Información del producto</legend>							
				<div data-linea="7">
					<label>Tipo produto</label> 
					<select id="tipoProducto" name="tipoProducto">
					<option value="0">Seleccione....</option> 
						<option value="IAP">Plaguicida</option> 
						<option value="IAV">Veterinario</option> 
					</select>
				</div>	
				<div data-linea="7">
					<label>Tipo</label> 
						<input type="text" id="tipoCertificado" name="tipoCertificado" disabled="disabled"/>
				</div>																				
	
	            <div id="id_producto_plaguicida">
				    <div  data-linea="15">
						<label>Producto</label> 
						<select id="producto_plaguicida" name="producto_plaguicida">
							<option value="">Seleccione....</option>
							<?php 
								foreach ($productosInocuidad as $productosInocuidadArray){
									if($productosInocuidadArray['id_area']=='IAP')
										echo '<option data-idProducto="'.$productosInocuidadArray['id_producto'].'" data-codigo="'.$productosInocuidadArray['codigo_producto'].'" data-subpartida="'.$productosInocuidadArray['subpartida'].'" data-clasificacion="'.$productosInocuidadArray['clasificacion'].'" data-producto="'.$productosInocuidadArray['producto'].'" data-nombreCientifico="'.$productosInocuidadArray['nombre_cientifico'].'" data-composicion="'.$productosInocuidadArray['composicion'].'" 
											data-formulacion="'.$productosInocuidadArray['formulacion'].'" value="' . $productosInocuidadArray['id_producto'] . '">' . $productosInocuidadArray['producto'] . '</option>';							
								}
							?>
					    </select>					
						<input type="hidden" id="nombre_producto_plaguicida" name="nombre_producto_plaguicida" />						
					</div>
					
					<div data-linea="23">							
						<textarea  id="ccomposicion_plaguicida" name="ccomposicion_plaguicida" rows="2" cols="100" disabled="disabled"></textarea>															
					</div>	
							    							 
				    <div data-linea="24">			
						<label>Ingrediente activo</label> 
						<input type="text" id="ingrediente_activo" name="ingrediente_activo"/>																	
					</div>
					
					<div data-linea="25">
						<label>Concentración</label> 
						<input type="text" id="concentracion" name="concentracion"/>
					</div>
					
					<button type="button" onclick="agregar_plaguicida()" class="mas">Agregar productos</button>		
									  
					<table>
						<thead>
							<tr>
								<th></th>
								<th>Ingrediente activo</th>
								<th>Concentración</th>																	
							<tr>
						</thead> 							
						<tbody id="producto_plaguicida_det">
						</tbody>						
					</table>
					
				</div>	
				
				<div id="id_producto_veterinario">	
				 	<div data-linea="15" >
						<label>Producto</label> 
						<select id="producto_veterinario" name="producto_veterinario">
						<option value="">Seleccione....</option>
						<?php 
							foreach ($productosInocuidad as $productosInocuidadArray){
								if($productosInocuidadArray['id_area']=='IAV')
									echo '<option data-idProducto="'.$productosInocuidadArray['id_producto'].'" data-codigo="'.$productosInocuidadArray['codigo_producto'].'" data-subpartida="'.$productosInocuidadArray['subpartida'].'" data-clasificacion="'.$productosInocuidadArray['clasificacion'].'" data-producto="'.$productosInocuidadArray['producto'].'" data-nombreCientifico="'.$productosInocuidadArray['nombre_cientifico'].'" data-composicion="'.$productosInocuidadArray['composicion'].'" 
											data-formulacion="'.$productosInocuidadArray['formulacion'].'" value="' . $productosInocuidadArray['id_producto'] . '">' . $productosInocuidadArray['producto'] . '</option>';							
							}
						?>
					    </select>					
						<input type="hidden" id="nombre_producto_veterinario" name="nombre_producto_veterinario" />						
					</div>
			    
				</div>
				
		    </fieldset> 
	   	    	   	
		<fieldset>
		    <legend>Documentación requerida para Solicitud de Certificado de Libre Comercio</legend>
		      <div  data-linea="1">
			      <label>Registro del producto:</label>
			      <input type="file" name="registroProducto" id="registroProducto" accept="application/pdf"/>
			      <input type="hidden" id="archivoRegistroProducto" name="archivoRegistroProducto" value="0"/> 
		      </div>		      
		  <p class="nota">Por favor revise que la información ingresada sea correcta. Una vez enviada no podrá ser modificada.</p>	
	   </fieldset> 
	    
		<button id="btn_guardar" type="submit" name="btn_guardar" class="guardar">Guardar</button>

  </form>

<script type="text/javascript">	

	var array_producto_inocuidad = <?php echo json_encode($productosInocuidad); ?>;	

	$("#nuevoClv").submit(function(event){ 
		chequearCamposGuardar(this);
	 });
	
	//array_producto_inocuidad tipoProducto
	$("#tipoProducto").change(function(){
		//-- Carga datos del combo plaguicida
		if($("#tipoProducto").val()=='IAP'){
			$('#tipoCertificado').val('Formulador');
			$("#id_producto_plaguicida").show();
			$("#id_producto_veterinario").hide();
							
		}
		//-- Carga datos del combo veterinario     
		if($("#tipoProducto").val()=='IAV'){
			visibleProductos('IAV');
			$('#tipoCertificado').val('Fabricante');
			$("#id_producto_plaguicida").hide();
			$("#id_producto_veterinario").show();

		}
	});
	
	$("#producto_plaguicida").change(function(){
		if($("#producto_plaguicida").val()!=''){			
			$('#ccomposicion_plaguicida').val($('#producto_plaguicida option:selected').attr('data-composicion'));	
			$('#nombre_producto_plaguicida').val($('#producto_plaguicida option:selected').text());	
						
		}
	});


	$("#producto_veterinario").change(function(){
		if($("#producto_veterinario").val()!=''){      
			$('#nombre_producto_veterinario').val($('#producto_veterinario option:selected').text());
		}
	});
	
	
	function agregar_plaguicida(){

	    var codigo = $("#ingrediente_activo").val().replace(/ /g,'')+$("#concentracion").val().replace(/ /g,'');

	    if($("#ingrediente_activo").val()!="" && $("#concentracion").val()!="" )
    		if($("#producto_plaguicida_det #r_"+codigo).length==0)
	       		$("#producto_plaguicida_det").append("<tr id='r_"+codigo+"'><td><button type='button' onclick='quitar_plaguicida(\"#r_"+codigo+"\")' class='menos'>Quitar</button></td><td>"+$("#ingrediente_activo").val()+"<input id='hingrediente_activo' name='hingrediente_activo[]' value='"+$("#ingrediente_activo").val()+"' type='hidden'></td><td>"+$("#concentracion").val()+"<input id='hconcentracion' name='hconcentracion[]' value='"+$("#concentracion").val()+"' type='hidden'></td></tr>");	
		
		$("#ingrediente_activo").val('');
		$("#concentracion").val('');
		
	}

	function quitar_plaguicida(fila){	
		$("#producto_plaguicida_det tr").eq($(fila).index()).remove();
	}
	
	/////////////////////// VALIDACION ////////////////////////

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
	//Activar la subida de archivos documentales para el CLV
	$('#registroProducto').change(function(event){		  
		  $("#estado").html('');
		  var archivo = $("#registroProducto").val();
		  var extension = archivo.split('.');

		  if(extension[extension.length-1].toUpperCase() == 'PDF'){
		   subirArchivo('registroProducto',$("#identificador").val()+'_archivoRegistroProducto_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/certificadoLibreVenta/archivosAdjuntos', 'archivoRegistroProducto');
		  }else{
		   $("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
		   $('#registroProducto').val('');
		  }
  	});

	function chequearCamposGuardar(form){
		$("#estado").html("").addClass('correcto');
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false; 
        //campos 
          
		if(!$.trim($("#tipoProducto").val())){
			error = true;
			$("#tipoProducto").addClass("alertaCombo");
		}

		if(!$.trim($("#tipoDocumento").val())){
			error = true;
			$("#tipoDocumento").addClass("alertaCombo");
		}

		if($("#archivoRegistroProducto").val()=='0'){
			error = true;
			$("#archivoRegistroProducto").addClass("alertaCombo");
		}
		
		if (!error){
			abrir(form,event,false); 		
		}else{			
			$("#estado").html("Por favor revise el formato de la información ingresada").addClass('alerta');
			return false;
		}
		
	}
	$(document).ready(function(){			
		distribuirLineas();
		construirAnimacion($(".pestania"));	
		construirValidador();
		$("#id_producto_plaguicida").hide();
		$("#id_producto_veterinario").hide();
		$('#estado').html('Certificado de Libre Venta').addClass('correcto');
		
	});
  		
</script>