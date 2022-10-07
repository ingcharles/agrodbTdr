<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

// ==> Registro de catastro
$conexion = new Conexion();
$cc = new ControladorCatalogos();
$va = new ControladorVacunacionAnimal();
$productoEspecie = $va->listaProductosPorEspecie($conexion);
$empresa = $va-> listaAdministradorEmpresa($conexion, $_SESSION['usuario']);
$autoservicio = "0";
while ($fila = pg_fetch_assoc($empresa)){
	$autoservicio = $fila['estado'];
}

?>

<header>
	<h1>Nuevo registro de catastro animal</h1>
</header>

<form id='nuevoRegistroCatastro' data-rutaAplicacion='vacunacionAnimal' data-opcion='accionesCatastroAnimal'>
	<input type="hidden" id="usuario_responsable" name="usuario_responsable" value="<?php echo $_SESSION['usuario'];?>" />
	<input type="hidden" id="autoservicio" name="autoservicio" value="<?php echo $autoservicio;?>" />
	<input type="hidden" id="estadoCatastro" name="estadoCatastro" value="0" />
	<input type="hidden" id="numeroDias" name="numeroDias" value="0" />
	<input type="hidden" id="idArea" name="idArea" value="0" />
	
    <div id="estado"></div>
	<div>
		<fieldset id="infoCatastro" name="infoCatastro">
			<legend>Búsqueda del sitio</legend>	
					
			<div data-linea="1">
				<label>Especie:</label> 
				<select id="cmbEspecie" name="cmbEspecie">
					<option value="0">Seleccione...</option>
					<?php
						$especie = $cc-> listaEspecies($conexion);
						while ($fila = pg_fetch_assoc($especie)){
					    	echo '<option value="' . $fila['id_especies'] . '">' . $fila['nombre'] . '</option>';
					    }

					?>						
				</select>	
			</div>	
			
			<div data-linea="2">
				<label>Identificación operador: </label> 
				<input type="text" id="identificadorOperador" name="identificadorOperador" value="" placeholder="Ej. 9999999999"  maxlength="13" />
			</div>
					
			<div data-linea="2">
				<label>Nombre operador: </label> 
				<input type="text" id="nombreOperador" name="nombreOperador" value="" placeholder="Ej: Diego Andino"  maxlength="250" />
			</div>
			
			<div data-linea="3">
				<label>Nombre sitio: </label> 
				<input type="text" id="nombreSitio" name="nombreSitio" value="" placeholder="Ej: Hacienda San Vicente"  maxlength="250" />
			</div>
							
			<div data-linea="3">
				<label>Codigo área: </label> 
				<input type="text" id="codigoArea" name="codigoArea" value="" placeholder="Ej: 1712387123.17010601"  maxlength="22" />
			</div>
		
			<div data-linea="4" style="text-align: center">
						<button type="button" id="btnBusquedaSitio" name="btnBusquedaSitio" onclick="buscarSitio()">Buscar sitio</button>
			</div>
					<div data-linea="5" ><label>NOTA:</label> Por favor escoja la especie y llene al menos un campo para buscar el sitio</div>
		</fieldset>	
																																					
		<fieldset>
			<legend>Información del catastro animal</legend>
				
			<div id="res_sitio" data-linea="1">
			<label>Nombre sitio:</label>
				<select>
					<option value="0">Seleccione...</option>
				</select>
			</div>
		
						
			<div id="resultadoArea" data-linea="2" >
				<label>Nombre área :</label> 
				<select >
				<option value="0">Seleccione...</option>
				</select>
			</div>			
			<div id="div6" data-linea="3">
				<label>Concepto de catastro :</label> 
				<select id="cmbConceptoCatastro" name="cmbConceptoCatastro" disabled="disabled">
					<option value="0">Seleccione....</option>
					<?php
						$conceptoCatastro = $va->listaConceptoCatastro($conexion);
						while ($fila = pg_fetch_assoc($conceptoCatastro)){						  
					    	echo '<option value="' . $fila['id_concepto_catastro'] . '" data-coeficiente="'.$fila['coeficiente'].'">' . $fila['nombre_concepto_catastro'] . '</option>';
					    }

					?>
				</select>
			</div>				
			<table id="tablaVacunaAnimal">
			    <thead>	
					<tr>
						<th>Producto</th>
						<th>Cantidad</th>												
					</tr>
				</thead>
				<tbody id="tabCatastro">
				</tbody>
			</table>
			<div id="div_fecha_nacimiento" data-linea="4">
				<label>Fecha de nacimiento :</label> 
				<input type="text" id="fecha_nacimiento" name="fecha_nacimiento" />
			</div>
			<div id="div_fecha_muerte" data-linea="4">
				<label  >Fecha de muerte :</label> 
			
				<input type="text" id="fecha_muerte" name="fecha_muerte" />
			</div>	
			
		</fieldset>
	</div>
	
	<button id="btn_guardar" type="button" name="btn_guardar" onclick="grabarCatastro()">Guardar catastro</button>
			
	<input type="hidden" id="opcion" name="opcion" value="0">
