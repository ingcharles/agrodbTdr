
<style>
.mas {
	background-image: url(../img/mas.png);
	background-position: left left;
	background-repeat: no-repeat;
	padding: 0;
	background-color: #5CCD00;
	width: 100%;
	height: 31px;
}

.mensaje {
	color: #DF0101;
	tex-align: center;
	font-weight: bold;
}

.menos {
	background-image: url(../img/menos.png);
	background-position: left left;
	background-repeat: no-repeat;
	padding: 0;
	background-color: #5CCD00;
	width: 100%;
	height: 31px;
	/*border: none;*/
}

.mas span:before {
	color: #635B53;
}

.menos span:before {
	margin-right: 0.9em;
	color: #635B53;
}

.mas:focus,.menos:focus {
	outline: none;
}
</style>

<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cfe = new ControladorFitosanitarioExportacion();
$cro = new ControladorRegistroOperador();

$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
$identificadorExportador = htmlspecialchars ($_POST['identificadorExportador'],ENT_NOQUOTES,'UTF-8');

$qNotificacionExportador = $cfe->obtenerNotificacionesXExportador($conexion, $identificadorExportador);

$qSancionExportador = $cfe->obtenerSancionesXExportador($conexion, $identificadorExportador);

switch ($opcion) {

	case 'sanciones':

		$qExportador = $cro->buscarOperador($conexion, $identificadorExportador);
		$exportador = pg_fetch_assoc($qExportador);

		if(pg_num_rows($qExportador)==0){
			echo '<div class="mensaje">El usuario no se encuentra registrado.</div>';
		}else{
			echo '<label>Razón Social:</label>
		<input type="text" id="razonSocial" name="razonSocial" value="'. $exportador['razon_social'] .'" /><hr/>';
			echo '<div data-linea="2">';

			if(pg_num_rows($qNotificacionExportador)==0){
				echo '<div class="mensaje"><span>El exportador no posee notificaciones.</span></div>';
			}else{
				$contador=1;

				echo '<div class="requisitos">' ;
				echo  '<div class="mapa">'.
						'<button type="button" class="mas"><span>Notificaciones</span></button>';
					
				echo '<div style="display:none" >';

				while ($notificacionExportador = pg_fetch_assoc($qNotificacionExportador)){
					echo '<b>Notificación '.$contador++.':</b> en la fecha '.substr($notificacionExportador['fecha_notificacion'], 0, 10).' por producto '.$notificacionExportador['nombre_producto'].' para el país '.$notificacionExportador['pais'].' debido a '.$notificacionExportador['motivo_notificacion'].'<hr/>';
				}
				echo '</div></div>';
				echo '</div>';

			}

			if(pg_num_rows($qSancionExportador)==0){
				echo '<div class="mensaje"><span>El exportador no posee sanciones.</span></div>';
			}else{
				echo '<div class="requisitos">' ;
				echo  '<div class="mapa">'.
						'<button type="button" class="mas"><span>Sanciones</span></button>';

				echo '<div  style="display:none" >';
				while ($sancionExportador = pg_fetch_assoc($qSancionExportador)){
					echo '<b>Sanción '.$contador++.':</b> en la fecha '.substr($sancionExportador['fecha_inicio_sancion'], 0, 10).' por producto '.$sancionExportador['nombre_producto'].' para el país '.$sancionExportador['id_pais'].' debido a '.$sancionExportador['motivo_sancion'].'<hr/>';
				}
				echo '</div></div>';
				echo '</div>';
			}

			echo'</div>';

			$qTipoProducto = $cfe->obtenerTipoProductoXExportadorSancion($conexion, $identificadorExportador);

			echo '<div data-linea="6"><label>Tipo producto: </label>
						<select id="idTipoProducto" name="idTipoProducto" required>
						<option value="0">Seleccione...</option>';
			while ($tipoProducto = pg_fetch_assoc($qTipoProducto)){
				echo '<option value="'. $tipoProducto['id_tipo_producto'].'" >'.$tipoProducto['nombre'].'</option>';
			}
			echo '</select></div><hr/>';
		}

		break;

	case 'tipoProducto':
		$qSubtipoProducto = $cfe->obtenerSubtipoProductoXExportadorXTipoSancion($conexion, $_POST[idTipoProducto]);

		echo '<div data-linea="8"><label>Subtipo producto: </label>
					<select id="idSubtipoProducto" name="idSubtipoProducto" required>
					<option value="0">Seleccione...</option>';
		while ($subTipoProducto = pg_fetch_assoc($qSubtipoProducto)){
			echo '<option value="'. $subTipoProducto['id_subtipo_producto'].'" >'.$subTipoProducto['nombre'].'</option>';
		}
		echo '</select><hr/>';
		break;

	case 'subtipoProducto':
		$qproducto = $cfe->obtenerProductoXExportadorXSubtipoSancion($conexion, $_POST[idSubtipoProducto]);

		echo '<div data-linea="9"><label>Producto: </label>
			<select id="idProducto" name="idProducto" required>
			<option value="0">Seleccione...</option>';
		while ($producto = pg_fetch_assoc($qproducto)){
			echo '<option value="'. $producto['id_producto'].'" >'.$producto['nombre_comun'].'</option>';
		}
		echo '</select><input type="hidden" id="nombreProducto" name="nombreProducto"/><hr/>';
		break;


	case 'producto';
	$qPaisSancionExportador=  $cfe->obtenerPaisesSancionesXExportador($conexion, $identificadorExportador);

	echo '<div data-linea="10"><label>Pais: </label>
			<select id="idPais" name="idPais" required>
			<option value="0">Seleccione...</option>';
	while ($paisSancionExportador = pg_fetch_assoc($qPaisSancionExportador)){
		echo '<option value="'. $paisSancionExportador['id_pais_destino'].'" >'.$paisSancionExportador['nombre_pais_destino'].'</option>';
	}
	echo '</select>
					<input type="hidden" id="nombrePais" name="nombrePais"/>';

	break;
}


