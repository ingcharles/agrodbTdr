<?php
	session_start();
	require_once '../../clases/Conexion.php';

	require_once '../../clases/ControladorAplicaciones.php';

	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierFertilizante.php';

	require_once '../ensayoEficacia/clases/Perfil.php';
	require_once '../ensayoEficacia/clases/Flujo.php';

	$conexion = new Conexion();

	$ca = new ControladorAplicaciones();
	$ce = new ControladorEnsayoEficacia();

	$identificador=$_SESSION['usuario'];

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<header>
		<h1>Dossier aprobados</h1>
		<nav>
			<?php

			//identifico el perfil de la aprobacion
			$perfiles= $ce->obtenerPerfiles($conexion,$usuario);
			$perfil=new Perfil($perfiles);
			$testOperacion=false;
			if($perfil->EsOperador()){
				$testOperacion=$ce->testAccesoPermitido($conexion,$_SESSION['usuario'],'IAF','DF_OPERA');
			}
			else
				$testOperacion=true;




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

	<div id="aprobado">
		<h2>Dossier aprobados</h2>
		<div class="elementos"></div>
	</div>
	<div id="rechazado">
		<h2>Dossier rechazados</h2>
		<div class="elementos"></div>
	</div>

	<?php
	if($testOperacion){


		$cf = new ControladorDossierFertilizante();

		//Miro que perfil es el usuario logeado
		$perfiles= $ce->obtenerPerfiles($conexion,$identificador);
		$perfil=new Perfil($perfiles);

		$estado='aprobado';
		$aplicacion=$cf->obtenerFlujoEquivalente($conexion,'PRG_DOSSIER_PLA');
		$flujo=$ce->obtenerFlujo($conexion,$aplicacion,$estado);

		$yaDesplego=false;
		$tramites =array();
		$perfiles=array();
		$ts=array();
		$division=$ce->obtenerDivisionZonal($conexion,$identificador);

		$fase=$flujo['id_fase'];


		if($perfil->EsOperador() ){

			
			$ts=$cf->obtenerFlujosDeTramitesDelOperadorDF($conexion,$identificador,$fase,'A');
			while($fila = pg_fetch_assoc($ts))
			{
				$tramites[]=$fila;
			}

		}
		else{
			$ts=$cf->obtenerFlujosDeTramitesAsignarDossierDF($conexion,null,$fase,$estado,null,'N');
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
				
				$fecha="";

				$contenido = '<article
							id="'.$fila['id_documento'].'"
							data-flujo="'.$flujo['id_flujo'].'"
							data-idOpcion="'.$flujo['id_fase'].'"
							data-nombre="'.$fila['id_tramite_flujo'].'"
							class="item"
							data-rutaAplicacion="dossierFertilizante"
							data-opcion="abrirDossierAprobado"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<span>'.(strlen($fila['razon_social'])>45?(substr($fila['razon_social'],0,45).'...'):(strlen($fila['razon_social'])>0?$fila['razon_social']:'Empresa')).'</span><br/>
							<span>'.(strlen($fila['plaguicida_nombre'])>45?(substr($fila['nombre'],0,45).'...'):(strlen($fila['nombre'])>0?$fila['nombre']:'X definir')).'</span><br/>
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