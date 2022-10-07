<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>DECLARACIÓN JURADA / AFFIDAVIT</title>
	<link rel='stylesheet' href='<?php echo URL_MVC_MODULO ?>FormularioBoleta/vistas/estilos/bootstrap.min.css'>
	<style type="text/css">

	</style>
</head>
<body>

		<h5>FORMULARIO</h5>

	<main>
		<div class="container">
		<div class="row informacion">
			<div class=col>
			
			<div class="form-group row ">
				<div class="col-12 mb-3">
				 <strong>I. IDENTIFICACIÓN / <span class="text-muted">PERSONAL PARTICULARS</span></strong>
				</div>
			
						<div class="col-12 col-md-4 mb-3">
							<label for="apellidosC">1. Apellidos / <span class="text-muted">Last Name</span></label>
							<input type="text" class="form-control" value="<?php echo $this->modeloDatosIngreso->getApellidos();?>" pattern="[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+" title="Solo letras / only letters" required maxlength="30" placeholder="Apellidos / Last Name" name="apellidosC" id="apellidosC" autocomplete="off"> 
						</div>
						<div class="col-12 col-md-4 mb-3">
							<label for="nombresC">Nombres / <span class="text-muted">Names</span></label>
							<input type="text" class="form-control" value="<?php echo $this->modeloDatosIngreso->getNombres();?>" pattern="[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+" title="Solo letras / only letters" required maxlength="30" placeholder="Nombres / Names" name="nombresC" id="nombresC" autocomplete="off">
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
							<input type="text" class="form-control" value="<?php echo $this->modeloDatosIngreso->getNacionalidad();?>" pattern="[A-Za-zñÑÁáÉéÍíÓóÚúÜü0 ]+" title="Solo letras / only letters" required maxlength="30" placeholder="Nacionalidad / Nationality" name="nacionalidad" id="nacionalidad" autocomplete="off"> 
						</div>
						<div class="col-12 col-md-8 mb-3">
							<label for="identificadorC">3. Nº de pasaporte o cédula de ciudadania / <span class="text-muted">Passport Number or Citizen ID</span></label>
							<input type="text" class="form-control"  value="<?php echo $this->modeloDatosIngreso->getIdentificador();?>" pattern="[A-Za-z0-9]+" title="Letras y números / letters and numbers" required maxlength="13" placeholder="Nº de pasaporte o cédula de ciudadania / Passport Number or Citizen ID" name="identificadorC" id="identificadorC" autocomplete="off"> 
						</div>
										
						<div class="col-12 col-md-5 mb-3">
							<label for="pais_procedencia">4. País de procedencia / <span class="text-muted">Country of origin</span></label>
							<input type="text" class="form-control"  value="<?php echo $this->modeloDatosIngreso->getPaisProcedencia();?>" required maxlength="30" placeholder="País de procedencia / Country of origin" name="pais_procedencia" id="pais_procedencia" autocomplete="off"> 
						</div>
						<div class="col-12 col-md-7 mb-3">
							<label for="puerto_aeropuerto">5. Puerto o Aeropuerto de origen  / <span class="text-muted">Port or Airport of origen</span></label>
							<input type="text" class="form-control"  value="<?php echo $this->modeloDatosIngreso->getPuertoAeropuerto();?>" required maxlength="30" placeholder="Puerto o Aeropuerto de origen / Port or Airport of origen" name="puerto_aeropuerto" id="puerto_aeropuerto" autocomplete="off"> 
						</div>
						
						<div class="col-12  mb-3">
							<label for="paises_visitados">6. Países visitados en los últimos 30 días / <span class="text-muted">Countries visited in the last 30 days</span></label>
							<input type="text" class="form-control" value="<?php echo $this->modeloDatosIngreso->getPaisesVisitados();?>" required maxlength="100" placeholder="Países visitados en los últimos 30 días / Countries visited in the last 30 days" name="paises_visitados" id="paises_visitados" autocomplete="off"> 
						</div>
						<div class="col-12  mb-3">
							<label for="direccion_ecuador">7. Dirección en Ecuador / <span class="text-muted">Address in Ecuador</span></label>
							<input type="text" class="form-control" value="<?php echo $this->modeloDatosIngreso->getDireccionEcuador();?>" required maxlength="1024" placeholder="Dirección en Ecuador / Address in Ecuador" name="direccion_ecuador" id="direccion_ecuador" autocomplete="off"> 
						</div>
						<div class="col-12  mb-3">
							<label for="medio_ingreso">8. Nombre Control Fronterizo / Aeropuerto / Puerto de ingreso a  Ecuador / <span class="text-muted">Name of border crossing Point / Airport / Port of entry into Ecuador</span></label>
							<input type="text" class="form-control" value="<?php echo $this->modeloDatosIngreso->getMedioIngreso();?>" required maxlength="250" placeholder="Nombre Control Fronterizo / Aeropuerto / Puerto de ingreso a  Ecuador / Name of border crossing Point / Airport / Port of entry into Ecuador" name="medio_ingreso" id="medio_ingreso" autocomplete="off"> 
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
							<input type="text" class="form-control" value="<?php echo $this->modeloDatosIngreso->getCompaniaTransporte();?>" maxlength="250" required placeholder="Compañia de transporte / Carrier" name="compania_transporte" id="compania_transporte" autocomplete="off"> 
						</div>
						
			</div>
			<hr size="4">
			<div class="form-group row contenido justify-content-center preguntas">
				<div class="col-12 text-justify mb-3 contenido">
				 <strong >II. AGENCIA DE REGULACIÓN Y CONTROL FITO Y ZOOSANITARIO / <span class="text-muted">PHYTO AND ZOOSANITARY REGULATION AND CONTROL AGENCY</span></strong>
				</div>
				
				       <?php echo $this->preguntas;?>
						<div class="col-12  mb-3">
							<label for="puerto_origen" id="afirmativo">Si la respuesta es afirmativa, indicar / <span class="text-muted">If the answer is yes, please indicate</span></label>
    						<div class="form-group row">
        						<div class="col-6 col-md-4 mb-2">
        							<label for="num_hombres">N° de hombres / <span class="text-muted">Number of men</span></label>
        							<input type="tel" maxlength="2" value="<?php echo $this->numHombres;?>" pattern="[0-9]{1,2}" title="Solo números / only numbers" class="form-control" placeholder="N° de hombres / Number of men" name="num_hombres" id="num_hombres" autocomplete="off"> 
        						</div>
        						<div class="col-6 col-md-4 mb-2">
        							<label for="num_mujeres">N° de mujeres / <span class="text-muted">Number of women</span></label>
        							<input type="tel" maxlength="2" value="<?php echo $this->numMujeres;?>" pattern="[0-9]{1,2}" title="Solo números / only numbers" class="form-control" placeholder="N° de mujeres / Number of women" name="num_mujeres" id="num_mujeres" autocomplete="off">
        						</div>
    						</div>
							
						</div>
			</div>
			<hr size="4">
			
			</div>
		</div>
			
		</div>
		
		</main>
	<footer>
		
	</footer>
	<script src="<?php echo URL_MVC_MODULO ?>FormularioBoleta/vistas/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?php echo URL_MVC_MODULO ?>FormularioBoleta/vistas/js/jquery-3.2.1.min.js" type="text/javascript"></script>
	<script src="<?php echo URL_MVC_MODULO ?>FormularioBoleta/vistas/js/popper.min.js" type="text/javascript"></script>
	<script type ="text/javascript">
	var genero = <?php echo json_encode($this->modeloDatosIngreso->getGenero());?>;
	var medioTransporte = <?php echo json_encode($this->modeloDatosIngreso->getMedioTransporte());?>;
	$(document).ready(function() {
			$("#"+genero.toLowerCase()).attr('checked',true);
			$("#"+medioTransporte.toLowerCase()).attr('checked',true);
			if(medioTransporte == 'Avión'){
				$("#avion").attr('checked',true);
			}
			if(medioTransporte == 'Camión'){
				$("#camion").attr('checked',true);
			}

			$(".informacion input").prop('disabled', true);
	 });
	
</script>
</body>

</html>