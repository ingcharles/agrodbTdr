<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

$identificador = $_SESSION['usuario'];

$qSitio = $cr ->abrirSitio($conexion, $_POST['id']);
$sitio = pg_fetch_assoc($qSitio);

$qLocalizacion = $cc->obtenerIdLocalizacion($conexion, $sitio['provincia'], 'PROVINCIAS');
$provincia = pg_fetch_assoc($qLocalizacion);

$qLocalizacion = $cc->obtenerIdLocalizacion($conexion, $sitio['canton'], 'CANTONES');
$canton = pg_fetch_assoc($qLocalizacion);

$qLocalizacion = $cc->obtenerIdLocalizacion($conexion, $sitio['parroquia'], 'PARROQUIAS');
$parroquia = pg_fetch_assoc($qLocalizacion);

$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');

$sitiosOperacion = $cr->verificarSitioOperacion($conexion, $_POST['id']);

if( pg_num_rows($sitiosOperacion) > 0){
	$modificar = 0;
}else{
	$modificar = 1;
}
?>

<header>
	<h1>Sitio operador</h1>
</header>

<div class="pestania">

<form id='nuevoSitio' data-rutaAplicacion='registroOperador' data-opcion='actualizarSitio' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	
	
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	
	
	<input type="hidden" id="idSitioS" name="idSitioS"	value="<?php echo $_POST['id'];?>" />
	<input type="hidden" id="codigoSitioProvincia" name="codigoProvincia" class="classProvincia"/>
	<input type="hidden" id="accionSitio" name="tipo"/>
	
	<fieldset>
		<legend>Información del Sitio</legend>
			<div data-linea="1">			
			<label>Nombre del sitio</label> 
			<input type="text" id="nombreSitio" name="nombreSitio" placeholder="Ej: Hacienda..." value="<?php echo $sitio['nombre_lugar'];?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" />
			</div>
			<div data-linea="1">
			<label>Superficie total(m2)</label> 
				<input type="text" step="0.1" id="superficieTotal" name="superficieTotal" placeholder="Ej: 123.56" value="<?php echo $sitio['superficie_total'];?>" disabled="disabled" data-er="^[0-9]+(\.[0-9]{1,2})?$" title="Ejemplo: 999.99"/>
			</div>
			<div data-linea="2">
				<!-- div id="divLocalizacion"-->
					<label>Provincia</label>
						<select id="provincia" name="provincia" disabled="disabled">
							<option value="">Provincia....</option>
							<?php 
								$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
								foreach ($provincias as $provinciaArray){
									if($sitio['provincia'] == $provinciaArray['nombre']){
										echo '<option value="' . $provinciaArray['codigo'] . '" selected="selected">' . $provinciaArray['nombre'] . '</option>';
									}else{
										echo '<option value="' . $provinciaArray['codigo'] . '">' . $provinciaArray['nombre'] . '</option>';
									}
								}
							?>
						</select>
					<!-- /div--> 
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
					<input type="text" id="direccion" name="direccion" placeholder="Ej: Santa Rosa" value="<?php echo $sitio['direccion'];?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$"/>
			</div>
			<div data-linea="3">
				<label>Referencia</label> 
					<input type="text" id="referencia" name="referencia" placeholder="Ej: Sector El Inca frente a..." value="<?php echo $sitio['referencia'];?>" disabled="disabled" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$"/>
			</div>
			<div data-linea="4">
				<label>Teléfono</label> 
					<input type="text" id="telefono" name="telefono" placeholder="Ej: (02) 456-9857" value="<?php echo $sitio['telefono'];?>" disabled="disabled" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" />
			</div>
			<div data-linea="5">	
				<label>Latitud UTM</label>
					<input type="text" id="latitud" name="latitud" value="<?php echo $sitio['latitud'];?>" disabled="disabled"/>
			</div>
			<div data-linea="5">		
				<label>Longitud UTM</label>
					<input type="text" id="longitud" name="longitud" value="<?php echo $sitio['longitud'];?>" disabled="disabled"/>
			</div>
			
			<div data-linea="5">
			<label>Zona</label>
				<input type="text" id="zona" name="zona" value="<?php echo $sitio['zona'];?>" disabled="disabled"/>
				<input type="hidden" id="zoom" name="zoom"/>
			</div>
	</fieldset>
