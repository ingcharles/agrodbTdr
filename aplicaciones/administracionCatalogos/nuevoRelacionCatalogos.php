<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$conexion = new Conexion();
$cac = new ControladorAdministrarCatalogos();
$idCatalogo= $_POST['id'];
$usuario=$_SESSION['usuario'];

$qCatalogoPadre= $cac->listarCatalogos($conexion);
$qCatalogoHijo= $cac->listarCatalogos($conexion);

?>




<header>
	<h1>Nuevo Registro Catálogo</h1>
</header>

<div id="estado"></div>


<form id="frmCatalogo" data-rutaAplicacion="administracionCatalogos" data-accionEnExito="ACTUALIZAR">	
	<input type="hidden" id="opcion" />
	<input type="hidden" id="txtIdCatalogo" name="txtIdCatalogo" value="<?php echo $idCatalogo?>"/>
	<fieldset>
			<legend>Catálogos a asociar</legend>
			<div data-linea="1">
				<label for="cbCatalogoPadre">Catálogo padre: </label>
				<select id="cbCatalogoPadre" name="cbCatalogoPadre">
    				<?php 
        				while ($catalogoPadre = pg_fetch_assoc($qCatalogoPadre)){
        				    echo '<option value="' . $catalogoPadre['id_catalogo_negocios'] . '">' . $catalogoPadre['nombre'] . '</option>';
        				}
    				?>					
				</select>				
			</div>
			<div data-linea="1">
				<label for="cbCatalogoHijo">Catálogo hijo: </label>
				<select id="cbCatalogoHijo" name="cbCatalogoHijo">
					<option>Seleccione....</option>    								
				</select>
			</div>
	</fieldset>
	
	<fieldset>
		<legend>Nuevo Ítem:</legend>
		<div data-linea="1">
				<label for="txtItem" >Ítem:</label>
				<input type="text" id="txtItem" name="txtItem" >
		</div>
		<div data-linea="2">
				<label for="txtDescripcion" >Descripción:</label>
				<input type="text" id="txtDescripcion" name="txtDescripcion" >
		</div>
		<div style="text-align:center;width:100%">
			<button id="btnAgregarItem" class="mas" onclick="agregar();return false;"> Agregar</button>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Ítems:</legend>
		<div data-linea="1">
				<table id="tbItems" style="width:100%">
					<thead>
						<tr>
							<th style="width: 15%;">#</th>
							<th style="width: 60%;">Ítem</th>
							<th>Acciones</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
		</div>		
	</fieldset>
	
	<button type="submit" id="btnGuardar" class="guardar" disabled="disabled">Guardar</button>	
	
</form>

<script type="text/javascript">
var con=0;

$("document").ready(function(event){
	distribuirLineas();
		
});


$("#btnAgregarItem").click(function(event){
	event.preventDefault();
	val = $("#txtNombreCatalogo").val();
	$("#txtNombreCatalogo").attr("disabled",false);	
});


/*
$("#submit").click(function(event){
	event.preventDefault();
	$("#frmCatalogo").attr('data-destino','abrirCatalogo');
	$("#frmCatalogo").attr('data-opcion', 'actualizarCatalogo');
	$("#opcion").val('registros');
	ejecutarJson($("#frmCatalogo"));
	$("#txtNombreCatalogo").attr("disabled",true);
});
*/

function agregar(){	
	
	$(".alertaCombo").removeClass("alertaCombo");
	
	var error = false;
	var dulpicado = false;
		
	var nombre=$("#txtItem").val();
    var descripcion=$("#txtDescripcion").val();

	if($.trim($("#txtItem").val())==""){
		error=true;
		$("#txtItem").addClass("alertaCombo");		
	}
	
	if(!error){
		//var nFilas = $("#tablaProductos tr").length;
	    //var nColumnas = $("#tablaProductos tr:last td").length;	
	    
		$('#tbItems tbody tr').each(function (rows){
			var rd=$(this).find('td').eq(1).find('input[id="dtxtItem"]').val();
			if(rd.toUpperCase() == nombre.toUpperCase()){
				dulpicado=true;	
				$("#estado").html("Ya agregó un ítem con el mismo nombre.").addClass('alerta');	
				$("#txtItem").addClass("alertaCombo");
		    	return false;
	    	}
		});	   
	    	con=con+1;
	    	if(!dulpicado){
				var cadena= '<tr><td>'
							+con+'</td>'+
							'<td><input type="hidden" id="dtxtItem" name="dtxtItem[]" value="'+nombre+'" style="width:30px">'+
							'<input type="hidden" name="dtxtDescripcion[]" value="'+descripcion+'" style="width:30px">'+nombre+ ' </td>'+														
							'<td class="borrar"><button class="icono" onClick="delFilaActual(this);return false;"></td></tr>';						
				$("#tbItems tbody").append(cadena);	
				$("#btnGuardar").removeAttr("disabled");
				$("#txtItem").val("");
				$("#txtDescripcion").val("");
				$("#txtItem").focus();
				$("#estado").html("");				
	    	}
	} else{
		$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
	}
}

function delFilaActual(r){
	var i = r.parentNode.parentNode.rowIndex;		    	    
    var table = document.getElementById('tbItems');
    table.deleteRow(i);

    var filas = table.rows.length;
    
	if(filas == 1){
 		$("#estado").html(""); 		
 		$("#btnGuardar").attr("disabled",true); 	
	}
	enumerar(r);
}

function enumerar(e){
	
	var i = e.parentNode.parentNode.rowIndex;		    	    
    var tabla = document.getElementById('tbItems');
    con=0;   
    $("#tbItems tbody tr").each(function(row){        
    	con+=1;    	
    	$(this).find('td').eq(0).html(con);
    	console.log(con);    	
    });
}


$("#frmCatalogo").submit(function(event){
	event.preventDefault();

	$(".alertaCombo").removeClass("alertaCombo");
	$("#estado").html("");

	var error=false;

	if($.trim($("#txtNombreCatalogo").val())==""){
		error=true;
		$("#txtNombreCatalogo").addClass("alertaCombo");		
	}

	if(!error){
    	$("#frmCatalogo").attr('data-destino', 'detalleItem');
        $("#frmCatalogo").attr('data-opcion', 'guardarNuevoCatalogo');
        //$("#frmCatalogo").attr('data-accionEnExito', 'ACTUALIZAR');
        ejecutarJson($(this));
	} else{
		$("#estado").html("Por favor revise los datos obligatorios.").addClass("alerta");
	}
});

	
	
</script>
