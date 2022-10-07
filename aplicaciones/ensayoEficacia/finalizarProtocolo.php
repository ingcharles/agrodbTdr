<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

require_once './clases/Transaccion.php';
require_once './clases/Flujo.php';

require_once 'clases/GeneradorProtocolo.php';

$procesado=false;

try{
	$conexion = new Transaccion();
	$ce = new ControladorEnsayoEficacia();
	//miro si solicitud ya existe
	$datoProtocolo=array();


	$identificador= $_SESSION['usuario'];
	try{
		$id_documento=intval($_POST['id_protocolo']);
		if($id_documento>0)
			$datoProtocolo['id_protocolo'] = $id_documento;
	}catch(Exception $e){}

		$cultivo= htmlspecialchars ($_POST['cultivo_menor'],ENT_NOQUOTES,'UTF-8');
		$motivo=htmlspecialchars ($_POST['motivo'],ENT_NOQUOTES,'UTF-8');
		if($motivo!='MOT_AMP')
			$cultivo='f';
		$id_flujo=$_POST['id_flujo'];
		if($id_flujo==null)
			$id_flujo=$_SESSION['idAplicacion'];
		$flujos=$ce->obtenerFlujosDelDocumento($conexion,$id_flujo);
		$flujoActual=new Flujo($flujos,'EP','cultivo_menor',$id_flujo);
		$flujoActual=$flujoActual->InicializarFlujo($cultivo,'',1);
		$identificador_tecnico=$flujoActual->PerfilSiguiente();
		$flujo=$flujoActual->BuscarFaseSiguiente();
		if($flujo!=null){
			$datoProtocolo['id_flujo_documento'] =$flujo->Flujo_documento();
			$datoProtocolo['estado'] =$flujo->EstadoActual();


				$fecha=new DateTime();
				$fecha = $fecha->format('Y-m-d');
				$fechaSubsanacion=new DateTime();
				$plazo=$flujo->Plazo();
				$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo en DIAS
				$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');
				//*************** DISTRIBUYO EL TRAMITE ********

				$division='';
				try{
					
					$datoProtocolo['fecha_solicitud']=$fecha;
					
					if($flujo->EstadoActual()=='pago'){
						$division=$ce->asignarTramiteADivision($conexion,$id_documento);
						$identificador_tecnico=$flujo->PerfilSiguiente();
						$flujo=$flujo->BuscarFaseSiguiente();		//para llegar a la proxima asignación
					}
					else{
						$division='DIV_PICH';
					}
					
					//verifica modificacion
					$respuesta=$ce->verificarProtocoloEstado($conexion,$id_documento);
					if($respuesta['es_modificacion']=='t'){
						$datoProtocolo['estado'] =$flujo->EstadoActual();
						$fechaSubsanacion=new DateTime();
						$plazo=$flujo->Plazo();
						$fechaSubsanacion->add(new DateInterval('P'.$plazo.'D'));		//AÑADE plazo en DIAS
						$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');
					}
					else{
						$datoProtocolo['id_expediente']=$ce->obtenerSecuencialEEProtocolo($conexion,date('Y'));	//Genera el codigo del expediente
					}

					//Pasa al siguiente flujo

					$conexion->Begin();
					$ce -> guardarProtocolo($conexion,$datoProtocolo);
					//Genera el pdf del protocolo
	
					$tituloPrevio=$ce->generarTituloDelEnsayo($conexion, $id_documento);
					$cProtocolo=new GeneradorProtocolo();
					$msg=$cProtocolo->generarProtocolo($conexion,$id_documento,$tituloPrevio,'');
					if($msg['estado']!='exito')
						throw new Exception('No se generó el pdf');

					$datoProtocolo['ruta']=$msg['datos'];
					$ce -> guardarProtocolo($conexion,$datoProtocolo);
					$numeroTramite=$ce ->guardarTramiteDelDocumento($conexion,$flujo->TipoDocumento(),$id_documento,$identificador_tecnico,$fecha,$fechaSubsanacion,'S',$division);
					$ce->guardarFlujoDelTramite($conexion,null,$numeroTramite,$flujo->Flujo_documento(),$identificador_tecnico,$identificador,$identificador,'S',$fecha,$fechaSubsanacion,'',$plazo);

					$conexion->Commit();
					$procesado=true;

				}
				catch(Exception $e){
					$procesado=false;
					$conexion->Rollback();

				}

				$conexion->desconectar();


		}
		else{
			$conexion->desconectar();

		}

	} catch (Exception $ex) {
		pg_close($conexion);


	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />

</head>
<body>


	<div id="estado"></div>

</body>




<script type="text/javascript">

	var procesado= <?php echo json_encode($procesado); ?>;

		$(document).ready(function(){
			if(procesado==true){
				mostrarMensaje('La solicitud de protocolo ha sido enviada','EXITO');
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"), null, true);
			abrir($("input:hidden"), null, false);

			}
			else{
				mostrarMensaje('Para finalizar acepte las condiciones y verifique toda la información','FALLO');
			}
		});


</script>

</html>

