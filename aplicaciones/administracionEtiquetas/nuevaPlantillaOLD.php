<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';


$conexion = new Conexion();
$id= $_POST['id'];
$usuario=$_SESSION['usuario'];

?>

<header>
	<h1>Nuevo Seleccionar Plantilla</h1>
</header>

<div id="estado"></div>


<form id="frmPlantilla" data-rutaAplicacion="administracionEtiquetas" data-accionEnExito="ACTUALIZAR">	
	<input type="hidden" id="opcion" name="opcion"/>
	<input type="hidden" id="txtIdCatalogo" name="txtIdCatalogo" value="<?php echo $id?>"/>
	
	<fieldset>
		<legend>Información del Producto</legend>
		<div data-linea="1">
				<label for="cbArea">Área:</label>
				<select id="cbArea" name="cbArea">
						<option value="">Seleccione....</option>
						<option value="SA">Sanidad Animal</option>
						<option value="SV">Sanidad Vegetal</option>
						<option value="LT">Laboratorios</option>
						<option value="AI">Inocuidad de los alimentos</option>
				</select>
		</div>
		<div data-linea="2" id="resultadoTipoProducto">
		</div>
		<div data-linea="3" id="resultadoSubTipoProducto">
		</div>
		<div data-linea="4" id="resultadoProducto">
		</div>
		<div data-linea="5">
			<label for="cbPlantilla">Plantilla:</label>
				<select id="cbPlantilla" name="cbPlantilla" disabled>
					<option value="P1">Plantilla 1</option>
					<option value="P2">Plantilla 2</option>	
			</select>
		</div>
		<div data-linea="6" id="resultadoPlantilla">	
			<div style="text-align:center;width:100%">						
			</div>
		</div>
		<div data-linea="7" >
		<label for="cbTamanio">Tamaño de papel:</label>
				<select id="cbTamanio" name="cbTamanio" disabled>					
					<option value="A4">A4</option>
					<option value="etiqueta">Etiqueta 5cmx10cm</option>				
			</select>
		</div>
		<div data-linea="8" >
    		<label for="cbOrientacion">Orientación de la hoja:</label>
    		<select id="cbOrientacion" name="cbOrientacion" disabled>					
    			<option value="v">Vertical</option>
    			<option value="h">Horizontal</option>				
    		</select>
		</div>
		<div data-linea="9" >
    		<label for="cbEtiquetaPorHoja">Etiquetas por hoja:</label>
    		<select id="cbEtiquetaPorHoja" name="cbEtiquetaPorHoja" disabled>					
    			<option value="1">1</option>
    			<option value="2">2</option>
    			<option value="3">3</option>
    			<option value="4">4</option>
    			<option value="5" selected>5</option>				
    		</select>		
		</div>
		<div data-linea="10" >
			<label for="txtNombreImpresion">Configuración impresión:</label>
			<input type="text" id="txtNombreImpresion" name="txtNombreImpresion" disabled>
		</div>
		<div style="text-align:center;width:100%">
			<button id="btnPrevizualizar" disabled="disabled">Previzualizar</button>
		</div>
		<div style="text-align:center;width:100%">
		<div id="vizualizador" >		
		<!-- img alt="plantilla1" src="aplicaciones/administracionEtiquetas/img/plantilla1.png" width="250" height="150"-->		
		</div>
		</div>
		
	</fieldset>
	
	<button type="submit" id="btnGuardar" class="guardar" disabled="disabled">Guardar</button>	
	
</form>

<script type="text/javascript">
var con=0;

$("document").ready(function(event){
	distribuirLineas();
		
});

$("#cbArea").change(function(event){
	event.preventDefault();	
	$('#frmPlantilla').attr('data-opcion','comboPlantilla');
	$('#frmPlantilla').attr('data-destino','resultadoTipoProducto');
	$('#opcion').val('tipoProducto');
	abrir($("#frmPlantilla"),event,false);	
});

$("#cbPlantilla").change(function(event){

	switch($("#cbPlantilla").val()){
	case'P1':
		$("#resultadoPlantilla").html('<div style="text-align:center;width:100%;padding-bottom: 10px;"><img alt="plantilla1" src="aplicaciones/administracionEtiquetas/img/plantilla1.png" width="250" height="150"></div>');
	break;
	case'P2':
		$("#resultadoPlantilla").html('<div style="text-align:center;width:100%;padding-bottom: 10px;"><img alt="plantilla1" src="aplicaciones/administracionEtiquetas/img/plantilla2.png" width="250" height="150"></div>');
	break;
	default:
		$("#resultadoPlantilla").html('');
	break;
	}

});

