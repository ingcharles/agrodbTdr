<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';

$conexion = new Conexion();
$cc = new ControladorCertificados();

$identificadorUsuarioRegistro = $_SESSION['usuario'];
	
	$distritos = $cc -> listarTodosDistritos($conexion);
	$institucion = pg_fetch_assoc($cc->listarDatosInstitucion($conexion,$identificadorUsuarioRegistro));
	
	$numeroEstablecimientos = $cc -> listarTodosEstablecimientosConsumoComprobante($conexion);
	
	while($fila = pg_fetch_assoc($numeroEstablecimientos)){
		$establecimiento[]= array(numEstablecimiento=>$fila['numero_establecimiento'], ruc=>$fila['ruc']);
	}
?>

<header>
    <h1>Comprobantes electrónicos</h1>
<nav>

 <form id='listaComprobantes' data-rutaAplicacion='consumoComprobantes' data-opcion='listaComprobantesFiltrados' data-destino="tabla">      
  	<table class="filtro">
		<tr>
			<th>Solicitud</th>
			<td>
			<select name="tipoBusquedaDocumento" id="tipoBusquedaDocumento" style="width: 100%;">
				<option value="01">Factura</option>
			</select>
		
			<th>Ruc</th>
			<td>
				<select id="rucDistrito" name="rucDistrito" style="width: 100%;">
					<option value="" selected="selected">Seleccione...</option>
						<?php 
							while($fila = pg_fetch_assoc($distritos)){
						echo '<option value="' . $fila['ruc'] . '">' . $fila['ruc'] . '</option>';
						}
						?>
				</select> 
		</tr>
		<tr>
			<th>Establecimiento</th>
			<td>
				<select id="numeroEstablecimiento" name="numeroEstablecimiento" style="width: 100%;">
				</select>
			</td>
		
			<th>P. Emisión</th>
			<td>
	 			<select id="puntoEmision" name="puntoEmision" style="width: 100%;">
	 				<option value="001">001</option>
	 			</select>
	   		</td>
		</tr>
		<tr> 
			<th># Factura</th>
			<td>
				<input type="text" id="txtDocumentoBusqueda" name="txtDocumentoBusqueda" placeholder=" Ej.: 000000001"/>	
			</td>
		</tr>

		<tr>	
			<td colspan="5"><button id="buscar" name="buscar">Buscar</button></td>
		</tr>
	</table>
    
 </form>   
</nav>
 
 </header>
 <div id="tabla"></div>
	
<script>

var array_establecimiento= <?php echo json_encode($establecimiento); ?>;
	
    $(document).ready(function(){
    	distribuirLineas();
    	construirValidador();
    	
    	cargarValorDefecto("rucDistrito","<?php echo $institucion['ruc'];?>");	
    	sestablecimiento = '<option value="">Establecimiento....</option>';
    	
    	for(var i=0;i<array_establecimiento.length;i++){
    		if(array_establecimiento[i]['ruc'] == $('#rucDistrito').val()){        		
    			sestablecimiento += '<option value="'+array_establecimiento[i]['numEstablecimiento']+'">'+array_establecimiento[i]['numEstablecimiento']+'</option>';
    		}   
        }	
    	
    	$('#numeroEstablecimiento').html(sestablecimiento);
   
    	cargarValorDefecto("numeroEstablecimiento","<?php echo $institucion['numero_establecimiento'];?>");
        cargarValorDefecto("puntoEmision","<?php echo $institucion['punto_emision'];?>");

        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
    	 	
    });

    $("#listaComprobantes").submit(function(e){
	    $('#listaComprobantes').attr('data-opcion','listaComprobantesFiltrados');
    	$('#listaComprobantes').attr('data-destino','tabla');
    	abrir($(this),e,false);
    });



    $("#rucDistrito").change(function (event) {

    	sestablecimiento = '';
        
       	for(var i=0;i<array_establecimiento.length;i++){
    		if(array_establecimiento[i]['ruc'] == $('#rucDistrito').val()){        		
    			sestablecimiento += '<option value="'+array_establecimiento[i]['numEstablecimiento']+'">'+array_establecimiento[i]['numEstablecimiento']+'</option>';
    		}   
        }	
    	
    	$('#numeroEstablecimiento').html(sestablecimiento);
    	
    });

    
</script>
