<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorImportaciones.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$ci = new ControladorImportaciones();

$fecha1= date('Y-m-d - H-i-s');
$fecha = str_replace(' ', '', $fecha1);


//Obtener lista de paises
$paises = $cc->listarSitiosLocalizacion($conexion,'PAIS');
	
//Obtener listado de países habilitados para el operador
$qPaisOperador = $ci -> listarPaisesAutorizadosOperador($conexion, $_SESSION['usuario']);

while($fila = pg_fetch_assoc($qPaisOperador)){
	$paisAutorizado[]= array(idPais=>$fila['id_pais'], nombre=>$fila['nombre_pais']);
}

$qProductoOperador = $ci -> listarProductosOperador($conexion, $_SESSION['usuario']);

while($fila = pg_fetch_assoc($qProductoOperador)){
	//AGREGAR LOS CAMPOS NUEVOS DE UNIDAD(CENTIMETROS CUBICO, KILOGRAMO BRUTO, LITRO, METRO CUBICO REAL, NUMERO DE UNIDADES) Y PESO(tonelada, kg, lb)
	$productoAutorizado[]= array(idProducto=>$fila['id_producto'], nombre=>$fila['nombre_comun'], idArea=>$fila['id_area'], idPais=>$fila['id_pais'], tipoProducto=>$fila['nombre_tipo'], certificadoSemillas=>$fila['certificado_semillas'], licenciaMagap=>$fila['licencia_magap']);
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

//Obtener listado de Puertos Ecuador
$paisOrigen = pg_fetch_assoc($cc->obtenerIdLocalizacion($conexion, 'ECUADOR', 'PAIS'));

$qPuertoEcuador = $cc->listarPuertosPorPais($conexion, $paisOrigen['id_localizacion']);


while ($fila = pg_fetch_assoc($qPuertoEcuador)){
	$puertoEcuador[] =  array(idPuerto=>$fila['id_puerto'], nombre=>$fila['nombre_puerto'], pais=>$fila['id_pais']);
}

?>
<header>
	<h1>Nueva importación</h1>
</header>

	<form id='nuevaImportacion' data-rutaAplicacion='importaciones' data-opcion='comboPuertos' data-destino="comboPuertoEmbarque" data-accionEnExito="ACTUALIZAR">
	
	<div id="estado"></div>
	
	<div class="pestania">
	
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $_SESSION['usuario']?>" />
	<input type=hidden id="fecha" name="fecha" value="<?php echo $fecha;?>" />
	
		<fieldset>
			<legend>Información del remitente</legend>
				<div data-linea="1">			
				<label>Nombre remitente</label> 
					<input type="text" id="nombreExportador" name="nombreExportador" placeholder="Ej: Roche..." data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/ ]+$" />
				</div>
			
				<div data-linea="2">
				<label>País origen</label>
					<select id="paisExportador" name="paisExportador">
						<option value="">Seleccione....</option>
						<?php 
							foreach ($paisAutorizado as $pais){
								echo '<option value="' . $pais['idPais'] . '">' . $pais['nombre'] . '</option>';
							}
						?>
					</select> 
					<input type="hidden" id="idPaisExportador"  name="idPaisExportador" />
				</div>
				
				<div data-linea="3">
				<label>Dirección</label> 
					<input type="text" id="direccionExportador" name="direccionExportador" placeholder="Ej: Calle D N°123" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" />
				</div> 
		</fieldset>
		
		<fieldset>
			<legend>Información de Embarque</legend>
				
				<div data-linea="4">
					<label>Nombre Embarcador</label> 
						<input type="text" id="nombreEmbarcador" name="nombreEmbarcador" placeholder="Ej: Juan Pérez" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü. ]+$" />
				</div>
				
				<div data-linea="5">
					<label>Régimen aduanero</label> 
						<select id="regimenAduanero" name="regimenAduanero">
							<option value="">Seleccione....</option>					
						</select> 
				</div>
				
				<div data-linea="6">
					<label>Moneda</label> 
						<select id="moneda" name="moneda">
							<option value="">Seleccione....</option>
							
							<?php  
								foreach ($moneda as $unidadMoneda){
									echo '<option value="' . $unidadMoneda['idMoneda'] . '">' . $unidadMoneda['nombre'] . '</option>';
								}
							?>
						</select> 
				</div>
				
				<div data-linea="7">
					<label>País Embarque</label>
						<select id="paisEmbarque" name="paisEmbarque">
							<option value="">Seleccione....</option>
							<?php 
								foreach ($paises as $pais){
									echo '<option value="' . $pais['codigo'] . '">' . $pais['nombre'] . '</option>';
								}
							?>
						</select> 
				</div>
				
				<div id="comboPuertoEmbarque"></div>
					<input type="hidden" id="nombrePuertoEmbarque"  name="nombrePuertoEmbarque" />
				
				<div data-linea="9">
					<label>Puerto Destino</label>
						 <select id="puertoDestino" name="puertoDestino">
							<option value="">Seleccione....</option>
							<?php  
								foreach ($puertoEcuador as $puertoEmbarqueE){
									echo '<option value="' . $puertoEmbarqueE['idPuerto'] . '" data-pais="'.$puertoEmbarqueE['pais'].'">' . $puertoEmbarqueE['nombre'] . '</option>';
								}
							?>
							<!-- INCLUIR COMO DATA-TIPO EL TIPO DE PUERTO: AEREO MARITIMO ETC -->
						</select>
						
						<input type="hidden" id="nombrePuertoDestino"  name="nombrePuertoDestino" />
				</div> 
				
				<div data-linea="10">
					<label>Medio transporte</label> 
					<select id="transporte" name="transporte" >
						<option value="" data-area="">Transporte....</option>
						<option value="Aéreo">Aéreo</option>
						<option value="Marítimo">Marítimo</option>
						<option value="Terrestre">Terrestre</option>
					</select>
				</div> 
		</fieldset>
	</div>
	
	
	
	<div class="pestania">
		
		<fieldset>
			<legend>Detalle de Productos</legend>
			
				<div data-linea="4">			
					<label>Tipo Certificado</label> 
					<select id="tipoCertificado" name="tipoCertificado" >
						<option value="" data-area="">Tipo de certificado....</option>
						<option value="Permiso Fitosanitario de Importación" data-area="SV">Permiso Fitosanitario de Importación</option>
						<option value="Permiso Zoosanitario de Importación" data-area="SA">Permiso Zoosanitario de Importación</option>
						<option value="Autorización para Importación de Plaguicidas" data-area="IAP">Autorización para Importación de Plaguicidas</option>
						<option value="Autorización para Importación de Productos Veterinarios" data-area="IAV">Autorización para Importación de Productos Veterinarios</option>
					</select>
					<input type="hidden" id="idTipoCertificado" name="idTipoCertificado" />
				</div>
				
				<!-- DEBE TENER EN DATA-UNIDAD Y DATA-PESO LOS VALORES PARA CAMBIAR LABELS lUnidades Y lPeso -->
				<div data-linea="7">
					<label>Producto</label> 
					<select id="producto" name="producto" >
						<option value="">Producto....</option>	
					</select>	

					<input type="hidden" id="nombreProducto" name="nombreProducto" />	
					
					<div id="productoLicencia"></div>
				</div>
				
				<div data-linea="8">	
					<label id="lUnidades">Cantidad </label> 
						<input type="text" step="0.1" id="unidades" name="unidades" placeholder="Ej: 10.56" data-er="^[0-9]+(\.[0-9]{1,2})?$" title="Ejemplo: 999.99"/>
				</div>
				
				<div data-linea="8">	
					<label id="lPeso">Peso (Kg.)</label> 
						<input type="text" step="0.1" id="peso" name="peso" placeholder="Ej: 10.56" data-er="^[0-9]+(\.[0-9]{1,2})?$" title="Ejemplo: 999.99"/>
				</div>
				
				<div data-linea="9">	
					<label>Valor FOB</label> 
						<input type="text" step="0.1" id="valorFob" name="valorFob" placeholder="Ej: 10.56" data-er="^[0-9]+(\.[0-9]{1,2})?$" title="Ejemplo: 999.99"/>
				</div>
				
				<div data-linea="9">	
					<label>Valor CIF</label> 
						<input type="text" step="0.1" id="valorCif" name="valorCif" placeholder="Ej: 10.56" data-er="^[0-9]+(\.[0-9]{1,2})?$" title="Ejemplo: 999.99"/>
				</div>
				
				<!-- MOSTRARSE SOLO PARA PRODUCTOS DE SA Y SV -->
				<div data-linea="10">	
					<label id="lLicenciaMagap">Licencia MAGAP</label> 
						<input type="text" id="licenciaMagap" name="licenciaMagap" data-er="^[0-9]+$" title="Ejemplo: 99999"/>
				</div>				
				
				<!-- MOSTRARSE SOLO PARA TIPO PRODUCTO SEMILLAS -->
				<div data-linea="11">	
					<label id="lRegistroSemillas">Registro semillas</label> 
						<input type="text" id="registroSemillas" name="registroSemillas" data-er="^[0-9]+$" title="Ejemplo: 99999"/>
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
									<th>Cantidad</th>
									<th>Peso</th>
									<th>Licencia MAGAP</th>
									<th>Registro Semillas</th>
								<tr>
							</thead> 
							
							<tbody id="productos">
							</tbody>
							
						</table>
					</div>
	
		</fieldset>
	</div>
	
	<div class="pestania">	
			<fieldset id="documentosSV">
				<legend>Documentación requerida para Permiso Fitosanitario</legend>
					<div data-linea="1">
						<label>Registro Semillas: </label>
							<input type="file" name="registroSemillasSV" id="registroSemillasSV" accept="application/pdf"/>
							<input type="hidden" id="archivoRegistroSemillasSV" name="archivoRegistroSemillasSV" value="0"/>
					</div>
			</fieldset>
			
			<fieldset id="documentosSA">
				<legend>Documentación requerida para Permiso Zoosanitario</legend>
					<div data-linea="1">
						<label>Certificado de predio de cuarentena: </label>
							<input type="file" name="certificadoPredioCuarentenaSA" id="certificadoPredioCuarentenaSA" accept="application/pdf"/>
							<input type="hidden" id="archivoCertificadoPredioCuarentenaSA" name="archivoCertificadoPredioCuarentenaSA" value="0"/> 
					</div>
					<div data-linea="2">
						<label>Informe de ganadería: </label>
							<input type="file" name="informeGanaderiaSA" id="informeGanaderiaSA" accept="application/pdf"/>
							<input type="hidden" id="archivoInformeGanaderiaSA" name="archivoInformeGanaderiaSA" value="0"/>
					</div>
			</fieldset>
			
			<fieldset id="documentosIAV">
				<legend>Documentación requerida para Productos Veterinarios</legend>
					<label>Factura proforma: </label>
						<input type="file" name="facturaProformaIAV" id="facturaProformaIAV" accept="application/pdf"/>
						<input type="hidden" id="archivoFacturaProformaIAV" name="archivoFacturaProformaIAV" value="0"/>
					<br />
					<label>Nota de pedido: </label>
						<input type="file" name="notaPedidoIAV" id="notaPedidoIAV" accept="application/pdf"/>
						<input type="hidden" id="archivoNotaPedidoIAV" name="archivoNotaPedidoIAV" value="0"/>
					<br />
					<label>Carta de autorización: </label>
						<input type="file" name="cartaAutorizacionIAV" id="cartaAutorizacionIAV" accept="application/pdf"/>
						<input type="hidden" id="archivoCartaAutorizacionIAV" name="archivoCartaAutorizacionIAV" value="0"/>
					<br />
			</fieldset>
			
			<fieldset id="documentosIAP">
				<legend>Documentación requerida para Plaguicidas</legend>
					<label>Factura proforma: </label>
						<input type="file" name="facturaProformaIAP" id="facturaProformaIAP" accept="application/pdf"/>
						<input type="hidden" id="archivoFacturaProformaIAP" name="archivoFacturaProformaIAP" value="0"/>
					<br />
					<label>Nota de pedido: </label>
						<input type="file" name="notaPedidoIAP" id="notaPedidoIAP" accept="application/pdf"/>
						<input type="hidden" id="archivoNotaPedidoIAP" name="archivoNotaPedidoIAP" value="0"/>
					<br />
					<label>Carta de autorización: </label>
						<input type="file" name="cartaAutorizacionIAP" id="cartaAutorizacionIAP" accept="application/pdf"/>
						<input type="hidden" id="archivoCartaAutorizacionIAP" name="archivoCartaAutorizacionIAP" value="0"/>
					<br />
			</fieldset>
			
			<p class="nota">Por favor revise que la información ingresada sea correcta. Una vez enviada no podrá ser modificada.</p>
			<button type="submit" class="guardar">Guardar solicitud</button> 
	</div>
