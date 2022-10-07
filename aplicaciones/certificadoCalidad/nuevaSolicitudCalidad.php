<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

$identificador = $_SESSION['usuario'];

$qExportador = $cr->buscarOperador($conexion,$identificador);
$exportador = pg_fetch_assoc($qExportador);

$paisesExportacion = $cr->obtenerPaisXtipoOperacionArea($conexion, 'EXP', 'SV');

while($fila = pg_fetch_assoc($paisesExportacion)){
	$paisesExportacionXProducto[]= array(idProducto=>$fila['id_producto'], nombreProducto=>$fila['nombre_producto'], idPais=>$fila['id_pais'], nombrePais=>$fila['nombre_pais']);
}


$paisOrigen = pg_fetch_assoc($cc->obtenerIdLocalizacion($conexion, 'ECUADOR', 'PAIS'));

$puertosEcuador = $cc->listarPuertosPorPais($conexion, $paisOrigen['id_localizacion']);

while($fila = pg_fetch_assoc($puertosEcuador)){
	$puertosOrigen[]= array(idPuerto=>$fila['id_puerto'], nombrePuerto=>$fila['nombre_puerto'], tipoPuerto=>$fila['tipo_puerto'], codigoPuerto=>$fila['codigo_puerto']);
}

$provincia =$cr->listarDatosXtipoArea($conexion, $identificador, 'Centro de acopio', 'registrado', 'PROVINCIAS');

$qAreas = $cr->listarDatosXtipoArea($conexion, $identificador, 'Centro de acopio', 'registrado', 'AREAS');

while($fila = pg_fetch_assoc($qAreas)){
	$areas[]= array(idArea=>$fila['id_area'], nombreArea=>$fila['nombre_area'], nombreSitio=>$fila['nombre_lugar'], idProvincia=>$fila['id_localizacion']);
}

$qProductos = $cr->listarDatosXtipoArea($conexion, $identificador, 'Centro de acopio', 'registrado', 'PRODUCTOS');

while($fila = pg_fetch_assoc($qProductos)){
	$producto[]= array(idArea=>$fila['id_area'], idProducto=>$fila['id_producto'], nombreProducto=>$fila['nombre_producto'], idProvincia=>$fila['id_localizacion'], idUnidadMedida=>$fila['unidad_medida']);
}

$variedades = $cc->ListarVariedades($conexion);
$calidades = $cc->ListarCalidades($conexion);

while ($fila = pg_fetch_assoc($calidades)) {
	$calidadesProd[] = array(id_calidad_producto => $fila['id_calidad_producto'], nombre => $fila['nombre'], id_variedad_producto => $fila['id_variedad_producto']);
}

$unidadesNeto = $cc->listarUnidadesMedidaXTipo($conexion, 'Peso');
$unidadesBruto = $cc->listarUnidadesMedidaXTipo($conexion, 'Peso');

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<header>
    <h1>Certificado de calidad</h1>
</header>

<div id="estado"></div>

