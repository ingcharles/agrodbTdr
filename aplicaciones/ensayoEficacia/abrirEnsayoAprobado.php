<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

require_once '../../clases/ControladorEnsayoEficacia.php';

require_once '../ensayoEficacia/clases/Perfil.php';

$conexion = new Conexion();
$cr=new ControladorRegistroOperador();
$ce=new ControladorEnsayoEficacia();


$id_solicitud = $_POST['id'];

$fechaAprobacion='';

if($id_solicitud!=null && $id_solicitud!='_nuevo'){
	$datosGenerales=$ce->obtenerProtocolo($conexion, $id_solicitud);
	$identificador=$datosGenerales['identificador'];
	$res = $cr->buscarOperador($conexion, $identificador);
	$operador = pg_fetch_assoc($res);
	$fechaAprobacion=new DateTime($datosGenerales['fecha_aprobacion']);
	$fechaAprobacion=$fechaAprobacion->format('Y-M-d');
}

?>

<header>
   <h1>Ensayo de eficacia</h1>
</header>

<div id="estado"></div>

<div id="P1" class="pestania" style="display: block;">
   <form id='frmVistaPreviaDossier' data-rutaAplicacion='ensayoEficacia' data-opcion=''>
      <fieldset>
         <legend>Datos del ensayo de eficacia</legend>
         <div data-linea="1">
            <label for="empresa">Empresa :</label>
            <input id="empresa" value="<?php echo $operador['razon_social'];?>" disabled="disabled" />

         </div>
         <div data-linea="2">
            <label for="fecha">Fecha de aprobación :</label>
				<input id="fecha" value="<?php echo $fechaAprobacion;?>" disabled="disabled" />

         </div>

         <div data-linea="5">
            <label for="ruta_dossier">Protocolo :</label>
				<a id="ruta_dossier" href="<?php echo $datosGenerales['ruta'];?>" target="_blank">
					<?php echo $datosGenerales['id_expediente'];?>
				</a>

         </div>
        
      </fieldset>
      
   </form>

</div>

<script type="text/javascript">
	var solicitud=<?php echo json_encode($datosGenerales); ?>;
		
	$("document").ready(function () {
		distribuirLineas();
		
	});


</script>
