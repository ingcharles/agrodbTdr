<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>HistoriasClinicas' data-opcion='certificadomedico/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>Certificados e Informes</legend>				

		<div data-linea="1">
			<label for="descripcion_certificado">Tipo de documento: </label>
			<select
				id="descripcion_certificado" name="descripcion_certificado">
				 <?php echo $this->comboTipoDocumento(); ?>
			</select>
		</div>	comboOpcion			
       <div data-linea="2" id="egresoMsg">
			<label for="txt">¿ Existe algún diagnóstico adicional que no haya sido actualizado en la Historia Clínica?: </label><br>
			<select
				id="diagnostico_adicional" name="diagnostico_adicional" >
				 <?php echo $this->comboOpcion(); ?>
			</select>
		</div>				
		<div data-linea="3" id="egresoFallo">
			<label for="txt">Por favor actualice la Historia Clínica para continuar</label>
		</div>		
		<div data-linea="4" id="campoTxt">
			<label for="identificador_paciente">Documento identidad: </label>
			<input type="text" id="identificador_paciente" name="identificador_paciente" value=""
			placeholder="Identificador" required maxlength="13" />
		</div>				
        <div data-linea="4" id="campoBuscar">
			<button type="button" class="buscar">Buscar</button>
		</div>
		
		
	</fieldset >
	<div id="errorHistorial"><strong>Nota: No se ha generado aún la Historia Clínica del funcionario.</strong></div>
	<fieldset id="divFuncionario">
    </fieldset>
    <fieldset id="divFirma">
    </fieldset >
    <div data-linea="3">
    <div id="guardarCertificadoCarga"></div>
			<button type="button" class="guardar" id="guardarCertificado"></button>
	</div>
</form >
<script type ="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		$("#divFuncionario").hide();
		$("#divFirma").hide();
		$("#errorHistorial").hide();
		$(".guardar").hide();
		$("#egresoMsg").hide();
		$("#egresoFallo").hide();
	 });

	$("#guardarCertificado").click(function (event) {
		event.preventDefault();
		var texto = "Por favor revise los campos obligatorios.";
		$(".alertaCombo").removeClass("alertaCombo");
		mostrarMensaje("", "FALLO");
		var error = false;
		
		if(!$.trim($("#descripcion_certificado").val())){
			   $("#descripcion_certificado").addClass("alertaCombo");
			   error = true;
		  }
		if(!$.trim($("#identificador_paciente").val())){
			   $("#identificador_paciente").addClass("alertaCombo");
			   error = true;
		  }

		if($("#descripcion_certificado").val() == 'Certificado de Egreso'){
			if(!$.trim($("#fecha_salida").val())){
				   $("#fecha_salida").addClass("alertaCombo");
				   error = true;
			  }
		}
		if($("#descripcion_certificado").val() == 'Informe Médico'){
			if(!$.trim($("#analisis").val())){
				   $("#analisis").addClass("alertaCombo");
				   error = true;
			  }
			if(!$.trim($("#recomendaciones").val())){
				   $("#recomendaciones").addClass("alertaCombo");
				   error = true;
			  }
		}
		if (!error) {
			$("#guardarCertificadoCarga").html("<div id='cargando'>Cargando...</div>").fadeIn();
			$.post("<?php echo URL ?>HistoriasClinicas/certificadoMedico/guardarRegistros", 
                {
				identificador: $('#identificador_paciente').val(),
          		descripcion_certificado: $('#descripcion_certificado').val(),
          		observaciones: $('#observaciones').val(),
          		fecha_salida: $('#fecha_salida').val(),
          		analisis: $('#analisis').val(),
          		recomendaciones: $('#recomendaciones').val()
          						    
                }, function (data) {
                	$("#guardarCertificadoCarga").html("");
                	if (data.estado === 'EXITO') {
	                   	 mostrarMensaje(data.mensaje, data.estado);
	                     //abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
	                 	 $("#detalleItem").html('<embed src="'+data.contenido+'#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100%" height="100%" />');
	                   	 distribuirLineas();
                    } else {
                    	mostrarMensaje(data.mensaje, "FALLO");
                    }
        }, 'json');
			
		} else {
			$("#estado").html(texto).addClass("alerta");
		}
	});
	//Función que agrega información del funcionario
    $(".buscar").click(function(){
    	var texto = "Por favor revise los campos obligatorios.";
		$(".alertaCombo").removeClass("alertaCombo");
		mostrarMensaje("", "FALLO");
		var error = false;
		if(!$.trim($("#descripcion_certificado").val())){
			   $("#descripcion_certificado").addClass("alertaCombo");
			   error = true;
		  }
		if(!$.trim($("#identificador_paciente").val())){
  			   $("#identificador_paciente").addClass("alertaCombo");
  			   error = true;
  		  }
		  
		if (!error) {
	 	$.post("<?php echo URL ?>HistoriasClinicas/certificadoMedico/buscarFuncionario", 
              {
          		identificador: $('#identificador_paciente').val(),
          		descripcion_certificado: $('#descripcion_certificado').val()
              }, function (data) {
              	if (data.estado === 'EXITO') {
              		 $("#divFuncionario").html(data.paciente);
	                   	 $("#divFirma").html(data.firma);
	                   	 $("#divFuncionario").html(data.discapacidad);
	                   	 mostrarMensaje(data.mensaje, data.estado);
	                   	 $("#divFuncionario").show();
	            		 $("#divFirma").show();
	            		 $("#errorHistorial").hide();
	            		 $(".guardar").show();
	                     distribuirLineas();
	                     activarFecha();
                  } else {
                  	  mostrarMensaje(data.mensaje, "FALLO");
              		  $("#divFuncionario").hide();
            		  $("#divFirma").hide();
            		  $("#errorHistorial").show();
            		  $(".guardar").hide();
                  }
      }, 'json');
      
  	}else{
  		mostrarMensaje(texto, "FALLO");
  	}
  });
    $("#descripcion_certificado").change(function (event) {
    	$("#divFuncionario").hide();
		$("#divFirma").hide();
		$("#errorHistorial").hide();
		$(".guardar").hide();
		$('#identificador_paciente').val('');
 		$("#guardarCertificado").html($(this).val());
		if($(this).val() == 'Certificado de Egreso'){
			$("#egresoMsg").show();
			$("#campoTxt").hide();
			$("#campoBuscar").hide();
			}else{
				$("#egresoMsg").hide();
				$("#campoTxt").show();
				$("#campoBuscar").show();
			}
        
    });

    $("#diagnostico_adicional").change(function (event) {

		if($(this).val() == 'Si'){
			$("#divFuncionario").hide();
			$("#campoTxt").hide();
			$("#campoBuscar").hide();
			$("#divFirma").hide();
			$("#errorHistorial").hide();
			$(".guardar").hide();
			$('#identificador_paciente').val('');
			$("#egresoFallo").show();
			}else{
				$("#egresoFallo").hide();
				$("#campoTxt").show();
				$("#campoBuscar").show();
			}
        
    });
    function activarFecha(){
 	   $("#fecha_salida").datepicker({
 	    	yearRange: "c:c",
 	    	changeMonth: false,
 	        changeYear: false,
 	        dateFormat: 'yy-mm-dd',
 	      });
    }
</script>
