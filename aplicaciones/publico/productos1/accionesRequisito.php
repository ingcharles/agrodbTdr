<?php
	require_once '../../../clases/Conexion.php';
	require_once '../../../clases/ControladorCatalogos.php';
	require_once '../../../clases/ControladorRequisitos.php';
	require_once '../../../clases/ControladorComplementos.php';
	
	$conexion = new Conexion();
	$cc = new controladorCatalogos();
	$cr = new controladorRequisitos();
	$cco = new controladorComplementos();

	function quitar_tildes($cadena) {
		$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
		$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
		$texto = str_replace($no_permitidas, $permitidas ,$cadena);
		return $texto;
	}
	
	$actividadComercial1='Importación';
	$actividadComercial2='Exportación';
	$actividadComercial3='Tránsito';
	//$banderaProductos=true;
	
	if($_POST['tipoRequisito']=='Nacional'){
		$actividadComercial="('".$actividadComercial1."','".$actividadComercial2."','".$actividadComercial3."')";
		$productos=$cr->obtenerProductosConRequisitos($conexion, $_POST['area'], NULL, $actividadComercial , 'NULL',quitar_tildes($_POST['productoN']), $_POST['uso']);
		
		if(pg_num_rows($productos)!=0){
			while ($registros = pg_fetch_assoc($productos)) {
				$idProductos.="".$registros['id_producto'].",";
			}
			$producto=$cr->mostrarDatosGeneralesDeProductoSinRequisito($conexion, $_POST['area'],"(" . rtrim ( $idProductos, ',' ) . ")",quitar_tildes($_POST['productoN']),$_POST['partidaArancelaria'], $_POST['uso']);
		}else{
			$producto=$cr->mostrarDatosGeneralesDeProductoSinRequisito($conexion, $_POST['area'],"(0)",quitar_tildes($_POST['productoN']),$_POST['partidaArancelaria'], $_POST['uso']);
		}		
	}else{
		$actividadComercial="('".$_POST['tipoRequisito']."')";
		$producto=$cr->obtenerProductosConRequisitos($conexion, $_POST['area'], $_POST['pais'], $actividadComercial, 'NULL',quitar_tildes($_POST['productoN']), $_POST['uso'], $_POST['partidaArancelaria']);
	}
	
	
	if(pg_num_rows($producto)>0 ){
		?>
			<h1>LISTADO</h1>
			<form id="datosProductos" data-rutaAplicacion="../../../publico/productos1" data-opcion="mostrarRequisitos"	data-destino="resultadoProducto2">
			<input type = "hidden" id="tipoRequisito" name="tipoRequisito" value="<?php echo $actividadComercial;?>"  />
			<input type = "hidden" id="tipoArea" name="tipoArea" value="<?php echo $_POST['area']?>"  />
			<input type = "hidden" id="pais" name="pais" value="<?php echo $_POST['pais']?>"  />
			
		<?php 
		/*echo '<h1>Listado</h1>';
	echo '<div class="seleccionTemporal">
				<input class="seleccionTemporal"  id = "cTemporal" type = "checkbox" />
		    	<label >Seleccionar todos </label>
			</div>
		<hr>';*/
	
		echo '<table style="border-collapse: initial;">
				<thead>
  					<tr>
						<th width="35%">Producto</th>
						<th width="30%">Subtipo</th>
				    	<th width="35%">Tipo</th>
						
 					</tr>
 				</thead></table>';
	echo '<div id="contenedorProducto" >
			<table style="border-collapse: initial;">
			';

					$cantidadLinea = 0;

				while ($registros = pg_fetch_assoc($producto)) {
					$res=$cr->mostrarDatosGeneralesDeProducto($conexion, $registros['id_producto']);
					$registro=pg_fetch_assoc($res);
					echo '<tr>'.
							'<td align="left" width="35%">'.
								'<input id="'.$registro['id_producto'].'" type="checkbox" name="producto[]" class="productoActivar" value="'.$registro['id_producto'].'" />
								<label for="'.$registro['id_producto'].'">'.$registro['producto'].'</label></td>'.
							'<td align="left" width="31%"><label for="'.$registro['subtipo'].'">'.$registro['subtipo'].'</label></td>'.
							'<td align="left"  width="34%" ><label for="'.$registro['tipo'].'">'.$registro['tipo'].'</label></td>';
					echo '</tr>';
					
					if($cantidadLinea == 15){
						echo '<script type="text/javascript">$("#contenedorProducto").css({"height": "490px", "overflow": "auto"}); </script>';
					}
					
					$cantidadLinea++;
				}
		echo '</table>'.
		'</div><br>';
		echo '<div><button class="buttonCentro"type="submit" id="buscar"  name="buscar" >MOSTRAR</button></div>';
	}else{
	    switch ($_POST['tipoRequisito']){
	        case 'Exportación':
	            switch ($_POST['area']){
	                case 'SA':
	                    echo $cco->cargarMensaje('Agrocalidad Informa', 'Estimado Usuario:', 'El producto consultado no cuenta con requisitos zoosanitarios de exportación establecidos para el país de destino seleccionado, por favor dirija su consulta al siguiente correo electrónico: certificacion.zoosanitaria@agrocalidad.gob.ec; o al teléfono (02) 3828860 ext 2012', 'texto-popup-titulo-medio','texto-popup-subtitulo-medio','texto-popup-mensaje-medio');
                    break;
	                case 'SV':
	                    echo $cco->cargarMensaje('Agrocalidad Informa', 'Estimado Usuario:', 'El producto consultado no cuenta con requisitos fitosanitarios de exportación establecidos para el país de destino seleccionado, por favor dirija su consulta al siguiente correo electrónico: certificación.fitosanitaria@agrocalidad.gob.ec; o al teléfono (02) 3828860 ext 1064', 'texto-popup-titulo-medio','texto-popup-subtitulo-medio','texto-popup-mensaje-medio');
                    break;
                    default:
                        echo '<label class="mensajeInicial">NO EXISTEN COINCIDENCIAS DE BÚSQUEDA</label>';
	            }
            break;
            
	        case 'Importación':
	            switch ($_POST['area']){
	                case 'SA':
	                       echo $cco->cargarMensaje('Agrocalidad Informa', 'Estimado Usuario:', 'El producto consultado no cuenta con requisitos zoosanitarios de importación establecidos para el país de origen seleccionado, por favor dirija su consulta al siguiente correo electrónico: certificacion.zoosanitaria@agrocalidad.gob.ec; o al teléfono (02) 3828860 ext 2010', 'texto-popup-titulo-medio','texto-popup-subtitulo-medio','texto-popup-mensaje-medio');
	                    break;
	                case 'SV':
	                       echo $cco->cargarMensaje('Agrocalidad Informa', 'Estimado Usuario:', 'El producto consultado no cuenta con requisitos fitosanitarios de importación establecidos para el país de origen seleccionado, por favor dirija su consulta al siguiente correo electrónico: vigilancia.fitosanitaria@agrocalidad.gob.ec; o al teléfono (02) 3828860 ext 1063', 'texto-popup-titulo-medio','texto-popup-subtitulo-medio','texto-popup-mensaje-medio');
	                    break;
	                default:
	                    echo '<label class="mensajeInicial">NO EXISTEN COINCIDENCIAS DE BÚSQUEDA</label>';
	            }
            break;
            
            default:
                echo '<label class="mensajeInicial">NO EXISTEN COINCIDENCIAS DE BÚSQUEDA</label>';
	    }
	}
?>
	
</form>

</body>
<script type="text/javascript">

$("#datosProductos").submit(function(event){
	event.preventDefault();	
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;
	var selected=false;
	
	$('.productoActivar').each(function() {
		if($(this).prop('checked') ) {	
        	selected=true;    		
		 }else{
			$(this).addClass("alertaCombo");
		}	        
	});

	if (!selected) 
    	error=true;
    
	if (!error){
		$("#estado").html('');
		abrir($("#datosProductos"),event,false);
		 $("#resultadoProducto").html('');
	}else{
		$("#estado").html('Por favor debes seleccionar al menos un producto.').addClass("alerta");	
	}
	
});

</script>