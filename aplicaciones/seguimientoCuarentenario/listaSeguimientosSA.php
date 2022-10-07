<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorSeguimientoCuarentenario.php';
	require_once '../../clases/ControladorAplicaciones.php';
?>
<div id="contendorArticulos">
<header>
		<h1>Seguimiento cuarentenario</h1>
		<nav>
		<?php 
			$conexion = new Conexion();
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
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
	
	<div id="notificado" >
		<h2>Seguimientos Cuarentenarios Notificados</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="abierto">
		<h2>Seguimientos Cuarentenarios Abiertos</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="cerrado">
		<h2>Seguimientos Cuarentenarios Cerrados</h2>
		<div class="elementos"></div>
	</div>
	
	<?php 
		$csc = new ControladorSeguimientoCuarentenario();
		
		$res = $csc->listarSeguimientosSADDAOperador($conexion, $_SESSION['nombreProvincia']);
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			$producto=$fila['productos'];
			$producto = (strlen($producto)>=60?(substr($producto,0,60).'...'):$producto);
			$categoria = $fila['estado_seguimiento'];
			$contenido = '<article 
						id="'.$fila['id_destinacion_aduanera'].'"
						class="item"
						data-rutaAplicacion="seguimientoCuarentenario"
						data-opcion="abrirSeguimientoSA" 
						ondragstart="drag(event)"  
						draggable="true" 
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span><small><b>'.$fila['codigo_certificado'].'<br/></b></small></span>
					<span><small>'.$fila['fecha_inicio'].'</small><br /></span>
					<span><small>'.$producto.'</small><br /></span>
                    <span><small>'.$fila['nombre_operador'].'</small><br /></span>
					<aside>Estado: '.$fila['estado_seguimiento'].'</aside>
				</article>';
			?>
			<script type="text/javascript">
				var contenido = <?php echo json_encode($contenido);?>;
				var categoria = <?php echo json_encode($categoria);?>;
				$("#"+categoria+" div.elementos").append(contenido);
			</script>
			<?php					
		}
		
		
		$ress = $csc->listarSeguimientosAbiertoCerradosSADDAOperador($conexion, $_SESSION['nombreProvincia'],'NO',10,0);
		
		$contador = 0;
		while($fila = pg_fetch_assoc($ress)){
			$producto=$fila['productos'];
			$producto = (strlen($producto)>=60?(substr($producto,0,60).'...'):$producto);
			$categoria = $fila['estado_seguimiento'];
			$contenido = '<article
						id="'.$fila['id_destinacion_aduanera'].'"
						class="item"
						data-rutaAplicacion="seguimientoCuarentenario"
						data-opcion="abrirSeguimientoSA"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span><small><b>'.$fila['codigo_certificado'].'<br/></b></small></span>
					<span><small>'.$fila['fecha_inicio'].'</small><br /></span>
					<span><small>'.$producto.'</small><br /></span>
                    <span><small>'.$fila['nombre_operador'].'</small><br /></span>
					<aside>Estado: '.$fila['estado_seguimiento'].'</aside>
				</article>';
			?>
					<script type="text/javascript">
						var contenido = <?php echo json_encode($contenido);?>;
						var categoria = <?php echo json_encode($categoria);?>;
						$("#"+categoria+" div.elementos").append(contenido);
					</script>
					<?php					
				}
	?>
</div>
<div id="mensajeCargando"></div>
<script>
var nombreProvincia = <?php echo json_encode($_SESSION['nombreProvincia']); ?>;
$(document).ready(function(){
	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
	$("#notificado div> article").length == 0 ? $("#notificado").remove():"";
	$("#abierto div> article").length == 0 ? $("#abierto").remove():"";
	$("#cerrado div> article").length == 0 ? $("#cerrado").remove():"";
	incremento = 10;
	datoIncremento = 10;
});

sinDato = true;

if($("#cerrado div> article").length>=10){
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
        		name : 'nombreProvincia',
        		value : nombreProvincia
        	});
        
        	url = "aplicaciones/seguimientoCuarentenario/cargarDatosSeguimientoSA.php";
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
		        		    			console.log(this.categoria);
			        		    		if($("#cerrado div> article").length == 0){
		    								$('#contendorArticulos').append("<div id='cerrado'><h2>Seguimientos Cuarentenarios Cerrados</h2><div class='elementos'></div></div>");
		            		    		}
		        		    		   $("#"+this.categoria+" div.elementos").append(this.contenido);
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
}

</script>