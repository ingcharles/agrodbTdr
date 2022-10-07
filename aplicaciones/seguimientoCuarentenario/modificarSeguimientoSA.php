<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguimientoCuarentenario.php';

$conexion = new Conexion();
$csc = new ControladorSeguimientoCuarentenario();

$usuario = $_SESSION['usuario'];
$provincia=$_SESSION['nombreProvincia'];
$idDetalleSeguimientoCuarentenarioSa=$_POST['id'];

$resultadoDatosDetalle=$csc->abrirDetalleSeguimientoSADDA($conexion, $idDetalleSeguimientoCuarentenarioSa);
$datosDetalleSeguimiento=pg_fetch_assoc($resultadoDatosDetalle);

$requisitoPais = json_decode($datosDetalleSeguimiento['datos_seguimiento'], true);
$arrayDetalleSeguimientos=(array)$requisitoPais;

$cantidadSanos = $datosDetalleSeguimiento['cantidad_sanos'];
$cantidadEnfermos =  $datosDetalleSeguimiento['cantidad_enfermos'];
$cantidadMuertos = $datosDetalleSeguimiento['cantidad_muertos'];

$cantidadProductoDisponible = $cantidadSanos + $cantidadEnfermos + $cantidadMuertos;

?>
			
<legend>Modificar Seguimiento</legend>
	
<div data-linea="1">
	<label>Sanos: </label> 
	<input type="text" id="sanos" name="sanos" value="<?php echo $cantidadSanos;?>" readOnly onkeypress='ValidaSoloNumeros()' placeholder="Ej: 2" maxlength="5" data-er="^[0-9]+$" />
</div>
<div data-linea="1">
	<label>Enfermos: </label>
	<input type="text" id="enfermos" name="enfermos" value="<?php echo $cantidadEnfermos;?>" readOnly onkeypress='ValidaSoloNumeros()' placeholder="Ej: 2" maxlength="5" data-er="^[0-9]+$" />
</div>
<div data-linea="1">
	<label>Muertos: </label>
	<input type="text" id="muertos" name="muertos" value="<?php echo $cantidadMuertos;?>"  readOnly onkeypress='ValidaSoloNumeros()' placeholder="Ej: 2" maxlength="5" data-er="^[0-9]+$" />
</div>
<div data-linea="1">
	<label>Total: </label>
	<input type="text" id="total" name="total" value="<?php echo $datosDetalleSeguimiento['cantidad_total_seguimiento'];?>" readOnly onkeypress='ValidaSoloNumeros()' placeholder="Ej: 2" maxlength="5" data-er="^[0-9]+$" />
</div>

<table id="tablaItemsSeguimientos">
	<thead>
		<tr>
			<th>Identificación</th>
			<th>Cantidad</th>
			<th>Sexo</th>
			<th>Edad</th>
			<th>Sintomatología</th>	
			<th>Observaciones</th>
		</tr>
	</thead>
	<tbody id="tablaItemsDetalle" ></tbody>
</table>
<button type="button" style="" id="agregarFila" name="agregarFila" class="mas"></button>
<hr/>

<div data-linea="2">
	<label>Resultado Inspección: </label>
	<select id="resultadoInspeccion" name="resultadoInspeccion">
		<option value="">Seleccione...</option>
		<option value="Continuar cuarentena pos entrada">Continuar cuarentena pos entrada</option>
		<option value="Finalizar cuarentena pos entrada">Finalizar cuarentena pos entrada</option>
		<option value="Sacrificio sanitario">Sacrificio sanitario</option>
	</select>
