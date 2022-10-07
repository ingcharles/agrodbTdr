<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorEmpleados.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();
$ca = new ControladorAreas();
$cu = new ControladorUsuarios();
$ce = new ControladorEmpleados();

$res = $cv->abrirVehiculo($conexion, $_POST['id']);
$vehiculo= pg_fetch_assoc($res);

//Identificador Usuario Administrador o Apoyo de Transportes
if($_SESSION['usuario'] != '' && $_SESSION['usuario']!=$vehiculo['identificador_registro']){
	$identificadorUsuarioRegistro = $_SESSION['usuario'];
}else if($_SESSION['usuario'] != '' && $_SESSION['usuario']==$vehiculo['identificador_registro']){
	$identificadorUsuarioRegistro = $vehiculo['identificador_registro'];
}else{
	$identificadorUsuarioRegistro = '';
}

$area = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central','Oficina Técnica')", "(3,4,1)");
$usuario = $cu->obtenerUsuariosXarea($conexion);

while($fila = pg_fetch_assoc($usuario)){
	$responsable[]= array(identificador=>$fila['identificador'], apellido=>$fila['apellido'], nombre=>$fila['nombre'], area=>$fila['id_area']);
}

$per = $ce->obtenerDatosPersonales($conexion, $vehiculo['identificador']);
$conductor = pg_fetch_assoc($per);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>

<body>
<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Datos del vehículo</h1>
</header>

