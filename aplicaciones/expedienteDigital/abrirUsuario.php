<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorExpedienteDigital.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorDestinacionAduanera.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorClv.php';
require_once '../../clases/ControladorFitosanitario.php';
require_once '../../clases/ControladorZoosanitarioExportacion.php';
require_once '../../clases/ControladorCertificacionBPA.php';
require_once '../../clases/ControladorProveedorExterior.php';
require_once '../../clases/ControladorTransitoInternacional.php';

// --------------------------------------------------------------------------------
$conexion = new Conexion();
$cd = new ControladorDestinacionAduanera();
$ci = new ControladorImportaciones();
$ce = new ControladorExpedienteDigital();
$cro = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();
$cl = new ControladorClv();
$fi = new ControladorFitosanitario();
$cze = new ControladorZoosanitarioExportacion();
$ccbpa = new ControladorCertificacionBPA();
$cpe = new ControladorProveedorExterior();
$cti = new ControladorTransitoInternacional();
// -------------------------------------------------------------------------------
$idOperacion = htmlspecialchars($_POST['id'], ENT_NOQUOTES, 'UTF-8');
$tmp = explode(".", htmlspecialchars($_POST['opcion'], ENT_NOQUOTES, 'UTF-8'));
$area = $tmp[0];
$provincia = $tmp[1];
$identificador = $tmp[2];
$tipoServicio = $tmp[3];
$idVue = $tmp[4];
$idTipoOperacion = $tmp[5];
$fechaServicio = $tmp[6];
$idFlujoOperacion = $tmp[7];//Para certificación bpa envía si es asociación o no
// -----------------------traer datos generales-----------------------------------

$consultaDocumentoCGRIA = false;
?>
<header>
	<h1>
    	<?php 
        	switch ($tipoServicio){
        	    case 'Operadores':
        	        $tipoServicioTitulo = 'Registro de operador';
        	        break;
        	    case 'certificacionBPA':
        	        $tipoServicioTitulo = 'Certificación BPA';
        	        break;
        	    case 'proveedorExterior':
        	        $tipoServicioTitulo = 'Provedores en el exterior';
        	    break;
        	    case 'TransitoInternacional':
        	        $tipoServicioTitulo = 'Solicitud de Tránsito Internacional';
        	        break;
        	    default:
        	        $tipoServicioTitulo = $tipoServicio;
        	    break;
        	}
        	echo $tipoServicioTitulo; 
    	?>
	</h1>
</header>

<?php
// -----------imprimir informacion del cliente------------------
if ($tipoServicio == 'TransitoInternacional'){
    
}else if ($tipoServicio != 'CLV' && $tipoServicio != 'TransitoInternacional'){
    if($idFlujoOperacion != 'Si'){
        $operadorJson = $cro->obtenerOperador($conexion, $identificador, $area, $provincia);
        $operador = (array) (json_decode($operadorJson[row_to_json]));
        echo $ce->datosCliente($operador);
    }else{
        $operador = pg_fetch_assoc($ccbpa->obtenerDatosAsociacion($conexion, $identificador));
        echo $ce->datosAsociacion($operador);
    }
}else {
    echo $datosCliente = '<fieldset>
							<legend>Datos del Cliente</legend>
							<div data-linea="1">
								<label>RUC/CI:</label>
								<span>' . $identificador . '</span>
							</div>
						</fieldset>';
}
// -------------------------------------------------------------

switch ($tipoServicio) {
    case 'Operadores':
        $datosPorOeracionJson = $ce->obtenerDatosOperacionSolicitud($conexion, $identificador, $idTipoOperacion, $area, $idOperacion);
        $datosOperacion = (array) (json_decode($datosPorOeracionJson[array_to_json]));
        echo '<header>' . '<h1>Descripción Lugar </h1>' . '</header>';
        $qcodigoTipoOperacion= $cc->obtenerCodigoTipoOperacion($conexion, $idOperacion);
        $opcionArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'codigo');
        $idArea=  pg_fetch_result($qcodigoTipoOperacion, 0, 'id_area');

        switch ($idArea){
        	case 'IAP':
        	case 'IAV':
        	case 'IAF':
        	case 'CGRIA':
        		$consultaDocumentoCGRIA = true;
        		switch ($opcionArea){
        			case 'ALM':
        				$qDocumentosOperador = $cro->obtenerDocumentoGeneradoPorSecuencialIdentificadorOperadorTipoDocumento($conexion, $datosOperacion[0]->id_sitio, $identificador, 'riaAlmacenistas');
        				break;
        			default:
        				$qDocumentosOperador = $cro->obtenerDocumentoGeneradoPorSecuencialIdentificadorOperadorTipoDocumento($conexion, '1', $identificador, 'riaEmpresas');
        		}
        		
        		if(pg_num_rows($qDocumentosOperador)!= 0){
        			$documentoOperador = true;
        		}
        		break;
        }
    break;
    case 'ROCE':
        $datosPorOeracionJson = $ce->obtenerDatosOperacionSolicitud($conexion, $identificador, $idTipoOperacion, $area, $idOperacion);
        $datosOperacion = (array) (json_decode($datosPorOeracionJson[array_to_json]));
        echo '<header>' . '<h1>Descripción Lugar </h1>' . '</header>';
        break;
    case 'DDA':
        $qDestinacionAduanera = $cd->abrirDDA($conexion, $idOperacion);
        $importacion = pg_fetch_assoc($ci->buscarVigenciaImportacion($conexion, $qDestinacionAduanera[0]['permisoImportacion']));
?>
		<fieldset>
			<legend>Datos de documento de destinación aduanera</legend>
		
			<div data-linea="1">
				<label># importación: </label> <?php echo  $qDestinacionAduanera[0]['permisoImportacion']; ?>
					</div>
			<div data-linea="13">
				<label>Fecha Creación: </label><span><?php echo  $fechaServicio; ?></span>
			</div>
			<div data-linea="12">
				<label>Fecha inicio vigencia: </label><span><?php echo  date('d/m/Y',strtotime($importacion['fecha_inicio'])); ?></span>
			</div>
		
			<div data-linea="12">
				<label>Fecha fin vigencia: </label><span><?php echo  date('d/m/Y',strtotime($importacion['fecha_vigencia'])); ?></span>
			</div>
		
			<div data-linea="2">
				<label>Certificado exportación: </label> <?php echo $qDestinacionAduanera[0]['permisoExportacion']; ?>
					</div>
			<div data-linea="2">
				<label>Propósito: </label> <?php echo $qDestinacionAduanera[0]['proposito']; ?> 
					</div>
		
			<div data-linea="3">
				<label>Categoría producto: </label> <?php echo $qDestinacionAduanera[0]['categoriaProducto']; ?> 
					</div>
		
			<div data-linea="4">
				<label>Exportador: </label> <?php echo $qDestinacionAduanera[0]['nombreExportador']; ?>
					</div>
			<div data-linea="5">
				<label>Dirección: </label> <?php echo $qDestinacionAduanera[0]['direccionExportador']; ?> 
					</div>
		
			<div data-linea="6">
				<label>País origen: </label> <?php echo  $qDestinacionAduanera[0]['paisExportacion']; ?>
					</div>
		
			<div data-linea="6">
				<label># carga: </label> <?php echo $qDestinacionAduanera[0]['numeroCarga']; ?> 
					</div>
		
			<div data-linea="8">
				<label>Puerto destino: </label> <?php echo $qDestinacionAduanera[0]['nombrePuertoDestino']; ?> 
					</div>
		
			<div data-linea="9">
				<label>Medio de transporte: </label> <?php echo $qDestinacionAduanera[0]['tipoTransporte']; ?> 
					</div>
		
			<div data-linea="9">
				<label># Doc. transporte: </label> <?php echo $qDestinacionAduanera[0]['numeroTransporte']; ?> 
					</div>
		
			<div data-linea="10">
				<label>Lugar inspección: </label> <?php echo $qDestinacionAduanera[0]['nombreLugarInspeccion']; ?> 
					</div>
		
			<div data-linea="11">
				<label>Observación: </label> <?php echo $qDestinacionAduanera[0]['observacionImportacion']; ?> 
					</div>
		</fieldset>
		
		<?php
        $qDestinacionAduanera = $cd->abrirDDA($conexion, $idOperacion);
        $i = 1;
        foreach ($qDestinacionAduanera as $destinacionAduanera) {

            $validacionCantidad = false;

            echo '
			<fieldset>
			<legend>Producto DDA ' . $i . '</legend>';

            $qProductoTipoSubtipo = $cc->obtenerTipoSubtipoXProductos($conexion, $destinacionAduanera['idProducto']);
            $productoTipoSubtipo = pg_fetch_assoc($qProductoTipoSubtipo);

            // ---------------------------------------------------------------------------------------------------------------------------------------------------------------------

            $importacionProducto = pg_fetch_assoc($ci->buscarImportacionProductoVUE($conexion, $destinacionAduanera['identificador'], $destinacionAduanera['permisoImportacion'], $destinacionAduanera['idProducto']));

            $cantidadProducto = pg_fetch_assoc($cd->obtenerCantidadProductoXimportacion($conexion, $destinacionAduanera['permisoImportacion'], $destinacionAduanera['idProducto']));

            $cantidadActualProducto = $importacionProducto['unidad'] - $cantidadProducto['cantidad_producto'];
            $pesoActualProducto = $importacionProducto['peso'] - $cantidadProducto['peso_producto'];

            if ($destinacionAduanera['unidad'] > $cantidadActualProducto) {
                $validacionCantidad = true;
                $validacionAprobacion = true;
            }

            // ---------------------------------------------------------------------------------------------------------------------------------------------------------------------

            echo '
			<div data-linea="3">
			<label>Tipo producto: </label> ' . $productoTipoSubtipo['nombre_tipo'] . ' <br/>
			</div>
			<div data-linea="4">
			<label>Subtipo producto: </label> ' . $productoTipoSubtipo['nombre_subtipo'] . ' <br/>
			</div>
			 
			<div data-linea="5">
			<label>Nombre del producto: </label> ' . $destinacionAduanera['nombreProducto'] . ' <br/>
			</div>
			<div data-linea="6">
			<label>Partida arancelaria: </label> ' . $destinacionAduanera['partidaArancelaria'] . ' <br/>
			</div>
			<div data-linea="6">
			<label>Cantidad: </label> ' . $destinacionAduanera['unidad'] . ' ' . $destinacionAduanera['unidadMedida'] . '<br/>
			</div>
			<div data-linea="7">
			<label>Peso disponible del permiso: </label> ' . $pesoActualProducto . ' ' . $importacionProducto['unidad_peso'] . '<br/>
			</div>';

            if ($validacionCantidad) {
                echo '<p class="alerta">El permiso de importacion cuenta con ' . $cantidadActualProducto . ' ' . $destinacionAduanera['unidadMedida'] . ' de ' . $destinacionAduanera['nombreProducto'] . ' disponible.</p>';
            }
            echo '</fieldset>';
            $i ++;
        }
    break;
    case 'Fitosanitario':
        $qfitoExportacion = $fi->listarFitoExportacion($conexion, $idOperacion);
        $fitoExportacion = pg_fetch_assoc($qfitoExportacion);
        $qfitoExportacionDetalle = $fi->listarFitoExportacionDetalle($conexion, $idOperacion);
