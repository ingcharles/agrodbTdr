<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierPlaguicida.php';

require_once '../ensayoEficacia/clases/Perfil.php';

$conexion = new Conexion();
$cr=new ControladorRegistroOperador();
$ce=new ControladorEnsayoEficacia();
$cg=new ControladorDossierPlaguicida();
$tipo_documento='DP';
$usuario=$_SESSION['usuario'];
$id_documento = $_POST['id'];

$id_solicitud=$id_documento;
$fechaAprobacion='';

if($id_solicitud!=null && $id_solicitud!='_nuevo'){
	$datosGenerales=$cg->obtenerSolicitud($conexion, $id_solicitud);
	$identificador=$datosGenerales['identificador'];
	$res = $cr->buscarOperador($conexion, $identificador);
	$operador = pg_fetch_assoc($res);
	$fechaAprobacion=new DateTime($datosGenerales['fecha_inscripcion']);
	$fechaAprobacion=$fechaAprobacion->format('Y-M-d');
}

?>

<header>
   <h1>Dossier aprobados</h1>
</header>

<div id="estado"></div>

<div id="P1" class="pestania" style="display: block;">
   <form id='frmVistaPreviaDossier' data-rutaAplicacion='dossierPecuario' data-opcion=''>
      <fieldset>
         <legend>Datos del dossier</legend>
         <div data-linea="1">
            <label for="empresa">Empresa :</label>
            <input id="empresa" value="<?php echo $operador['razon_social'];?>" disabled="disabled" />

         </div>
         <div data-linea="2">
            <label for="fecha">Fecha de aprobación :</label>
				<input id="fecha" value="<?php echo $fechaAprobacion;?>" disabled="disabled" />

         </div>

         <div data-linea="5">
            <label for="ruta_dossier">Dossier :</label>
            <a id="ruta_dossier" href="<?php echo $datosGenerales['ruta_dossier'];?>" target="_blank"><?php echo $datosGenerales['id_expediente'];?></a>

         </div>
         <div data-linea="6">
            <label for="ruta_certificado">Certificado :</label>
            <a id="ruta_certificado" href="<?php echo $datosGenerales['ruta_certificado'];?>" target="_blank"><?php echo $datosGenerales['id_certificado'];?>
            </a>

         </div>
         <div data-linea="7">
            <label for="ruta_etiqueta">Etiqueta :</label>
            <a id="ruta_etiqueta" href="<?php echo $datosGenerales['ruta_etiqueta'];?>" target="_blank"><?php echo $datosGenerales['id_certificado'].' puntos mínimos';?>
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

	//**************************************************************************************
	$('button.btnDossierAprobado').click(function (event) {

		event.preventDefault();
		
		var form=$(this).parent();
		form.append("<input type='hidden' id='id_solicitud' name='id_solicitud' value='"+solicitud.id_solicitud+"' />"); // añade el nivel del formulario
		
		form.attr('data-opcion', 'crearCertificadoPecuario');
		mostrarMensaje("Generando certificado ... ",'FALLO');
		$('#verDossierAprobado').hide();
		
		ejecutarJson(form,new exitoDossierAprobado());

	});


	function exitoDossierAprobado(){
		this.ejecutar=function (msg){
			mostrarMensaje(msg.mensaje, "EXITO");
			$('#verDossierAprobado').show();
			$('#verDossierAprobado').attr('href',msg.datos);
		};
	}

	//*******************************************************
	$('button.btnVistaPreviaDossier').click(function (event) {

		event.preventDefault();

		var form=$(this).parent();
		form.append("<input type='hidden' id='id_solicitud' name='id_solicitud' value='"+solicitud.id_solicitud+"' />"); // añade el nivel del formulario
		form.append("<input type='hidden' id='id_protocolo' name='id_protocolo' value='"+$('#protocolo').val()+"' />"); // añade el nivel del formulario
		form.append("<input type='hidden' id='producto_nombre' name='producto_nombre' value='"+$('#producto_nombre').val()+"' />");
		form.append("<input type='hidden' id='normativa' name='normativa' value='"+$('#normativa').val()+"' />");
		form.append("<input type='hidden' id='ingrediente_activo' name='ingrediente_activo' value='"+$('#producto_ia').html()+"' />");
		form.append("<input type='hidden' id='ingredientes_paises' name='ingredientes_paises' value='"+$('#producto_pais').val()+"' />");
		form.append("<input type='hidden' id='usos' name='usos' value='"+$('#producto_uso').val()+"' />");
		form.append("<input type='hidden' id='formulacion' name='formulacion' value='"+$('#producto_formulacion').val()+"' />");
		form.append("<input type='hidden' id='formuladores_paises' name='formuladores_paises' value='"+$('#producto_pais_producto').val()+"' />");

		form.attr('data-opcion', 'crearDossierPecuario');

		mostrarMensaje("Generando archivo ... ",'FALLO');
		$('#verReporteDossier').hide();
		ejecutarJson(form,new exitoVistaPreviaDossier());

	});


	function exitoVistaPreviaDossier(){
		this.ejecutar=function (msg){
			mostrarMensaje(msg.mensaje, "EXITO");
			$('#verReporteDossier').show();
			$('#verReporteDossier').attr('href',msg.datos);
		};
	}


	//*******************************************************



</script>
