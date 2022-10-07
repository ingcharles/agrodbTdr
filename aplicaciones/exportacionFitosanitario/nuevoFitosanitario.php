<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorFitosanitario.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();
$fi = new ControladorFitosanitario();

$fecha1= date('Y-m-d - H-i-s');
$fecha = str_replace(' ', '', $fecha1);

//Obtener listado de países de operaciones
$qPaisOperador = $fi -> listarPaisesAutorizadosOperador($conexion);

while($fila = pg_fetch_assoc($qPaisOperador)){
	$paisAutorizado[]= array(idPais=>$fila['id_pais'], nombre=>$fila['nombre_pais']);
}

//Obtener listado de proveedores de países de operaciones
$proveedores = $fi -> listaProveedores($conexion);

//Obtener listado de productos de proveedores de países de operaciones
$productosFito = $fi -> listaProductos($conexion);

$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');

$qProductoOperador = $fi -> listarProductosOperador($conexion, $_SESSION['usuario']);

while($fila = pg_fetch_assoc($qProductoOperador)){
	//AGREGAR LOS CAMPOS NUEVOS DE UNIDAD(CENTIMETROS CUBICO, KILOGRAMO BRUTO, LITRO, METRO CUBICO REAL, NUMERO DE UNIDADES) Y PESO(tonelada, kg, lb)
	$productoAutorizado[]= array(idProducto=>$fila['id_producto'], nombre=>$fila['nombre_comun'], idArea=>$fila['tipo'], idPais=>$fila['id_localizacion'], tipoProducto=>$fila['nombre_tipo'], certificadoSemillas=>$fila['certificado_semillas'], licenciaMagap=>$fila['licencia_magap']);
}

//Obtener listado de Regimen Aduanero
$qRegimenAduanero = $cc -> listarRegimenAduanero($conexion);

while ($fila = pg_fetch_assoc($qRegimenAduanero)){
	$regimen[] =  array(idRegimen=>$fila['id_regimen'], descripcion=>$fila['descripcion']);
}

//Obtener listado de Moneda
$qMoneda = $cc -> listarMoneda($conexion);

while ($fila = pg_fetch_assoc($qMoneda)){
	$moneda[] =  array(idMoneda=>$fila['id_moneda'], nombre=>$fila['nombre']);
}

$paisOrigen = pg_fetch_assoc($cc->obtenerIdLocalizacion($conexion, 'ECUADOR', 'PAIS'));

$qPuertoEcuador = $cc->listarPuertosPorPais($conexion, $paisOrigen['id_localizacion']);


while ($fila = pg_fetch_assoc($qPuertoEcuador)){
	$puertoEcuador[] =  array(idPuerto=>$fila['id_puerto'], nombre=>$fila['nombre_puerto'], pais=>$fila['id_pais']);
}

?>
<header>
	<h1>Nuevo fitosanitario de exportación</h1>
