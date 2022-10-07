<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorRegistroOperador.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/GoogleAnalitica.php';
	
	function reemplazarCaracteres($cadena){
		$cadena = str_replace('á', 'a', $cadena);
		$cadena = str_replace('é', 'e', $cadena);
		$cadena = str_replace('í', 'i', $cadena);
		$cadena = str_replace('ó', 'o', $cadena);
		$cadena = str_replace('ú', 'u', $cadena);
		$cadena = str_replace('ñ', 'n', $cadena);
		$cadena = strtolower(str_replace(' ', '', $cadena));
		
		return $cadena;
	}
	
	
	function reemplazarCadenaCaracteres($cadena){
		$cadena = str_replace('á', 'a', $cadena);
		$cadena = str_replace('é', 'e', $cadena);
		$cadena = str_replace('í', 'i', $cadena);
		$cadena = str_replace('ó', 'o', $cadena);
		$cadena = str_replace('ú', 'u', $cadena);
		$cadena = str_replace('ñ', 'n', $cadena);
	
		$cadena = str_replace('Á', 'A', $cadena);
		$cadena = str_replace('É', 'E', $cadena);
		$cadena = str_replace('Í', 'I', $cadena);
		$cadena = str_replace('Ó', 'O', $cadena);
		$cadena = str_replace('Ú', 'U', $cadena);
		$cadena = str_replace('Ñ', 'N', $cadena);
	
		return $cadena;
	}
	
?>

<header>
		<h1>Sitios</h1>
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
	
	<div id="azuay">
		<h2>Azuay</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="bolivar">
		<h2>Bolivar</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="canar">
		<h2>Cañar</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="carchi">
		<h2>Carchi</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="chimborazo">
		<h2>Chimborazo</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="cotopaxi">
		<h2>Cotopaxi</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="eloro">
		<h2>El Oro</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="esmeraldas">
		<h2>Esmeraldas</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="galapagos">
		<h2>Galápagos</h2>
		<div class="elementos"></div>
	</div>
	<div id="guayas">
		<h2>Guayas</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="imbabura">
		<h2>Imbabura</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="loja">
		<h2>Loja</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="losrios">
		<h2>Los Ríos</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="manabi">
		<h2>Manabí</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="moronasantiago">
		<h2>Morona Santiago</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="napo">
		<h2>Napo</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="orellana">
		<h2>Orellana</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="pastaza">
		<h2>Pastaza</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="pichincha">
		<h2>Pichincha</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="santaelena">
		<h2>Santa Elena</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="santodomingodelostsachilas">
		<h2>Santo Domingo de los Tsáchilas</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="sucumbios">
		<h2>Sucumbíos</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="tungurahua">
		<h2>Tungurahua</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="zamorachinchipe">
		<h2>Zamora Chinchipe</h2>
		<div class="elementos"></div>
	</div>
	<?php 
		$cr = new ControladorRegistroOperador();
		
		$res = $cr->listarSitios($conexion, $_SESSION['usuario']);
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			$categoria = reemplazarCaracteres($fila['provincia']);
			$contenido =  '<article 
						id="'.$fila['id_sitio'].'"
						class="item"
						data-rutaAplicacion="registroOperador"
						data-opcion="abrirSitio" 
						ondragstart="drag(event)"  
						draggable="true" 
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span><small><b>Código: </b>'.$fila['identificador_operador'].'.'.$fila['codigo_provincia'].$fila['codigo'].'</small><br/></span>
					<span><b>'.(strlen($fila['nombre_lugar'])>15?(substr(reemplazarCadenaCaracteres($fila['nombre_lugar']),0,15).'...'):(strlen($fila['nombre_lugar'])>0?$fila['nombre_lugar']:'')).'</b><br/></span>
					<span><i>'.(strlen($fila['direccion'])>20?(substr(reemplazarCadenaCaracteres($fila['direccion']),0,20).'...'):(strlen($fila['direccion'])>0?$fila['direccion']:'')).'</i></small></span>
					<aside><small>Áreas registradas: </small><b>'.$fila['num_areas'].'</b></aside>
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
	

<script>
$(document).ready(function(){
	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un sitio para revisarlo.</div>');
	$("#azuay div> article").length == 0 ? $("#azuay").remove():"";
	$("#bolivar div> article").length == 0 ? $("#bolivar").remove():"";
	$("#canar div> article").length == 0 ? $("#canar").remove():"";
	$("#carchi div> article").length == 0 ? $("#carchi").remove():"";
	$("#chimborazo div> article").length == 0 ? $("#chimborazo").remove():"";
	$("#cotopaxi div> article").length == 0 ? $("#cotopaxi").remove():"";
	$("#eloro div> article").length == 0 ? $("#eloro").remove():"";
	$("#esmeraldas div> article").length == 0 ? $("#esmeraldas").remove():"";
	$("#galapagos div> article").length == 0 ? $("#galapagos").remove():"";
	$("#guayas div> article").length == 0 ? $("#guayas").remove():"";
	$("#imbabura div> article").length == 0 ? $("#imbabura").remove():"";
	$("#loja div> article").length == 0 ? $("#loja").remove():"";
	$("#losrios div> article").length == 0 ? $("#losrios").remove():"";
	$("#manabi div> article").length == 0 ? $("#manabi").remove():"";
	$("#moronasantiago div> article").length == 0 ? $("#moronasantiago").remove():"";
	$("#napo div> article").length == 0 ? $("#napo").remove():"";
	$("#orellana div> article").length == 0 ? $("#orellana").remove():"";
	$("#pastaza div> article").length == 0 ? $("#pastaza").remove():"";
	$("#pichincha div> article").length == 0 ? $("#pichincha").remove():"";
	$("#santaelena div> article").length == 0 ? $("#santaelena").remove():"";
	$("#santodomingodelostsachilas div> article").length == 0 ? $("#santodomingodelostsachilas").remove():"";
	$("#sucumbios div> article").length == 0 ? $("#sucumbios").remove():"";
	$("#tungurahua div> article").length == 0 ? $("#tungurahua").remove():"";
	$("#zamorachinchipe div> article").length == 0 ? $("#zamorachinchipe").remove():"";
});
</script>