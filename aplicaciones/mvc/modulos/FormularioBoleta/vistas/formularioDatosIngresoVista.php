<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>DECLARACIÓN JURADA / AFFIDAVIT</title>
	<link rel='stylesheet' href='<?php echo URL_MVC_MODULO ?>FormularioBoleta/vistas/estilos/bootstrap.min.css'>
	<link rel='stylesheet' href='<?php echo URL_MVC_MODULO ?>FormularioBoleta/vistas/estilos/estiloFormulario.css'>
</head>
<body>

	<header>
		<div class="container">
			<div class="nav row rounded-top align-items-strech justify-content-between menup">
				<div class="col align-items-center justify-content-center justify-content-lg-start texto_responsive">
					<h5>BIENVENIDO/A A ECUADOR / <span class="color_text_ingles">WELCOME TO ECUADOR</span></h5>
				</div>
			</div>
			<div class="nav row rounded-top align-items-strech justify-content-between menus">
				<div class="col-12  align-items-center justify-content-center justify-content-lg-start texto_responsive">
					<p>DECLARACIÓN JURADA / <span class="color_text_ingles">AFFIDAVIT</span></p>
				</div>
			
				<div class="col-12  align-items-center justify-content-center justify-content-lg-start texto_responsive_info">
					<p>ESTA DECLARACIÓN DEBE SER LLENADA POR TODA PERSONA QUE INGRESE AL PAÍS / <span class="color_text_ingles">TO BE FILLED OUT BY ANY PERSON ENTERING THE COUNTRY</span></p>
				</div>
			</div>
			
		</div>
	</header>

	<main id="principal">
	    <hr size="4">
		<div class="container">
		<div class="row">
			<div class=col>
			<form id="formulario" data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>FormularioBoleta' data-opcion='datosIngreso/guardar' method="post">
			
			<div class="form-group row contenido">
				<div class="col-12 mb-3">
				 <strong>I. IDENTIFICACIÓN / <span class="text-muted">PERSONAL PARTICULARS</span></strong>
				</div>
			
						<div class="col-12 col-md-4 mb-3">
							<label for="apellidos">1. Apellidos / <span class="text-muted">Last Name</span></label>
							<input type="text" class="form-control" pattern="[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+" title="Solo letras / only letters" required maxlength="30" placeholder="Apellidos / Last Name" name="apellidos" id="apellidos" autocomplete="off"> 
						</div>
						<div class="col-12 col-md-4 mb-3">
							<label for="nombres">Nombres / <span class="text-muted">Names</span></label>
							<input type="text" class="form-control" pattern="[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+" title="Solo letras / only letters" required maxlength="30" placeholder="Nombres / Names" name="nombres" id="nombres" autocomplete="off">
						</div>
			
						<div class="col-12 col-md-4">
						<br>
							<div class="form-check form-check-inline">
								<label class="form-check-label">
									<input type="radio" name="genero" id="masculino" value="Masculino" class="form-check-input mr-2">Masculino / <span class="text-muted">Male</span>
								</label>
							</div>
							<div class="form-check form-check-inline">
								<label class="form-check-label">
									<input type="radio" name="genero" required id="femenino" value="Femenino" class="form-check-input mr-2">Femenino / <span class="text-muted">Female</span>
								</label>
							</div>
						</div>
			
			</div>
			<div class="form-group row text-justify">
						<div class="col-12 col-md-4 mb-3 ">
							<label for="nacionalidad">2. Nacionalidad / <span class="text-muted">Nationality</span></label>
							<input type="text" class="form-control" pattern="[A-Za-zñÑÁáÉéÍíÓóÚúÜü0 ]+" title="Solo letras / only letters" required maxlength="30" placeholder="Nacionalidad / Nationality" name="nacionalidad" id="nacionalidad" autocomplete="off"> 
						</div>
						<div class="col-12 col-md-8 mb-3">
							<label for="identificador">3. Nº de pasaporte o cédula de ciudadania / <span class="text-muted">Passport Number or Citizen ID</span></label>
							<input type="text" class="form-control"  pattern="[A-Za-z0-9]+" title="Letras y números / letters and numbers" required maxlength="30" placeholder="Nº de pasaporte o cédula de ciudadania / Passport Number or Citizen ID" name="identificador" id="identificador" autocomplete="off"> 
						</div>
										
						<div class="col-12 col-md-5 mb-3">
							<label for="pais_procedencia">4. País de procedencia / <span class="text-muted">Country of origin</span></label>
					 	<select name="pais_procedencia" id="pais_procedencia" required class="form-control">
							<?php echo $this->paises;?>
						</select>
					
						</div>
						<div class="col-12 col-md-7 mb-3">
							<label for="puerto_aeropuerto">5. Puerto o Aeropuerto de origen  / <span class="text-muted">Port or Airport of origen</span></label>
    						<select name="puerto_aeropuerto" id="puerto_aeropuerto" required class="form-control">
    							<option value="">Seleccione/to select</option>
    						</select>
						</div>
						
						<div class="col-12  mb-3">
							<label for="paises_visitados">6. Países visitados en los últimos 30 días / <span class="text-muted">Countries visited in the last 30 days</span></label>
							<input type="text" class="form-control"  required maxlength="100" placeholder="Países visitados en los últimos 30 días / Countries visited in the last 30 days" name="paises_visitados" id="paises_visitados" autocomplete="off"> 
						</div>
						<div class="col-12  mb-3">
							<label for="direccion_ecuador">7. Dirección en Ecuador / <span class="text-muted">Address in Ecuador</span></label>
							<input type="text" class="form-control"  required maxlength="1024" placeholder="Dirección en Ecuador / Address in Ecuador" name="direccion_ecuador" id="direccion_ecuador" autocomplete="off"> 
						</div>
						<div class="col-12  mb-3">
							<label for="medio_ingreso">8. Nombre Control Fronterizo / Aeropuerto / Puerto de ingreso a  Ecuador / <span class="text-muted">Name of border crossing Point / Airport / Port of entry into Ecuador</span></label>
							<select name="medio_ingreso" id="medio_ingreso" required class="form-control">
    							<?php echo $this->puertos;?>
    						</select>
						</div>
						<div class="col-12  mb-3">
							<label for="medio_transporte">9. Medio de transporte de ingreso a Ecuador / <span class="text-muted">Means of transport entering the country</span></label>
    						<div class="col-12 ">
        							<div class="form-check form-check-inline justify-content-center">
        								<label class="form-check-label text-aling-center">
        									<input type="radio" name="medio" required id="nave" value="Nave" class="form-check-input mr-2">Nave<br><span class="text-muted ml-4">Vessel</span>
        								</label>
        							</div>
        							<div class="form-check form-check-inline justify-content-center">
        								<label class="form-check-label text-aling-center">
        									<input type="radio" name="medio" id="avion" value="Avión" class="form-check-input mr-2">Avión<br><span class="text-muted ml-4">Plane</span>
        								</label>
        							</div>
        							<div class="form-check form-check-inline justify-content-center">
        								<label class="form-check-label text-aling-center">
        									<input type="radio" name="medio" id="bus" value="Bus" class="form-check-input mr-2">Bus<br><span class="text-muted ml-4">Bus</span>
        								</label>
        							</div>
        							<div class="form-check form-check-inline justify-content-center">
        								<label class="form-check-label text-aling-center">
        									<input type="radio" name="medio" id="camion" value="Camión" class="form-check-input mr-2">Camión<br><span class="text-muted ml-4">Truck</span>
        								</label>
        							</div>
        							<div class="form-check form-check-inline justify-content-center">
        								<label class="form-check-label text-aling-center">
        									<input type="radio" name="medio" id="auto" value="Auto" class="form-check-input mr-2">Auto<br><span class="text-muted ml-4">Car</span>
        								</label>
        							</div>
        							<div class="form-check form-check-inline justify-content-center">
        								<label class="form-check-label text-aling-center">
        									<input type="radio" name="medio" id="moto" value="Moto" class="form-check-input mr-2">Moto<br><span class="text-muted ml-4">Motorcycle</span>
        								</label>
        							</div>
        							<div class="form-check form-check-inline justify-content-center">
        								<label class="form-check-label text-aling-center">
        									<input type="radio" name="medio" id="otro" value="Otro" class="form-check-input mr-2">Otro<br><span class="text-muted ml-4">Other</span>
        								</label>
        							</div>
        					</div>
    				</div>
    				<div class="col-12  mb-3">
							<label for="compania_transporte">10. Compañia de transporte / <span class="text-muted">Carrier</span></label>
							<input type="text" class="form-control"  maxlength="250" required placeholder="Compañia de transporte / Carrier" name="compania_transporte" id="compania_transporte" autocomplete="off"> 
						</div>
						
			</div>
			<hr size="4">
			<div class="form-group row contenido justify-content-center preguntas">
				<div class="col-12 text-justify mb-3 contenido">
				 <strong >II. AGENCIA DE REGULACIÓN Y CONTROL FITO Y ZOOSANITARIO / <span class="text-muted">PHYTO AND ZOOSANITARY REGULATION AND CONTROL AGENCY</span></strong>
				</div>
				
				<div class="col-12 text-justify mb-3 alert alert-warning">
				 <strong >HE LEÍDO LAS INSTRUCCIONES QUE APARECEN <a download href="../../modulos/FormularioBoleta/archivos/anexo.pdf">AQUI</a> Y DECLARO BAJO JURAMENTO QUE: / <span class="text-muted">I HAVE READ THE INSTRUCTIONS <a download href="../../modulos/FormularioBoleta/archivos/anexo.pdf">HERE</a> AND I HEREBY DECLARE UNDER OATH THAT:</span></strong>
				</div>
				       <?php echo $this->preguntas;?>
						<div class="col-12  mb-3">
							<label for="puerto_origen" id="afirmativo">Si la respuesta es afirmativa, indicar / <span class="text-muted">If the answer is yes, please indicate</span></label>
    						<div class="form-group row">
        						<div class="col-6 col-md-4 mb-2">
        							<label for="num_hombres">N° de hombres / <span class="text-muted">Number of men</span></label>
        							<input type="tel" maxlength="2" pattern="[0-9]{1,2}" title="Solo números / only numbers" class="form-control" placeholder="N° de hombres / Number of men" name="num_hombres" id="num_hombres" autocomplete="off"> 
        						</div>
        						<div class="col-6 col-md-4 mb-2">
        							<label for="num_mujeres">N° de mujeres / <span class="text-muted">Number of women</span></label>
        							<input type="tel" maxlength="2" pattern="[0-9]{1,2}" title="Solo números / only numbers" class="form-control" placeholder="N° de mujeres / Number of women" name="num_mujeres" id="num_mujeres" autocomplete="off">
        						</div>
    						</div>
							
						</div>
			</div>
			<hr size="4">
			<div class="form-group row contenido justify-content-center">
				<div class="col-12 text-justify alert alert-info">
				 <strong >NO SE EXPONGA A SANCIONES, SI TIENE DUDAS DECLARE O CONSULTE AL PERSONAL DE LA AGENCIA / <span class="text-muted">DO NOT EXPOSE YOURSELF TO SANCTIONS, IF YOU HAVE ANY DOUBTS, DO DECLARE OR MAKE YOUR ENQUIRIES TO AGENCY INSPECTORS.</span></strong>
				</div>
			</div>
			<div class="form-group row contenido justify-content-center">
				<div class="col-12 text-justify mb-3">
				 <strong >* Artículo reglamentado: Cualquier planta, producto vegetal, medio de almacenamiento, embalaje, medio de transporte, contenedor, suelo y cualquier otro organismo, objeto o material capaz de albergar o dispersar plagas, que se considere que debe estar sujeto a medidas fitosanitarias, en particular en el transporte internacional / <span class="text-muted">Regulated article: Any plant, plant product, storage medium, packaging, means of transport, container, soil and any other organism, object or material capable of harboring or dispersing pests, which is considered to be subject to phytosanitary measures, in particular in international transport.</span></strong>
				</div>
			</div>
					<div class="form-group row">
						<div class="col-12 text-center">
							<div class="row justify-content-center">
								<div class="col-12 col-sm-9 col-md-4">
									<button class="btn btn-primary btn-block" type="submit">Enviar</button>
								</div>
							</div>
						</div>
					</div>
			
				</form>
			</div>
		</div>
			
		</div>
		
		</main>
	<div class="modal fade" id="fm-modal" tabindex="-1" role="dialog" aria-labelledby="fm-modal" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header alert alert-danger">
								<h5 class="modal-title " id="">Advertencia / Warning</h5>
								<button class="close" data-dismiss="modal" aria-label="Cerrar">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<h5>Error al guardar los datos / <span class="text-muted">Failed to save data</span></h5>
							</div>
						<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	
	<script src="<?php echo URL_MVC_MODULO ?>FormularioBoleta/vistas/js/jquery-3.2.1.min.js" type="text/javascript"></script>
	<script src="<?php echo URL_MVC_MODULO ?>FormularioBoleta/vistas/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?php echo URL_MVC_MODULO ?>FormularioBoleta/vistas/js/popper.min.js" type="text/javascript"></script>
	<script type ="text/javascript">
	 $('#num_hombres').bind('keyup paste', function(){
	        this.value = this.value.replace(/[^0-9]/g, '');
	  });
	 $('#num_mujeres').bind('keyup paste', function(){
	        this.value = this.value.replace(/[^0-9]/g, '');
	  });
	$("#formulario").submit(function (event) {
		event.preventDefault();
		$(".alert").removeClass("alert");
		$(".alert-danger").removeClass("alert-danger");
		$(".modal-header").addClass("alert alert-danger");
    	var error = false;
		var respuestas = $(".preguntas input[type='radio']:checked").map(function(){return $(this).attr("name")+'-'+$(this).val()}).get();
    	if(!$.trim($("#apellidos").val())){
			error = true;
			$("#apellidos").addClass("alert alert-danger");
		}
    	if(!$.trim($("#nombres").val())){
			error = true;
			$("#nombres").addClass("alert alert-danger");
		}
    	if(!$("input[name='genero']").is(':checked') ){
 			 
 			$("input[name='genero']").addClass("alert alert-danger");
 			 error = true;
	  		}
    	if(!$.trim($("#identificador").val())){
			error = true;
			$("#identificador").addClass("alert alert-danger");
		}
    	if(!$.trim($("#pais_procedencia").val())){
			error = true;
			$("#pais_procedencia").addClass("alert alert-danger");
		}
    	if(!$.trim($("#nacionalidad").val())){
			error = true;
			$("#nacionalidad").addClass("alert alert-danger");
		}
    	if(!$.trim($("#puerto_aeropuerto").val())){
			error = true;
			$("#puerto_aeropuerto").addClass("alert alert-danger");
		}
    	if(!$.trim($("#paises_visitados").val())){
			error = true;
			$("#paises_visitados").addClass("alert alert-danger");
		}
    	if(!$.trim($("#direccion_ecuador").val())){
			error = true;
			$("#direccion_ecuador").addClass("alert alert-danger");
		}
    	if(!$.trim($("#medio_ingreso").val())){
			error = true;
			$("#medio_ingreso").addClass("alert alert-danger");
		}
    	if(!$.trim($("#compania_transporte").val())){
			error = true;
			$("#compania_transporte").addClass("alert alert-danger");
		}
		
    	var ultimo = respuestas[respuestas.length-1].split('-');
		if(ultimo[1] == 'Si'){
			var ban=1;
                if(!$.trim($("#num_hombres").val()) ){
                	if(!$.trim($("#num_mujeres").val())){
                    	error = true; ban=0;
                    	$("#num_hombres").addClass("alert alert-danger").focus();
                    	$("#num_mujeres").addClass("alert alert-danger");
                    }
                }
               	if(ban){
               		respuestas[respuestas.length-1]=ultimo[0]+'-'+ultimo[1]+'-'+$("#num_hombres").val()+'-'+$("#num_mujeres").val();
                   	}
		}
		if (!error) {
			        $.post("<?php echo URL ?>FormularioBoleta/DatosIngreso/guardar", 
						{
							identificador: $("#identificador").val(),
							apellidos: $("#apellidos").val(),
							nombres: $("#nombres").val(),
							genero: $('[name="genero"]:checked').map(function(){return this.value;}).get(),
							pais_procedencia: $("#pais_procedencia option:selected").text(),
							nacionalidad: $("#nacionalidad").val(),
							puerto_aeropuerto: $("#puerto_aeropuerto option:selected").text(),
							paises_visitados: $("#paises_visitados").val(),
							direccion_ecuador: $("#direccion_ecuador").val(),
							medio_ingreso:  $("#medio_ingreso option:selected").text(),
							medio_transporte: $('[name="medio"]:checked').map(function(){return this.value;}).get(),
							compania_transporte: $("#compania_transporte").val(),
							respuestas: respuestas
							
						},
						function (data) {
							if (data.estado === 'EXITO') {
                    		    $("#principal").html( '<div class="text-center"><hr size="4"><p class="text-success h5 mb-3">'+data.mensaje+'</p><form id="impresion" action="nuevo.php" method="post"> <button type="submit" class="btn btn-primary"> Nuevo registro / New entry </button></form></div>');
                            } else {
                            	$(".modal-body").html('<h5>Error al guardar los datos / <span class="text-muted">Failed to save data</span></h5>');
                            	$("#fm-modal").modal("show");
                            }
			        	}, 'json'); 
		}else {
			$(".modal-body").html('<h5>Por favor revise los campos obligatorios. / <span class="text-muted">Please check the required fields. </span></h5>');
        	$("#fm-modal").modal("show");
		}
	});
	
	$("#activar").click(function (event) {
		$(".modal-body").html('<h5>Por favor revise los campos obligatorios. / <span class="text-muted">Please check the required fields. </span></h5>');
    	$("#fm-modal").modal("show");
    $("#principal").html( '<div class="text-center"><hr size="4"><p class="text-success h2 mb-3">djalkjd alksdjalsj </p><form id="impresion" action="nuevo.php" method="post"> <button type="submit" class="btn btn-primary"> Nuevo registro / New entry </button></form></div>');
	});

	 //paises
    $("#pais_procedencia").change(function () {
        if($('#pais_procedencia').val() != ''){
  	  $.post("<?php echo URL ?>FormularioBoleta/DatosIngreso/buscarPuertos", 
                {
            		idPais: $('#pais_procedencia').val()
                }, function (data) {
                	if (data.estado === 'EXITO') {
                        $("#puerto_aeropuerto").html(data.contenido);
                    } 
        }, 'json');
        }
    });
</script>
</body>

</html>