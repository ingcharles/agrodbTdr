<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

require_once '../../clases/ControladorDossierPecuario.php';


$conexion = new Conexion();
$cr=new ControladorRegistroOperador();

$cp=new ControladorDossierPecuario();
$tipo_documento='DP';
$usuario=$_SESSION['usuario'];
$id_documento = $_POST['id'];
$id_flujo = $_POST['idFlujo'];
$id_fase = $_POST['opcion'];

$id_solicitud=$id_documento;

if($id_solicitud!=null && $id_solicitud!='_nuevo'){
   $datosGenerales=$cp->obtenerSolicitud($conexion, $id_solicitud);
   $identificador=$datosGenerales['identificador'];
   $res = $cr->buscarOperador($conexion, $identificador);
   $operador = pg_fetch_assoc($res);

}

?>

<header>
   <h1>Dossier aprobados</h1>
</header>

<div id="estado"></div>


<div>

	<form id='frmVistaPreviaDossier' data-rutaaplicacion='dossierPecuario'>
		<fieldset>
			<legend>Datos del dossier</legend>
			<div data-linea="1">
				<label for="empresa">Empresa :</label>
				<input id="empresa" value="<?php echo $operador['razon_social'];?>" disabled="disabled" />

			</div>
			<div data-linea="2">
				<label for="fecha">Fecha de aprobación :</label>
				<input id="fecha" value="<?php echo $datosGenerales['fecha_inscripcion'];?>" disabled="disabled" />

			</div>

			<div data-linea="5">
				<label for="ruta_dossier">Dossier :</label>
				<a id="ruta_dossier" href="<?php echo $datosGenerales['ruta_dossier'];?>" target="_blank">
					<?php echo $datosGenerales['id_expediente'];?>
				</a>

			</div>
			<div data-linea="6">
				<label for="ruta_certificado">Certificado :</label>
				<a id="ruta_certificado" href="<?php echo $datosGenerales['ruta_certificado'];?>" target="_blank">
					<?php echo $datosGenerales['id_certificado'];?>
				</a>

			</div>
			<div data-linea="7">
				<label for="ruta_etiqueta">Etiqueta :</label>
				<a id="ruta_etiqueta" href="<?php echo $datosGenerales['ruta_etiqueta'];?>" target="_blank">
					<?php echo $datosGenerales['id_certificado'].' puntos mínimos';?>
				</a>
			</div>
		</fieldset>

		<fieldset>
			<legend>Archivos adjuntos</legend>
			<?php
			$anexoNumero=1;
				$anexoVector=$cp->listarArchivosAnexos($conexion,$id_solicitud);
				foreach($anexoVector as $anexo){
					if($anexo['path']==null || $anexo['path']=='0' || $anexo['path']=='')
						continue;
					echo '<div data-linea="'.$anexoNumero++.'">';
					echo '<a href='.$anexo['path'].' target="_blank">'.$anexo['nombre'].'</a>';
					echo '</div>';
				}
			?>
		</fieldset>

	</form>

</div>

<script type="text/javascript">
	var datosGenerales=<?php echo json_encode($datosGenerales); ?>;
		
	$("document").ready(function () {
		
		distribuirLineas();
		if(datosGenerales.estado=="rechazado"){
			$('#ruta_certificado').hide();
			$('#ruta_etiqueta').hide();
		}

		
	});



</script>