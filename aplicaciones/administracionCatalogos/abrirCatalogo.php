<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$conexion = new Conexion();
$cac = new ControladorAdministrarCatalogos();
$idCatalogo= $_POST['id'];

$etapaProceso = array(array('id'=>$idCatalogo,'pagina'=>'abrirCatalogo'));

?>

<header>
	<h1>Modificar Registro Catálogo</h1>
</header>

<form id="frmCatalogo" data-rutaAplicacion="administracionCatalogos">	
	<input type="hidden" id="opcion" />
	<input type="hidden" id="txtIdCatalogo" name="txtIdCatalogo" value="<?php echo $idCatalogo?>"/>
	<fieldset>
			<legend>Modificar Catálogo:</legend>
			<div data-linea="1">
				<label for="txtNombreCatalogo">Nombre Catálogo</label>
				<?php
				$res=$cac->abrirCatalogo($conexion, $idCatalogo);
				$filas = pg_fetch_assoc($res);
				
				?>
				<input type="text" id="txtNombreCatalogo" name="txtNombreCatalogo" value="<?php echo $filas['nombre'] ?>" disabled="disabled">
					
			</div>
			
			<div data-linea="2">
				<label for="txtCodigo" >Código:</label>
				<input type="text" id="txtCodigo" name="txtCodigo" value="<?php echo $filas['codigo'] ?>" maxlength="30" disabled="disabled">
				
				<div style="text-align:center;width:100%">
					<button id="btnModificarNombre" class="editar">Modificar</button>
					<button id="btnActualizarNombre" class="guardar" disabled="disabled">Actualizar</button>
				</div>	
			</div>
			
	</fieldset>
	
	<fieldset>
		<legend>Nuevo Ítem:</legend>
		<input type="hidden" name="estadoCatalogo" value="<?php echo $filas['estado'] ?>" disabled="disabled">
		<div data-linea="1">
				<label for="txtItem" >Ítem:</label>
				<input type="text" id="txtItem" name="txtItem">
		</div>
		<div data-linea="2">
				<label for="txtDescripcion" >Descripción:</label>
				<input type="text" id="txtDescripcion" name="txtDescripcion">
		</div>
		<div style="text-align:center;width:100%">
			<button class="mas" type="submit">Agregar</button>
		</div>
	</fieldset>	
	
</form>
	
	<fieldset>
		<legend>Ítems:</legend>
		<div data-linea="1">
				<table id="tbItems" style="width:100%">
					<thead>
						<tr>
							<th style="width: 15%;">#</th>
							<th style="width: 60%;">Ítem</th>
							<th>Editar</th>
							<th>Habilitación</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$res=$cac->listarItems($conexion, $idCatalogo, '1,2');
							$con=0;
							while ($fila = pg_fetch_assoc($res)){
								$con+=1;
								switch ($fila['estado']){
									case '1':
										$estado="activo";
									break;
									
									case '2':
										$estado="inactivo";
									break;	
									
									default:
										$estado="inactivo";
									break;
								}

								echo '<tr id="R'.$fila['id_item'].'"><td>'.$con.'</td><td>'. $fila['nombre'] .'</td>'.
										'<td style="text-align:center;width:100%">' .		
    									'<form class="abrir" data-rutaAplicacion="administracionCatalogos" data-opcion="abrirItem" data-destino="detalleItem" data-accionEnExito="NADA" >' .
    									'<input type="hidden" name="idCatalogoPadre" value="'.$idCatalogo.'" >'.
    									'<input type="hidden" name="idItemPadre" value="'.$fila['id_item'].'" >'.
    									'<input type="hidden" name="idExclusionCatalogo" value="'.$idCatalogo.'" >'.
    									'<input type="hidden" name="nivel" value="1" >'.
    									'<input type="hidden" name="etapaProceso" value='.serialize($etapaProceso).' >'.
    									'<input type="hidden" name="tipoProceso" value="carga" >'.
    									'<button class="icono" type="submit" ></button>' .
    									'</form>' .
    									'</td>
										<td>'.
										'<form class="'.$estado.'" data-rutaAplicacion="administracionCatalogos" data-opcion="actualizarEstadoItem">'.
										'<input type="hidden" id="estadoRequisito" name="estadoRequisito">' .
										'<input type="hidden" name="idServicioProducto" value="' . $fila['id_item']. '" >' .
										'<input type="hidden" name="idCatalogoPadre" value="'.$idCatalogo.'" >'.
										'<input type="hidden" name="estadoCatalogo" value="'.$filas['estado'].'" >'.
										'<button type="submit" class="icono"></button>'.
										'</form>'.
										'</td>
   										</tr>'.
										'</td>' 
								;
							}
						?>
					</tbody>
				</table>
		</div>		
	</fieldset>
	


<script type="text/javascript">

$("document").ready(function(event){
	distribuirLineas();
	construirValidador();
	acciones("NULL","#tbItems");	
});


$("#btnModificarNombre").click(function(event){
	event.preventDefault();
	val = $("#txtNombreCatalogo").val();
	$("#txtNombreCatalogo").attr("disabled",false);
	$("#txtNombreCatalogo").focus().val("").val(val);
	$("#txtCodigo").attr("disabled",false);
	$("#btnActualizarNombre").attr("disabled",false);
	if($.trim($("#txtCodigo").val())!=''){
		$("#txtCodigo").attr("disabled",true);
	}	
});


$("#btnActualizarNombre").click(function(event){
	event.preventDefault();
	$("#frmCatalogo").attr('data-destino','abrirCatalogo');
	$("#frmCatalogo").attr('data-opcion', 'actualizarCatalogo');	
	$("#opcion").val('registros');
	$("#txtCodigo").attr("disabled",false);
	ejecutarJson($("#frmCatalogo"),new exitoCatalogo(),new errorCatalogo());
});


function exitoCatalogo(){
	this.ejecutar = function(msg){
		mostrarMensaje(msg.mensaje,"EXITO");	
		$("#txtNombreCatalogo").attr("disabled",true);	
		$("#txtCodigo").attr("disabled",true);
		$("#btnActualizarNombre").attr("disabled",true);
	};
}

function errorCatalogo(){
	this.ejecutar = function(msg){
		mostrarMensaje(msg.mensaje,"FALLO");
	};
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

function enumerar(){	
    $("#tbItems tbody tr").each(function(row){        
    	con+=1;    	
    	$(this).find('td').eq(0).html(con);
    	console.log(con);    	
    });
}

$("#frmCatalogo").submit(function(event){
	event.preventDefault();	
	$(".alertaCombo").removeClass("alertaCombo");
	error=false;

	if($.trim($("#txtItem").val())==""){
		error=true;
		$("#txtItem").addClass("alertaCombo");		
	}

	if(!error){

    	var data = $("#frmCatalogo").serialize();
        $.ajax({
            type: "POST",
            data: data,        
            url: "aplicaciones/administracionCatalogos/agregarItem.php",
            dataType: "json",
            success: function(msg) {           
            	if(msg.estado=="exito"){ 
        			$("#tbItems tbody").append(msg.mensaje);        			
        			mostrarMensaje("Nuevo registro agregado","EXITO");  
            		enumerar();
            	} else{
            		mostrarMensaje(msg.mensaje,"FALLO");
            	}
                	                       
            },
            error: function(msg){
            	mostrarMensaje(msg.mensaje,"FALLO");       
            }
        });	
	} else{		
		mostrarMensaje("Por favor revise los campos obligatorios","FALLO");
	}
});
	
	
</script>
