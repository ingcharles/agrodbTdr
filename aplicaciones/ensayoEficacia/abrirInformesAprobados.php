<?php
session_start();

	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorRegistroOperador.php';



	$idUsuario= $_SESSION['usuario'];
	$id_informe = $_POST['id'];
	$id_documento = $_POST['id_documento'];
	$id_protocolo = $_POST['id_protocolo'];


	$identificador=$idUsuario;

	$conexion = new Conexion();
	$ce = new ControladorEnsayoEficacia();
	$cr = new ControladorRegistroOperador();


	$datosGenerales=array();



	if($id_informe==null || $id_informe=='_nuevo' || $id_informe==''){
		if($id_documento!=null && $id_documento!='_nuevo' && $id_documento!='')
			$id_informe=$id_documento;
	}

	$fechaAprobacion='';
	if($id_informe!=null && $id_informe!='_nuevo' && $id_informe!=''){
		$datosGenerales=$ce->obtenerInformeFinalEnsayo($conexion,$id_informe);
		$protocolo=$ce->obtenerProtocoloDesdeInformes($conexion,$datosGenerales['id_protocolo_zona']);
		$identificador=$protocolo['identificador'];
		$res = $cr->buscarOperador($conexion, $identificador);
		$operador = pg_fetch_assoc($res);

		$fechaAprobacion=new DateTime($datosGenerales['fecha_aprobacion']);
		$fechaAprobacion=$fechaAprobacion->format('Y-M-d');
	}


?>

<header>
	<h1>Informes finales</h1>
</header>

<div id="estado"></div>


<div >
	
		<fieldset>
			<legend>Datos del informe final</legend>
			<div data-linea="1">
				<label for="empresa">Empresa :</label>
				<input id="empresa" value="<?php echo $operador['razon_social'];?>" disabled="disabled" />

			</div>
			<div data-linea="2">
				<label for="fecha">Fecha de aprobación :</label>
				<input id="fecha" value="<?php echo $fechaAprobacion;?>" disabled="disabled" />

			</div>

			<div data-linea="4" >
				<label>
					<?php
					if($datosGenerales['ruta']!=null)
						echo '<a href='.$datosGenerales['ruta'].' target="_blank">Informe final</a>';
                    ?>
				</label>
			</div>
			
			<div data-linea="5">
				<label>
					<?php
					if($datosGenerales['ruta_resumen']!=null)
						echo '<a href='.$datosGenerales['ruta_resumen'].' target="_blank">Informe resumen</a>';
					?>
				</label>
			</div>
			<div data-linea="6">
				<label>
					<?php
					if(($identificador!=$idUsuario) &&($datosGenerales['ruta_informe_inspeccion']!=null))
						echo '<a href='.$datosGenerales['ruta_informe_inspeccion'].' target="_blank">Informe de inspección</a>';
                    ?>
				</label>
			</div>
			<div data-linea="7">
				<label>
					<?php
					if($protocolo['ruta']!=null)
						echo '<a href='.$protocolo['ruta'].' target="_blank">Ensayo de eficacia</a>';
					?>
				</label>
			</div>
			

		</fieldset>
		
	
</div>

<script type="text/javascript">

	$("document").ready(function(){
			
		distribuirLineas();

	});

	

</script>


