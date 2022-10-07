<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorLotes.php';


$conexion = new Conexion();
$cac = new ControladorLotes();
$cc = new ControladorCatalogos();
$id= $_POST['id'];
$usuario=$_SESSION['usuario'];

?>

<header>
	<h1>Modificar Plantilla</h1>
</header>

<div id="estado"></div>


<fieldset>
	<legend>Configuración Impresión:</legend>
	<div data-linea="1">
		<table id="tbItems" style="width:100%">
			<thead>
				<tr>
					<th style="width: 10%;">#</th>
					<th style="width: 69%;">Configuración</th>
					<th style="text-align:center;width: 60px;">Acciones</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$res=$cac->obtenerPlantillasXidProducto($conexion, $id, '1,2');
			while($fila=pg_fetch_assoc($res)){
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
				
				echo '<tr id="R'.$fila['id_plantilla'].'"><td>'.$con.'</td><td>'. $fila['nombre'] .'</td>'.
						'<td style="display:flex;justify-content: center;">' .
						
						'<form class="abrir" >' .					
						'<input type="hidden" name="opcion" value="plantilla">'.
						'<input type="hidden" name="idProducto" value="'.$_POST['id'].'">'.
						'<button class="icono" onclick="editar('.$fila['id_plantilla'].');return false;"></button>' .
						'</form>' .	
										
						'<form class="'.$estado.'" data-rutaAplicacion="administracionEtiquetas" data-opcion="actualizarEstadoPlantilla">'.
						'<input type="hidden" id="estadoRequisito" name="estadoRequisito">' .
						'<input type="hidden" name="idServicioProducto" value="' . $fila['id_plantilla']. '" >' .
						'<input type="hidden" name="idCatalogo" value="'.$id.'" >'.
						//'<input type="hidden" name="estadoCatalogo" value="'.$filas['estado'].'" >'.
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

<div id="resultadoCargarPlantilla">
<form id="frmPlantilla" data-rutaAplicacion="administracionEtiquetas" data-accionEnExito="ACTUALIZAR">	
	<input type="hidden" id="opcion" name="opcion"/>
	<input type="hidden" id="id" name="id" value="<?php echo $id?>"/>
	
</form>

</div>

<script type="text/javascript">
var con=0;

$("document").ready(function(event){
	distribuirLineas();
	acciones("NULL","#tbItems");	
});

function editar(id){
	
	var data ="opcion=plantilla"+'&idPlantilla='+id+'&idProducto='+<?php echo $id;?>;
	$.ajax({
		type : "POST",
		url: "aplicaciones/administracionEtiquetas/obtenerPlantilla.php",
		data : data,
		dataType : "json",
		async:   true,
		beforeSend : function() {	
			$("#resultadoCargarPlantilla").html("<div id='cargando'>Cargando...</div>").fadeIn();
		},
		success : function(msg) {	
			$(msg.mensaje).each(function(i){
				$("#resultadoCargarPlantilla").html(this.contenido);			

				$("#txtNombreImpresion").html(this.nombre);
				cargarValorDefecto("cbPlantilla",this.plantilla);
				cargarValorDefecto("cbTamanio",this.hoja);				
				cargarValorDefecto("cbEtiquetaPorHoja",this.cantidad);				
				cargarValorDefecto("cbOrientacion",this.orientacion);
				$("#txtNombreImpresion").val(this.nombre);
				distribuirLineas();

				$("#cbPlantilla").change(function(event){
					switch($("#cbPlantilla").val()){
					case'P1':
						$("#resultadoPlantilla").html('<div style="text-align:center;width:100%;padding-bottom: 10px;"><img alt="plantilla1" src="aplicaciones/administracionEtiquetas/img/plantilla1.png" width="250" height="150"></div>');
					break;
					case'P2':
						$("#resultadoPlantilla").html('<div style="text-align:center;width:100%;padding-bottom: 10px;"><img alt="plantilla1" src="aplicaciones/administracionEtiquetas/img/plantilla2.png" width="250" height="150"></div>');
					break;
					}
					$("#resultadoPlantilla").html();
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
							} else{
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


				$("#btnEditar").click(function(event){
					event.preventDefault();
					$("#cbPlantilla").attr("disabled",false);
					$("#cbTamanio").attr("disabled",false);
					//$("#cbEtiquetaPorHoja").attr("disabled",false);
					$("#cbOrientacion").attr("disabled",false);
					$("#txtNombreImpresion").attr("disabled",false);
					$("#btnGuardar").attr("disabled",false);

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


				$("#cbTamanio").change(function(event){
					
					cargarValorDefecto("cbEtiquetaPorHoja","1");
					var valor = $("#cbTamanio").val();	
					var val=$("#cbOrientacion").val();
					if(valor=="etiqueta"){
						$("#cbEtiquetaPorHoja").attr("disabled",true);
						$("#cbOrientacion").attr("disabled",true);
						cargarValorDefecto("cbOrientacion","h");						
					} else{
						$("#cbEtiquetaPorHoja").attr("disabled",false);
						$("#cbOrientacion").attr("disabled",false);
						if(val=="h"){
							$("#cbEtiquetaPorHoja").append('<option value="6">6</option>');
						}
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
				});


				$("#frmPlantilla").submit(function(event){
					event.preventDefault();
					$("#cbOrientacion").attr("disabled",false);
					$("#cbEtiquetaPorHoja").attr("disabled",false);
					$("#frmPlantilla").attr("data-opcion","actualizarPlantilla");
					$("#frmPlantilla").attr("data-destino","detalleItem");
					 ejecutarJson($(this));

					$("#cbOrientacion").attr("disabled",true);
					$("#cbEtiquetaPorHoja").attr("disabled",true);
				});
				
				
			});			
		},
		error: function(jqXHR, textStatus, errorThrown){
	    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
	    	//$("#estado").html(data).addClass("alerta");  
	    }/*,
	    after{
		    
	    }*/
	});
}

$("#cbArea").change(function(event){
	event.preventDefault();	
	$('#frmPlantilla').attr('data-opcion','comboPlantilla');
	$('#frmPlantilla').attr('data-destino','resultadoTipoProducto');
	$('#opcion').val('tipoProducto');
	abrir($("#frmPlantilla"),event,false);	
});


$("#frmPlantilla").submit(function(event){
	event.preventDefault();	
	$("#frmPlantilla").attr('data-destino', 'detalleItem');
    $("#frmPlantilla").attr('data-opcion', 'guardarNuevaPlantilla');
    //$("#frmCatalogo").attr('data-accionEnExito', 'ACTUALIZAR');    
    ejecutarJson($(this));
    
});

	
	
</script>
