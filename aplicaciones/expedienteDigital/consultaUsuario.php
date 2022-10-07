<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorExpedienteDigital.php';
	require_once '../../clases/GoogleAnalitica.php'; 
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$ce = new ControladorExpedienteDigital();
    $provincias = $cc->listarLocalizacion($conexion, 'PROVINCIAS');
    //----1791773373001 pruebas con servicios  16930044201500000018P clv
?>                
<header>
    <h1>Consulta de Clientes</h1>
    <nav>  
            <table id="busqueda">
                <tr>
                    <td class="obligatorio">Buscar por: </td>                                   
                    <td ><input name="tipo" type="radio" id="busqueda1" onclick="verificar(id)"></td >
                    <td >Razón / RUC </td>                                   
                    <td ><input name="tipo" type="radio" id="busqueda2" onclick="verificar(id)"></td >
                    <td >Servicio  </td>                                    
                </tr>              
            </table>     
     <form id="listarOperadores" data-rutaAplicacion="expedienteDigital" data-opcion="listadoServicio" data-destino="respuesta">              
           <table class="filtro" id="consulta1">
                <tr>
                    <td >Tipo de servicio: </td>
                    <td><input name="tipo" type="radio" id="razon" value="razon" onclick="determinarOpcion()"><label for="razon">Razón social</label>
                        <input name="tipo" type="radio" id="rucci" value="ruc" checked="checked" onclick="determinarOpcion()" ><label for="rucci">RUC / CI</label>
                    </td>
                </tr>
                <tr>
                    <td class="obligatorio">Razón / RUC</td>
                    <td><input type="text" name="textoDeBusqueda" id="textoDeBusqueda" data-er="^[0-9]{8,13}$" maxlength="13" autocomplete="off"/><div id="ltextoDeBusqueda"></div></td>
                    
                </tr>
               <tr>
                    <td class="obligatorio">Provincia</td>
                    <td>
                        <select id="provincia" name="provincia">
                            <option value="Todas">
                                Seleccione...
                            </option>
                            <?php
                            while ($provincia = pg_fetch_assoc($provincias)) {
                                echo "<option value='" . $provincia['nombre'] . "'>" .
                                    $provincia['nombre'] .
                                    "</option>";
                            }
                            ?>
                        </select>
                    <div id="lprovincia"></div></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <button>Buscar</button>
                    </td>
                </tr>
            </table>  
            <table class="filtro" id="consulta2">
                <tr>
                    <td class="obligatorio">Servicio</td>
                    <td>
                        <select id="servicio" name="servicio">
                            <option value="Todas">
                               Seleccione...
                            </option>
                            <option value="Operadores">
                                Registro Operador
                            </option>
							<option value="ROCE">
                                ROCE
                            </option>
                            <option value="DDA">
                                DDA
                            </option>
                            <option value="Fitosanitario">
                                Fitosanitario
                            </option>
                            <option value="CLV">
                                CLV
                            </option>
                            <option value="Importación">
                                Importación
                            </option><option value="Zoosanitario">
                                Zoosanitario
                            </option>
                            <option value="certificacionBPA">
                            	Certificación BPA
                            </option>
                            <option value="proveedorExterior">
                            	Proveedor en el exterior
                            </option>
                            <option value="TransitoInternacional">
                            	Tránsito Internacional
                            </option>                  
                        </select>
                    <div id="lservicio"></div></td>
                </tr>
                <tr>
                    <td class="obligatorio">Numero de Solicitud</td>
                    <td>
                        <input type="text" name="numeroSolicitud" id="numeroSolicitud" data-er="^[0-9]{1,}" maxlength="30" autocomplete="off"/>
                    <div id="lnumeroSolicitud"></div></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <button>Buscar</button>
                    </td>
                </tr>
            </table> 
        </form>
        
        <form id="migrar" data-rutaAplicacion="expedienteDigital" data-opcion="migrarDatos" data-destino="respuesta">         
              <!--  <button>Migrar</button> -->   
       </form>
       
       <div id="lestado"></div>
    </nav>
</header> 
    <div id="respuesta" class="contenedor">	
		<div class="elementos"></div>
	</div>

<script>
$(document).ready(function () {
	$("#listadoItems").addClass("comunes");
    $("#consulta1").hide();
    $("#consulta2").hide();
    $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un documento para revisarlo.</div>');
});

