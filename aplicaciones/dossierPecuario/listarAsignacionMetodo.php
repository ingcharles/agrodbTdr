<?php
	session_start();
	require_once '../../clases/Conexion.php';
	
	require_once '../../clases/ControladorAplicaciones.php';
	
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierPecuario.php';

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
	
	<div id="asignarDirector">
		<h2>Para asignar trámites al director</h2>
		<div class="elementos"></div>
	</div>
	
	<div id="asignarTecnicoIngreso">
		<h2>Para asignar trámites a los técnicos de Sanidad Animal</h2>
		<div class="elementos"></div>
	</div>
	<div id="analizarIngreso">
		<h2>Solicitudes por analizar ingreso al país</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobarIngresoDirector">
		<h2>Solicitudes por aprobar ingreso al país</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobarIngresoCoordinador">
		<h2>Solicitudes por aprobar ingreso al país</h2>
		<div class="elementos"></div>
	</div>
	<div id="asignarTecnicoMetodo">
		<h2>Para asignar el análisis del método analítico al técnico de laboratorio</h2>
		<div class="elementos"></div>
	</div>
	
	

	<?php

	$ce = new ControladorEnsayoEficacia();
	$cp=new ControladorDossierPecuario();
	//Miro si perfil es Director
	$perfiles= $ce->obtenerPerfiles($conexion,$identificador);

	$perfil=new Perfil($perfiles);

	$estado='asignarTecnicoMetodo';
	$flujo=$ce->obtenerFlujo($conexion,$_SESSION['idAplicacion'],$estado);

	$yaDesplego=false;
	$tramites =array();

	$perfilDirector='PFL_DP_DDIACIA';
	if($perfil->tieneEstePerfil($perfilDirector)){
		$ts=$cp->obtenerFlujosDeTramitesCentralesParaAsingnarDP($conexion,$identificador,$flujo['id_fase'],$perfilDirector,'N');
		while($fila = pg_fetch_assoc($ts))
		{
			$tramites[]=$fila;
		}
	}

	if(sizeof($tramites)>0){
		$flujos=$ce->obtenerFlujosDelDocumento($conexion,$flujo[id_flujo]);

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
							data-rutaAplicacion="dossierPecuario"
							data-opcion="abrirAsignacionTecnicoLaboratorio"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<span>'.(strlen($fila['razon_social'])>45?(mb_substr($fila['razon_social'],0,45).'...'):(strlen($fila['razon_social'])>0?$fila['razon_social']:'Empresa')).'</span><br/>
							<span>'.(strlen($fila['nombre'])>45?(mb_substr($fila['nombre'],0,45).'...'):(strlen($fila['nombre'])>0?$fila['nombre']:'X definir')).'</span><br/>
							<span>'.(strlen($fila['id_expediente'])>45?(mb_substr($fila['id_expediente'],0,45).'...'):(strlen($fila['id_expediente'])>0?$fila['id_expediente']:'X definir')).'</span>
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
		$("#asignarDirector div> article").length == 0 ? $("#asignarDirector").remove():"";
		$("#asignarTecnicoIngreso div> article").length == 0 ? $("#asignarTecnicoIngreso").remove():"";
		$("#analizarIngreso div> article").length == 0 ? $("#analizarIngreso").remove():"";
		$("#aprobarIngresoDirector div> article").length == 0 ? $("#aprobarIngresoDirector").remove():"";
		$("#aprobarIngresoCoordinador div> article").length == 0 ? $("#aprobarIngresoCoordinador").remove():"";
		$("#asignarTecnicoMetodo div> article").length == 0 ? $("#asignarTecnicoMetodo").remove():"";

	});

</script>
</html>