</form>
	
<script type="text/javascript">
	var array_producto = <?php echo json_encode($productoAutorizado); ?>;
	var array_regimen = <?php echo json_encode($regimen); ?>;
	var array_puerto = <?php echo json_encode($puerto); ?>;
	var magap = 0;
	var wproducto = '<option value="">Seleccione Producto....</option>';

	/*$("#botonLicenciaMagap").click(function(event){
		$("#nuevaImportacion").attr('data-opcion','webServiceMagap');
		$("#nuevaImportacion").attr('data-destino','producto');

		alert('Su solicitud se realizará sólo para este grupo de productos por la Licencia de MAGAP.');
		
		abrir($("#nuevaImportacion"),event,false); //Se ejecuta ajax, busqueda de puertos			 		
	});*/

	$("#paisEmbarque").change(function(event){
		$("#nuevaImportacion").attr('data-opcion','comboPuertos');
		$("#nuevaImportacion").attr('data-destino','comboPuertoEmbarque');
		abrir($("#nuevaImportacion"),event,false); //Se ejecuta ajax, busqueda de puertos			 		
	});

	$(document).ready(function(){
		sregimen = '<option value="">Seleccione....</option>';
		for(var i=0; i<array_regimen.length; i++){
		    sregimen += '<option value="'+array_regimen[i]['idRegimen']+'">'+array_regimen[i]['descripcion']+'</option>';
	    }
	    $('#regimenAduanero').html(sregimen);
	    
		distribuirLineas();
		construirAnimacion($(".pestania"));	
		construirValidador();

		$("#lLicenciaMagap").hide();
		$("#licenciaMagap").hide();
		$("#lRegistroSemillas").hide();
		$("#registroSemillas").hide();

		$("#documentosSA").hide();
		$("#documentosSV").hide();
		$("#documentosIAV").hide();
		$("#documentosIAP").hide();

		$('#estado').html('Si posee una Licencia de MAGAP sólo podrá ingresar los productos de la misma.').addClass('correcto');
	});

	$("#paisExportador").change(function(){	
		if ($("#idPaisExportador").val() != $("#paisExportador option:selected").val() && $("#idPaisExportador").val() != ''){
			alert("Debe elegir el mismo país de origen.");
			$('select[name="paisExportador"]').find('option[value="'+$("#idPaisExportador").val()+'"]').prop("selected","selected");
		}else{	
			$("#idPaisExportador").val($("#paisExportador option:selected").val());
		    $('select[name="tipoCertificado"]').find('option[value=""]').prop("selected","selected");
			$('select[name="producto"]').find('option[value=""]').prop("selected","selected");
		}
	});

	$("#tipoCertificado").change(function(){	
		if ($("#idTipoCertificado").val() != $("#tipoCertificado option:selected").val() && $("#idTipoCertificado").val() != ''){
			alert("Debe elegir el mismo tipo de Certificado para su registro.");
			$('select[name="tipoCertificado"]').find('option[value="'+$("#idTipoCertificado").val()+'"]').prop("selected","selected");
		}else{	
			$("#idTipoCertificado").val($("#tipoCertificado option:selected").val());
				
			sproducto ='0';
			sproducto = '<option value="">Producto....</option>';
			if($("#paisExportador option:selected").val()!=''){
				//Carga lista de productos de inocuidad del catálogo aprobado
				for(var i=0; i<array_producto.length; i++){
					if ($("#tipoCertificado option:selected").attr('data-area')==array_producto[i]['idArea'] && $("#paisExportador option:selected").val()==array_producto[i]['idPais']){
						sproducto += '<option value="'+array_producto[i]['idProducto']+'" data-semilla="'+array_producto[i]['certificadoSemillas']+'" data-magap="'+array_producto[i]['licenciaMagap']+'">'+array_producto[i]['nombre']+'</option>';					
			    	}
				}
				
			}else{
				alert('Por favor elija un país de origen.');
				$('select[name="tipoCertificado"]').find('option[value=""]').prop("selected","selected");
			}
			
		    $('#producto').html(sproducto);

		    if($("#tipoCertificado option:selected").attr('data-area')=='SA'){
		    	$("#documentosSA").show();
		    }else if($("#tipoCertificado option:selected").attr('data-area')=='IAP'){
				$("#documentosIAP").show();
		    }else if($("#tipoCertificado option:selected").attr('data-area')=='IAV'){
				$("#documentosIAV").show();
		    }else if($("#tipoCertificado option:selected").attr('data-area')=='SV'){
				$("#documentosSV").show();
		    }
		}
	});

	/*$('#requiereLicenciaMagap').change(function() {
        if($(this).is(":checked")) {
        	$("#lLicenciaMagap").fadeIn();
			$("#licenciaMagap").fadeIn();
			$("#botonLicenciaMagap").fadeIn();
			//Eliminar valores del combo de productos para cargarlos del web service
			$('#producto').html('<option value="">Producto....</option>');
			//$('#producto').html(wproducto);
        }else{
        	$("#licenciaMagap").val('');
        	$("#lLicenciaMagap").hide();
			$("#licenciaMagap").hide();
			$("#botonLicenciaMagap").hide();
			//Carga valores de productos al combo según catálogo
			$('#producto').html(sproducto);

			$('#productos tr').each(function() {
				if($('#productos tr').attr('data-magap')!=''){
				   $(this).remove();
				}
			});
        }        
    });*/
	

	$("#producto").change(function(){	
		$('#nombreProducto').val($("#producto option:selected").text());
		
		if($("#tipoCertificado option:selected").attr('data-area') == 'SV' && $("#producto option:selected").attr('data-semilla').replace(/ /g,'').toUpperCase() == 'SI'){
			$("#lRegistroSemillas").fadeIn();
			$("#registroSemillas").fadeIn();
		}else{
			$("#lRegistroSemillas").hide();
			$("#registroSemillas").hide();
		}

		if(($("#tipoCertificado option:selected").attr('data-area') == 'SV' || $("#tipoCertificado option:selected").attr('data-area') == 'SA') && $("#producto option:selected").attr('data-magap').replace(/ /g,'').toUpperCase() == 'SI'){
			$("#lLicenciaMagap").fadeIn();
			$("#licenciaMagap").fadeIn();
		}else{
			$("#lLicenciaMagap").hide();
			$("#licenciaMagap").hide();
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

		$("#licenciaMagap").val('');
		$("#registroSemillas").val('');
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

		if(!$.trim($("#tipoCertificado").val())){
			error = true;
			$("#tipoCertificado").addClass("alertaCombo");
		}

		if(!$.trim($("#producto").val())){
			error = true;
			$("#producto").addClass("alertaCombo");
		}
		
		if(!$.trim($("#unidades").val()) || !esCampoValido("#unidades")){
			error = true;
			$("#unidades").addClass("alertaCombo");
		}

		if(!$.trim($("#peso").val()) || !esCampoValido("#peso")){
			error = true;
			$("#peso").addClass("alertaCombo");
		}

		if(!$.trim($("#valorFob").val()) || !esCampoValido("#valorFob")){
			error = true;
			$("#valorFob").addClass("alertaCombo");
		}

		if(!$.trim($("#valorCif").val()) || !esCampoValido("#valorCif")){
			error = true;
			$("#valorCif").addClass("alertaCombo");
		}

		//// Conexion a web service en caso de requerir ////
		////LICENCIA MAGAP
		if(($("#tipoCertificado option:selected").attr('data-area') == 'SV' || $("#tipoCertificado option:selected").attr('data-area') == 'SA') && $("#producto option:selected").attr('data-magap').replace(/ /g,'').toUpperCase() == 'SI'){
			if(!$.trim($("#licenciaMagap").val()) || !esCampoValido("#licenciaMagap")){
				error = true;
				$("#licenciaMagap").addClass("alertaCombo");
			}else{
				//Revisa valor en el arreglo de productos del web service
				
			}
		}

		////SEMILLAS
		if(($("#tipoCertificado option:selected").attr('data-area') == 'SV') && $("#producto option:selected").attr('data-semilla').replace(/ /g,'').toUpperCase() == 'SI'){
			if(!$.trim($("#registroSemillas").val()) || !esCampoValido("#registroSemillas")){
				error = true;
				$("#registroSemillas").addClass("alertaCombo");
			}else{
				
			}
		}


		if (error == true){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			$("#estado").html("").removeClass('alerta');
			if($("#productos #r_"+$("#producto").val()).length==0){
					$("#productos").append("<tr id='r_"+$("#producto").val()+"' data-magap='"+ $("#licenciaMagap").val() +"' data-semilla='"+ $("#registroSemillas").val() +"'><td><button type='button' onclick='quitarProductos(\"#r_"+$("#producto").val()+"\")' class='menos'>Quitar</button></td><td>"+$("#producto option:selected").text()+"<input id='hIdProducto' name='hIdProducto[]' value='"+$("#producto").val()+"' type='hidden'><input id='hNombreProducto' name='hNombreProducto[]' value='"+$("#producto option:selected").text()+"' type='hidden'></td><td>"+$("#unidades").val()+" "+$("#producto option:selected").attr("data-unidad")+"<input id='hUnidades' name='hUnidades[]' value='"+$("#unidades").val()+"' type='hidden'></td><td>"+$("#peso").val()+" Kg."+"<input id='hPeso' name='hPeso[]' value='"+$("#peso").val()+"' type='hidden'><input id='hValorFob' name='hValorFob[]' value='"+$("#valorFob").val()+"' type='hidden'><input id='hValorCif' name='hValorCif[]' value='"+$("#valorCif").val()+"' type='hidden'></td><td>"+$("#licenciaMagap").val()+"<input id='hLicenciaMagap' name='hLicenciaMagap[]' value='"+$("#licenciaMagap").val()+"' type='hidden'></td><td>"+$("#registroSemillas").val()+"<input id='hRegistroSemillas' name='hRegistroSemillas[]' value='"+$("#registroSemillas").val()+"' type='hidden'></td></tr>");
			}
		}
	}
	

	function chequearCamposExportacion(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false; 

		if(!$.trim($("#nombreExportador").val()) || !esCampoValido("#nombreExportador")){
			error = true;
			$("#nombreExportador").addClass("alertaCombo");
		}

		if(!$.trim($("#paisExportador").val())){
			error = true;
			$("#paisExportador").addClass("alertaCombo");
		}

		if(!$.trim($("#direccionExportador").val()) || !esCampoValido("#direccionExportador")){
			error = true;
			$("#direccionExportador").addClass("alertaCombo");
		}

		if(!$.trim($("#nombreEmbarcador").val()) || !esCampoValido("#nombreEmbarcador")){
			error = true;
			$("#nombreEmbarcador").addClass("alertaCombo");
		}

		if(!$.trim($("#regimenAduanero").val())){
			error = true;
			$("#regimenAduanero").addClass("alertaCombo");
		}

		if(!$.trim($("#moneda").val())){
			error = true;
			$("#moneda").addClass("alertaCombo");
		}

		if(!$.trim($("#paisEmbarque").val())){
			error = true;
			$("#paisEmbarque").addClass("alertaCombo");
		}

		if(!$.trim($("#puertoEmbarque").val())){
			error = true;
			$("#puertoEmbarque").addClass("alertaCombo");
		}

		if(!$.trim($("#puertoDestino").val())){
			error = true;
			$("#puertoDestino").addClass("alertaCombo");
		}

		if(!$.trim($("#transporte").val())){
			error = true;
			$("#transporte").addClass("alertaCombo");
		}

		if($("#tipoCertificado option:selected").attr('data-area') == 'SV'){
			if($("#archivoRegistroSemillasSV").val() == 0){
				error = true;
				$("#registroSemillasSV").addClass("alertaCombo");
			}
		}

		if($("#tipoCertificado option:selected").attr('data-area') == 'SA'){
			if($("#archivoInformeGanaderiaSA").val() == 0){
				error = true;
				$("#informeGanaderiaSA").addClass("alertaCombo");
			}

			if($("#archivoCertificadoPredioCuarentenaSA").val() == 0){
				error = true;
				$("#certificadoPredioCuarentenaSA").addClass("alertaCombo");
			}
		}

		if($("#tipoCertificado option:selected").attr('data-area') == 'IAP'){
			if($("#archivoFacturaProformaIAP").val() == 0){
				error = true;
				$("#facturaProformaIAP").addClass("alertaCombo");
			}

			if($("#archivoNotaPedidoIAP").val() == 0){
				error = true;
				$("#notaPedidoIAP").addClass("alertaCombo");
			}

			//NO SE VALIDA POR SER OPCIONAL
			/*if($("#archivoCartaAutorizacionIAP").val() == 0){
				error = true;
				$("#cartaAutorizacionIAP").addClass("alertaCombo");
			}*/
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

			//NO SE VALIDA POR SER OPCIONAL
			/*if($("#archivoCartaAutorizacionIAV").val() == 0){
				error = true;
				$("#cartaAutorizacionIAV").addClass("alertaCombo");
			}*/
		}

		if (!error){
			if($('#hIdProducto').length == 0 ){
				$("#estado").html("Por favor ingrese uno o varios productos").addClass("alerta");
			}else{
				ejecutarJson(form);
			}
		}else{
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}
	}

	

	///////////////////////////////////////////// ADMINISTRACION DE DOCUMENTOS ////////////////////////////////////////////////////
	
	//SANIDAD VEGETAL
	$('#registroSemillasSV').change(function(event){
		
		$("#estado").html('');
		var archivo = $("#registroSemillasSV").val();
		var extension = archivo.split('.');

		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			subirArchivo('registroSemillasSV',$("#identificador").val()+'archivoRegistroSemillasSV_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/importaciones/archivosAdjuntos', 'archivoRegistroSemillasSV');
		}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#registroSemillasSV').val('');
		}
	});

	//SANIDAD ANIMAL
	$('#certificadoPredioCuarentenaSA').change(function(event){
		
		$("#estado").html('');
		var archivo = $("#certificadoPredioCuarentenaSA").val();
		var extension = archivo.split('.');

		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			subirArchivo('certificadoPredioCuarentenaSA',$("#identificador").val()+'_archivoCertificadoPredioCuarentenaSA_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/importaciones/archivosAdjuntos', 'archivoCertificadoPredioCuarentenaSA');
		}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#certificadoPredioCuarentenaSA').val('');
		}
	});
	

	$('#informeGanaderiaSA').change(function(event){
		
		$("#estado").html('');
		var archivo = $("#informeGanaderiaSA").val();
		var extension = archivo.split('.');

		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			subirArchivo('informeGanaderiaSA',$("#identificador").val()+'_archivoInformeGanaderiaSA_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/importaciones/archivosAdjuntos', 'archivoInformeGanaderiaSA');
		}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#informeGanaderiaSA').val('');
		}
	});

	//INOCUIDAD VETERINARIOS
	$('#facturaProformaIAV').change(function(event){
		
		$("#estado").html('');
		var archivo = $("#facturaProformaIAV").val();
		var extension = archivo.split('.');

		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			subirArchivo('facturaProformaIAV',$("#identificador").val()+'_archivoFacturaProformaIAV_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/importaciones/archivosAdjuntos', 'archivoFacturaProformaIAV');
		}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#facturaProformaIAV').val('');
		}
	});

	$('#notaPedidoIAV').change(function(event){
		
		$("#estado").html('');
		var archivo = $("#notaPedidoIAV").val();
		var extension = archivo.split('.');

		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			subirArchivo('notaPedidoIAV',$("#identificador").val()+'_archivoNotaPedidoIAV_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/importaciones/archivosAdjuntos', 'archivoNotaPedidoIAV');
		}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#notaPedidoIAV').val('');
		}
	});

	$('#cartaAutorizacionIAV').change(function(event){
		
		$("#estado").html('');
		var archivo = $("#cartaAutorizacionIAV").val();
		var extension = archivo.split('.');

		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			subirArchivo('cartaAutorizacionIAV',$("#identificador").val()+'_archivoCartaAutorizacionIAV_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/importaciones/archivosAdjuntos', 'archivoCartaAutorizacionIAV');
		}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#cartaAutorizacionIAV').val('');
		}
	});

	//INOCUIDAD PLAGICIDAS
	$('#facturaProformaIAP').change(function(event){
		
		$("#estado").html('');
		var archivo = $("#facturaProformaIAP").val();
		var extension = archivo.split('.');

		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			subirArchivo('facturaProformaIAP',$("#identificador").val()+'_archivoFacturaProformaIAP_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/importaciones/archivosAdjuntos', 'archivoFacturaProformaIAP');
		}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#facturaProformaIAP').val('');
		}
	});

	$('#notaPedidoIAP').change(function(event){
		
		$("#estado").html('');
		var archivo = $("#notaPedidoIAP").val();
		var extension = archivo.split('.');

		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			subirArchivo('notaPedidoIAP',$("#identificador").val()+'_archivoNotaPedidoIAP_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/importaciones/archivosAdjuntos', 'archivoNotaPedidoIAP');
		}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#notaPedidoIAP').val('');
		}
	});

	$('#cartaAutorizacionIAP').change(function(event){
		
		$("#estado").html('');
		var archivo = $("#cartaAutorizacionIAP").val();
		var extension = archivo.split('.');

		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			subirArchivo('cartaAutorizacionIAP',$("#identificador").val()+'_archivoCartaAutorizacionIAP_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/importaciones/archivosAdjuntos', 'archivoCartaAutorizacionIAP');
		}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#cartaAutorizacionIAP').val('');
		}
	});
</script>