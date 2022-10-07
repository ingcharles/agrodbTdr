<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorNotificacionEnfermedades.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cre= new ControladorNotificacionEnfermedades();

$usuario = $_SESSION['usuario'];


?>
<header>
	<h1>Reporte de Enfermedades Zoonósicas</h1>
</header>
	<div id="estado"></div>
	<div id="mensajeCargando"></div>
	
	<form id='nuevoNotificacionEnfermedades' data-rutaAplicacion='notificacionEnfermedades' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
		
		<input type="hidden" name="opcion" id="opcion" />
		<input type="hidden" id="areaProducto" name="areaProducto" />
		<input type="hidden" id="idProducto" name="idProducto"/>
		<input type="hidden" id="nombreProducto" name="nombreProducto"/>
		<input type="hidden" id="razonSocial" name="razonSocial"/>
		<input type="hidden" id="tipoOperac" name="tipoOperac"/>
				
		<fieldset id="datosConsultaWebServices">
			<legend>Información del Producto</legend>
				<div data-linea="1">
					<label>Cédula del dueño:</label>
					<input type="text" id="numero" name="numero" placeholder="N° de cédula" maxlength="10"/>
					<input type="hidden" id="clasificacion" name="clasificacion" value="Cédula"/>
				</div>
				<div id="paraNombreAnimal" data-linea="2">
					<label>Nombre del animal:</label>
					<input type="text" id="nombreAnimal" name="nombreAnimal" placeholder="Ej: Max" maxlength="50" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$"/>
				</div>
				<div id="paraIdentificacionAnimal" data-linea="2">
					<label>Identificación animal:</label>
					<input type="text" id="identificacionAnimal" placeholder="N°: Tatuaje/Carnet" name="identificacionAnimal" maxlength="13"/>
				</div>				
				<div id="paraTipoProducto" data-linea="3">
					<label>Tipo producto:</label>				
					<select id="tipoProducto" name="tipoProducto">
						<option value="0">Seleccione...</option>
						<?php 
							$tipo= $cre-> listaTipoProducto($conexion, 'tipoProducto', $_SESSION['usuario'],'','');
							
							while ($fila = pg_fetch_assoc($tipo)){
					    		echo '<option value="'.$fila['id_tipo_producto']. '" data-grupo="'. $fila['id_area'] . '">'. $fila['nombre'] .'</option>';
					    	}
						?>
					</select>
				</div>
				<div id="resultadoTipoProducto" data-linea="4"></div>
				<div id="resultadosubtipoProducto" data-linea="5"></div>
				<div id="resultadoProducto" data-linea="6"></div>
				<div id="resultadoOperacion" data-linea="7"></div>
				
		</fieldset>
		
		<fieldset id="registrarDiagnostico">
			<legend>Resultado de Diagnóstico</legend>
				<div data-linea="1">
					<label>Fecha:</label>
					<input type="text" id="fechaDiagnostico" name="fechaDiagnostico"/>
				</div>
				<div data-linea="1">
					<label>Laboratorio propio:</label>
					<input type="checkbox" id="labPropio" name="labPropio"/>
				</div>			
				<div data-linea="2">
					<label>Laboratorio:</label>
					<input type="text" id="laboratorio" name="laboratorio"/>
				</div>
				<div id="resultadoTipoEnfermedad" data-linea="3"></div>
				<div id="resultadoEnfermedad" data-linea="4"></div>
				<div data-linea="5">
				<button type="button" id="btnReporte" name="btnReporte" onclick="agregarDiagnostico()" class="mas">Agregar resultado</button>
				</div>
				<!-- div data-linea="11"-->
				<div>
					<table id="tablaAnimales">
						<tr>
							<th>Opción</th>						
							<th>Diagnóstico</th>
							<th>Agente causal</th>		
													
						</tr>
						<tbody id="animalMovilizacion">
						</tbody>
					</table>
				</div>
				<!-- /div-->
		</fieldset>
		<fieldset id="archivoAdjunto">
			<legend>Resultado de análisis de laboratorio</legend>	
			
				<div  data-linea="12">
			      <label>Seleccione Adjunto</label>
			      	<input type="hidden" class="rutaArchivo" id="rutaArchivo" name="rutaArchivo" value="0"/>
            		<input type="file"  id="estadoCarga" class="archivo" accept="application/msword | application/pdf | image/*"/>
            		<div class="estadoCarga" >En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
           			<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/notificacionEnfermedades/archivosAdjuntos">Subir archivo</button> 
		      	</div> 
		</fieldset>
		
		<fieldset id="observacion">
			<legend>Observación</legend>	
			<div  data-linea="11">
			      <label style="vertical-align:top;">Observación:</label>
			      <textarea name="descripcionEnfermedad" id="descripcionEnfermedad" placeholder="Escriba descripción." rows="4"></textarea> 
		      	</div>	 	
		 </fieldset>
		<label>*Nota:</label> Por favor llene correctamente la información, una vez ingresada no podrá ser modificada.
		<div>
			<button id="btnGuardar" type="submit" name="btnGuardar">Guardar Reporte</button>
		</div>	
	</form>

