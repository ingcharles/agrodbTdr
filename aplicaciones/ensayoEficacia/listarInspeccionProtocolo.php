<?php
	session_start();
	require_once '../../clases/Conexion.php';

	require_once '../../clases/ControladorAplicaciones.php';

	require_once '../../clases/ControladorEnsayoEficacia.php';

	require_once './clases/Perfil.php';
	require_once './clases/Flujo.php';

	$conexion = new Conexion();

	$ca = new ControladorAplicaciones();
	$ce = new ControladorEnsayoEficacia();

	$identificador=$_SESSION['usuario'];

	$ce = new ControladorEnsayoEficacia();

	$perfiles= $ce->obtenerPerfiles($conexion,$identificador);
	$perfil=new Perfil($perfiles);


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<header>
		<h1>Protocolos en Inspección/Supervición</h1>
		<nav>
			<?php
			$testOperacion=false;
			if($perfil->esOperador())
				$testOperacion=$ce->testAccesoPermitido($conexion,$_SESSION['usuario'],'IAP','EE_OPERA');
			else
				$testOperacion=true;
			if(!$testOperacion){
				if($perfil->tieneEstePerfil('PFL_EE_OI') || $perfil->tieneEstePerfil('PFL_EE_SE')){
					$testOperacion=true;
				}
			}
			if($testOperacion){
				$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
				while($fila = pg_fetch_assoc($res)){
					echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
						>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
				}
			}
			?>
		</nav>
</header>

	<div id="inspeccion">
		<h2>En inspeccion / supervisión</h2>
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
		<h2 id="textoAprobado">Protocolos aprobados</h2>
		<div class="elementos"></div>
	</div>

	<?php

	
	
	if($testOperacion){



		$estado='inspeccion';

		$flujo=$ce->obtenerFlujo($conexion,$_SESSION['idAplicacion'],$estado);


		$tramites =array();

		$perfilUsuario='PFL_EE_SE';
		if($perfil->tieneEstePerfil($perfilUsuario)){
			$ts=$ce->obtenerInformesFinalesPorEstado($conexion,$identificador,$flujo['id_fase'],$estado,$perfilUsuario,'N');
			while($fila = pg_fetch_assoc($ts))
			{
					$tramites[]=$fila;
			}
		}
		$perfilUsuario='PFL_EE_OI';
		if($perfil->tieneEstePerfil($perfilUsuario)){
			$ts=$ce->obtenerInformesFinalesDeOrganismosPorEstado($conexion,$identificador,$flujo['id_fase'],$estado,$perfilUsuario,'N');
			while($fila = pg_fetch_assoc($ts))
			{
					$tramites[]=$fila;
			}
		}
		if($perfil->esOperador()){
			$ts=$ce->obtenerInformesFinalesDelOperador($conexion,$identificador,'N');
			while($fila = pg_fetch_assoc($ts))
			{
				$tramites[]=$fila;
			}
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

				$paginaSiguiente='';
				switch($categoria){
					case 'solicitud':
						$paginaSiguiente='abrirSolicitudProtocolo';
						break;
					case 'pago':
						/*$paginaSiguiente='abrirPagoProtocolo';
						break;*/
					case 'verificacionProtocolo':
						$paginaSiguiente='abrirProtocoloBloqueado';
						break;
					case 'subsanarProtocolo':
						$paginaSiguiente='abrirSubsanacionProtocolo';
						break;
					case 'inspeccion':
						$paginaSiguiente='abrirInspeccionProtocolo';
						break;

					case 'aprobarProtocoloDir':
					case 'aprobarProtocoloCor':
						$paginaSiguiente='abrirProtocoloBloqueado';
						break;
					case 'verificacionInforme':
					case 'aprobarInformeDir':
					case 'aprobarInformeCor':
						$paginaSiguiente='abrirInformeBloqueado';
						break;
					case 'subsanarInforme':
						$paginaSiguiente='abrirSubsanacionInforme';
						break;
					case 'aprobado':
						$paginaSiguiente='abrirInformesAprobados';
						break;
					case 'elegirOrganismo':
						$paginaSiguiente='abrirAsignacionOrganismo';
						break;

				}


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
								data-idOpcion="'.$fila['provincia'].'"
								data-nombre="'.$fila['id_tramite_flujo'].'"
								class="item"
								data-rutaAplicacion="ensayoEficacia"
								data-opcion="'.$paginaSiguiente.'"
								ondragstart="drag(event)"
								draggable="true"
								data-destino="detalleItem">
								<span class="ordinal">'.++$contador.'</span>
								<span>'.(strlen($fila['razon_social'])>45?(substr($fila['razon_social'],0,45).'...'):(strlen($fila['razon_social'])>0?$fila['razon_social']:'Empresa')).'</span><br/>
								<span>'.(strlen($fila['plaguicida_nombre'])>45?(substr($fila['plaguicida_nombre'],0,45).'...'):(strlen($fila['plaguicida_nombre'])>0?$fila['plaguicida_nombre']:'X definir')).'</span><br/>
								<span>'.(strlen($fila['inf_id_expediente'])>45?(substr($fila['inf_id_expediente'],0,45).'...'):(strlen($fila['inf_id_expediente'])>0?$fila['inf_id_expediente']:'X definir')).'</span>
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
	}
    ?>	
</body>


<script type="text/javascript">
	$("document").ready(function (){
		$('#textoAprobado').text("Informes finales aprobados");
		$("#listadoItems").addClass("comunes");
		$("#listadoItems").addClass("lista");
		 $("#inspeccion div> article").length == 0 ? $("#inspeccion").remove() : "";
		 $("#verificacionInforme div> article").length == 0 ? $("#verificacionInforme").remove() : "";
		 $("#subsanarInforme div> article").length == 0 ? $("#subsanarInforme").remove() : "";
		 $("#aprobarInformeDir div> article").length == 0 ? $("#aprobarInformeDir").remove() : "";
		 $("#aprobarInformeCor div> article").length == 0 ? $("#aprobarInformeCor").remove() : "";
		$("#aprobado div> article").length == 0 ? $("#aprobado").remove() : "";

	});
</script>

</html>