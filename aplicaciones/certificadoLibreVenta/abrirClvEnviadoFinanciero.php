<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorClv.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cr = new ControladorRegistroOperador();
$cl  = new ControladorClv();
$crs = new ControladorRevisionSolicitudesVUE();

$idSolicitud = $_POST['id'];
$identificadorInspector = $_SESSION['usuario'];


$cClv	  = $cl->listarCertificados($conexion,$idSolicitud);
$dClv     = $cl->listarDetalleCertificados($conexion,$idSolicitud);
$dcClv	  = $cl->listarDocumentos($conexion,$idSolicitud);

$cTitular = pg_fetch_assoc($cr->buscarOperador($conexion,$cClv[0]['idTitular']));

//Obtener monto a pagar
if($cClv[0]['estado']=='verificacion'){
	$qIdGrupo = $crs->buscarIdGrupo($conexion, $idSolicitud, 'CLV', 'Financiero');
	$idGrupo = pg_fetch_assoc($qIdGrupo);
	//Obtener monto a pagar
	$qDatosPago = $crs->buscarIdImposicionTasa($conexion, $idGrupo['id_grupo'], 'CLV', 'Financiero');
	$datosPago = pg_fetch_assoc($qDatosPago);
}


//Obtener datos de entidades bancarias
$qEntidadesBancarias = $cc->listarEntidadesBancariasAgrocalidad($conexion);
?>

<header>
	<h1>Certificado de libre venta</h1>
</header>

	<div id="estado"></div>
	