$("#migrar").submit(function(event){
	alert(event);
	event.preventDefault();
    abrir($(this), e, false);
});

    $("#listarOperadores").submit(function(e){
    	e.preventDefault();
    	//e.stopPropagation();
    	var error = true;
    	var num;
    	$("#textoDeBusqueda").removeClass("alertaCombo");
    	$("#provincia").removeClass("alertaCombo");	
    	$("#servicio").removeClass("alertaCombo");
    	$("#numeroSolicitud").removeClass("alertaCombo");
    	$("#ltextoDeBusqueda").text('');
    	$("#lprovincia").text('');	
    	$("#lservicio").text('');
    	$("#lnumeroSolicitud").text('');
    	$("#lestado").text('');	
    	if(!$('input[id="busqueda2"]').is(':checked'))
    	{
          	
    	if(!$('input[id="razon"]').is(':checked')){
    		if($.trim($("#textoDeBusqueda").val())==""){
				error = false;
				$("#textoDeBusqueda").val("");
				$("#textoDeBusqueda").addClass("alertaCombo");
				$("#textoDeBusqueda").attr('placeholder','Campo vacio..');   					
			}
			
    	if(!esCampoValidoExp("#textoDeBusqueda",0)){
    			error = false;
    			$("#textoDeBusqueda").addClass("alertaCombo");
    			$("#ltextoDeBusqueda").text('No posee el formato correcto...').addClass("alerta");
			}   
    	if($("#textoDeBusqueda").val().length < 8 || $("#textoDeBusqueda").val().length > 13){
    		error = false;
			$("#textoDeBusqueda").addClass("alertaCombo");
			$("#ltextoDeBusqueda").text('No posee el formato correcto...').addClass("alerta");
    	}				
		if($("#provincia").val() == 'Todas'){
			error=false;
			$("#provincia").addClass("alertaCombo");
			$("#lprovincia").text('Debe seleccionar una provincia...').addClass("alerta");
		   }
		if(error) {   
			$("#listarOperadores").attr('data-opcion', 'listadoServicio');
			abrir($(this), e, false);
		    }
        }else {
        			if($.trim($("#textoDeBusqueda").val())==""){
        				error = false;
        				$("#textoDeBusqueda").addClass("alertaCombo");
        				$("#textoDeBusqueda").val("");
                        $("#textoDeBusqueda").attr('placeholder','Campo vacio');
                        $("#ltextoDeBusqueda").text('Debe ingresar una Razón social...').addClass("alerta");		    			
        			}
        			if($("#textoDeBusqueda").val().length < 3){
        	    		error = false;
        	    		$("#textoDeBusqueda").addClass("alertaCombo");
        	    		$("#ltextoDeBusqueda").text('Debe ingresar minimo 3 caracteres..').addClass("alerta");
        				}  
			        if($("#provincia").val() == 'Todas'){
			        	error=false;
			        	$("#provincia").addClass("alertaCombo");
			        	$("#lprovincia").text('Debe seleccionar una provincia...').addClass("alerta");
			           } 
			        if(error) {   
			        	$("#listarOperadores").attr('data-opcion', 'listarUsuario');
			        	abrir($(this), e, false);
					    }       	       
           }
         }
     if(!$('input[id="busqueda1"]').is(':checked')){
     
        if($.trim($("#numeroSolicitud").val())=="")
        {
        	error = false;

        	$("#numeroSolicitud").addClass("alertaCombo");
			$("#numeroSolicitud").val("");
			$("#numeroSolicitud").attr('placeholder','Debe ingresar el número de solicitud...');
			$("#lnumeroSolicitud").text('No posee el formato correcto.').addClass("alerta");
            }
    	if($("#servicio").val() == 'Todas'){
        	error=false;
        	$("#servicio").addClass("alertaCombo");
        	$("#lservicio").text('Debe seleccionar un servicio...').addClass("alerta");
           }
    	
    	if($("#servicio").val()=="Operadores")         
            if(!esCampoValidoExp("#numeroSolicitud",2) ){
        		error = false; 	
        		$("#numeroSolicitud").addClass("alertaCombo");
        		$("#lnumeroSolicitud").text('No posee el formato correcto.').addClass("alerta");
    			}  
        if($("#servicio").val()=="ROCE"){         
        if(!esCampoValidoExp("#numeroSolicitud",1) ){
    		error = false; 	
    		$("#numeroSolicitud").addClass("alertaCombo");
    		$("#lnumeroSolicitud").text('No posee el formato correcto.').addClass("alerta");
			}  
        }else{
        	if($("#servicio").val()=="proveedorExterior"){
        		if(esCampoValidoExp("#numeroSolicitud",0) ){
            		error = false; 	
            		$("#numeroSolicitud").addClass("alertaCombo");
            		$("#lnumeroSolicitud").text('No posee el formato correcto.').addClass("alerta");
    			}   
    		}else if(!esCampoValidoExp("#numeroSolicitud",0) ){
        		error = false; 	
        		$("#numeroSolicitud").addClass("alertaCombo");
        		$("#lnumeroSolicitud").text('No posee el formato correcto.').addClass("alerta");
    			}
         }
        if(error) {   
        	$("#listarOperadores").attr('data-opcion', 'listarDetalleServicioVue');
        	abrir($(this), e, false);
   		    }    
        }   	       
    }); 

function esCampoValidoExp(elemento,exp){ 
	if(exp==0)var patron = new RegExp($(elemento).attr("data-er"),"g");
	if(exp==1)var patron = new RegExp("^([0-9]{20})P");
	if(exp==2)var patron = new RegExp("^[a-zA-Z\-0-9]+$");
   	return patron.test($(elemento).val());
    }
function verificar(id)
    {
	if(id == 'busqueda1'){$("#consulta1").show(); 
    	$("#consulta2").hide();
   		limpiarConsulta();
    	}
	if(id == 'busqueda2'){$("#consulta2").show(); 
    	$("#consulta1").hide();
   		limpiarConsulta();
    	}  	
	}
