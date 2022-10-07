<?php 
	session_start();
	
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorUsuarios.php';
	
	$_SESSION['tipo'] = 'especifico';
	$conexion = new Conexion();
	$cu = new ControladorUsuarios();
	
	$arrayPerfil=array('PFL_SANID_ANIMA','PFL_SANID_VEGET','PFL_LABORATORIO','PFL_INOCU_ALIME','PFL_INSUM_PLAGU','PFL_INSUM_VETER','PFL_INSUM_PRO_AU');
	$banderaPerfil=false;
	foreach ($arrayPerfil as $codificacionPerfil ){
	
		$qVerificarUsuario=$cu->verificarUsuario($conexion, $_SESSION['usuario'],$codificacionPerfil);
		if(pg_num_rows($qVerificarUsuario)>0){
			$banderaPerfil=true;
			switch ($codificacionPerfil){
				case 'PFL_SANID_ANIMA':		
					$areaTematica.= "'SA',";
				break;
						
				case 'PFL_SANID_VEGET':
					$areaTematica.="'SV',";
				break;
						
				case 'PFL_LABORATORIO':
					$areaTematica.="'LT',";
				break;
				
				case 'PFL_INOCU_ALIME':
					$areaTematica.="'AI',";
				break;
	
				case 'PFL_INSUM_PLAGU':
					$areaTematica.="'IAP',";
				break;
	
				case 'PFL_INSUM_VETER':
					$areaTematica.="'IAV',";
					break;
					
				case 'PFL_INSUM_FERTIL':
					$areaTematica.="'IAF',";
				break;
				
				case 'PFL_INSUM_PRO_AU':
					$areaTematica.="'IAPA',";
				break;
			}
			
			
		}
	}
	
	if(!$banderaPerfil)
		$areaTematica="''";


?>



<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<header>
		<h1>Requisitos</h1>
		<nav>

		<?php 
			$ca = new ControladorAplicaciones();
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
<?php   if(!$banderaPerfil){ echo '<pre><center><label class="alerta">El técnico aún no tiene asignado ningún perfil</label></center></pre>';}else{	?>	
	<table id="Importacion">
		<thead>
			<tr>
				<th colspan="5">
					<input id="mostrarListaImportacion" for="listaImportacion" type="checkbox" checked />
					<label id="listaImportacion">Requisitos de importación</label>
				</th>
			</tr>
			<tr>
				<th>#</th>
				<th>Código</th>
				<th>Descripción</th>
				<th>Área</th>
				<th>Estado</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	
	<table id="Exportacion">
		<thead>
			<tr>
				<th colspan="5">
					<input id="mostrarListaExportacion" for="listaExportacion" type="checkbox" checked />
					<label id="listaExportacion">Requisitos de exportación</label>
				</th>
			</tr>
			<tr>
				<th>#</th>
				<th>Código</th>
				<th>Descripción</th>
				<th>Área</th>
				<th>Estado</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	
	<table id="Transito">
		<thead>
			<tr>
				<th colspan="5">
					<input id="mostrarListaTransito" for="listaTransito" type="checkbox" checked />
					<label id="listaTransito">Requisitos de tránsito</label>
				</th>
			</tr>
			<tr>
				<th>#</th>
				<th>Código</th>
				<th>Descripción</th>
				<th>Área</th>
				<th>Estado</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	
	<table id="Movilizacion">
		<thead>
			<tr>
				<th colspan="5">
					<input id="mostrarListaMovilizacion" for="listaMovilizacion" type="checkbox" checked />
					<label id="listaMovilizacion">Requisitos de movilización</label>
				</th>
			</tr>
			<tr>
				<th>#</th>
				<th>Código</th>
				<th>Descripción</th>
				<th>Área</th>
				<th>Estado</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	<?php }
	 
		$cr = new ControladorRequisitos();
		$res = $cr->listarRequisitosXidArea($conexion,"(" . rtrim ( $areaTematica, ',' ) . ")");
		$contador = 0;
		while($requisito = pg_fetch_assoc($res)){
			if($requisito['tipo']=='Importación')
				$categoria = 'Importacion';
			if($requisito['tipo']=='Exportación')
				$categoria = 'Exportacion';			
			if($requisito['tipo']=='Tránsito')
				$categoria = 'Transito';
			if($requisito['tipo']=='Movilización')
			    $categoria = 'Movilizacion';
			
			$contenido = '<tr 
								id="'.$requisito['id_requisito'].'"
								class="item"
								data-rutaAplicacion="administracionRequisitos"
								data-opcion="abrirRequisito" 
								ondragstart="drag(event)" 
								draggable="true" 
								data-destino="detalleItem">
							<td>'.++$contador.'</td>
							<td>'.$requisito['codigo'].'</td>
							<td>'.$requisito['nombre'].'</td>
							<td>'.$requisito['area'].'</td>
							<td><span class="n'.($requisito['estado']==1?'Aprobado':'Rechazado').'"></span></td>			
						</tr>';		
	?>   
	<script type="text/javascript">
			var contenido = <?php echo json_encode($contenido);?>;
			var categoria = <?php echo json_encode($categoria);?>;
			$("#"+categoria+" tbody").append(contenido);
	</script>
	<?php 				
		}
	?>	
</body>
<script>
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#Importacion tbody tr").length == 0 ? $("#Importacion").remove():"";
		$("#Exportacion tbody tr").length == 0 ? $("#Exportacion").remove():"";
		$("#Transito tbody tr").length == 0 ? $("#Transito").remove():"";
		$("#Movilizacion tbody tr").length == 0 ? $("#Movilizacion").remove():"";
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí una requisito para revisarlo.</div>');
	});
	
	$("#mostrarListaImportacion").change(function () {
        if ($(this).is(':checked'))
            $("#Importacion tbody tr").show();
        else
            $("#Importacion tbody tr").hide();
    });
	
	$("#mostrarListaExportacion").change(function () {
        if ($(this).is(':checked'))
            $("#Exportacion tbody tr").show();
        else
            $("#Exportacion tbody tr").hide();
    });

	$("#mostrarListaTransito").change(function () {
        if ($(this).is(':checked'))
            $("#Transito tbody tr").show();
        else
            $("#Transito tbody tr").hide();
    });
	
	$("#mostrarListaMovilizacion").change(function () {
        if ($(this).is(':checked'))
            $("#Movilizacion tbody tr").show();
        else
            $("#Movilizacion tbody tr").hide();
    });
</script>
</html>