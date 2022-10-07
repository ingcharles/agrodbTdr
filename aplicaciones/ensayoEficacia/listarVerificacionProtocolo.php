<?php
	session_start();
	require_once '../../clases/Conexion.php';
	
	require_once '../../clases/ControladorAplicaciones.php';
	
	require_once '../../clases/ControladorEnsayoEficacia.php';

	require_once './clases/Perfil.php';
	require_once './clases/Flujo.php';

	$conexion = new Conexion();
	
	$ca = new ControladorAplicaciones();

	$identificador=$_SESSION['usuario'];


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<header>
		<h1>Verificación de Protocolo</h1>
		<nav>
		<?php 
			
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

	<div id="solicitud">
		<h2>Solicitudes sin enviar</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="pago">
		<h2>Solicitudes en pago</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="verificacionProtocolo">
		<h2>En revisión de solicitud de Protocolo</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="subsanarProtocolo">
		<h2>Para subsanar protocolo</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobarProtocoloDir">
		<h2>Por aprobar protocolo</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobarProtocoloCor">
		<h2>Por aprobar protocolo</h2>
		<div class="elementos"></div>
	</div>
	<div id="inspeccion">
		<h2>En inspección</h2>
		<div class="elementos"></div>
	</div>
	<div id="verificacionInforme">
		<h2>En revisión de informes finales</h2>
		<div class="elementos"></div>
	</div>
	<div id="subsanarInforme">
		<h2>Para subsanar informes finales</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobarInformeDir">
		<h2>Por aprobar informes finales</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobarInformeCor">
		<h2>Por aprobar informes finales</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobado">
		<h2>Protocolos aprobados</h2>
		<div class="elementos"></div>
	</div>

	<?php
		
		$ce = new ControladorEnsayoEficacia();

		//Miro que perfil es el usuario logeado
		$perfiles= $ce->obtenerPerfiles($conexion,$identificador);
		$perfil=new Perfil($perfiles);
		
		$estado='verificacionProtocolo';
		$flujo=$ce->obtenerFlujo($conexion,$_SESSION['idAplicacion'],$estado);
		$yaDesplego=false;
		$tramites =array();
		$paginaSiguiente='';
		if($perfil->EsAnalistaCentral() || $perfil->EsAnalistaDistrital()){
			$tramitesRecuperados=$ce->obtenerFlujosDeTramitesEE($conexion,$flujo['id_fase'],$identificador,'N');
			while($fila = pg_fetch_assoc($tramitesRecuperados)){
				$tramites[]=$fila;
			}
			$paginaSiguiente='abrirVerificarProtocolo';
		}
		if($perfil->EsDirector() || $perfil->EsDirectorTipoA()){
			$paginaSiguiente='abrirVerificarProtocolo';
		}
		if(sizeof($tramites)>0){

			$flujos=$ce->obtenerFlujosDelDocumento($conexion,$flujo['id_flujo']);



		$contador = 0;
		foreach($tramites as $key=>$fila)
		{
			$categoria = $fila['estado'];
			if($categoria==null)
				$categoria='solicitud';
			else
				$categoria = trim($categoria);

			$fechaTiempo=new DateTime($fila['fecha_fin']);			
			$fecha='';
			if($fechaTiempo!=null){
				$fechaActual=new DateTime();
				if($fechaActual<$fechaTiempo)
					$fecha ='F.límite:'.$fechaTiempo->format('Y-m-d');
				else
					$fecha ='<font color="red">'.'F.límite:'.$fechaTiempo->format('Y-m-d').'</font>';
			}

			
				$contenido = '<article
							id="'.$fila['id_documento'].'"
							data-flujo="'.$flujo['id_flujo'].'"
							data-idOpcion="'.$flujo['id_fase'].'"
							data-nombre="'.$fila['id_tramite_flujo'].'"
							class="item"
							data-rutaAplicacion="ensayoEficacia"
							data-opcion="'.$paginaSiguiente.'"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<span>'.(strlen($fila['razon_social'])>45?(substr($fila['razon_social'],0,45).'...'):(strlen($fila['razon_social'])>0?$fila['razon_social']:'Empresa')).'</span></br>
							<span>'.(strlen($fila['plaguicida_nombre'])>45?(substr($fila['plaguicida_nombre'],0,45).'...'):(strlen($fila['plaguicida_nombre'])>0?$fila['plaguicida_nombre']:'X definir')).'</span></br>
							<span>'.(strlen($fila['id_expediente'])>45?(substr($fila['id_expediente'],0,45).'...'):(strlen($fila['id_expediente'])>0?$fila['id_expediente']:'X definir')).'</span>
							<aside><small>'.$fecha.'</small></aside>
						</article>';
	?>
				<script type="text/javascript">
					var contenido = <?php echo json_encode($contenido);?>;
					var categoria = <?php echo json_encode($categoria);?>;
					$("#"+categoria+" div.elementos").append(contenido);
				</script>

	<?php 				
		}
	}
	?>	
</body>
<script>
$(document).ready(function(){
		$("#listadoItems").addClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#solicitud div> article").length == 0 ? $("#solicitud").remove():"";
		$("#pago div> article").length == 0 ? $("#pago").remove():"";
		$("#verificacionProtocolo div> article").length == 0 ? $("#verificacionProtocolo").remove():"";
		$("#subsanarProtocolo div> article").length == 0 ? $("#subsanarProtocolo").remove():"";
		$("#aprobarProtocoloDir div> article").length == 0 ? $("#aprobarProtocoloDir").remove():"";
		$("#aprobarProtocoloCor div> article").length == 0 ? $("#aprobarProtocoloCor").remove():"";
		$("#inspeccion div> article").length == 0 ? $("#inspeccion").remove():"";
		$("#verificacionInforme div> article").length == 0 ? $("#verificacionInforme").remove():"";
		$("#subsanarInforme div> article").length == 0 ? $("#subsanarInforme").remove():"";
		$("#aprobarInformeDir div> article").length == 0 ? $("#aprobarInformeDir").remove():"";
		$("#aprobarInformeCor div> article").length == 0 ? $("#aprobarInformeCor").remove():"";
		$("#aprobado div> article").length == 0 ? $("#aprobado").remove():"";

	});

</script>
</html>