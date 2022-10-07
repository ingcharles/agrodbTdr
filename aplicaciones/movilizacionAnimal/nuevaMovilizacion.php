<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$conexion = new Conexion();
$cm = new ControladorMovilizacionAnimal();
$cc = new ControladorCatalogos();
$cv = new ControladorVacunacionAnimal();

$tipoAutorizado = $cm->listaTipoAutorizado($conexion);
$autorizado = $cm->listaAutorizado($conexion,$tipoAutorizado,$tipoBusquedaOrigen, $varVacunador);
?>
<header>
	<h1>Nuevo movilización animal</h1>
</header>
<div id="estado"></div>
<form id='nuevoMovilizacion' data-rutaAplicacion='movilizacionAnimal' data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="idEventoMovilizacion" name="idEventoMovilizacion" value="0" />
	<input type="hidden" id="nombreEspecie" name="nombreEspecie" value="" />
	<input type="hidden" id="usuario_responsable" name="usuario_responsable" value="<?php echo $_SESSION['usuario'];?>" />
	<input type="hidden" id="usuario_empresa" name="usuario_empresa" value="<?php echo $_SESSION['usuario'];?>" />
	<input type="hidden" id="serieCertificadoMovilizacion" name="serieCertificadoMovilizacion" value="0" />
	<input type="hidden" id="numeroCertificadoMovilizacion" name="numeroCertificadoMovilizacion" value="0" />
	
	<input type="hidden" id="idSitioOrigen" name="idSitioOrigen" value="0" />
	<input type="hidden" id="idAreaOrigen" name="idAreaOrigen" value="0" />
	<input type="hidden" id="idEspecie" name="idEspecie" value="0" />
	
	<input type="hidden" id="identificador_emisor" name="identificador_emisor" value="0" />	
	<input type="hidden" id="identificador_autoservicio" name="identificador_autoservicio" value="0" />
	<input type="hidden" id="identificador_autorizado" name="identificador_autorizado" value="0" />	
	
	<input type="hidden" id="id_sitio_origen" name="id_sitio_origen" value="0" />
	<input type="hidden" id="id_area_origen" name="id_area_origen" value="0" />
	<input type="hidden" id="id_sitio_destino" name="id_sitio_destino" value="0" />
	<input type="hidden" id="id_area_destino" name="id_area_destino" value="0" />
	
	<input type="hidden" id="idTipoMovilizacionOrigen" name="idTipoMovilizacionOrigen" value="0" />
	<input type="hidden" id="lugarEmision" name="lugarEmision" value="0" />	
	
	<input type="hidden" id="total_movilizados_aux" name="total_movilizados_aux" value="0" />
	
	<input type="hidden" id="codigoProvinciaOrigen" name="codigoProvinciaOrigen" value="0" />
	<input type="hidden" id="codigoProvinciaDestino" name="codigoProvinciaDestino" value="0" />
		
	<div class="pestania" id="ParteI">
		<fieldset>		
			<legend>Información de la movilización :</legend>				
			<div data-linea="1">
				<label>Lugar emisión:</label> 
				<select id="cmbLugarEmision" name="cmbLugarEmision">
					<option value="0">Seleccione...</option>
					<?php									
						$emisionMovilizacion = $cm-> lugarEmisionEmpresa($conexion, $_SESSION['usuario']);					
						while ($fila = pg_fetch_assoc($emisionMovilizacion)){
					    	echo '<option value="'. $fila['identificador_emisor'].'" 
								  data-emisor="'. $fila['identificador_emisor'].'" 
		                     	  data-nombre-emisor="'. $fila['nombre_emisor'].'" 
		                     	  data-lugar-emision="'. $fila['nombre_lugar_emision'].'" 
		                      	  data-autoservicio="'. $fila['identificador_autoservicio'].'">'.$fila['nombre_lugar_emision']. '</option>';
					    }					
					?>
				</select>
			</div>													
		</fieldset>		

		<fieldset id="fsLugarOrigenAutoservicio" name="fsLugarOrigenAutoservicio">		
			<legend>Lugar de la movilización de origen</legend>	
			<div data-linea="1">
				<label>CI: Autorizado:</label> 
				<input type="text" id="identificacion_autorizado" name="identificacion_autorizado" disabled="disabled"/>
			</div>			   			  
			<div data-linea="1">
				<label>Autorizado:</label> 
				<input type="text" id="nombre_autorizado" name="nombre_autorizado" disabled="disabled"/>
			</div>	
			<div data-linea="2" id="res_a"></div>										
			<div data-linea="2">
				<label>Área origen:</label> 
				<select id="cmbAreasOrigen" name="cmbAreasOrigen" disabled="disabled">
				</select>
			</div>	
			<div data-linea="3">
				<label>Especie:</label> 
				<select id="cmbEspecie" name="cmbEspecie" disabled="disabled">	
				</select>												
			</div>				
		</fieldset>	

		<fieldset id="detalleMovilizacionI">
			<legend>Detalle de animales para movilizar</legend>

			<div data-linea="1" id="res_certificado_vacunacion">				
			    <input type="hidden" id="numeroCV" name="numeroCV"/>
				<input type="hidden" id="fechaCV" name="fechaCV"/>
			</div>
			<hr/>											
			<div data-linea="2">
				<label>Animales :</label>
			</div>
			<div data-linea="2">
				<label>No.Existentes :</label>
			</div>			
			<div data-linea="2">
				<label>Cantidad :</label>
			</div>			
			<div data-linea="2">
					<label> Agregar</label>
				</div>							
			<div data-linea="3">
				<select id="cmbAnimales" name="cmbAnimales" disabled="disabled">
				</select>	
			</div>				
			<div data-linea="3">
				<input type="text" id="existente" name="existente" disabled="disabled"/>
			</div>
			<div data-linea="3">
				<input type="text" id="cantidad" name="cantidad" placeholder="Ej: 8" onkeypress='ValidaSoloNumeros()' />
			</div>
			<div data-linea="3">
				<button type="button" id="btnAnimales" name="btnAnimales" onclick="agregarAnimales()" class="mas">Agregar</button>
			</div>
			<div data-linea="4">
				<table id="tablaAnimales">
					<tr>						
						<th width="126px">No.Certificado</th>
						<th>Fecha.Certificado</th>
						<th width="60px">Animal</th>
						<th>Cantidad</th>
						<th></th>			
						<th style='text-align:left' >Quitar</th>			
					</tr>
					<tbody id="animalMovilizacion">
					</tbody>
				</table>
			</div>
			<div data-linea="5">
				<label>Total animales movilizados :</label>
				<input type="text" id="total_movilizados" name="total_movilizados" value="0" readOnly/>
			</div>			
		</fieldset>
	</div>
	<div class="pestania" id="ParteIII">
		<!-- <fieldset id="lugarDestino" name="lugarDestino">
			<legend>Lugar de la movilización de destino</legend>
			<div id="div1" data-linea="10">
				<label>Tipo movilización destino :</label> 
				<select id="cmbTipoMovilizacionDestino" name="cmbTipoMovilizacionDestino">
					<option value="0">Seleccione...</option>
					< ?php									
						$tipoDeMovilizacion = $cm->tipoMovilizacionAnimal($conexion, 'destino');					
						while ($fila = pg_fetch_assoc($tipoDeMovilizacion)){
					    	echo '<option value="'.$fila['id_tipo_movilizacion_animales'].'">'.$fila['lugar_movilizacion_animal'].'</option>';
					    }					
					?>
				</select>
			</div>				
			<div id="div2" data-linea="10">
				<input type="text" id="txtSitioBusquedaDestino" name="txtSitioBusquedaDestino" />							
			</div>
			<div id="div3" data-linea="11">
				<button type="button" id="btnBusquedaSitioDestino" name="btnBusquedaSitioDestino">Buscar sitio destino</button>
			</div>			
			<div id="res_sitio_destino" data-linea="12"></div>			
			<div id="div4" data-linea="13">
				<label>Areas destino:</label> 
				<select id="cmbAreasDestino" name="cmbAreasDestino" disabled="disabled">
				</select>
			</div>								
		</fieldset> -->
		<fieldset id="lugarDestinoAutoservicio" name="lugarDestinoAutoservicio">
			<legend>Lugar de la movilización de destino</legend>
			<div id="div1" data-linea="10">
				<label>Tipo movilización destino :</label> 
				<select id="cmbTipoMovilizacionDestinoAutoservicio" name="cmbTipoMovilizacionDestinoAutoservicio">
					<option value="0">Seleccione...</option>
					<?php									
						$tipoDeMovilizacion = $cm->tipoMovilizacionAnimal($conexion, 'destino');					
						while ($fila = pg_fetch_assoc($tipoDeMovilizacion)){
							if($fila['id_tipo_movilizacion_animales']==1 || $fila['id_tipo_movilizacion_animales']==4){
								echo '<option value="'.$fila['id_tipo_movilizacion_animales'].'">'.$fila['lugar_movilizacion_animal'].'</option>';	
							}					    	
					    }					
					?>
				</select>
			</div>									
			<div data-linea="2" id="res_a2"></div>
			<div id="div4" data-linea="13">
				<label>Área destino:</label> 
				<select id="cmbAreasDestinoAutoservicio" name="cmbAreasDestinoAutoservicio" disabled="disabled">
				</select>
			</div>								
		</fieldset>
		<fieldset id="numeroCertificado" name="numeroCertificado">
			<legend>Número de certificado de movilización</legend>
			<div data-linea="1">
				<label>Certificado de movilización :</label> 
				<input type="text" id="txtNumeroCertificado" name="txtNumeroCertificado" />
			</div>
			<div data-linea="1">
				<button type="button" id="btn_certificado_movilizacion" name="btn_certificado_movilizacion">Buscar número certificado</button>
			</div>
			<div data-linea="2" id="res_certificado_movilizacion">
			</div>
		</fieldset>
		<fieldset id="datosCertificado" name="datosCertificado">
			<legend>Datos del certificado de movilización</legend>			
			<div data-linea="1">
				<label>Sitio origen</label> 
				<input type="text" id="sitio_origen" name="sitio_origen" disabled="disabled"/>
			</div>	

			 <div data-linea="1">
				<label>Sitio destino</label> 
				<input type="text" id="sitio_destino" name="sitio_destino" disabled="disabled"/>
			</div>

			<div data-linea="2">
				<label>Valido por</label> 
				<select id="cmbTiempo" name="cmbTiempo">
					<option value="0">Seleccione...</option>					
					<option value="6">6 horas</option>
					<option value="12">12 horas</option>									
					<option value="18">18 horas</option>
					<option value="24">24 horas</option>
				</select>
			</div>
			<div data-linea="2">
				<label>Medio transporte</label> 
				<select id="cmbMedioTransporte" name="cmbMedioTransporte">
					<option value="0">Seleccione...</option>					
					<option value="Terrestre">Terrestre</option>
					<!--<option value="Aereo">Aereo</option> -->									
				</select>
			</div>
			<div data-linea="3" id="placa">
				<label>Placa</label> 
				<input type="text" id="txtPlaca" name="txtPlaca" maxlength="8" onKeyUp="this.value=this.value.toUpperCase();"/>
			</div>			
			<div data-linea="3" id="conductor">
				<label>CI: Conductor</label> 
				<input type="text" id="identificacion_conductor" name="identificacion_conductor" maxlength="10" onkeypress='ValidaSoloNumeros()'/>
			</div>
			<div data-linea="4">
				<label>Fecha movilización</label> 
				<input type="text" id="fecha_movilizacion" name="fecha_movilizacion" />
			</div>
			<div data-linea="4">
				<label>Hora</label> 					
			    <input id="hora" name="hora" type="text" placeholder="Hora 10:30" data-er="^\([0-9]{2}\) [0-9]{2}?" data-inputmask="'mask': '99:99'" size="15" />				
			</div>	
			
			<div data-linea="5">
				<label>Descripción</label> 
				<input type="text" id="txtDescripcionTransporte" name="txtDescripcionTransporte" onKeyUp="this.value=this.value.toUpperCase();"/>
			</div>
		</fieldset>	
		<div id='btnGuardar'>
			<button id="btn_guardar" type="button" name="btn_guardar" onclick="grabarMovilizacion()">Guardar movilización</button>
		</div>	
		<input type="hidden" id="opcion" name="opcion" value="0">		
	</div>
		    	
