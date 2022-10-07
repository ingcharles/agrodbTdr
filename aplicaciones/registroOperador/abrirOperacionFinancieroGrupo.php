<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';

$conexion = new Conexion();
$cca = new ControladorCatalogos();
$cc = new ControladorCertificados();
$cr = new ControladorRegistroOperador();
$crs = new ControladorRevisionSolicitudesVUE();

$operaciones = str_replace(' ', '', ($_POST['elementos']==''?$_POST['id']:$_POST['elementos']));
$identificadorInspector = $_SESSION['usuario'];
$idGrupo = $_POST['nombreOpcion'];
$estadoActual = ($idGrupo == ''?'pago':'verificacion');
$idGrupoOperaciones = explode(",",($_POST['elementos']==''?$_POST['id']:$_POST['elementos']));

if($_POST['id'] == '_agrupar'){
	$condicion = 'pago';
	$solicitudesArr=explode(",",$_POST['elementos']);
}else{
	$condicion = $_POST['opcion'];
}

$qOperadorSitio = $cr->obtenerOperadorSitioInspeccion($conexion,$operaciones);
//$operadorSitio = pg_fetch_assoc($qOperadorSitio);

$arraySitio = pg_fetch_all($qOperadorSitio);

$contadorSitio = 1;
$contadorDataLinea = 3;

while($operadorSitio = pg_fetch_assoc($qOperadorSitio)){

	if($contadorSitio == 1){
	
		if($idGrupo != ''){
			$ordenPago = $cc->obtenerIdOrdenPagoXtipoOperacion($conexion, $idGrupo, $operaciones, 'Operadores');
		}
		
		if($condicion == 'pago'){
			echo '<input type="hidden" id="'.$operaciones.'-'.$operadorSitio['identificador'].'-'.$estadoActual.'-Operadores-tarifarioNuevo" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo.'"/>';
		}else if($condicion == 'verificacionVUE' && pg_num_rows($ordenPago) != 0){
			echo '<input type="hidden" id="'.$operaciones.'-'.$operadorSitio['identificador'].'-'.$estadoActual.'-Operadores-tarifarioNuevo" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo.'"/>';
		}else if($condicion == 'verificacion' && pg_num_rows($ordenPago) == 0){
			echo '<input type="hidden" id="'.$operaciones.'-'.$operadorSitio['identificador'].'-pago-Operadores-tarifarioAntiguo" data-rutaAplicacion="financiero" data-opcion="asignarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo.'"/>';
		}else if($condicion == 'verificacion' && pg_num_rows($ordenPago) != 0){
			$numeroOrdenPago = pg_fetch_result($ordenPago, 0, 'id_pago');
			echo '<input type="hidden" id="'.$operaciones.'-'.$operadorSitio['identificador'].'-'.$estadoActual.'-Operadores-'.$numeroOrdenPago.'" data-rutaAplicacion="financiero" data-opcion="finalizarMontoSolicitud" data-destino="ordenPago" data-nombre = "'.$idGrupo.'"/>';
		}
		
		$identificadorOperador = $operadorSitio['identificador'];
		$nombreOperador = $operadorSitio['nombre_operador'];
		
	}
		
		$idOperadorTipoOperacion1 = $operadorSitio['id_operador_tipo_operacion'];
		
		$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion1);
		$historialOperacion1 = pg_fetch_assoc($qHistorialOperacion);
		$historialOperacion .= $historialOperacion1['id_historial_operacion'].', ';
		
		$idOperadorTipoOperacion .= $idOperadorTipoOperacion1.', ';

		$totalSitios .= '
		<div data-linea="'.$contadorDataLinea.'"><label>Nombre sitio: </label> '. $operadorSitio['nombre_lugar'] .'</div>
		<div data-linea="'.++$contadorDataLinea.'"><label>Provincia: </label>'. $operadorSitio['provincia'] . '</div>
		<div data-linea="'.$contadorDataLinea.'"><label>Canton: </label>' . $operadorSitio['canton'] . '</div>
		<div data-linea="'.$contadorDataLinea.'"><label>Parroquia: </label>'.$operadorSitio['parroquia'].'</div>
		<div data-linea="'.++$contadorDataLinea.'"><label>Dirección: </label>'.$operadorSitio['direccion'].'<br /></div>
		<hr/>';
		++$contadorDataLinea;
		
		$contadorSitio++;
}

$idOperadorTipoOperacion = rtrim($idOperadorTipoOperacion,', ');
$historialOperacion = rtrim($historialOperacion,', ');
$productos = $cr->obtenerProductosPorIdOperadorTipoOperacionHistorico($conexion, $idOperadorTipoOperacion, $historialOperacion, 'pago');

?>

<header>
	<h1>Solicitud Operador</h1>
