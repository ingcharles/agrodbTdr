<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEventoSanitario.php';
	
	$conexion = new Conexion();
	$cnes = new ControladorEventoSanitario();
	
	$numSolicitud = htmlspecialchars ($_POST['bNumSolicitud'],ENT_NOQUOTES,'UTF-8');
	$fecha = htmlspecialchars ($_POST['bFechaCreacion'],ENT_NOQUOTES,'UTF-8');
	$nombrePredio = htmlspecialchars ($_POST['bNombrePredio'],ENT_NOQUOTES,'UTF-8');
	$nombrePropietario = htmlspecialchars ($_POST['bNombrePropietario'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['bIdProvincia'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['bIdCanton'],ENT_NOQUOTES,'UTF-8');
	$idParroquia = htmlspecialchars ($_POST['bIdParroquia'],ENT_NOQUOTES,'UTF-8');
	$sitio = htmlspecialchars ($_POST['bSitio'],ENT_NOQUOTES,'UTF-8');
	$tipo = htmlspecialchars ($_POST['bTipo'],ENT_NOQUOTES,'UTF-8');
	$sindrome = htmlspecialchars ($_POST['bSindrome'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['bEstado'],ENT_NOQUOTES,'UTF-8');
	$idLaboratorio = htmlspecialchars ($_POST['bLaboratorioUsuario'],ENT_NOQUOTES,'UTF-8');
	$idLabSelec = htmlspecialchars ($_POST['bIdLaboratorio'],ENT_NOQUOTES,'UTF-8');
	
	//Función buscarEventoSanitario
	$eventoSanitario = $cnes->buscarEventoSanitarioLaboratorioFiltrado($conexion, $numSolicitud, $fecha, $idProvincia,
									$idCanton, $idParroquia, $sitio, $nombrePredio,$estado, $sindrome, $idLabSelec);
		
	while($fila = pg_fetch_assoc($eventoSanitario)){
		
		echo '<article 
					id="'.$fila['id_evento_sanitario'].'"
					class="item"
					data-rutaAplicacion="seguimientoEventosSanitarios"
					data-opcion="abrirEventoSanitarioLaboratorios" 
					ondragstart="drag(event)" 
					draggable="true" 
					data-destino="detalleItem">
				<span class="ordinal">'.++$contador.'</span>
				<span><b>'.$fila['numero_formulario'].'</b></span><br/>
				<small><span><b> Predio: </b>'.$fila['nombre_predio'].'</span><br /></small>
				<small><span><b> Patología: </b>'.$fila['sindrome_presuntivo'].'</span><br /></small>
						<small><span><b> Provincia: </b>'.$fila['provincia'].'</span><br /></small>
				<aside><small><b> Creación:</b>'.date('j/n/Y',strtotime($fila['fecha'])).'<br />
				</small></aside>
			</article>';
	}
?>