</form>
</body>
<script type="text/javascript">	
	var array_tipoAutorizado = <?php echo json_encode($tipoAutorizado); ?>;
						
	$(document).ready(function(){	
		construirValidador();	
		//$('#ParteII').hide();
		$('#divCertificado').hide();
		$('#detalleMovilizacionI').hide();
		$('#detalleMovilizacionII').hide();
		$('#autorizadoMovilizacion').hide();
		$('#lugarOrigen').hide();		
		$('#fsLugarOrigen').hide();
		$('#datosCertificado').hide();
		$('#fsLugarOrigenAutoservicio').hide();
		//TODO: ELIMINAR ESTA LINEA INSERVIBLE
		$('#numeroCertificado').hide();//datos de la movilizacion - certificado
		$('#datosCertificado').hide();  
		$('#btnGuardar').hide()
		construirAnimacion($(".pestania"));	
		$("#fecha_movilizacion").datepicker({
		      changeMonth: true,
		      changeYear: true
		});	
		$("#cmbLugarEmision").focus();
		$('#placa').hide();
		$('#conductor').hide();

			
		distribuirLineas();

		
	
	});

	$("#cmbTipoMovilizacionOrigen").change(function(event){         
 		stipoAutorizacion = '0';
 		stipoAutorizacion = '<option value="0">Seleccione...</option>';
 		for(var i=0;i<array_tipoAutorizado.length;i++){	 			
 	 		if ($("#cmbTipoMovilizacionOrigen option:selected").text()==array_tipoAutorizado[i]['tipo_movilizacion']){	    
 				stipoAutorizacion += '<option value="'+array_tipoAutorizado[i]['id_tipo_autorizado']+'">'+array_tipoAutorizado[i]['nombre_autorizado']+'</option>';
 			}			  
 		}   
 	    $('#cmbTipoAutorizado').html(stipoAutorizacion);
 	 	$('#cmbTipoAutorizado').removeAttr("disabled");
			 				
     });

	$("#btnAutorizado").click(function(event){	
		if ($("tipoBusquedaResponsable").val()!=0){
			$('#nuevoMovilizacion').attr('data-opcion','accionesMovilizacionAnimal');	
			$('#nuevoMovilizacion').attr('data-destino','res_autorizado');		 
	     	$('#opcion').val('1');		
		 	abrir($("#nuevoMovilizacion"),event,false); //Se ejecuta ajax, busqueda de vacunador
			$('#txtBusquedaResponsable').val('');
			//distribuirLineas();
		}	
		else
			$("#estado").html("Seleccione el tipo de busqueda del vacunador").addClass('alerta'); 	 		
	});

	/*$("#btnBusquedaSitioDestino").click(function(event){
		 $('#nuevoMovilizacion').attr('data-opcion','accionesMovilizacionAnimal');
		 $('#nuevoMovilizacion').attr('data-destino','res_sitio_destino');
	     $('#opcion').val('3');		
		 abrir($("#nuevoMovilizacion"),event,false); //Se ejecuta ajax, busqueda de sitio
		 $('#txtSitioBusquedaDestino').val('');						 		
	 });*/

	$("#cmbEspecie").change(function(event){	
         //validar si es autoservicio
         $('#idSitioOrigen').val($('#cmbSitioOrigen').val());
         $('#idAreaOrigen').val($('#cmbAreasOrigen').val());
         $('#idEspecie').val($('#cmbEspecie').val());
         $('#detalleMovilizacionI').show();
			
		 $('#nuevoMovilizacion').attr('data-opcion','accionesMovilizacionAnimal');
		 $('#nuevoMovilizacion').attr('data-destino','res_certificado_vacunacion');
	     $('#opcion').val('4');		
		 abrir($("#nuevoMovilizacion"),event,false); //Se ejecuta ajax, busqueda de sitio
		 			 		
	 });
	
	$("#cmbNumeroCertificadoVacunacion").change(function(event){
		 $('#nuevoMovilizacion').attr('data-opcion','accionesMovilizacionAnimal');
		 $('#nuevoMovilizacion').attr('data-destino','res_catastro_animal');
	     $('#opcion').val('5');		
		 abrir($("#nuevoMovilizacion"),event,false); //Se ejecuta ajax, busqueda de sitio
		 //$('#txtSitioBusquedaDestino').val('');						 		
	 });

	$("#btn_certificado_movilizacion").click(function(event){				
		 var h1 = ($('#txtNumeroCertificado').val());
		 h=("0000000000" + h1).slice (-9);
		 valorCadena = h.length;			
		 if (valorCadena < 6)
	 		alert('Error en el número de certificado de movilización');

 		 $('#serieCertificadoMovilizacion').val(h);			 
 		 $('#nuevoMovilizacion').attr('data-opcion','accionesMovilizacionAnimal');
		 $('#nuevoMovilizacion').attr('data-destino','res_certificado_movilizacion');
		 $('#opcion').val('6');		
		 abrir($("#nuevoMovilizacion"),event,false); //Se ejecuta ajax, busqueda de sitio	
								 			 						 		
	 });

	function movimientoOrigenDestino(){//autoservicio		
		if ($("#cmbTipoMovilizacionDestinoAutoservicio").val()==1)
			$("#idEventoMovilizacion").val(0);
				
		if ($("#cmbTipoMovilizacionDestinoAutoservicio").val()==4)
			$("#idEventoMovilizacion").val(1);		
	}
	 
	$("#btn_guardar").click(function(event){	
		if($("#estado").html() == '')
		{		 	    		  		      	  
			event.preventDefault();
			//case 1://Es sitio/predio/granja (sitio a sitio) ok
			//case 2://Evento destino (hacienda a ferias)
			//case 3://Evento origen (ferias a hacienda)
			var tipoTransaccion = 0;
			var tipoNegocio = 1; //1 = Autoservicio ; 2 = Traspatio
	
			if (tipoNegocio == 1){//1 = Autoservicio
				if ($("#cmbTipoMovilizacionDestinoAutoservicio").val()==1)//Sitio
					tipoTransaccion = 1;
					
				if ($("#cmbTipoMovilizacionDestinoAutoservicio").val()==4)//Camal
					tipoTransaccion = 2;	
				 
				switch (tipoTransaccion){
					case 1: 					
						$('#nuevoMovilizacion').attr('data-opcion','accionesMovilizacionAnimalSitio');
						$('#nuevoMovilizacion').attr('data-destino','res_movilizacion');
					    $('#opcion').val('10');		     	
						abrir($("#nuevoMovilizacion"),event,false); //Se ejecuta ajax, busqueda de sitio					
						abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);	//evento				
						break;
					case 2:
						$('#nuevoMovilizacion').attr('data-opcion','accionesMovilizacionAnimalCamal');
						$('#nuevoMovilizacion').attr('data-destino','res_movilizacion');
					    $('#opcion').val('10');		     	
						abrir($("#nuevoMovilizacion"),event,false); //Se ejecuta ajax, busqueda de sitio
						abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true); //evento
						break;
					case 3: 
						break;
				}	
			} 
		}//fin del if
	 }); 

	$("#cmbEspecie").change(function(event){ 	       
    	if ($("#cmbEspecie").val() !='0'){
    		$('#txtEspecie').val($("#cmbEspecie option:selected").text());  
    		$('#txtEspecieII').val($("#cmbEspecie option:selected").text());
    		$('#nombreEspecie').val($("#cmbEspecie option:selected").text());      		    		  		  		   
    	}	    	
	});

	$("#cmbMedioTransporte").change(function(event){ 
		if ($("#cmbMedioTransporte").val() !='0'){	       
	    	if ($("#cmbMedioTransporte").val() =='Terrestre'){
	    		$('#placa').show();
				$('#conductor').show();
	    	}
	    	if ($("#cmbMedioTransporte").val() =='Aereo'){
	    		$('#placa').hide();
				$('#conductor').hide();
	    	}
	    	$('#btnGuardar').show();
		}
	});

	$("#cmbLugarEmision").change(function(event){ 	       
    	if ($("#cmbLugarEmision").val() !='0'){
    		$('#lugarOrigen').show();

			 $('#fsLugarOrigenAutoservicio').show();
			 $('#autorizadoMovilizacion').hide();
			 $('#lugarDestino').hide();

			 $('#lugarEmision').val($("#cmbLugarEmision option:selected").attr('data-lugar-emision')); 
			 $('#identificador_autorizado').val($("#cmbLugarEmision option:selected").attr('data-emisor'));
			 $('#identificador_emisor').val($("#cmbLugarEmision option:selected").attr('data-emisor'));
			 $('#identificador_autoservicio').val($("#cmbLugarEmision option:selected").attr('data-autoservicio'));
			 $('#usuario_empresa').val($("#cmbLugarEmision option:selected").attr('data-autoservicio'));
			 $('#nombre_autorizado').val($("#cmbLugarEmision option:selected").attr('data-nombre-emisor'));
			 $('#identificacion_autorizado').val($("#cmbLugarEmision option:selected").attr('data-emisor'));

			 $('#idResponsableMovilizacion').val($("#cmbLugarEmision").val());
			 $('#idTipoMovilizacionOrigen').val(1); //1 es Sitio a Sitio = 2 es Sitio a Camal
			 
    		 $('#nuevoMovilizacion').attr('data-opcion','accionesMovilizacionAnimal');
    		 $('#nuevoMovilizacion').attr('data-destino','res_a');
    		 $('#opcion').val('2');		
    		 abrir($("#nuevoMovilizacion"),event,false);

    	}
	});

	$("#cmbTipoMovilizacionDestinoAutoservicio").change(function(event){ 	       
    	if ($("#cmbTipoMovilizacionDestinoAutoservicio").val() !='0'){
    		validarDetalle();
    		if($("#estado").html() == ''){			        	
	    		 $('#nuevoMovilizacion').attr('data-opcion','accionesMovilizacionAnimal');
	    		 $('#nuevoMovilizacion').attr('data-destino','res_a2');
	    		 $('#opcion').val('5');		
	    		 abrir($("#nuevoMovilizacion"),event,false);
    		}
    		else{
    			$('#cmbTipoMovilizacionDestinoAutoservicio').val(0);
        	}
    		   		
    	}
	});
	
	//activa las opciones de el tipo de movilización, para el detalle catastro y vacunación
	$("#cmbTipoMovilizacionOrigen").change(function(){ 	       
    	if ($("#cmbTipoMovilizacionOrigen").val() !='0' ){          
    		if ($("#cmbTipoMovilizacionOrigen").val()==1){//sitios/predios/granjas
    			$('#detalleMovilizacionI').show();
    			$('#detalleMovilizacionII').hide();
    		}
    		if ($("#cmbTipoMovilizacion").val()==2){ //ferias
    			$('#detalleMovilizacionI').hide();
    			$('#detalleMovilizacionII').show();
    		}   		  		    
    	}	    	
	 });

	$("#cmbTipoBusquedaSitioDestino").change(function(){ 
		if (($("#cmbTipoBusquedaSitioOrigen").val()==1) && ($("#cmbTipoBusquedaSitioDestino").val()==1)){
			$("#idEventoMovilizacion").val(0);
		}
		if (($("#cmbTipoBusquedaSitioOrigen").val()==1) && ($("#cmbTipoBusquedaSitioDestino").val()==2)){
			$("#idEventoMovilizacion").val(1);
		}
		if (($("#cmbTipoBusquedaSitioOrigen").val()==2) && ($("#cmbTipoBusquedaSitioDestino").val()==1)){
			$("#idEventoMovilizacion").val(3);			
		}
		if (($("#cmbTipoBusquedaSitioOrigen").val()==2) && ($("#cmbTipoBusquedaSitioDestino").val()==2)){
			$("#idEventoMovilizacion").val(4);			
		}
   	
	 });


	//TODO ELIMINAR ESTAS LINEAS INSERVIBLE
	$("#cmbAreasDestinoAutoservicio").change(function(){
		if ($("#cmbTipoBusquedaSitioOrigen").val()!=0){
		//	$('#numeroCertificado').show();
		}
	});

	function agregarAnimales(){
		
		chequearCamposAnimales();
	}
	
	function chequearCamposAnimales(form){				
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#cantidad").val()) || !esCampoValido("#cantidad")){	
			 error = true;		
			$("#cantidad").addClass("alertaCombo");
		}
		else
		   {
			   vTotal = parseInt($("#existente").val()) - parseInt($("#cantidad").val());
			   if (vTotal < 0){
				   error = true;
			       $("#cantidad").addClass("alertaCombo");
			   }
		   }

		if (error == true){
			$("#estado").html("Por favor revise la cantidad ingresada.").addClass('alerta');
		}
		else{		 
			$("#estado").html("").removeClass('alerta');
			if($("#cmbAnimales").val()!="0"){	

			$('#fsLugarDestino').show();
			numeroCV = $("#cmbAnimales option:selected").attr('data-documento');
	  		fechaCV = $("#cmbAnimales option:selected").attr('data-fecha');
	  		fechaNacimientoCV = $("#cmbAnimales option:selected").attr('data-fecha-nacimiento');
	  		edadProductoCV = $("#cmbAnimales option:selected").attr('data-edad');  				
	  				           			
	  		if(numeroCV =='Ninguno') 
	  		fechaCV = '';
	  		if(fechaCV =='undefined') 
	  		fechaCV = '';

			var codigo = $("#cmbAnimales").val() + numeroCV.substring(13,20);
	  		var cantidadTotal=$("#cantidad").val();

	  		$('#animalMovilizacion tr').each(function() {
	  			if (numeroCV==$(this).find('td').eq(0).find('input').val() && $("#cmbAnimales option:selected").val()==$(this).find('td').eq(2).find('input').val()) {

	  				var cantidad = $(this).find('td').eq(3).text();
	  				cantidadTotal=parseInt(cantidad)+parseInt($("#cantidad").val());
	  				quitarAnimal("#r_"+codigo.replace(/ /g,''));
	  				calcularTotal();

	  				var cantidadDetalle=parseInt($(this).find('td').eq(3).text())+parseInt($("#cantidad").val());
	  	  			if($('#existente').val()<cantidadDetalle){
	  	  				cantidadTotal=parseInt(cantidad);
	  	  	  			$("#estado").html("No se puede movilizar cantidad de animales mayor a los existentes.").addClass('alerta');
	  	  			}
	  	  			
	  			}
	 
	  		});

	  		cadena = "<tr id='r_"+codigo.replace(/ /g,'')+"'>";
	  		cadena = cadena + "<td><input id='hNumeroCertificado' name=hNumeroCertificado[]' value='"+numeroCV+"'type='hidden'>"+numeroCV+"</td>";              
	  		cadena = cadena + "<td align='center'><input id='hFechaCertificado' name='hFechaCertificado[]' value='"+fechaCV+"' type='hidden'>"+fechaCV+"</td>";
	  		cadena = cadena + "<td ><input id='hCodigoAnimal' name='hCodigoAnimal[]' value='"+$("#cmbAnimales").val()+"' type='hidden'>"+$("#cmbAnimales option:selected").text()+"</td>";
	  		cadena = cadena + "<td align=center>"+cantidadTotal+"<input id='hCantidad' name='hCantidad[]' value='"+cantidadTotal+"' type='hidden'></td>";
	  		cadena = cadena + "<td ><input id='hFechaNacimiento' name='hFechaNacimiento[]' value='"+fechaNacimientoCV+"' type='hidden'><input id='hEdadProducto' name='hEdadProducto[]' value='"+edadProductoCV+"' type='hidden'></td>";
	  		cadena = cadena + "<td ><button type='button' onclick='quitarAnimal(\"#r_"+codigo.replace(/ /g,'')+"\")' class='menos'>Quitar</button></td>";
	  		cadena = cadena + "</tr>";

			$("#animalMovilizacion").append(cadena);

			calcularTotal();
	  		$("#cantidad").val("");
	  		$("#cantidad").focus();	

			}	
		}
	}//inicio

	function quitarAnimal(fila){
		$("#animalMovilizacion tr").eq($(fila).index()).remove();
		calcularTotal();	
	}

	function calcularTotal(){
		var total = 0;
	    var sumarCantidad=0;
	    
		$('#animalMovilizacion tr').each(function(row, tr) {
			var sumarCantidad =$(this).find('td').eq(3).text();
		   	if (!isNaN(sumarCantidad)) 
		        total += parseInt(sumarCantidad);        
		 });      
		 
		$('#total_movilizados').val(total);
	};

	function validarDetalle(){
		chequearDetalle();
	}
		
	function chequearDetalle(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#cmbSitioOrigen").val()==0){
			error = true;
			$("#cmbSitioOrigen").addClass("alertaCombo");
		}	
		if($("#cmbAreasOrigen").val()==0){
			error = true;
			$("#cmbAreasOrigen").addClass("alertaCombo");
		}		
		if($("#cmbEspecie").val()==0){
			error = true;
			$("#cmbEspecie").addClass("alertaCombo");
		}		
		if($("#total_movilizados").val()=="0"){
			error = true;
			$("#total_movilizados").addClass("alertaCombo");
		}

		if (error == true){
			$("#estado").html("Revise la información ingresada. No se ha agregado productos movilización. Por favor igresar el producto...").addClass('alerta');
		}else{                   
			$("#estado").html("").removeClass('alerta');			      	
		}		
	}//inicio
	
	function grabarMovilizacion(){
		chequearMovilizacion();
	}
		
	function chequearMovilizacion(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#cmbSitioOrigen").val()==0){
			error = true;
			$("#cmbSitioOrigen").addClass("alertaCombo");
		}	
		if($("#cmbAreasOrigen").val()==0){
			error = true;
			$("#cmbAreasOrigen").addClass("alertaCombo");
		}		
		if($("#cmbEspecie").val()==0){
			error = true;
			$("#cmbEspecie").addClass("alertaCombo");
		}
		if($("#cmbTipoMovilizacionDestinoAutoservicio").val()==0){
			error = true;
			$("#cmbTipoMovilizacionDestinoAutoservicio").addClass("alertaCombo");
		}
		if($("#cmbSitioDestinoAutoservicio").val()==0){
			error = true;
			$("#cmbSitioDestinoAutoservicio").addClass("alertaCombo");
		}
		if($("#cmbMedioTransporte").val()==0){
			error = true;
			$("#cmbMedioTransporte").addClass("alertaCombo");
		}		
		if($("#cmbTiempo").val()==0){
			error = true;
			$("#cmbTiempo").addClass("alertaCombo");
		}		
		if($("#fecha_movilizacion").val()==""){
			error = true;
			$("#fecha_movilizacion").addClass("alertaCombo");
		}	
		if($("#hora").val()=="" || $("#hora").val().substring(0, 2)==24 && $("#hora").val().substring(3, 5)>00 || $("#hora").val().substring(0, 2)>24 && $("#hora").val().substring(3, 5)>=00 ){
				error = true;
				$("#hora").addClass("alertaCombo");
		}

		var fechaMovilizacion = $("#fecha_movilizacion").val().substring(6, 10)+$("#fecha_movilizacion").val().substring(3, 5)+$("#fecha_movilizacion").val().substring(0, 2)+$("#hora").val().substring(0, 2)+$("#hora").val().substring(3, 5);
		var f=new Date();
		var fechaRegistro =f.getFullYear()+""+("0" + (f.getMonth()+1)).slice (-2) +""+ ("0" + f.getDate()).slice (-2) +  "" + ("0" + f.getHours()).slice (-2)+""+("0" + f.getMinutes()).slice (-2);
		//alert(fechaMovilizacion+'<'+fechaRegistro);

		if(fechaMovilizacion<fechaRegistro){
			error = true;
			$("#hora").addClass("alertaCombo");
		}
		
		if($("#txtDescripcionTransporte").val()==""){
			error = true;
			$("#txtDescripcionTransporte").addClass("alertaCombo");
		}
		if($("#txtPlaca").val()==""){
			error = true;
			$("#txtPlaca").addClass("alertaCombo");
		}
		if($("#identificacion_conductor").val()==""){
			error = true;
			$("#identificacion_conductor").addClass("alertaCombo");
		}

		if (error == true){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{                   
			$("#estado").html("").removeClass('alerta');			      	
		}		
	}//inicio
	
	function quitarTicket(fila){
		$("#tiketMovilizacion tr").eq($(fila).index()).remove();
	}

	function ValidaSoloNumeros() {
		 if ((event.keyCode < 48) || (event.keyCode > 57))
		  event.returnValue = false;
	}	

</script>


     		
