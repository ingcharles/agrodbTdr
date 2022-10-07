<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastroProducto.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cp = new ControladorCatastroProducto();

$identificadorUsuario = $_SESSION['usuario'];
$filaTipoUsuario = pg_fetch_assoc($cp->obtenerTipoUsuario($conexion, $identificadorUsuario));

$banderaSolicitante = false;
$identificadorSolicitante = "";

switch ($filaTipoUsuario['codificacion_perfil']){
        
    case 'PFL_USUAR_EXT':
        
        $qOperacionesEmpresaUsuario = $cp->obtenerOperacionesEmpresaUsuario($conexion, $identificadorUsuario, "('digitadorVacunacion')", "('OPT')");
        $operacionesEmpresaUsuario = pg_fetch_assoc($qOperacionesEmpresaUsuario);
        
        $codigoTipoOperacion = $operacionesEmpresaUsuario['codigo_tipo_operacion'];
        
        if(stristr($codigoTipoOperacion, 'OPT') == true){
            //echo "<br/> Es empleado traspatio-> catastra libre cualquier categoria<br/>";
        }else{
            
            $qResultadoEmpresaOperador = $cp->consultarEmpresaPorOperacion($conexion, "('OPI', 'FER')" , $identificadorUsuario);
            $resultadoEmpresaOperador = pg_fetch_assoc($qResultadoEmpresaOperador);
            
            $identificadorEmpresa = $resultadoEmpresaOperador['identificador_empresa'];
            
            if(pg_num_rows($qResultadoEmpresaOperador) > 0){
                //echo "<br/> Es empleado -> catastra de la empresa solo lechones y lechonas<br/>";
                $banderaSolicitante = true;
                $identificadorSolicitante = $identificadorEmpresa;
            }else{
                
                $qOperacionesUsuario = $cp->obtenerOperacionesUsuario($conexion, $identificadorUsuario, "( 'OPI', 'PRO', 'COM')");
                
                if(pg_num_rows($qOperacionesUsuario) > 0){
                    //echo "<br/> Es operador-> catastra sus propios cerdos solo lechones y lechonas<br/>";
                    $banderaSolicitante = true;
                    $identificadorSolicitante = $identificadorUsuario;
                }
                
            }
            
        }
        
    break;
        
}

$qUnidadComercial = $cc->obtenerIdUnidadMedida($conexion, 'U');
$unidadComercial = pg_fetch_assoc($qUnidadComercial);

?>
<header>
	<h1>Nuevo Registro de Catastro </h1>
