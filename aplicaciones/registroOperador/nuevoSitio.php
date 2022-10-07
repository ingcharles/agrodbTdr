<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');

?>
<header>
	<h1>Nuevo sitio operador</h1>
</header>

<form id='nuevoSitio' data-rutaAplicacion='registroOperador' data-opcion='guardarNuevoSitio' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="tipo" name="tipo"/>
	
	<div id="estado"></div>
	
	<div class="pestania">
	
	<fieldset>
		<legend>Información del Sitio</legend>
			<div data-linea="1">			
			<label>Nombre del sitio</label> 
				<input type="text" id="nombreSitio" name="nombreSitio" placeholder="Ej: Hacienda..." data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" />
			</div>
			<div data-linea="1">
			<label>Superficie total(m2)</label> 
				<input type="text" step="0.1" id="superficieTotal" name="superficieTotal" placeholder="Ej: 1234.56" data-er="^[0-9]+(\.[0-9]{1,2})?$" title="Ejemplo: 999.99" />
			</div>
			<div data-linea="2">
			<label>Provincia</label>
				<select id="provincia" name="provincia">
					<option value="">Provincia....</option>
					<?php 
						$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
						foreach ($provincias as $provincia){
							echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
						}
					?>
				</select> 
				
				<input type="hidden" id="codigoProvincia" name="codigoProvincia"/>
				
			</div>
			
			<div data-linea="2">
			<label>Cantón</label>
				<select id="canton" name="canton" disabled="disabled">
				</select>
			</div>
			
			<div data-linea="2">	
			<label>Parroquia</label>
				<select id="parroquia" name="parroquia" disabled="disabled">
				</select>
			</div>
			
			<div data-linea="3">
			<label>Dirección</label> 
				<input type="text" id="direccion" name="direccion" placeholder="Ej: Santa Rosa" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" />
			</div>
			
			<div data-linea="3">	
			<label>Referencias</label> 
				<input type="text" id="referencia" name="referencia" placeholder="Ej: Sector El Inca frente a..." data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" />
			</div>
			
			<div data-linea="4">
			<label>Teléfono</label> 
				<input type="text" id="telefono" name="telefono" placeholder="Ej: (02) 456-9857" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" />
			</div>
			
			<div data-linea="5">	
			<label>Latitud UTM</label>
				<input type="text" id="latitud" name="latitud" placeholder="Elija en el mapa" data-er="^[0-9]+(\.[0-9]{1,})?$" />
			</div>
			
			<div data-linea="5">
			<label>Longitud UTM</label>
				<input type="text" id="longitud" name="longitud" placeholder="Elija en el mapa" data-er="^[0-9]+(\.[0-9]{1,})?$" />
			</div>	
			
			<div data-linea="5">
			<label>Zona</label>
				<input type="text" id="zona" name="zona" placeholder="Elija en el mapa"/>
				<input type="hidden" id="zoom" name="zoom"/>
			</div>
		</fieldset>
		
		<fieldset id="subirCroquis">
			<legend>Croquis</legend>
				<!-- input type="file" name="croquis" id='croquis' /-->
				<div data-linea ="6">
					<input type="file" class="archivo" name="informe" accept="application/pdf"/>
					<input type="hidden" class="rutaArchivo" name="archivo" value="0"/>
					<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
					<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/registroOperador/croquisSitio" >Subir archivo</button>
				</div>
		</fieldset>
		
		<fieldset>			
			<legend>Ubicación</legend>
			<p class="nota">Por favor marque en el mapa la ubicación del sitio. Puede ampliar el mismo para indicar la posición exacta.</p>
			<div id="mapa"></div>
			
			<table>
				<tr>
					<td>
						<input type="checkbox" id="requiereCroquis" name="requiereCroquis" value="croquis" />
					</td>
					<td>
						<label for="requiereCroquis">Tengo problemas para localizar mi sitio en el mapa.</label>
					</td>
					
				</tr>
			</table>
			
		</fieldset>	
	</div>
	
	
	<div class="pestania">
		<fieldset>
			<legend>Áreas</legend>
				
				<div data-linea="7">
					<label>Tipo de áreas </label> 
						<select id="tipoArea" name="tipoArea" >
							<option value="">Seleccione...</option>
							<?php 
							$areas = $cr->listarAreas($conexion);
							while ($fila = pg_fetch_assoc($areas)){
								echo '<option value="' . $fila['nombre'] . '" data-unidad="'.$fila['unidad_medida'].'" data-codigo="'.$fila['codigo'].'">' . $fila['nombre'] . '</option>';
							}
							?>
						</select>
						
						<input type="hidden" id="codigoTipoArea" name="codigoTipoArea"/>
				</div>
				
				<div data-linea="7">	
					<label>Nombre del área </label> 
						<input type="text" id="nombreArea" name="nombreArea" placeholder="Ej: Bodega 1..." data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#°0-9 ]+$"/>
				</div>
				
				<div data-linea="8">	
					<label id="lSuperficie">Superficie</label> 
						<input type="text" step="0.1" id="superficie" name="superficie" placeholder="Ej: 10.56" data-er="^[0-9]+(\.[0-9]{1,2})?$" title="Ejemplo: 999.99"/>
				</div>
				
				<div id="dCodigoArea"></div>
				<div id="dCodigoSitio"></div>
				
									
					<button type="button" onclick="agregarAreas()" class="mas">Agregar áreas</button>
				</fieldset>
					 
		<fieldset>
			<legend>Áreas agregadas</legend>
					 <div>
						<table>
							<thead>
								<tr>
									<th></th>
									<th>Nombre</th>
									<th>Tipo</th>
									<th>Superficie</th>
									<th>Código</th>
								<tr>
							</thead> 
							<tbody id="areas">
							</tbody>
						</table>
					</div>
	
		</fieldset>
		
		<button type="submit" class="guardar">Guardar sitio</button> 
	</div>