</header>

	<form id='nuevaFitosanitario' data-rutaAplicacion='exportacionFitosanitario' data-opcion='comboPuertos' data-destino="comboPuertoEmbarque" data-accionEnExito="ACTUALIZAR">
	
	<div id="estado"></div>
	
	<div class="pestania">
	
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $_SESSION['usuario']?>" />
	<input type=hidden id="fecha" name="fecha" value="<?php echo $fecha;?>" />
	
		<fieldset>
			<legend>Información del Importador - Recibe produducto Exterior</legend>
				<div data-linea="1">			
				<label>Nombre importador</label> 
					<input type="text" id="nombreImportador" name="nombreImportador" placeholder="Ej: Empresa exterior..." data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/ ]+$" />
				</div>
			
				<div data-linea="2">
				<label>País destino</label>
					<select id="paisImportador" name="paisImportador">
						<option value="">Seleccione....</option>
						<?php 
							foreach ($paisAutorizado as $pais){
								echo '<option value="' . $pais['idPais'] . '" data-nombre= "' . $pais['nombre'] . '">' . $pais['nombre'] . '</option>';
							}
						?>
					</select> 					
					<input type="hidden" id="nomPaisImportador"  name="nomPaisImportador" />
				</div>
				
				<div data-linea="3">
				<label>Dirección importador</label> 
					<input type="text" id="direccionImportador" name="direccionImportador" placeholder="Ej: Calle D N°123" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" />
				</div> 
		</fieldset>
		
		<fieldset>
			<legend>Información de Embarque</legend>
							
				<div data-linea="4">
					<label>Nombre del Embarcador</label> 
						<input type="text" id="nombreEmbarcador" name="nombreEmbarcador" placeholder="Ej: Embarcador" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
				
				<div data-linea="5">
					<label>Nombre de la Marca</label> 
						<input type="text" id="nombreMarca" name="nombreMarca" placeholder="Ej: Marcas" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
										
				<div data-linea="6">
					<label>Pto Destino</label>
					    <input type="text" id="nombreOperadorVacunador" name="nombreOperadorVacunador" value="Ecuador" disabled="disabled"/>					    						
				</div>
							
				<div data-linea="7">
					<label>Pto Embarque</label>
						 <select id="puertoDestino" name="puertoDestino">
							<option value="">Seleccione....</option>
							<?php  
								foreach ($puertoEcuador as $puertoEmbarqueE){
									echo '<option value="' . $puertoEmbarqueE['idPuerto'] . '" data-pais="'.$puertoEmbarqueE['pais'].'">' . $puertoEmbarqueE['nombre'] . '</option>';
								}
							?>							
						</select>						
						<input type="hidden" id="nombrePuertoDestino"  name="nombrePuertoDestino" />
				</div> 
				
				<div data-linea="8">
					<label>Transporte</label>			
					<select id="transporte" name="transporte">
					<option value="">Seleccione........</option>
					<?php
					    $medioTransporte = $cc->listarMediosTrasporte($conexion);
					    while ($fila = pg_fetch_assoc($medioTransporte)){
					    	echo '<option value="' . $fila['tipo'] . '">' . $fila['tipo'] . '</option>';
					    }
					    					
					?>
				</select>	
				</div>
				
				<div data-linea="8">
				<label>Fecha Embarque</label>				
					<input type="text" id="fecha_embarque" name="fecha_embarque" />
				</div>
				
				<div data-linea="9">
					<label>No.Viaje</label> 
					<input type="text" id="numViaje" name="numViaje" placeholder="Ej: 123" />
				</div>				
		</fieldset>		
	</div>
	
	<div class="pestania">
		<fieldset>
			<legend>Información del Tratamiento</legend>
			<div data-linea="10">
				<label>Tratamiento realizado</label> 
					<input type="text" id="tratamientoRealizado" name="tratamientoRealizado" placeholder="Ej: Tratamiento relizado" />
			</div>
			<div data-linea="11">
				<label>Duración</label> 
					<input type="text" id="duracion" name="duracion" placeholder="Ej: 2" />
			</div>
			<div data-linea="11">
				<label>Temperatura</label> 
					<input type="text" id="temperatura" name="temperatura" placeholder="Ej: 10" />
			</div>
			<div data-linea="12">
				<label>Fecha realizacion</label>				
					<input type="text" id="fecha_realizacion" name="fecha_realizacion" />
				</div>
			<div data-linea="12">
				<label>Producto químico</label> 
					<input type="text" id="productoQuimico" name="productoQuimico" placeholder="Ej: Producto químico" />
			</div>
			<div data-linea="13">
				<label>Concentración</label> 
					<input type="text" id="concentracion" name="concentracion" placeholder="Ej: Concentración química" />
			</div>
		</fieldset>
		
		<fieldset>
			<legend>Información de Inspección</legend>
			<div data-linea="14">
				<label>Provincia</label>				
				<select id="provincia" name="provincia">
					<option value="">Seleccione....</option>
					<?php 
						$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
						foreach ($provincias as $provinciaArray){
							echo '<option value="' . $provinciaArray['codigo'] . '">' . $provinciaArray['nombre'] . '</option>';							
						}
					?>
				</select>
				<input type="hidden" id="nombreProvincia" name="nombreProvincia" />	 
			</div>
			<div data-linea="14">				
				<label>Cantón</label>
				<select id="ciudad" name="ciudad" disabled="disabled">
				</select>
				<input type="hidden" id="nombreCiudad" name="nombreCiudad" />	
			</div>
			<div data-linea="15">
				<label>No.Inspeccion</label> 
					<input type="text" id="numInspeccion" name="numInspeccion" placeholder="Ej: 2265" />
			</div>
			<div data-linea="16">
				<label>Observacion</label> 
					<input type="text" id="observacion" name="observacion" placeholder="Ej: Observacion de la inspección" />
			</div>
		</fieldset>		
	</div>
	
	<div class="pestania">	
		<fieldset>
			<legend>Detalle de Productos</legend>
			    <div data-linea="14">			
					<label>Proveedor</label> 
					<select id="proveedor" name="proveedor" disabled="disabled">
				    </select>					
					<input type="hidden" id="nombreProveedor" name="nombreProveedor" />					
				</div>
							
				<div data-linea="15">
					<label>Producto</label> 
					<select id="productofito" name="productofito" disabled="disabled">
				    </select>					
					<input type="hidden" id="nombreProductofito" name="nombreProductofito" />						
				</div>
				
				<div id="musaceas_div" data-linea="16">	
					<label id="lmusaceas">Musaceas </label> 
						<input type="text" step="0.1" id="musaceas" name="musaceas" placeholder="Ej: 10" title="Ejemplo: 999.99"/>
				</div>
				
				<div data-linea="17">	
					<label id="Lbulteos">Bultos </label> 
						<input type="text" step="0.1" id="bultos" name="bultos" placeholder="Ej: 5" data-er="^[0-9]+(\.[0-9]{1,2})?$" title="Ejemplo: 999.99"/>
				</div>
				
				<div data-linea="17">	
				    <label id="LUnidad">Unidad </label>					
					<select id="unidades" name="unidades">
						<option value="">Seleccione........</option>
							<?php
								$unidadMedida = $cc->listarUnidadesMedida($conexion);
							    while ($fila = pg_fetch_assoc($unidadMedida)){
							    	echo '<option value="' . $fila['id_unidad_medida'] . '">' . $fila['nombre'] . '</option>';
							    }
							    					
							?>
						</select>
				</div>
				
				<div data-linea="18">	
					<label id="lcantidad">Cantidad (Kg.)</label> 
						<input type="text" step="0.1" id="cantidad" name="cantidad" placeholder="Ej: 1" data-er="^[0-9]+(\.[0-9]{1,2})?$" title="Ejemplo: 999.99"/>
				</div>
														
				<button type="button" onclick="agregarProductos()" class="mas">Agregar productos</button>
		</fieldset>
					 
		<fieldset>
			<legend>Productos agregados</legend>
					 <div>
						<table>
							<thead>
								<tr>
									<th></th>
									<th>Proveedores</th>
									<th>Producto</th>									
									<th>Bultos</th>
									<th>Unidades</th>
									<th>Cantidad</th>
									<th>Musáceas</th>
								<tr>
							</thead> 							
							<tbody id="productos">
							</tbody>
							
						</table>
					</div>
	
		</fieldset>
	</div>
	<div class="pestania">
	<fieldset>
	    <legend>Documentación requerida para Solicitud de Certificado Fitosanitario</legend>
	      <div id="doc1" data-linea="1">
		      <label>Reporte de inspección:</label>
		      <input type="file" name="reporteInspeccion" id="reporteInspeccion" accept="application/pdf"/>
		      <input type="hidden" id="archivoReporteInspeccion" name="archivoReporteInspeccion" value="0"/> 
	      </div>
	      <div id="doc2" data-linea="2"> 
		      <label>Manifiesto de carga:</label>
		      <input type="file" name="manifiestoCarga" id="manifiestoCarga" accept="application/pdf"/>
		      <input type="hidden" id="archivoManifiestoCarga" name="archivoManifiestoCarga" value="0"/>
	      </div>
	      <div id="doc3" data-linea="3"> 
		      <label>Certificado de calidad de Cacao:</label>
		      <input type="file" name="certificadoCalidadCacao" id="certificadoCalidadCacao" accept="application/pdf"/>
		      <input type="hidden" id="archivoCertificadoCalidadCacao" name="archivoCertificadoCalidadCacao" value="0"/>	     
	     </div>
	     <div id="doc4" data-linea="4"> 
		      <label>Manifiesto Unidad de banano:</label>
		      <input type="file" name="manifiestoUnidadBanano" id="manifiestoUnidadBanano" accept="application/pdf"/>
		      <input type="hidden" id="archivoManifiestoUnidadBanano" name="archivoManifiestoUnidadBanano" value="0"/>	     
	     </div>
	     <div id="doc5" data-linea="5"> 
		      <label>Registro de operador de Unibananao:</label>
		      <input type="file" name="registroOperadorUnibananao" id="registroOperadorUnibananao" accept="application/pdf"/>
		      <input type="hidden" id="archivoRegistroOperadorUnibananao" name="archivoRegistroOperadorUnibananao" value="0"/>	     
	     </div>
	   </fieldset>
	   
		   <p class="nota">Por favor revise que la información ingresada sea correcta. Una vez enviada no podrá ser modificada.</p>
			
			<button id="btn_guardar" type="button" name="btn_guardar" onclick="chequearCamposExportacion()" >Guardar</button> 
	</div>
			
	
  </form>