</header>
<div id="estado"></div>


	<fieldset>
		<legend>Datos operador</legend>
		<div data-linea="1">
			<label>Número de identificación: </label> <?php echo $identificadorOperador; ?>
		</div>

		<div data-linea="2">
			<label>Razón social: </label> <?php echo $nombreOperador; ?> 
		</div>
		
		<hr/>
		
		<?php  echo $totalSitios; ?>
		<!--  div data-linea="3">
			<label>Nombre sitio: </label> < ?php echo $operadorSitio['nombre_lugar']; ?> 
		</div>
		
		<div data-linea="4">
			<label>Provincia: </label> < ?php echo $operadorSitio['provincia']; ?> 
		</div>

		<div data-linea="4">
			<label>Canton: </label> < ?php echo $operadorSitio['canton']; ?> <br />
		</div>

		<div data-linea="4">
			<label>Parroquia: </label> < ?php echo $operadorSitio['parroquia']; ?> <br />
		</div>

		<div data-linea="5">
			<label>Dirección: </label> < s?php echo $operadorSitio['direccion']; ?> <br />
		</div-->
		
	</fieldset>
	

	<fieldset>
		<legend>Operación, producto, área</legend>
	
	<?php 
	$contador = 40;	
	foreach ($idGrupoOperaciones as $solicitud){
		$registros = array();
		$qAreasOperador = $cr->obtenerOperadorOperacionAreaInspeccion($conexion, $solicitud);
		
		while($areaOperacion = pg_fetch_assoc($qAreasOperador)){
			$registros[] = array('nombreArea' => $areaOperacion['nombre_area'], 'tipoArea' => $areaOperacion['tipo_area'], 
								'nombreOperacion' => $areaOperacion['nombre_operacion'], 'idArea' => $areaOperacion['id_area'], 'superficieUtilizada' => $areaOperacion['superficie_utilizada'], 
			                    'idOperacion' => $areaOperacion['id_operacion'], 'areaOperacion' => $areaOperacion['area_operacion']);
		}
		
		$qDocumentosAdjuntos = $cr->obtenerDocumentosAdjuntoXoperacion($conexion, $solicitud);
		
		echo ($contador!=40?'<hr>':'');
		
		switch ($registros[0]['areaOperacion']){
		    case 'SA':
		        $tipoArea = 'Sanidad Animal';
		        break;
		        
		    case 'SV':
		        $tipoArea = 'Sanidad Vegetal';
		        break;
		        
		    case 'IAV':
		        $tipoArea = 'Registros de insumos Pecuarios';
		        break;
		        
		    case 'IAF':
		        $tipoArea = 'Registros de insumos Fertilizantes';
		        break;
		        
		    case 'IAP':
		        $tipoArea = 'Registros de insumos Agricolas';
		        break;
		        
		    case 'IAPA':
		        $tipoArea = 'Registro de insumos para plantas de autoconsumo';
		        break;
		        
		    case 'AI':
		        $tipoArea = 'Inocuidad de los alimentos';
		        break;
		        
		    case 'LT':
		        $tipoArea = 'Laboratorios';
		        break;

		    case 'CGRIA':
		    	$tipoArea = 'Coordinación de Registros de Insumos Agropecuarios';
			break;
		        
		    default:
		        $tipoArea = 'Tipo Area Desconocido';
		        
		}
		
		echo'
		<div data-linea="'.$contador.'">
			<label>Tipo operación: </label> ' . $registros[0]['nombreOperacion']. ' - '. $tipoArea.'
		</div>';
				
		$areaImpreso = '';
		foreach ($registros as $areas){
			//Información de tamaño de áreas
			$qUnidadMedida = $cca->obtenerUnidadMedidaAreas($conexion, $areas['idArea']);
			$unidadMedida = pg_fetch_result($qUnidadMedida, 0, 'unidad_medida');
			
			echo '<div data-linea="'.++$contador.'">
					<label>Nombre área: </label> '.$areas['nombreArea'].' ('. $areas['superficieUtilizada'].' '.$unidadMedida .')
				</div>';			
		}

		//echo '<hr/>';
		
		if(pg_num_rows($qDocumentosAdjuntos)!= 0){
	
			echo '<div data-linea="'.++$contador.'">
						<label>Documentos adjuntos: </label></div>';
			
			while ($documento = pg_fetch_assoc($qDocumentosAdjuntos)){
				echo '<div data-linea="'.++$contador.'"><label>'.$documento['titulo'].'.-  </label><a href="'.$documento['ruta_documento'].'">'.$documento['descripcion'].'</a></div>';
			}
		}
		
		$contador++;
	}	
	
	?>
	
	</fieldset>
	
<?php if(pg_num_rows($productos)!= 0){?>
	<fieldset id="datosProducto">
		<legend>Productos</legend>
				
		<table style="width: 100%">
			<thead>
				<tr>
					<th>#</th>
					<th>Tipo producto</th>
					<th>Subtipo producto</th>
					<th>Producto</th>
				</tr>
			</thead>
			<tbody>
			<?php
				while ($fila = pg_fetch_assoc($productos)){
					echo '<tr><td>'.++$contador.'</td><td>'.$fila['nombre_tipo'].'</td><td>'.$fila['nombre_subtipo'].'</td><td>'.$fila['nombre_comun'].'</td></tr>';
				} 						
			?>			
			</tbody>
		</table>
		
	</fieldset>
<?php }?>
	
	
	<div id="ordenPago"></div>

	
<script type="text/javascript">	
							
	var array_solicitudes= <?php echo json_encode($solicitudesArr); ?>;

	$(document).ready(function(){

		if(array_solicitudes == ''){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione una o varias solicitudes y a continuación presione el boton agrupar.</div>');
		}
		
		abrir($("#detalleItem input:hidden"),null,false);
	});

</script>
	