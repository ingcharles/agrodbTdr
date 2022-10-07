<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAplicaciones.php';

set_time_limit(360);

$identificadorOperador = $_SESSION['usuario'];

?>

<div id="contendorArticulos">
	<header>
		<h1>Solicitudes</h1>
		<nav>
			<?php 

			$conexion = new Conexion();
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $identificadorOperador);
			while($fila = pg_fetch_assoc($res)){
				echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '">'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
			}
		?>
		</nav>
	</header>

	<div id="registrado">
		<h2>Operaciones</h2>
		<div class="elementos"></div>
	</div>

	<div id="subsanacion">
		<h2>Operaciones en proceso de subsanación</h2>
		<div class="elementos"></div>
	</div>

	<div id="pago">
		<h2>Imposición de pago (AGROCALIDAD)</h2>
		<div class="elementos"></div>
	</div>

	<div id="verificacion">
		<h2>Operaciones por pagar</h2>
		<div class="elementos"></div>
	</div>

	<div id="inspeccion">
		<h2>Operaciones en proceso de inspección</h2>
		<div class="elementos"></div>
	</div>

	<div id="cargarAdjunto">
		<h2>Operaciones para subir documentos adjuntos</h2>
		<div class="elementos"></div>
	</div>


	<div id="cargarIA">
		<h2>Operaciones para cargar información adicional</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="representanteTecnico">
		<h2>Operaciones para cargar representante técnico</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="cargarProducto">
		<h2>Operaciones para declarar productos</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="documental">
		<h2>Operaciones para revisión documental</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="porCaducar">
		<h2>Operaciones por caducar</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="cargarRendimiento">
		<h2>Operaciones por cargar rendimiento</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="declararICentroAcopio">
		<h2>Operaciones por declarar información de centro de acopio</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="declararDVehiculo">
		<h2>Operaciones por declarar información de vehículo</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="declararProveedor">
		<h2>Operaciones por declarar proveedores</h2>
		<div class="elementos"></div>
	</div>

	<?php 
	$cr = new ControladorRegistroOperador();
	
	$res = $cr->listarOperacionesOperador($conexion, $identificadorOperador, " not in ('eliminado')", 20, 0);
	
	while($fila = pg_fetch_assoc($res)){
	
		switch ($fila['estado']){
	
			case 'registrado':
				$categoria = 'registrado';
				$estado = 'aprobada';
				$clase = 'circulo_verde';
			break;
					
			case 'rechazado':
				$categoria = 'registrado';
				$estado = 'rechazada';
				$clase = 'circulo_rojo';
			break;
					
			case 'cancelado':
				$categoria = 'registrado';
				$estado = 'Cancelado';
				$clase = 'circulo_rojo';
			break;
					
			case 'anulado':
				$categoria = 'registrado';
				$estado = 'Anulado';
				$clase = 'circulo_rojo';
			break;
					
			case 'noHabilitado':
				$categoria = 'registrado';
				$estado = 'No habilitado';
				$clase = 'circulo_rojo';
			break;
					
			case 'registradoObservacion':
				$categoria = 'registrado';
				$estado = 'aprobada con observación';
				$clase = 'circulo_amarillo';
			break;
					
			case 'inactivo':
				$categoria = 'registrado';
				$estado = 'Inactivo';
				$clase = 'circulo_rojo';
			break;
					
			case 'inspeccion':
				$categoria = 'inspeccion';
				$estado = 'por asignar';
				$clase = '';
			break;
					
			case 'asignadoInspeccion':
				$categoria = 'inspeccion';
				$estado = 'asignado';
				$clase = '';
			break;
					
			case 'pago':
				$categoria = 'pago';
				$estado = 'por asignar valor';
				$clase = '';
			break;
					
			case 'representanteTecnico':
				$categoria = 'representanteTecnico';
				$estado = 'Repre. técnico';
				$clase = '';
			break;
					
			case 'cargarAdjunto':
				$categoria = 'cargarAdjunto';
				$estado = 'Adjunto';
				$clase = '';
			break;
					
			case 'subsanacion':
			    $categoria = 'subsanacion';
			    $estado = 'Adjunto';
			    $clase = '';
			break;
			case 'subsanacionRepresentanteTecnico':
			    $categoria = 'subsanacion';
			    $estado = 'Repre. técnico';
			    $clase = '';
			break;
			case 'subsanacionProducto':
			    $categoria = 'subsanacion';
				$estado = 'Por cargar productos';
				$clase = '';
			break;
					
			case 'cargarIA':
			case 'declararICentroAcopio':
			case 'declararDVehiculo':
			case 'declararIMercanciaPecuaria':
			case 'declararIColmenar':
				$categoria = 'cargarIA';
				$estado = 'Inf. Adicional';
				$clase = '';
			break;
					
			case 'verificacion':
				$categoria = 'verificacion';
				$estado = 'Por pagar';
				$clase = '';
			break;
				
			case 'cargarProducto':
				$categoria = 'cargarProducto';
				$estado = 'Por cargar productos';
				$clase = '';
			break;
			
			case 'documental':
				$categoria = 'documental';
				$estado = 'Por revisión documental';
				$clase = '';
			break;
			
			case 'asignadoDocumental':
				$categoria = 'documental';
				$estado = 'Por revisión documental';
				$clase = '';
			break;
				
			case 'porCaducar':
			    $categoria = 'porCaducar';
			    $estado = 'Por caducar';
			    $clase = '';
			break;
			
			case 'cargarRendimiento':
			    $categoria = 'cargarRendimiento';
			    $estado = 'Cargar rendimiento';
			    $clase = '';
			break;
			
			case 'declararProveedor':
			    $categoria = 'declararProveedor';
			    $estado = 'Declarar proveedor';
			    $clase = '';
			    break;
	
			default:
				$categoria = 'ninguna';
				$estado = 'ninguna';
				$clase = '';
		}
	
		$nombreArea = $cr->buscarNombreAreaPorSitioPorTipoOperacion($conexion, $fila['id_tipo_operacion'], $identificadorOperador, $fila['id_sitio'], $fila['id_operacion']);
		
		$codigoSitio = $fila['id_sitio'].'-'.$categoria;
		$nombreSitio = $fila['nombre_lugar'];
		$contenido = '<article
			id="'.$fila['id_operacion'].'"
			class="item"
			data-rutaAplicacion="registroOperador"
			data-opcion="abrirOperacion"
			ondragstart="drag(event)"
			draggable="true"
			data-destino="detalleItem">
			<span><small> # '.$fila['id_tipo_operacion'].'-'.$fila['id_sitio'].' </small></span>
						<span><small>'.(strlen($fila['provincia'])>14?(substr($cr->reemplazarCaracteres($fila['provincia']),0,14).'...'):(strlen($fila['provincia'])>0?$fila['provincia']:'')).'</small></span><br />
						<span><small>'.(strlen($fila['nombre_tipo_operacion'])>30?(substr($cr->reemplazarCaracteres($fila['nombre_tipo_operacion']),0,30).'...'):(strlen($fila['nombre_tipo_operacion'])>0?$fila['nombre_tipo_operacion']:'')).'<b> en </b> '.
							(strlen($nombreArea)>42?(substr($cr->reemplazarCaracteres($nombreArea),0,42).'...'):(strlen($nombreArea)>0?$nombreArea:'')).'</small></span>
					<aside class= "estadoOperador"><small> Estado: '.$estado.'<span><div class= "'.$clase.'"></div></span></small></aside>
						</article>';
		?>
		<script type="text/javascript">
						var contenido = <?php echo json_encode($contenido);?>;
						var subcategoria = <?php echo json_encode($codigoSitio);?>;	
						var nombreSitio = <?php echo json_encode($nombreSitio);?>;	
						var categoria = <?php echo json_encode($categoria);?>;						
						if($("#"+subcategoria).length == 0){
							$("#"+categoria+" div.elementos").append("<div id= "+subcategoria+"><h3>"+nombreSitio+"</h3><div class='subElementos'></div></div>");
						}
						$("#"+subcategoria+" div.subElementos").append(contenido);
						
					</script>
	
		<?php					
		}
	
	?>


