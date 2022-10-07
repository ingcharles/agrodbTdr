<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorExpedienteDigital.php';
    
    $conexion = new Conexion ();   
    $ce = new ControladorExpedienteDigital();
   //------------------------------------------------------------------------------------------------------
    $tipoDeBusqueda = htmlspecialchars($_POST ['tipo'], ENT_NOQUOTES, 'UTF-8');
    $textoDeBusqueda = htmlspecialchars($_POST ['textoDeBusqueda'], ENT_NOQUOTES, 'UTF-8');
    $provincia = htmlspecialchars($_POST ['provincia'], ENT_NOQUOTES, 'UTF-8');
    $area = htmlspecialchars($_POST ['servicio'], ENT_NOQUOTES, 'UTF-8');
    $nume=0;
    
    $operadores = $ce->filtrarRazonSocialUsuariosRucCiNumero($conexion, $tipoDeBusqueda, $textoDeBusqueda, $provincia, $area,0,0,1);
    while ($fila = pg_fetch_assoc($operadores)){
        $nume += $fila['contador'];
    }
?>
    <form id="listarConsultaItems" data-rutaAplicacion="expedienteDigital" data-opcion="listarUsuarioItems" data-destino="tablaItems22" >
         <input type="hidden" name="limite" id="limite" value=10 />
         <input type="hidden" name="inicio" id="inicio" value=0 />
         <input type="hidden" name="desplazamiento" id="desplazamiento" value=10 />  
         <input type="hidden" name="tipoDeBusqueda" value="<?php echo $tipoDeBusqueda;?>" />
         <input type="hidden" name="textoDeBusqueda" value="<?php echo $textoDeBusqueda;?>" />
         <input type="hidden" name="provincia" value="<?php echo $provincia;?>" /> 
         <input type="hidden" name="area" value="<?php echo $area;?>" /> 
         <input type="hidden" name="totalRegistros" value="<?php echo $nume;?>" />                   
    </form>				    
    <div id="paginacion" class="normal">
	</div>
	<table id="tablaItems22"> 
	</table>
 <script type="text/javascript">
    var itemInicial = 0;
        $(document).ready(function(event){
        $("#listadoItems").removeClass("comunes");
        $("#listadoItems").addClass("lista");
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un documento para revisarlo.</div>');    
        construirPaginacionexp($("#paginacion"),<?php echo $nume;?>);
    });   
    //----------------------------------------------------------------------------------------
        
</script>