<!--div class="pestania"-->
	
	<?php 
		if($cClv[0]['idVue'] != ''){
			echo '<fieldset>
				<legend>Información de la Solicitud</legend>
					<div data-linea="1">
						<label>Identificación VUE: </label> '. $cClv[0]["idVue"] .'
					</div>
			</fieldset>';
		}
	?>
	<fieldset>
		<legend>Información del titular</legend>
			<div data-linea="3">
				<label>RUC / Cédula: </label> <?php echo $cTitular['identificador']; ?> 
			</div>
			
			<div data-linea="4">
				<label>Nombre: </label> <?php echo $cTitular['nombre_representante'] . ' ' . $cTitular['apellido_representante']; ?>
			</div>
			
			<div data-linea="7">
				<label>Provincia: </label> <?php echo $cTitular['provincia']; ?>
			</div>
			
			<div data-linea="7">
				<label>Cantón: </label> <?php echo $cTitular['canton']; ?>
			</div>
			
			<div data-linea="8">
				<label>Parroquia: </label> <?php echo $cTitular['parroquia']; ?>
			</div>
			
			<div data-linea="9">
				<label>Dirección: </label> <?php echo $cTitular['direccion']; ?> 
			</div>
	</fieldset>	
	
	<fieldset id="informacionOperador">
			<legend>Información Operador</legend>
			
			<div data-linea="9">
				<label>Nombre Operador: </label> <?php echo $cClv[0]['nombreDatoCertificado']; ?> 
			</div>
			
			<div data-linea="10">
				<label>Dirección Operador: </label> <?php echo $cClv[0]['direccionDatoCertificado']; ?> 
			</div>
			
	</fieldset>		
	
	<fieldset id="informacionProductoClv">
			 <legend>Información del producto <?php echo ($cClv[0]['tipoProducto'] == 'IAV'?'Veterinario':'Plaguicida'); ?></legend>	
			    <div data-linea="14">
					<label>Tipo de producto: </label> <?php echo ($cClv[0]['tipoProducto'] == 'IAV'?'Veterinario':'Plaguicida'); ?> 
				</div>
				<div data-linea="14">
					<label>Tipo operación: </label> <?php echo $cClv[0]['tipoDatoCertificado']; ?> 
				</div>		
			 	<div data-linea="15">								
					<label>Producto: </label> <?php echo $cClv[0]['nombre_producto']; ?>
				</div>
				<div data-linea="16">
				    <label>Subpartida: </label> <?php echo $pClv[0]['subpartida']; ?>	
				 </div>
				 
				<?php 
					 if ($cClv[0]['tipoProducto'] == 'IAP'){
				  		echo '<div data-linea="17">
								<label>Formulación VUE: </label>' .$pClv[0]['formulacion'] .
							'</div>
							<div data-linea="17">
								<label>Formulación GUIA: </label>' .$pClv[0]['formulacionGuia'] .
							'</div>
							<div data-linea="18">
								<label>Composición: </label>' .$pClv[0]['composicionGuia'] .
							'</div>';
					 }else{
					 	echo '<div data-linea="16">
						 		<label>Forma farmacética: </label>' . $pClv[0]['formulacionGuia'] .
						 	'</div>';
					 }
				?>
							
				<div data-linea="19">
					<label>Clasifición: </label> <?php echo $pClv[0]['clasificacion']; ?>
				</div>												
	</fieldset>
	
	<?php 
	//IMPRESION DE DOCUMENTOS
	
		if(count($dcClv)>0){
			$i=1;
		
			echo'<div id="documentos" >
					<fieldset>
						<legend>Documentos adjuntos</legend>
							<table>
								<tr>
									<td><label>#</label></td>
									<td><label>Nombre</label></td>
									<td><label>Enlace</label></td>
								</tr>';
		
			foreach ($dcClv as $documento){
					echo '<tr>
						  	<td>'.$i.'</td>
							<td>'.$documento['tipoArchivo'].'</td>
							<td>
								<form id="f_'.$i.'" action="aplicaciones/general/accederDocumentoFTP.php" method="post" enctype="multipart/form-data" target="_blank">
									<input name="rutaArchivo" value="'.$documento['rutaArchivo'].'" type="hidden">
									<input name="nombreArchivo" value="'.$documento['tipoArchivo'].'.pdf" type="hidden">
									<input name="idVue" value="'.$documento['idVue'].'" type="hidden">
									<button type="submit" name="boton">Descargar</button>
								</form>
							</td>
						 </tr>';
				$i++;
			}
				
			echo '</table>
				</fieldset>
			</div>';
		}
	?>
		
	<fieldset id="informacionProducto">
			<legend>Descripción del producto</legend>
					
			<div data-linea="6">
				<label>Tipo de producto: </label> <?php echo ($cClv[0]['tipoProducto']=="IAV"?'Inocuidad de Alimentos Veterinarios':'Inocuidad de Alimentos Plaguicidas'); ?> 
			</div>
			
			<div data-linea="11">
				<label>Fecha vigencia: </label> <?php echo date('j/n/Y',strtotime($cClv[0]['fechaVigenciaProducto'])); ?>
			</div>
			
			<div data-linea="11">
				<label>Fecha inscripcion: </label> <?php echo date('j/n/Y',strtotime($cClv[0]['fechaInscripcionProducto'])); ?>
			</div>
			
			<div data-linea="15">
				<label>Forma Farmaceútica: </label> <?php echo $cClv[0]['formaFarmaceutica']; ?>
			</div>
			
			<div data-linea="17">
				<label>Formulación: </label> <?php echo $cClv[0]['formulacion']; ?>
			</div>
			
			<?php 
				if($cClv[0]['tipoProducto'] == 'IAV') {
                  echo "<div data-linea='16'>
                      		 <label>Uso: </label>" . $cClv[0]['usoProducto'] . "
				 		</div>
				 		
				 		<div data-linea='19'>
                      		 <label>Especies: </label>" . $cClv[0]['especie']. "
				  		</div>
                  		
                  		<div data-linea='13'>
                  				<label>Presentación comercial: </label> " . $cClv[0]['presentacionComercial'] ."
                  		</div>
                  		
                  		<div data-linea='14'>
                  				<label>Clasificación: </label> ". $cClv[0]['clasificacionProducto']."
                  		</div>";
				}
			?>
	</fieldset>

<?php 
	//DETALLE DE PRODUCTOS
	if($cClv[0]['tipoProducto'] == 'IAP'  && count($dClv) > 0){
		echo '<fieldset>
				<legend>Composición Plaguicida</legend>;
			      	<table>
						<tr>
							<td><label>#</label></td>
							<td><label>Ingrediente activo</label></td> 
							<td><label>Concentración</label></td>
						</tr>';
				$i=1;
				
				foreach ($dClv as $detalleProducto){
					echo '<tr>
							<td>'.$i.'</td>
							<td>' . $detalleProducto['ingredienteActivo'] . ' </td>
						  	<td>' . number_format($detalleProducto['concentracion'], 2) . ' '. $detalleProducto['unidadMedida'] . ' </td>
						</tr>';			
					$i++;
					
				}
		echo '</table>
			</fieldset>';
	}
	
	if($cClv[0]['tipoProducto'] == 'IAV' && count($dClv) > 0) {
		$i=1;
		
		echo '<fieldset>
				<legend>Composición Veterinario</legend>;
					<table>
						<tr>
							<td><label>#</label></td>
							<td><label>Nombre</label></td>
							<td><label>Cantidad</label></td>
							<td><label>Descripción</label></td>
						</tr>';
		
		foreach ($dClv as $detalleProducto){
			echo '<tr>
					<td>' . $i . '</td>
					<td>' . $detalleProducto['composicionDeclarada'] . ' </td>
					<td>' . number_format($detalleProducto['cantidadComposicion'],2) . ' ' . $detalleProducto['unidadMedida'] . ' </td>
					<td>' . $detalleProducto['descripcionComposicion'] . ' </td>
				  </tr>';
			$i++;
				
		}
		echo '</table>
		</fieldset>';
	}		
	
