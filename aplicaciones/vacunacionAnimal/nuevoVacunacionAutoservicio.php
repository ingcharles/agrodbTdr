<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

// ==> Vacunación para digitadores
$OperadorDigitador = 2;
$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();
$ppc = new ControladorVacunacionAnimal();

$laboratorios=$cc->listaLaboratorios($conexion,'6');
$lotes = $cc->listaLotes($conexion);

$validaCertificado = $ppc->validarCertificadosVacunacion($conexion);
$controlAreteoVacuna = 'si';//si aretea	
$areaSitios = $ppc->listaAreaEmpresa($conexion, $_SESSION['usuario']); 
$sitios = $ppc->listaSitioEmpresas($conexion, $_SESSION['usuario']);

?>
<header>
	<h1>Vacunación Autoservicio</h1>
</header>

<form id='nuevoVacunacionAutoservicio' data-rutaAplicacion='vacunacionAnimal' data-opcion='accionesVacunacionAutoservicio'>
	<div id="estado"></div>
	<input type="hidden" id="usuario_responsable" name="usuario_responsable" value="<?php echo $_SESSION['usuario'];?>" />	
	<input type="hidden" id="control_areteo" name="control_areteo" value="<?php echo $controlAreteoVacuna;?>" />
	<input type="hidden" id="especie_valorada" name="especie_valorada" value="0" />

	<input type="hidden" id="idArete" name="idArete" value="0" />
	<input type="hidden" id="nombre_especie" name="nombre_especie" value="0" />	
	<input type="hidden" id="costo_vacuna" name="costo_vacuna" value="0" />
	<input type="hidden" id="opcion" name="opcion" value="0">
	<input type="hidden" id="operadorVacunacion" name="operadorVacunacion" value="0" />
	<input type="hidden" id="distribuidorVacunacion" name="distribuidorVacunacion" value="0" />
		
	<div class="pestania">	
		<fieldset id="infoEspecie" name="infoEspecie">
			<legend>Registro del certificado de vacunación</legend>	
			<div data-linea="1">		        
				<label>Empresa:</label> 
				<select id="cmbEmpresa" name="cmbEmpresa">
					<option value="0">Seleccionar....</option>
					<?php
						$empresa = $ppc-> listaAdministradorEmpresa($conexion, $_SESSION['usuario']);
						while ($fila = pg_fetch_assoc($empresa)){							
					    	echo '<option value="' . $fila['identificador'] . '">' . $fila['empresa'] . '</option>';					    	
					    }

					?>
				 </select>
			</div>		
		    <div data-linea="1">		        
				<label>Especie:</label> 
				<select id="cmbEspecie" name="cmbEspecie">
					<option value="0">Seleccionar....</option>
					<?php
						$especie = $cc-> especiesVacunacion($conexion);
						while ($fila = pg_fetch_assoc($especie)){
							if($fila['nombre']=='Porcinos'){
					    		echo '<option value="' . $fila['id_especies'] . '">' . $fila['nombre'] . '</option>';
					    	}
					    }

					?>
				 </select>
			</div>																													
			<div data-linea="2">
				<label>Certificado de vacunación :</label> 
				<input type="text" id="txtEspecieValorada" name="txtEspecieValorada" />
			</div>
			<div data-linea="2">
				<button type="button" id="btn_especie" name="btn_especie">Buscar especie</button>
			</div>			
		</fieldset>
	    <fieldset id="infoVacuna" name="infoVacuna">
		<legend>Información de la vacuna</legend>
		
			<div  id="res_especie" data-linea="1" ></div>	
			<div data-linea="1">
				<label>Fecha de vacunación</label> 
				<input type="text" id="fecha_emision" name="fecha_emision" class='fecha_emision'/>
			</div>
			<div id="resultadoVacunador" data-linea="2"></div>
			<div data-linea="3">
				<label>Tipo vacunación</label> 
				<select id="tipoVacuna" name="tipoVacuna">
					<option value="0">Seleccionar...</option>
					<?php 
					$tipoVacuna = $cc->listaTipoVacuna($conexion);
					while ($fila = pg_fetch_assoc($tipoVacuna)){
						if ($fila['nombre_vacuna'] == 'Autoservicio')
						   echo '<option data-costo="' . $fila['costo'] . '"  value="' . $fila['id_tipo_vacuna'] . '">' . $fila['nombre_vacuna'] . '</option>';
				    }
				    ?>
				</select>
			</div>
			<div data-linea="4">
				<label>Laboratorio</label> 
				<select id="laboratorio" name="laboratorio">
				</select>
			</div>
			<div data-linea="4">
				<label>Lote</label> <select id="lote" name="lote" disabled="disabled">
				</select>
			</div>						
		</fieldset>	
		<fieldset id="infoSitio" name="infoSitio"> 
			<legend>Búsqueda del sitio</legend>
			<div data-linea="1">
				<label>Sitio : </label>
				<select id="cmbSitio" name="cmbSitio" disabled="disabled"> 
				</select>		        		
			</div>
			<div data-linea="2">
				<label>Area : </label>	
				<select id="cmbArea" name="cmbArea" disabled="disabled"> 
				</select>
			</div>				
		</fieldset>			
	</div>	
	<div class="pestania">
		<fieldset >
			<legend>Detalle de la vacunación</legend>	
			<div data-linea="1">
				<label>Sitio :</label> 
				<input type="text" id="nombreSitio" name="nombreSitio" disabled="disabled"/>
			</div>
			<div data-linea="1">
				<label>Area :</label> 
				<input type="text" id="nombreArea" name="nombreArea" disabled="disabled"/>
			</div>			
			<div data-linea="2" id="res_catastro_productos">
			</div>				
	    </fieldset>		    					
	</div>
	<div class="pestania">
	  <div id='siAretea'>	
		<fieldset>
			<legend>Detalle de serie de aretes</legend>
			<div data-linea="13">
				<label>Total vacunados</label>
			</div>
		    <div data-linea="13">
		        <label>Serie inicio</label>
		    </div>
		    <div data-linea="13">
		        <label>Serie fin</label>
		    </div>
		    <div data-linea="13">
				<label>Agregar aretes</label>
			</div>
		    <div data-linea="14">
				<input type="text" id="tVacunados" name="tVacunados" disabled="disabled" />
			</div>
			<div data-linea="14">
				<input type="text" id="serie_inicio" name="serie_inicio" placeholder="Ej: 10" data-er="^[0-9]+(\[0-9]{1,2})?$"/>
			</div>
		    <div data-linea="14">
				<input type="text" id="serie_fin" name="serie_fin" placeholder="Ej: 15" data-er="^[0-9]+(\[0-9]{1,2})?$"/>
			</div>
			<div data-linea="14">					
				<button type="button" id="btn_serie_aretes" name="btn_serie_aretes" onclick="agregarAretes()" class="mas">Agregar</button>
			</div>	
			<div>
				<table id="tablaArete">
					<tr>					
						<th width="78px">Quitar</th>
						<th>Serie inicio</th>
						<th>Serie fin</th>					
					</tr>								
					<tbody id="serie_aretes">
					</tbody>							
				</table>
			 </div>	
			 <div data-linea="16"></div>
			 <div data-linea="16">
					<label>Total aretes : </label> 
					<input type="text" id="totalAretes"	name="totalAretes" value="0" disabled="disabled" />
			 </div>						   		 
		</fieldset>	
	 </div>
	   <button id="btn_guardar" type="button" name="btn_guardar" onclick="grabarVacunacion()">Guardar vacunación</button> 	
	</div>
	  	
