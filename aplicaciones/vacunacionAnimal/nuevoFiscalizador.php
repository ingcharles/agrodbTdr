<?php 
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel='stylesheet' href='../general/estilos/agrodb_papel.css' >
<link rel='stylesheet' href='../general/estilos/agrodb.css'>
</head>
<body>

<header>
	<h1>Nuevo datos del vacunador</h1>
</header>
<div id="estado"></div>
<form id='nuevoVacunador' data-rutaAplicacion='vacunacionAnimal' data-opcion='guardarNuevoVacunador' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">	
		<fieldset>
			<legend>Información del vacunador</legend>
			    <div data-linea="1">			
					<label>Tipo documento</label>
					<select id="tipoIdentificacion" name="tipoIdentificacion">
					<option value="0">Seleccione....</option> 
						<option value="Cedula">Cedula</option> 
						<option value="Ruc">Ruc</option> 
					</select>					
				</div>						
				<div data-linea="1">			
					<label>Identificación</label> 
					<input type="text" id="identificacion" name="identificacion" placeholder="Ej: 1712874433..."/>
				</div>							
				<div data-linea="2">			
				<label>Nombres</label> 
					<input type="text" id="nombres" name="nombres" placeholder="Ej: Juan" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/ ]+$" />
				</div>
				<div data-linea="3">			
				<label>Apellidos</label> 
					<input type="text" id="apellidos" name="apellidos" placeholder="Ej: López..." data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/ ]+$" />
				</div>									
			    <div data-linea="5">
			     <label>Teléfono</label> 
				 <input type="text" id="telefono" name="telefono" placeholder="Ej: 02800555" data-er="^[0-9 ]+$" />
			    </div>
			    <div data-linea="5">
			     <label>Celular</label> 
				 <input type="text" id="celular" name="celular" placeholder="Ej: 0998556523" data-er="^[0-9 ]+$" />
			    </div>
			    <div data-linea="6">
			     <label>Correo</label> 
				 <input type="text" id="correo" name="correo" placeholder="Ej: nombre@hotmail.com" />
			    </div>			   			  
		</fieldset>
    	<button type="submit" class="guardar">Guardar vacunador</button> 

</form>
</body>
<script type="text/javascript">	
	$(document).ready(function(){			
		distribuirLineas();		
	});
	
	$("#nuevoVacunador").submit(function(event){		
		event.preventDefault();
		abrir($(this),event,false);
	});

</script>
</html>


  		