<form id='nuevaSolicitudCalidad' data-rutaAplicacion='certificadoCalidad' data-opcion='guardarSolicitudCalidad' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">

	<input type="hidden" id="opcion" name="opcion" />
	<input type="hidden" id="identificadorOperador" name="identificadorOperador" value="<?php echo $identificador;?>"/>
	<input type="hidden" id="razonSocial" name="razonSocial" value="<?php echo $exportador['razon_social'];?>"/>
	
	<div class="pestania">

    <fieldset>
        <legend>Datos del exportador</legend>

        <div data-linea="1">
            <label>Razón social</label> <?php echo $exportador['razon_social'];?>
        </div>

    </fieldset>

    <fieldset>
        <legend>Datos de importador</legend>

        <div data-linea="1">
            <label>Nombre</label>
            <input type="text" id="nombreImportador" name="nombreImportador" data-er="[A-Za-z]" />
        </div>

        <div data-linea="2">
            <label>Dirección</label>
            <input type="text" id="direccionImportador" name="direccionImportador" data-er="[A-Za-z]" />
        </div>
    </fieldset>

    <fieldset>
        <legend>Datos de exportación</legend>

        <div data-linea="1">
            <label>Fecha de embarque</label>
            <input type="text" id="fechaEmbarque" name="fechaEmbarque" />

        </div>

        <div data-linea="2">
            <label>Nombre y # de transporte</label>
            <input type="text" id="numeroTransporte" name="numeroTransporte" />
        </div>

        <div data-linea="2">
            <label>Medio de transporte</label>
            <select id="medioTransporte" name="medioTransporte">
                <option value="">Seleccione....</option>
                <option value="Puerto">Maritimo</option>
                <option value="Aéreo">Aéreo</option>
            </select>
        </div>

        <div data-linea="3">
            <label>País de embarque</label>
            <select id="paisEmbarque" name="paisEmbarque">
            <?php
           		echo '<option value="'.$paisOrigen['id_localizacion'].'" selected="selected">'.$paisOrigen['nombre'].'</option>';
            ?>
                
            </select>
            <input type="hidden" id="nombrePaisEmbarque" name="nombrePaisEmbarque" value="<?php echo $paisOrigen['nombre'];?>"/>
        </div>

        <div data-linea="3">
            <label>Puerto de embarque</label>
            <select id="puertoEmbarque" name="puertoEmbarque" disabled="disabled"></select>
            <input type="hidden" id="nombrePuertoEmbarque" name="nombrePuertoEmbarque" />
        </div>

        <div data-linea="4">
            <label>País destino</label>
            <select id="paisDestino" name="paisDestino">
	             <option value="" selected="selected">Seleccione...</option>
	            <?php 
	            foreach ($paisesExportacionXProducto as $paises){
	            	echo '<option value="'.$paises['idPais'].'">'.$paises['nombrePais'].'</option>';
	            }
            
            ?>
            </select>
            <input type="hidden" id="nombrePaisDestino" name="nombrePaisDestino" />
        </div>

        <div data-linea="5">
            <div id="dPuertoDestino"></div>           
        </div>

    </fieldset>

    <fieldset>
        <legend>Datos de inspección</legend>

        <div data-linea="1">
            <label>Provincia</label>
            <select id="provincia" name="provincia">
            	<option value="" selected="selected">Seleccione...</option>
            <?php 
	            while ($fila = pg_fetch_assoc($provincia)){
	            	echo '<option value="'.$fila['id_localizacion'].'">'.$fila['nombre'].'</option>';
	            }
            ?>
            </select>
        </div>

        <div data-linea="1">
            <label>Lugar</label>
            <select id="lugarInspeccion" name="lugarInspeccion"></select>
        </div>

        <div data-linea="2">
            <label>Fecha</label>
            <input type="text" id="fechaInspeccion" name="fechaInspeccion" />
        </div>
        
        <div data-linea="2">
            <label>Hora de inspección</label>
            <input id="hora" name="hora" type="text" placeholder="10:30" data-inputmask="'mask': '99:99'" />
        </div>
        
        

        <button type="button" onclick="agregarInspeccion()" class="mas">Agregar inspección</button>

        <table>
            <thead>
            <tr>
                <th colspan="2">Provincia</th>
                <th>Área de inspección</th>
                <th>Fecha y hora</th>
            <tr>
            </thead>
            <tbody id="inspecciones">
            </tbody>
        </table>

    </fieldset>
    </div>
    
    <div class="pestania">

    <fieldset>
        <legend>Información lotes</legend>

        <div data-linea="1">
            <label>Lugar de inspección</label>
            <select id="loteInspeccion" name="loteInspeccion">
            	<option value="" selected="selected">Seleccione...</option>
            </select>
        </div>
        
        <div data-linea="1">
            <label>N° lote</label>
             <input type="text" id="numeroLote" name="numeroLote" placeholder="Ej: 2500" />
        </div>

        <div data-linea="2">
            <label>Producto</label>
            <select id="producto" name="producto"></select>
        </div>

        <div data-linea="2">
            <label>Valor FOB</label>
            <input type="text" id="valorFob" name="valorFob" placeholder="Ej: 2500.45" data-er="^[0-9]+(\.[0-9]{1,2})?$" />
        </div>

        <div data-linea="3">
            <label>Peso neto </label>
            <input type="text" id="pesoNeto" name="pesoNeto" placeholder="Ej: 2500.45" data-er="^[0-9]+(\.[0-9]{1,2})?$" />
        </div>

        <div data-linea="3">
            <label>Unidad</label>
            <select id="unidadNeto" name="unidadNeto">
            <option value="" selected="selected">Unidad....</option>
                <?php
                while ($fila = pg_fetch_assoc($unidadesNeto)) {
                    echo '<option value="' . $fila['id_unidad_medida'] . '" data-codigo="'. $fila['codigo'].'">' . $fila['nombre'] . '</option>';
                }
                ?>
            </select>
        </div>

        <div data-linea="4">
            <label>Peso bruto </label>
            <input type="text" id="pesoBruto" name="pesoBruto" placeholder="Ej: 2500.45" data-er="^[0-9]+(\.[0-9]{1,2})?$" />
        </div>

        <div data-linea="4">
            <label>Unidad</label>
            <select id="unidadBruto" name="unidadBruto">
            <option value="" selected="selected">Unidad....</option>
                <?php
                while ($fila = pg_fetch_assoc($unidadesBruto)) {
                    echo '<option value="' . $fila['id_unidad_medida'] . '" data-codigo="'. $fila['codigo'].'">' . $fila['nombre'] . '</option>';
                }
                ?>
            </select>
        </div>

        <div data-linea="5">
            <label>Variedad</label>
            
            <select id="variedad" name="variedad">
            <option value="" selected="selected">Variedad....</option>
             <?php
                while ($fila = pg_fetch_assoc($variedades)) {
                    echo '<option value="' . $fila['id_variedad_producto'] . '" >' . $fila['nombre'] . '</option>';
                }
                ?>
            </select>
        </div>

        <div data-linea="5">
            <label>Calidad</label>
            <select id="calidad" name="calidad"></select>
        </div>

        <button type="button" onclick="agregarLote()" class="mas">Agregar lote</button>

        <table>
            <thead>
            <tr>
                <th  colspan="2"># lote</th>
                <th>Lote</th>
                <th>Producto</th>
                <th>Valor FOB</th>
                <th>Peso neto</th>
                <th>Peso bruto</th>
            <tr>
            </thead>
            <tbody id="lotes">
            </tbody>
        </table>
    </fieldset>


    <button type="submit" class="guardar">Guardar solicitud</button>
    </div>