?>
		<fieldset>
			<legend>Datos de Generales de la Exportación</legend>
			<div data-linea="16">
				<label>Fecha Creación: </label> <?php echo $fechaServicio; ?> 
						</div>
			<div data-linea="1">
				<label>País embarque: </label> <?php echo $fitoExportacion['pais_embarque']; ?> 
						</div>
		
			<div data-linea="2">
				<label>Puerto embarque: </label> <?php echo $fitoExportacion['puerto_embarque']; ?> 
						</div>
		
			<div data-linea="3">
				<label>País destino: </label> <?php echo $fitoExportacion['pais_destino']; ?> 
						</div>
		
			<div data-linea="20">
				<label>Puerto destino: </label> <?php echo $fitoExportacion['puerto_destino']; ?> 
						</div>
		
			<div data-linea="4">
				<label>Pais origen: </label> <?php echo $fitoExportacion['pais_origen']; ?> 
						</div>
		
			<div data-linea="4">
				<label>Lugar inspección: </label> <?php echo $fitoExportacion['lugar_inspeccion']; ?> 
						</div>
		
			<div data-linea="6">
				<label>Transporte: </label> <?php echo $fitoExportacion['transporte']; ?> 
						</div>
		
			<div data-linea="6">
				<label>Fecha embarque: </label> <?php echo $fitoExportacion['fecha_embarque']; ?> 
						</div>
			<div data-linea="7">
				<label>Número viaje: </label> <?php echo $fitoExportacion['numero_viaje']; ?> 
						</div>
		
			<div data-linea="7">
				<label>Provincia: </label> <?php echo $fitoExportacion['provincia']; ?> 
						</div>
		
			<div data-linea="10">
				<label>Producto orgánico: </label> <?php echo ($fitoExportacion['producto_organico'] == 'S' ? 'SI': 'NO'); ?>  
						</div>
		
			<div data-linea="10">
				<label>Certificación orgánica: </label> <?php echo ($fitoExportacion['numero_producto_organico'] == '' ? 'No disponible': $fitoExportacion['numero_producto_organico']); ?> 
						</div>
		
			<div data-linea="8">
				<label>Marca: </label> <?php echo $fitoExportacion['nombre_marcas']; ?> 
						</div>
		
			<div data-linea="14">
				<label>Reporte inspección: </label> <?php echo $fitoExportacion['reporte_inspeccion']; ?> 
						</div>
		
			<div data-linea="15">
				<label>Información adicional: </label> <?php echo $fitoExportacion['observacion_operador']; ?> 
						</div>
		</fieldset>

		<!--<fieldset>
			<legend>Datos de Generales del Tratamiento del Producto</legend>	
								
				<div data-linea="8">
					<label>Tratamiento realizado: </label> < ?php echo $fitoExportacion['tratamiento_realizado']; ?> 
				</div>
				
				<div data-linea="9">
					<label>Duración: </label> < ?php echo $fitoExportacion['duracion_tratamiento']; ?> 
				</div>
				
				<div data-linea="9">
					<label>Temperatura: </label> < ?php echo $fitoExportacion['temperatura_tratamiento'].' '.$fitoExportacion['unidad_temperatura']; ?> 
				</div>
				
				<div data-linea="10">
					<label>Fecha: </label> < ?php echo $fitoExportacion['fecha_tratamiento']; ?> 
				</div>
				
				<div data-linea="10">
					<label>Químico usado: </label> < ?php echo $fitoExportacion['quimico_tratamiento']; ?> 
				</div>
				
				<div data-linea="11">
					<label>Concentración: </label> < ?php echo $fitoExportacion['concentracion_producto']; ?> 
				</div>
		</fieldset>-->
		<?php
		        $i = 1;
		
		        echo '<fieldset>
				<legend>Productos para Exportación</legend>;
				<table>
				<tr>
				<td>#</td>
				<td><label>Producto</label></td>
				<td><label>Número de bultos</label></td>
				<td><label>Cantidad neta</label></td>';
		        foreach ($qfitoExportacionDetalle as $detalleFito) {
		            if ($detalleFito['permisoMusaceas'] != '') {
		                echo '<td><label>Permiso musaceas</label></td>';
		                break;
		            }
		        }
		
		        echo '</tr>';
		        $i = 1;
		
		        foreach ($qfitoExportacionDetalle as $detalleFito) {
		            echo '<tr>
					<td>' . $i . '</td>
					<td>' . $detalleFito['nombreProducto'] . '</td>
					<td>' . $detalleFito['numeroBultos'] . ' ' . $detalleFito['unidadBultos'] . '</td>
					<td>' . $detalleFito['cantidadProducto'] . ' ' . $detalleFito['unidadCantidadProducto'] . '</td>';
		            if ($detalleFito['permisoMusaceas'] != '') {
		                echo '<td>' . $detalleFito['permisoMusaceas'] . '</td>';
		            }
		            echo '</tr>';
		            $i ++;
		        }
		        echo '</table>
				</fieldset>';
		        break;
		    case 'CLV':
		        $cClv = $cl->listarCertificados($conexion, $idOperacion);
		        $pClv = $cl->listaProdInocuidad($conexion, $idOperacion);
		        ?>
		<fieldset id="informacionProductoClv">
			<legend>Información del producto <?php echo ($cClv[0]['tipoProducto'] == 'IAV'?'Veterinario':($cClv[0]['tipoProducto'] == 'IAP'?'Plaguicida':'Fertilizante')); ?></legend>
			<div data-linea="13">
				<label>Fecha Creación: </label> <?php echo $fechaServicio; ?> 
						</div>
			<div data-linea="14">
				<label>Tipo de producto: </label> <?php echo ($cClv[0]['tipoProducto'] == 'IAV'?'Veterinario':($cClv[0]['tipoProducto'] == 'IAP'?'Plaguicida':'Fertilizante')); ?> 
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
		        if ($cClv[0]['tipoProducto'] == 'IAP') {
		            echo '<div data-linea="17">
										<label>Formulación VUE: </label>' . $pClv[0]['formulacion'] . '</div>
									<div data-linea="17">
										<label>Formulación GUIA: </label>' . $pClv[0]['formulacionGuia'] . '</div>
									<div data-linea="18">
										<label>Composición: </label>' . $pClv[0]['composicionGuia'] . '</div>';
		        } else {
		            echo '<div data-linea="16">
								 		<label>Forma farmacética: </label>' . $pClv[0]['formulacionGuia'] . '</div>';
		        }
		        ?>
									
						<div data-linea="19">
				<label>Clasifición: </label> <?php echo $pClv[0]['clasificacion']; ?>
						</div>
		</fieldset>
		<?php
	break;
    case 'Importación':
        $qImportacion = $ci->abrirImportacionEnviada($conexion, $idOperacion);
        $qMoneda = $cc->obtenerNombreMoneda($conexion, $qImportacion[0]['moneda']);
        $moneda = pg_fetch_result($qMoneda, 0, 'nombre');
        $qRegimenAduanero = $cc->obtenerNombreRegimenAduanero($conexion, $qImportacion[0]['regimenAduanero']);
        $regimenAduanero = pg_fetch_result($qRegimenAduanero, 0, 'descripcion');