</header>
<div id="mensajeCargando"></div>
<form id='nuevoCatastroProducto' data-rutaAplicacion='catastroProducto' data-accionEnExito="ACTUALIZAR" >

	<?php 
		if($banderaSolicitante){
		  echo '<input type="hidden" name="controlUsuario" id="controlUsuario" value="usuario">';
		}		
	?>

	<input type="hidden" id="opcion" name="opcion" value="" readonly="readonly">
	<input type="hidden" id="identificadorOperador" name="identificadorOperador" value="" readonly="readonly">
	<input type="hidden" id="areaTematica" name="areaTematica" value="SA" readonly="readonly">
	<input type="hidden" id="unidadMedida" name="unidadMedida" value="<?php echo $unidadComercial['id_unidad_medida'] ?>" readonly="readonly">	
	<input type="hidden" id="identificadorResponsable" name="identificadorResponsable" value="<?php echo $identificadorUsuario;?>" readonly="readonly">
	<input type="hidden" id="codigoProductoTemp" name="codigoProductoTemp" value="">	
			
	<input type="hidden" id="diasInicioEtapa" name="diasInicioEtapa" value="" readonly="readonly">
	<input type="hidden" id="diasFinEtapa" name="diasFinEtapa" value="" readonly="readonly">	
	<input type="hidden" id="codigoEspecie" name="codigoEspecie" value="" readonly="readonly">
	<input type="hidden" id="idEspecie" name="idEspecie" value="" readonly="readonly">
	<input type="hidden" id="cantidadCupoSaldo" name="cantidadCupoSaldo" value="0" readonly="readonly">
	<input type="hidden" id="totalIdentificadores" name="totalIdentificadores" value="0" readonly="readonly">
	
	<input type="hidden" id="gCantidad" name="gCantidad" value="" readonly="readonly">
	<input type="hidden" id="gIdentificador" name="gIdentificador" value="" readonly="readonly">
	<input type="hidden" id="gRango" name="gRango" value="" readonly="readonly">

	<input type="hidden" id="array_detalle_catastro" name="array_detalle_catastro" value="" readonly="readonly" />

	<div id="resultadoReproduccion"></div>
	<div id="estado"></div>
			
	<fieldset>
		<legend>Búsqueda del Sitio</legend>
			<div data-linea="1">
				<label>Identificación Operador: </label> 
				<input type="text" id="identificadorSolicitante" name="identificadorSolicitante" placeholder="Ej: 9999999999"  maxlength="13" <?php if ($banderaSolicitante){ ?> value="<?php echo $identificadorSolicitante; ?>" readonly="readonly" <?php }?> />
			</div>					
			<div data-linea="1">
				<label>Nombre del Sitio: </label> 
				<input type="text" id="nombreSitioOrigen" name="nombreSitioOrigen" value="" placeholder="Ej: Hacienda San José"  maxlength="250" />
			</div>	
			<div data-linea="3" style="text-align: center">
				<button type="button" id="buscarSitioOrigen" name="buscarSitioOrigen" >Buscar sitio</button>
			</div>
	</fieldset>
		
	<fieldset>
		<legend>Detalle de Productos a Catastrar</legend>
		
			<div data-linea="1" id="resultadoSitios" >
				<label >Nombre del Sitio: </label>
				<select id="campoSitio" name="campoSitio">
					<option value="0">Seleccione...</option>
				</select>
			</div>

			<div  data-linea="2" id="resultadoOperaciones" >
				<label>Operación: </label>
				<select id="campoOperacion" name="campoOperacion">
					<option value="0">Seleccione...</option>
				</select>
			</div>
			
			<div data-linea="2" id="resultadoAreas">			
				<label>Nombre del Área: </label>
				<select id="campoArea" name="campoArea" >
					<option value="0">Seleccione...</option>
				</select>
			</div>
				
			<div  data-linea="3" id="resultadoProductos" >
				<label>Producto: </label>
				<select id="campoProducto" name="campoProducto">
					<option value="0">Seleccione...</option>
				</select>
			</div>	
			
			<div data-linea="3">
				<label>Fecha de Nacimiento: </label>  
				<input type="text" id="fechaNacimiento" name="fechaNacimiento"  data-er="^(?:(?:0?[1-9]|1\d|2[0-8])(\/|-)(?:0?[1-9]|1[0-2]))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(?:(?:31(\/|-)(?:0?[13578]|1[02]))|(?:(?:29|30)(\/|-)(?:0?[1,3-9]|1[0-2])))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(29(\/|-)0?2)(\/|-)(?:(?:0[48]00|[13579][26]00|[2468][048]00)|(?:\d\d)?(?:0[48]|[2468][048]|[13579][26]))$"   data-inputmask="'mask': '99/99/9999'" />
			</div>
			
			<div data-linea="4">
				<label>Cantidad: </label>
				<input type="text" id="cantidad" name="cantidad" placeholder="Ej: 10" maxlength="5" onKeyPress='soloNumeros()' />
			</div>
			
			<div data-linea="6">
				<hr/>	
			</div>
					
			<div data-linea="7" id="catastroLote">
				<label>Registrar por Lote: </label>
				<input id="lote" name="lote"  style=" vertical-align: middle; margin-right:28%; float: right; " type="checkbox" /> <!-- checked = "checked" -->
			</div>	
					
			<div data-linea="7" id="catastroLote1">
				<label>N° lote: </label>
				<input type="text" id="numeroLote" name="numeroLote" value="" placeholder="Ej: 2345"  maxlength="18" />	
			</div>	
					
			<div data-linea="8" id="catastroIdentificador">
				<label>Registrar por Identificador: </label> 
				<input id="identificadorProducto" name="identificadorProducto"  style=" margin-right:28%; float: right; "  type="checkbox" >
			</div>	
					
			<div data-linea="8" id="catastroIdentificador1">
				<label>Rango: </label> 
				<input id="rango" name="rango"  style=" vertical-align: middle;" disabled="disabled" type="checkbox" />
			</div>
			
			<div data-linea="9" id="hrInferior">	
				<hr/>
			</div>
			
			<div data-linea="10">
			<button type="button" id="agregarDetalleCatastro" name="agregarDetalleCatastro"  class="mas">Agregar</button>
			</div>

		<table>
			<thead>
				<tr>
					<th>N° Reg.</th>
					<th>Operación</th>
					<th>Área</th>
					<th>Producto</th>
					<th>Cant</th>
					<th>Unidad</th>
					<th>N° Lt</th>
					<th>Por Iden</th>
					<th>Por Rang</th>
					<th></th> 
				</tr>
			</thead>
			
			<tbody id="tablaDetalleCatastro">
			</tbody>
		</table>
	</fieldset>
	
	<fieldset  id="detalleIdentificadores" >
		<legend>Detalle de Identificadores</legend>
		<div data-linea="1">
            <label>Registros de Catastros: </label>
            <select id="comboDetalleCatastro" name="comboDetalleCatastro">
            	<option value="" selected="selected">Seleccione...</option>
            </select>
        </div>
		<div data-linea="1" >
			<label>No.Identificador: </label> 
			<input type="text" id="numeroIdentificador" name="numeroIdentificador" value=""  maxlength="11"  onKeyPress='soloNumeros()' data-er="^[0-9]+$" />
		</div>
	
		<div data-linea="2">
			<button type="button" id="agregarDetalleIdentificador" name="agregarDetalleIdentificador"  class="mas">Agregar</button>
		</div>
		
		<div data-linea="3">
			<table id="tabla">
			<thead>
				<tr>
					<th width="65%">Registros de Catastros</th>
					<th width="15%">N° Identificador</th>
					<th width="20%"></th>
				</tr>
			</thead>
				<tbody id="tablaDetalleIdentificador"></tbody>
			</table>
		</div>
	</fieldset>

	<button type="submit" id="btnGuardar"  name="btnGuardar" class="guardar" >Guardar Catastro </button>
</form>