<script type="text/javascript">	
			
	var array_regimen = <?php echo json_encode($regimen); ?>;
	var array_proveedor = <?php echo json_encode($proveedores); ?>;
	var array_producto_fito = <?php echo json_encode($productosFito); ?>;
	var array_puerto = <?php echo json_encode($puerto); ?>;
	var array_canton= <?php echo json_encode($cantones); ?>;
	var magap = 0;
	var wproducto = '<option value="">Seleccione Producto....</option>';

	$("#btn_guardar").click(function(event){
		 $('#nuevaFitosanitario').attr('data-opcion','guardarNuevaFitosanitario');
		 $('#nuevaFitosanitario').attr('data-destino','res_sitio');	     
		 abrir($("#nuevaFitosanitario"),event,false); 						  
		 abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);			 		 	
	 });

	$("#paisEmbarque").change(function(event){
		$("#nuevaImportacion").attr('data-opcion','comboPuertos');
		$("#nuevaImportacion").attr('data-destino','comboPuertoEmbarque');
		abrir($("#nuevaImportacion"),event,false); //Se ejecuta ajax, busqueda de puertos			 		
	});

	$("#provincia").change(function(){
    	scanton ='0';
		scanton = '<option value="">Cantón...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provincia").val()==array_canton[i]['padre']){
		    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			    }
	   		}
	    $('#ciudad').html(scanton);
	    $("#ciudad").removeAttr("disabled");

        if($("#provincia").val()!='0')
        	$('#nombreProvincia').val( $("#provincia option:selected").text());
        		    
	});

	$("#ciudad").change(function(){
		if($("#ciudad").val()!='0')
        	$('#nombreCiudad').val( $("#ciudad option:selected").text());
	});

	$(document).ready(function(){
		sregimen = '<option value="">Seleccione....</option>';
		for(var i=0; i<array_regimen.length; i++){
		    sregimen += '<option value="'+array_regimen[i]['idRegimen']+'">'+array_regimen[i]['descripcion']+'</option>';
	    }

		$("#fecha_embarque").datepicker({
		      changeMonth: true,
		      changeYear: true
		});

		$("#fecha_realizacion").datepicker({
		      changeMonth: true,
		      changeYear: true
		});		
	    
	    $('#regimenAduanero').html(sregimen);
	    
		distribuirLineas();
		construirAnimacion($(".pestania"));	
		construirValidador();

		//Revisión Documental
		$("#doc1").hide();	
		$("#doc2").hide();	
		$("#doc3").hide();
		$("#doc4").hide();
		$("#doc5").hide();
		$("#musaceas_div").hide();
					
		//Fin Revisión Documental

		$('#estado').html('Exportacion fitosanitario.').addClass('correcto');

		
	});
	
	$("#paisImportador").change(function(){	
		sproveedor = '0';	
		sproveedor = '<option value="">Seleccione...</option>';
		for(var i=0;i<array_proveedor.length;i++){	
			if ($("#paisImportador").val()==array_proveedor[i]['id_pais']){	    
				sproveedor += '<option value="'+array_proveedor[i]['identificador_operador']+'"> '+array_proveedor[i]['identificador_operador']+' - '+ array_proveedor[i]['razon_social']+  ' </option>';
			}			  
		}	   
	    $('#proveedor').html(sproveedor);
	    $("#proveedor").removeAttr("disabled");
	    
	    if ($("#paisImportador").val() != ''){
			$("#nomPaisImportador").val($('#paisImportador option:selected').attr('data-nombre'));				
		}else{
			alert("Debe elegir el mismo país de origen.");	
		}
			
	});
	
	$("#proveedor").change(function(){		
		sproductofito = '0';	
		sproductofito = '<option value="">Seleccione...</option>';
		for(var i=0;i<array_producto_fito.length;i++){	
			if ($("#proveedor").val()==array_producto_fito[i]['identificador_operador']){	    
				sproductofito += '<option value="'+array_producto_fito[i]['id_producto']+'">'+array_producto_fito[i]['nombre_producto']+'</option>';
			}			  
		}	   
	    $('#productofito').html(sproductofito);
	    $('#productofito').removeAttr("disabled");
	    	   
	});

	$("#productofito").change(function(){
		// Condiciones Fitos para los diferentes Tipos productos
		// 1=>Si el producto es diferente de CACAO y BANANO
		// 2=>Si el producto es CACAO  
		// 3=>Si el producto es BANANO 
		
		//$('#productofito').removeAttr("disabled");
		
		if($("#productofito").val()!=''){			
			$("#doc1").show();
			$("#doc2").show();					
            $varProducto =  $("#productofito option:selected").text(); 
			//Si el producto es CACAO            
			if($varProducto == 'cacao'){
				$("#doc3").show();
				$("#musaceas_div").hide();
			}			
			//Si el producto es BANANO
			if($varProducto == 'banano'){
				$("#doc4").show();
				$("#doc5").show();
				$("#musaceas_div").show();							
			}

			if ($varProducto != 'banano')
				$("#musaceas_div").hide();	
						
		}

	});

	$("#puertoDestino").change(function(){	
		$('#nombrePuertoDestino').val($("#puertoDestino option:selected").text());
	});

	$("#nuevaImportacion").submit(function(event){
		$("#nuevaImportacion").attr('data-opcion','guardarNuevaImportacion');
		$("#nuevaImportacion").attr('data-destino','detalleItem');
		event.preventDefault();
		chequearCamposExportacion(this);		
	});

	///////////////////////////// AGREGAR PRODUCTOS //////////////////////////////

	function agregarProductos(){		
		chequearCamposProducto();
		
	}
	
	function quitarProductos(fila){
		/*if($("#productos tr").eq($(fila).index()).attr("data-magap") != ""){
			magap = 0;
		}*/
				
		$("#productos tr").eq($(fila).index()).remove();
	}

	/////////////////////// VALIDACION ////////////////////////

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCamposProducto(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
					
		if (error == true){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			$("#estado").html("").removeClass('alerta');
			if($("#productos #r_"+$("#producto").val()).length==0){
				var codigo = $("#productofito").val();
				$("#productos").append("<tr id='r_"+codigo.replace(/ /g,'')+"'><td><input id='hCodProductos' name='hCodProductos[]' value='"+codigo+"' type='hidden'></td><td>"+$("#proveedor").val()+"<input id='hproveedor' name='hproveedor[]' value='"+$ ("#proveedor").val()+"'type='hidden'></td><td>"+$("#productofito option:selected").text()+"<input id='hproducto' name='hproducto[]' value='"+$("#productofito option:selected").text()+"'type='hidden'></td><td>"+$("#bultos").val()+"<input id='hbultos' name='hbultos[]' value='"+$("#bultos").val()+"' type='hidden'></td><td>"+$("#unidades option:selected").text()+"<input id='hproducto' name='hproducto[]' value='"+$("#unidades option:selected").text()+"'type='hidden'></td><td>"+$("#cantidad").val()+"<input id='hcantidad' name='hcantidad[]' value='"+$("#cantidad").val()+"' type='hidden'></td><td>"+$("#musaceas").val()+"<input id='hmusaceas' name='hmusaceas[]' value='"+$("#musaceas").val()+"' type='hidden'></td><td><input id='hunidades' name='hunidades[]' value='"+$ ("#unidades").val()+"' type='hidden'></td><td><button type='button' onclick='quitarProductos(\"#r_"+codigo.replace(/ /g,'')+"\")' class='menos'>Quitar</button></td></tr>");
				$("#bultos").val('');
				$("#cantidad").val('');
				$("#unidades").val('');				
				$("#productofito").val('');
				$("#musaceas").val('');
			}
		}
	}	

	function chequearCamposExportacion(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false; 
        //campos
		if(!$.trim($("#nombreImportador").val()) || !esCampoValido("#nombreImportador")){
			error = true;
			$("#nombreImportador").addClass("alertaCombo");
		}

		if(!$.trim($("#direccionImportador").val()) || !esCampoValido("#direccionImportador")){
			error = true;
			$("#direccionImportador").addClass("alertaCombo");
		}

		if(!$.trim($("#nombreEmbarcador").val()) || !esCampoValido("#nombreEmbarcador")){
			error = true;
			$("#nombreEmbarcador").addClass("alertaCombo");
		}

		if(!$.trim($("#nombreMarca").val()) || !esCampoValido("#nombreMarca")){
			error = true;
			$("#nombreMarca").addClass("alertaCombo");
		}

		if(!$.trim($("#numViaje").val()) || !esCampoValido("#numViaje")){
			error = true;
			$("#numViaje").addClass("alertaCombo");
		}

		if(!$.trim($("#tratamientoRealizado").val()) || !esCampoValido("#tratamientoRealizado")){
			error = true;
			$("#tratamientoRealizado").addClass("alertaCombo");
		}

		if(!$.trim($("#duracion").val()) || !esCampoValido("#duracion")){
			error = true;
			$("#duracion").addClass("alertaCombo");
		}

		if(!$.trim($("#temperatura").val()) || !esCampoValido("#temperatura")){
			error = true;
			$("#temperatura").addClass("alertaCombo");
		}

		if(!$.trim($("#productoQuimico").val()) || !esCampoValido("#productoQuimico")){
			error = true;
			$("#productoQuimico").addClass("alertaCombo");
		}

		if(!$.trim($("#concentracion").val()) || !esCampoValido("#concentracion")){
			error = true;
			$("#concentracion").addClass("alertaCombo");
		}

		if(!$.trim($("#numInspeccion").val()) || !esCampoValido("#numInspeccion")){
			error = true;
			$("#numInspeccion").addClass("alertaCombo");
		}

		if(!$.trim($("#observacion").val()) || !esCampoValido("#observacion")){
			error = true;
			$("#observacion").addClass("alertaCombo");
		}

		if(!$.trim($("#paisImportador").val())){
			error = true;
			$("#paisImportador").addClass("alertaCombo");
		}

		if(!$.trim($("#transporte").val())){
			error = true;
			$("#transporte").addClass("alertaCombo");
		}
		
		if(!$.trim($("#puertoDestino").val())){
			error = true;
			$("#puertoDestino").addClass("alertaCombo");
		}

		if(!$.trim($("#provincia").val())){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}

		//fecha
		
		if(!$.trim($("#fecha_embarque").val())){
			error = true;
			$("#fecha_embarque").addClass("alertaCombo");
		}

		if(!$.trim($("#fecha_embarque").val())){
			error = true;
			$("#fecha_embarque").addClass("alertaCombo");
		}

		if(!$.trim($("#fecha_realizacion").val())){
			error = true;
			$("#fecha_realizacion").addClass("alertaCombo");
		}
		
/*
		if($("#tipoCertificado option:selected").attr('data-area') == 'IAP'){
			if($("#archivoFacturaProformaIAP").val() == 0){
				error = true;
				$("#facturaProformaIAP").addClass("alertaCombo");
			}

			if($("#archivoNotaPedidoIAP").val() == 0){
				error = true;
				$("#notaPedidoIAP").addClass("alertaCombo");
			}
		}

		if($("#tipoCertificado option:selected").attr('data-area') == 'IAV'){
			if($("#archivoFacturaProformaIAV").val() == 0){
				error = true;
				$("#facturaProformaIAV").addClass("alertaCombo");
			}

			if($("#archivoNotaPedidoIAV").val() == 0){
				error = true;
				$("#notaPedidoIAV").addClass("alertaCombo");
			}

		}
		*/
		if (!error){
			if($('#hCodProductos').length == 0 ){
				$("#estado").html("Por favor ingrese uno o varios productos").addClass("alerta");
			}else{
				ejecutarJson(form);
				$("#estado").html("Los datos han sido actualizados satisfactoriamente.").addClass('correcto');
			}
		}else{
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}
	}

	//Activar la subida de archivos documentales para el fito
	$('#reporteInspeccion').change(function(event){		  
		  $("#estado").html('');
		  var archivo = $("#reporteInspeccion").val();
		  var extension = archivo.split('.');

		  if(extension[extension.length-1].toUpperCase() == 'PDF'){
		   subirArchivo('reporteInspeccion',$("#identificador").val()+'_archivoManifiestoCarga_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/fitosanitario/archivosAdjuntos', 'archivoManifiestoCarga');
		  }else{
		   $("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
		   $('#reporteInspeccion').val('');
		  }
  	});
  	
	$('#manifiestoCarga').change(function(event){		  
		  $("#estado").html('');
		  var archivo = $("#manifiestoCarga").val();
		  var extension = archivo.split('.');

		  if(extension[extension.length-1].toUpperCase() == 'PDF'){
		   subirArchivo('manifiestoCarga',$("#identificador").val()+'_archivoReporteInspeccion_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/fitosanitario/archivosAdjuntos', 'archivoReporteInspeccion');
		  }else{
		   $("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
		   $('#manifiestoCarga').val('');
		  }
    });

	$('#certificadoCalidadCacao').change(function(event){		  
		  $("#estado").html('');
		  var archivo = $("#certificadoCalidadCacao").val();
		  var extension = archivo.split('.');

		  if(extension[extension.length-1].toUpperCase() == 'PDF'){
		   subirArchivo('certificadoCalidadCacao',$("#identificador").val()+'_archivoCertificadoCalidadCacao_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/fitosanitario/archivosAdjuntos', 'archivoCertificadoCalidadCacao');
		  }else{
		   $("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
		   $('#certificadoCalidadCacao').val('');
		  }
	});

	$('#manifiestoUnidadBanano').change(function(event){		  
		  $("#estado").html('');
		  var archivo = $("#manifiestoUnidadBanano").val();
		  var extension = archivo.split('.');

		  if(extension[extension.length-1].toUpperCase() == 'PDF'){
		   subirArchivo('manifiestoUnidadBanano',$("#identificador").val()+'_archivoManifiestoUnidadBanano_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/fitosanitario/archivosAdjuntos', 'archivoManifiestoUnidadBanano');
		  }else{
		   $("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
		   $('#manifiestoUnidadBanano').val('');
		  }
	});

	$('#registroOperadorUnibananao').change(function(event){		  
		  $("#estado").html('');
		  var archivo = $("#registroOperadorUnibananao").val();
		  var extension = archivo.split('.');

		  if(extension[extension.length-1].toUpperCase() == 'PDF'){
		   subirArchivo('registroOperadorUnibananao',$("#identificador").val()+'_archivoRegistroOperadorUnibananao_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/fitosanitario/archivosAdjuntos', 'archivoRegistroOperadorUnibananao');
		  }else{
		   $("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
		   $('#registroOperadorUnibananao').val('');
		  }
	});

</script>