<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorLotes.php';

$conexion = new Conexion();
$cl = new ControladorLotes();
$usuario= $_SESSION['usuario'];
?>

<header>
	<h1>Nuevo Lote</h1>	
</header>

<div id="estado"></div>

<form id="nuevoLote" data-rutaAplicacion="conformacionLotes" data-accionEnExito="ACTUALIZAR">
<input type ="hidden" id="idUsuario" name="idUsuario" value=<?php echo $usuario?>>
<input type ="hidden" id="codigoProducto" name="codigoProducto" >
<input type="hidden" id="opcion" name="opcion" />
	<fieldset>
	<legend>Datos de ingreso:</legend>
	<div data-linea="1" >
		<label for="cbProducto">Nombre del producto: </label>
		<select id="cbProducto" name="cbProducto">		
				<option value="">Seleccione....</option>
				<?php 
					$val=0;
					$tipo = $cl->obtenerCodigoTipoOperacion($conexion,"SV","ACO");
					$tipofila =pg_fetch_assoc($tipo);
					$productos = $cl->listarProductosTrazabilidad($conexion,$usuario);
					while ($produFila = pg_fetch_assoc($productos)){
						if($produFila['total']=='2' || ($produFila['total']=='1' && $produFila['tipo']==$tipofila['id_tipo_operacion']) ){
							echo '<option value="' . $produFila['id_producto'] . '">' . $produFila['nombre_comun'].'</option>';
						}
					}
				?>					
		</select>		
	</div>
	
	<div id="productoFlujo" style="width:100%">
    		<div data-linea="4" id="resultadoProveedor" >
    			<label for="cbProveedor">Nombre del Proveedor: </label>
    			<select id="cbProveedor" name="cbProveedor" disabled>
    					<option value="">Seleccione....</option>
    			</select>
    			<input type="hidden" id="nproveedor" name="nproveedor"/>			
    		</div>
	</div>
	
	<div data-linea="7" id="resultadoSitioProveedor" > </div>
		
	</fieldset>
	<fieldset>
		
		<legend>Selección de ingresos:</legend>		
		<div data-linea="1">
			<div id="dRegistro"></div>			
		</div>		
		
	</fieldset>
	
	<button class="mas" disabled="disabled" id="agregarRegistro" onclick="addFilasprueba('contenedorProducto');return false;" >Agregar</button>		
	</form>
	
	<fieldset>
		
		<legend>Registros que conforman el Lote:</legend>		
		<div data-linea="1">
			<div id="dRegistroAConformar">
				<table style="width:100%" id="tablaLotesConformar">				
				<thead>
				<tr>
					<th>ID</th>
					<th>Código Ingreso</th>
					<th>Proveedor</th>
					<th>Cantidad</th>	
				</tr>
				</thead>
				<tbody id="bodyTablaLotesConformar">
				<!-- tr></tr -->
				</tbody>
				</table>				
			</div>			
		</div>		
		
	</fieldset>
	<button type="submit" id="conformarLote" disabled="disabled">Conformar Lote</button>
	<form id="loteConformado" data-rutaAplicacion="conformacionLotes" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" id="opcion" name="opcion">		
		<input type="hidden" id="idProducto" name="idProducto">
		<input type ="hidden" id="nProducto" name="nProducto">
		<input type ="hidden" id="idAreaProveedor" name="idAreaProveedor">
		<input type ="hidden" id="idUsuario" name="idUsuario" value=<?php echo $usuario?>>
		<input type ="hidden" id="idProveedor" name="idProveedor" >				
		<div id="dLote"></div>		
		<div id="resultadoCodigoLote"></div>	
		<div id="cargarRegistros"></div>				
		<div id=resultadoCaracteristica></div>		
		<button type=submit class=guardar id=guardarLote style="display:none">Guardar</button>
	</form>