<script type="text/javascript">			
var contador = 0;

    $(document).ready(function(){			
		distribuirLineas();	
		$('#registrarDiagnostico').hide();//------oculta nuevo fieldset
		$('#archivoAdjunto').hide();
		$('#observacion').hide();
		$('#btnGuardar').hide();
		$("#btnGuardar").attr("disabled",true);
		
		$("#fechaDiagnostico").datepicker({
		      changeMonth: true,
		      changeYear: true
		}).datepicker('setDate', 'today');

		$("#numero").numeric();
		$("#paraNombreAnimal").hide();
		$("#paraIdentificacionAnimal").hide();
		$("#paraTipoProducto").hide();		   
		
		construirValidador();
	});

    
/*PARA NO SELLECCIONAR ANTES /*{ minDate: 0 }*/
    
    $("#tipoProducto").change(function(event){
			 $('#nuevoNotificacionEnfermedades').attr('data-opcion','accionesNotificacionEnfermedades');
    		 $('#nuevoNotificacionEnfermedades').attr('data-destino','resultadoTipoProducto');
    		 $('#opcion').val('tipoProducto');	
    		 $("#areaProducto").val($("#tipoProducto option:selected").attr('data-grupo'));	
    		 abrir($("#nuevoNotificacionEnfermedades"),event,false);	
	});

    
///FUNCION AGREGAR DIAGNOSTICO///
    
	function agregarDiagnostico(){
		chequearCamposAnimales();
	}
		
	function chequearCamposAnimales(form){

		$(".alertaCombo").removeClass("alertaCombo");
		
		var error = false;
		
		if($("#laboratorio").val()==""){	
			error = true;		
			$("#laboratorio").addClass("alertaCombo");
		}
		
		if($("#tipoEnfermedad option:selected").val()=="0"){	
			error = true;		
			$("#tipoEnfermedad").addClass("alertaCombo");
		}
			
		if($("#enfermedad option:selected").val()=="0"){	
			error = true;		
			$("#enfermedad").addClass("alertaCombo");
		}
			
		if (error == true){
			$("#estado").html("Por favor, llene todos los campos.").addClass('alerta');
		}
		else{
	
			$("#estado").html("").removeClass('alerta');	
			$("#descripcionEnfermedad").val("");
			$('#archivoAdjunto').show();
			$('#observacion').show();
			$("#btnGuardar").show();
			$("#btnGuardar").attr("disabled",false);
			$("#laboratorio").attr("readonly",true);
			
			tipoEnfermedad=$("#tipoEnfermedad option:selected").text();
			idTipoEnfermedad=$("#tipoEnfermedad option:selected").val();
			enfermedad=$("#enfermedad option:selected").text();	
			idEnfermedad=$("#enfermedad option:selected").val();
			
			var codigo = $("#enfermedad option:selected").val();	

			if($("#animalMovilizacion #r_"+codigo.replace(/ /g,'')).length==0){
				contador++;																						
			$("#animalMovilizacion").append("<tr id='r_"+codigo.replace(/ /g,'')+"'><td><button type='button' onclick='quitarAnimal(\"#r_"+codigo.replace(/ /g,'')+"\")' class='menos'>Quitar</button></td><td><input id='hTipoEnfermedad' name=hTipoEnfermedad[]' value='"+$("#tipoEnfermedad option:selected").val()+"'type='hidden'/>"+tipoEnfermedad+"</td><td><input id='hEnfermedad' name=hEnfermedad[]' value='"+enfermedad+"'type='hidden'/>"+enfermedad+"</td><input id='hIdTipoEnfermedad' name=hIdTipoEnfermedad[]' value='"+idTipoEnfermedad+"'type='hidden'><input id='hIdEnfermedad' name=hIdEnfermedad[]' value='"+idEnfermedad+"'type='hidden'></tr>");

			}else{

				$("#estado").html("Por favor verifique datos, no puede registrar el mismo diagnóstico  dos veces.").addClass('alerta');
				}
		}
    }//inicio

	
	function quitarAnimal(fila){	
		$("#animalMovilizacion tr").eq($(fila).index()).remove();
			contador--;
		if(contador==0)
		{			
			$("#btnGuardar").attr("disabled",true);
			$("#laboratorio").attr("readonly",false);
			$('#archivoAdjunto').hide();
			$("#estado").html("Ingrese al menos un resultado de diagnóstico").addClass('alerta');
		}
	}

	$("#nuevoNotificacionEnfermedades").submit(function(event){
        
	    event.preventDefault();

	    $(".alertaCombo").removeClass("alertaCombo");
	  	var error = false;
	
			if($("#nombreAnimal").val()==""){	
				error = true;		
				$("#nombreAnimal").addClass("alertaCombo");
				//alert(variablejs);
			}
	
			if($("#fechaDiagnostico").val()==""){	
				error = true;		
				$("#fechaDiagnostico").addClass("alertaCombo");
			}
	
			if($("#identificacionAnimal").val()==""){	
				error = true;		
				$("#identificacionAnimal").addClass("alertaCombo");
			}
	
			if($("#numero").val()==""){	
				error = true;		
				$("#numero").addClass("alertaCombo");
			}
			
			if($("#tipoProducto option:selected").val()=="0"){	
				error = true;		
				$("#tipoProducto").addClass("alertaCombo");
			}
	
			if($("#subtipoProducto option:selected").val()=="0"){	
				error = true;		
				$("#subtipoProducto").addClass("alertaCombo");
			}
	
			if($("#producto option:selected").val()=="0"){	
				error = true;		
				$("#producto").addClass("alertaCombo");
			}
	
			if($("#tipoOperacion option:selected").val()=="0"){	
				error = true;		
				$("#tipoOperacion").addClass("alertaCombo");
			}

			if($("#areaOperaciones option:selected").val()=="0"){	
				error = true;		
				$("#areaOperaciones").addClass("alertaCombo");
			}

			if($("#descripcionEnfermedad").val()==""){	
				error = true;		
				$("#descripcionEnfermedad").addClass("alertaCombo");
			}
			
			if($("#rutaArchivo").val()=="0"){
				error = true;
				$("#estadoCarga").addClass("alertaCombo");
			}

			if (error){
				$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
				}else{
					 $('#nuevoNotificacionEnfermedades').attr('data-opcion','guardarNuevoNotificacionEnfermedades');
					ejecutarJson($(this));                             
				}
		});

	 
