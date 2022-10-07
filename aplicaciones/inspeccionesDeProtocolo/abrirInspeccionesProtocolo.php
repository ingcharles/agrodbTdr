<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProtocolos.php';

$conexion = new Conexion();
$cp = new ControladorProtocolos();

$idProtocoloArea = htmlspecialchars($_POST['id'], ENT_NOQUOTES, 'UTF-8');

$qListaAreasProtocolosAsignados = $cp->obtenerAreasProtocolosAsignados($conexion, $idProtocoloArea);
$qObtenerAreasProtocolos = $cp->obtenerAreasProtocolos($conexion, $idProtocoloArea);
$listaAreasProtocolos = pg_fetch_assoc($qObtenerAreasProtocolos);

?>

<header>
	<h1>Abrir Inspección de Protocolo</h1>
</header>
<div id="estado"></div>

<fieldset id="CabeceraProtocolos">
	<legend>Datos generales</legend>
	
    <div data-linea="1">
		<label>Razon social:</label>
		<input type="text" id="razonSocial" name="razonSocial" value="<?php echo $listaAreasProtocolos ['nombre_operador']; ?>" disabled />
    </div>
                    	
	<div data-linea="2">
		<label>Sitio:</label> 
		<input type="text" id="sitio" name="sitio" value="<?php echo $listaAreasProtocolos ['codigo_sitio'] . ' - ' . $listaAreasProtocolos ['nombre_sitio']; ?>" disabled />
	</div>

	<div data-linea="3">
		<label>Área:</label> 
		<input type="text" id="area" name="area" value="<?php echo $listaAreasProtocolos ['codigo_area'] . ' - ' . $listaAreasProtocolos ['nombre_area']; ?>" disabled />
	</div>

	<div data-linea="4">
		<label>Operacion:</label> 
		<input type="text" id="operacion" name="operacion" value="<?php echo $listaAreasProtocolos ['nombre_tipo_operacion']; ?>" disabled />
	</div>

</fieldset>

<fieldset id="protocolosAsignados">
	<legend>Protocolos asignados</legend>
	<table id="tabla" style="width: 100%">
		<tr style="font-weight: bold;">
			<th>Protocolo</th>
			<th>Resultado</th>
			<th>Opción</th>
		</tr>
		<tbody id="protocoloComercio">
		<?php
            while ($protocolo = pg_fetch_assoc($qListaAreasProtocolosAsignados)) {
                echo $cp->imprimirLineaProtocoloAreaAsignado($protocolo['id_protocolo_area_asignado'], $protocolo['nombre_protocolo'], $protocolo['estado_protocolo_asignado']);
            }
        ?>	
        </tbody>
	</table>
</fieldset>

<script type="text/javascript">

    $(document).ready(function(){
		$("#estado").html("").removeClass('alerta');
		distribuirLineas();
		acciones(false,"#protocoloComercio");	
    });
        
    $(".icono").click(function(event){
    	if ($('#protocoloComercio >tr').length == 1){
    		$("#imprimirProtocolo").attr('data-accionEnExito','ACTUALIZAR');
    	}	
    });        

</script>