<script type="text/javascript">

	var variedad="";
	var con=0;
	var suma=0;
	var rDuplicado=0;

	$(document).ready(function(){	
		distribuirLineas();		
		$("#resultadoSitio").hide();
		$("#resultadoSitioProveedor").hide();
	});	

	$("#cbProducto").change(function(event){

		if($.trim($("#cbProducto").val())){
			$("#nSitioProveedor").val("");
			$("#codigoProducto").val($("#cbProducto").val());
			$("#estado").html("");
			
			$("#nProducto").val($("#cbProducto option:selected").text());	    	
	        $("#nuevoLote").attr('data-destino', 'resultadoProveedor');
	        $("#nuevoLote").attr('data-opcion', 'comboConformarLote');
	        $("#opcion").val('proveedor');
	       	abrir($("#nuevoLote"), event, false);

	       	$("#nuevoLote").attr('data-destino', 'dRegistro');
	        $("#nuevoLote").attr('data-opcion', 'comboConformarLote');
	        $("#opcion").val('borrarRegistros');
	       	abrir($("#nuevoLote"), event, false);	       	
	       		       	       	
		} else{
			$('#cbProveedor')
		    .find('option')
		    .remove()
		    .end()
		    .append('<option value="">Seleccione....</option>')
		    .val('')
		    .attr("disabled","disabled");
		    $("#bodyTablaLotes").html("");
		    $("#estadoIngresos").html("");
		    $("#estado").html("");
		    $("#agregarRegistro").attr("disabled","disabled");
		    $("#sitio").attr("disabled","disabled").val("");
		    $("#sitioProveedor").attr("disabled","disabled").val("");
		}
	});

	$("#conformarLote").click(function(event){
		event.preventDefault();

		$("#idProveedor").val($("#cbProveedor").val());
 		$("#loteConformado").attr('data-destino','dLote');
 		$("#loteConformado").attr('data-opcion', 'comboConformarLote');
 		$("#loteConformado #opcion").val("lote");
 		abrir($("#loteConformado"),event,false);

 		$("#guardarLote").show(); 		
 		$("#detalleItem #codigoLote").focus();

 		$("#loteConformado").attr('data-destino','resultadoCaracteristica');
 		$("#loteConformado").attr('data-opcion', 'comboConformarLote');
 		$("#loteConformado #opcion").val("caracteristica");
 		abrir($("#loteConformado"),event,false);
 		  
 		
 	});


	 $("#loteConformado").submit(function(event){
		event.preventDefault();
				
	    $(".alertaCombo").removeClass("alertaCombo");
	    var error = false;
	    var error2 = false;
	    var str =$("#nProducto").val();
		var nombreProducto;
		var index;	

	    $("#codigoAreaOperador").val($("#nSitio").val());
	    $("#codigoAreaProveedor").val($("#nSitioProveedor").val());
	    
	    if ($.trim($("#detalleItem #codigoLote").val()) == "" ) {
	        error = true;
	        $("#detalleItem #codigoLote").addClass("alertaCombo");
	    }		
		       
	    if ($.trim($("#detalleItem #paisDestino").val()) == "" ) {
	        error = true;
	        $("#detalleItem #paisDestino").addClass("alertaCombo");
	    }    


	    if($("#CProveedor").val()!="2"){
			if ($.trim($("#detalleItem #cbProveedor").val()) == "" ) {
		        error = true;
		        $("#detalleItem #cbProveedor").addClass("alertaCombo");
		        $("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
		    }	        
		}

	    if($("#CAreas").val()!="2"){
			if ($.trim($("#detalleItem #sitio").val()) == "" ) {
		        error = true;
		        $("#detalleItem #sitio").addClass("alertaCombo");
		        $("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
		    }	        
		}

	    if($("#CAreaProveedor").val()!="3"){
			if ($.trim($("#detalleItem #sitioProveedor").val()) == "" ) {
		        error = true;
		        $("#detalleItem #sitioProveedor").addClass("alertaCombo");
		        $("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
		    }	        
		}
	    

	    var cantidad = parseFloat($("#detalleItem #cantidadLote").val());		    
	    if (cantidad < 1) {
	        error2 = true;	        
	        mostrarMensaje("Debe existir al menos un registro para conformar el Lote.","FALLO");
	    }	
	    	      
	    if (!error && !error2){ 

	        $("#loteNro").removeAttr("disabled","disabled");
			$("#fechaConformacion2").removeAttr("disabled","disabled");
			$("#cantidadLote").removeAttr("disabled","disabled");
			$("#loteVariedad").removeAttr("disabled","disabled");
			$("#cargarRegistros").html("");

			filas=$('#tablaLotesConformar tbody tr').length;
	        $("#tablaLotesConformar tbody tr").each(function (rows) {		        
	    		if(filas>0){
		            var cantidad = $(this).find("td").eq(0).html();
		            $("#idRegistro").val($(this).find("td").eq(0).html());
		            var val =$(this).find("td").eq(0).html();		            			              
			        $("#cargarRegistros").append("<input type='hidden' name='idRegistro[]' value='"+val+"'>");			        
	    		}
    		});

	        $("#loteConformado").attr('data-destino', 'nuevoLote');    	
	        $("#loteConformado").attr('data-opcion', 'guardarNuevoLote');
	        $("#loteConformado #opcion").val("guardarLote");
	        ejecutarJson($(this));
	        
	        
	    } else {
	    	 if (!error && error2) {
	 	       $("#estado").html("Debe existir al menos un registro para conformar el Lote.").addClass('alerta');
	 	    } else		 	    
	        	mostrarMensaje("Por favor revise los campos obligatoriosss.","FALLO");
	    }
	 });	

	var producto="";
	function addFilasprueba(tableID){
		var cont=0;
		var array2="";

		if($("#CProveedor").val()=="1"){
			$("#cbProveedor").attr("disabled",true);
	  	}

		if($("#CAraProveedor").val()=="1"){
			$("#sitioProveedor").attr("disabled",true);
	  	}

 		if($('#tablaLotes input:checkbox:checked').length < 1){
 			$("#estado").html("Seleccione uno o más registros para conformar el lote").addClass("alerta");
 		}
 		
 		else{
 			$("#estado").html("");
 		}
		
		$('#tablaLotes input:checkbox:checked').each(function(){
			$("#cbProducto").attr("disabled","disabled");


			var str =$("#nProducto").val();
			var nombreProducto;
			var index;				
			//$("#sitioProveedor").attr("disabled","disabled");
		    var array = $(this).parent().siblings().map(function(){        
		      return $(this).text().trim();
		    }).get();  
		   
		   var thArray = [];

            $('#tablaLotes > thead > tr > th').each(function(){
                thArray.push($(this).text());
            });                   
       
            
            var banderaVariedad="vacio";
            var columnaVariedad=0;
            var cadena = "vacia";

			var i = 0;
            
            thArray.forEach(function(element){             
                if(element.toUpperCase() =='VARIEDAD'){
                	banderaVariedad="existe";
                	columnaVariedad= i ;
                	cadena = element;
                }
                i++;                
            });           
       		    
		    if(con===0){
			    
			    $("#cbProducto").attr("disabled","disabled");
			    $("#sitio").attr("disabled","disabled");			
			    			    
			    con+=1;
			    if(banderaVariedad=="existe"){
			    	variedad = array[columnaVariedad-1];
			    	console.log(columnaVariedad);
			    	console.log("1:" + variedad);
			    }
			    
			    producto = $("#cbProducto").val();
			    $("#idProducto").val($("#cbProducto").val());
			    $("#conformarLote").removeAttr("disabled");
			    var table = document.getElementById('tablaLotesConformar');
			    var nColumnas = $("#tablaLotes tr:last td").length;
				var caracteristicas="";			    
			    inicio=5;
			    
			    if(nColumnas>6){
				    limite = nColumnas - 6;
				    for(i=0;i<=limite-1;i++){
				    	caracteristicas+='<td>'+array[inicio]+'</td>';
				    	inicio+=1;
				    	$("#tablaLotesConformar thead tr").append('<th>'+thArray[inicio]+'</th>');
				    }
			    }

			    $("#tablaLotesConformar thead tr").append('<th>Eliminar</th>');
				dProveedor = $("#cbProveedor").val();
				dArea= $("#idAreaProveedor").val();
				
			    var cadena = '<tr><td>'+array[0]+'</td><td>'+array[1]+'<input type="hidden" value="'+dProveedor+'" id="dProveedor" name="dProveedor[]">'+'<input type="hidden" value="'+dArea+'" name="dArea[]">'+
			    '</td><td>'+array[3]+'</td><td>'+array[4]+'</td>'+caracteristicas+'<td class="borrar"><button class="icono" onclick="delFilaActual(this);return false"></button></td></tr>';
			    			    
				$("#tablaLotesConformar tbody").append(cadena);

				
			  
		    } else{
			    
		    	if(producto != $("#cbProducto").val().toLowerCase()){		    		
		    		$("#estado").html("No puede conformar un lote con distintos productos.").addClass('alerta');		    	
		    	} else{			    				    	
			    	
			    	var validacion=false;

			    	if(banderaVariedad=="existe"){
			    		
    		    		if(variedad != array[columnaVariedad-1]){
    		    			
    		    			$("#estado").html("Uno o más registros no fueron agregados al tener distintas variedades.").addClass('alerta');

    		    			validacion=true;
    		    		}
			    	}
			    	
		    		if(!validacion){

		    			verificarRegistro(array[0]);
		    			
			    		if(rDuplicado==1){			    			
			    			$("#estado").html("Uno o varios registros ya se encuentran agregados.").addClass('alerta');			    
			    			console.log("entro duplicado");			
			    		}
			    		else{
			    			
			    			$("#conformarLote").removeAttr("disabled");	

			    			var nColumnas = $("#tablaLotes tr:last td").length;
							var caracteristicas="";			    
						    inicio=5;
						    
						    if(nColumnas>6){
							    limite = nColumnas - 6;
							    for(i=0;i<=limite-1;i++){
							    	caracteristicas+='<td>'+array[inicio]+'</td>';
							    	inicio+=1;							    	
							    }
						    }

						    dProveedor = $("#cbProveedor").val();
							dArea= $("#idAreaProveedor").val();

							var validarAreaUnica = false;

							if($("#CAreaProveedor").val()=="1"){

    							$('#tablaLotesConformar tbody tr').each(function (rows){
    							    valProveedor = $(this).find("td").eq(1).find('input[name="dProveedor[]"]').val();
    							    valArea = $(this).find("td").eq(1).find('input[name="dArea[]"]').val();		
    
    							    if(valProveedor==dProveedor){
    								    if(valArea!=dArea){
    								    	validarAreaUnica = true;
    								    }
    							    }
    							    
    							    console.log(valProveedor +" - " + valArea );
    						    });
    
    						    if(!validarAreaUnica){
        						    var cadena = '<tr><td>'+array[0]+'</td><td>'+array[1]+'<input type="hidden" value="'+dProveedor+'" name="dProveedor[]">'+'<input type="hidden" value="'+dArea+'" name="dArea[]">'+
        						    '</td><td>'+array[3]+'</td><td>'+array[4]+'</td>'+caracteristicas+'<td class="borrar"><button class="icono" onclick="delFilaActual(this);return false"></button></td></tr>';			    
        							$("#tablaLotesConformar tbody").append(cadena);
    
    						    } else{
    						    	mostrarMensaje("No puede conformar un lote con diferentes areas del proveedor.","FALLO");
    						    }

							} else{
								var cadena = '<tr><td>'+array[0]+'</td><td>'+array[1]+
    						    '</td><td>'+array[3]+'</td><td>'+array[4]+'</td>'+caracteristicas+'<td class="borrar"><button class="icono" onclick="delFilaActual(this);return false"></button></td></tr>';			    
    							$("#tablaLotesConformar tbody").append(cadena);
							}
				    	}
		    		}
		    	}
		    }		    
		    sumarTabla(0);
		  });
		
		}
	
		function delFilaActual(r){
		    var i = r.parentNode.parentNode.rowIndex;		    	    
		    var table = document.getElementById('tablaLotesConformar');
		    table.deleteRow(i);
	 		var rowCount = table.rows.length;
	 		if(rowCount == 1){
		 		$("#conformarLote").attr("disabled","disabled");
		 		$("#cbProducto").removeAttr("disabled","disabled");
		 		$("#cbProveedor").removeAttr("disabled","disabled");
		 		$("#sitio").removeAttr("disabled","disabled");
		 		$("#sitioProveedor").removeAttr("disabled","disabled"); 		
		 		$("#conformarLote").attr("disabled","disabled");
		 		$("#dLote").html("");
		 		$("#resultadoCaracteristica").html("");
		 		$("#guardarLote").hide();
		 		$("#estado").html("");
		 		con=0;
		 		variedad="";

		 		$("#tablaLotesConformar").html('<thead>'+
						'<tr>'+
						'<th>ID</th>'+
						'<th>Código Ingreso</th>'+
						'<th>Proveedor</th>'+
						'<th>Cantidad</th>'	+
					'</tr>'+
					'</thead>'+
					'<tbody id="bodyTablaLotesConformar">'+					
					'</tbody>')
	 		}
	 		sumarTabla(1);	
		}

	   function verificarRegistro(produ){
		   $('#tablaLotesConformar tbody tr').each(function (rows) {
			   var rd= $(this).find('td').eq(0).html();
			   filas=$('#bodyTablaLotesConformar tr').length;
			   //if (rows>0){
			    if (filas>0){				    
			    	if(rd == produ){			    		
			    		rDuplicado=1;
			    		return false;
			    	} else{
			    		rDuplicado=0;			    		
			    	}			        
			    }
		    
			});
	   }
	
		
 </script>
