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
		<h1>Aprobación de Dossier</h1>
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

	<div id="aprobarDirector">
		<h2>Dossier por aprobar Director</h2>
		<div class="elementos"></div>
	</div>
	<div id="aprobarCoordinador">
		<h2>Dossier por aprobar Coordinador</h2>
		<div class="elementos"></div>
	</div>

	<?php

	$ce = new ControladorEnsayoEficacia();
	$cp = new ControladorDossierPecuario();

	//Miro que perfil es el usuario logeado
	$perfiles= $ce->obtenerPerfiles($conexion,$identificador);
	$perfil=new Perfil($perfiles);

	$yaDesplego=false;
	$tramites =array();
	$perfiles=array();
	$ts=array();
	$division=$ce->obtenerDivisionZonal($conexion,$identificador);
	
	$estado='aprobarDirector';
	$flujo=$ce->obtenerFlujo($conexion,$_SESSION['idAplicacion'],$estado);
	$fase=$flujo['id_fase'];

	$perfilActual='PFL_EE_DDTA';
	if($perfil->tieneEstePerfil($perfilActual) ){
		//Los tramites
		/*INICIO EJAR
		 * ($division=='DIV_PICH')
			$ts=$cp->obtenerFlujosDeTramitesAsignarDossierDP($conexion,$identificador,$fase,$estado,$perfilActual,'N','not',"'RIP-FAR','RIP-BIO','RIP-KD'");
		else
			$ts=$cp->obtenerFlujosDeTramitesAsignarDossierDP($conexion,$identificador,$fase,$estado,$perfilActual,'N');*/
			
		$ts=$cp->obtenerFlujosDeTramitesAsignarDossierDP($conexion,$identificador,$fase,$estado,$perfilActual,'N');
		/*FIN EJAR*/
		while($fila = pg_fetch_assoc($ts))
		{
			$tramites[]=$fila;
		}

	}
	$perfilActual='PFL_DP_DRIP';
	if($perfil->tieneEstePerfil($perfilActual) ){
		if($division=='DIV_PICH'){
			$ts=$cp->obtenerFlujosDeTramitesAsignarDossierDP($conexion,$identificador,$fase,$estado,$perfilActual,'N','',"'RIP-FAR','RIP-BIO','RIP-KD'");
			while($fila = pg_fetch_assoc($ts))
			{
				$tramites[]=$fila;
			}
		}
	}

	
	$perfilActual='PFL_EE_CRIA';
	if($perfil->tieneEstePerfil('PFL_EE_CRIA') ){
		$estado='aprobarCoordinador';
		$flujo=$ce->obtenerFlujo($conexion,$_SESSION['idAplicacion'],$estado);
		$fase=$flujo['id_fase'];
		if($division=='DIV_PICH'){
			$ts=$cp->obtenerFlujosDeTramitesAsignarDossierDP($conexion,$identificador,$fase,$estado,$perfilActual,'N','',"'RIP-FAR','RIP-BIO','RIP-KD'");
			while($fila = pg_fetch_assoc($ts))
			{
				$tramites[]=$fila;
			}
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
							data-opcion="abrirAprobacionDossier"
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

		$("#aprobarDirector div> article").length == 0 ? $("#aprobarDirector").remove():"";
		$("#aprobarCoordinador div> article").length == 0 ? $("#aprobarCoordinador").remove():"";
		

	});

</script>
</html>