?>
		<fieldset>
			<legend>Datos de Importación</legend>
			<div data-linea="3">
				<label>Fecha Creación: </label> <?php echo $fechaServicio; ?> 
					</div>
			<div data-linea="4">
				<label>Nombre exportador: </label> <?php echo $qImportacion[0]['nombreExportador']; ?> 
					</div>
		
			<div data-linea="1">
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
			
			<?php
			
				$predioCuarentena = $qImportacion[0]['numeroCuarentena'];
				$identificadorCuarentena =reset(explode(".",$predioCuarentena));
				$idAreaSeguimiento = $qImportacion[0]['idAreaSeguimiento'];
			
				if($qImportacion[0]['numeroCuarentena'] != ''){
				
					echo '<div data-linea="10">
							<label>Predio/Sitio de cuarentena: </label>'.$predioCuarentena.'
						</div>
						<form id="datosOperadorCuarentena" data-rutaAplicacion="expedienteDigital/json" data-opcion="cargarDatosDeOperacion">
							<input type="hidden" name="tipoResultado" value="html"/>
							<input type="hidden" name="identificador" value="'.$identificadorCuarentena.'"/>
							<input type="hidden" name="idAreaSeguimiento" value="'.$predioCuarentena.'"/>
					
					<button class="mo_areas" type="submit">Mostrar/Ocutar áreas</button>
					</form>
					
					<div id="resultadoOperadorCuarentena"></div>';
					
				}
			?>
					
			
			
		</fieldset>