</div>
<div id="mensajeCargando"></div>

<script>

	var identificadorOperador = <?php echo json_encode($identificadorOperador); ?>;

	$(document).ready(function(){
				
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
		$("#registrado div> article").length == 0 ? $("#registrado").remove():"";
		$("#pago div> article").length == 0 ? $("#pago").remove():"";
		$("#temporal div> article").length == 0 ? $("#temporal").remove():"";
		$("#inspeccion div> article").length == 0 ? $("#inspeccion").remove():"";
		$("#verificacion div> article").length == 0 ? $("#verificacion").remove():"";
		$("#cargarAdjunto div> article").length == 0 ? $("#cargarAdjunto").remove():"";
		$("#subsanacion div> article").length == 0 ? $("#subsanacion").remove():"";
		$("#cargarIA div> article").length == 0 ? $("#cargarIA").remove():"";
		$("#representanteTecnico div> article").length == 0 ? $("#representanteTecnico").remove():"";
		$("#cargarProducto div> article").length == 0 ? $("#cargarProducto").remove():"";
		$("#documental div> article").length == 0 ? $("#documental").remove():"";
		$("#porCaducar div> article").length == 0 ? $("#porCaducar").remove():"";
		$("#cargarRendimiento div> article").length == 0 ? $("#cargarRendimiento").remove():"";
		$("#declararICentroAcopio div> article").length == 0 ? $("#declararICentroAcopio").remove():"";
		$("#declararDVehiculo div> article").length == 0 ? $("#declararDVehiculo").remove():"";
		$("#declararProveedor div> article").length == 0 ? $("#declararProveedor").remove():"";
	
		colors = ['#FFB30C', '#8CC63F', '#0087EC', '#FF5A00' ];
		var i = 0;
		animate_loop = function() {      
		$('#verificacion div> article').animate({backgroundColor:colors[(i++)%colors.length]
			}, 900, function(){
				animate_loop();
			});
		}
		animate_loop();	

		incremento = 10;
		datoIncremento = 20;	
	});
	