</form>

<script type="text/javascript">
	$(document).ready(function(){		
	
		$("#div4").hide();		
		$("#div_fecha_nacimiento").hide();
		$("#div_fecha_muerte").hide();
		$("#btn_guardar").hide();

		$("#fecha_nacimiento").datepicker({
		      changeMonth: true,
		      changeYear: true
		});		

		$("#fecha_muerte").datepicker({
		      changeMonth: true,
		      changeYear: true
		});	

		fecha = fechaActual();
		$("#fecha_nacimiento").val(fecha);
		$("#fecha_muerte").val(fecha);
		$("#cmbEspecie").focus();
		distribuirLineas();
			
	});
	
	//productos especies
	var array_productoEspecie = <?php echo json_encode($productoEspecie); ?>;

	function ValidaSoloNumeros() {
		 if ((event.keyCode < 48) || (event.keyCode > 57))
		  event.returnValue = false;
	}

	function ValidaAdultos() {	
		if ((event.keyCode < 48) || (event.keyCode > 57))
			  event.returnValue = false;
	}
    
	$("#cmbConceptoCatastro").change(function(){         		
		if($("#cmbConceptoCatastro").val()!='0'){
			$("#btn_guardar").show();				
			nombreCatastro = $("#cmbConceptoCatastro option:selected").text();	
									
			switch (nombreCatastro){
				case 'Nacimiento de animales':													
					$("#tabCatastro tr").remove();
										
					for(var i=0;i<array_productoEspecie.length;i++){																						
						if ((array_productoEspecie[i]['nombre_especie']==$("#cmbEspecie option:selected").text()) 
								&&  (array_productoEspecie[i]['codigo']=='PORHON'))
						{
							codigoProducto = array_productoEspecie[i]['id_producto'];	 
							nombreProducto = array_productoEspecie[i]['nombre_producto'];
							idEspecie = array_productoEspecie[i]['id_especie']; 
							nombreEspecie = array_productoEspecie[i]['nombre_especie'];
							dia = array_productoEspecie[i]['rango_edad_promedio'];							
							fechanacimientoAnimal = fechaActual();
																																				
							$("#tabCatastro").append("<tr id='r_"+codigoProducto.replace(/ /g,'')+"'><td><input id='hCodProductos' name='hCodProductos[]' value='"+codigoProducto+"'type='hidden'><input id='hProductos' name='hProductos[]' value='"+nombreProducto+"'type='hidden'>"+nombreProducto+"</td><td><input id='hCantidad' name='hCantidad[]' value='0' type='text' size='8' maxlength='8' onkeypress='ValidaSoloNumeros()'><input id='hEspecies' name='hEspecies[]' value='"+idEspecie+"'type='hidden'><input id='hNombreEspecies' name='hNombreEspecies[]' value='"+nombreEspecie+"' type='hidden'><input id='hCoeficiente' name='hCoeficiente[]' value='"+$("#cmbConceptoCatastro option:selected").attr('data-coeficiente')+"' type='hidden'><input id='hConceptos' name='hConceptos[]' value='"+$("#cmbConceptoCatastro option:selected").val()+"'type='hidden'><input id='hSitios' name='hSitios[]' value='"+$("#cmbSitio option:selected").val()+"'type='hidden'><input id='hAreas' name='hAreas[]' value='"+$("#areas option:selected").val()+"'type='hidden'><input id='hDia' name='hDia[]' value="+dia+" type='hidden'><input id='hFechaNacimiento' name='hFechaNacimiento[]' value='"+fechanacimientoAnimal+"' type='hidden'></td></tr>");																																								
						}					  			 
					}
					$("#div_fecha_nacimiento").show();
					$("#div_fecha_muerte").hide();
				
										
				break;
				case 'Muerte del animal':
					$("#tabCatastro tr").remove();											
					for(var i=0;i<array_productoEspecie.length;i++){																								
							if (array_productoEspecie[i]['nombre_especie']==$("#cmbEspecie option:selected").text()){
								codigoProducto = array_productoEspecie[i]['id_producto'];	 
								nombreProducto = array_productoEspecie[i]['nombre_producto'];
								idEspecie = array_productoEspecie[i]['id_especie']; 
								nombreEspecie = array_productoEspecie[i]['nombre_especie'];
								dia = array_productoEspecie[i]['rango_edad_promedio'];
								fechanacimientoAnimal = fechaActual();
								codigoP=array_productoEspecie[i]['codigo'];
																																			
								if(codigoP!='PORLTO'){																																										
									  $("#tabCatastro").append("<tr id='r_"+codigoProducto.replace(/ /g,'')+"'><td><input id='hCodProductos' name='hCodProductos[]' value='"+codigoProducto+"'type='hidden'><input id='hProductos' name='hProductos[]' value='"+nombreProducto+"' type='hidden'>"+nombreProducto+"</td><td><input id='hCantidad' name='hCantidad[]' value='0' type='text' size='8' maxlength='8' onkeypress='ValidaSoloNumeros()'><input id='hEspecies' name='hEspecies[]' value='"+idEspecie+"'type='hidden'><input id='hNombreEspecies' name='hNombreEspecies[]' value='"+nombreEspecie+"' type='hidden'><input id='hCoeficiente' name='hCoeficiente[]' value='"+$("#cmbConceptoCatastro option:selected").attr('data-coeficiente')+"' type='hidden'><input id='hConceptos' name='hConceptos[]' value='"+$("#cmbConceptoCatastro option:selected").val()+"'type='hidden'><input id='hSitios' name='hSitios[]' value='"+$("#cmbSitio option:selected").val()+"'type='hidden'><input id='hAreas' name='hAreas[]' value='"+$("#areas option:selected").val()+"'type='hidden'><input id='hDia' name='hDia[]' value="+dia+" type='hidden'><input id='hFechaNacimiento' name='hFechaNacimiento[]' value='"+fechanacimientoAnimal+"' type='hidden'></td></tr>");
								}
								else{
     							  $("#tabCatastro").append("<tr id='r_"+codigoProducto.replace(/ /g,'')+"'><td><input id='hCodProductos' name='hCodProductos[]' value='"+codigoProducto+"'type='hidden'><input id='hProductos' name='hProductos[]' value='"+nombreProducto+"' type='hidden'>"+nombreProducto+"</td><td><input id='hCantidad' name='hCantidad[]' value='0' type='text' size='8' maxlength='8' readonly onkeypress='ValidaAdultos()'><input id='hEspecies' name='hEspecies[]' value='"+idEspecie+"'type='hidden'><input id='hNombreEspecies' name='hNombreEspecies[]' value='"+nombreEspecie+"' type='hidden'><input id='hCoeficiente' name='hCoeficiente[]' value='"+$("#cmbConceptoCatastro option:selected").attr('data-coeficiente')+"' type='hidden'><input id='hConceptos' name='hConceptos[]' value='"+$("#cmbConceptoCatastro option:selected").val()+"'type='hidden'><input id='hSitios' name='hSitios[]' value='"+$("#cmbSitio option:selected").val()+"'type='hidden'><input id='hAreas' name='hAreas[]' value='"+$("#areas option:selected").val()+"'type='hidden'><input id='hDia' name='hDia[]' value="+dia+" type='hidden'><input id='hFechaNacimiento' name='hFechaNacimiento[]' value='"+fechanacimientoAnimal+"' type='hidden'></td></tr>");										
								}																																								
							}								  			 
					}
					$("#div_fecha_nacimiento").hide();
					$("#div_fecha_muerte").show();	
															
				break;	
					
				default:							
					$("#tabCatastro tr").remove();																	
					for(var i=0;i<array_productoEspecie.length;i++){	
							if (array_productoEspecie[i]['nombre_especie']==$("#cmbEspecie option:selected").text()){					
								codigoProducto = array_productoEspecie[i]['id_producto'];	 
								nombreProducto = array_productoEspecie[i]['nombre_producto'];
								idEspecie = array_productoEspecie[i]['id_especie']; 
								nombreEspecie = array_productoEspecie[i]['nombre_especie'];
								dia = array_productoEspecie[i]['rango_edad_promedio'];
								dia_aproximado = dia*-1;
								fechanacimientoAnimal = fechaNacimiento(dia_aproximado);	
								codigoP=array_productoEspecie[i]['codigo'];
								if(codigoP!='PORLTO'){																																										
								  $("#tabCatastro").append("<tr id='r_"+codigoProducto.replace(/ /g,'')+"'><td><input id='hCodProductos' name='hCodProductos[]' value='"+codigoProducto+"'type='hidden'><input id='hProductos' name='hProductos[]' value='"+nombreProducto+"'type='hidden'>"+nombreProducto+"</td><td><input id='hCantidad' name='hCantidad[]' value='0' type='text' size='8' maxlength='8' onkeypress='ValidaSoloNumeros()'><input id='hEspecies' name='hEspecies[]' value='"+idEspecie+"'type='hidden'><input id='hNombreEspecies' name='hNombreEspecies[]' value='"+nombreEspecie+"' type='hidden'><input id='hCoeficiente' name='hCoeficiente[]' value='"+$("#cmbConceptoCatastro option:selected").attr('data-coeficiente')+"' type='hidden'><input id='hConceptos' name='hConceptos[]' value='"+$("#cmbConceptoCatastro option:selected").val()+"'type='hidden'><input id='hSitios' name='hSitios[]' value='"+$("#cmbSitio option:selected").val()+"'type='hidden'><input id='hAreas' name='hAreas[]' value='"+$("#areas option:selected").val()+"'type='hidden'><input id='hDia' name='hDia[]' value="+dia+" type='hidden'><input id='hFechaNacimiento' name='hFechaNacimiento[]' value='"+fechanacimientoAnimal+"' type='hidden'></td></tr>");
								}
								else{
     							  $("#tabCatastro").append("<tr id='r_"+codigoProducto.replace(/ /g,'')+"'><td><input id='hCodProductos' name='hCodProductos[]' value='"+codigoProducto+"'type='hidden'><input id='hProductos' name='hProductos[]' value='"+nombreProducto+"'type='hidden'>"+nombreProducto+"</td><td><input id='hCantidad' name='hCantidad[]' value='0' type='text' size='8' maxlength='8' readonly onkeypress='ValidaAdultos()'><input id='hEspecies' name='hEspecies[]' value='"+idEspecie+"'type='hidden'><input id='hNombreEspecies' name='hNombreEspecies[]' value='"+nombreEspecie+"' type='hidden'><input id='hCoeficiente' name='hCoeficiente[]' value='"+$("#cmbConceptoCatastro option:selected").attr('data-coeficiente')+"' type='hidden'><input id='hConceptos' name='hConceptos[]' value='"+$("#cmbConceptoCatastro option:selected").val()+"'type='hidden'><input id='hSitios' name='hSitios[]' value='"+$("#cmbSitio option:selected").val()+"'type='hidden'><input id='hAreas' name='hAreas[]' value='"+$("#areas option:selected").val()+"'type='hidden'><input id='hDia' name='hDia[]' value="+dia+" type='hidden'><input id='hFechaNacimiento' name='hFechaNacimiento[]' value='"+fechanacimientoAnimal+"' type='hidden'></td></tr>");										
								}
																																														
							}								  			 
					}	
					$("#div_fecha_nacimiento").hide();
					$("#div_fecha_muerte").hide();	
								
			    break;							  
			}		 		
		}
	}); 

	$("#cmbEspecie").change(function(){         
		if($("#cmbEspecie").val()!='0'){
			especieProducto($("#cmbEspecie option:selected").text());	
		}
	}); 

	$("#areas").change(function(){         
		if($("#areas").val()!='0'){
			$("#idArea").val($("#areas").val());
			 $("#cmbConceptoCatastro").removeAttr("disabled");	
		}
	}); 

	function especieProducto(especie){
		sProductoEspecie ='0';
		sProductoEspecie = '<option value="0">Seleccionar...</option>';
		for(var i=0;i<array_productoEspecie.length;i++){			
			if (especie==array_productoEspecie[i]['nombre_especie']){	    
				sProductoEspecie += '<option data-edad-promedio='+array_productoEspecie[i]['rango_edad_promedio']+' value="'+array_productoEspecie[i]['id_producto']+'"> '+ array_productoEspecie[i]['nombre_producto']+'</option>';
			}			  
		}	   	    
		$('#cmbProducto').html(sProductoEspecie);
		$("#cmbProducto").removeAttr("disabled");		
	}

	//***************//Acciones de botton
	$("#btn_guardar").click(function(event){		
		if(controlarValor()){	
			if($("#estado").html() == ''){		
				 dia = calcularEdad($('#fecha_nacimiento').val());
				 $('#numeroDias').val(dia);			
				 event.preventDefault();		
				 $('#nuevoRegistroCatastro').attr('data-opcion','accionesCatastroAnimal');
				 $('#nuevoRegistroCatastro').attr('data-destino','res_sitio');
			     $('#opcion').val('2');		     	
				 abrir($("#nuevoRegistroCatastro"),event,false); //Se ejecuta ajax, busqueda de sitio	
				 abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
			}
		}				 		 		
	});		

	$("#btnBusquedaSitio").click(function(event){		
		if($("#estado").html() == ''){		
		 $('#nuevoRegistroCatastro').attr('data-opcion','accionesCatastroAnimal');
		 $('#nuevoRegistroCatastro').attr('data-destino','res_sitio');
	     $('#opcion').val('1');		
		 abrir($("#nuevoRegistroCatastro"),event,false); //Se ejecuta ajax, busqueda de sitio
		 $('#txtSitioBusqueda').val('');
		}						 		
	 }); 

	function controlarValor(){
		var sw = false;	
		var vCantidad=0; 
	    for(var i=0;i<array_productoEspecie.length;i++){
		    nombreCatastro = $("#cmbConceptoCatastro option:selected").text();
			
			switch (nombreCatastro){
				case 'Nacimiento de animales':
					if((array_productoEspecie[i]['nombre_especie']==$("#cmbEspecie option:selected").text()) &&  (array_productoEspecie[i]['codigo']=='PORHON'))
						vCantidad = $("#tabCatastro tr").eq(i).find("input[id='hCantidad']").val();
					if(vCantidad !=0)
				    	  sw=true;	
					break;
				default:																					
					if(array_productoEspecie[i]['nombre_especie']==$("#cmbEspecie option:selected").text()){
						vCantidad = $("#tabCatastro tr").eq(i).find("input[id='hCantidad']").val();						
					}
					if(vCantidad !=0)
				    	  sw=true;	
					break;
			}	      
	    }
	   // alert(sw);
	    if (!sw){
	    	alert("Por favor ingrese la cantidad de animales en el catastro !");
	    }
	    if($("#areas").val()==0){
	    	alert("Por favor el area del catastro !");
	    	sw = false;
		}	
	    return sw;
	}

	function grabarCatastro(){
		chequearCatastro();
	}
		
	function chequearCatastro(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#cmbConceptoCatastro").val()==0){
			error = true;
			$("#cmbConceptoCatastro").addClass("alertaCombo");
		}			
		if($("#areas").val()==0){
			error = true;
			$("#areas").addClass("alertaCombo");
		}				
		if (error == true){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{                   
			$("#estado").html("").removeClass('alerta');			      	
		}//
	}//inicio
	
	function buscarSitio(){
		chequearCamposSitio();
	}
		
	function chequearCamposSitio(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#identificadorOperador").val()=="" && $("#nombreOperador").val()=="" &&  $("#nombreSitio").val()=="" && $("#codigoArea").val()=="" ){
			error = true;
			$("#identificadorOperador").addClass("alertaCombo");
			$("#nombreOperador").addClass("alertaCombo");
			$("#nombreSitio").addClass("alertaCombo");
			$("#codigoArea").addClass("alertaCombo");
		}
		
		if($("#cmbEspecie").val()==0){
			error = true;
			$("#cmbEspecie").addClass("alertaCombo");
		}

		if (error == true){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{                   
			$("#estado").html("").removeClass('alerta');			      	
		}//
	}//inicio	

	function calcularEdad(fecha_nacimiento){
		if(fecha_nacimiento=='')
			fecha_nacimiento = fechaActual();
		
		var dia1 = fecha_nacimiento.substr(0,2);
		var mes1 = fecha_nacimiento.substr(3,2);
		var anyo1 = fecha_nacimiento.substr(6);
		
		var fecha_actual = new Date();
		var dia2 = fecha_actual.getDate();
		var mes2 = (fecha_actual.getMonth()+1);
		var anyo2 = fecha_actual.getFullYear();
		
		var nuevafecha1= new Date(anyo1,mes1,dia1);
		var nuevafecha2= new Date(anyo2,mes2,dia2);

		var Dif= nuevafecha2.getTime() - nuevafecha1.getTime();
		var dias= Math.floor(Dif/(1000 * 60 * 60 * 24));

		return dias;
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
	
	function fechaNacimiento(days)
	{ 
		fecha=new Date(); 			
		tiempo=fecha.getTime(); 
		milisegundos=parseInt(days*24*60*60*1000); 
		total=fecha.setTime(tiempo+milisegundos); 
		day=fecha.getDate(); 
		month=fecha.getMonth()+1; 
		year=fecha.getFullYear();
		fecNacimiento = day+"/"+month+"/"+year;
		return fecNacimiento; 
	}
	
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
	
</script>
<style type="text/css">
#tablaVacunaAnimal td, #tablaVacunaAnimal th 
{
font-size:1em;
border:1px solid rgba(0,0,0,.1);
padding:3px 7px 2px 7px;
}
</style>
