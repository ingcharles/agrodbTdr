<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRegistroOperador.php';
    require_once '../../clases/ControladorExpedienteDigital.php';
      
    $ce = new ControladorExpedienteDigital();
    $conexion = new Conexion ();

    $tipoServicio = htmlspecialchars($_POST['id'], ENT_NOQUOTES, 'UTF-8');
    $tmp = explode(".", htmlspecialchars($_POST['opcion'], ENT_NOQUOTES, 'UTF-8'));
    $area=$tmp[0];
    $provincia=$tmp[1];
    $numservicio=$tmp[2];
    $identificador=$tmp[3];
    
    $consulta=$ce->listarDetalleServicios($conexion,$numservicio,$identificador,$provincia,0,0,0);
    $nume  = pg_fetch_assoc($consulta);
    
//-------------------------------------------------------------------------------------------------------------------------
?>
    <form id="listarConsultaItems" data-rutaAplicacion="expedienteDigital" data-opcion="listarDetalleServicioItems" data-destino="tablaItems" >
         <input type="hidden" name="limite" id="limite" value=10 />
         <input type="hidden" name="inicio" id="inicio" value=0 />
         <input type="hidden" name="desplazamiento" id="desplazamiento" value=10 />  
         <input type="hidden" name="identificador" value="<?php echo $identificador;?>" />
         <input type="hidden" name="area" value="<?php echo $area;?>" />
         <input type="hidden" name="provincia" value="<?php echo $provincia;?>" /> 
         <input type="hidden" name="numServicio" value="<?php echo $numservicio;?>" /> 
         <input type="hidden" name="tipoServicio" value="<?php echo $tipoServicio;?>" />   
         <input type="hidden" name="totalRegistros" value="<?php echo $nume['contador'];?>" />                   
    </form>					    
	<div id="paginacion" class="normal">
	</div>
	<table id="tablaItems">
	</table>

<script type="text/javascript">
    var itemInicial = 0;
    $(document).ready(function(){
        $("#listadoItems").removeClass("comunes");
        $("#listadoItems").addClass("lista");
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un documento para revisarlo.</div>');
        construirPaginacionexp($("#paginacion"),<?php echo $nume['contador'];?>);
    });
</script>