</div>
<div data-linea="3" id="vistaAdjuntoSacrificioSanitario">
	<label>Acta Sacrificio Sanitario: </label> <?php echo ($datosDetalleSeguimiento['ruta_sacrificio_sanitario'] == ''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$datosDetalleSeguimiento['ruta_sacrificio_sanitario'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar</a>')?>
</div>
<div data-linea="4" id="vistaSacrificioSanitario">
<label>Adjuntar Acta Sacrificio Sanitario: </label>
	<input type="hidden" class="rutaArchivo" id="archivoSacrificioSanitario" name="archivoSacrificioSanitario" value="<?php echo $datosDetalleSeguimiento['ruta_sacrificio_sanitario'];?>" />
	<input type="file" class="archivo" id="informeSacrificioSanitario"  name="informe" accept="application/pdf"/>
	<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
	<button type="button" class="subirArchivoSacrificioSanitario adjunto" data-rutaCarga="aplicaciones/seguimientoCuarentenario/archivosSacrificioSanitario" >Subir archivo</button>
</div>

<script type="text/javascript">

var cantidadProductoDisponibleModificar = <?php echo json_encode($cantidadProductoDisponible);?>;

	$(document).ready(function(){
		$('#opcion').val('modificar');
		$("#btnGuardar").attr('disabled',false);
		$("#btnCierre").attr('disabled',true);
		$("#idDetalleSeguimientoCuarentenarioSa").val(<?php echo json_encode($idDetalleSeguimientoCuarentenarioSa);?>);
		
		var estadoSeguimiento = <?php echo json_encode($qSeguimientoDDA[0]['estadoSeguimiento']);?>;
		var tamanioArchivo = <?php echo json_encode(ini_get('upload_max_filesize'));?>;
		var arrayDetalleSeguimientos = <?php echo json_encode($arrayDetalleSeguimientos); ?>;
		cargarValorDefecto("resultadoInspeccion","<?php echo $datosDetalleSeguimiento['resultado_inspeccion'];?>");
		var ultimoRegistro = <?php echo json_encode($_POST['ultimoRegistro']);?>;
		if(ultimoRegistro=='no'){
			$("#resultadoInspeccion option[value='Finalizar cuarentena pos entrada']").prop("disabled",true);
		}else{
			$("#resultadoInspeccion option[value='Finalizar cuarentena pos entrada']").prop("disabled",false);
		}
		
		var resultadoInspeccion = <?php echo json_encode($datosDetalleSeguimiento['resultado_inspeccion']); ?>; 
	

		var sDetalle='';
		var contador=0;
		for(var i=0;i<arrayDetalleSeguimientos.length;i++) {
			var valorSelectDuracion=''
			if(arrayDetalleSeguimientos[i]['edadProducto'] == 0){
				valorSelectDuracion+="<option value='Días'>Días</option>";
				valorSelectDuracion+="<option value='Meses'>Meses</option>";
				valorSelectDuracion+="<option value='Años'>Años</option>";
			}else if(arrayDetalleSeguimientos[i]['edadProducto'] == 1){
				valorSelectDuracion+="<option value='Día'>Día</option>";
				valorSelectDuracion+="<option value='Mes'>Mes</option>";
				valorSelectDuracion+="<option value='Año'>Año</option>";
			}else if(arrayDetalleSeguimientos[i]['edadProducto'] > 1){
				valorSelectDuracion+="<option value='Días'>Días</option>";
				valorSelectDuracion+="<option value='Meses'>Meses</option>";
				valorSelectDuracion+="<option value='Años'>Años</option>";
			}
			contador=contador+1;
			$("#tablaItemsSeguimientos tbody").append("<tr id='r_"+contador+"'><td><input type='text' name='dIdentificacion[]' id='dIdentificacion' value='"+arrayDetalleSeguimientos[i]['identificacionProducto']+"' style='width: 70%;'></td>"+
		    	'<td><input type="text" id="dCantidad" name="dCantidad[]" value="'+arrayDetalleSeguimientos[i]['cantidadProducto']+'" style="width: 50%;" onkeypress="ValidaSoloNumeros()" placeholder="Ej: 2" maxlength="7" data-er="^[0-9]+$"></td>'+
	            '<td><select id="dSexo" name="dSexo[]" style="width:90%">'+
				'<option value="N/A">N/A</option>'+
				'<option value="Macho">Macho</option>'+
				'<option value="Hembra">Hembra</option>'+
	        	'</select></td>'+
				'<td><input type="text" id="dEdad" name="dEdad[]" value="'+arrayDetalleSeguimientos[i]['edadProducto']+'" style="width:16%" onkeypress="ValidaSoloNumeros()" placeholder="Ej: 2" maxlength="4" data-er="^[0-9]+$">'+
				'<select id="dDuracion" name="dDuracion[]" style="width:40%">'+
				'<option value="">Seleccione...</option>'+valorSelectDuracion+
				'</select></td>'+
	        	'<td><select id="dSintomatologia" name="dSintomatologia[]"  style="width:90%">'+
	        	'<option value="Ninguna">Ninguna</option>'+
				'<option value="Síndrome respiratorio">Síndrome respiratorio</option>'+
				'<option value="Síndrome digestivo">Síndrome digestivo</option>'+
				'<option value="Síndrome reproductivo">Síndrome reproductivo</option>'+
				'<option value="Síndrome neurológico">Síndrome neurológico</option>'+
				'<option value="Muertos">Muertos</option>'+
				'<option value="Otros">Otros</option>'+
				'</select></td>'+
				'<td><input type="text" id="dObservacion" name="dObservacion[]" value="'+arrayDetalleSeguimientos[i]['observacionProducto']+'" style="width: 70%;"></td>'+
				'</tr>');
			
			$("#r_"+ contador+ " select[name='dSexo[]']").find('option[value="'+arrayDetalleSeguimientos[i]['sexoProducto']+'"]').prop("selected","selected")
			$("#r_"+ contador+ " select[name='dSintomatologia[]']").find('option[value="'+arrayDetalleSeguimientos[i]['sintamatologiaProducto']+'"]').prop("selected","selected")
			$("#r_"+ contador+ " select[name='dDuracion[]']").find('option[value="'+arrayDetalleSeguimientos[i]['duracionProducto']+'"]').prop("selected","selected")
		}

		if(resultadoInspeccion=='Sacrificio sanitario'){
			$("#vistaAdjuntoSacrificioSanitario").show();
			$("#vistaSacrificioSanitario").show();
		}else{
			$("#vistaAdjuntoSacrificioSanitario").hide();
			$("#vistaSacrificioSanitario").hide();
		}
		
		distribuirLineas();

		if($("#estadoSeguimiento").val()=='cerrado'){
			$("#btnGuardar").attr('disabled',true);
			$("#vistaSeguimientoAbierto *").prop("disabled",true);
			$("#vistaSeguimientoAbierto legend").html("Nuevo Seguimiento");
		}else{
			$("#btnGuardar").html('Modificar');
		}
		$('#agregarFila').attr('disabled',true);

		$("#cantidadProductoDisponible").val(cantidadProductoDisponibleModificar);

	});

	function exitoIngresoSeguimiento(){
		this.ejecutar = function(msg){
	
			mostrarMensaje("Nuevo registro agregado","EXITO");
			var fila = msg.mensaje;
			$("#seguimientoGuardados").append(fila);
		
			$("#nuevoSeguimientoCuarentenario #archivoSacrificioSanitario").parent().find(".archivo").removeClass('verde');
			$("#nuevoSeguimientoCuarentenario #archivoSacrificioSanitario").parent().find(".estadoCarga").html("En espera de archivo... (Tamaño máximo "+ tamanioArchivo +"B)");
			$("#nuevoSeguimientoCuarentenario fieldset input[id='sanos'],[id='enfermos'],[id='muertos'],[id='total']").val(0);
			$("#nuevoSeguimientoCuarentenario fieldset input[id='archivoSacrificioSanitario'],[id='resultadoInspeccion']").val("");
			$("#nuevoSeguimientoCuarentenario fieldset select[id='resultadoInspeccion']").val("");
			$("#nuevoSeguimientoCuarentenario fieldset #tablaItemsSeguimientos tbody").html('');
		
		};
	}

	var contador=0;
	$("#agregarFila").on("click",function(event){
		$(".alertaCombo").removeClass("alertaCombo");
	 	var error = false;
				
		if($("#opcion").val()=='nuevo'){
			var valorSeguimiento=$("#seguimientoGuardados tbody tr:last-child .resutadoInspeccion").html();
			if(valorSeguimiento=='Finalizar cuarentena pos entrada'){
				error = true;	
				 $("#seguimientoGuardados tbody tr:last-child .resutadoInspeccion").addClass("alertaCombo");
				 mostrarMensaje("No es posible realizar más seguimiento por el último resultado fue ( Finalizar cuarentena pos entrada )","FALLO");
			}	
		}else{
			 error = true;
			 mostrarMensaje("No es posible agregar más filas cuando se está modificando un registro","FALLO");
				
		}
			
		$('#tablaItemsSeguimientos tbody tr #dCantidad').each(function(){
			if($(this).val()==0 || $(this).val()==''){
				error = true;
				mostrarMensaje("No es posible agregar otro fila hasta que la anterior sea llenada","FALLO");
			}
		});

		if(parseInt($('#total').val())>=parseInt($('#cantidadProductoDisponible').val())){
			error = true;
			mostrarMensaje("No es posible agregar mas fila porque se ha completado la cantidad total de productos","FALLO");
		}

	   	if (!error){
	   		contador=contador+1;
	    	$("#tablaItemsSeguimientos tbody").append("<tr id='r_"+contador+"'><td><input type='text' name='dIdentificacion[]' id='dIdentificacion' style='width: 70%;'></td>"+
		    	'<td><input type="text" id="dCantidad" name="dCantidad[]" value="0" style="width: 50%;" onkeypress="ValidaSoloNumeros()" placeholder="Ej: 2" maxlength="4" data-er="^[0-9]+$"></td>'+
	            '<td><select id="dSexo" name="dSexo[]" style="width:90%">'+
				'<option value="N/A">N/A</option>'+
				'<option value="Macho">Macho</option>'+
				'<option value="Hembra">Hembra</option>'+
	        	'</select></td>'+
				'<td><input type="text" id="dEdad" name="dEdad[]" style="width:16%" onkeypress="ValidaSoloNumeros()" placeholder="Ej: 2" maxlength="4" data-er="^[0-9]+$">'+
				'<select id="dDuracion" name="dDuracion[]" style="width:40%">'+
				'<option value="">Seleccione...</option>'+
				'</select></td>'+
	        	'<td><select id="dSintomatologia" name="dSintomatologia[]" style="width:90%">'+
	        	'<option value="Ninguna">Ninguna</option>'+
				'<option value="Síndrome respiratorio">Síndrome respiratorio</option>'+
				'<option value="Síndrome digestivo">Síndrome digestivo</option>'+
				'<option value="Síndrome reproductivo">Síndrome reproductivo</option>'+
				'<option value="Síndrome neurológico">Síndrome neurológico</option>'+
				'<option value="Muertos">Muertos</option>'+
				'<option value="Otros">Otros</option>'+
				'</select></td>'+
				'<td><input type="text" id="dObservacion" name="dObservacion[]" style="width: 70%;"></td>'+
			'</tr>');
		}	
	});

	$('button.subirArchivoSacrificioSanitario').click(function (event) {

		numero = Math.floor(Math.random()*100000000);	
		
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {
	        	
        		subirArchivo(
    	                archivo
    	                , identificadorOperador+'_'+numero
    	                , boton.attr("data-rutaCarga")
    	                , rutaArchivo
    	                , new carga(estado, archivo, $("#vacio"))
    	                
    	            );
	            
        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("");
        }
    });
    
	$("#tablaItemsSeguimientos").on("change","#dCantidad, #dSintomatologia",function (event){
		sumarCantidades(this);
	});
	
	
	function sumarCantidades(campoCantidad){
		var valorNoSuma=parseInt($(campoCantidad).parents('tr').find('#dCantidad').val());
		var valorSintomatologia=$(campoCantidad).parents('tr').find('#dSintomatologia option:selected').val();

		if($(campoCantidad).parents('tr').find('#dCantidad').val()=='' || valorNoSuma>parseInt($('#cantidadProductoDisponible').val())){
			$(campoCantidad).parents('tr').find('#dCantidad').val(0);
			valorNoSuma=0;
		} 

		var sumaSanos=0;
		var sumaEnfermos=0;
		var sumaMuertos=0;
		var sumaTotal=0;
    	$('#tablaItemsSeguimientos tbody tr').each(function(){
	 		var cantidad=parseInt($(this).find('input[id="dCantidad"]').val());
	 		var sintomatologia=$(this).find('select[id="dSintomatologia"] option:selected').val();

	 		if(sintomatologia=='Ninguna'){
		 		sumaSanos+=cantidad;
	 		}else if(sintomatologia=='Síndrome respiratorio' || sintomatologia=='Síndrome digestivo' || sintomatologia=='Síndrome reproductivo' || sintomatologia=='Síndrome neurológico' || sintomatologia=='Otros'){
	 			sumaEnfermos+=cantidad;
	 		}else if(sintomatologia=='Muertos'){
	 			sumaMuertos+=cantidad;
	 		}
	 	}); 

    	sumaTotal=sumaSanos+sumaEnfermos;
    	if(sumaTotal>parseInt($('#cantidadProductoDisponible').val())){
	       	if(valorSintomatologia=='Ninguna'){
		 		sumaSanos-=valorNoSuma;
	 		}else if(valorSintomatologia=='Síndrome respiratorio' || valorSintomatologia=='Síndrome digestivo' || valorSintomatologia=='Síndrome reproductivo' || valorSintomatologia=='Síndrome neurológico' || valorSintomatologia=='Otros'){
	 			sumaEnfermos-=valorNoSuma;
	 		}else if(valorSintomatologia=='Muertos'){
	 			sumaMuertos-=valorNoSuma;
	 		}
	 		
    		sumaTotal-=valorNoSuma;
    		
    		$(campoCantidad).parents('tr').find('#dCantidad').val(0);
    		mostrarMensaje("No se puede sobrepasar la cantidad total de producto disponible","FALLO");
    	}else{
    		mostrarMensaje("","EXITO");
    	}

    	$('#sanos').val(sumaSanos);
		$('#enfermos').val(sumaEnfermos);
		$('#muertos').val(sumaMuertos);
		$('#total').val(sumaTotal);
		
	}

	$('#resultadoInspeccion').change(function(event) {
		if($('#resultadoInspeccion option:selected').text()=='Sacrificio sanitario'){
			$('#vistaSacrificioSanitario').show();
		}else{
			$('#archivoSacrificioSanitario').val("");
			$('#vistaSacrificioSanitario').hide();
		}
		
	});

	$("#tablaItemsSeguimientos").on("change"," #dEdad",function (event){
		var valorSelect=$(this).siblings();
		if($(this).val() == 0){
			valorSelect.html("");
			valorSelect.append("<option value=''>Seleccione...</option>");
			valorSelect.append("<option value='Días'>Días</option>");
			valorSelect.append("<option value='Meses'>Meses</option>");
			valorSelect.append("<option value='Años'>Años</option>");
		}else if($(this).val() == 1){
			valorSelect.html("");
			valorSelect.append("<option value=''>Seleccione...</option>");
			valorSelect.append("<option value='Día'>Día</option>");
			valorSelect.append("<option value='Mes'>Mes</option>");
			valorSelect.append("<option value='Año'>Año</option>");
		}else if($(this).val() > 1){
			valorSelect.html("");
			valorSelect.append("<option value=''>Seleccione...</option>");
			valorSelect.append("<option value='Días'>Días</option>");
			valorSelect.append("<option value='Meses'>Meses</option>");
			valorSelect.append("<option value='Años'>Años</option>");
		}
	});
	
</script>