<?php

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFinanciero.php';
require_once '../../clases/ControladorCertificados.php';

$conexion = new Conexion();
$cf = new ControladorFinanciero();
$cc = new ControladorCertificados();

$area = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
$opcion = htmlspecialchars ($_POST['opcionServicio'],ENT_NOQUOTES,'UTF-8');

	switch ($opcion){
				
		case 'tarifario':
			
			if($area != "todos"){
			
			$idServicio = pg_fetch_assoc($cc->obtenerIdServicioXarea($conexion, $area, 'activo'));
			$tarifario = $cc->obtenerServicioXarea($conexion, $idServicio['id_servicio'], 'TODO');
			
			echo '<th>Tipo de transacción</th>';	
			
			while($fila = pg_fetch_assoc($tarifario)){
				$arrayTarifario[]= array('idServicio'=>$fila['id_servicio'], 'concepto' =>$fila['concepto'], 'codigo'=>$fila['codigo'], 'idCategoria'=>$fila['id_categoria_servicio'], 'valor'=>$fila['valor']*1, 'iva'=>$fila['iva'], 'medida'=>$fila['unidad_medida'], 'subsidio'=>$fila['subsidio']);
			}
				
			echo'<td colspan="4">
					<select id="transaccion" name="transaccion" style="width: 400px;">
					</select><td>';
			}

		break;
	
			default:
			echo 'Acción desconocida';
	}

?>

<script type="text/javascript">  

var vOpcion = <?php echo json_encode($opcion);?>;
var array_tarifario = <?php echo json_encode($arrayTarifario); ?>;

if(vOpcion == 'tarifario'){
	distribuirLineas();	
		if(array_tarifario!=null){
		sdatos ='0';
		sdatos+='<option value="todos">Todos</option>'
		for(var i=0;i<array_tarifario.length;i++){
		    if (array_tarifario[i]['idCategoria'] != '1'){
		    	switch(array_tarifario[i]['idCategoria']){
			    	case '2':
				    	sdatos += '<optgroup label="'+array_tarifario[i]['codigo']+'- '+array_tarifario[i]['concepto']+'">';
			    	break;
			    	case '3':
			    		var concepto = array_tarifario[i]['concepto'];
			    		if(concepto.length > 100){
			    			var parteConcepto = concepto.substring(0, 100)+'...';
					    }else{
					    	var parteConcepto = concepto;
						}		    	    
			    		sdatos += '<option title = "'+array_tarifario[i]['concepto']+ ' - VALOR: '+array_tarifario[i]['valor']+' - UNIDAD MEDIDA: '+array_tarifario[i]['medida']+'" value="'+array_tarifario[i]['idServicio']+'" data-precio="'+array_tarifario[i]['valor']+'" data-subsidio="'+array_tarifario[i]['subsidio']+'" data-iva="'+array_tarifario[i]['iva']+'">'+array_tarifario[i]['codigo']+'- '+parteConcepto+'</option>';
			    	break;
		    	}
			}
		}
		
		$('#transaccion').html(sdatos);
		
}}


$(document).ready(function(){

	distribuirLineas();
	construirValidador();

});

</script>
