<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$crs = new ControladorRevisionSolicitudesVUE();
$ci = new ControladorImportaciones();
$cc = new ControladorCatalogos();
$cce = new ControladorCertificados();

$idSolicitud = $_POST['id'];
$identificadorInspector = $_SESSION['usuario'];
$condicion = $_POST['opcion'];

$qImportacion = $ci->abrirImportacionEnviada($conexion, $idSolicitud);

$qDocumentos = $ci->abrirImportacionesArchivos($conexion, $idSolicitud);

$qAmpliacion = $ci->abrirImportacionesArchivoIndividual($conexion,$idSolicitud, 'PEDIDO DE AMPLIACION');

$qRegimenAduanero = $cc->obtenerNombreRegimenAduanero($conexion, $qImportacion[0]['regimenAduanero']);
$regimenAduanero = pg_fetch_result($qRegimenAduanero, 0, 'descripcion');

$qMoneda = $cc->obtenerNombreMoneda($conexion, $qImportacion[0]['moneda']);
$moneda = pg_fetch_result($qMoneda, 0, 'nombre');

$estadoActual = $qImportacion[0]['estadoImportacion'];



if($estadoActual == 'verificacion' || $estadoActual == 'verificacionVUE'){
	$qIdGrupo = $crs->buscarIdGrupo($conexion, $idSolicitud, 'Importación', 'Financiero');
	$idGrupo = pg_fetch_assoc($qIdGrupo);
	//Obtener monto a pagar
	$qDatosPago = $crs->buscarIdImposicionTasa($conexion, $idGrupo['id_grupo'], 'Importación', 'Financiero');
	$datosPago = pg_fetch_assoc($qDatosPago);
}

if($idGrupo['id_grupo'] != ''){
	$ordenPago = $cce->obtenerIdOrdenPagoXtipoOperacion($conexion, $idGrupo['id_grupo'], $idSolicitud, 'Importación');
}



if($condicion == 'pago'){
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$qImportacion[0]['identificador'].'-'.$estadoActual.'-Importación-tarifarioNuevo" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-idOpcion = "'.$qImportacion[0]['idVue'].'" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}else if ($condicion == 'verificacionVUE' && pg_num_rows($ordenPago)!=0){
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$qImportacion[0]['identificador'].'-'.$estadoActual.'-Importación-tarifarioNuevo" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-idOpcion = "'.$qImportacion[0]['idVue'].'" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}else if ($condicion == 'verificacion' && pg_num_rows($ordenPago) == 0){
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$qImportacion[0]['identificador'].'-pago-Importación-tarifarioAntiguo" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-idOpcion = "'.$qImportacion[0]['idVue'].'" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}else if ($condicion == 'verificacion' && pg_num_rows($ordenPago) != 0){
	$numeroOrdenPago = pg_fetch_result($ordenPago, 0, 'id_pago');
	echo '<input type="hidden" class= "abrirPago" id="'.$idSolicitud.'-'.$qImportacion[0]['identificador'].'-'.$estadoActual.'-Importación-'.$numeroOrdenPago.'" data-rutaAplicacion="financiero" data-opcion="finalizarMontoSolicitud" data-destino="ordenPago" data-idOpcion = "'.$qImportacion[0]['idVue'].'" data-nombre = "'.$idGrupo['id_grupo'].'"/>';
}


//Obtener datos de entidades bancarias
//$qEntidadesBancarias = $cc->listarEntidadesBancariasAgrocalidad($conexion);

//$fecha1= date('Y-m-d - H-i-s');
//$fecha = str_replace(' ', '', $fecha1);


?>

<header>
	<h1>Solicitud Importación</h1>
</header>
	
<div id="estado"></div>