?>

<!--/div-->

<!-- SECCION DE REVISIÓN DE PAGOS PARA CLV -->
<!--div class="pestania"-->	 

	 <header>
		<h1>Datos de facturación</h1>
	</header>
	
	<form id="asignarMonto" data-rutaAplicacion="revisionFormularios" data-opcion="asignarMontoSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $identificadorInspector;?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>"/>
		<input type="hidden" name="tipoSolicitud" value="CLV"/>
		<input type="hidden" name="tipoInspector" value="Financiero"/>
		<input type="hidden" name="estado" value="verificacionVUE"/>
		<input type="hidden" name="idVue" value="<?php echo $cClv[0]['idVue'];?>"/>
		
		<input type="hidden" id="opcion" name="opcion" value="0">
		<input type="hidden" id="tipoBusquedaCliente" name="tipoBusquedaCliente" value="01">
		<input type="hidden" id="idGupoSolicitudes" name="idGupoSolicitudes">
		
		<!-- fieldset>
			<legend>Valor a cancelar</legend>
				<div data-linea="11" >		
					<p class="nota">Por favor ingrese el valor a cancelar por el certificado.</p>
					
					<label>Monto: </label>
						<input type="text" id="monto" name="monto" placeholder="Ej: 10.56" data-er="^[0-9]+(\.[0-9]{1,3})?$"/>
				</div>
		</fieldset-->	
		
		
	<fieldset id="modificarDatosOperador">
		<legend>Datos de facturación</legend>
		
		<div data-linea="1">
			<label>Identificador</label>
				<input type="text" id="ruc" name="ruc" value="<?php echo $cTitular['identificador'];?>"  readonly="readonly">
		</div>
		
		<div data-linea="2">
			<label>Razón Social: </label> 
					<input type="text" id="razonSocial" name="razonSocial" value="<?php echo $cTitular['razon_social'];?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" />
		</div>
		
		<div data-linea="3">
			<label>Dirección: </label> 
				<input type="text" id="direccion" name="direccion" value="<?php echo $cTitular['direccion'];?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" />
		</div>
		
		<div data-linea="4">
			<label>Teléfono: </label> 
				<input type="text" id="telefono" name="telefono" value="<?php echo $cTitular['telefono_uno'];?>" disabled="disabled" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?"  data-inputmask="'mask': '(99) 999-9999'"/>
		</div>
		
		<div data-linea="5">
			<label>Correo: </label> 
				<input type="text" id="correo" name="correo" value="<?php echo $cTitular['correo'];?>" disabled="disabled" title="99" />
		</div>
		
		<button id="modificarCliente" type="button" class="editar">Editar</button>
		
	</fieldset>
		
	<fieldset>
		<legend>Información adicional</legend>
			<div data-linea="3">
				<label>Motivo</label>
					<input	type="text" id="observacion" name="observacion" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$"/>
			</div>
	</fieldset>	
	
	<fieldset>
		<legend>Detalle</legend>
		<div data-linea="4">
			<label>Área</label>
			<select id="area" name="area" >
				<option value="" selected="selected">Área....</option>
				<option value="SA">Sanidad Animal</option>
				<option value="SV">Sanidad Vegetal</option>
				<option value="IA">Inocuidad de los Alimentos</option>
				<option value="LT">Laboratorios</option>
			</select>
		</div>
		
		<div data-linea="4">
			<label>Buscar codigo</label>
			<input type="search" id="codigo" name="codigo" />
		</div>
		
		<div id="res_tarifario" data-linea="3"></div>
		
		<div data-linea="5">
			<label>Cantidad</label> 
			<input type="number" step="0.01" id="cantidad" name="cantidad" />
			<input type="hidden" id="valorTotal" name="valorTotal" />
		</div>
		<div data-linea="5">
			<label>Descuento</label>
			<input	type="number" step="0.01" id="descuento" name="descuento"/>
		</div>	

		<div data-linea="6" class="info"></div>
			<button type="button" onclick="agregarItem()" class="mas">Agregar Item</button>
				
						<table id="tablaDetalle">
							<thead>
								<tr>
									<th></th>
									<th>Concepto</th>
									<th>Cantidad</th>
									<th>Valor Unitario</th>
									<th>SubTotal</th>
									<th>Descuento</th>
									<th>IVA</th>
									<th>Total</th>								
								<tr>
							</thead> 
							
							<tbody id="detalles">
							</tbody>
					  </table>
	</fieldset>
				
		<button type="submit" class="guardar">Autorizar pago</button>
	
	</form>	
	
	<form id="verificarPago" data-rutaAplicacion="revisionFormularios" data-opcion="verificarPagoSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $identificadorInspector;?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>"/>
		<input type="hidden" name="tipoSolicitud" value="CLV"/>
		<input type="hidden" name="tipoInspector" value="Financiero"/>
		<input type="hidden" name="estado" value="inspeccion"/>
		<input type="hidden" name="idVue" value="<?php echo $cClv[0]['idVue'];?>"/>
		<input type="hidden" name="idGrupo" value="<?php echo $idGrupo['id_grupo'];?>"/>
		
		<fieldset id="factura">
				<legend>Pago de arancel</legend>
					<div data-linea="12" >
						<label>Monto a pagar: </label> $ <?php 
							if(pg_num_rows($qDatosPago) != 0){
								echo $datosPago['monto']; 
							}						
						?>
					</div>
		</fieldset>
		
		<fieldset>
			<legend>Resultado de Revisión</legend>
								
				<div data-linea="5">
					<label>Número de factura: </label>
						<input type="text" id="numeroFactura" name="numeroFactura" placeholder="Ej: 00234" data-er="^[0-9-]+$"/>
				</div>
				
				<div data-linea="6">
					<label>Entidad bancaria</label>
						<select id="codigoBanco" name="codigoBanco">
							<option value="">Seleccione....</option>
							<?php 
								while ($fila = pg_fetch_assoc($qEntidadesBancarias)){
									echo '<option value="'.$fila['id_banco']. '" data-codigovue="'.$fila['codigo_vue'].'">'. $fila['nombre'] .'</option>';
								}
							?>
						</select>
						
						<input type="hidden" id="nombreBanco" name="nombreBanco"></input>
				</div>	
				
				<div data-linea="7">
					<label>Monto recaudado: </label>
						<input type="text" id="montoRecaudado" name="montoRecaudado" placeholder="Ej: 153" data-er="^[0-9]+(\.[0-9]{1,3})?$" value="<?php echo$datosPago['monto_recaudado']; ?>"/>
				</div>
				
				<div data-linea="7">
					<label>Fecha de facturación: </label>
						<input type="text" id="fechaFacturacion" name="fechaFacturacion" value="<?php echo $datosPago['fecha_facturacion']; ?>"/>
				</div>
					
				<div data-linea="8">
					<label>Resultado</label>
						<select id="resultado" name="resultado">
							<option value="">Seleccione....</option>
							<option value="aprobado">Confirmar pago</option>
						</select>
				</div>	
				
				<div data-linea="9">
					<label>Observaciones</label>
						<input type="text" id="observacion" name="observacion"/>
				</div>
		</fieldset>
		
		<button type="submit" class="guardar">Finalizar proceso</button>
	</form>
