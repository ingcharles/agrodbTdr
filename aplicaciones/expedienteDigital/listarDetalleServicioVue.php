<?php
    session_start();

    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRegistroOperador.php';
    require_once '../../clases/ControladorExpedienteDigital.php';
    
    $ce = new ControladorExpedienteDigital();
    $conexion = new Conexion ();
    //-----------------------------------------------------------------------------------
    $area="Todas";
    $provincia="Todas";
    $numeroSolicitud=htmlspecialchars($_POST['numeroSolicitud'], ENT_NOQUOTES, 'UTF-8');;
    $tipoServicio=htmlspecialchars($_POST['servicio'], ENT_NOQUOTES, 'UTF-8');
    
    $consulta=$ce->listarDetalleServiciosVue($conexion,$tipoServicio,$numeroSolicitud);
    
    
    $ban=1;
   /* if(count($ce->listarDetalles($consulta,$tipoServicio,0,$provincia,$area))>0 ){
    	$ban=0;
       $itemsFiltrados=$ce->listarDetalles($consulta,$tipoServicio,0,$provincia,$area);
    }
    //$itemsFiltrados= $ce->listarDetalles($consulta,$tipoServicio,0,$provincia,$area);
      
    */
    $contador=0;
     $itemsFiltrados[] = array();
    while ($servicio = pg_fetch_assoc($consulta)) {
    	$fecha = $ce->devolverFecha($servicio['fecha']);
    	switch ($tipoServicio){
    		case 'Operadores':
    			$identificador=$servicio['identificador'];			
    			$idOperacion= $servicio['id_operacion'];
    			$columna0=$idOperacion;
    			$columna1=$servicio['subtipo'];
    			$columna2=$servicio['producto'];
    			$columna3=$servicio['operacion'];
    			$datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$servicio['id_tipo_operacion'].'.'.$fecha.'.'.$servicio['id_flujo_operacion'];
    			break; 
    	    case 'ROCE':
    				$identificador=$servicio['identificador'];
    				$idOperacion= $servicio['id_operacion'];
    				$columna0=$idOperacion;
    				$columna1=$servicio['subtipo'];
    				$columna2=$servicio['producto'];
    				$columna3=$servicio['operacion'];
    				$datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$servicio['id_tipo_operacion'].'.'.$fecha;
    				break;
    		case 'DDA':
    			
    			$identificador=$servicio['identificador'];
    			$idOperacion= $servicio['id_destinacion_aduanera'];
    			$columna0=$identificador;
    			$columna1=$servicio['id_vue'];
    			$columna2=$tipoServicio;
    			$columna3=$servicio['tipo_transporte'];
    			$datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$tipoServicio.'.'.$fecha;
    			break;
    		case 'Fitosanitario':
    			
    			$identificador=$servicio['identificador'];
    			$idOperacion= $servicio['id_fito_exportacion'];
    			$columna0=$identificador;
    			$columna1=$servicio['id_vue'];
    			$columna2=$tipoServicio;
    			$columna3=$servicio['transporte'];
    			$datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$tipoServicio.'.'.$fecha;
    			break;
    		case 'CLV':
    			
    			$identificador=$servicio['identificador'];
    			$idOperacion= $servicio['id_clv'];
    			$columna0=$identificador;
    			$columna1=$servicio['id_vue'];
    			$columna2='Certificado de Libre Venta';
    			$columna3=$servicio['pais'];
    			$datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$tipoServicio.'.'.$fecha;
       			break;
    		case 'Importación':
    			
    			$identificador=$servicio['identificador'];
    			$idOperacion= $servicio['id_importacion'];
    			$columna0=$idOperacion;
    			$columna1=$servicio['id_vue'];
    			$columna2=$tipoServicio;
    			$columna3=$servicio['tipo_transporte'];
    			$datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$tipoServicio.'.'.$fecha;
    			break;
    		case 'Zoosanitario':
    			
    			$identificador=$servicio['identificador'];
    			$idOperacion= $servicio['id_zoo_exportacion'];
    			$columna0=$identificador;
    			$columna1=$servicio['id_vue'];
    			$columna2=$tipoServicio;
    			$columna3=$servicio['transporte'];
    			$datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$tipoServicio.'.'.$fecha;
    			break;
    			
    		case 'certificacionBPA':
    		    
    		    $identificador=$servicio['identificador'];
    		    $idOperacion= $servicio['id_solicitud'];
    		    $columna0=$identificador;
    		    $columna1=$servicio['id_solicitud'];
    		    $columna2=$servicio['tipo_solicitud'];
    		    $columna3=($servicio['tipo_explotacion']=='SA'?'Sanidad Animal':($servicio['tipo_explotacion']=='SV'?'Sanidad Vegetal':'Inocuidad de los Alimentos'));
    		    $datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_solicitud'].'.'.$tipoServicio.'.'.$fecha.'.'.$servicio['es_asociacion'];
    		    break;
    		    
    		case 'proveedorExterior':
    		    
    		    $identificador=$servicio['identificador'];
    		    $idOperacion= $servicio['id_proveedor_exterior'];
    		    $columna0=$identificador;
    		    $columna1=$servicio['id_proveedor_exterior'];
    		    $columna2=$servicio['codigo_creacion_solicitud'];
    		    $columna3=$servicio['estado_solicitud'];
    		    $datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_solicitud'].'.'.$tipoServicio.'.'.$fecha;
    		    break;
    		    
    		case 'TransitoInternacional':
    		    
    		    $identificador=$servicio['identificador'];
    		    $idOperacion= $servicio['id_transito_internacional'];
    		    $columna0=$servicio['nombre_importador'];
    		    $columna1=$servicio['id_vue'];
    		    $columna2=$tipoServicio;
    		    $columna3=$servicio['nombre_provincia'];
    		    $datos=$area.'.'.$provincia.'.'.$identificador.'.'.$tipoServicio.'.'.$servicio['id_vue'].'.'.$tipoServicio.'.'.$fecha;
    		    break;
    	}
    	$ban=0;
        $itemsFiltrados[] = array('<tr
		id="' . $idOperacion . '"
		class="item"
		data-rutaAplicacion="expedienteDigital"
		data-opcion="abrirUsuario"
        data-idOpcion="'.$datos.'"
		ondragstart="drag(event)"
		draggable="true"
		data-destino="detalleItem">
		<td>' . ++$contador . '</td>
		<td style="white-space:nowrap;">'.$columna0.'</td>
		<td>' . $columna1 . '</td>
        <td>' . $columna2 . '</td>
        <td>' . $columna3 . '</td>
        		
				</tr>');
    }if($ban==1){
			 ?><script type="text/javascript">
		   	    $("#lestado").text('No existen datos para la consulta ...').addClass("alerta");
		   	    </script>
		   	 <?php 
		     }else{
?>
<div id="paginacion" class="normal">  </div>
<?php 
echo $ce->encabezadoDetalleServicio($tipoServicio);
	}
 ?>
<script type="text/javascript">
    var itemInicial = 0;
    $(document).ready(function(){
        $("#listadoItems").removeClass("comunes");
        $("#listadoItems").addClass("lista");
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un documento para revisarlo.</div>');
        construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);

    });
</script>