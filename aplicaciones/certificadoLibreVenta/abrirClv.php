<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorClv.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';

require_once '../general/administrarArchivoFTP.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cr = new ControladorRegistroOperador();
$cl  = new ControladorClv();
$crs = new ControladorRevisionSolicitudesVUE();

$cClv	  = $cl->listarCertificados($conexion,$_POST['id']);
$dClv     = $cl->listarDetalleCertificados($conexion,$_POST['id']);
$dcClv	  = $cl->listarDocumentos($conexion,$_POST['id']);

$cTitular = $cr->buscarOperador($conexion,$cClv[0]['idTitular']);

//Obtener monto a pagar
$qMonto = $crs->obtenerMontoSolicitud($conexion, $_POST['id'], 'CLV');
?>

<header>
	<h1>Certificado de libre venta</h1>
</header>

	<fieldset id="resultado">
		<legend>Resultado de Inspección</legend>
			<div data-linea="1">
				<label>Resultado: </label> 
				<?php echo ($cClv[0]['estado']=='aprobado'? '<span class="exito">'.$cClv[0]['estado'].'</span>':'<span class="alerta">'.$cClv[0]['estado'].'</span>');?>
			</div>
			
			<div data-linea="2">
				<label>Observaciones: </label> <?php echo $cClv[0]['observacion']; ?> <br/>
			</div>
	</fieldset>
			
	<?php 
		if($cClv[0]['idVue'] != ''){
			echo '<fieldset>
				<legend>Información de la Solicitud</legend>
					<div data-linea="1">
						<label>Identificación VUE: </label> '. $cClv[0]["idVue"] .'
					</div>
			</fieldset>';
		}
	?>
	<fieldset>
		<legend>Información del titular</legend>
			<div data-linea="3">
				<label>RUC / Cédula: </label> <?php echo pg_fetch_result($cTitular, 0, 'identificador'); ?> 
			</div>
			
			<div data-linea="4">
				<label>Nombre: </label> <?php echo pg_fetch_result($cTitular, 0, 'nombre_representante') . ' ' . pg_fetch_result($cTitular, 0, 'apellido_representante'); ?>
			</div>
			
			<div data-linea="7">
				<label>Provincia: </label> <?php echo pg_fetch_result($cTitular, 0, 'provincia'); ?>
			</div>
			
			<div data-linea="7">
				<label>Cantón: </label> <?php echo pg_fetch_result($cTitular, 0, 'canton'); ?>
			</div>
			
			<div data-linea="8">
				<label>Parroquia: </label> <?php echo pg_fetch_result($cTitular, 0, 'parroquia'); ?>
			</div>
			
			<div data-linea="9">
				<label>Dirección: </label> <?php echo pg_fetch_result($cTitular, 0, 'direccion'); ?> 
			</div>
	</fieldset>	
	
	<fieldset id="informacionOperador">
			<legend>Información Operador</legend>
			
			<div data-linea="9">
				<label>Nombre Operador: </label> <?php echo $cClv[0]['nombreDatoCertificado']; ?> 
			</div>
			
			<div data-linea="10">
				<label>Dirección Operador: </label> <?php echo $cClv[0]['direccionDatoCertificado']; ?> 
			</div>
			
	</fieldset>		
	
	<fieldset id="informacionProductoClv">
			 <legend>Información del producto <?php echo ($cClv[0]['tipoProducto'] == 'IAV'?'Veterinario':'Plaguicida'); ?></legend>	
			    <div data-linea="14">
					<label>Tipo de producto: </label> <?php echo ($cClv[0]['tipoProducto'] == 'IAV'?'Veterinario':'Plaguicida'); ?> 
				</div>
				<div data-linea="14">
					<label>Tipo operación: </label> <?php echo $cClv[0]['tipoDatoCertificado']; ?> 
				</div>		
			 	<div data-linea="15">								
					<label>Producto: </label> <?php echo $cClv[0]['nombre_producto']; ?>
				</div>
				<div data-linea="16">
				    <label>Subpartida: </label> <?php echo $pClv[0]['subpartida']; ?>	
				 </div>
				 
				<?php 
					 if ($cClv[0]['tipoProducto'] == 'IAP'){
				  		echo '<div data-linea="17">
								<label>Formulación VUE: </label>' .$pClv[0]['formulacion'] .
							'</div>
							<div data-linea="17">
								<label>Formulación GUIA: </label>' .$pClv[0]['formulacionGuia'] .
							'</div>
							<div data-linea="18">
								<label>Composición: </label>' .$pClv[0]['composicionGuia'] .
							'</div>';
					 }else{
					 	echo '<div data-linea="16">
						 		<label>Forma farmacética: </label>' . $pClv[0]['formulacionGuia'] .
						 	'</div>';
					 }
				?>
							
				<div data-linea="19">
					<label>Clasifición: </label> <?php echo $pClv[0]['clasificacion']; ?>
				</div>												
	</fieldset>
	
	<?php 
	//IMPRESION DE DOCUMENTOS
	
		if(count($dcClv)>0){
			$i=1;

			echo'<div id="documentos" >
					<fieldset>
						<legend>Documentos adjuntos</legend>
							<table>
								<tr>
									<td><label>#</label></td>
									<td><label>Nombre</label></td>
									<td><label>Enlace</label></td>
								</tr>';

			foreach ($dcClv as $documento){
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
	?>
		
	<fieldset id="informacionProducto">
			<legend>Descripción del producto</legend>
					
			<div data-linea="6">
				<label>Tipo de producto: </label> <?php echo ($cClv[0]['tipoProducto']=="IAV"?'Inocuidad de Alimentos Veterinarios':'Inocuidad de Alimentos Plaguicidas'); ?> 
			</div>
			
			<div data-linea="11">
				<label>Fecha vigencia: </label> <?php echo date('j/n/Y',strtotime($cClv[0]['fechaVigenciaProducto'])); ?>
			</div>
			
			<div data-linea="11">
				<label>Fecha inscripcion: </label> <?php echo date('j/n/Y',strtotime($cClv[0]['fechaInscripcionProducto'])); ?>
			</div>
			
			<div data-linea="15">
				<label>Forma Farmaceútica: </label> <?php echo $cClv[0]['formaFarmaceutica']; ?>
			</div>
			
			<div data-linea="17">
				<label>Formulación: </label> <?php echo $cClv[0]['formulacion']; ?>
			</div>
			
			<?php 
				if($cClv[0]['tipoProducto'] == 'IAV') {
                  echo "<div data-linea='16'>
                      		 <label>Uso: </label>" . $cClv[0]['usoProducto'] . "
				 		</div>
				 		
				 		<div data-linea='19'>
                      		 <label>Especies: </label>" . $cClv[0]['especie']. "
				  		</div>
                  		
                  		<div data-linea='13'>
                  				<label>Presentación comercial: </label> " . $cClv[0]['presentacionComercial'] ."
                  		</div>
                  		
                  		<div data-linea='14'>
                  				<label>Clasificación: </label> ". $cClv[0]['clasificacionProducto']."
                  		</div>";
				}
			?>
	</fieldset>

<?php 
	//DETALLE DE PRODUCTOS
	if($cClv[0]['tipoProducto'] == 'IAP'  && count($dClv) > 0){
		echo '<fieldset>
				<legend>Composición Plaguicida</legend>;
			      	<table>
						<tr>
							<td><label>#</label></td>
							<td><label>Ingrediente activo</label></td> 
							<td><label>Concentración</label></td>
						</tr>';
				$i=1;
				
				foreach ($dClv as $detalleProducto){
					echo '<tr>
							<td>'.$i.'</td>
							<td>' . $detalleProducto['ingredienteActivo'] . ' </td>
						  	<td>' . number_format($detalleProducto['concentracion'], 2) . ' '. $detalleProducto['unidadMedida'] . ' </td>
						</tr>';			
					$i++;
					
				}
		echo '</table>
			</fieldset>';
	}
	
	if($cClv[0]['tipoProducto'] == 'IAV' && count($dClv) > 0) {
		$i=1;
		
		echo '<fieldset>
				<legend>Composición Veterinario</legend>;
					<table>
						<tr>
							<td><label>#</label></td>
							<td><label>Nombre</label></td>
							<td><label>Cantidad</label></td>
							<td><label>Descripción</label></td>
						</tr>';
		
		foreach ($dClv as $detalleProducto){
			echo '<tr>
					<td>' . $i . '</td>
					<td>' . $detalleProducto['composicionDeclarada'] . ' </td>
					<td>' . number_format($detalleProducto['cantidadComposicion'],2) . ' ' . $detalleProducto['unidadMedida'] . ' </td>
					<td>' . $detalleProducto['descripcionComposicion'] . ' </td>
				  </tr>';
			$i++;
				
		}
		echo '</table>
		</fieldset>';
	}		
	
?>

<script type="text/javascript">
var estado= <?php echo json_encode($cClv[0]['estado']); ?>;
	
	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));
		$("#resultado").hide();
		$("#id_veterinario1").hide();
		$("#id_veterinario2").hide();
		$("#id_plaguicida1").hide();
					
		if (estado == "aprobado" || estado == "rechazado"){
			$("#resultado").show();
		}

	});
</script>