<script type="text/javascript">
	
	$(document).ready(function(event){
		
        $(':checkbox[name=lote]').click(function(){  
         	return false;  
        });  

        $("#fechaNacimiento").datepicker({
              changeMonth: true,
              changeYear: true,
              maxDate: "0"
        });	
        
        $("#detalleIdentificadores").hide();
        
        ocultarTipoCatastro();
        
        construirValidador();
        distribuirLineas();
		
	});

	$("#buscarSitioOrigen").click(function(event){
		
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#identificadorSolicitante").val() == "" && $("#nombreSitioOrigen").val().length < 3 ){	
			error = true;		
			$("#nombreSitioOrigen").addClass("alertaCombo");
			$("#identificadorSolicitante").addClass("alertaCombo");
		 	$("#estado").html("Por favor ingrese al menos un campo para realizar la búsqueda.").addClass('alerta');
		}
		
		if (!error){
			$("#sitio").removeAttr('disabled'); 
			$("#estado").html("").removeClass('alerta');
			$('#nuevoCatastroProducto').attr('data-destino','resultadoSitios');
			$('#nuevoCatastroProducto').attr('data-opcion','accionesCatastro');
		    $('#opcion').val('listaSitios');		
			abrir($("#nuevoCatastroProducto"),event,false); 
		}
		
	 });

    function ocultarTipoCatastro(){    
    	$("#catastroLote").hide();	
    	$("#catastroLote1").hide();	
    	$("#catastroIdentificador").hide();	
    	$("#catastroIdentificador1").hide();
    	$("#hrInferior").hide();	    	
    }

    function mostrarTipoCatastro(categoria){
    
    	switch(categoria){
    
    		case "lote":    
    			$("#lote").attr("checked", true); 
            	$("#catastroLote").show();	
            	$("#catastroLote1").show();	
            	$("#catastroIdentificador").hide();	
            	$("#catastroIdentificador1").hide();
            	$("#hrInferior").show();	
            break;
    
    		case "identificador":    
            	$("#catastroLote").hide();	
            	$("#catastroLote1").hide();	
            	$("#catastroIdentificador").show();	
            	$("#catastroIdentificador1").show();	
            	$("#hrInferior").show();    
            break;
    
    	}
    
    }

	$("#identificadorProducto").change(function(event){ 
		if($("#identificadorProducto").is(":checked") == true){
			$("#rango").attr("disabled", false); 
		}else{
			$("#rango").attr("disabled", true);
			$("#rango").attr("checked",false);
		}
	});

    $("#agregarDetalleCatastro").click(function(event){
    
		var operacionesOperadorV = $("#operacionesOperador").val();
    	 	
     	$(".alertaCombo").removeClass("alertaCombo");
		error = false;
        
        var sumaUno = 0;
        var sumaDos = 0;
     	  
        $('#tablaDetalleCatastro tr').each(function(){
            
         	var codigo = $(this).find('input[id="hCodigo"]').val(); //alert(codigo);
         	var cantidad = $(this).find('input[id="hCantidad"]').val();//alert(cantidad);
         	var ident = $(this).find('input[id="hIdentificador"]').val();//alert(ident);
        	var rango = $(this).find('input[id="hRango"]').val();//alert(rango);
        
            if ((ident == 'checked' || rango == 'checked') && $('#tablaDetalleIdentificador >tr').length == 0){
                error = true;	
                $("#estado").html('Por favor agrege los identificadores de acuerdo a la cantidad de productos ingresada. En N° Reg ' + codigo).addClass("alerta");
        	}else{
                if(ident == 'checked' && rango == 'checked'){
                    var contadorIR = 0;
                    $('#tablaDetalleIdentificador tr').each(function(){
                        if(codigo == $(this).find('input[id="hCodigoDetalle"]').val() && ident == 'checked' && rango == 'checked'){
							contadorIR++;
                        }
                    });
                    if(contadorIR == 0){
                        error = true;	
                        $("#estado").html('Por favor cuando selecciona el campo por rango debe ingresar el identificador del inicio del rango. En N° Reg ' + codigo).addClass("alerta");
                    } 
                }
        		
        		if(ident == 'checked' && rango == ''){
        			var contadorI = 0;
        			$('#tablaDetalleIdentificador tr').each(function(){
                        if(codigo == $(this).find('input[id="hCodigoDetalle"]').val() && ident == 'checked' && rango == ''){
							contadorI++;
                        }
        			});
        			if(contadorI!=cantidad){
        				error = true;	
        		    	$("#estado").html('Por favor cuando selecciona el campo por identificador la cantidad de identificadores agregados debe ser igual a la cantidad de productos ingresada. En N° Reg ' + codigo).addClass("alerta");
        			}
        		}	
        	}
        
        	if(($(this).find('input[id="hCodigoProducto"]').val() == 'PORHON') || ($(this).find('input[id="hCodigoProducto"]').val() == 'PORONA')){
                if($(this).find('input[id="hSitio"]').val() == $('#sitio').val()){
					sumaUno = sumaUno + parseInt($(this).find('input[id="hCantidad"]').val());
                }
        	}
        		
        });
    			 	
     	if($("#fechaNacimiento").val() != "" && $("#diasInicioEtapa ").val() != "" && $("#diasFinEtapa ").val() != ""){	
    		var dia = $("#diasInicioEtapa").val();
    		var dia_aproximado = dia * -1;
    		var fechaInicio = calcularFecha(dia_aproximado);
    		
    		var dias = $("#diasFinEtapa").val();
    		var dia_aproximados = dias * -1;
    		var fechaFin = calcularFecha(dia_aproximados);
    			
    		var partsI = fechaInicio.split("/");
    		var fechaIncioCompleta = partsI[2] + '' + partsI[1] + '' + partsI[0];
    		
    		var partsF = fechaFin.split("/");
    		var fechaFinCompleta = partsF[2] + '' + partsF[1] + '' + partsF[0];
    		
    		var partsN = $("#fechaNacimiento").val().split("/");
    		var fechaNacimientoCompleta = partsN[2] + '' + partsN[1] + '' + partsN[0];
    
    		if(!esCampoValido("#fechaNacimiento") || fechaNacimientoCompleta > fechaIncioCompleta || fechaNacimientoCompleta < fechaFinCompleta){
    			error = true;
    			$("#fechaNacimiento").addClass("alertaCombo");
    			$("#estado").html('La fecha de nacimiento no concuerda con la edad del producto.').addClass("alerta");
    		}
    	}
    
    	if (operacionesOperadorV.length > 0) {   	 	
    	 	if(operacionesOperadorV.indexOf('OPI') > -1){
    
        	 	if ($("#lote").is(":checked") == true ){
        			if($("#numeroLote").val() == 0 || $.trim($("#numeroLote").val()) == '' ){
        			   error = true;
        		       $("#numeroLote").addClass("alertaCombo");
        		       $("#estado").html('Por favor ingrese el número de lote.').addClass("alerta");
        			}
        		}
        		
    	 	}    
    	}
    
    	if ($("#cantidad").val() <=  0 || $.trim($("#cantidad").val()) == ''){
    		error = true;
    		$("#cantidad").addClass("alertaCombo");
    	  	$("#estado").html('Por favor ingrese la cantidad de productos a registrar.').addClass("alerta");
    	}    
    	
    	if ($("#codigoProductoTemp").val() == 'PORHON' || $("#codigoProductoTemp").val() == 'PORONA' ){
    		$("#cantidadCupoSaldo").val(parseInt($("#cantidadCupo").val()) - sumaUno);
    		if(parseInt($("#cantidadCupoSaldo").val()) < $("#cantidad").val()){
    			error = true;
    			$("#cantidad").addClass("alertaCombo");
    		  	$("#estado").html('Su cupo disponible para registrar el producto seleccionado es ' + $("#cantidadCupoSaldo").val()).addClass("alerta");
    		}
    	}
     	
     	if($("#campoProducto").val() ==  0 || $("#producto").val() ==  0){
    	   error = true;
           $("#campoProducto").addClass("alertaCombo");
           $("#producto").addClass("alertaCombo");
           $("#estado").html('Por favor seleccione el nombre del producto.').addClass("alerta");
    	}
    	   
      	if($("#campoArea").val() == 0 || $("#area").val() ==  0){
    	   error = true;
           $("#campoArea").addClass("alertaCombo");
           $("#area").addClass("alertaCombo");
           $("#estado").html('Por favor seleccione el nombre del área.').addClass("alerta");
    	}
    
      	if($("#campoOperacion").val() == 0 || $("#operacion").val() == 0){
    		error = true;
    	    $("#campoOperacion").addClass("alertaCombo");
    	    $("#operacion").addClass("alertaCombo");
    	    $("#estado").html('Por favor seleccione la operación.').addClass("alerta");
    	}    
    	
      	if($("#sitio").val() == 0 || $("#campoSitio").val() == 0){	
    		error = true;		
    		$("#sitio").addClass("alertaCombo");
    		$("#campoSitio").addClass("alertaCombo");
    		$("#estado").html('Por favor seleccione el nombre del sitio.').addClass("alerta");
    	}

      	if (!error){	 
    		var rango = $("#rango").is(":checked") == true ? "checked" : "";	
     	  	var xIdentificador = $("#identificadorProducto").is(":checked") == true ? "checked" : "";	
    	 	var numeroLoteProducto = $("#lote").is(":checked") == true ? $("#numeroLote").val() : "";
    	 	var lote = $("#lote").is(":checked") == true ? "checked" : ""; 
        	var arrayCodigo = new Array();
        	$('#tablaDetalleCatastro tr').each(function (event) {
        		arrayCodigo = parseInt($(this).find('input[id="hCodigo"]').val());
    	    });
    
        	var codigo = Math.max(arrayCodigo) + 1;

    		$("#tablaDetalleCatastro").append("<tr id='r_" + codigo 
    	    +"'><td><input type='hidden' id='hCodigo' name='hCodigo[]' value='" + codigo + "'>" + codigo + "</td>" 
    	    + "<td><input type='hidden' id='hOperacion' name='hOperacion[]' value='" + $("#operacion").val() + "'>" + $("#operacion option:selected").text()
    	    +"</td><td><input type='hidden' id='hIdArea' name='hIdArea[]' value='" + $("#area option:selected").val() + "'>" + $("#area option:selected").text()
    		+"</td><td><input type='hidden' id='hProducto' name='hProducto[]' value='" + $("#producto option:selected").val() + "'>" + $("#producto option:selected").text()	   
    	    +"</td><td><input type='hidden' id='hCantidad' name='hCantidad[]' value='" + $("#cantidad").val() + "'>" + $("#cantidad").val()
    	    +"</td><td><input type='hidden' id='hUnidadComercial' name='hUnidadComercial[]' value='" + $("#unidadMedida").val() + "'>Unidad"
    	    +"</td><td><input type='hidden' id='hLote' name='hLote[]' value=" + lote + " > <input id='hNumeroLote' name='hNumeroLote[]' value='" + numeroLoteProducto + "' type='hidden' />" + numeroLoteProducto
    	    +"</td><td><input type='checkbox' " + xIdentificador + " readonly='readonly' onclick='javascript: return false;' /> <input id='hIdentificador' name='hIdentificador[]' type='hidden' value='" + xIdentificador
    	    +"'></td><td><input type='checkbox' " + rango + " readonly='readonly' onclick='javascript: return false;' /><input id='hRango' name='hRango[]' type='hidden' value='" + rango
    	    +"'><input type='hidden' id='hCodigoProducto' name='hCodigoProducto[]' value='" + $("#producto option:selected").attr('data-codigo-producto')
        	+"'><input type='hidden' id='hFechaNacimiento' name='hFechaNacimiento[]' value='" + $("#fechaNacimiento").val()
    	    +"'><input type='hidden' id='hCodigoEspecie' name='hCodigoEspecie[]' value='" + $("#codigoEspecie").val()
    	    +"'><input type='hidden' id='hDiasInicioEtapa' name='hDiasInicioEtapa[]' value='" + $("#diasInicioEtapa").val()
    	    +"'><input type='hidden' id='hDiasFinEtapa' name='hDiasFinEtapa[]' value='" + $("#diasFinEtapa").val()
    	    +"'><input type='hidden' id='hAreaTematica' name='hAreaTematica[]' value='" + $("#areaTematica").val()
    	    +"'><input type='hidden' id='hSitio' name='hSitio[]' value='" + $("#sitio").val()
    	    +"'></td><td align='center' class='borrar' ><button type='button' onclick='quitarDetalleCatastro(\"#r_" + codigo + "\")' class='icono'></button></td></tr>");
    	    
            if(xIdentificador == "checked")
    			$("#comboDetalleCatastro").append("<option value=" + codigo + ">Reg " + codigo + " " + $("#operacion option:selected").text() + "(" + $("#area option:selected").text()+ ") - " + $("#producto option:selected").text() + " </option>");
    
    		if($("#codigoProductoTemp").val() == 'PORHON' || $("#codigoProductoTemp").val() == 'PORONA' ){
    	    	sumaDos = parseInt($("#cantidadCupo").val()) - sumaUno-parseInt($("#cantidad").val());
    		 	$("#cantidadCupoSaldo").val(sumaDos);
    		}
   
			limpiarCampos();
			mostrarOcultarDetalleIdentificadores();
    	} 
    });

	function calcularFecha(days){ 
		fecha = new Date(); 			
		tiempo = fecha.getTime(); 
		milisegundos = parseInt(days * 24 * 60 * 60 * 1000); 
		total = fecha.setTime(tiempo + milisegundos); 
		day = ("00" + fecha.getDate()).slice (-2); 
		month = ("00" + (fecha.getMonth()+1)).slice (-2); 
		year = fecha.getFullYear();
		fecNacimiento = day+"/"+month+"/"+year;
		return fecNacimiento; 
	}

	function mostrarOcultarDetalleIdentificadores(){
		var contadorDC = 0;
    	$('#tablaDetalleCatastro tr').each(function (event) {
			if($(this).find('input[id="hIdentificador"]').val() == 'checked'){
				contadorDC++;
			}
		});
		 
    	if(contadorDC > 0){	
			$("#detalleIdentificadores").show();
    	}else{
			$("#detalleIdentificadores").hide();
    	}
	}

    $("#comboDetalleCatastro").change(function(event){
        if($("#comboDetalleCatastro").val() != 0){
        	var contardorDI = 0;
          	$('#tablaDetalleIdentificador tr').each(function (event) {	  	  
        		if($(this).find('input[id="hCodigoDetalle"]').val() == $("#comboDetalleCatastro").val()){
         			 contardorDI++;
        		}
         	});
        
          	$('#tablaDetalleCatastro tr').each(function (event) {	  	  
        		if($("#comboDetalleCatastro").val() == $(this).find('input[id="hCodigo"]').val()){
        			$("#gCantidad").val($(this).find('input[id="hCantidad"]').val());
        			$("#gIdentificador").val($(this).find('input[id="hIdentificador"]').val());
        			$("#gRango").val($(this).find('input[id="hRango"]').val());
        			$("#codigoEspecie").val($(this).find('input[id="hCodigoEspecie"]').val());
        		}
         	});
         	
        	$("#totalIdentificadores").val(contardorDI);	
        }	 
    });

	$("#agregarDetalleIdentificador").click(function(event){
		
		$(".alertaCombo").removeClass("alertaCombo");
		var  error = false;
		   
		if ($("#numeroIdentificador").val() == '' || $("#numeroIdentificador").val() == 0){
		   error = true;
	       $("#numeroIdentificador").addClass("alertaCombo");
	       $("#estado").html("Por favor ingrese el número de identificador del producto.").addClass('alerta');
	   	}
	   
	   	if ( $("#comboDetalleCatastro").val() == 0){
			error = true;
		   $("#estado").html("Por favor seleccione el detalle de productos a catastrar").addClass('alerta');
		   $("#comboDetalleCatastro").addClass("alertaCombo");
		}

	   	if (!esCampoValido("#numeroIdentificador")){
			error = true;
		   $("#estado").html("Por favor ingrese solo números").addClass('alerta');
		   $("#numeroIdentificador").addClass("alertaCombo");
		}

		
		
	   	if (!error){
			$("#btnGuardar").attr('disabled',false);
            $("#estado").html("");	
            
            if($("#gIdentificador").val() == 'checked'){
               if($("#gRango").val() == 'checked' && $("#totalIdentificadores").val() >= 1){
               		$("#estado").html("Si selecciona el campo rango solo permite ingresar el inicio del rango.").addClass('alerta');	
            	}else{
            		if(parseInt($("#totalIdentificadores").val()) < parseInt($("#gCantidad").val()) ){
            			var codigo = $("#comboDetalleCatastro").val();
            			var codigoDetalle = $("#comboDetalleCatastro").val() + '_' + $("#codigoEspecie").val() + $("#numeroIdentificador").val();
            			var codigoValidar = $("#codigoEspecie").val() + $("#numeroIdentificador").val();
            			
            			
            			if($("#tablaDetalleIdentificador #" + codigoValidar.replace(/ /g,'')).length == 0){
            				$("#tablaDetalleIdentificador").append("<tr id='r_"+codigoDetalle.replace(/ /g,'')
            				+"'><td><input type='hidden' id='hCodigoDetalle' name='hCodigoDetalle[]' value='"+codigo
            				+"'><input type='hidden' id='"+codigoValidar+"' name='"+codigoValidar+"' value='"+codigoValidar
            				+"'>"+$("#comboDetalleCatastro option:selected").text()+"</td><td align=center><input type='hidden' id='hIdentificadorProducto' name='hIdentificadorProducto[]' value='"+parseInt($("#numeroIdentificador").val())+"'>"+parseInt($("#numeroIdentificador").val())
            				+"</td><td align='center' class='borrar' ><button type='button' onclick='quitarDetalleIdentificador(\"#r_"+codigoDetalle.replace(/ /g,'')+"\")' class='icono'></button></td></tr>");
            				
            				if(validarDetalleIdentificadores()){
            					$("#totalIdentificadores").val(parseInt($("#totalIdentificadores").val()) + 1);
                				$("#numeroIdentificador").val('');
                			}else{
                				$("#tablaDetalleIdentificador tr").eq($("#r_"+codigoDetalle.replace(/ /g,'')).index()).remove();
                			}
            						
            				
            			}else{
            			    $("#estado").html("Por favor verifique datos, no puede ingresar en la misma especie el mismo identificador más de una vez.").addClass('alerta');
            			}		   
            		}else{
            			$("#estado").html("Los identificadores agregados no puede ser mayor a la cantidad de productos ingresada").addClass('alerta');  
            		}			   
               }
            }else{
               $("#estado").html("Si desea ingresar identificadores de producto seleccione el campo registrar por identificador").addClass('alerta');
               $("#identificadorProducto").addClass("alertaCombo");
            }
	   } 			
	});

	function soloNumeros() {
		if ((event.keyCode < 48) || (event.keyCode > 57)){		 
			event.returnValue = false;
		}
	}
	
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
	function limpiarCampos(){
		$("#estado").html("").removeClass('alerta');
		$("#operacion").val(0);
		$("#area").val(0);
    	$("#producto").val(0);
    	$("#fechaNacimiento").val("");
    	$("#cantidad").val("");
    	$("#numeroLote").val("");
		$("#identificadorProducto").attr("checked", false);
		$("#rango").attr("checked", false);
		$("#rango").attr("disabled", true);
	 }

    $("#nuevoCatastroProducto").submit(function(event){
    	event.preventDefault();	
    	$(".alertaCombo").removeClass("alertaCombo");
    	var error = false;
    
    	$('#tablaDetalleCatastro tr').each(function(){
         	var codigo=$(this).find('input[id="hCodigo"]').val();
         	var cantidad=$(this).find('input[id="hCantidad"]').val();
         	var ident=$(this).find('input[id="hIdentificador"]').val();
    		var rango=$(this).find('input[id="hRango"]').val();
    	
             if ((ident=='checked' || rango=='checked') && $('#tablaDetalleIdentificador >tr').length==0  ){
            	 error = true;	
        		 $("#estado").html('Por favor agrege los identificadores de acuerdo a la cantidad de productos ingresada. En N° Reg '+codigo).addClass("alerta");
     		}else{
     			if(ident == 'checked' && rango == 'checked'){
    				var contadorIR = 0;
    				$('#tablaDetalleIdentificador tr').each(function(){
    	            	if(codigo == $(this).find('input[id="hCodigoDetalle"]').val() && ident == 'checked' && rango=='checked')
    	                contadorIR++;
    				 });
    				 if(contadorIR == 0){
    					error = true;	
    		    		$("#estado").html('Por favor cuando selecciona el campo por rango debe ingresar el identificador del inicio del rango. En N° Reg '+codigo).addClass("alerta");
    		 		 } 
        		}
        		
    			if(ident == 'checked' && rango == ''){
    				var contadorI = 0;
    				$('#tablaDetalleIdentificador tr').each(function(){
    		        	if(codigo == $(this).find('input[id="hCodigoDetalle"]').val() && ident == 'checked' && rango=='')
    		           		contadorI++;
    				});
    				if(contadorI!=cantidad){
    					error = true;	
    			    	$("#estado").html('Por favor cuando selecciona el campo por identificador la cantidad de identificadores agregados debe ser igual a la cantidad de productos ingresada. En N° Reg '+codigo).addClass("alerta");
    				}
     			}	
     		}					
    	});	
    
    	if ($('#tablaDetalleCatastro >tr').length == 0){
    		error = true;	
    		$("#estado").html('Por favor ingrese al menos un detalle catastro.').addClass("alerta");
    	} 
    
    	if (!error){
    		cargarDatosDetalle();
    		$("#nuevoCatastroProducto").attr('data-destino', 'detalleItem'); 
    		$('#nuevoCatastroProducto').attr('data-opcion','guardarNuevoCatastro');   
    		ejecutarJson("#nuevoCatastroProducto");
    		$("#btnGuardar").attr('disabled','disabled');
    	}
    });
    
    $("#cantidad").change(function(event){
    	if($("#cantidad").val()!=0)
    	$("#cantidad").removeClass("alertaCombo");
    }); 
        

	function cargarDatosDetalle(){

		var arrayDetalleCatastro = [];    	
		var arrayDetalleIdentificadores = [];
		
		$('#tablaDetalleCatastro tr').each(function (rows) {			
			var arrayIdentificadores = [];
    		var hCodigo = $(this).find('td').find('input[name="hCodigo[]"]').val();		
    		var hOperacion = $(this).find('td').find('input[name="hOperacion[]"]').val();	
    		var hIdArea = $(this).find('td').find('input[name="hIdArea[]"]').val();	
    		var hProducto = $(this).find('td').find('input[name="hProducto[]"]').val();	
    		var hCantidad = $(this).find('td').find('input[name="hCantidad[]"]').val();	
    		var hUnidadComercial = $(this).find('td').find('input[name="hUnidadComercial[]"]').val();	
    		var hLote = $(this).find('td').find('input[name="hLote[]"]').val();
    		var hNumeroLote = $(this).find('td').find('input[name="hNumeroLote[]"]').val();
    		var hIdentificador = $(this).find('td').find('input[name="hIdentificador[]"]').val();
    		var hRango = $(this).find('td').find('input[name="hRango[]"]').val();
    		var hCodigoProducto = $(this).find('td').find('input[name="hCodigoProducto[]"]').val();
    		var hFechaNacimiento = $(this).find('td').find('input[name="hFechaNacimiento[]"]').val();
    		var hCodigoEspecie = $(this).find('td').find('input[name="hCodigoEspecie[]"]').val();
    		var hDiasInicioEtapa = $(this).find('td').find('input[name="hDiasInicioEtapa[]"]').val();
    		var hDiasFinEtapa = $(this).find('td').find('input[name="hDiasFinEtapa[]"]').val();
    		var hAreaTematica = $(this).find('td').find('input[name="hAreaTematica[]"]').val();	
    		var hSitio = $(this).find('td').find('input[name="hSitio[]"]').val();	


			if(hIdentificador == "checked" && hRango == "" ){

				$('#tablaDetalleIdentificador tr').each(function (rows) {			

		    		var hCodigoDetalle = $(this).find('td').find('input[name="hCodigoDetalle[]"]').val();		
		    		var hIdentificadorProducto = $(this).find('td').find('input[name="hIdentificadorProducto[]"]').val();
					
		 			if (hCodigo == hCodigoDetalle && $('#tablaDetalleIdentificador tr').length){
		 		
		 				hIdentificadores = 'EC' + String(hIdentificadorProducto).padStart(9,'0');
    		 			arrayIdentificadores.push(hIdentificadores);
    		 			hIdentificadorProducto = parseInt(hIdentificadorProducto) + 1;		
					}
			
				});
				
			}else{
				
				$('#tablaDetalleIdentificador tr').each(function (rows) {			

		    		var hCodigoDetalle = $(this).find('td').find('input[name="hCodigoDetalle[]"]').val();		
		    		var hIdentificadorProducto = $(this).find('td').find('input[name="hIdentificadorProducto[]"]').val();
					
		 			if (hCodigo == hCodigoDetalle && $('#tablaDetalleIdentificador tr').length){
			 					
        				for(var i = 0; i < hCantidad; i++){
            				
        					hIdentificadores = 'EC' + String(hIdentificadorProducto).padStart(9,'0');
        					arrayIdentificadores.push(hIdentificadores);
        					hIdentificadorProducto = parseInt(hIdentificadorProducto) + 1
        					
    					} 
    					
					}
					
				});
			}
			
			datosDetalleCatastro = {"hCodigo" : hCodigo, "hOperacion" : hOperacion, "hIdArea" : hIdArea, "hProducto" : hProducto,
	 								"hCantidad" : hCantidad, "hUnidadComercial" : hUnidadComercial , "hLote" : hLote, "hNumeroLote" : hNumeroLote,
	 								"hIdentificador" : hIdentificador , "hRango" : hRango, "hCodigoProducto" : hCodigoProducto, 
	 								"hFechaNacimiento" : hFechaNacimiento, "hCodigoEspecie" : hCodigoEspecie, "hDiasInicioEtapa" : hDiasInicioEtapa, 
	 								"hDiasFinEtapa" : hDiasFinEtapa, "hAreaTematica" : hAreaTematica, "hSitio" : hSitio, "arrayIdentificadores" : arrayIdentificadores};
			agregarElementos(arrayDetalleCatastro, datosDetalleCatastro, $("#array_detalle_catastro"));
			
		});
	}

	//función permite validar los identificadores aretes codigos EC si si no están utilizados se los agrega
	function validarExistenciaArete(){
		var bool = false;
		var data = $("#array_detalle_catastro").serialize();
	    $.ajax({
	        type: "POST",
	        data: {array_detalle_catastro:$("#array_detalle_catastro").val(), codigo_seleccionado : $("#comboDetalleCatastro").val()},
	        url: "aplicaciones/catastroProducto/validarExistenciaAretes.php",
	        dataType: "json",
	        async: false,
	        beforeSend: function(){
		    	$("#mensajeCargando").html("<div id='cargando'>Cargando...</div>").fadeIn();
		    	$("#estado").removeClass();
		    },
	        success: function(msg) {  

	        	if(msg.estado == "exito"){ 
		        	$("#btnGuardar").attr("disabled",false);
		        	mostrarMensaje("","EXITO");
		        	bool = true;
	    		}else{
	    			$("#btnGuardar").attr("disabled",true);
	    			mostrarMensaje(msg.mensaje,"FALLO");
	    			bool = false;
	        	}         	                       
	        },
	 	   error: function(jqXHR, textStatus, errorThrown){
			   $("#cargando").delay("slow").fadeOut();
		    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
		    	$("#btnGuardar").attr("disabled",true);
		    },
	        complete: function(){
	        	$("#cargando").delay("slow").fadeOut();
	        }
	    });	

	    return trueOrFalse(bool);  
	}
	
	function trueOrFalse(bool){
	    return bool;
	}

	function validarDetalleIdentificadores(){

		var arrayDetalleIdentificadores = [];    	
		var datosDetalleIdentificadores = {};

		$('#tablaDetalleCatastro tr').each(function (rows) {
				
			var datosDetalleCatastro = {};
			var arrayIdentificadores = [];
    		var hCodigo = $(this).find('td').find('input[name="hCodigo[]"]').val();		
    		var hCantidad = $(this).find('td').find('input[name="hCantidad[]"]').val();	
    		var hIdentificador = $(this).find('td').find('input[name="hIdentificador[]"]').val();
    		var hRango = $(this).find('td').find('input[name="hRango[]"]').val(); 		

			if(hIdentificador == "checked" && hRango == "" ){

				$('#tablaDetalleIdentificador tr').each(function (rows) {			

		    		var hCodigoDetalle = $(this).find('td').find('input[name="hCodigoDetalle[]"]').val();		
		    		var hIdentificadorProducto = $(this).find('td').find('input[name="hIdentificadorProducto[]"]').val();
					
		 			if (hCodigo == hCodigoDetalle && $('#tablaDetalleIdentificador tr').length){
		 				hIdentificadores = 'EC' + String(hIdentificadorProducto).padStart(9,'0');
    		 			arrayIdentificadores.push(hIdentificadores);
    		 			hIdentificadorProducto = parseInt(hIdentificadorProducto) + 1;
		 			}
				
				});
		
				datosDetalleIdentificadores[hCodigo] = { arrayIdentificadores: arrayIdentificadores};
     			
    			
			}else if(hIdentificador == "checked" && hRango == "checked" ){
				
				$('#tablaDetalleIdentificador tr').each(function (rows) {			

		    		var hCodigoDetalle = $(this).find('td').find('input[name="hCodigoDetalle[]"]').val();		
		    		var hIdentificadorProducto = $(this).find('td').find('input[name="hIdentificadorProducto[]"]').val();
					
		 			if (hCodigo == hCodigoDetalle && $('#tablaDetalleIdentificador tr').length)		
      	    			for(var i = 0; i < hCantidad; i++){
    						hIdentificadores = 'EC' + String(hIdentificadorProducto).padStart(9,'0');
        					arrayIdentificadores.push(hIdentificadores);
        					hIdentificadorProducto = parseInt(hIdentificadorProducto) + 1
    					} 
				});

				datosDetalleIdentificadores[hCodigo] = { arrayIdentificadores: arrayIdentificadores};
			
			}
	
		});

		arrayDetalleIdentificadores.push(datosDetalleIdentificadores);
		$("#array_detalle_catastro").val(JSON.stringify(arrayDetalleIdentificadores));
		return validarExistenciaArete();
			
	}
	
	//Funcion que agrega elementos a un array//
    //Recibe array, datos del array y el objeto donde se almacena//
    function agregarElementos(array, datos, objeto){
    	array.push(datos);
    	objeto.val(JSON.stringify(array));
	}
		
	function quitarDetalleCatastro(fila){
		codigo = fila.split('_');
		var codigoDC = 0;
		
		$('#tablaDetalleIdentificador tr').each(function (event) {
			if(codigo[1] == $(this).find('input[id="hCodigoDetalle"]').val())
			codigoDC++;
		 });

		if(codigoDC > 0){			
			$("#estado").html('Por favor verifique los datos, no puede quitar porque existen detalle de identificadores agregados. En N° Reg '+codigo[1]).addClass('alerta');
		}else{ 

			if($("#sitio").val()==0){
				$("#estado").html('Por favor seleccione el sitio al que va a quitar el detalle de catastro');
			}else{
				if($('#tablaDetalleCatastro tr').find('input[id="hCodigoProducto"]').eq($(fila).index()).val()=='PORHON' || $("#tablaDetalleCatastro tr").find('input[id="hCodigoProducto"]').eq($(fila).index()).val()=='PORONA' ){
					if($('#tablaDetalleCatastro tr').find('input[id="hSitio"]').eq($(fila).index()).val()==$('#sitio').val()){
						var cantidadUnitaria=$("#tablaDetalleCatastro tr").find('input[id="hCantidad"]').eq($(fila).index()).val();
						$('#cantidadCupoSaldo').val(parseInt($('#cantidadCupoSaldo').val())+parseInt(cantidadUnitaria));
					}
				}
				$("#estado").html("").removeClass('alerta');
				$("#tablaDetalleCatastro tr").eq($(fila).index()).remove();
				$("#comboDetalleCatastro").find("option[value="+codigo[1]+"]").remove();
			}		
		}
		mostrarOcultarDetalleIdentificadores();	
	}

	function quitarDetalleIdentificador(fila){
		$(".alertaCombo").removeClass("alertaCombo");
		$("#estado").html("").removeClass('alerta'); 
		var codigo = fila.split('_');
		if(codigo[1]==$("#comboDetalleCatastro").val() && $("#comboDetalleCatastro").val()!=0 ){
			$("#tablaDetalleIdentificador tr").eq($(fila).index()).remove();
			var numA =  parseInt($("#totalIdentificadores").val()) - 1;
			$("#totalIdentificadores").val(numA);
			$("#btnGuardar").attr('disabled',false);
		}else{
			$("#comboDetalleCatastro").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione el detalle de productos a catastrar correspondiente para quitar el identificador. En N° Reg '+codigo[1]).addClass('alerta'); 
		}
	}


	 
</script>