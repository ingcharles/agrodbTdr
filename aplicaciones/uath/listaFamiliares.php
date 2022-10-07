<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();

$identificador=$_SESSION['usuario'];
	
?>
<style type="text/css">

	div h2 {
		text-align: right;
		font-size: 1.7em;
		font-weight: lighter;
		border-bottom: 1px solid #DEDEDE;
		color: #656569;
	}

</style>
<header>
		
			<h1>Familiares y Contactos</h1>
			
			<nav>
				<a id="_nuevo" data-rutaaplicacion="uath" data-opcion="nuevosDatosFamiliares" data-destino="detalleItem" href="#">Nuevo</a>
				<a id="_actualizar" data-rutaaplicacion="uath" data-opcion="listaFamiliares" data-destino="listadoItems" href="#">Actualizar</a>
				<a id="_eliminar" data-rutaaplicacion="uath" data-opcion="eliminarFamiliar" data-destino="detalleItem" href="#">Eliminar</a>
			</nav>
		</header>
		
		
	<div id="contactos_Emergencia">
		<h2>Contactos de Emergencia</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="familiares">
		<h2>Familiares</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="familiaresDiscapacidad">
		<h2>Familiares con Discapacidad</h2>
		<div class="elementos"></div>
	</div>
		

			<?php 
				$cd = new ControladorCatastro();
				$res = $cd->obtenerDatosFamiliares($conexion, $identificador);
				$contador = 0;
				$impreso=0;
				$contador1=0;
						
				while($familiar = pg_fetch_assoc($res)){
					$contador1=0;
					$enf = $cd->obtenerDiscapacidad($conexion, $familiar['identificador_familiar']);
					$contador1=pg_num_rows($enf);
					
					if(strcmp($familiar['posee_discapacidad'],"SI")==0){
					  $categoria="familiaresDiscapacidad";	
					}
					else if($familiar['contacto_emergencia']=="t"){
						$categoria="contactos_Emergencia";
					}
					else if(strcmp($familiar['posee_discapacidad'],"NO")==0){
					  $categoria="familiares";
					}
					
					$contenido =  '<article
						id="'.$familiar['identificador_familiar'].'"
						class="item"
						data-rutaAplicacion="uath"
						data-opcion="modificarDatosFamiliares"
						ondragstart="drag(event)"
						draggable="true"
						data-destino="detalleItem">
					<span class="ordinal">'.++$contador.'</span>
					<span><small><b>Nombre: </b>'.$familiar['apellido'].' '.$familiar['nombre'].'</small><br/></span>
					<span><small><b>Parentezco: </b>'.($familiar['relacion']).'</small><br/></span>
					<aside><small>Enfermedades registradas: </small><b>'.$contador1.'</b></aside>
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
		$("#contactos_Emergencia div> article").length == 0 ? $("#contactos_Emergencia").remove():"";
		$("#familiares div> article").length == 0 ? $("#familiares").remove():"";
		$("#familiaresDiscapacidad div> article").length == 0 ? $("#familiaresDiscapacidad").remove():"";
	});

	$('#_actualizar').click(function(event){
		event.preventDefault();
		abrir($('#_actualizar'),event, false);
	});
	
</script>