$("#cbOrientacion").change(function(event){
	var val=$("#cbOrientacion").val();

	if(val=="v"){
		$("#cbEtiquetaPorHoja option[value='6']").remove();	
		cargarValorDefecto("cbEtiquetaPorHoja","5");
	} else{
		$("#cbEtiquetaPorHoja").append('<option value="6">6</option>');
		cargarValorDefecto("cbEtiquetaPorHoja","6");
	}
	
	//$("#cbOrientacion option[value='X']").remove();
	
});


$("#btnPrevizualizar").click(function(event){
	event.preventDefault();
	var val= $("#cbEtiquetaPorHoja").val();
	var contenido="";
	switch($("#cbTamanio").val()){
		case'etiqueta':			
				contenido+='<div style="text-align:center;width:100%;padding-bottom: 10px;"><img alt="plantilla1" src="aplicaciones/administracionEtiquetas/img/plantilla2.png" width="250" height="150"></div>';			
				$("#vizualizador").html(contenido);	
				$("#hoja").remove();
		break;
		case'A4':
			if($("#cbOrientacion").val()=="v"){
			$("#vizualizador").html('<div id="hoja"></div>');
			for(i=0;i<=val-1;i++){
				contenido+='<div style="text-align:left;width:100%;padding-bottom: 10px;"><img alt="plantilla1" src="aplicaciones/administracionEtiquetas/img/plantilla2.png" width="250" height="150"></div>';
			}
			$("#hoja").html(contenido);	
			}
			else{
				$("#vizualizador").html('<div id="hojaH"></div>');	
				var i=0;	
					
				for(i=0;i<=val-1;i++){			
					contenido+='<div style="float:left; padding-bottom: 10px; margin-right:20px"><img alt="plantilla1" src="aplicaciones/administracionEtiquetas/img/plantilla2.png" width="250" height="150"></div>';					
				}				
				$("#hojaH").html(contenido);					
			}
		break;
	}
	
});


$("#cbTamanio").change(function(event){

	cargarValorDefecto("cbEtiquetaPorHoja","1");
	var valor = $("#cbTamanio").val();	
	if(valor=="etiqueta"){
		$("#cbEtiquetaPorHoja").attr("disabled",true);
		$("#cbOrientacion").attr("disabled",true);
		cargarValorDefecto("cbOrientacion","h");
	} else{
		$("#cbEtiquetaPorHoja").attr("disabled",false);
		$("#cbOrientacion").attr("disabled",false);
	}
});


$("#btnPrevizualizar").click(function(event){
	$("#btnGuardar").attr("disabled",false);
});
	

$("#frmPlantilla").submit(function(event){
	event.preventDefault();	
	var error = false;

	$(".alertaCombo").removeClass("alertaCombo");

	if($("#cbArea").val()==""){
		$("#cbArea").addClass("alertaCombo");
		error=true;
	}

	if($("#cbTipoProducto").val()==""){
		$("#cbTipoProducto").addClass("alertaCombo");
		error=true;
	}

	if($("#cbSubTipoProducto").val()==""){
		$("#cbSubTipoProducto").addClass("alertaCombo");
		error=true;
	}

	if($("#cbProducto").val()==""){
		$("#cbProducto").addClass("alertaCombo");
		error=true;
	}

	if($("#cbArea").val()==""){
		$("#cbArea").addClass("alertaCombo");
		error=true;
	}

	if($("#txtNombreImpresion").val()==""){
		$("#txtNombreImpresion").addClass("alertaCombo");
		error=true;
	}

	if(!error){
	
    	$("#frmPlantilla").attr('data-destino', 'detalleItem');
        $("#frmPlantilla").attr('data-opcion', 'guardarNuevaPlantilla');
        //$("#frmCatalogo").attr('data-accionEnExito', 'ACTUALIZAR');    
        $("#cbOrientacion").attr("disabled",false);
        $("#cbEtiquetaPorHoja").attr("disabled",false);
        ejecutarJson($(this));
        $("#cbOrientacion").attr("disabled",true);
        $("#cbEtiquetaPorHoja").attr("disabled",true);
	} else{
		$("#estado").html("Por favor, revise los campos obligatorios").addClass("alerta");
	}
    
});

	
	
</script>