<?php
        $i = 1;
        foreach ($qImportacion as $importacion) {
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
			<label>Presentación producto: </label> ' . $importacion['presentacion'] . ' <br/>
			</div>
			<div data-linea="6">
			<label>Partida arancelaria: </label> ' . $importacion['partidaArancelaria'] . ' <br/>
			</div>
			<div data-linea="7">
			<label>Cantidad: </label> ' . $importacion['unidad'] . ' ' . $importacion['unidadMedida'] . ' <br/>
			</div>
			<div data-linea="7">
			<label>Peso neto: </label> ' . $importacion['peso'] . ' kgs <br/>
			</div>
			<div data-linea="8">
			<label>Valor FOB: </label> ' . $importacion['valorFob'] . ' <br/>
			</div>
			<div data-linea="8">
			<label>Valor CIF: </label> ' . $importacion['valorCif'] . ' <br/>
			</div>';
            if ($importacion['licenciaMagap'] != '') {
                echo '
				<div data-linea="9">
				<label>Licencia MAGAP: </label> ' . $importacion['licenciaMagap'] . ' <br/>
				</div>';
            }

            if ($importacion['registroSemillas'] != '') {
                echo '
				<div data-linea="9">
				<label>Registro Semillas: </label> ' . $importacion['registroSemillas'] . ' <br/>
				</div>';
            }

            echo '</fieldset>';

            $i ++;
        }
    break;
    case 'Zoosanitario':
        $qZoosanitario = $cze->abrirZoo($conexion, $idOperacion);
        $zoosanitario = pg_fetch_assoc($qZoosanitario);
        $qZoosanitarioProductos = $cze->abrirZooProductos($conexion, $idOperacion);
        ?>
		<fieldset>
			<legend>Datos generales de exportación</legend>
			<div data-linea="4">
				<label>Fecha Creación: </label> <?php echo $fechaServicio; ?> 
					</div>
			<div data-linea="5">
				<label>País destino: </label> <?php echo $zoosanitario['pais_destino']; ?> 
					</div>
		
			<div data-linea="6">
				<label>Dirección: </label> <?php echo $zoosanitario['direccion_importador']; ?> 
					</div>
		
			<div data-linea="7">
				<label>Puerto embarque: </label> <?php echo $zoosanitario['puerto_embarque']; ?> 
					</div>
		
			<div data-linea="7">
				<label>Medio de transporte: </label> <?php echo $zoosanitario['transporte']; ?> 
					</div>
		
			<div data-linea="8">
				<label>Uso producto: </label> <?php echo $zoosanitario['nombre_uso']; ?> 
					</div>
		
			<div data-linea="8">
				<label>Bultos: </label> <?php echo $zoosanitario['numero_bultos'] . ' ' . $zoosanitario['descripcion_bultos']; ?> 
					</div>
		</fieldset>
		
		<?php
		        $i = 1;
		
		        echo '<div id="documentos" >
		        <fieldset>
		        <legend>Datos del producto</legend>
		        <form id="f_' . $i . '" data-rutaAplicacion="../general" data-opcion="abrirPdfFtp" data-destino="documentoAdjunto" data-accionEnExito="ACTUALIZAR">
		        <table>
		        <tr>
		        <td><label>#</label></td>
		        <td><label>Nombre Producto</label></td>
		        <td><label>Partida arancelaria</label></td>';
		
		        foreach ($qZoosanitarioProductos as $zooProductos) {
		            if ($zooProductos['sexo'] != '' && $zooProductos['edad'] != 0) {
		                echo '<td><label>Sexo</label></td>
		        		<td><label>Edad</label></td>';
		                break;
		            }
		        }
		
		        echo '<td><label>Cantidad física</label></td>';
		        echo '</tr>';
		
		        foreach ($qZoosanitarioProductos as $zooProductos) {
		            echo '<tr>
		        	<td>' . $i . '</td>
		        	<td>' . $zooProductos['nombreProducto'] . '</td>
		        	<td>' . $zooProductos['partidaArancelaria'] . '</td>';
		
		            if ($zooProductos['sexo'] != '') {
		                echo '<td>' . $zooProductos['sexo'] . '</td>';
		            }
		            if ($zooProductos['edad'] != 0) {
		                $qEdad = $cc->buscarRangoEdadesAnimal($conexion, $zooProductos['edad']);
		                echo '<td>' . pg_fetch_result($qEdad, 0, 'nombre') . '</td>';
		            }
		
		            echo '<td>' . $zooProductos['cantidadFisica'] . ' ' . $zooProductos['unidadFisica'] . '</td>';
		
		            $i ++;
		        }
		
		        // </td></tr>
		        echo '</fieldset>';
		
		        echo '</table>
		        </form>
		        </fieldset>
		        </div>';
        break;
        
    case 'certificacionBPA':
        $qCertificacion= $ccbpa->abrirSolicitud($conexion, $idOperacion);
        $certificacionBPA = pg_fetch_assoc($qCertificacion);
        ?>
		<?php 
			if($certificacionBPA['ruta_certificado'] != ''){
			  echo '<fieldset>
					 <legend>Certificado BPA</legend> 
							<label>Certificado emitido: </label>' .($certificacionBPA['ruta_certificado']==''?'<span class="alerta">No se ha emitido ningún certificado</span>':'<a href="'.$certificacionBPA['ruta_certificado'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click aquí para ver el archivo</a>').				
						'</div>
					</fieldset>';
			}
        ?>
    		<fieldset>
    			<legend>Datos generales de Certificación BPA</legend>
    			<div data-linea="5">
        			<label>Tipo Solicitante: </label> <?php echo ($certificacionBPA['es_asociacion']=='Si'?'Asociación':'Individual'); ?>			
        		</div>
        		
    			<div data-linea="5">
        			<label>Tipo Solicitud: </label> <?php echo $certificacionBPA['tipo_solicitud']; ?>			
        		</div>		
        		
        		<div data-linea="6"> 
        			<label>Tipo Explotación: </label> 
        			<?php echo ($certificacionBPA['tipo_explotacion']=="SV"?"Sanidad Vegetal":($certificacionBPA['tipo_explotacion']=="SA"?"Sanidad Animal":"Inocuidad de Alimentos"));?>			
        		</div>
    		</fieldset>
    		
    		<fieldset>
        		<legend>Datos Operador</legend>
        		<div data-linea="7">
        			<label>Identificador: </label> <?php echo $certificacionBPA['identificador']; ?>						
        		</div>
        		
        		<div data-linea="8">
        			<label>Nombre/Razón Social: </label> <?php echo $certificacionBPA['razon_social']; ?>						
        		</div>		
        		
        		<hr />
        		
        		<div data-linea="9"> 
        			<label>Identificación Representante: </label> <?php echo $certificacionBPA['identificador_representante_legal']; ?>				
        		</div>
        		
        		<div data-linea="10">
        			<label>Representante Legal: </label> <?php echo $certificacionBPA['nombre_representante_legal']; ?>						
        		</div>		
        	</fieldset>
    		
    		<fieldset>
        		<legend>Datos del Responsable Técnico de la Unidad de Producción Agrícola y/o Pecuaria</legend>
        		<div data-linea="12">
        			<label>Identificación: </label> <?php echo $certificacionBPA['identificador_representante_tecnico']; ?>						
        		</div>
        		
        		<div data-linea="13">
        			<label>Nombres: </label> <?php echo $certificacionBPA['nombre_representante_tecnico']; ?>						
        		</div>		
        		
        		<div data-linea="14"> 
        			<label>E-mail: </label> <?php echo $certificacionBPA['correo_representante_tecnico']; ?>				
        		</div>
        		
        		<div data-linea="14">
        			<label>Teléfono: </label> <?php echo $certificacionBPA['telefono_representante_tecnico']; ?>						
        		</div>		
        	</fieldset>
    		
    		<!--fieldset>
        		<legend>Datos de la Unidad de Producción</legend>
        		<div data-linea="15">
        			<label>Nombre del Sitio: </label> <?php echo $certificacionBPA['sitio_unidad_produccion']; ?>						
        		</div>
        		
        		<div data-linea="16">
        			<label>Provincia: </label> <?php echo $certificacionBPA['provincia_unidad_produccion']; ?>						
        		</div>		
        		
        		<div data-linea="16"> 
        			<label>Cantón: </label> <?php echo $certificacionBPA['canton_unidad_produccion']; ?>				
        		</div>
        		
        		<div data-linea="17">
        			<label>Parroquia: </label> <?php echo $certificacionBPA['parroquia_unidad_produccion']; ?>						
        		</div>	
        		
        		<div data-linea="18">
        			<label>Dirección: </label> <?php echo $certificacionBPA['direccion_unidad_produccion']; ?>						
        		</div>	
        		
        		<hr />
        		
        		<div data-linea="19">
        			<b>Coordenadas </b>
        		</div>
        		
        		<div data-linea="20">
        			<label>UTM (X): </label> <?php echo $certificacionBPA['utm_x']; ?>						
        		</div>
        		
        		<div data-linea="20">
        			<label>UTM (Y): </label> <?php echo $certificacionBPA['utm_y']; ?>						
        		</div>
        		
        		<div data-linea="20">
        			<label>Altitud: </label> <?php echo $certificacionBPA['altitud']; ?>						
        		</div>
        	</fieldset-->
        	
        	<fieldset>			
			<legend><?php echo ($certificacionBPA['es_asociacion']=='Si'?"Sitios de Miembros de la Asociación a Certificar":"Sitios, Áreas y Productos Agregados")?></legend>
        		<div data-linea="11">
        			<table id="tbSitiosAreasProductos" style="width:100%">
        				<thead>
        					<tr>
        						<th style="width: 5%;">Nº</th>
        						<th style="width: 15%;">Nombre Sitio</th>
                                <th style="width: 15%;">Nombre Área</th>
                                <th style="width: 15%;">Producto</th>
                                <th style="width: 15%;">Provincia</th>
                                <th style="width: 15%;">Operación</th>
                                <th style="width: 10%;">Hectáreas</th>
                                <th style="width: 10%;">Estado</th>
        					</tr>
        				</thead>
        				<tbody>
        				<?php 
        				    $res = $ccbpa->obtenerDetalleSitiosAreasProductos($conexion, $idOperacion);
                    		$fila = null;
                    		$i = 1;
                    		
                    		      while($fila = pg_fetch_assoc($res)){
                    				echo '<tr>'.
                                                '<td>'.$i++.'</td>'.
                                				'<td>'.$fila['nombre_sitio'].'</td>'.
                                				'<td>'.$fila['nombre_area'].'</td>'.
                                				'<td>'.($certificacionBPA['tipo_explotacion']=="SA"?$fila['nombre_subtipo_producto']:$fila['nombre_producto']).'</td>'.
                                				'<td>'.$fila['nombre_provincia'].'</td>'.
                                				'<td>'.$fila['nombre_operacion'].'</td>'.
                                				'<td>'.$fila['superficie'].'</td>'.
                                				'<td>'.$fila['estado'].'</td>'.
                            			 '</tr>';
                    		      }
                    	?>
        				</tbody>
        			</table>
        		</div>        		
        	</fieldset>
			
        	<fieldset>
				<legend>Alcance</legend>
        		<div data-linea="21">
        			<label>Tipo de Certificado: </label> <?php echo $certificacionBPA['tipo_certificado']; ?>						
        		</div>
        		
        		<div data-linea="21">
        			<label>Nº de Trabajadores: </label> <?php echo $certificacionBPA['num_trabajadores']; ?>						
        		</div>	
        		
        		<div data-linea="22"> 
        			<label>Fecha de Solicitud: </label> <?php echo date('Y-m-d',strtotime($certificacionBPA['fecha_creacion'])); ?>				
        		</div>	
        		
        		<?php 
        		if($certificacionBPA['tipo_solicitud'] == 'Equivalente'){
        		  echo '<div data-linea="23" class="equivalente"> 
                			<label>Código Equivalente: </label>' .($certificacionBPA['codigo_equivalente']!=''?$certificacionBPA['codigo_equivalente']:'NA').				
                		'</div>
        		
        		<div data-linea="24" class="equivalente"> 
        			<label>Fecha Inicio: </label>' .($certificacionBPA['fecha_inicio_equivalente']!=''?$certificacionBPA['fecha_inicio_equivalente']:'NA').				
        		'</div>
        		
        		<div data-linea="24" class="equivalente"> 
        			<label>Fecha Fin: </label>' .($certificacionBPA['fecha_fin_equivalente']!=''?$certificacionBPA['fecha_fin_equivalente']:'NA').				
        		'</div>';
        		}
        		?>
        		
        		<div data-linea="25">
        			<label>Observaciones: </label> <?php echo $certificacionBPA['observacion_alcance']; ?>						
        		</div>	
        		
        		<hr />
        		
        		<?php 
            		if($certificacionBPA['tipo_solicitud'] == 'Equivalente'){
            		  echo '<div data-linea="26" class="equivalente">
                    			<b>Documentos Adjuntos </b>
                    		</div>
                    		
                    		<div data-linea="27" class="equivalente">
                    			<label>A-. Certificado BPA: </label> '
                    			. ($certificacionBPA['ruta_certificado_equivalente']==''? '<span class="alerta">No ha cargado ningún certificado</span>':'<a href="'.$certificacionBPA['ruta_certificado_equivalente'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click aquí para ver el archivo</a>').					
                    		'</div>
                    		
                    		<hr />';
					}else{
            		    echo '<div data-linea="26" class="nacional">
                    			<b>Documentos Adjuntos </b>
                    		</div>
    
                    		<div data-linea="27" class="equivalente">
                    			<label>A-. Documentos de Apoyo: </label> '
                            . ($certificacionBPA['anexo_nacional']==''? '<span class="alerta">No ha cargado ningún certificado</span>':'<a href="'.$certificacionBPA['anexo_nacional'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click aquí para ver el archivo</a>').
                            '</div>
    
                    		<hr />';
            		}
        		?>		
        		
        		<div data-linea="28">
        			<b>Descripción de la población / producto </b>
        		</div>
        		
        		<div data-linea="29">
        			<label>Nº Hectáreas a certificar: </label> <?php echo $certificacionBPA['num_hectareas']; ?>						
        		</div>
        		
        		<?php 
            		if($certificacionBPA['tipo_explotacion'] == 'SA'){
            		    echo '<div data-linea="29" class="num_animales">
                        			<label>Nº Animales: </label>'. $certificacionBPA['num_animales'].						
                        	 '</div>';
            		}
        		?>
        	</fieldset>
        	
    		<fieldset>
		<legend>Tipo de Auditoría Solicitada</legend>
		<div data-linea="30" id="contenedorAuditoria">
			<table id="tbAuditorias" style="width:100%">
				<thead>
					<tr>
						<th style="width: 100%;">Tipo de Auditoría</th>
					</tr>
				</thead>
				<tbody>
				<?php 
				    $res = $ccbpa->obtenerDetalleAuditoriasSolicitadas($conexion, $idOperacion);
            		$fila = null;
            		$i = 1;
            		$tipoAuditoriaBandera = false;
            		
            		      while($fila = pg_fetch_assoc($res)){
            		          if($fila['fase'] == 'pago'){
            		              $tipoAuditoriaBandera = true;     
            		          }
                				
                			  echo '<tr>'.
                                        '<td>'.$i++.'. ' .$fila['tipo_auditoria'].'</td>'.
                        	       '</tr>';
            		      }
            	?>
				</tbody>
			</table>
		</div>
		
		<div data-linea="26" class="auditoriaInspeccion">
			<label>Fecha Auditoría Programada: </label> <?php echo ($certificacionBPA['fecha_auditoria_programada']!=null?$certificacionBPA['fecha_auditoria_programada']:'NA'); ?>
		</div>
	</fieldset>
    		<?php
		        
        break;
    case 'proveedorExterior':
        
        $tipoAdjunto = "Certificado Proveedor Exterior";
        $certificadoProveedorExterior = '';
        
        $qProveedorExterior = $cpe->obtenerDatosProveedorExteriorPorIdProveedorExterior($conexion, $idOperacion);
        $proveedorExterior = pg_fetch_assoc($qProveedorExterior);
        $qProductosProveedorExterior = $cpe->obtenerDatosProductosProveedorExteriorPorIdProveedorExterior($conexion, $idOperacion);
        $qAdjuntosProveedorExterior = $cpe->obtenerAdjuntosProveedorExteriorPorIdProveedorExteriorPorTipoAdjunto($conexion, $idOperacion, $tipoAdjunto);
        $adjuntoProveedorExterior = pg_fetch_assoc($qAdjuntosProveedorExterior);
                
        echo '<fieldset>
                <legend>Información del proveedor en el exterior</legend>
                    <div data-linea="1">
                    	<label for="nombre_fabricante">Nombre del fabricante: </label>
                		' . $proveedorExterior['nombre_fabricante'] . '
                    </div>
                    <div data-linea="2">
                    	<label for="id_pais_fabricante">País del fabricante: </label>
                    	' . $proveedorExterior['nombre_pais_fabricante'] . '
                    </div>                
                    <div data-linea="3">
                    	<label for="direccion_fabricante">Dirección del fabricante: </label>
                    	' . $proveedorExterior['direccion_fabricante'] . '
                    </div>
                    <div data-linea="4">
                    	<label for="servicio_oficial">Servicios oficiales que regulan los productos que fabrica la planta: </label>
                        ' . $proveedorExterior['servicio_oficial'] . '
                    </div>	
              </fieldset>';
        
            if(pg_num_rows($qProductosProveedorExterior) > 0){              
                      echo '<fieldset>
                        <legend>Subtipos de productos veterinarios que desea exportar</legend>
                        <table id="detalleProductosProveedor" style="width: 100%">
                			<thead>
                				<tr>
                					<th>#</th>
                					<th>Tipos de productos agregados</th>
                				</tr>
                			</thead>			
                			<tbody>';
                                $contador = 1;
                                while ($productosProveedorExterior = pg_fetch_assoc($qProductosProveedorExterior)){
                                    echo '<tr>
                                            <td>
                            				    ' . $contador++ . '
                                            </td>
                                            <td>
                            				' . $productosProveedorExterior['nombre_subtipo_producto'] . '
                                            </td>
                                          </tr>';
                                }
                			echo '</tbody>
                		</table>
                      </fieldset>';
            }
                			
            if(isset($adjuntoProveedorExterior['ruta_adjunto'])){
                $certificadoProveedorExterior = '<fieldset>
                                                    <legend>Certificado de proveedor en el exterior</legend>
                                                        <div data-linea="1">
                                                        	<label for="nombre_fabricante">Descargar: </label>
                                                            <a href="' . $adjuntoProveedorExterior['ruta_adjunto'] . '" target="_blank">Descargar certificado de proveedor en el exterior</a>
                                                        </div>
                                                </fieldset>';
            }
        
    break;
    
    case 'TransitoInternacional':
        $qTransito = $cti->abrirTransitoInternacional($conexion, $idOperacion);
        $transitoInternacional = pg_fetch_assoc($qTransito);
        
        $qTransitoInternacionalProductos = $cti->abrirTransitoInternacionalProductos($conexion, $_POST['id']);
        $qDocumentos = $cti->abrirDocumentosTransitoInternacional($conexion, $_POST['id']);
        ?>
		<fieldset>
			<legend>Certificado de Tránsito</legend>
			
			<div data-linea="0">
				<label>Tipo de Certificado: </label> <?php echo $transitoInternacional['nombre_documento']; ?> 
			</div>
			
			<div data-linea="1">
				<label>Razón social solicitante: </label> <?php echo $transitoInternacional['nombre_solicitante']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Razón social : </label> <?php echo $transitoInternacional['nombre_importador']; ?> 
			</div>
			
			<div data-linea="3">
				<label>Representante legal Importador: </label> <?php echo  $transitoInternacional['representante_legal_importador']; ?> <br/>
			</div>

	</fieldset>
	
	<fieldset>
		<legend>Datos de Tránsito Internacional</legend>	
			
			<div data-linea="1">
				<label>Régimen aduanero: </label> <?php echo $transitoInternacional['nombre_regimen_aduanero']; ?> 
			</div>
			
			<div data-linea="2">
				<label>País de origen: </label> <?php echo $transitoInternacional['nombre_pais_origen']; ?>
			</div>
			
			<div data-linea="2">
				<label>País de procedencia: </label> <?php echo $transitoInternacional['nombre_pais_procedencia']; ?>
			</div>
			
			<div data-linea="3">
				<label>País de destino: </label> <?php echo $transitoInternacional['nombre_pais_destino']; ?>
			</div>
			
			<div data-linea="4">
				<label>Lugar de ubicación de envío: </label><?php echo $transitoInternacional['nombre_ubicacion_envio']; ?> 
			</div>	
			
			<div data-linea="5">
				<label>Punto de ingreso: </label> <?php echo $transitoInternacional['nombre_punto_ingreso']; ?>
			</div>
			
			<div data-linea="5">
				<label>Punto de salida: </label> <?php echo $transitoInternacional['nombre_punto_salida']; ?>
			</div>			
			
			<div data-linea="6">
				<label>Medio de transporte: </label> <?php echo $transitoInternacional['nombre_medio_transporte']; ?>
			</div>
			
			<div data-linea="6">
				<label>Placa del vehículo: </label> <?php echo $transitoInternacional['placa_vehiculo']; ?>
			</div>		
			
			<div data-linea="7">
				<label>Ruta a seguir: </label><?php echo $transitoInternacional['ruta_seguir']; ?> 
			</div>	
	</fieldset>
	
	
	<?php 
	
	$i=1;
	while ($producto = pg_fetch_assoc($qTransitoInternacionalProductos)){
		echo '
		<fieldset>
			<legend>Producto de importación ' . $i . '</legend>
			
				<div data-linea="3">
					<label>Tipo de producto: </label> ' . $producto['nombre_tipo_producto'] . ' <br/>
				</div>
				<div data-linea="4">
					<label>Subtipo de producto: </label> ' . $producto['nombre_subtipo_producto'] . ' <br/>
				</div>
				<div data-linea="5">
					<label>Nombre del producto: </label> ' . $producto['nombre_producto'] . ' <br/>
				</div>
				<div data-linea="5">
					<label>Partida arancelaria: </label> ' . $producto['subpartida_arancelaria'] . ' <br/>
				</div>
				<div data-linea="6">
					<label>Cantidad: </label> ' . $producto['cantidad_producto'] . ' ' . $producto['nombre_unidad_cantidad'] . ' <br/>
				</div>
				<div data-linea="6">
					<label>Peso neto (Kg): </label> ' . $producto['peso_kilos'] . ' <br/>
				</div>';
		echo '</fieldset>';
		$i++;
	}
	
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
									<input name="idVue" value="'.$documento['reqNo'].'" type="hidden">
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
    break;
}
// -----------------------presentar informacion de los sitios de operador-----------------------------------------
$observacionAreas = $html = '';

if ($tipoServicio == 'Operadores') {
    foreach ($datosOperacion as $item) {
        $area = $item;
        $html .= '<fieldset>' . 
            '<legend>' . $area->tipo_area . '</legend>' . 
                '<div data-linea="a" class="destacar">' . 
                    '<label>Código de área: </label> ' . $identificador . '.' . $area->codigo_provincia . $area->codigo_sitio . $area->codigo_area . $area->secuencial . 
                '</div>' . 
                '<div data-linea="0">' . 
                        '<label>Nombre del área: </label>' . $area->nombre_area . 
                '</div>' . 
                '<div data-linea="0">' . 
                    '<label>ID del sistema: </label>' . $area->id_area . 
                '</div>' . 
                '<div data-linea="15">' . 
                    '<label>Superficie declarada: </label>' . $area->superficie_utilizada . 'm<sup>2</sup>' . 
                '</div>' . '<hr/>' . 
                '<div data-linea="5">' . 
                    '<label>Nombre del sitio: </label>' . $area->nombre_lugar . 
                '</div>' . '<div data-linea="7">' . 
                    '<label>Dirección: </label>' . $area->direccion . 
                '</div>' . 
                '<div data-linea="6">' . 
                    '<i>' . $area->parroquia . '(' . $area->canton . ' - ' . $area->provincia . ')</i>' . 
                '</div>' . 
                '<div data-linea="8">' . 
                    '<label>Referencias: </label>' . $area->referencia . 
                '</div>' . 
                '<div data-linea="9">' . 
                    '<label>Teléfono: </label>' . $area->telefono . 
                '</div>' . 
                '<hr/>' . 
                '<div data-linea="10" class="longitud">' . 
                    '<label>Longitud: </label><br/><span>' . $area->longitud . '</span>' . 
                '</div>' . 
                '<div data-linea="10" class="latitud">' . 
                    '<label>Latitud: </label><br/><span>' . $area->latitud . '</span>' . 
                '</div>' . '<div data-linea="10" class="zona">' . 
                    '<label>Zona: </label><br/><span>' . $area->zona . '</span>' . 
                '</div>' . 
                '<div data-linea="11">';
            if ($area->croquis != 0)
                $html .= '<a href="' . $area->croquis . '" target="_blank" >Ver croquis en ventana externa</a>';
            else
                $html .= 'No se ha cargado croquis';
                $html .= '</div>' . 
                            '<div data-linea="12" class="mapa">' . 
                                '<button type="button" class="mostrar">Mostrar/Ocultar mapa</button>' . 
                                '<div class="mapa" data-estado="Por cargar mapa" style="display:none;">
                                </div>' . 
                            '</div>' . 
                         '<table style="width: 100%;">
    			<tr>
    			<th><u>Producto</u><br/>Partida</th>
    			<th><u>Tipo</u><br/>Subtipo</th>
    			<th>País</th>
    			<th>Estado</th>
    			<th># Solicitud<br/>Creación</th>
    			<th>Fecha <br/>Finalización</th>
    			</tr>';
        $productos = $area->productos;
        $contadorDeProductos = 0;
        if (! empty($productos)) {
            foreach ($productos as $producto) {
                $pais = $producto->nombre_pais;
                $observacion = $producto->observacion;
                $fechaFinalizacion = $producto->fecha_finalizacion;
                if ($pais == '')
                    $pais = 'Ecuador';
                if ($observacion == '')
                    $observacion = 'N/A';
                if ($fechaFinalizacion == '')
                    $fechaFinalizacion = 'N/A';
                $html .= '<tr>' . '<td><u>' . $producto->nombre_comun . ' (' . $producto->nombre_cientifico . ')</u><br/>' . $producto->partida_arancelaria . '</td>' . '<td><u>' . $producto->tipo . '</u><br/>' . $producto->subtipo . '</td>' . '<td>' . $pais . '</td>' . '<td>' . 
                '<span class="__operacion_' . $producto->estado_operacion . '"></span>' . '</td>' . '<td>' . $producto->id_operacion . '<br/>' . $producto->fecha_creacion . '</td>
						<td>' . $fechaFinalizacion . '</td></tr>
				        <tr ><th><u>Observacion</u> </tr>' . '<td colspan="3">' . $observacion . '</td>';
            }
        }
        $html .= '</table>' . '</fieldset>';
    }
}

echo $html;
// -------------------------------------------consulta de documentos adjuntos anexos---------------------------------------------------------

if (count($ce->abrirArchivos($conexion, $idOperacion, $tipoServicio)) > 0) {
    $i = 1;
    echo '<div id="documentos" >
					<fieldset>
					<legend>Documentos adjuntos</legend>
					<table>
					<tr>
					<td><label>#</label></td>
					<td><label>Nombre</label></td>
					<td><label>Enlace</label></td>
					</tr>';
    foreach ($ce->abrirArchivos($conexion, $idOperacion, $tipoServicio) as $documento) {
        echo '<tr>
						<td>' . $i . '</td>
						<td>' . $documento['tipoArchivo'] . '</td>
						<td>
						<form id="f_' . $i . '" action="aplicaciones/general/accederDocumentoFTP.php" method="post" enctype="multipart/form-data" target="_blank">
						<input name="rutaArchivo" value="' . $documento['rutaArchivo'] . '" type="hidden">
						<input name="nombreArchivo" value="' . $documento['tipoArchivo'] . '.pdf" type="hidden">
						<input name="idVue" value="' . $documento['idVue'] . '" type="hidden">
						<a href="#" id=' . $i . ' onclick="abrirPdf(id); return false">Descargar</a>
						</form>
						</td>
						</tr>';
        $i ++;
    }
    echo '</table>
					</fieldset>
					</div>';
}

switch ($tipoServicio) {

    case 'Operadores':

        $qOperacion = $cro->abrirOperacionXid($conexion, $idOperacion);
        $operacion = pg_fetch_assoc($qOperacion);
		
		$verificacion=pg_numrows($cro->obtenerResultadoRevision($conexion,$operacion['id_operador_tipo_operacion']));
		if($verificacion>0){
			$qPlanificacionInspeccion=pg_fetch_assoc($cro->obtenerPlanificacionInspeccion($conexion,$operacion['id_operador_tipo_operacion']));
			if ($qPlanificacionInspeccion['id_planificacion']!=''){
				echo '<fieldset>
				<legend>Planificación inspección</legend>
				<div data-linea="1">
						<label>Nombre del técnico que realiza la inspección</label>
						<input type="text" id="tecnicoInspeccion" name="tecnicoInspeccion" value="'.$qPlanificacionInspeccion['nombre_tecnico'].'" readonly disabled/>
				</div>
				<div data-linea="2">
						<label>Fecha de inspección</label>
						<input type="text" id="fechaInspeccion" name="fechaInspeccion" value="'.$qPlanificacionInspeccion['fecha_inspeccion'].'" readonly disabled/>
				</div>
				<div data-linea="3">
						<label>Hora de inspección</label>
						<input type="text" id="horaInspeccion" name="horaInspeccion" placeholder="10:30" value="'.$qPlanificacionInspeccion['hora_inspeccion'].'" data-inputmask="'."'".'mask'."'".': '."'".'99:99'."'".'" readonly disabled/>						
				</div>
				</fieldset>';
			}
		}
				
        $qDocumentosAdjuntos = $cro->obtenerDocumentosAdjuntoXoperacion($conexion, $idOperacion);
        if (pg_num_rows($qDocumentosAdjuntos) != 0) {
            $documentoAdjunto = true;
        } else {
            $qDocumentosAdjuntos = $cro->obtenerDocumentosAdjuntoPorOperadorTipoOperacion($conexion, $operacion['id_operador_tipo_operacion']);
            if (pg_num_rows($qDocumentosAdjuntos) != 0) {
                $documentoAdjunto = true;
            } else {
                $documentoAdjunto = false;
            }
        }

        if ($documentoAdjunto) {
            echo '<div id="documentos" >
									<fieldset>
										<legend>Documentos adjuntos</legend>
										<table>';
            echo '<div data-linea="' . ++ $contador . '">
									</div>';

            while ($documento = pg_fetch_assoc($qDocumentosAdjuntos)) {
                echo '<div data-linea="' . ++ $contador . '"><label>' . $documento['titulo'] . '.-  </label><a href="' . $documento['ruta_documento'] . '" target="_blank" class="archivo_cargado">' . $documento['descripcion'] . '</a></div>';
            }
            echo '</table>';

            $qEstadoCargarAdjunto = $ce->obtenerFlujoOperacionEstadoActualEstadoAnterior($conexion, $idFlujoOperacion, 'cargarAdjunto');

            if (pg_num_rows($qEstadoCargarAdjunto) != 0) {
                $estadoCargarAdjunto = pg_fetch_assoc($qEstadoCargarAdjunto);
                $fechaIncio = pg_fetch_result($ce->obtenerFechasAuditoriaRegistroOperador($conexion, $operacion['id_operador_tipo_operacion'], $estadoCargarAdjunto['actual'], $estadoCargarAdjunto['anterior']), 0, 'fecha');
                $fechaFin = pg_fetch_result($ce->obtenerFechasAuditoriaRegistroOperador($conexion, $operacion['id_operador_tipo_operacion'], $estadoCargarAdjunto['predecesor'], $estadoCargarAdjunto['actual']), 0, 'fecha');
                echo '<div data-linea="' . ++ $contador . '"><label>Fecha inicial: </label><span>' . $ce->devolverFecha($fechaIncio) . '</span></div>';
                echo '<div data-linea="' . $contador . '"><label>Fecha final: </label><span>' . $ce->devolverFecha($fechaFin) . '</span></div>';
            }

            echo '</fieldset>
								</div>';
        }

        // ----------------------------------------------DOCUMENTOS GENERADOS PROCESO INSPECCION-----------------------------------------------------------------------------------------------
	if(!$consultaDocumentoCGRIA){
        $qDocumentosOperador = $cro->obtenerDocumentoGeneradoInspeccionPorIdentificador($conexion, $idOperacion);
        if (pg_num_rows($qDocumentosOperador) != 0) {
            $documentoOperador = true;
        } else {
            $qDocumentosOperador = $cro->obtenerDocumentoGeneradoInspeccionPorOperadorTipoOperacion($conexion, $operacion['id_operador_tipo_operacion']);
            if (pg_num_rows($qDocumentosOperador) != 0) {
                $documentoOperador = true;
            } else {
                $documentoOperador = false;
            }
        }
	}

        $numeroDocumento = 1;
        if ($documentoOperador) {
            echo '<div>
					<fieldset>
						<legend>Documentos generados</legend>
							<table>';
            echo '<div data-linea="' . ++ $contador . '">
								</div>';

            while ($documentoOperador = pg_fetch_assoc($qDocumentosOperador)) {
                echo '<div data-linea="' . ++ $contador . '"><label>' . $numeroDocumento ++ . '.-  </label><a href="' . $documentoOperador['ruta_archivo'] . '" target="_blank" class="archivo_cargado">' . $documentoOperador['nombre'] . '</a></div>';
            }
            echo '</table>
                    </fieldset>
					</div>';
        }

        // ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

//Inicio Registro de operador Organico//
                
				$imprimirOrganicos = "";
				
                $qDatosOrganicos = $cro->obtenerDatosRegistroOrganicosXidOperacion($conexion, $idOperacion);                
                                                
                if (pg_num_rows($qDatosOrganicos) != 0) {
										
                    $qCodigoPOA = $cro-> obtenerCodigoPoaOperador($conexion, $identificador, $idTipoOperacion);
                    $codigo = pg_fetch_assoc($qCodigoPOA);
                    
                    $codigoPOA = $codigo['codigo_poa'];
                    $subCodigoPOA = $codigo['subcodigo_poa'];
                    
                    if(pg_num_rows($qCodigoPOA) != 0){
                        
    					require_once '../../clases/Constantes.php'; 
    						
    					$constgi = new Constantes();
                        
                                    $imprimirOrganicos .= '<fieldset>
                        <legend>Resultado organicos</legend>
                        <table>
                        <thead>
                        <tr>
                        <th>Nombre agencia</th>
                        <th>Tipo transición</th>
                        <th>Tipo producción</th>
                        </tr>
                        </thead>
                        <tbody>';
                                    while ($fila = pg_fetch_assoc($qDatosOrganicos)) {
                                        $imprimirOrganicos .= '<tr>
                        <td>' . $fila['nombre_agencia_certificadora'] . '</td>' . '<td>' . $fila['nombre_tipo_transicion'] . '</td>' . '<td>' . $fila['nombre_tipo_produccion'] . '</td>
                        </tr>';
                                    }
                                    $imprimirOrganicos .= '</tbody>
                        </table>
                                    
                        <div data-linea="10">
                        <label>Código POA: </label>' . $codigoPOA . '
                        </div>
                        <div data-linea="11">
                        <label>Subcódigo POA: </label>' . $subCodigoPOA . '
                        </div>';																								  
																														
                        $salidaArchivoPoa = $codigo['ruta_poa'];
                                                
						if($operacion['estado'] == "registrado"){						
							$imprimirOrganicos .= '<div data-linea="12">
													<label>Ver Certificado POA:</label>
													<a href="' . $salidaArchivoPoa . '" target="_blank" class="archivo_cargado" id="archivo_cargado">Descargar certificado de Registro de Operador Orgánico</a>
													</div></fieldset>';												
						}
                                    
                    }else{
                        $imprimirOrganicos .= '<fieldset>
                                                <legend>Resultado organicos</legend>
                                                <div data-linea="13">
                                                <label>Ver Certificado POA:</label> El operador no posee operaciones en estado aprobado.
                                                </div>
                                                </fieldset>';
                    }
                }
                    
                //Fin Registro de operador Organico//

        $qOperadorTipoOperacion = $cro->obtenerOperadorTipoOperacionPorIdentificador($conexion, $operacion['id_operador_tipo_operacion']);
        $operadorTipoOperacion = pg_fetch_assoc($qOperadorTipoOperacion);        
        
        break;       
        
}

// ----------------------------------------------REGISTRO DE PROCESO DOCUMENTAL, FINANCIERO, INSPECCION-----------------------------------------------------------------------------------------

$info = 1;

$consulta = $ce->listarResultadoServicio($conexion, $tipoServicio, $identificador, $idOperacion);
if (pg_num_rows($consulta) != 0) {
    $validacionProceso = true;
} else {
    $consulta = $ce->listarResultadoServicio($conexion, $tipoServicio, $identificador, $operadorTipoOperacion['id_operacion']);
    if (pg_num_rows($consulta) != 0) {
        $validacionProceso = true;
    } else {
        $validacionProceso = false;
    }
}

if ($validacionProceso) {
    while ($servicio = pg_fetch_assoc($consulta)) {

        if ($info) {
            echo '<header><h1>';
            echo 'Detalle Servicio ' . $tipoServicio;
            echo '</h1></header>';
        }

        switch ($servicio['tipo_inspector']) {
            case 'Financiero':
                echo '<fieldset>';
                $ban = 0;
                $tipoInspector = $servicio['tipo_inspector'];
                $id_grupo = $servicio['id_grupo'];
                $sql = $ce->listarResultadoInspector($conexion, $tipoInspector, $id_grupo, 1, $idOperacion);
                while ($resultadoInspector = pg_fetch_assoc($sql)) {
                    $ban = 1;
                    $fechaI = $ce->devolverFecha($resultadoInspector['fecha_orden_pago']);
                    $fechaF = $ce->devolverFecha($resultadoInspector['fecha_facturacion']);
                    $estado = $resultadoInspector['estado_sri'];
                    $datosOperador = pg_fetch_assoc($ce->datosOperador($conexion, $resultadoInspector['identificador_usuario']));
                    $infoOperador = $datosOperador['nombre'] . '  ' . $datosOperador['apellido'];
                    if ($datosOperador['nombre'] == 'G.U.I.A')
                        echo $infoOperador = $datosOperador['nombre'] . 'ss';
                    $observacion = $resultadoInspector['observacion'];
                    if ($resultadoInspector['estado'] == 9) {
                        $estado = "Eliminada";
                        $observacion = $resultadoInspector['observacion_eliminacion'];
                    }

                    echo '<legend>Financiero</legend>';
                    echo '<div data-linea="1">';
                    // echo '<label>Codigo: </label><span>'.$resultadoInspector['numero_solicitud'].'</span>';
                    echo '</div>';
                    echo '<div data-linea="2">
            						<label>Técnico: </label><span>' . $infoOperador . '</span>
            						</div>';
                    echo '<div data-linea="3">';
                    echo $estado == '' ? '' : '<label>Estado Sri: </label><span>' . strtoupper($estado) . '</span>';
                    echo '</div>
            						<div data-linea="4">';
                    echo $fechaI == '' ? '' : '<label>Fecha Asignación Tasa: </label><span>' . $fechaI . '</span>';
                    echo '</div>';
                    if ($tipoServicio != 'Operadores') {
                        echo '<div data-linea="5">';
                        echo $fechaF == '' ? '' : '<label>Fecha Facturación: </label><span>' . $fechaF . '</span>';
                        echo '</div>';
                    }
                    echo '<div data-linea="6">';
                    echo $resultadoInspector['orden_pago'] == '' ? '' : '<label>Archivo Adjunto: </label><a href=' . $resultadoInspector['orden_pago'] . ' target="_blank" class="archivo_cargado" id="archivo_cargado">Orden Pago</a>';
                    echo '</div>
            						<div data-linea="7">';
                    echo $resultadoInspector['factura'] == '' ? '' : '<label>Archivo Adjunto: </label><a href=' . $resultadoInspector['factura'] . ' target="_blank" class="archivo_cargado" id="archivo_cargado">Factura generada</a>';
                    echo '</div>
            						<div data-linea="8">';
                    echo $observacion == '' ? '' : '<label>Observación: </label><span>' . $observacion . '</span>';
                    echo '</div>';
                }
                if ($ban == 0 and $resultadoInspector = pg_fetch_assoc($ce->listarResultadoInspector($conexion, $servicio['tipo_inspector'], $servicio['id_grupo'], 2, $idOperacion))) {

                    $fechaI = $ce->devolverFecha($resultadoInspector['fecha_asignacion_monto']);
                    $fechaF = $ce->devolverFecha($resultadoInspector['fecha_facturacion']);
                    $estado = $resultadoInspector['estado'];
                    $datosOperador = pg_fetch_assoc($ce->datosOperador($conexion, $resultadoInspector['identificador_inspector']));
                    $infoOperador = $datosOperador['nombre'] . '  ' . $datosOperador['apellido'];
                    if ($datosOperador['nombre'] == 'G.U.I.A')
                        $infoOperador = $datosOperador['nombre'];
                    echo '<legend>Financiero</legend>';
                    echo '<div data-linea="1">
            						<label>Técnico: </label><span>' . $infoOperador . '</span>
            						</div>
            						<div data-linea="2">';
                    echo $estado == '' ? '' : '<label>Estado: </label><span>N/A</span>';
                    echo '</div>
            						<div data-linea="3">';
                    echo $fechaI == '' ? '' : '<label>Fecha Asignación Tasa: </label><span>' . $fechaI . '</span>';
                    echo '</div>';
                    if ($tipoServicio != 'Operadores') {
                        echo '<div data-linea="4">';
                        echo $fechaF == '' ? '' : '<label>Fecha Facturación: </label><span>' . $fechaF . '</span>';
                        echo '</div>';
                    }
                    echo '<div data-linea="5">';
                    echo $resultadoInspector['factura'] == '' ? '' : '<label>Archivo Adjunto: </label><a href=' . $resultadoInspector['factura'] . ' target="_blank" class="archivo_cargado" id="archivo_cargado">Factura generada</a>';
                    echo '</div>
            						<div data-linea="6">';
                    echo $resultadoInspector['observacion'] == '' ? '' : '<label>Observación: </label><span>' . $resultadoInspector['observacion'] . '</span>';
                    echo '</div>';
                }

                if ($tipoServicio == 'Operadores') {
                    $qEstadoPago = $ce->obtenerFlujoOperacionEstadoActualEstadoAnterior($conexion, $idFlujoOperacion, 'pago');

                    if (pg_num_rows($qEstadoPago) != 0) {
                        $estadoPago = pg_fetch_assoc($qEstadoPago);
                        $fechaIncio = pg_fetch_result($ce->obtenerFechasAuditoriaRegistroOperador($conexion, $operacion['id_operador_tipo_operacion'], $estadoPago['actual'], $estadoPago['anterior']), 0, 'fecha');
                        /*
                         * $qEstadoVerificacion= $ce->obtenerFlujoOperacionEstadoActualEstadoAnterior($conexion, $idFlujoOperacion, $estadoPago['predecesor']);
                         * if(pg_num_rows($qEstadoVerificacion)!= 0){
                         * $estadoVerificacion = pg_fetch_assoc($qEstadoVerificacion);
                         * $fechaFin = pg_fetch_result($ce->obtenerFechasAuditoriaRegistroOperador($conexion, $operacion['id_operador_tipo_operacion'], $estadoVerificacion['predecesor'], $estadoVerificacion['actual']), 0, 'fecha');
                         * }
                         */
                        echo '<div data-linea="10"><label>Fecha inicial: </label><span>' . $ce->devolverFecha($fechaIncio) . '</span></div>';
                    }
                }
                echo $fechaF == '' ? '' : '<div data-linea="10"><label>Fecha Fin: </label><span>' . $fechaF . '</span></div>';
                echo '</fieldset>';

                break;
            case 'Documental':
                $sql = $ce->listarResultadoInspector($conexion, $servicio['tipo_inspector'], $servicio['id_grupo'], 1, $idOperacion);

                while ($resultadoInspector = pg_fetch_assoc($sql)) {
                    $datosOperador = pg_fetch_assoc($ce->datosOperador($conexion, $resultadoInspector['identificador_inspector']));
                    $fechaI = $fechaServicio;
                    $fechaF = $ce->devolverFecha($resultadoInspector['fecha_inspeccion']);
                    $estado = $resultadoInspector['estado'];
                    if ($resultadoInspector['estado'] == 'pago')
                        $estado = 'Aprobado';
                    if ($resultadoInspector['estado'] == 'inspeccion')
                        $estado = 'Aprobado';

                    echo '<fieldset>';
                    echo '<legend>Revisión Documental</legend>';
                    echo '<div data-linea="1">
       							  <label>Técnico: </label><span>' . $datosOperador['nombre'] . '  ' . $datosOperador['apellido'] . '</span>
    		                      </div>
    		                      <div data-linea="2">
    		                      <label>Estado: </label> <span>' . strtoupper($estado) . '</span>
    		                      </div>';
                    if ($tipoServicio != 'Operadores') {
                        echo '<div data-linea="5">';
                        echo '<label>Fecha Respuesta: </label><span>' . $fechaF . '</span>';
                        echo '</div>';
                        echo '<div data-linea="3">';
                        echo (($resultadoInspector['ruta_archivo_documental']=='0' || $resultadoInspector['ruta_archivo_documental']=='')? '':'<label>Archivo Adjunto: </label><a href='.$resultadoInspector['ruta_archivo_documental'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo</a>');
                        echo '</div>';
                    }
                    echo '<div data-iinea="6">';
                    echo '</div>
            				      <div data-linea="7">';
                    echo $resultadoInspector['observacion'] == '' ? '' : '<label>Observación: </label><span>' . $resultadoInspector['observacion'] . '</span>';
                    echo '</div>';
                    if ($tipoServicio == 'Operadores') {
                        $qFechaActual = $ce->obtenerFechaInicoEstadoOperacion($conexion, $operacion['id_operador_tipo_operacion'], $idOperacion, $estado, 'documental', $resultadoInspector['fecha_inspeccion']);
                        if (pg_num_rows($qFechaActual) != 0) {
                            echo '<div data-linea="8"><label>Fecha inicial: </label><span>' . $ce->devolverFecha(pg_fetch_result($qFechaActual, 0, 'fecha')) . '</span></div>';
                        }
                        echo '<div data-linea="8"><label>Fecha final: </label><span>' . $fechaF . '</span></div>';
                    }
                    echo '</fieldset>';
                }
                


                break;
            case 'Técnico':
                $ban = 0;
                $identif = '.';
                $verfG = $ce->verificarAgrupacion($conexion, $servicio['id_grupo']);
                if ($rows = pg_num_rows($verfG) == 1) {
                    $sql = $ce->listarResultadoInspector($conexion, $servicio['tipo_inspector'], $servicio['id_grupo'], 1, $idOperacion);
                    $ban = 1;
                    $identif = '';
                } else
                    $sql = $ce->listarResultadoInspector($conexion, $servicio['tipo_inspector'], $servicio['id_grupo'], 2, $idOperacion);

                while ($resultadoInspector = pg_fetch_assoc($sql)) {
                    $datosOperador = pg_fetch_assoc($ce->datosOperador($conexion, $resultadoInspector['identificador_inspector']));
                    $fechaI = $fechaServicio;
                    $fechaF = $ce->devolverFecha($resultadoInspector['fecha_inspeccion']);
                    $estado = $resultadoInspector['estado'];
                    if ($resultadoInspector['estado'] == 'pago')
                        $estado = 'APROBADO';
                    if ($resultadoInspector['estado'] == 'inspeccion')
                        $estado = 'APROBADO';

                    echo '<fieldset>';
                    echo '<legend>Inspección' . $identif . '</legend>';
                    if ($zoosanitario['codigo_sitio'] != '') {
                        echo '<div data-linea="1">
       								  <label>Código de sitio: </label><span>' . $zoosanitario['codigo_sitio'] . '</span>
    		                	      </div>
    		                	      <div data-linea="2">
       								  <label>Fecha de Inspección: </label><span>' . $zoosanitario['fecha_inspeccion'] . '</span>
    		                	      </div>';
                    }
                    echo ' <div data-linea="3">
       								  <label>Técnico: </label><span>' . $datosOperador['nombre'] . '  ' . $datosOperador['apellido'] . '</span>
    		                	      </div>
    		                	      <div data-linea="4">
	    		                      <label>Estado: </label> <span>' . strtoupper($estado) . '</span>';
                    if ($tipoServicio != 'Operadores') {
                        echo '<div data-linea="6">
    		                  	           <label>Fecha Respuesta: </label><span>' . $fechaF . '</span>
    		                  	           </div>';
                    }
                    echo '<div data-iinea="7">';
                    echo $resultadoInspector['ruta_archivo'] == '0' ? '' : '<label>Archivo Adjunto: </label><a href=' . $resultadoInspector['ruta_archivo'] . ' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo</a>';
                    echo '</div>';

                    echo '<div data-linea="8">';
                    echo $resultadoInspector['observacion'] == '' ? '' : '<label>Observación: </label><span>' . $resultadoInspector['observacion'] . '</span>';
                    echo '</div>';
                    if ($tipoServicio == 'Operadores') {
                        $qFechaActual = $ce->obtenerFechaInicoEstadoOperacion($conexion, $operacion['id_operador_tipo_operacion'], $idOperacion, $estado, 'inspeccion', $resultadoInspector['fecha_inspeccion']);
                        if (pg_num_rows($qFechaActual) != 0) {
                            echo '<div data-linea="10"><label>Fecha inicial: </label><span>' . $ce->devolverFecha(pg_fetch_result($qFechaActual, 0, 'fecha')) . '</span></div>';
                        }
                        echo '<div data-linea="10"><label>Fecha final: </label><span>' . $fechaF . '</span></div>';
                    }

                    echo '</fieldset>';
                }
                break;
                
            case 'Aprobación':
                $sql = $ce->listarResultadoInspector($conexion, $servicio['tipo_inspector'], $servicio['id_grupo'], 1, $idOperacion);
                
                while ($resultadoInspector = pg_fetch_assoc($sql)) {
                    $datosOperador = pg_fetch_assoc($ce->datosOperador($conexion, $resultadoInspector['identificador_inspector']));
                    $fechaF = $ce->devolverFecha($resultadoInspector['fecha_inspeccion']);
                    $estado = $resultadoInspector['estado'];
                                                
                            echo '<fieldset>';
                            echo '<legend>Aprobación</legend>';
                            echo '<div data-linea="1">
       							  <label>Técnico: </label><span>' . $datosOperador['nombre'] . '  ' . $datosOperador['apellido'] . '</span>
    		                      </div>
    		                      <div data-linea="2">
    		                      <label>Estado: </label> <span>' . strtoupper($estado) . '</span>
    		                      </div>';
                            echo '<div data-linea="5">';
                            echo '<label>Fecha Respuesta: </label><span>' . $fechaF . '</span>';
                            echo '</div>';
            				echo '<div data-linea="7">';
                            echo $resultadoInspector['observacion'] == '' ? '' : '<label>Observación: </label><span>' . $resultadoInspector['observacion'] . '</span>';
                            echo '</div>';
                            
                            echo '</fieldset>';
                }
                    break;
        }
        $info = 0;
    }                
		echo $imprimirOrganicos;
		echo $certificadoProveedorExterior;
}
if ($info) {
    if ($idVue != "" and $tipoServicio == 'Operadores') {
        echo '<header><h1>';
        echo 'Detalle Servicio ' . $tipoServicio;
        echo '</h1></header>';
        echo '<fieldset>';
        echo '<legend>VUE</legend>';
        echo '<span>Aprobado en Ventanilla Unica Ecuatoriana</span>';
        echo '<h1>Codigo: ' . $idVue . '</h1>';
        echo '</fieldset>';
    }
}
?>
<script>
  $(document).ready(function () { 
        $("div.mapa div").hide();
        distribuirLineas();     
  });
  
  $("fieldset").on("click","div.mapa button",function () {
        mapaDestino = $(this).parent().find("div");
        if ($(this).hasClass("mostrar")) {
            $(this).removeClass("mostrar");
            $(this).addClass("ocultar");
            mapaDestino.show();
            {
                longitud = $(this).parent().parent().find("div.longitud span").html();
                latitud = $(this).parent().parent().find("div.latitud span").html();
                zona = $(this).parent().parent().find("div.zona span").html();
                iniciarMapa(latitud, longitud, zona, 10, mapaDestino);
            }
        } else {
            $(this).removeClass("ocultar");
            $(this).addClass("mostrar");
            mapaDestino.hide();
        }
    });

 function iniciarMapa(latitud, longitud, zona, porcentajeZoom, mapaDestino) {
        var _mapOptions = {
            zoom: porcentajeZoom,
            center: new google.maps.LatLng(latitud, longitud),
            mapTypeControl: false,
            navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},

            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var _map = new google.maps.Map(mapaDestino[0], _mapOptions);
        if (latitud != '' && longitud != '' && zona != '') {
            var latLog = new Array(2);
            latLog = UTM2Lat(latitud, longitud, zona);
            var _latitud = latLog[0];
            var _longitud = latLog[1];
            marker = null;
            placeMarker(marker, new google.maps.LatLng(_latitud, _longitud), _map);
        }
    }
 function placeMarker(marker, position, map) {
        if (marker != null)
            marker.setMap(null);
        marker = new google.maps.Marker({
            position: position,
            map: map
        });
        map.panTo(position);
        $("#zoom").val(map.zoom);
        var xya = new Array(3);
        xya = NuevaLat2UTM(position.lat(), position.lng());
        $("#latitud").val(xya[0]);
        $("#longitud").val(xya[1]);
        $("#zona").val(xya[2]);
    }   
 function abrirPdf(id)
    {
		$("#f_"+id).submit();
 	}

	 $("#datosOperadorCuarentena").submit(function (e) {
	 	e.preventDefault();
	     ejecutarJson($(this), new exito());
	 });
	
	 $("#datosOperadorCuarentena").on("click",".mo_areas[type='button']",function () {
	     $(this).parent().parent().find("#resultadoOperadorCuarentena").toggle();
	 });

	 function exito(){
	        this.ejecutar = function(msg){

	            $(msg.destino).html(msg.resultado);
	            $(msg.destino).parent().find("#datosOperadorCuarentena button").attr("type","button");
	            distribuirLineas();
	        };
	    }
	
</script>