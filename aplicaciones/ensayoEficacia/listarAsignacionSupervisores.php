<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';	
	require_once '../../clases/ControladorEnsayoEficacia.php';
	

	require_once '../ensayoEficacia/clases/Perfil.php';
	require_once '../ensayoEficacia/clases/Flujo.php';

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
		<h1>Asignación de técnico de laboratorio</h1>
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
		<h2>Protocolos por asignar Supervisor de Ensayo</h2>
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

	//Miro si perfil es Director
	$perfiles= $ce->obtenerPerfiles($conexion,$identificador);

	$perfil=new Perfil($perfiles);

	$yaDesplego=false;
	$tramites =array();

	$estado='inspeccion';		//el estado que binene de finanzas despues del pago
	$flujo=$ce->obtenerFlujo($conexion,$_SESSION['idAplicacion'],$estado);	
	$fase=$flujo['id_fase'];

	$perfiles=array();
	$perfilResponsable='PFL_RES_CENTRAL';	//En planta central
	if($perfil->tieneEstePerfil($perfilResponsable)){
		$ts=$ce->obtenerFlujosDeTramitesAsignarEE($conexion,$identificador,$fase,$estado,$perfilResponsable,'N');
		while($fila = pg_fetch_assoc($ts))
		{
			$tramites[]=$fila;
		}

	}
	$perfilResponsable='PFL_RES_DISTRITO';	//En planta central
	if($perfil->tieneEstePerfil($perfilResponsable)){
		$ts=$ce->obtenerFlujosDeTramitesAsignarEE($conexion,$identificador,$fase,$estado,$perfilResponsable,'N');
		while($fila = pg_fetch_assoc($ts))
		{

			$tramites[]=$fila;
		}

	}

	$perfilDirector='PFL_EE_DDTA';
	if($perfil->tieneEstePerfil($perfilDirector)){
		$ts=$ce->obtenerFlujosDeTramitesAsignarEE($conexion,$identificador,$fase,$estado,$perfilDirector,'N');
		while($fila = pg_fetch_assoc($ts))
		{
			$tramites[]=$fila;
		}

	}
	$perfiles[]='PFL_EE_SE';
	if(sizeof($tramites)>0){
		$flujos=$ce->obtenerFlujosDelDocumento($conexion,$flujo[id_flujo]);

		$contador = 0;

		foreach($tramites as $key=>$fila)
		{

			if(!in_array($fila['identificador'],$perfiles))
				continue;
			if($fila['pendiente']!='A')			//solo ejecuta los protocolos A=Aprobados
				continue;

			$categoria = $fila['estado'];
			if($categoria==null)
				$categoria='solicitud';
			else
				$categoria = trim($categoria);

				$date=date_create($fila['fecha_inicio']);
				$flujoActual=new Flujo($flujos,$fila[id_flujo_documento]);
				$f=$flujoActual->Plazo();
				date_add($date,date_interval_create_from_date_string($f." days"));
				$fecha="F.limite: ".date_format($date,"Y-m-d");


				$contenido = '<article
							id="'.$fila['id_documento'].'"
							data-flujo="'.$flujo['id_flujo'].'"
							data-idOpcion="'.$flujo['id_fase'].'"
							data-nombre="'.$fila['id_tramite_flujo'].'"
							class="item"
							data-rutaAplicacion="ensayoEficacia"
							data-opcion="abrirAsignacionSupervisores"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<span>'.(strlen($fila['razon_social'])>45?(substr($fila['razon_social'],0,45).'...'):(strlen($fila['razon_social'])>0?$fila['razon_social']:'Empresa')).'</span>
							<span>'.(strlen($fila['plaguicida_nombre'])>45?(substr($fila['plaguicida_nombre'],0,45).'...'):(strlen($fila['plaguicida_nombre'])>0?$fila['plaguicida_nombre']:'X definir')).'</span><br/>
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