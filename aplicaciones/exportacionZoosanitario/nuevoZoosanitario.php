<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorZoosanitarioExportacion.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();
$ci = new ControladorZoosanitarioExportacion();

$fecha1= date('Y-m-d - H-i-s');
$fecha = str_replace(' ', '', $fecha1);

//Obtener lista de paises
$paises = $cc->listarSitiosLocalizacion($conexion,'PAIS');

//$mediosTransporte = $cc->listarMediosTrasporte($conexion);
$uso = $cc->listarUsos($conexion);
$tiempo = $cc->listaRangoEdadesAnimal($conexion);
$unidadesMedida = $cc->listarUnidadesMedida($conexion);
	
//Obtener listado de países habilitados para el operador
$qPaisOperador = $ci -> listarPaisesAutorizadosOperador($conexion);

while($fila = pg_fetch_assoc($qPaisOperador)){
	$paisAutorizado[]= array(idPais=>$fila['id_pais'], nombre=>$fila['nombre_pais']);
}

//Lista de productos SA
$productoZoo = $ci -> listarProductos($conexion);

//Obtener listado de Puertos Ecuador
$paisOrigen = pg_fetch_assoc($cc->obtenerIdLocalizacion($conexion, 'ECUADOR', 'PAIS'));

$qPuertoEcuador = $cc->listarPuertosPorPais($conexion, $paisOrigen['id_localizacion']);


while ($fila = pg_fetch_assoc($qPuertoEcuador)){
	$puertoEcuador[] =  array(idPuerto=>$fila['id_puerto'], nombre=>$fila['nombre_puerto'], pais=>$fila['id_pais']);
}

//Obtener Sitios
$iSitios = $cr -> listarAreasOperador($conexion,$_SESSION['usuario']);

?>
<header>
	<h1>Nueva exportación</h1>
</header>