</form>
	
<script type="text/javascript">
							
var marker = null;
var latitud = -1.537901237431487;
var longitud = -78.99169921875;

	$(document).ready(function(){
		
		distribuirLineas();
		construirAnimacion($(".pestania"));	
		iniciarMapa(latitud,longitud, 6);	
		$('#subirCroquis').hide();
		construirValidador();
		$("#latitud").numeric();
		$("#longitud").numeric();
	});
	
	$("#tipoArea").change(function(event){	
		if ($("#tipoArea option:selected").attr('data-unidad') == ''){
	    	$('#lSuperficie').html("Superficie");
		}else{
			$('#lSuperficie').html("Superficie (" + $("#tipoArea option:selected").attr('data-unidad') + ")");
		}

		if($.trim($("#provincia").val())){

			$("#codigoTipoArea").val($("#tipoArea option:selected").attr('data-codigo'));
			$("#tipo").val('area');
	        $("#nuevoSitio").attr('data-destino', 'dCodigoArea');
	        $("#nuevoSitio").attr('data-opcion', 'codigoSitioArea');

	        $("#estado").html("");
	       	abrir($("#nuevoSitio"), event, false); //Se ejecuta ajax, busqueda de sitios
	        
		}else{
			$("#estado").html("Por favor seleccione la ubicación del sitio ").addClass('alerta');
			cargarValorDefecto("tipoArea","");
		}
		
	
	});

	function agregarAreas(){
		chequearCamposArea();
	}
	
	function quitarArea(fila){
		$("#areas tr").eq($(fila).index()).remove();
	}
	
	var array_canton= <?php echo json_encode($cantones); ?>;
	var array_parroquia= <?php echo json_encode($parroquias); ?>;
	
    $("#provincia").change(function(event){
    	scanton ='0';
		scanton = '<option value="">Cantón...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provincia").val()==array_canton[i]['padre']){
		    	scanton += '<option data-latitud="'+array_canton[i]['latitud']+'"data-longitud="'+array_canton[i]['longitud']+'"data-zona="'+array_canton[i]['zona']+'" value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			}
	   	}
	    $('#canton').html(scanton);
	    $("#canton").removeAttr("disabled");
	    $("#codigoProvincia").val($("#provincia").val());
	    cargarValorDefecto("tipoArea","");

	    if($.trim($("#provincia").val())){
	    	$("#tipo").val('sitio');
	        $("#nuevoSitio").attr('data-destino', 'dCodigoSitio');
	        $("#nuevoSitio").attr('data-opcion', 'codigoSitioArea');

	       	abrir($("#nuevoSitio"), event, false); //Se ejecuta ajax, busqueda de sitios
	        
		}

		
	});

    $("#canton").change(function(){
		sparroquia ='0';
		sparroquia = '<option value="">Parroquia...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#canton").val()==array_parroquia[i]['padre']){
		    	sparroquia += '<option value="'+array_parroquia[i]['codigo']+'">'+array_parroquia[i]['nombre']+'</option>';
			    } 
	    	}

	    var valLat = $('#canton option:selected').attr('data-latitud');
	    var valLon = $('#canton option:selected').attr('data-longitud');
	    var zona = $('#canton option:selected').attr('data-zona');

	    /*var xy = new Array(2);
        xy = UTM2Lat(valLat, valLon, zona); 
		alert(xy[0]);
        
        if(xy[0]!=""){        
          iniciarMapa(xy[0], xy[1], 10);
        }  */
        
	    $('#parroquia').html(sparroquia);
		$("#parroquia").removeAttr("disabled");
	});
    
	function iniciarMapa(latitud, longitud, porcentajeZoom) {
		
	  var mapOptions = {
	    zoom: porcentajeZoom,
	    center: new google.maps.LatLng(latitud, longitud),
	    mapTypeId: google.maps.MapTypeId.ROADMAP
	  };
	  var map = new google.maps.Map(document.getElementById('mapa'), mapOptions);


	  if( $("#latitud").val() != '' &&  $("#longitud").val() != '' &&  $("#zona").val() != ''){

		  var latLog = new Array(2);
		  latLog = UTM2Lat($("#latitud").val(), $("#longitud").val(), $("#zona").val());
		        
	        var latitud  = latLog[0];
	        var longitud = latLog[1];
		  
		  placeMarker(new google.maps.LatLng(latitud,longitud), map);
	  }

	  google.maps.event.addListener(map, 'click', function(e) {
	    placeMarker(e.latLng, map);
	  });
	  
	}

	function placeMarker(position, map) {

	 
	  if (marker != null)
	  	marker.setMap(null);

	  marker = new google.maps.Marker({
	     position: position,
	     map: map
	   });
	   
	  map.panTo(position);
	  
	  $("#zoom").val(map.zoom);

	  var xya = new Array(3);	  
	  xya = NuevaLat2UTM(position.lat(),position.lng()); 

	  $("#latitud").val(xya[0]);
	  $("#longitud").val(xya[1]);
	  $("#zona").val(xya[2]);
	}

	$('#requiereCroquis').change(function() {
        if($(this).is(":checked")) {
        	$('#subirCroquis').fadeIn();
        }else{
        	$('#subirCroquis').hide();
        }        
    });

    
	/*$('#croquis').change(function(event){
		if($("#nombreSitio").val() != ""){
			subirArchivo('croquis',< ?php echo $_SESSION['usuario'];?>+'-'+$("#nombreSitio").val().replace(/ /g,''),'aplicaciones/registroOperador/croquisSitio', 'archivo');
		}else{
			alert("Por favor ingrese el nombre del sitio para subir el croquis.");
			$("#croquis").val("");
		}
	});*/

	 var usuario = <?php echo json_encode($_SESSION['usuario']);?>;
	
	 $('button.subirArchivo').click(function (event) {
	        var boton = $(this);
	        var archivo = boton.parent().find(".archivo");
	        var rutaArchivo = boton.parent().find(".rutaArchivo");
	        var extension = archivo.val().split('.');
	        var estado = boton.parent().find(".estadoCarga");

	        if (extension[extension.length - 1].toUpperCase() == 'PDF' || $("#nombreSitio").val() != "") {

	            subirArchivo(
	                archivo
	                , usuario + "-" + $("#nombreSitio").val().replace(/ /g,'')
	                , boton.attr("data-rutaCarga")
	                , rutaArchivo
	                , new carga(estado, archivo, boton)
	            );
	        } else {
	            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
	            archivo.val("");
	        }
	    });
	

	$("#nuevoSitio").submit(function(event){
		event.preventDefault();
		//if($('#latitud').val()!="" && $('#longitud').val()!=""){
			//abrir($(this),event,false);
		//}else{
			//alert('Por favor marque la ubicación de su sitio en el mapa.');
		//}
		chequearCamposSitio(this);	
	});
	

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	

	function chequearCamposSitio(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombreSitio").val()) || !esCampoValido("#nombreSitio")){
			error = true;
			$("#nombreSitio").addClass("alertaCombo");
		}

		if(!$.trim($("#superficieTotal").val()) || !esCampoValido("#superficieTotal")){
			error = true;
			$("#superficieTotal").addClass("alertaCombo");
		}

		if(!$.trim($("#provincia").val()) || !esCampoValido("#provincia")){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}

		if(!$.trim($("#canton").val()) || !esCampoValido("#canton")){
			error = true;
			$("#canton").addClass("alertaCombo");
		}

		if(!$.trim($("#parroquia").val()) || !esCampoValido("#parroquia")){
			error = true;
			$("#parroquia").addClass("alertaCombo");
		}

		if(!$.trim($("#direccion").val()) || !esCampoValido("#direccion")){
			error = true;
			$("#direccion").addClass("alertaCombo");
		}

		if(!$.trim($("#referencia").val()) || !esCampoValido("#referencia")){
			error = true;
			$("#referencia").addClass("alertaCombo");
		}

		if(!$.trim($("#telefono").val()) || !esCampoValido("#telefono")){
			error = true;
			$("#telefono").addClass("alertaCombo");
		}

		if($("input:checkbox[name=requiereCroquis]:checked").val() == null){
			if(!$.trim($("#latitud").val()) || !esCampoValido("#latitud")){
				error = true;
				$("#latitud").addClass("alertaCombo");
			}
	
			if(!$.trim($("#longitud").val()) || !esCampoValido("#longitud")){
				error = true;
				$("#longitud").addClass("alertaCombo");
			}
		}else{
			if($("#archivo").val() == 0){
				error = true;
				$("#croquis").addClass("alertaCombo");
			}	
		}

		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			if($('#hTipoArea').length == 0 ){
				$("#estado").html("Por favor ingrese una o varias áreas").addClass("alerta");
			}else{
				 $("#nuevoSitio").attr('data-opcion', 'guardarNuevoSitio');
				ejecutarJson(form);
				//$("#estado").html("Los datos han sido actualizados satisfactoriamente.").addClass('correcto');
			}
		}
	}


	function chequearCamposArea(){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#tipoArea").val()) || !esCampoValido("#tipoArea")){
			error = true;
			$("#tipoArea").addClass("alertaCombo");
		}
		
		if(!$.trim($("#nombreArea").val()) || !esCampoValido("#nombreArea")){
			error = true;
			$("#nombreArea").addClass("alertaCombo");
		}

		if(!$.trim($("#superficie").val()) || !esCampoValido("#superficie")){
			error = true;
			$("#superficie").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			$("#estado").html("").removeClass('alerta');

			if($("#tipoArea").val()!="" && $("#superficie").val()!=""){
				var numero = Math.floor(Math.random()*100);	
				var codigo = ($("#nombreArea").val()!=""?$("#nombreArea").val():"SN"+numero);	
				if($("#areas #r_"+codigo.replace(/ /g,'')).length==0){
					$("#areas").append("<tr id='r_"+codigo.replace(/ /g,'')+"'><td><button type='button' onclick='quitarArea(\"#r_"+codigo.replace(/ /g,'')+"\")' class='menos'>Quitar</button></td><td>"+codigo+"<input id='hNombreArea' name='hNombreArea[]' value='"+codigo+"' type='hidden'></td><td>"+$("#tipoArea  option:selected").text()+"<input id='hTipoArea' name='hTipoArea[]' value='"+$("#tipoArea  option:selected").text()+"' type='hidden'></td><td>"+$("#superficie").val()+" "+$("#tipoArea option:selected").attr("data-unidad")+"<input id='hSuperficie' name='hSuperficie[]' value='"+$("#superficie").val()+"' type='hidden'><input id='hCodigo' name='hCodigo[]' value='"+$("#tipoArea option:selected").attr('data-codigo')+"' type='hidden'></td><td>"+$("#codigoArea").val()+"<input id='hCodigoArea' name='hCodigoArea[]' value='"+$("#codigoArea").val()+"' type='hidden'></td></tr>");
				}else{
					$("#estado").html("Por favor verifique datos, las áreas no pueden tener los mismos datos.").addClass('alerta');
				}
			}
		}
	}

	$(window).resize(function() {

		if($("#latitud").val() != '' &&  $("#longitud").val() != '' &&  $("#zona").val() != ''){

			var vLat=$("#latitud").val();
		    var vLong=$("#longitud").val();
		    var zona=$("#zona").val();
		    var zoom= Number($("#zoom").val());

		    var xy = new Array(2);
	        xy = UTM2Lat(vLat, vLong, zona);
		        
	        var latitud  = xy[0];
	        var longitud = xy[1];		    
			
		}else{
			var latitud = -1.537901237431487;
			var longitud = -78.99169921875;
			var zoom = 6;
		}

		iniciarMapa(latitud,longitud, zoom);		 
		
	});
		
	</script>