sinDato = true;

	
$("#listadoItems").scroll(function(event){


		
		if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight-1) {
				
			event.preventDefault();
			event.stopImmediatePropagation();

    		var data = new Array();

    		 var $formulario = $(this);
    		
        	data.push({
        		name : 'incremento',
        		value : incremento
        	}, {
        		name : 'datoIncremento',
        		value : datoIncremento
        	}, {
        		name : 'identificadorOperador',
        		value : identificadorOperador
        	});
        	
        	url = "aplicaciones/registroOperador/cargarDatosOperaciones.php";
        	if(sinDato){

	        	if ($formulario.data('locked') == undefined || !$formulario.data('locked')){
	        		resultado = $.ajax({
	        		    url: url,
	        		    type: "post",
	        		    data: data,
	        		    dataType: "json",
	        		    async:   true,
	        		    beforeSend: function(){
	        		    	$("#estado").html('').removeClass();
	        		    	$("#mensajeCargando").html("<div id='cargando'>Cargando...</div>").fadeIn();
	        		    	$formulario.data('locked', true);	        		    	
	        			},
	        			
	        		    success: function(msg){
	        		    	if(msg.estado=="exito"){
		        		    	if(msg.mensaje.length != 0){
		        		    		$(msg.mensaje).each(function(i){
		        		    			switch(this.categoria) {
		        		    		    	case 'registrado':
		        		    		    		if($("#registrado div> article").length == 0){
		    										$('#contendorArticulos').append("<div id='registrado'><h2>Operaciones</h2><div class='elementos'></div></div>");
		            		    		    	}
		        		    		   		break;
		        		    		    	case 'pago':
		        		    		    		if($("#pago div> article").length == 0){
		    										$('#contendorArticulos').append("<div id='pago'><h2>Imposición de pago (AGROCALIDAD)</h2><div class='elementos'></div></div>");
		            		    		    	}
		        		    		    	break;
		        		    		    	case 'verificacion':
		        		    		    		if($("#verificacion div> article").length == 0){
		    										$('#contendorArticulos').append("<div id='verificacion'><h2>Operaciones por pagar</h2><div class='elementos'></div></div>");
		            		    		    	}
		        		    		    	break;
		        		    		    	case 'inspeccion':
		        		    		    		if($("#inspeccion div> article").length == 0){
		    										$('#contendorArticulos').append("<div id='inspeccion'><h2>Operaciones en proceso de inspección</h2><div class='elementos'></div></div>");
		            		    		    	}
		        		    		    	break;
		        		    		    	case 'subsanacion':
		        		    		    		if($("#subsanacion div> article").length == 0){
		    										$('#contendorArticulos').append("<div id='subsanacion'><h2>Operaciones en proceso de subsanación</h2><div class='elementos'></div></div>");
		            		    		    	}
		        		    		    	break;
		        		    		    	case 'cargarIA':
		        		    		    	case 'declararICentroAcopio':
		        		    		    	case 'declararDVehiculo':
		        		    		    		if($("#cargarIA div> article").length == 0){
		    										$('#contendorArticulos').append("<div id='cargarIA'><h2>Operaciones para cargar información adicional</h2><div class='elementos'></div></div>");
		            		    		    	}
		        		    		    	break;
		        		    		    	case 'cargarAdjunto':
		        		    		    		if($("#cargarAdjunto div> article").length == 0){
		    										$('#contendorArticulos').append("<div id='cargarAdjunto'><h2>Operaciones para subir documentos adjuntos</h2><div class='elementos'></div></div>");
		            		    		    	}
		        		    		    	break;
		        		    		    	case 'representanteTecnico':
		        		    		    		if($("#representanteTecnico div> article").length == 0){
		    										$('#contendorArticulos').append("<div id='representanteTecnico'><h2>Operaciones para cargar representante técnico</h2><div class='elementos'></div></div>");
		            		    		    	}
		        		    		    	break;
		        		    		    	case 'cargarProducto':
		        		    		    		if($("#cargarProducto div> article").length == 0){
		    										$('#contendorArticulos').append("<div id='cargarProducto'><h2>Operaciones para cargar productos</h2><div class='elementos'></div></div>");
		            		    		    	}
		        		    		    	break;
		        		    		    	case 'documental':
		        		    		    		if($("#documental div> article").length == 0){
		    										$('#contendorArticulos').append("<div id='documental'><h2>Operaciones en proceso de revisión documental</h2><div class='elementos'></div></div>");
		            		    		    	}
		        		    		    	break;
		        		    		    	case 'porCaducar':
		        		    		    		if($("#porCaducar div> article").length == 0){
		    										$('#contendorArticulos').append("<div id='porCaducar'><h2>Operaciones por caducar</h2><div class='elementos'></div></div>");
		            		    		    	}
		        		    		    	break;
											case 'cargarRendimiento':
		        		    		    		if($("#cargarRendimiento div> article").length == 0){
		    										$('#contendorArticulos').append("<div id='cargarRendimiento'><h2>Operaciones por cargar rendimiento</h2><div class='elementos'></div></div>");
		            		    		    	}
		        		    		    	break;
											case 'cargarRendimiento':
		        		    		    		if($("#cargarRendimiento div> article").length == 0){
		    										$('#contendorArticulos').append("<div id='cargarRendimiento'><h2>Operaciones por cargar rendimiento</h2><div class='elementos'></div></div>");
		            		    		    	}
		        		    		    	break;	
											case 'declararProveedor':
		        		    		    		if($("#declararProveedor div> article").length == 0){
		    										$('#declararProveedor').append("<div id='cargarRendimiento'><h2>Operaciones por declarar proveedoreso</h2><div class='elementos'></div></div>");
		            		    		    	}
		        		    		    	break;											
		        		    			}
							
		        						if($("#"+this.subcategoria).length == 0){
		        							$("#"+this.categoria+" div.elementos").append("<div id= "+this.subcategoria+"><h3>"+this.nombreSitio+"</h3><div class='subElementos'></div></div>");
		        						}
		        						$("#"+this.subcategoria+" div.subElementos").append(this.contenido); 
		        		    	    });
		        		    	}else{
		        		    		sinDato = false;
		        			    }		    		
	        		    	}else{
	        		    		mostrarMensaje(msg.mensaje,"FALLO");
		        		    }
	        		    		
	        		   },
	        		    error: function(jqXHR, textStatus, errorThrown){
	        		    	$("#cargando").delay("slow").fadeOut();
	        		    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
	        		    },
	        	        complete: function(){
	        	        	datoIncremento = datoIncremento +10;    	        	
	        	        	$("#cargando").delay("slow").fadeOut();
	        	        	$formulario.data('locked', false);   	        	
							    
	        	        }
	        		});
	            }
			}
		}

                        
});
	

</script>