</form>

<script type="text/javascript">
	var array_lote= <?php echo json_encode($lotes); ?>;
	var array_validaCertificado = <?php echo json_encode($validaCertificado); ?>;
	var array_laboratorio = <?php echo json_encode($laboratorios); ?>;
	var array_areaSitios = <?php echo json_encode($areaSitios); ?>;
	var array_Sitios = <?php echo json_encode($sitios); ?>;
	
	$(document).ready(function(){		
		distribuirLineas();
		construirAnimacion($(".pestania"));			
		$("#fecha_emision").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		if($("#control_areteo").val()=="si"){
			$("#siAretea").show();
			$("#noAretea").hide();
		}else{
			$("#siAretea").hide();
			$("#noAretea").show();
		}	
		$("#infoEspecie").show();
     	$("#infoVacuna").hide();
     	$("#infoSitio").hide();
     	fecha = fechaActual();
		$("#fecha_emision").val(fecha);	
     	$("#cmbEspecie").focus();							
	 });
	 
	 $("#cmbEspecie").change(function(){	
		 $("#nombre_especie").val($("#cmbEspecie option:selected").text());		 	
	 });

	 $("#laboratorio").change(function(){   
		 if($("#laboratorio").val() != 0){         	    
	    	slote ='0';
	    	slote = '<option value="0">Lote...</option>';
	    	for(var i=0;i<array_lote.length;i++){	
				if ($("#laboratorio").val()==array_lote[i]['id_laboratorio']){																						  
					slote += '<option value="'+array_lote[i]['id_lote']+'">'+ array_lote[i]['numero_lote']+'</option>';
				}			  
			}	   
		    $('#lote').html(slote);
		    $("#lote").removeAttr("disabled");	
		 }	   
	 });

	 $("#tipoVacuna").change(function(event){	
		 if($("#tipoVacuna").val() != 0){
			$("#costo_vacuna").val($("#tipoVacuna option:selected").attr('data-costo'));
			sLaboratorio ='0';
			sLaboratorio = '<option value="0">Seleccionar...</option>';
			for(var i=0;i<array_laboratorio.length;i++){	
				if ($("#nombre_especie").val()==array_laboratorio[i]['nombre_especie']){	    
					sLaboratorio += '<option value="'+array_laboratorio[i]['id_laboratorio']+'"> '+ array_laboratorio[i]['nombre_laboratorio']+'</option>';
				}			  
			}	   		    
			$('#laboratorio').html(sLaboratorio);
			$("#laboratorio").removeAttr("disabled");	  			
		 }		 
	 });

	 $("#tipoArea").change(function(){	
		if ($("#tipoArea option:selected").attr('data-unidad') == ''){
	    	$('#lSuperficie').html("Superficie");
		}else{
			$('#lSuperficie').html("Superficie (" + $("#tipoArea option:selected").attr('data-unidad') + ")");
		}
	 });

	 $("#cmbSitio").change(function(){     
		 	sAreas ='0';
	    	sAreas = '<option value="0">Seleccionar...</option>';
	    	for(var i=0;i<array_areaSitios.length;i++){	
				if ($("#cmbSitio").val()==array_areaSitios[i]['id_sitio']){																						  
					sAreas += '<option value="'+array_areaSitios[i]['id_area']+'">'+ array_areaSitios[i]['nombre_area']+'</option>';
				}			  
			}	   
		    $('#cmbArea').html(sAreas);
		    $("#cmbArea").removeAttr("disabled");  					
	 });

	 $("#cmbEmpresa").change(function(){ 
		 if($("#cmbEmpresa").val() != 0){		    
		 	sSitio ='0';
	    	sSitio = '<option value="0">Seleccionar...</option>';
	    	for(var i=0;i<array_Sitios.length;i++){
	    		//alert($("#cmbEmpresa").val());
	    		//alert(array_Sitios[i]['identificador_operador']);
		    		
				if ($("#cmbEmpresa").val()==array_Sitios[i]['identificador_operador'])
				{																						  
					sSitio += '<option value="'+array_Sitios[i]['id_sitio']+'">'+ array_Sitios[i]['granja']+'</option>';
				}			  
			}	   
		    $('#cmbSitio').html(sSitio);
		    $("#cmbSitio").removeAttr("disabled"); 
		 }					
	 });

	 $("#cmbArea").change(function(){
		if($("#cmbArea").val() != 0){			 
			 $("#tabCatastro tr").remove();			 
			 $('#nuevoVacunacionAutoservicio').attr('data-opcion','accionesVacunacionAutoservicio');	
			 $('#nuevoVacunacionAutoservicio').attr('data-destino','res_catastro_productos');		 
		     $('#opcion').val('4');		
			 abrir($("#nuevoVacunacionAutoservicio"),event,false); //Se ejecuta ajax, busqueda de vacunador
			 //carga el nombre del sitio y area
			 $('#nombreSitio').val($("#cmbSitio option:selected").text());
			 $('#nombreArea').val($("#cmbArea option:selected").text());			 			 		
		}					 	
	 });

	 $("#btn_especie").click(function(event){	
			var h1 = ($('#txtEspecieValorada').val());
			nombreEspecie = $('#cmbEspecie option:selected').text();
			switch (nombreEspecie){
				case 'Porcinos':	
					if (certificadoEspecie(nombreEspecie)){				
						h=("00000000" + h1).slice (-7);
						valorCadena = h.length;			
						if (valorCadena < 6)
							alert('Error en el numero de especie valorada');
						$('#especie_valorada').val(h);	
						$('#nuevoVacunacionAutoservicio').attr('data-opcion','accionesVacunacionAutoservicio');
						$('#nuevoVacunacionAutoservicio').attr('data-destino','res_especie');
					    $('#opcion').val('3');		
						abrir($("#nuevoVacunacionAutoservicio"),event,false); //Se ejecuta ajax, busqueda de sitio


						$('#nuevoVacunacionAutoservicio').attr('data-opcion','accionesVacunacionAutoservicio');	
						 $('#nuevoVacunacionAutoservicio').attr('data-destino','resultadoVacunador');		 
					     $('#opcion').val('1');		
						 abrir($("#nuevoVacunacionAutoservicio"),event,false);	
						 
					}
					else	
						alert('No está activo la serie de números de certificados de vacunación para la especie ' + nombreEspecie);						
				  break;
				case 'Bovinos':
					if (certificadoEspecie(nombreEspecie)){	
				  		alert('Bovinos');
					}
					else
						alert('No está activo la serie de números de certificados de vacunación para la especie ' + nombreEspecie);					
				  break;
				case 'Bufalos':
					if (certificadoEspecie(nombreEspecie)){	
						alert('Bufalos');
					}
					else
						alert('No está activo la serie de números de certificados de vacunación para la especie ' + nombreEspecie);	
				  
				  break;				
				default:
				   		alert('No tiene la especie, serie activa para el certificado de vacunación');
			       break;							  
			}	

					 						
	 });

	 $("#fecha_emision").change(function(event){
			if($("#fecha_emision").val() != ""){	
				$("#cmbVacunador").focus();	
			}					 	
	 });	
	 	 	 
	 $("#btn_guardar").click(function(event){
		 if($("#estado").html() == ''){
			 $('#nuevoVacunacionAutoservicio').attr('data-opcion','accionesVacunacionAutoservicio');
			 $('#nuevoVacunacionAutoservicio').attr('data-destino','res_sitio');
		     $('#opcion').val('10');	
		     event.preventDefault();	
			 abrir($("#nuevoVacunacionAutoservicio"),event,false); //Se ejecuta ajax, busqueda de sitio	
			 abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);	
		 }		 		 	
	 });

    function certificadoEspecie(especie){
		certificado = false;
		sNumeroDocumento ='0';
		sNumeroDocumento = '<option value="0">Seleccionar...</option>';
		for(var i=0;i<array_validaCertificado.length;i++){			
			if (especie==array_validaCertificado[i]['nombre_especie']){	    
				certificado = true;
			}			  
		}	   	    		
		return certificado;	
	}	

	function agregarAreas(){
		chequearCamposArea();
	}
		
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCamposSitio(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#provincia").val()) || !esCampoValido("#provincia")){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}

		if(!$.trim($("#almacen").val()) || !esCampoValido("#almacen")){
			error = true;
			$("#almacen").addClass("alertaCombo");
		}		
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			if($('#hTipoArea').length == 0 ){
				$("#estado").html("Por favor ingrese una o varias áreas").addClass("alerta");
			}else{
				ejecutarJson(form);
				$("#estado").html("Los datos han sido actualizados satisfactoriamente.").addClass('correcto');
			}
		}
	}
	
	function quitarArete(fila){
		serieInicio = $("#serie_aretes tr").eq($(fila).index()).find("input[id='hSerie_inicio']").val();
		serieFin = $("#serie_aretes tr").eq($(fila).index()).find("input[id='hSerie_fin']").val();

		vRes = (parseInt(serieFin) - parseInt(serieInicio)) + 1;
		vTRes =  parseInt($("#totalAretes").val())-vRes;

		$("#serie_aretes tr").eq($(fila).index()).remove();
		$("#totalAretes").val(vTRes);
	}

	var arr = [];
	var arrAux = [];
	var arrAnimal = [];
	var ban = 0;
	var sw1 = 0;						
	var sw2 = 0;
	var sw3 = 0;

	function agregarAretes(){		
		chequearCamposAreaAretes();				
	}

	function chequearCamposAreaAretes(){		
		error = false;	
        res = false;
        resAreteVac = false;
        $(".alertaCombo").removeClass("alertaCombo");
	
		if(!$.trim($("#cmbArea").val()) || !esCampoValido("#cmbArea")){		
			$("#cmbArea").addClass("alertaCombo");
		}
        
        if ($("#serie_inicio").val().length != 0)
        	valInicio = $("#serie_inicio").val();		
	    else
	    	valInicio = 0;

	    if ($("#serie_fin").val().length != 0)
	    	valFin = $("#serie_fin").val();		
	    else
	    	valFin = 0;

	    resAreteVac = parseInt(valInicio)  <=  parseInt(valFin);
			
	    if (error == true){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			  if(resAreteVac==true){				
				id_arete = $("#idArete").val();
				if (id_arete==0)
					codigo_arete = $("#cmbArea option:selected").val();
				else
					codigo_arete = $("#idArete").val()+1;		
				$("#idArete").val(codigo_arete);
				if($("#cmbArea #r_"+codigo_arete.replace(/ /g,'')).length==0){	
					if ($("#totalAretes").val() !=""){
						TArete = $("#totalAretes").val();
					}else{
						TArete = 0;
					}					
					numArete = parseInt(TArete) + (($("#serie_fin").val() - $("#serie_inicio").val()) + 1);	
					res = numArete <= parseInt($("#tVacunados").val());
									
					if (res){
						$("#serie_aretes").append("<tr id='r_"+codigo_arete.replace(/ /g,'')+"'><td><button type='button' onclick='quitarArete(\"#r_"+codigo_arete.replace(/ /g,'')+"\")' class='menos'>Quitar</button></td><td><input id='hCodSerie_aretes' name='hCodSerie_aretes[]' value='"+codigo_arete+"' type='hidden'><input id='hSerie_aretes' name='hSerie_aretes[]' value='"+$("#areas_arete option:selected").text()+"'type='hidden'><input id='hSerie_inicio' name='hSerie_inicio[]' value='"+$("#serie_inicio").val()+"' type='hidden'>"+$("#serie_inicio").val()+"</td><td>"+$("#serie_fin").val()+"<input id='hSerie_fin' name='hSerie_fin[]' value='"+$("#serie_fin").val()+"' type='hidden'></td></tr>");
						$("#serie_inicio").val("");
						$("#serie_fin").val("");
						$("#totalAretes").val(numArete);	
						
					}
					else{
						TArete = 0;
						$("#serie_inicio").val("");
						$("#serie_fin").val("");																																				
						alert('Error al ingresar la serie de aretes');
					}													
				}
			  }
			  else {
				  alert('Error al ingresar la serie de aretes !!!');
			  }									
		 }
    }

	function fechaActual() {
		  var date = new Date();
		  var year = date.getFullYear();
		  var month = (1 + date.getMonth()).toString();
		  month = month.length > 1 ? month : '0' + month;
		  var day = date.getDate().toString();
		  day = day.length > 1 ? day : '0' + day;
		  return  day + '/' + month + '/' +  year;
	}	

	function grabarVacunacion(){
		chequearCamposGrabarVacunacion();
	}
		
	function chequearCamposGrabarVacunacion(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		if($("#tipoVacuna").val()==0){
			error = true;
			$("#tipoVacuna").addClass("alertaCombo");
		}
		if($("#laboratorio").val()==0){
			error = true;
			$("#laboratorio").addClass("alertaCombo");
		}
		if($("#cmbVacunador").val()==0){
			error = true;
			$("#cmbVacunador").addClass("alertaCombo");
		}
		if($("#laboratorio").val()==0){
			error = true;
			$("#laboratorio").addClass("alertaCombo");
		}
		if($("#lote").val()==0){
			error = true;
			$("#lote").addClass("alertaCombo");
		}
		if($("#fecha_emision").val()==""){
			error = true;
			$("#fecha_emision").addClass("alertaCombo");
		}					
		if($("#cmbVacunador").val()==0){
			error = true;
			$("#cmbVacunador").addClass("alertaCombo");
		}
		if($("#cmbSitio").val()==0){
			error = true;
			$("#cmbSitio").addClass("alertaCombo");
		}						
		if (error == true){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{                   
			$("#estado").html("").removeClass('alerta');			      	
		}//estado
	}//inicio
	
</script>
<style type="text/css">
#tablaArete td 
{
font-size:1em;
border:1px solid rgba(0,0,0,.1);
padding:3px 7px 2px 7px;
}
#tablaArete th 
{
font-size:1em;
border:1px solid rgba(0,0,0,.1);
padding:3px 7px 2px 7px;
background-color: rgba(0,0,0,.1)
}
#tablaVacunaAnimal td 
{
font-size:1em;
border:1px solid rgba(0,0,0,.1);
padding:3px 7px 2px 7px;
}
#tablaVacunaAnimal th 
{
font-size:1em;
border:1px solid rgba(0,0,0,.1);
padding:3px 7px 2px 7px;
background-color: rgba(0,0,0,.1)
}
</style>