<!-- div class="pestania"-->
	
	<fieldset>
			<legend>Certificado de Importación</legend>
			
			
			<div data-linea="1">
				<label>Tipo Certificado: </label> <?php echo $qImportacion[0]['tipoCertificado']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Razón social: </label> <?php echo $qImportacion[0]['razonSocial']; ?> <br/>
			</div>
			
			<div data-linea="3">
				<label>Representante legal: </label> <?php echo $qImportacion[0]['nombreRepresentante'] . ' ' . $qImportacion[0]['apellidoRepresentante']; ?> <br/>
			</div>
			
			<div data-linea="4">
				<label>Estado de solicitud: </label><?php echo ($qImportacion[0]['estadoImportacion']=='aprobado'? '<span class="exito">'.$qImportacion[0]['estadoImportacion'].'</span>': ($qImportacion[0]['estadoImportacion']=='enviado'?'<span class="alerta">Solicitud en revisión documental</span>':($qImportacion[0]['estadoImportacion']=='pago'? '<span class="alerta">Solicitud en proceso de pago</span>' :($qImportacion[0]['estadoImportacion']=='verificacion' ?'<span class="alerta">Solicitud en proceso de verificación sistema GUIA</span>':	($qImportacion[0]['estadoImportacion']=='verificacionVUE'?	'<span class="alerta">Solicitud en proceso de verificación de pago en sistema VUE</span>':	$qImportacion[0]['estadoImportacion']))))); ?>
			</div>

	</fieldset>
	
	<fieldset>
		<legend>Datos de Importación</legend>		
			<div data-linea="4">
				<label>Nombre exportador: </label> <?php echo $qImportacion[0]['nombreExportador']; ?> 
			</div>
			
			<div data-linea="10">
				<label>Dirección exportador: </label> <?php echo $qImportacion[0]['direccionExportador']; ?> 
			</div>
			
			<div data-linea="5">
				<label>País origen: </label> <?php echo $qImportacion[0]['paisExportacion']; ?> 
			</div>
			
			<div data-linea="5">
				<label>País embarque: </label> <?php echo $qImportacion[0]['paisEmbarque']; ?> 
			</div>
			
			<div data-linea="6">
				<label>Nombre embarcador: </label> <?php echo $qImportacion[0]['nombreEmbarcador']; ?> 
			</div>
			
			<div data-linea="7">
				<label>Régimen aduanero: </label> <?php echo $regimenAduanero; ?> 
			</div>
			
			<div data-linea="8">
				<label>Moneda: </label> <?php echo $moneda; ?> 
			</div>
			
			<div data-linea="8">
				<label>Medio transporte: </label> <?php echo $qImportacion[0]['tipoTransporte']; ?> 
			</div>
			
			<div data-linea="9">
				<label>Puerto embarque: </label> <?php echo $qImportacion[0]['puertoEmbarque']; ?> 
			</div>
			
			<div data-linea="9">
				<label>Puerto destino: </label> <?php echo $qImportacion[0]['puertoDestino']; ?> 
			</div>
			<div data-linea="10">
				<label>Licencia MAGAP: </label> <?php echo $qImportacion[0]['licenciaMagap']; ?>
			</div>
	</fieldset>
	
	
	<?php 
	//IMPRESION DE DOCUMENTOS
	$i=1;
	if(count($qDocumentos)>0){
		
		echo'<div id="documentos" >
					<fieldset>
						<legend>Documentos adjuntos</legend>
				
								<table>
									<tr>
										<td><label>#</label></td>
										<td><label>Nombre</label></td>
										<td><label>Enlace</label></td>
									</tr>';


			foreach ($qDocumentos as $documento){
					echo '<tr>
						  	<td>'.$i.'</td>
							<td>'.$documento['tipoArchivo'].'</td>
							<td>
								<form id="f_'.$i.'" action="aplicaciones/general/accederDocumentoFTP.php" method="post" enctype="multipart/form-data" target="_blank">
									<input name="rutaArchivo" value="'.$documento['rutaArchivo'].'" type="hidden">
									<input name="nombreArchivo" value="'.$documento['tipoArchivo'].'.pdf" type="hidden">
									<input name="idVue" value="'.$documento['idVue'].'" type="hidden">
									<button type="submit" name="boton">Descargar</button>
								</form>
							</td>
						 </tr>';
					$i++;
			}

	echo '</table>
			</fieldset>
			</div>';
		
	}
	
	$i=1;
	foreach ($qImportacion as $importacion){
		echo '
		<fieldset>
			<legend>Producto de importación ' . $i . '</legend>';
		
		$qProductoTipoSubtipo = $cc->obtenerTipoSubtipoXProductos($conexion, $importacion['idProducto']);
		$productoTipoSubtipo = pg_fetch_assoc($qProductoTipoSubtipo);
		
		echo '<div data-linea="3">	
					<label>Tipo producto: </label> ' . $productoTipoSubtipo['nombre_tipo'] . ' <br/>
				</div>
				<div data-linea="4">	
					<label>Subtipo producto: </label> ' . $productoTipoSubtipo['nombre_subtipo'] . ' <br/>
				</div>
				<div data-linea="5">	
					<label>Nombre del producto: </label> ' . $importacion['nombreProducto'] . ' <br/>
				</div>
				<div data-linea="5">	
					<label>presentación producto: </label> ' . $importacion['presentacion'] . ' <br/>
				</div>
				<div data-linea="6">	
					<label>Partida arancelaria: </label> ' . $importacion['partidaArancelaria'] . ' <br/>
				</div>
				<div data-linea="7">
					<label>Cantidad: </label> ' . $importacion['unidad'] . ' '.$importacion['unidadMedida']. ' <br/>
				</div>
				<div data-linea="7">
					<label>Peso neto: </label> ' . $importacion['peso'] . ' kgs <br/>
				</div>
				<div data-linea="8">
					<label>Valor FOB: </label> ' . $importacion['valorFob'] . ' <br/>
				</div>
				<div data-linea="8">
					<label>Valor CIF: </label> ' . $importacion['valorCif'] . ' <br/>
				</div>
				<div data-linea="9">
					<label>Registro Semillas: </label> ' . $importacion['registroSemillas'] . ' <br/>
				</div>';
				
				
		echo '</fieldset>';
		
		$i++;
	}
	
	
?>	

<div id="ordenPago"></div>

<script type="text/javascript">
var estado= <?php echo json_encode($qImportacion[0]['estadoImportacion']); ?>;
//var banco = < ?php echo json_encode($datosPago['codigo_banco']);?>;

	$(document).ready(function(){
		abrir($(".abrirPago"),null,false);
		distribuirLineas();		
	});


</script>