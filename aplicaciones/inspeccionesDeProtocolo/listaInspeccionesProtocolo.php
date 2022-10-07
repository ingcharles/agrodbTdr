<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
$identificador = $_SESSION['usuario'];

?>

<header>
	<h1>Consultar operador</h1>
    <nav>
        <form id="filtrar" data-rutaAplicacion="inspeccionesDeProtocolo" data-opcion="listaInspeccionesProtocoloFiltrado" data-destino="listadoFiltrado">
            <table class="filtro" style='width: 400px;'>
                <tbody>
                <tr>
                    <th colspan="3">Buscar usuario:</th>
                </tr>
                <tr>
                    <td style="width: 50%;">Identificador operador:</td>
                    <td style="width: 50%;"><input id="bIdentificador" type="text" name="bIdentificador" maxlength="13" value=""></td>
                </tr>
                <tr>
                    <td style="width: 50%;">Razón social:</td>
                    <td style="width: 50%;"><input id="bRazonSocial" type="text" name="bRazonSocial" maxlength="128" value="<?php echo $_POST['apellido']; ?>"></td>
                </tr>
                <tr>
                    <td style="width: 50%;">Código sitio:</td>
                    <td id="resultadofiltroIdentificador" style="width: 50%;">
                    	<select id="bCodigoSitio" name="bCodigoSitio" style="width: 91%;">
							<option value="">Seleccione...</option>
						</select>
					</td>

                </tr>
                <tr>
                    <td colspan="5">
                        <button id='buscar'>Buscar</button>
                    </td>
                </tr>
                <tr>
                	<td colspan="2" id="mensajeError"></td>
                </tr>
                </tbody>
            </table>
        </form>
    </nav>
    
    <nav>
		<?php 
		$conexion = new Conexion();
		$ca = new ControladorAplicaciones();


		$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $identificador);
		while($fila = pg_fetch_assoc($res)){

			echo '<a href="#"
			id="' . $fila['estilo'] . '"
				data-destino="detalleItem"
				data-opcion="' . $fila['pagina'] . '"
				data-rutaAplicacion="' . $fila['ruta'] . '"
				>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
		}
		?>
	</nav>
	
</header>

<div id="listadoFiltrado"></div>

<script type="text/javascript"> 

    $(document).ready(function(){
    	$("#listadoItems").removeClass("comunes");
    	$("#listadoItems").addClass("lista");
    	//$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');
    });

    $("#bIdentificador").change(function(event){
    	$('#filtrar').attr('data-destino','resultadofiltroIdentificador');
    	$('#filtrar').attr('data-opcion','accionesInspeccionesProtocolo');
        $('#opcion').val('filtroIdentificador');	
        event.stopImmediatePropagation();		
    	abrir($("#filtrar"),event,false);
    	$('#filtrar').attr('data-destino','listadoFiltrado');
    	$('#filtrar').attr('data-opcion','listaInspeccionesProtocoloFiltrado');  	
    });

	$("#filtrar").submit(function(event){
		event.preventDefault();	
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#bIdentificador").val() == "" && $("#bRazonSocial").val() == "" && $("#bCodigoSitio").val() == ""){	
			 error = true;		
		 	$("#mensajeError").html("Por favor ingrese al menos un parámetro de busqueda.").addClass('alerta');
		}

    if($.trim($("#bRazonSocial").val()) != "" && $.trim($("#bIdentificador").val()) == ""){
    		
		if($("#bRazonSocial").val().length < 3 || $("#bRazonSocial").val() == ""){	
			 error = true;		
		 	$("#mensajeError").html("Por favor ingrese al menos 3 letras.").addClass('alerta');
		}
    
    }

	if(!error){ 
		$("#mensajeError").html('');   
		abrir($(this),event,false);
	}	
	
	});
		
</script>