<form id='nuevoZoosanitario' data-rutaAplicacion='exportacionZoosanitario' data-opcion='comboPuertos' data-destino="comboPuertoEmbarque" data-accionEnExito="ACTUALIZAR">
	
	<div id="estado"></div>
	<div class="pestania">
	
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $_SESSION['usuario']?>" />
	<input type=hidden id="fecha" name="fecha" value="<?php echo $fecha;?>" />
	
		<fieldset>
			<legend>Información del importador</legend>
				<div data-linea="1">			
				<label>Nombre importador</label> 
					<input type="text" id="nombreImportador" name="nombreImportador" placeholder="Ej: Indaves..." data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/ ]+$" />
				</div>
			
				<div data-linea="2">
				<label>País destino</label>
					<select id="paisImportador" name="paisImportador">
						<option value="">Seleccione....</option>
						<?php 
							foreach ($paisAutorizado as $pais){
								echo '<option value="' . $pais['idPais'] . '">' . $pais['nombre'] . '</option>';
							}
						?>
					</select> 
				</div>
				
				<div data-linea="2">
				<label>Dirección</label> 
					<input type="text" id="direccionImportador" name="direccionImportador" placeholder="Ej: Calle D N°123" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" />
				</div> 
		</fieldset>
		
		<fieldset>
			<legend>Información del exportador</legend>
				<div data-linea="3">
					<label>Representante técnico</label> 
						<input type="text" id="representanteTecnico" name="representanteTecnico" placeholder="Ej: Juan Pérez" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
				
				<div data-linea="4">
					<label>País embarque</label>
						<input type="text" id="paisEmbarque" name="paisEmbarque" placeholder="Ecuador" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü. ]+$"  disabled="disabled"/> 
				</div>
				
				<div id="comboPuertoEmbarque"></div>
				<div data-linea="5">
					<label>Puerto embarque</label>
						 <select id="puertoEmbarque" name="puertoEmbarque">
							<option value="">Seleccione....</option>
							<?php  
								foreach ($puertoEcuador as $puertoEmbarqueE){
									echo '<option value="' . $puertoEmbarqueE['idPuerto'] . '" >' . $puertoEmbarqueE['nombre'] . '</option>';
								}
							?>
						</select>
				</div>
				
				<div data-linea="5">	
				<label>Medio de transporte</label> 
					<select id="medioTransporte" name="medioTransporte">
						<option value="" >Seleccione....</option>
						<option value="Aereo">Aereo</option>
						<option value="Marítimo">Marítimo</option>
						<option value="Terrestre">Terrestre</option>
					</select>
				</div>
				
				<div data-linea="6">
					<label>Uso producto</label> 
							<select id="usoProducto" name="usoProducto" >
							<option value="" selected="selected">Seleccione....</option>
							<?php 
								while($fila = pg_fetch_assoc($uso)){
								echo '<option value="' . $fila['id_uso'] . '">' . $fila['nombre_uso'] . '</option>';
								}
							?>
							</select>
				</div>
				
				<div data-linea="7">
					<label>Bultos</label>
						<input type="text" step="0.1" id="bultos" name="bultos" placeholder="Ej: 2" data-er="^[0-9]+(\.[0-9]{1,2})?$" title="Ejemplo: 12"/>
				</div>
				
				<div data-linea="7">
					<label>Descripción</label>
					<input type="text" id="descripcion" name="descripcion" placeholder="Ej: descripción uno" />
				</div>
				
		</fieldset>
		
		<fieldset>
			<legend>Información de inspección</legend>
			<div data-linea="9">
				<label>Código de sitio</label>
					<select id="tipoSitio" name="tipoSitio" >
						<option value="">Sitio....</option>
						<?php  
							foreach ($iSitios as $sitios){
							echo '<option value="' . $sitios['codigoSitio'] . '">' . $sitios['nombreSitio'] . '</option>';
							}
						?>
					</select>
			</div>	
				
			<div data-linea="9">
				
				<label>Fecha de inspección</label>	
					<input type="text" name="fechaInspeccion" id="fechaInspeccion" required="required"/>
					
			</div>
				
			<div data-linea="8">
			<label>Observación</label>	
				<input type="text" name="observacion" id="observacion" placeholder="Ej: observación"/>
			</div>
			
		</fieldset>
	</div>
	
	
	<div class="pestania">
		
		<fieldset>
			<legend>Detalle de Productos</legend>
				<div data-linea="9">
					<label>Producto</label> 
					<select id="producto" name="producto" >
						<option value="">Producto....</option>	
					</select>	

					<input type="hidden" id="nombreProducto" name="nombreProducto" />	
				</div>
				
				<div data-linea="9">	
					<label>Raza</label> 
						<input type="text" id="raza" name="raza" placeholder="Ej: Raza AAA" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü. ]+$" title="Ejemplo: Raza AAA"/>
				</div>
				
				<div data-linea="10">	
					<label>Sexo</label> 
						<select id="sexo" name="sexo" >
								<option value="" >Seleccione....</option>
								<option value="Masculino">Masculino</option>
								<option value="Femenino">Femenino</option>
						</select>
				</div>
				
				<div data-linea="10">	
					<label>Edad</label> 
					<select id="edad" name="edad">
						<option value="" selected="selected">Seleccione....</option>
						<?php 
							while($fila = pg_fetch_assoc($tiempo)){
							echo '<option value="' . $fila['id_rango_edad'] . '">' . $fila['nombre'] . '</option>';
							}
						?>
					</select>
				</div>
				
				<div data-linea="12">	
					<label>Cantidad física</label> 
						<input type="text" step="0.1" id="cantidadFisica" name="cantidadFisica" placeholder="Ej: 12" data-er="^[0-9]+(\.[0-9]{1,2})?$" title="Ejemplo: 12"/>
				</div>
				
				<div data-linea="12">
					<label>Unidad física</label>	
					<select id="unidadFisica" name="unidadFisica" >
							<option value="" selected="selected">Seleccione....</option>
								<?php 
								while($fila = pg_fetch_assoc($unidadesMedida)){
								echo '<option value="' . $fila['id_unidad_medida'] . '">' . $fila['nombre'] . '</option>';
								}
								?>
					</select>
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
									<th>Producto</th>
									<th>Raza</th>
									<th>Sexo</th>
									<th>Edad</th>
									<th>Cantidad</th>
									<th>Unidad</th>
								<tr>
							</thead> 
							
							<tbody id="productos">
							</tbody>
							
						</table>
					</div>
	
		</fieldset>
	</div>
	
	<div class="pestania">	
			<fieldset id="documentosSA">
				<legend>Documentación requerida para Solicitud de Certificado Zoosanitario</legend>
					<div data-linea="1">
						<label>Permiso de Importación del país de destino:</label>
							<input type="file" name="permisoImportacionPaisDestino" id="permisoImportacionPaisDestino" accept="application/pdf"/>
							<input type="hidden" id="archivoPermisoImportacionPaisDestino" name="archivoPermisoImportacionPaisDestino" value="0"/> 
					<br />
						<label>Factura de mercancía:</label>
						<input type="file" name="facturaMercancia" id="facturaMercancia" accept="application/pdf"/>
						<input type="hidden" id="archivoFacturaMercancia" name="archivoFacturaMercancia" value="0"/>
					<br />
						<label>Manifiesto de carga:</label>
						<input type="file" name="manifiestoCarga" id="manifiestoCarga" accept="application/pdf"/>
						<input type="hidden" id="archivoManifiestoCarga" name="archivoManifiestoCarga" value="0"/>
					
					</div>
			</fieldset>
			
			<p class="nota">Por favor revise que la información ingresada sea correcta. Una vez enviada no podrá ser modificada.</p>
			<button type="submit" onclick="chequearCamposExportacion()" class="guardar">Guardar solicitud</button> 
			
	</div>