?>

<script type="text/javascript"> 

	$(document).ready(function(){		
		distribuirLineas(); 
	});

	$("#idTipoProducto").change(function(event){
		
    	 $('#nuevoSancionProductoCFE').attr('data-opcion','accionesSancionProducto');
    	 $('#nuevoSancionProductoCFE').attr('data-destino','resultadoTipoProducto');
    	 $('#opcion').val('tipoProducto');
    	 event.stopImmediatePropagation(); 
    	 abrir($("#nuevoSancionProductoCFE"),event,false);	
   	});

	$("#idSubtipoProducto").change(function(event){	        
    	 $('#nuevoSancionProductoCFE').attr('data-opcion','accionesSancionProducto');
    	 $('#nuevoSancionProductoCFE').attr('data-destino','resultadoSubtipoProducto');
    	 $('#opcion').val('subtipoProducto');
    	 event.stopImmediatePropagation(); 
    	 abrir($("#nuevoSancionProductoCFE"),event,false);	
   	});

	$("#idProducto").change(function(event){        
   	 $('#nuevoSancionProductoCFE').attr('data-opcion','accionesSancionProducto');
   	 $('#nuevoSancionProductoCFE').attr('data-destino','resultadoProducto');
   	 $('#opcion').val('producto');
   	 $('#nombreProducto').val($('#idProducto option:selected').text());
   	event.stopImmediatePropagation(); 
   	 abrir($("#nuevoSancionProductoCFE"),event,false);	
  	});

	$("#idPais").change(function(event){        
		$('#nombrePais').val($('#idPais option:selected').text());
		$("#guardar").show();
	});

	$(".requisitos").on("click","div.mapa button",function (event) {
	 		event.stopImmediatePropagation(); 
		   visualizarPantalla = $(this).parent().find("div");
	        if ($(this).hasClass("mas")) {
	            $(this).removeClass("mas");
	            $(this).addClass("menos");
	            visualizarPantalla.show();
	        } else {
	        	$(this).removeClass("menos");
	            $(this).addClass("mas");
	           visualizarPantalla.hide();
	        }
	    });
	
</script>
