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
		<h1>Aprobación de Protocolos</h1>
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

	<div id="aprobado">
		<h2>Dossier aprobados</h2>
		<div class="elementos"></div>
	</div>
	<div id="rechazado">
		<h2>Dossier rechazados</h2>
		<div class="elementos"></div>
	</div>

	<?php

	$ce = new ControladorEnsayoEficacia();
	$cp = new ControladorDossierPecuario();

	//Miro que perfil es el usuario logeado
	$perfiles= $ce->obtenerPerfiles($conexion,$identificador);
	$perfil=new Perfil($perfiles);

	$estado='aprobado';
	$flujo=$ce->obtenerFlujo($conexion,$_SESSION['idAplicacion'],$estado);
	$fase=$flujo['id_fase'];
	if($fase==null)
		$fase=0;


	$yaDesplego=false;
	$tramites =array();
	$perfiles=array();
	$ts=array();
	$division=$ce->obtenerDivisionZonal($conexion,$identificador);

	$estado="'aprobado','rechazado'";
	if($perfil->EsOperador() ){

		$ts=$cp->obtenerFlujosDeTramitesDelOperadorDP($conexion,$identificador,$fase,'N');
		while($fila = pg_fetch_assoc($ts))
		{
			$tramites[]=$fila;
		}

	}
	else{
		$ts=$cp->obtenerTrámitesFinalesDP($conexion,$identificador,$fase,$estado,null,'N');
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
			$noRegistro='';
			if($categoria=='aprobado')
				$noRegistro=$fila['id_certificado'];
			else
				$noRegistro=$fila['id_expediente'];
			
			$fecha="";

			$contenido = '<article
							id="'.$fila['id_documento'].'"
							data-flujo="'.$flujo['id_flujo'].'"
							data-idOpcion="'.$flujo['id_fase'].'"
							data-nombre="'.$fila['id_tramite_flujo'].'"
							class="item"
							data-rutaAplicacion="dossierPecuario"
							data-opcion="abrirDossierAprobado"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<span>'.(strlen($fila['razon_social'])>45?(mb_substr($fila['razon_social'],0,45).'...'):(strlen($fila['razon_social'])>0?$fila['razon_social']:'Empresa')).'</span><br/>
							<span>'.(strlen($fila['plaguicida_nombre'])>45?(mb_substr($fila['nombre'],0,45).'...'):(strlen($fila['nombre'])>0?$fila['nombre']:'X definir')).'</span><br/>
							<span>'.(strlen($noRegistro)>45?(mb_substr($noRegistro,0,45).'...'):(strlen($noRegistro)>0?$noRegistro:'X definir')).'</span>
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

		$("#aprobado div> article").length == 0 ? $("#aprobado").remove():"";
		$("#rechazado div> article").length == 0 ? $("#rechazado").remove():"";
		

	});

</script>
</html>