<!--/div-->
<script type="text/javascript">
var estado= <?php echo json_encode($cClv[0]['estado']); ?>;
var banco = <?php echo json_encode($datosPago['codigo_banco']);?>;

$(document).ready(function(){
	distribuirLineas();
	construirValidador();
	//construirAnimacion($(".pestania"));
	
	$("#verificarPago").hide();
	$("#asignarMonto").hide();

	if(estado == 'pago'){
		$("#asignarMonto").show();
	}else if(estado == 'verificacion'){
		$("#verificarPago").show();
	}

	if($("#montoRecaudado").val().length >= 1){
		$("#montoRecaudado").prop("readonly",true);
	}

	if($("#fechaFacturacion").val().length >= 1){
		$("#fechaFacturacion").prop("readonly",true);
	}else{
		$("#fechaFacturacion").datepicker({
		    changeMonth: true,
		    changeYear: true
		  });
	}

	if(banco == '456'){
		$("#codigoBanco").find('option[data-codigovue="'+banco+'"]').prop("selected","selected");
		$("#codigoBanco").attr("disabled","disabled");
		$('#nombreBanco').val($("#codigoBanco  option:selected").text());
	}else{
		cargarValorDefecto("codigoBanco","<?php echo $datosPago['codigo_banco'];?>");
	}

	$("#descuento").numeric(".");
	$("#cantidad").numeric(".");
	$("#descuento").val('0');
	
});