</form>
	
<script type="text/javascript">
	var array_puerto = <?php echo json_encode($puerto); ?>;
	var array_producto_zoo = <?php echo json_encode($productoZoo); ?>;

	$("#paisEmbarque").change(function(event){
		$("#nuevoZoosanitario").attr('data-opcion','comboPuertos');
		$("#nuevoZoosanitario").attr('data-destino','comboPuertoEmbarque');
		abrir($("#nuevoZoosanitario"),event,false); //Se ejecuta ajax, busqueda de puertos			 		
	});

	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));	
		construirValidador();
		$('#estado').html('Solicitud de certificado zoosanitario de exportación.').addClass('correcto');

		$("#fechaInspeccion").datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
	});

	$("#paisImportador").change(function(){	
		 	sproductozoo = '0'; 
  			sproductozoo = '<option value="">Seleccione...</option>';
  			for(var i=0;i<array_producto_zoo.length;i++){ 
  					 if ($("#paisImportador").val()==array_producto_zoo[i]['id_pais']){     
   					 sproductozoo += '<option value="'+array_producto_zoo[i]['id_producto']+'">'+array_producto_zoo[i]['nombre_producto']+'</option>';
   			}     
  		}    
    	 $('#producto').html(sproductozoo);
    	 $('#producto').removeAttr("disabled");
	});

	$("#producto").change(function(){	
		$('#nombreProducto').val($("#producto option:selected").text());
		
		});

	$("#nuevoZoosanitario").submit(function(event){
		$("#nuevoZoosanitario").attr('data-opcion','guardarNuevoZoosanitario');
		$("#nuevoZoosanitario").attr('data-destino','detalleItem');
		event.preventDefault();
		abrir($("#nuevoZoosanitario"),event,false); //Se ejecuta ajax, busqueda de puertos
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

		if(!$.trim($("#producto").val())){
			error = true;
			$("#producto").addClass("alertaCombo");
		}
			
		if (error == true){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			$("#estado").html("").removeClass('alerta');
			if($("#productos #r_"+$("#producto").val()).length==0){
					$("#productos").append("<tr id='r_"+$("#producto").val()+"' ><td><button type='button' onclick='quitarProductos(\"#r_"+$("#producto").val()+"\")' class='menos'>Quitar</button></td><td>"+$("#producto option:selected").text()+"<input id='hIdProducto' name='hIdProducto[]' value='"+$("#producto").val()+"' type='hidden'><input id='hNombreProducto' name='hNombreProducto[]' value='"+$("#producto option:selected").text()+"' type='hidden'></td><td>"+$("#raza").val()+"<input id='hRaza' name='hRaza[]' value='"+$("#raza").val()+"' type='hidden'></td><td>"+$("#sexo").val()+"<input id='hSexo' name='hSexo[]' value='"+$("#sexo").val()+"' type='hidden'></td><td>"+$("#edad").val() +"<input id='hEdad' name='hEdad[]' value='"+$("#edad").val()+"' type='hidden'></td><td>"+$("#cantidadFisica").val()+"<input id='hCantidad' name='hCantidad[]' value='"+$("#cantidadFisica").val()+"' type='hidden'></td><td>"+$("#unidadFisica").val()+"<input id='hUnidades' name='hUnidades[]' value='"+$("#unidadFisica").val()+"' type='hidden'></td></tr>");
				}
		}
	}
	
	function chequearCamposExportacion(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false; 

		if(!$.trim($("#nombreImportador").val()) || !esCampoValido("#nombreImportador")){
			error = true;
			$("#nombreImportador").addClass("alertaCombo");
		}

		if(!$.trim($("#paisImportador").val()) || !esCampoValido("#paisImportador")){
			error = true;
			$("#paisImportador").addClass("alertaCombo");
		}

		if(!$.trim($("#direccionImportador").val()) || !esCampoValido("#direccionImportador")){
			error = true;
			$("#direccionImportador").addClass("alertaCombo");
		}

		if(!$.trim($("#representanteTecnico").val()) || !esCampoValido("#representanteTecnico")){
			error = true;
			$("#representanteTecnico").addClass("alertaCombo");
		}

		if(!$.trim($("#puertoEmbarque").val())){
			error = true;
			$("#puertoEmbarque").addClass("alertaCombo");
		}

		if(!$.trim($("#medioTransporte").val())){
			error = true;
			$("#medioTransporte").addClass("alertaCombo");
		}

		if(!$.trim($("#usoProducto").val())){
			error = true;
			$("#usoProducto").addClass("alertaCombo");
		}

		if(!$.trim($("#bultos").val()) || !esCampoValido("#bultos")){
			error = true;
			$("#bultos").addClass("alertaCombo");
		}

		if(!$.trim($("#descripcion").val()) || !esCampoValido("#descripcion")){
			error = true;
			$("#descripcion").addClass("alertaCombo");
		}

		if(!$.trim($("#archivoPermisoImportacionPaisDestino").val())){
			error = true;
			$("#archivoPermisoImportacionPaisDestino").addClass("alertaCombo");
		}


		if (!error){
			if($('#hIdProducto').length == 0 ){
				$("#estado").html("Por favor ingrese uno o varios productos").addClass("alerta");
			}else{
				ejecutarJson(form);
				$("#estado").html("Los datos han sido actualizados satisfactoriamente.").addClass('correcto');
			}
		}else{
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}
	}

	
	///////////////////////////////////////////// ADMINISTRACION DE DOCUMENTOS ////////////////////////////////////////////////////
	
	//ZOOSANITARIO
	$('#permisoImportacionPaisDestino').change(function(event){
		
		$("#estado").html('');
		var archivo = $("#permisoImportacionPaisDestino").val();
		var extension = archivo.split('.');

		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			subirArchivo('permisoImportacionPaisDestino',$("#identificador").val()+'_archivoPermisoImportacionPaisDestino_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/zoosanitario/archivosAdjuntos', 'archivoPermisoImportacionPaisDestino');
		}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#permisoImportacionPaisDestino').val('');
		}
	});
	

	$('#facturaMercancia').change(function(event){
		
		$("#estado").html('');
		var archivo = $("#facturaMercancia").val();
		var extension = archivo.split('.');

		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			subirArchivo('facturaMercancia',$("#identificador").val()+'_archivoFacturaMercancia_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/zoosanitario/archivosAdjuntos', 'archivoFacturaMercancia');
		}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#facturaMercancia').val('');
		}
	});

	$('#manifiestoCarga').change(function(event){
		
		$("#estado").html('');
		var archivo = $("#manifiestoCarga").val();
		var extension = archivo.split('.');

		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			subirArchivo('manifiestoCarga',$("#identificador").val()+'_archivoManifiestoCarga_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/zoosanitario/archivosAdjuntos', 'archivoManifiestoCarga');
		}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#manifiestoCarga').val('');
		}
	});

</script>