<form id="datosVehiculo" data-rutaAplicacion="transportes" data-opcion="actualizarVehiculo" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	
	<table class="soloImpresion">
	<tr><td>
	<div id="estado"></div>
	<fieldset>
		<legend> Vehículo placa <?php echo $vehiculo['placa'];?></legend>
		
		<input type="hidden" id="idVehiculo" name="idVehiculo" disabled="disabled" value="<?php echo $vehiculo['id_vehiculo'];?>"/>
		
		<div data-linea="1">
			<label>Placa </label>
				<input type="text" id="numPlaca" name="numPlaca" disabled="disabled" value="<?php echo $_POST['id'];?>" placeholder="Ej: AAA-0000" data-er="[A-Za-z]{3}-[0-9]{3,4}" data-inputmask="'mask': 'aaa-9999'"/>
		</div>
		
		<div data-linea="2">
		
		<label>Marca</label> 
			<select id="marca" name="marca" disabled="disabled">								
			</select> 
			
		</div><div data-linea="2">
			
		<label>Modelo</label> 
			<select id="modelo" name="modelo" disabled="disabled">
			</select> 
			
		</div><div data-linea="3">
				
		<label>Tipo</label> 
			<select id="tipo" name="tipo" disabled="disabled">		
			</select> 
			
		</div><div data-linea="3">			
	
		<label>Tipo Combustible</label>
			<select id="combustible" name="combustible" disabled="disabled">
				<option value="Extra" selected="selected">Extra</option>
				<option value="Super">Super</option>
				<option value="Diesel">Diesel</option>
			</select>
		
		</div>	
	</fieldset>	
	</td><td>
	<fieldset>
		<legend>Características externas</legend>
		
		<div data-linea="1">
		
		<label>Carroceria</label>
			<select id="carroceria" name="carroceria" disabled="disabled">
				<option value="Metalica" selected="selected">Metalica</option>
				<option value="Madera">Madera</option>
			</select>
		
		</div><div data-linea="1">
			
		<label>Color Uno</label>
		<input type="color" id="color_uno" name="color_uno" value="<?php echo $vehiculo['color'];?>" disabled="disabled"/> 
		
		</div><div data-linea="1">
			
		<label>Color Dos</label>
			<input type="color" id="color_dos" name="color_dos" value="<?php echo $vehiculo['color_secundario'];?>" disabled="disabled"/> 

		</div><div data-linea="2">

		<label>País de origen</label>
			<select id="pais_origen" name="pais_origen" disabled="disabled">
				<option value="Japón" selected="selected">Japón</option>
					<option value="Taylandia">Taylandia</option>
					<option value="Ecuador">Ecuador</option>
					<option value="Colombia">Colombia</option>
					<option value="Mexico">Mexico</option>
					<option value="Usa">USA</option>
			</select>
			
		</div><div data-linea="2">
		
		<label>Condición</label>
			<select id="condicion" name="condicion" disabled="disabled">
				<option value=""  selected="selected">Seleccione....</option>
				<option value="1">Bueno</option>
				<option value="2">Regular</option>
				<option value="3">Malo</option>
			</select>
			
		</div>	
			
	</fieldset>		
	</td><td>
	<fieldset>
		<legend>Características internas</legend>
		
		<div data-linea="1">
		
		<label>Año fabricación</label>
			<input type="text" id="fabricacion" name="fabricacion" value="<?php echo $vehiculo['anio_fabricacion'];?>" disabled="disabled" placeholder="Ej: 2013" data-er="[0-9]{4}" data-inputmask="'mask': '9999'"/>
		
		</div><div data-linea="1">
		
		<label>Tonelaje</label>
			<input type="text"  id="tonelaje" name="tonelaje" value="<?php echo $vehiculo['tonelaje'];?>" disabled="disabled" placeholder="Ej: 0.75" data-er="^[0-9]+(\.[0-9]{1,2})?$"/>
		
		</div><div data-linea="2">
		
		<label>Cilindraje</label>
			<input type="text" id="cilindraje" name="cilindraje" value="<?php echo $vehiculo['cilindraje'];?>" disabled="disabled" placeholder="Ej: 2700" data-er="^[0-9]+$"/>
		
		</div><div data-linea="2">
		
		<label>N° de motor</label>
			<input type="text" id="motor" name="motor" value="<?php echo $vehiculo['numero_motor'];?>" disabled="disabled" placeholder="Ej: MOTOR0123456" data-er="[A-Z0-9]"/>
		
		</div><div data-linea="3">
		
		<label>N° de chasis</label>
			<input type="text" id="chasis" name="chasis" value="<?php echo $vehiculo['numero_chasis'];?>" disabled="disabled" placeholder="Ej: CHASIS0123456" data-er="[A-Z0-9]"/>
		</div>
	
	</fieldset>
		</td><td>
	<fieldset>
		<legend>Datos de adquisición</legend>
		
		<div data-linea="1">
		
		<label>Fecha de compra</label>
			<input type="text"	id="fecha_compra" name="fecha_compra"	value="<?php echo date('j/n/Y',strtotime($vehiculo['fecha_compra']));?>" disabled="disabled" />
		
		</div><div data-linea="1">
		
		<label>N° de factura</label>
			<input type="text" id="factura_compra" name="factura_compra" value="<?php echo $vehiculo['factura_compra'];?>" disabled="disabled" placeholder="Ej: 123456789" data-er="^[0-9]+$"/>	
		
		</div><div data-linea="2">
		
		<label>Valor de compra</label>
			<input type="text" id="valor_compra" name="valor_compra" value="<?php echo $vehiculo['valor_compra'];?>" disabled="disabled" placeholder="Ej: 25000" data-er="^[0-9]+(\.[0-9]{1,2})?$"/>
			
		</div><div data-linea="2">	
		
		<label>Avaluó</label> 
			<input type="text" id="avaluo" name="avaluo" value="<?php echo $vehiculo['avaluo'];?>" disabled="disabled" placeholder="Ej: 10000" data-er="^[0-9]+(\.[0-9]{1,2})?$"/>
			
		</div>
	</fieldset>
	
	<fieldset>
							<legend>Datos del responsable</legend>
	
							<div id="divArea">
	
								<div data-linea="3">	
				
									<label>Área pertenece</label>
										<select id="area" name="area" disabled="disabled" >
											<option value="">Áreas....</option>
											<?php 
												while($fila = pg_fetch_assoc($area)){
														echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" >' . $fila['nombre'] . '</option>';
													}
											?>
										</select>
										
										<input type="hidden" id="categoriaArea" name="categoriaArea" />
								</div>
									
								<div data-linea="4">	
									<div id="dSubOcupante">
										<label>Conductor</label> 
											<select id="ocupante" name="ocupante" disabled="disabled">
											</select>
									</div>
								
								</div>	
	
							</div>
							
							<div data-linea="2">
								 
									
							</div>
							
						</fieldset>
	
	<fieldset>
		<legend>Información adicional</legend>

		<div data-linea="1">
		
		<label>Observaciones</label> 
			<input type="text" id="observaciones" name="observaciones" value="<?php echo $vehiculo['observacion'];?>" data-er="^[A-Za-z0-9.,/ ]+$" disabled="disabled"/>
			
		</div>
			
	</fieldset>
	
	<input  type="hidden" id="<?php echo $vehiculo['placa'];?>" data-rutaAplicacion="transportes" data-opcion="mostrarFotosVehiculo" data-destino="fotosVehiculo"/>
	
	</td>
	</tr>