///ACTIVAR SUBIDA DE ARCHIVOS///

  $("button.subirArchivo").click(function (event) {

	  	numero = Math.floor(Math.random()*100000000);
	  	  
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , $("#numero").val()+'_'+$("#fechaDiagnostico").val().replace(/[_\W]+/g, "-")+'_'+numero
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
                
            );
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");        }
    } );


///CHECK

$("#labPropio").change(function(event){
	if($("#labPropio").is(":checked")==true){
		$("#laboratorio").val($("#razonSocial").val());
		$("#laboratorio").attr('readonly', true);
		}
	else{
		$("#laboratorio").val("");
		$("#laboratorio").prop('readonly', false);
		}
});


///FUNCION CEDULA///
	
	$("#numero").change(function(event){

			event.preventDefault();
			
			var $botones = $("form").find("button[type='submit']"),
	    	serializedData = $("#datosConsultaWebServices").serialize(),
	    	//url = "aplicaciones/general/consultaValidarIdentificacion.php";
			url = "aplicaciones/general/consultaWebServices.php";
	  		$botones.attr("disabled", "disabled");
	   	    resultado = $.ajax({
		    url: url,
		    type: "post",
		    data: serializedData,
		    dataType: "json",
		    async:   true,
		    beforeSend: function(){
		    	$("#estado").html('').removeClass();
		    	$("#mensajeCargando").html("<div id='cargando'>Cargando...</div>").fadeIn();
			},
			
		    success: function(msg){
		    	if(msg.estado=="exito"){
			    	$botones.removeAttr("disabled");
			    	$("#paraNombreAnimal").show();
		    		$("#paraIdentificacionAnimal").show();
		    		$("#paraTipoProducto").show();
			    }else{
		    		mostrarMensaje(msg.mensaje,"FALLO");
		    		$("#numero").val("");
		    		$("#paraNombreAnimal").hide();
					$("#paraIdentificacionAnimal").hide();
					$("#paraTipoProducto").hide();		
			    }
		   },
		    error: function(jqXHR, textStatus, errorThrown){
		    	$("#cargando").delay("slow").fadeOut();
		    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
		    },
	        complete: function(){
	        	$("#cargando").delay("slow").fadeOut();
	        }
		});
	
	});
	
</script>