</form>
	<fieldset id="subirCroquis">
		<legend>Actualizar croquis</legend>
		
		<div data-linea="7">
			<label>Croquis</label> 
				<a href="<?php echo $sitio['croquis']; ?>" target="_blank">Descargar croquis</a>
		</div>
		
		<form id="subirCroquis" action="aplicaciones/registroOperador/subirCroquis.php" method="post" enctype="multipart/form-data" target="ventanaEmergente" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
				<input type="file" name="archivo" id="archivo" accept="application/pdf"/> 
				<input type="hidden" name="idSitio" value="<?php echo $_POST['id'];?>"/>
				<input type="hidden" name="identificador" value="<?php echo $sitio['identificador_operador'];?>"/>
				<input type="hidden" name="nombreSitio" value="<?php echo $sitio['nombre_lugar'];?>"/>
				<button type="submit" name="boton" value="documentacion" disabled="disabled" class="adjunto" >Subir croquis</button>
		</form>
		<iframe name="ventanaEmergente" class="ventanaEmergente"></iframe>
	</fieldset>

	<fieldset>			
			<legend>Ubicación</legend>
			<div id="mapa"></div>
			<input type="checkbox" id="requiereCroquis" name="requiereCroquis" value="croquis" />
				<label>Tengo problemas para localizar mi sitio en el mapa.</label>
	</fieldset>

	
	

</div>
	
	<div class="pestania">
	
	<form id='guardarNuevaArea' data-rutaAplicacion='registroOperador' data-opcion='guardarNuevaArea' data-destino="detalleItem">
	
		<input type="hidden" id="idSitioP" name="idSitioP" value="<?php echo $_POST['id'];?>" />
		<input type="hidden" id="codigoAreaProvincia" name="codigoProvincia" class="classProvincia"/>
		<input type="hidden" id="accionArea" name="tipo"/>
		<input type="hidden" id="codigoSitioIncial" name="codigoSitioIncial" value="<?php echo $sitio['codigo_provincia'].$sitio['codigo']?>"/>
	
	
		<fieldset>
			<legend>Áreas</legend>
				<div data-linea="8">
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
				
				<div data-linea="8">
					<label>Nombre del área </label> 
						<input type="text" id="nombreArea" name="nombreArea" placeholder="Ej: Bodega 1..." data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#°0-9 ]+$" />
				</div>
				
				<div data-linea="9">
					<label id="lSuperficie">Superficie</label> 
						<input type="text" id="superficie" name="superficie" placeholder="Ej: 10.56" data-er="^[0-9]+(\.[0-9]{1,2})?$" title="Ejemplo: 999.99"/>
				</div>
				
				<div id="dCodigoArea"></div>
				<div id="dCodigoSitio"></div>
				
				<div data-linea="9">		
					<button type="submit" class="mas">Agregar área</button>
				</div>
		</fieldset>
	</form>
					 
		<fieldset>
			<legend>Áreas agregadas</legend>
				
					 <div>
						<table id="listadoAreas">
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
								
									<?php
							
										$res = $cr->listarAreaOperador($conexion, $_POST['id']);
																	
										while($fila = pg_fetch_assoc($res)){
											
											echo "<tr id='r_".str_replace(' ', '', $fila['nombre_area'])."'>
													<td> 
														<form id='f_".$fila['nombre_area']."' data-rutaAplicacion='registroOperador' data-opcion='quitarArea'>
															<button type='submit' class='menos'>Quitar</button>
															<input name='nombreArea' value='".$fila['nombre_area'] ."' type='hidden'>
															<input name='idSitio' value='".$fila['id_sitio'] ."' type='hidden'>
														</form>
													</td>
													<td>".$fila['nombre_area']."</td>
													<td>".$fila['tipo_area']."</td>
													<td>".$fila['superficie_utilizada']." ".$fila['unidad_medida']."</td>
													<td>".$identificador.'.'.$sitio['codigo_provincia'].$sitio['codigo'].$fila['codigo'].$fila['secuencial']."</td>
												</tr>";
										}   
										
										
									?>
							
							</tbody>
						</table>
					</div>
		</fieldset>
	</div>