function limpiarConsulta()
	{
		$("#textoDeBusqueda").val("");
		$("#numeroSolicitud").val("");	
		$("#provincia").val("Seleccione...");
		$("#servicio").val("Seleccione...");
		$("#textoDeBusqueda").removeAttr("placeholder");
		$("#textoDeBusqueda").removeClass("alertaCombo");
		$("#provincia").removeClass("alertaCombo");	
		$("#servicio").removeClass("alertaCombo");
		$("#numeroSolicitud").removeClass("alertaCombo");
		$("#respuesta").empty();
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un documento para revisarlo.</div>');
	}
function determinarOpcion()
	{
		limpiarConsulta();
		if($('input[id="razon"]').is(':checked'))
			 $("#textoDeBusqueda").attr("maxlength",100);
		else $("#textoDeBusqueda").attr("maxlength",13);		
	}  

//-------------------------------------paginacion------------------------------------------------------------------
    function construirPaginacionexp(elemento,numerototal){
    	if(numerototal!=0){
    	$("#lestado").text('');
    	$(elemento).empty();
    	$(elemento).html(
    		'<span>' +
    			'Mostrar ' + 
    			'<select id="itemsAMostrarexp">' +
    			'	<option value="10">10 items</option>' +
    			'	<option value="20">20 items</option>' +
    			'	<option value="30">30 items</option>' +
    			'	<option value="*">Todos</option>' +
    			'</select>' +
    			' en pantalla.' +
    		'</span>' +
    		'<span style="float:right;">' +
    		'	Items del ' +
    		'	<select id="paginaexp">' +
    		'	</select>' +
    		'	de <span id="totalItemsExp"></span>' +
    		'	<button id="pagAnteriorexp" type="button">&lt;</button>' +
    		'	<button id="pagSiguienteexp"type="button">&gt;</button>' +
    		'</span>'
    	);
    	numeroT=numerototal+1;
    	$("#itemsAMostrarexp option[value='*']").attr("value",numerototal);
    	$("#totalItemsExp").html(numerototal);
    	construirListaPaginasexp(numeroT);
    	mostrarItemsexp(1,numerototal);
    	}else if(numerototal==0)$("#lestado").text('No existen datos para la consulta ...').addClass("alerta");
    }

    function construirListaPaginasexp(numerototal){
    	$("#paginaexp").empty();
    	numeroOpciones = numerototal / parseInt($("#itemsAMostrarexp").val());
    	desplazamiento = parseInt($("#itemsAMostrarexp").val());
    	
    	for (var contador = 0,itemInicial=1; contador<numeroOpciones; contador++,itemInicial=itemInicial+desplazamiento){
    		itemFinal = ((itemInicial+desplazamiento < numerototal)?itemInicial+desplazamiento:numerototal)-1;
    		$("#paginaexp").append("<option value='"+itemInicial+"'>"+(itemInicial)+"-"+(itemFinal)+"</option>");
    	}
    }
    
  function mostrarItemsexp(itemInicial,numerototal){	
    	$("#tablaItems tbody").html("");
    	desplazamiento = parseInt($("#itemsAMostrarexp").val());
    	itemFinal = ((itemInicial+desplazamiento < numerototal)?itemInicial+desplazamiento:numerototal)-1;
    	$("#inicio").val(itemInicial); //cambiar valor inicial para busqueda
    	$("#desplazamiento").val(desplazamiento); //indicar el desplazamiento de la busqueda
    	event.stopImmediatePropagation();
    	abrir($("#listarConsultaItems"),event,false);
    }
    $("#ventanaAplicacion").on("change","#itemsAMostrarexp",function (event){		
    	construirListaPaginasexp(numeroT);
    	event.stopImmediatePropagation();
    	mostrarItemsexp(parseInt($("#paginaexp").val()),numeroT);  	
    });
    $("#ventanaAplicacion").on("change","#paginaexp",function (event){
    	event.stopImmediatePropagation();
    	mostrarItemsexp(parseInt($("#paginaexp").val()),numeroT);
    });
    $("#ventanaAplicacion").on("click","#pagSiguienteexp",function (event){
    	event.stopImmediatePropagation();
    	mostrarNuevaPaginaexp($("#paginaexp option[value='"+$("#paginaexp").val()+"']").next().attr("value"),numeroT);
    });
    $("#ventanaAplicacion").on("click","#pagAnteriorexp",function (event){
    	event.stopImmediatePropagation();
    	mostrarNuevaPaginaexp($("#paginaexp option[value='"+$("#paginaexp").val()+"']").prev().attr("value"),numeroT);
    });
    function mostrarNuevaPaginaexp(nuevaOpcion,numerototal){
    	if (nuevaOpcion != undefined){
    		$("#paginaexp").val(nuevaOpcion);
    		mostrarItemsexp(parseInt($("#paginaexp").val()),numerototal);
    	}
    }  
//-----------------------------------------------------------------------------------------------------------------------------------
</script>