$("#codigoBanco").change(function(){
	$('#nombreBanco').val($("#codigoBanco  option:selected").text());

});


$("#asignarMonto").submit(function(event){
	event.preventDefault();
	chequearCamposAsignarMonto(this);
});

$("#verificarPago").submit(function(event){
	event.preventDefault();
	chequearCamposVerificarPago(this);
});

function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}

function chequearCamposAsignarMonto(form){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($('#razonSocial').val() == ''){
		error = true;
		$("#razonSocial").addClass("alertaCombo");
		$("#estado").html("Por favor ingrese la razón social").addClass("alerta");
	}

	if($('#direccion').val() == ''){
		error = true;
		$("#direccion").addClass("alertaCombo");
		$("#estado").html("Por favor ingrese la dirección").addClass("alerta");
	}

	if($('#telefono').val() == ''){
		error = true;
		$("#telefono").addClass("alertaCombo");
		$("#estado").html("Por favor ingrese el teléfono").addClass("alerta");
	}
	if($('#correo').val() == ''){
		error = true;
		$("#correo").addClass("alertaCombo");
		$("#estado").html("Por favor ingrese correo eléctronico").addClass("alerta");
	}

	if($('#idDeposito').length == 0 ){
		error = true;
		$("#estado").html("Por favor ingrese uno o varios detalles").addClass("alerta");
	}
	
	if (error){
		$("#estado").html("Por favor ingrese solamente decimales con dos dígitos.").addClass('alerta');
	}else{
		
		 $('#asignarMonto').attr('data-opcion','asignarMontoSolicitud');
		 $('#asignarMonto').attr('data-destino','detalleItem');
		 $('#asignarMonto').attr('data-rutaAplicacion','revisionFormularios');
		 
		ejecutarJson(form);

		var resultado = $("#estado").html().split('-');

		if(resultado[0]=='La operación se ha guardado satisfactoriamente.'){

			 $("#idGupoSolicitudes").val(resultado[1]);	
			 $("#modificarDatosOperador input").removeAttr("disabled");
			 $('#asignarMonto').attr('data-opcion','guardarNuevoOrdenPago');
			 $('#asignarMonto').attr('data-destino','detalleItem');
			 $('#asignarMonto').attr('data-rutaAplicacion','financiero');

			abrir($("#asignarMonto"),event,false);
			 
		 }
	}
}

function chequearCamposVerificarPago(form){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if(!$.trim($("#codigoBanco").val())){
		error = true;
		$("#codigoBanco").addClass("alertaCombo");
	}

	if(!$.trim($("#montoRecaudado").val()) || !esCampoValido("#montoRecaudado")){
		error = true;
		$("#montoRecaudado").addClass("alertaCombo");
	}

	if(!$.trim($("#fechaFacturacion").val())){
		error = true;
		$("#fechaFacturacion").addClass("alertaCombo");
	}

	if(!$.trim($("#resultado").val()) || !esCampoValido("#resultado")){
		error = true;
		$("#resultado").addClass("alertaCombo");
	}

	if(!$.trim($("#observacion").val()) || !esCampoValido("#observacion")){
		error = true;
		$("#observacion").addClass("alertaCombo");
	}
	
	if (error){
		$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
	}else{
		ejecutarJson(form);
	}
}


//---------------------------------------------------------------------------------------------------------------------------------

$("#area").change(function(event){
	$('#asignarMonto').attr('data-opcion','accionesCliente');
	$('#asignarMonto').attr('data-destino','res_tarifario');
	$('#asignarMonto').attr('data-rutaAplicacion','financiero');
	$('#opcion').val('tarifario');	
	abrir($("#asignarMonto"),event,false); //Se ejecuta ajax, busqueda de sitio
	$("#codigo").val('');
	distribuirLineas();
 });
 

$("#modificarCliente").click(function(){
	$("#modificarDatosOperador input").removeAttr("disabled");
	$(this).attr("disabled","disabled");
});


