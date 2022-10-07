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

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<header>
		<h1>Solicitud de Protocolo</h1>
		<nav>
			<?php
			
			$testOperacion=$ce->testAccesoPermitido($conexion,$_SESSION['usuario'],'IAP','EE_OPERA');

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

	
	

	<?php
	include 'listarProtocoloEstados.php';

		if($testOperacion){

			$res = $ce->listarProtocolosOperador($conexion, $_SESSION['usuario']);
			$protocolos=array();
			while($fila = pg_fetch_assoc($res)){
				$protocolos[]=$fila;
			}

			//revisa los factibles de modificar
			$protocolosModificar=$ce->obtenerProtocolosParaModificar($conexion,$_SESSION['usuario']);
			foreach($protocolosModificar as $fila){
				$fila['estado']='modificacion';
				$protocolos[]=$fila;
			}

			$idFlujo=$_SESSION['idAplicacion'];

			$flujosDocumento=$ce->obtenerFlujosDelDocumento($conexion,$idFlujo);
			$flujoActual=new Flujo($flujosDocumento,'EP','cultivo_menor',$idFlujo);
			$flujoActual->InicializarFlujo('t');
			$contador = 0;
			
			foreach($protocolos as $fila)
			{
				$categoria = $fila['estado'];
				if($categoria==null)
					$categoria='solicitud';
				else
					$categoria=trim($categoria);

				$paginaSiguiente='';
				$tipoDocumento='EP';

				$fechaTiempo=null;

				switch($categoria){
					case 'solicitud':
						$paginaSiguiente='abrirSolicitudProtocolo';
						$intervalo=15;
						$fechaTiempo=new DateTime($fila['fecha_solicitud']);
						$fechaTiempo->add(new DateInterval('P'.$intervalo.'D'));		//AÑADE plazo DIAS
						break;
					case 'pago':
						/*$paginaSiguiente='abrirPagoProtocolo';
						$fechaTiempo=new DateTime($fila['fecha_fin']);
						break;*/
					case 'verificacionProtocolo':
						$paginaSiguiente='abrirProtocoloBloqueado';
						$fechaTiempo=new DateTime($fila['fecha_fin']);
						break;
					case 'subsanarProtocolo':
						$paginaSiguiente='abrirSubsanacionProtocolo';
						$fechaTiempo=new DateTime($fila['fecha_fin']);
						break;
					case 'verificacion':
					case 'inspeccion':
					case 'aprobarProtocoloDir':
					case 'aprobarProtocoloCor':
					case 'verificacionInforme':
					case 'subsanarInforme':
					case 'aprobarInformeDir':
					case 'aprobarInformeCor':					
						$paginaSiguiente='abrirProtocoloBloqueado';
						$fechaTiempo=new DateTime($fila['fecha_fin']);
						break;
					case 'elegirOrganismo':
						$paginaSiguiente='abrirAsignacionOrganismo';
						$fechaTiempo=new DateTime($fila['fecha_fin']);
						break;
					case 'modificacion':
						$paginaSiguiente='abrirModificarProtocolo';
						$fechaTiempo=new DateTime($fila['fecha_fin']);
						break;
				

				}
				

				$fecha='';
				if($fechaTiempo!=null){
					$fechaActual=new DateTime();
					if($fechaActual<$fechaTiempo)
						$fecha ='F.límite:'.$fechaTiempo->format('Y-m-d');
					else
						$fecha ='<font color="red">'.'F.límite:'.$fechaTiempo->format('Y-m-d').'</font>';
				}

				$contenido = '<article
							id="'.$fila['id_protocolo'].'"
							data-flujo="'.$idFlujo.'"
							data-idOpcion="'.$tipoDocumento.'"
							class="item"
							data-rutaAplicacion="ensayoEficacia"
							data-opcion="'.$paginaSiguiente.'"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<span class="ordinal">'.++$contador.'</span>
							<span>'.(strlen($fila['plaguicida_nombre'])>45?(substr($fila['plaguicida_nombre'],0,45).'...'):(strlen($fila['plaguicida_nombre'])>0?$fila['plaguicida_nombre']:'Por definir nombre')).'</span><br/>
							<span>'.(strlen($fila['id_expediente'])>45?(substr($fila['id_expediente'],0,45).'...'):(strlen($fila['id_expediente'])>0?$fila['id_expediente']:'no expediente')).'</span>'.'</span><br/>
							<span>'.'Solicitud No:'.$fila['id_protocolo'].'</span>
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

<script type="text/javascript" src="aplicaciones/ensayoEficacia/funciones/listarProtocoloEstados.js"></script>

</html>