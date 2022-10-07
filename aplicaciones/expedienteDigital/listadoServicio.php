<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorExpedienteDigital.php';

	$conexion = new Conexion();
	$ce = new ControladorExpedienteDigital();

	if(htmlspecialchars($_POST['id'],ENT_NOQUOTES,'UTF-8') <> ""){
		$identificador=htmlspecialchars($_POST['id'],ENT_NOQUOTES,'UTF-8');
		$tmp = explode(".", htmlspecialchars($_POST['opcion'], ENT_NOQUOTES, 'UTF-8'));
		$area = $tmp[0];
		$provincia = $tmp[1];
	}else{
		$identificador = htmlspecialchars ($_POST['textoDeBusqueda'],ENT_NOQUOTES,'UTF-8');
		$provincia = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
		$tipo = htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
	}

echo '<div id="respuesta" class="contenedor">	
	  <div class="elementos"></div>
	  </div>';
$contador = 0; $ban=1;
for($i=1; $i<10; $i++){			
	if($listaServicio=pg_fetch_assoc($ce->listarServicioCliente($conexion,$i,$identificador,$provincia)))
	{   
		$ban=0; 
		//$fecha = $ce->devolverFecha($listaServicio['fecha_creacion']);
		$fecha = explode(" ",$listaServicio['fecha']);
		switch ($i){
			case 1: $nombre = 'REGISTRO OPERADOR'; $tipoSolicitud='Operadores';			        
			break;
			case 2: $nombre = 'DDA'; $tipoSolicitud='DDA'; 			        
			break;
			case 3: $nombre = 'FITO EXPORTACION'; $tipoSolicitud='Fitosanitario';
			break;
			case 4: $nombre = 'CLV'; $tipoSolicitud='CLV'; 
			break;
			case 5: $nombre = 'IMPORTACIONES'; $tipoSolicitud='Importación';
			break;
			case 6: $nombre = 'ZOO EXPORTACION'; $tipoSolicitud='Zoosanitario';
			break;
			case 7: $nombre = 'CERTIFICACIÓN BPA'; $tipoSolicitud='certificacionBPA';
			break;
			case 8: $nombre = 'PROVEEDOR EN EL EXTERIOR'; $tipoSolicitud='proveedorExterior';
			break;
			case 9: $nombre = 'TRANSITO INTERNACIONAL'; $tipoSolicitud='TransitoInternacional';
			break;
		}			
		$categoria = 'respuesta';
		$contenido = '<article
							id="'.$tipoSolicitud.'"
							class="item"
							data-rutaAplicacion="expedienteDigital"
							data-opcion="listadoDetalleServicios"
				            data-idOpcion="Todas.'.$provincia.'.'.$i.'.'.$identificador.'"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="respuesta"
							data-sitio="sitio">
							<span class="ordinal">'.++$contador.'</span>
							<span> '. $nombre .'</span>
							<aside>'.$fecha[0].'</aside>
					</article>';
		         ?>
				 <script type="text/javascript">
										var contenido = <?php echo json_encode($contenido);?>;
										var categoria = <?php echo json_encode($categoria);?>;
										$("#"+categoria+" div.elementos").append(contenido);
				 </script>
				 <?php 
		   }
		} if($ban==1){
			 ?><script type="text/javascript">
		   	    $("#lestado").text('No existen datos para la consulta ...').addClass("alerta");
		   	    </script>
		   	 <?php 
		     }			
?>
<script type="text/javascript"> 
	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un documento para revisarlo.</div>');
</script>