</table>
</form>
<table id="fotosVehiculo" class="soloImpresion">
<tr><td>
<div id="fotosVehiculo"></div>
</td></tr>
</table>
</body>
<script type="text/javascript">

	var array_responsable= <?php echo json_encode($responsable); ?>;

	var marcas = [  {'mid':'Nissan','marca':'Nissan'},
	                {'mid':'Chevrolet','marca':'Chevrolet'},
	                {'mid':'Toyota','marca':'Toyota'},
	                {'mid':'Kia','marca':'Kia'},
	                {'mid':'Mazda','marca':'Mazda'},
	                {'mid':'Mitsubishi','marca':'Mitsubishi'},
	                {'mid':'Yamaha','marca':'Yamaha'}];

	var modelos = [ {'moid':'Pathfinder','mid':'Nissan','modelo':'Pathfinder'},
	                {'moid':'Patrol sgl T/A','mid':'Nissan','modelo':'Patrol sgl T/A'},
	                {'moid':'Frontier','mid':'Nissan','modelo':'Frontier'},
	                {'moid':'Grand Vitara SZ','mid':'Chevrolet','modelo':'Grand Vitara SZ'},
	                {'moid':'Trailblazer','mid':'Chevrolet','modelo':'Trailblazer'},
	                {'moid':'Grand Vitara','mid':'Chevrolet','modelo':'Grand Vitara'},
	                {'moid':'Luv D-Max TM','mid':'Chevrolet','modelo':'Luv D-Max TM'},
	                {'moid':'Fortuner','mid':'Toyota','modelo':'Fortuner'},
	                {'moid':'Rav4','mid':'Toyota','modelo':'Rav4'},
	                {'moid':'Highlander','mid':'Toyota','modelo':'Highlander'},
	                {'moid':'Hilux CS','mid':'Toyota','modelo':'Hilux CS'},
	                {'moid':'4Runner','mid':'Toyota','modelo':'4Runner'},
	                {'moid':'Land cruiser','mid':'Toyota','modelo':'Land cruiser'},
	                {'moid':'Fortuner TA','mid':'Toyota','modelo':'Fortuner TA'},
	                {'moid':'Sportage Active','mid':'Kia','modelo':'Sportage Active'},
	                {'moid':'Sportage R','mid':'Kia','modelo':'Sportage R'},
	                {'moid':'Sorento','mid':'Kia','modelo':'Sorento'},
	                {'moid':'Sportage LX','mid':'Kia','modelo':'Sportage LX'},
	                {'moid':'BT-50','mid':'Mazda','modelo':'BT-50'},
	                {'moid':'TSX Action','mid':'Mazda','modelo':'TSX Action'},
	                {'moid':'L200 4x2 C/S T/M','mid':'Mitsubishi','modelo':'L200 4x2 C/S T/M'},
	                {'moid':'L-200 /4x4','mid':'Mitsubishi','modelo':'L-200 /4x4'},
	                {'moid':'NKR II chasís cabinado','mid':'Chevrolet','modelo':'NKR II chasís cabinado'},
	                {'moid':'DT175DS','mid':'Yamaha','modelo':'DT175DS'},
	                {'moid':'Coaster','mid':'Toyota','modelo':'Coaster'},
	                {'moid':'B2600','mid':'Mazda','modelo':'B2600'}];

	var tipos =  [  {'tid':'Jeep 4X2','moid':'Pathfinder','mid':'Nissan','tipo':'Jeep 4X2'},
	                {'tid':'Jeep 4X4','moid':'Pathfinder','mid':'Nissan','tipo':'Jeep 4X4'},
	                {'tid':'Jeep 4X2','moid':'Patrol sgl T/A','mid':'Nissan','tipo':'Jeep 4X2'},
	                {'tid':'Jeep 4X4','moid':'Patrol sgl T/A','mid':'Nissan','tipo':'Jeep 4X4'},
	                {'tid':'Cabina simple 4X2','moid':'Frontier','mid':'Nissan','tipo':'Cabina simple 4X2'},
	                {'tid':'Cabina simple 4X4','moid':'Frontier','mid':'Nissan','tipo':'Cabina simple 4X4'},
	                {'tid':'2.0 MT 4x2','moid':'Grand Vitara SZ','mid':'Chevrolet','tipo':'2.0 MT 4x2'},
	                {'tid':'2.0 AT 4x2','moid':'Grand Vitara SZ','mid':'Chevrolet','tipo':'2.0 AT 4x2'},
	                {'tid':'2.0L TM 4x4','moid':'Grand Vitara SZ','mid':'Chevrolet','tipo':'2.0L TM 4x4'},
	                {'tid':'2.4 4x2 AT','moid':'Grand Vitara SZ','mid':'Chevrolet','tipo':'2.4 4x2 AT'},
	                {'tid':'2.4 4x4 MT','moid':'Grand Vitara SZ','mid':'Chevrolet','tipo':'2.4 4x4 MT'},
	                {'tid':'2.4 4x4 AT','moid':'Grand Vitara SZ','mid':'Chevrolet','tipo':'2.4 4x4 AT'},
	                {'tid':'LTZ Turbo','moid':'Trailblazer','mid':'Chevrolet','tipo':'LTZ Turbo'},
	                {'tid':'2.0L TM 4x2','moid':'Grand Vitara','mid':'Chevrolet','tipo':'2.0L TM 4x2'},
	                {'tid':'1.6L Sport','moid':'Grand Vitara','mid':'Chevrolet','tipo':'1.6L Sport'},
	                {'tid':'2.4 Cabina Simple 4x2','moid':'Luv D-Max TM','mid':'Chevrolet','tipo':'2.4 Cabina Simple 4x2'},
	                {'tid':'2.4 Cabina Doble 4x2','moid':'Luv D-Max TM','mid':'Chevrolet','tipo':'2.4 Cabina Doble 4x2'},
	                {'tid':'V6 Cabina Doble 4x2','moid':'Luv D-Max TM','mid':'Chevrolet','tipo':'V6 Cabina Doble 4x2'},
	                {'tid':'V6 Cabina Doble 4x4','moid':'Luv D-Max TM','mid':'Chevrolet','tipo':'V6 Cabina Doble 4x4'},
	                {'tid':'3.0 Cabina Doble 4x2','moid':'Luv D-Max TM','mid':'Chevrolet','tipo':'3.0 Cabina Doble 4x2'},
	                {'tid':'3.0 CD TM 4x4','moid':'Luv D-Max TM','mid':'Chevrolet','tipo':'3.0 CD TM 4x4'},                
	                {'tid':'2.7 T. Automática','moid':'Fortuner','mid':'Toyota','tipo':'2.7 T. Automática'},
	                {'tid':'2.7 T. Manual','moid':'Fortuner','mid':'Toyota','tipo':'2.7 T. Manual'},
	                {'tid':'4.0 T. Automática','moid':'Fortuner','mid':'Toyota','tipo':'4.0 T. Automática'},
	                {'tid':'2.7 T. Manual','moid':'Fortuner','mid':'Toyota','tipo':'2.7 T. Manual'},
	                {'tid':'2.5L 4X4','moid':'Rav4','mid':'Toyota','tipo':'2.5L 4X4'},
	                {'tid':'2.0L 4X2','moid':'Rav4','mid':'Toyota','tipo':'2.0L 4X2'},
	                {'tid':'Premium','moid':'Highlander','mid':'Toyota','tipo':'Premium'},
	                {'tid':'Full','moid':'Highlander','mid':'Toyota','tipo':'Full'},
	                {'tid':'Limited','moid':'Highlander','mid':'Toyota','tipo':'Limited'},
	                {'tid':'4X2','moid':'Hilux CS','mid':'Toyota','tipo':'4X2'},
	                {'tid':'4X4','moid':'Hilux CS','mid':'Toyota','tipo':'4X4'},
	                {'tid':'4X4','moid':'4Runner','mid':'Toyota','tipo':'4X4'},	
	                {'tid':'200','moid':'Land cruiser','mid':'Toyota','tipo':'200'},
	                {'tid':'2.7 T. Automática','moid':'Fortuner TA','mid':'Toyota','tipo':'2.7 T. Automática'},
	                {'tid':'2.7 T. Manual','moid':'Fortuner TA','mid':'Toyota','tipo':'2.7 T. Manual'},
	                {'tid':'4.0 T. Automática','moid':'Fortuner TA','mid':'Toyota','tipo':'4.0 T. Automática'},
	                {'tid':'2.7 T. Manual','moid':'Fortuner TA','mid':'Toyota','tipo':'2.7 T. Manual'},
	                {'tid':'TM 4X2','moid':'Sportage R','mid':'Kia','tipo':'TM 4X2'},
	                {'tid':'TA 4X2','moid':'Sportage R','mid':'Kia','tipo':'TA 4X2'},
	                {'tid':'M/T 4X2','moid':'Sportage Active','mid':'Kia','tipo':'M/T 4X2'},
	                {'tid':'A/T 4X2','moid':'Sportage Active','mid':'Kia','tipo':'A/T 4X2'},
	                {'tid':'3.5L','moid':'Sorento','mid':'Kia','tipo':'3.5L'},
	                {'tid':'A/T 4X2','moid':'Sportage Active','mid':'Kia','tipo':'A/T 4X2'},
	                {'tid':'Jeep 4X2','moid':'Sportage LX','mid':'Kia','tipo':'Jep 4X2'},               
	                {'tid':'2.5L TD CRDI TSX Cabina doble 2WD','moid':'BT-50','mid':'Mazda','tipo':'2.5L TD CRDI TSX Cabina doble 2WD'},
	                {'tid':'2.5L TD CRDI TSX Cabina doble 4WD','moid':'BT-50','mid':'Mazda','tipo':'2.5L TD CRDI TSX Cabina doble 4WD'},
	                {'tid':'2.6L TD Cabina doble','moid':'TSX Action','mid':'Mazda','tipo':'2.6L TD Cabina doble'},
	                {'tid':'Pick-Up','moid':'L200 4x2 C/S T/M','mid':'Mitsubishi','tipo':'Pick-Up'},
	                {'tid':'DC/TM','moid':'L-200 /4x4','mid':'Mitsubishi','tipo':'DC/TM'},
					{'tid':'Furgón-C','moid':'NKR II chasís cabinado','mid':'Chevrolet','tipo':'Furgón-C'},
	                {'tid':'Paseo','moid':'DT175DS','mid':'Yamaha','tipo':'Paseo'},
	                {'tid':'Jeep','moid':'Coaster','mid':'Toyota','tipo':'Jeep'},
	                {'tid':'Cabina doble STD AC','moid':'B2600','mid':'Mazda','tipo':'Cabina doble STD AC'}];


		var smarca = '<option value="">Marcas...</option>';
			$(marcas).each(function(i){
		    	smarca += '<option value="'+this.mid+'">'+this.marca+'</option>';
		    });
		$('#marca').html(smarca);

		$('#marca').click(function(){ 
	        var marca = $('#marca').val(); 
	        var pmodelo = $.grep(modelos,function(n,i){return (n.mid == marca); });
	        var smodelo = '<option value="">Modelo...</option>'; 
	        $(pmodelo).each(function(i){ 
	            smodelo += '<option value="'+this.moid+'">'+this.modelo+'</option>'; 
	        });
	        $('#modelo').html(smodelo);
	        $('#modelo').removeAttr("disabled");
	    });


		$('#modelo').click(function(){ 
	        var modelo = $('#modelo').val(); 
	        var ptipo = $.grep(tipos,function(n,i){return (n.moid == modelo); }); 
	        var stipo = '<option value="">Tipo...</option>'; 
	        $(ptipo).each(function(i){ 
	            stipo += '<option value="'+this.tid+'">'+this.tipo+'</option>'; 
	        });
	        $('#tipo').html(stipo);
	        $('#tipo').removeAttr("disabled");
	    }); 



		$(document).ready(function(){
			if(<?php echo $identificadorUsuarioRegistro?> == ''){
				$('#estado').html('Su sesión expiró, ingrese nuevamente al sistema').addClass('alerta');
			}		
			
			$('select[name="marca"]').find('option[value="<?php echo $vehiculo['marca'];?>"]').prop("selected","selected");
			$('<option value="<?php echo $vehiculo['modelo'];?>"><?php echo $vehiculo['modelo'];?></option>').appendTo('#modelo');
			$('<option value="<?php echo $vehiculo['tipo'];?>"><?php echo $vehiculo['tipo'];?></option>').appendTo('#tipo');
			cargarValorDefecto("combustible","<?php echo $vehiculo['combustible'];?>");
			cargarValorDefecto("carroceria","<?php echo $vehiculo['carroceria'];?>");
			cargarValorDefecto("pais_origen","<?php echo $vehiculo['pais_origen'];?>");
			cargarValorDefecto("condicion","<?php echo $vehiculo['condicion'];?>");
			$('select[name="area"]').find('option[value="<?php echo $vehiculo['id_area'];?>"]').prop("selected","selected");
			$('<option value="<?php echo $vehiculo['identificador'];?>"><?php echo $vehiculo['apellido'].", ".$vehiculo['nombre'];?></option>').appendTo('#ocupante');

			distribuirLineas();
			construirValidador();

			abrir($("#datosVehiculo #"+ $("#numPlaca").val()),null,false);
			
			$( "#fecha_compra" ).datepicker({
			      changeMonth: true,
			      changeYear: true
			    });

			
		});
		
					
		$("#area").change(function(event){
			$("#categoriaArea").val($('#area option:selected').attr('data-categoria'));
			$('#lResponsable').hide();
			$('#responsable').hide();
			
			$("#datosVehiculo").attr('data-opcion', 'combosOcupante');
		    $("#datosVehiculo").attr('data-destino', 'dSubOcupante');
		    abrir($("#datosVehiculo"), event, false); //Se ejecuta ajax, busqueda de sub tipo producto
		    
		    $('#ocupante').html(socupante);
		    $('#ocupante').removeAttr("disabled");
		 });
				
	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$("#placa").attr("disabled","disabled");
		$(this).attr("disabled","disabled");
	});

	$("#datosVehiculo").submit(function(event){

		$("#datosVehiculo").attr('data-opcion', 'actualizarVehiculo');
	    $("#datosVehiculo").attr('data-destino', 'detalleItem');
	    
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#placa").val()==""){
			error = true;
			$("#placa").addClass("alertaCombo");
		}
		
		if($("#marca").val()==""){
			error = true;
			$("#marca").addClass("alertaCombo");
		}

		if($("#modelo").val()==""){
			error = true;
			$("#modelo").addClass("alertaCombo");
		}

		if($("#tipo").val()==""){
			error = true;
			$("#tipo").addClass("alertaCombo");
		}

		if($("#combustible").val()==""){
			error = true;
			$("#combustible").addClass("alertaCombo");
		}

		if($("#carroceria").val()==""){
			error = true;
			$("#carroceria").addClass("alertaCombo");
		}

		if($("#pais_origen").val()==""){
			error = true;
			$("#pais_origen").addClass("alertaCombo");
		}

		if($("#condicion").val()==""){
			error = true;
			$("#condicion").addClass("alertaCombo");
		}

		if($("#fabricacion").val()==""|| !esCampoValido("#fabricacion")){
			error = true;
			$("#fabricacion").addClass("alertaCombo");
		}

		if($("#tonelaje").val()==""|| !esCampoValido("#tonelaje")){
			error = true;
			$("#tonelaje").addClass("alertaCombo");
		}

		if($("#cilindraje").val()==""|| !esCampoValido("#cilindraje")){
			error = true;
			$("#cilindraje").addClass("alertaCombo");
		}

		if($("#kilometraje").val()==""|| !esCampoValido("#kilometraje")){
			error = true;
			$("#kilometraje").addClass("alertaCombo");
		}

		if($("#motor").val()==""|| !esCampoValido("#motor")){
			error = true;
			$("#motor").addClass("alertaCombo");
		}

		if($("#chasis").val()==""|| !esCampoValido("#chasis")){
			error = true;
			$("#chasis").addClass("alertaCombo");
		}

		
		if($("#fecha_compra").val()==""){
			error = true;
			$("#fecha_compra").addClass("alertaCombo");
		}
		
		if($("#factura_compra").val()!="" ){
			if(!esCampoValido("#factura_compra")){
				error = true;
				$("#factura_compra").addClass("alertaCombo");
			}
		}

		if($("#valor_compra").val()!=""){
			if(!esCampoValido("#valor_compra")){
				error = true;
				$("#valor_compra").addClass("alertaCombo");
			}
		}	
		
		if($("#avaluo").val()==""|| !esCampoValido("#avaluo")){
			error = true;
			$("#avaluo").addClass("alertaCombo");
		}

		if($("#area").val()==""){
			error = true;
			$("#area").addClass("alertaCombo");
		}

		if($("#ocupante").val()==null || $("#ocupante").val()=='' || $("#ocupante").val()=="Otro"){
			error = true;
			$("#ocupante").addClass("alertaCombo");
			$("#estado").html("Debe seleccionar a un funcionario de Agrocalidad").addClass("alerta");
		}	

		if($("#localizacion").val()==""){
			error = true;
			$("#localizacion").addClass("alertaCombo");
		}

		if($("#observaciones").val()!="" ){
			if(!$.trim($("#observaciones").val()) || !esCampoValido("#observaciones")){
				error = true;
				$("#observaciones").addClass("alertaCombo");
			}
		}	
		
		if (!error){
			if(<?php echo $identificadorUsuarioRegistro?> == ''){
				$('#estado').html('Su sesión expiró, ingrese nuevamente al sistema').addClass('alerta');
			}else{
				ejecutarJson($(this));
			}
			
		}else{
			$("#estado").html('Por favor revise el formato de la información ingresada').addClass('alerta');
		}
		
	});

	function esCampoValido(elemento){
		  var patron = new RegExp($(elemento).attr("data-er"),"g");
		  return patron.test($(elemento).val());
	}

</script>
</html>