</form>
</body>

<script type="text/javascript">

	var array_puertosOrigen= <?php echo json_encode($puertosOrigen); ?>;
	var array_areas= <?php echo json_encode($areas); ?>;
	var array_producto= <?php echo json_encode($producto); ?>;
	var array_calidades = <?php echo json_encode($calidadesProd); ?>;
	var array_paisesXProducto = <?php echo json_encode($paisesExportacionXProducto);?>;

    $(document).ready(function () {
        distribuirLineas();
        construirValidador();
        construirAnimacion($(".pestania"));	
        
       	$("#fechaEmbarque").datepicker({
		    changeMonth: true,
		    changeYear: true
		  });

       	$("#fechaInspeccion").datepicker({
		    changeMonth: true,
		    changeYear: true
		  });
        
    });


    $("#medioTransporte").change(function(){
    	spuertoOrigen ='0';
    	spuertoOrigen = '<option value="">Seleccione...</option>';
        for(var i=0;i<array_puertosOrigen.length;i++){
    	    if ($("#medioTransporte").val()==array_puertosOrigen[i]['tipoPuerto']){
    	    	spuertoOrigen += '<option value="'+array_puertosOrigen[i]['idPuerto']+'">'+array_puertosOrigen[i]['nombrePuerto']+'</option>';
    		    }
       		}
        $('#puertoEmbarque').html(spuertoOrigen);
        $('#puertoEmbarque').removeAttr("disabled");
     });

    $("#puertoEmbarque").change(function(){
			$('#nombrePuertoEmbarque').val($('#puertoEmbarque option:selected').text());
    });

    $("#paisDestino").change(function (event) {
        
    	$("#opcion").val('paisDestino');
        $("#nuevaSolicitudCalidad").attr('data-destino', 'dPuertoDestino');
        $("#nuevaSolicitudCalidad").attr('data-opcion', 'combosCalidad');

        $('#nombrePaisDestino').val($('#paisDestino option:selected').text());

        if($("#medioTransporte").val()==""){
        	$("#estado").html("Por favor seleccione el medio de transporte.").addClass('alerta');
        	$("#paisDestino").val('');
        }else{
        	abrir($("#nuevaSolicitudCalidad"), event, false); //Se ejecuta ajax, busqueda de sitios
         }

    });

    $("#provincia").change(function(){
    	sAreas ='0';
    	sAreas = '<option value="">Seleccione...</option>';
        for(var i=0;i<array_areas.length;i++){
    	    if ($("#provincia").val()==array_areas[i]['idProvincia']){
    	    	sAreas += '<option value="'+array_areas[i]['idArea']+'">'+array_areas[i]['nombreSitio']+' - '+array_areas[i]['nombreArea']+'</option>';
    		    }
       		}
        $('#lugarInspeccion').html(sAreas);
        $('#lugarInspeccion').removeAttr("disabled");
     });

    $("#producto").change(function(){

    	unidadMedida = $("#producto option:selected").attr("data-codigo");

    	$('select[name="unidadNeto"]').find('[data-codigo ="'+unidadMedida+'"]').attr("selected",true);	
    	$('select[name="unidadBruto"]').find('[data-codigo ="'+unidadMedida+'"]').attr("selected",true);	
		
	});

    function agregarInspeccion(){
        
    	$("#estado").html("").removeClass('alerta');
    	$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#provincia").val()==""){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}

		if($("#lugarInspeccion").val()==""){
			error = true;
			$("#lugarInspeccion").addClass("alertaCombo");
		}

		if(!$.trim($("#fechaInspeccion").val())){
			error = true;
			$("#fechaInspeccion").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor todos los campos son obligatorios.").addClass('alerta');
		}else{
			$("#estado").html("").removeClass('alerta');

			codigo = $("#lugarInspeccion").val()+$("#fechaInspeccion").val().replace(/[/]/g,'')+$("#hora").val().replace(/:/g,'');
			if($("#inspecciones #r_"+codigo).length==0){
				$("#inspecciones").append("<tr id='r_"+codigo+"'><td><button type='button' onclick='quitarInspeccion(\"#r_"+$("#provincia").val()+$("#lugarInspeccion").val()+"\")' class='menos'>Quitar</button></td><td>"+$("#provincia  option:selected").text()+"<input id='idProvincia' name='idProvincia[]' value='"+$("#provincia").val()+"' type='hidden'><input id='nombreProvincia' name='nombreProvincia[]' value='"+$("#provincia  option:selected").text()+"' type='hidden'></td><td>"+$("#lugarInspeccion  option:selected").text()+"<input id='idLugarInspeccion' name='idLugarInspeccion[]' value='"+$("#lugarInspeccion").val()+"' type='hidden'><input id='nombreLugarInspeccion' name='nombreLugarInspeccion[]' value='"+$("#lugarInspeccion  option:selected").text()+"' type='hidden'></td><td>"+$("#fechaInspeccion").val()+"<input id='fechaHoraInspeccion' name='fechaHoraInspeccion[]' value='"+$("#fechaInspeccion").val()+' '+$("#hora").val()+"' type='hidden'></td></tr>");
				$("#loteInspeccion").append("<option value="+$('#provincia').val()+$("#lugarInspeccion").val()+">"+$("#provincia option:selected").text()+" - "+$("#lugarInspeccion option:selected").text()+"</option>");
			}else{
				$("#estado").html("Por favor ya se ingresado la información seleccionada.").addClass('alerta');
			}
		}
	}

    function quitarInspeccion(fila){
        var codigo = fila.split('_');
		$("#inspecciones tr").eq($(fila).index()).remove();
		$("#loteInspeccion").find("option[value="+codigo[1]+"]").remove();	
	}

    $("#loteInspeccion").change(function(){
    	$("#estado").html("").removeClass('alerta');
    	sproducto ='0';
    	sproducto = '<option value="">Seleccione...</option>';
        for(var i=0;i<array_producto.length;i++){
    	    if ($("#loteInspeccion").val()==array_producto[i]['idProvincia']+array_producto[i]['idArea']){
        	    if($('#paisDestino').val()!=''){
        	    	for (var j=0; j<array_paisesXProducto.length; j++){
    					if(array_producto[i]['idProducto'] == array_paisesXProducto[j]['idProducto'] && $('#paisDestino').val() == array_paisesXProducto[j]['idPais']){
    						sproducto += '<option value="'+array_producto[i]['idProducto']+'" data-codigo="'+array_producto[i]['idUnidadMedida']+'">'+array_producto[i]['nombreProducto']+'</option>';
    					}
    				}
            	}else{
                	$("#loteInspeccion").val('');
    			 	$("#estado").html("Por favor seleccione el pais de destino.").addClass('alerta');
    			 	break;
    		 	}
        	}
       	}
        $('#producto').html(sproducto);
        $('#producto').removeAttr("disabled");
     });

   
    $("#variedad").change(function () {
        scalidad = '0';
        scalidad = '<option value="">Calidad...</option>';
        for (var i = 0; i < array_calidades.length; i++) {
            if ($("#variedad").val() == array_calidades[i]['id_variedad_producto']) {
                scalidad += '<option value="' + array_calidades [i]['id_calidad_producto'] + '">' + array_calidades[i]['nombre'] + '</option>';
            }
        }
        $('#calidad').html(scalidad);
        $('#calidad').removeAttr("disabled");
    });

	function agregarLote(){
		
		$("#estado").html("").removeClass('alerta');
    	$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#loteInspeccion").val()==""){
			error = true;
			$("#loteInspeccion").addClass("alertaCombo");
		}

		if(!$.trim($("#numeroLote").val())){
			error = true;
			$("#numeroLote").addClass("alertaCombo");
		}

		if($("#producto").val()==""){
			error = true;
			$("#producto").addClass("alertaCombo");
		}

		if(!$.trim($("#valorFob").val())){
			error = true;
			$("#valorFob").addClass("alertaCombo");
		}

		if(!$.trim($("#pesoNeto").val())){
			error = true;
			$("#pesoNeto").addClass("alertaCombo");
		}

		if($("#unidadNeto").val()==""){
			error = true;
			$("#unidadNeto").addClass("alertaCombo");
		}

		if(!$.trim($("#pesoBruto").val())){
			error = true;
			$("#pesoBruto").addClass("alertaCombo");
		}

		if($("#unidadBruto").val()==""){
			error = true;
			$("#unidadBruto").addClass("alertaCombo");
		}

		if($("#variedad").val()==""){
			error = true;
			$("#variedad").addClass("alertaCombo");
		}

		/*if($("#calidad").val()==""){
			error = true;
			$("#calidad").addClass("alertaCombo");
		}*/

		if (error){
			$("#estado").html("Por favor todos los campos son obligatorios.").addClass('alerta');
		}else{
			$("#estado").html("").removeClass('alerta');

			var codigo = $("#loteInspeccion").val()+$("#numeroLote").val()+$("#producto").val();

			if($("#lotes #r_"+codigo).length==0){
				$("#lotes").append("<tr id='r_"+codigo+"'><td><button type='button' onclick='quitarInspeccion(\"#r_"+codigo+"\")' class='menos'>Quitar</button></td><td>"+$("#numeroLote").val()+"<input id='iNumeroLote' name='iNumeroLote[]' value='"+$("#numeroLote").val()+"' type='hidden'></td><td>"+$("#loteInspeccion  option:selected").text()+"<input id='idLoteInspeccion' name='idLoteInspeccion[]' value='"+$("#loteInspeccion").val()+"' type='hidden'></td><td>"+$("#producto  option:selected").text()+"<input id='iProducto' name='iProducto[]' value='"+$("#producto").val()+"' type='hidden'><input id='iNombreProducto' name='iNombreProducto[]' value='"+$("#producto  option:selected").text()+"' type='hidden'></td><td>"+$("#valorFob").val()+"<input id='iValorFob' name='iValorFob[]' value='"+$("#valorFob").val()+"' type='hidden'></td><td>"+$("#pesoNeto").val()+' '+$("#unidadNeto  option:selected").text()+"<input id='iPesoNeto' name='iPesoNeto[]' value='"+$("#pesoNeto").val()+"' type='hidden'><input id='iUnidadNeto' name='iUnidadNeto[]' value='"+$("#unidadNeto  option:selected").text()+"' type='hidden'></td><td>"+$("#pesoBruto").val()+' '+$("#unidadBruto  option:selected").text()+"<input id='iPesoBruto' name='iPesoBruto[]' value='"+$("#pesoBruto").val()+"' type='hidden'><input id='iUnidadBruto' name='iUnidadBruto[]' value='"+$("#unidadBruto  option:selected").text()+"' type='hidden'><input id='iVariedad' name='iVariedad[]' value='"+$("#variedad").val()+"' type='hidden'><input id='iNombreVariedad' name='iNombreVariedad[]' value='"+$("#variedad  option:selected").text()+"' type='hidden'><input id='iCalidad' name='iCalidad[]' value='"+$("#calidad").val()+"' type='hidden'><input id='iNombreCalidad' name='iNombreCalidad[]' value='"+$("#calidad  option:selected").text()+"' type='hidden'></td></tr>");
			}else{
				$("#estado").html("Por favor ya se ingresado la información seleccionada.").addClass('alerta');
			}
		}
	}


	 $("#nuevaSolicitudCalidad").submit(function (event) {
		 event.preventDefault();
	     $("#nuevaSolicitudCalidad").attr('data-destino', 'detalleItem');
	     $("#nuevaSolicitudCalidad").attr('data-opcion', 'guardarSolicitudCalidad');

	     ejecutarJson(this); 

	    });
		
    
</script>
</html>