$("#codigo").change(function(event){        	
	sbusqueda ='0';
	$.map(array_tarifario, function(item) {
	    if (item.codigo.indexOf($("#codigo").val()) >= 0) {

	    	if (item.idCategoria != '1'){
		    	
		    	switch(item.idCategoria){
			    	case '2':
			    		sbusqueda += '<optgroup label="'+item.codigo+'- '+item.concepto+'">';
			    	break;
			    	case '3':
			    		var concepto = item.concepto;
			    		if(concepto.length > 100){
			    			var parteConcepto = concepto.substring(0, 100)+'...';
					    }else{
					    	var parteConcepto = concepto;
						}
			    		sbusqueda += '<option title = "'+item.concepto+ ' - VALOR: '+item.valor+' - UNIDAD MEDIDA: '+item.medida+'" value="'+item.idServicio+'" data-precio="'+item.valor+'" data-iva="'+item.iva+'">'+item.codigo+'- '+parteConcepto+'</option>';
			    	break;
		    	}
			}
	    	
	    }
	});

	$('#transaccion').html(sbusqueda);
	
});

var contador = 0; 

function agregarItem(){

	numSecuencial = ++contador;	
	
	cantidad = $("#cantidad").val();
	precio = $("#transaccion option:selected").attr("data-precio");
	descuento = $("#descuento").val();
	auxIva = $("#transaccion option:selected").attr("data-iva");

	subTotalProducto = (Math.round(((Number(cantidad) * Number(precio)))*100)/100);
	subTotalDescuento = subTotalProducto - descuento;
	
	if(auxIva === "t"){
		ivaProducto = Math.round(((Number(subTotalDescuento)*0.12))*100)/100;
	}else {
		ivaProducto = 0;
	}

	totalProducto = subTotalDescuento + ivaProducto;

	var total = 0;
	var ivaTotal = 0;
	var subTotal = 0;

	if($("#area").val()!="" && $("#transaccion").val()!="" && $("#cantidad").val()!="" ){
		
		$("#detalles").append("<tr id='r_"+$("#transaccion").val()+"'><td><button type='button' onclick='quitarItem(\"#r_"+numSecuencial+"\")' class='menos'>Quitar</button></td><td>"+$("#transaccion  option:selected").text()+"</td><td>"+$("#cantidad").val()+"</td><td>"+$("#transaccion option:selected").attr("data-precio")+"</td><td>"+subTotalProducto+"</td><td>"+descuento+"</td><td>"+ivaProducto+"</td><td>"+totalProducto+"</td><input id='idDeposito' name='idDeposito[]' value='"+$("#transaccion").val()+"' type='hidden'><input id='nombreDeposito' name='NombreDeposito[]' value='"+$("#transaccion  option:selected").text()+"' type='hidden'><input id='cantidad' name='cantidad[]' value='"+$("#cantidad").val()+"' type='hidden'><input id='precioUnitario' name='precioUnitario[]' value='"+$("#transaccion option:selected").attr("data-precio")+"' type='hidden'><input id='ivaIndividual' name='ivaIndividual[]' value='"+ivaProducto+"' type='hidden'><input id='totalIndividual' name='totalIndividual[]' value='"+totalProducto+"' type='hidden'><input id='descuentoUnidad' name='descuentoUnidad[]' value='"+descuento+"' type='hidden'><input id='subTotal' name='subTotal[]' value='"+subTotalProducto+"' type='hidden'><input id='subTotalDescuento' name='subTotalDescuento[]' value='"+subTotalDescuento+"' type='hidden'></tr>");

		total = sumarValor('totalIndividual');
		ivaTotal = sumarValor('ivaIndividual');
		subTotal = sumarValor('subTotalDescuento');
		
		
		$("div.info").html('Total : '+subTotal+ '+'+ivaTotal +'='+total );
		$("#valorTotal").val(total);
		$("#estado").html("").removeClass('alerta');
	}
 }	

function quitarItem(fila){

	$("#detalles tr").eq($(fila).index()).remove();

	total = sumarValor('totalIndividual');
	ivaTotal = sumarValor('ivaIndividual');
	subTotal = sumarValor('subTotalDescuento');

	 $("div.info").html('Total : '+subTotal+ '+'+ivaTotal +'='+total );
	 $("#valorTotal").val(total);
	
	 distribuirLineas();
}

function sumarValor(campo){
	var valor = 0; 
	
	$('input[id="'+campo+'"]').each(function(e){   
		valor += Number($(this).val());
		valor = Math.round((valor)*100)/100;
    });

    return valor;
}
</script>