<script type="text/javascript">
	var array_canton= <?php echo json_encode($cantones); ?>;
	var array_parroquia= <?php echo json_encode($parroquias); ?>;
	var marker = null;
	var mapaEditable = 'noEditable';

	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));	
		$('<option value="<?php echo $canton['id_localizacion'];?>"><?php echo $canton['nombre'];?></option>').appendTo('#canton');
		$('<option value="<?php echo $parroquia['id_localizacion'];?>"><?php echo $parroquia['nombre'];?></option>').appendTo('#parroquia');
		$('#subirCroquis').hide();	
		construirValidador();
		iniciarMapa($('#latitud').val(), $('#longitud').val(), 10);
		$(".classProvincia").val($("#provincia").val());
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
			$("#accionArea").val('area');
	        $("#guardarNuevaArea").attr('data-destino', 'dCodigoArea');
	        $("#guardarNuevaArea").attr('data-opcion', 'codigoSitioArea');
	        $("#estado").html("");
	        abrir($("#guardarNuevaArea"), event, false); //Se ejecuta ajax, busqueda de sitios
			
		}else{
			$("#estado").html("Por favor seleccione la ubicación del sitio ").addClass('alerta');
			cargarValorDefecto("tipoArea","");
		}
		
	
		
	});
	
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
	    $(".classProvincia").val($("#provincia").val());
	    cargarValorDefecto("tipoArea","");

	    if($.trim($("#provincia").val())){
	    	$("#accionSitio").val('sitio');
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
	    $('#parroquia').html(sparroquia);
		$("#parroquia").removeAttr("disabled");
	
		//$("#latitud").val($('#canton option:selected').attr('data-latitud'));
		//$("#longitud").val($('#canton option:selected').attr('data-longitud'));
		//$("#zona").val($('#canton option:selected').attr('data-zona'));
		
		//iniciar();

		 /*var xy = new Array(2);
        xy = UTM2Lat(valLat, valLon, zona); 
		alert(xy[0]);
        
        if(xy[0]!=""){        
          iniciarMapa(xy[0], xy[1], 10);
        }  */
        
	});

	$("#modificar").click(function(){
			mapaEditable = 'editable';		
		if (<?php echo $modificar?> == 1){ 
			$("input").removeAttr("disabled");
			$("select").removeAttr("disabled");
			$("textarea").removeAttr("disabled");
			$("#actualizar").removeAttr("disabled");
			//$("#divLocalizacion").fadeIn();
			$("#latitud").attr(disabled,"disabled");
			$("#longitud").attr(disabled,"disabled");
		}else{
			$("#nombreSitio").removeAttr("disabled");
			$("#superficieTotal").removeAttr("disabled");
			$("#referencia").removeAttr("disabled");
			$("#telefono").removeAttr("disabled");
			$("#actualizar").removeAttr("disabled");
			$("#estado").html("El sitio está en uso para una operación y no puede actualizar su ubicación.").addClass('alerta');
		}
	});


	$("#listadoAreas").on("submit","form",function(event){
		event.preventDefault();
		ejecutarJson($(this));
		if($("#estado").html()=='El área ha sido eliminado satisfactoriamente'){
			var texto=$(this).attr('id').substring(2);
			texto=texto.replace(/ /g,'');
			texto="#r_"+texto;
			$("#areas tr").eq($(texto).index()).remove();
		}	
	});

	$("#guardarNuevaArea").submit(function(event){
		event.preventDefault();
		chequearCamposArea(this);
	});

	$("#archivo").click(function(){
		$("#subirCroquis button").removeAttr("disabled");
	});

	$("#nuevoSitio").submit(function(event){
		event.preventDefault();
		//ejecutarJson($(this));
		chequearCamposSitio(this);
	});
    

	$('#requiereCroquis').change(function() {
        if($(this).is(":checked")) {
        	$('#subirCroquis').fadeIn();
        }else{
        	$('#subirCroquis').hide();
        }        
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

		if(!$.trim($("#latitud").val()) || !esCampoValido("#latitud")){
			error = true;
			$("#latitud").addClass("alertaCombo");
		}

		if(!$.trim($("#longitud").val()) || !esCampoValido("#longitud")){
			error = true;
			$("#longitud").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			$("#estado").html("").removeClass('alerta');

			$("#nuevoSitio").attr('data-destino', 'detalleItem');
	        $("#nuevoSitio").attr('data-opcion', 'actualizarSitio');
			
			ejecutarJson(form);
		}
	}

	function chequearCamposArea(form){
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
			
			if($("#nombreArea").val()==""){
				var numero = Math.floor(Math.random()*100);
				var codigo = ($("#nombreArea").val()!=""?$("#nombreArea").val():"SN"+numero);	
				$("#nombreArea").val(codigo);
			}
			
			if($("#areas #r_"+$("#nombreArea").val().replace(/ /g,'')).length==0){
				$("#areas").append("<tr id='r_"+$("#nombreArea").val().replace(/ /g,'')+"'><td><form id='f_"+$("#nombreArea").val().replace(/ /g,'')+"' data-rutaAplicacion='registroOperador' data-opcion='quitarArea'><button type='submit' class='menos'>Quitar</button><input id='nombreArea' name='nombreArea' value='"+$("#nombreArea").val()+"' type='hidden'><input name='idSitio' value='"+$("#idSitioP").val()+"' type='hidden'></form></td><td>"+$("#nombreArea").val()+"</td><td>"+$("#tipoArea  option:selected").text()+"</td><td>"+$("#superficie").val()+"</td><input id='hCodigo' name='hCodigo[]' value='"+$("#tipoArea option:selected").attr('data-codigo')+"' type='hidden'></td><td>"+$("#codigoArea").val()+"<input id='hCodigoArea' name='hCodigoArea[]' value='"+$("#codigoArea").val()+"' type='hidden'></td></tr>");
				$("#guardarNuevaArea").attr('data-opcion', 'guardarNuevaArea');
				ejecutarJson(form);
			}else{
				$("#estado").html("Por favor verifique datos, las áreas no pueden tener los mismos datos.").addClass('alerta');
			}
		}
	}

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