<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAuditoria.php';

require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierPlaguicida.php';
require_once '../ensayoEficacia/clases/Hoja.php';
require_once '../ensayoEficacia/clases/PdfStandar.php';
require_once '../ensayoEficacia/clases/PdfCertificado.php';

class GeneradorDocumentoPlaguicida{
	
	public function generarSolicitudRegistro($conexion,$esDocumentoLegal,$id_solicitud){
		$mensaje=array();
		$mensaje['mensaje'] = 'Error generando documento';
		$mensaje['estado'] = 'NO';
		$identificador= $_SESSION['usuario'];
		$esBorrador=true;
		if($esDocumentoLegal==='SI')
			$esBorrador=false;
		$ce = new ControladorEnsayoEficacia();
		$co = new ControladorRegistroOperador();
		$cr=new ControladorRequisitos();
		$cg=new ControladorDossierPlaguicida();
		$datos=array();
		$fabricantes=array();
		$formuladores=array();
		$protocolo=array();
		if($id_solicitud!=null && $id_solicitud!='_nuevo'){
			$datos=$cg->obtenerSolicitud($conexion, $id_solicitud);
			$identificador=$datos['identificador'];						//El duenio del documento
			$fabricantes=$cg->obtenerFabricantes($conexion,$id_solicitud,'F');
			$formuladores=$cg->obtenerFabricantes($conexion,$id_solicitud,'R');
			$protocolo=$ce->obtenerProtocoloDesdeExpediente($conexion,$datos['protocolo']);
		}
		$res = $co->buscarOperador($conexion, $identificador);
		$operador = pg_fetch_assoc($res);
		$fileName='DGS_'.$identificador."_".$id_solicitud.'.pdf';
		//************************************************** INICIO ***********************************************************
		$margen_superior=40;
		$margen_inferior=15;
		$margen_izquierdo=20;
		$margen_derecho=17;
		$doc=new PdfStandar('P','mm','A4',true,'UTF-8');
		if($esBorrador)
			$doc->PonerBorrador(true);
		else
			$doc->PonerBorrador(false);
		$doc->SetLineWidth(0.1);
		$doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
		$doc->SetAutoPageBreak(TRUE, $margen_inferior);
		$doc->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$doc->AddPage();
		$xfull=$doc->getPageWidth()-$margen_izquierdo-$margen_derecho;
		//****************************** INICIA *************************************
		$doc->setY($margen_superior);
		$doc->SetTextColor();
		$doc->SetFont('times', 'B', 12);
		$doc->Cell($xfull,9,"SOLICITUD DE REGISTRO",0,1,'C',false,0,0,true,'C','C');
		$doc->SetFont('times', '', 10);
		$y=$doc->GetY();
		$date=new DateTime();
		$sdate=$date->format('Y-m-d');
		$doc->Cell($xfull,5,'Lugar y fecha: Quito, '.$sdate,0,1,'');
		$doc->Ln();
		$doc->Cell($xfull,5,'Señor(a): Director(a) ejecutiv(o/a)',0,1,'');
		$doc->Cell($xfull,5,'(ANC)',0,1,'');
		$doc->Ln();
		$strTitulo='<style></style>';
		$strTitulo=$strTitulo.'<div style="text-align:justify">';
		$strTitulo=$strTitulo.'El suscrito '.$operador['razon_social'].' con dirección '.$operador['direccion'].', '.$operador['parroquia'].', '.$operador['canton'].', '.$operador['provincia'].', telefono: '.$operador['telefono_uno'];
		$items=$ce->listarElementosCatalogo($conexion,'P1C30');
		$items= array_filter($items, function ($elemento) use ($datos) { return (trim($elemento['codigo']) == $datos['normativa']); } );
		$items=current($items);
		$norma=$items['nombre'];
		$strTitulo=$strTitulo.', en cumplimiento a lo dispuesto por la '.$norma.' y normas complementarias nacionales, solicito el Registro del plaguicida: ';
		$strTitulo=$strTitulo.'"'.$datos['producto_nombre'].'"';
		$strTitulo=$strTitulo.'</div>';
		$x=$margen_izquierdo;
		$y=$doc->GetY();
		$doc->writeHTMLCell($xfull,5,$x,$y,$strTitulo,0,1);
		$doc->Cell($xfull,5,'Al efecto, consigno la siguiente información y el expediente que anexo:',0,1,'');
		$str='';
		$str=	$str.'<ol type="a">';
		$str=$str.'<li><p>ACTIVIDAD DEL SOLICITANTE : <br/>';
		$items = $ce->obtenerOperacionesDelOperador($conexion,$identificador,'IAP');
		$stri='';
		foreach ($items as $item){
			$stri=$stri.', '.$item['operacion'];
		}
		$stri=substr($stri,2);
		$str=$str.$stri.'</p></li>';
		$str=$str.'<li><p>DIRECCION DE LAS INSTALACIONES : <br/>';
		$str=$str.$operador['direccion'].', '.$operador['parroquia'].', '.$operador['canton'].', '.$operador['provincia'].', '.$operador['telefono_uno'].', '.$operador['correo'];
		$str=$str.'</p></li>';
		$str=$str.'<li><p>NOMBRE Y DIRECCIÓN DE LA (S) EMPRESA(S) FABRICANTE(S) O FORMULADORA(S): <br/>';
		$stri='';
		$paises=array();
		foreach ($fabricantes as $key=>$item){
			$stri=$stri.$item['nombre'].', '.$item['direccion'].'. ';
			if(!in_array($item['pais'],$paises))
				$paises[]=$item['pais'];
		}
		$paisesFabricantes=join(', ',$paises);
		$paises=array();
		foreach ($formuladores as $key=>$item){
			$stri=$stri.$item['nombre'].', '.$item['direccion'].'. ';
			if(!in_array($item['pais'],$paises))
				$paises[]=$item['pais'];
		}
		$paisesFormuladores=join(', ',$paises);
		$str=$str.$stri;
		$str=$str.'</p></li>';
		$str=$str.'<li><p>NOMBRE DEL PRODUCTO : <br/>';
		$str=$str.$datos['producto_nombre'];
		$str=$str.'</p></li>';
		$str=$str.'<li><p>NOMBRE DEL INGREDIENTE ACTIVO: <br/>';
		$stri='';
		$id_producto_madre=0;
		if($datos['es_clon']=='t'){
			//Cuando es CLON mustra los ingredientes del producto madre
			$items=$ce->obtenerIaXregistro($conexion,$datos['clon_registro_madre']);
		}
		else{	//caso contrario muestra los ingredientes del Ensayo
			$items=$ce->obtenerIngredientesActivos($conexion,$protocolo['id_protocolo']);
		}
		$formulacion='';
		$ingredientes=array();
		foreach ($items as $key=>$item){
			if(!in_array($item['ingrediente_activo'],$ingredientes)){
				$ingredientes[]=$item['ingrediente_activo'];
				$formulacion=$item['formulacion'];
				$id_producto_madre=$item['id_producto'];
			}
		}
		$ingredientes=join(' + ',$ingredientes);
		$stri=$ingredientes;
		$str=$str.$stri;
		$str=$str.'</p></li>';
		$str=$str.'<li><p>PAÍS(ES) DE ORIGEN: <br/>';
		$str=$str.$paisesFabricantes;
		$str=$str.'</p></li>';
		$str=$str.'<li><p>USO(S) PROPUESTO(S): <br/>';
		$usos='';
		if($datos['es_clon']=='t'){
			$respuesta=$cr->mostrarDatosGeneralesDeProducto($conexion,$id_producto_madre);
			$items=array();
			while($fila = pg_fetch_assoc($respuesta)){
				$items[]=$fila['subtipo'];
			}
			$usos=join(', ',$items);
		}
		else{
			$items=$ce->obtenerFormulaciones($conexion,'SI');
			$items= array_filter($items, function ($elemento) use ($protocolo) { return (trim( $elemento['id_formulacion']) == $protocolo['plaguicida_formulacion']); } );
			$items=current($items);
			$formulacion=$items['formulacion'];
			$usos=$protocolo['uso_propuesto'];
		}
		$str=$str.$usos;
		$str=$str.'</p></li>';
		$str=$str.'<li><p>TIPO Y CODIGO DE FORMULACIÓN: <br/>';
		$str=$str.$formulacion;
		$str=$str.'</p></li>';
		$str=$str.'<li><p>PAÍS(ES) DE PROCEDENCIA: <br/>';
		$str=$str.$paisesFormuladores;
		$str=$str.'</p></li>';
		$str=	$str.'</ol>';
		$y=$doc->GetY();
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();
		$y=$y+20;
		$xm=$xfull/4;
		$doc->SetAbsXY($xm,$y);
		$doc->Cell($xm,5,'Firma del Solicitante','T',0,'C');
		$doc->SetAbsX(2.5*$xm);
		$doc->Cell($xm,5,'Firma del Asesor Técnico','T',0,'C');
		//******************************* FIN DE LA EDICION ****************************************************************************************
		$paths=$ce->obtenerRutaAnexos($conexion,'dossierPlaguicida');
		$doc->Output($paths['rutaFisica'].'/'.$fileName, 'F');
		$mensaje['datos'] = $paths['ruta'].'/'.$fileName;
		ob_end_clean();
		$mensaje['mensaje'] = 'Archivo generado';
		$mensaje['estado'] = 'exito';
		return $mensaje;
	}
	
	public function generarDossier($conexion,$id_solicitud){
		ob_start();
		$mensaje=array();
		$mensaje['mensaje'] = 'Error generando documento';
		$mensaje['estado'] = 'NO';
		$identificador= '';
		$ce = new ControladorEnsayoEficacia();
		$cr = new ControladorRegistroOperador();
		$cg=new ControladorDossierPlaguicida();
		$datos=array();
		$protocolo=array();
		$informe=array();
		$fabricantes=array();
		$formuladores=array();
		$informesAprobados=array();
		$id_protocolo=0;
		$ingredientesActivos=array();
		if($id_solicitud!=null && $id_solicitud!='_nuevo'){
			$datos=$cg->obtenerSolicitud($conexion, $id_solicitud);
			$identificador=$datos['identificador'];						//El duenio del documento
			$fabricantes=$cg->obtenerFabricantes($conexion,$id_solicitud,'F');
			$formuladores=$cg->obtenerFabricantes($conexion,$id_solicitud,'R');
			$protocolo=$ce->obtenerProtocoloDesdeExpediente($conexion,$datos['protocolo']);
			$id_protocolo=$protocolo['id_protocolo'];
			if($id_protocolo!=null){
				$ingredientesActivos=$ce->obtenerIngredientesActivos($conexion,$id_protocolo);
				$informe=$ce->obtenerInformeFinalPorExpediente($conexion,$datos['id_informe_final']);
				$informesAprobados=$ce->obtenerInformesFinales($conexion,$id_protocolo);
			}
		}
		$res = $cr->buscarOperador($conexion, $identificador);
		$operador = pg_fetch_assoc($res);
		$fileName='DGD_'.$identificador."_".$id_solicitud.'.pdf';
		$res = $cr->buscarOperador($conexion, $identificador);
		$operador = pg_fetch_assoc($res);
		//************************************************** INICIO ***********************************************************
		$margen_superior=40;
		$margen_inferior=15;
		$margen_izquierdo=20;
		$margen_derecho=17;
		$doc=new PdfStandar('P','mm','A4',true,'UTF-8');
		$doc->SetLineWidth(0.1);
		$doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
		$doc->SetHeaderMargin(0);
		$doc->SetFooterMargin($margen_inferior);
		$doc->SetAutoPageBreak(TRUE, $margen_inferior);
		$doc->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$doc->AddPage();
		$doc->SetFont('times', '', 9);
		$xfull=$doc->getPageWidth()-$margen_izquierdo-$margen_derecho;
		$hoja=new Hoja();
		//****************************** INICIA *************************************
		$doc->setY($margen_superior);
		$doc->SetTextColor();
		$doc->SetFont('times', 'B', 12);
		$doc->Cell($xfull,9,"DOSSIER DE REGISTRO DE PLAGUICIDAS",0,1,'C',false,0,0,true,'C','C');
		$doc->SetFont('times', '', 10);
		$y=$doc->GetY();
		$date=new DateTime();
		$sdate=$date->format('Y-m-d');
		$doc->Cell($xfull,5,'Quito, '.$sdate,0,1,'');
		$doc->Ln();
		$str="";
		$numero=1;
		$vectorPuntos=array();
		$itemsVector=array();
		$itemsVector['Razón Social: ']=$operador['razon_social'];
		$itemsVector['Dirección: ']=$operador['direccion'];
		$itemsVector['Teléfono: ']=$operador['telefono_uno'];
		$itemsVector['Email: ']=$operador['correo'];
		$grupoVector=array("Solicitante :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$grupoItems=array();
		foreach ($fabricantes as $item){
			$itemsVector=array();
			$itemsVector['Razón Social: ']=$item['nombre'];
			$itemsVector['Dirección: ']=$item['direccion'];
			$itemsVector['Teléfono: ']=$item['telefono_uno'];
			$itemsVector['Email: ']=$item['correo'];
			$grupoItems[]=array($item['identificador'],'',$itemsVector);
		}
		$grupoVector=array("Fabricante y país de origen :",'',$grupoItems);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesActivos as $key=>$item){
			$itemsVector[]=	$item['ingrediente_activo'];
		}
		$grupoVector=array("Nombre común: Aceptado por ISO, o equivalente :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesActivos as $key=>$item){
			$itemsVector[]=$item['ingrediente_activo'].": ".$item['ingrediente_quimico'];
		}
		$grupoVector=array("Nombre químico: Aceptado o propuesto por IUPAC :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesActivos as $key=>$item){
			$itemsVector[]=	$item['ingrediente_activo'].": ".$item['cas'];
		}
		$grupoVector=array("Número de código experimental que fue asignado por el fabricante :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$ingredientesDossier=$cg->obtenerIngredientesSolicitud($conexion,$id_solicitud);
		$grupoItems=array();
		foreach ($ingredientesActivos as $key=>$item){
			$pesoMolecular=$item['peso_molecular'];
			foreach ($ingredientesDossier as $iaDossier){
				if($iaDossier['ingrediente_activo']==$item['ingrediente_activo']){
					$pesoMolecular=$iaDossier['peso_molecular'];
					break;
				}
			}
			$itemsVector=array();
			$itemsVector['Fórmula: ']=$item['formula_quimica'];
			$itemsVector['Peso molecular: ']=$pesoMolecular;
			$grupoItems[]=array($item['ingrediente_activo'],'',$itemsVector);
		}
		$grupoVector=array("Fórmula empírica, peso molecular :",'',$grupoItems);
		$vectorPuntos[]=$grupoVector;
		$grupoItems=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector=array();
			$itemsVector['Referencia: ']=$item['formula_estructural'];
			
			$itemsVector['Fórmula: ']='<img src="../../'.$item['formula_estructural_ruta'].'" border="0" width="70px" height="auto">';
			$grupoItems[]=array($item['ingrediente_activo'],'',$itemsVector);
		}
		$grupoVector=array("Fórmula estructural :",'',$grupoItems);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['grupo_quimico'];
		}
		$grupoVector=array("Grupo químico :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['grado_pureza'];
		}
		$grupoVector=array("Grado de pureza (de acuerdo con el origen químico) :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['isomeros'];
		}
		$grupoVector=array("Isómeros (identificarlos) :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['impurezas'];
		}
		$grupoVector=array("Impurezas (identificarlas) :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['aditivos'];
		}
		$grupoVector=array("Aditivos (Ejemplo: estabilizantes) (identificarlos) :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$hoja->escribirParrafos($str,'',$numero++,'IDENTIDAD :','',$vectorPuntos);
		$y=$doc->GetY();
		$doc->writeHTML($str);
		$str='';
		$estadoFisico=$ce->listarElementosCatalogo($conexion,'P2C4');
		$vectorPuntos=array();
		$grupoItems=array();
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$hoja->buscarEnArray('codigo',$item['estado_fisico'],'nombre',$estadoFisico);
		}
		$grupoItems[]=array("Estado físico :",'',$itemsVector);
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['color'];
		}
		$grupoItems[]=array("Color :",'',$itemsVector);
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['olor'];
		}
		$grupoItems[]=array("Olor :",'',$itemsVector);
		$grupoVector=array("Aspecto :",'',$grupoItems);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['punto_fusion'].' °C';
		}
		$grupoVector=array("Punto de fusión :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['punto_ebullicion'].' °C';
		}
		$grupoVector=array("Punto de ebullición :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['densidad'];
		}
		$grupoVector=array("Densidad :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['presion_vapor'];
		}
		$grupoVector=array("Presión de vapor :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['espectro_absorcion'];
		}
		$grupoVector=array("Espectro de absorción :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['solubilidad_agua'];
		}
		$grupoVector=array("Solubilidad en agua :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['solubilidad_disolventes'];
		}
		$grupoVector=array("Solubilidad en disolventes orgánicos :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['coeficiente_particion'];
		}
		$grupoVector=array("Coeficiente de partición en n-octanol/agua :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['punto_ignicion'].' °C';
		}
		$grupoVector=array("Punto de ignición :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['tension_superficial'];
		}
		$grupoVector=array("Tensión superficial :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['propiedades_explosivas'];
		}
		$grupoVector=array("Propiedades explosivas :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['propiedades_oxidantes'];
		}
		$grupoVector=array("Propiedades oxidantes :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['reactividad_envase'];
		}
		$grupoVector=array("Reactividad con el material de envases :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['viscosidad'];
		}
		$grupoVector=array("Viscosidad :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$hoja->escribirParrafos($str,'',$numero++,'PROPIEDADES FÍSICAS Y QUÍMICAS :','',$vectorPuntos);
		$y=$doc->GetY();
		$doc->writeHTML($str);
		$str='';
		$vectorPuntos=array();
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['modo_accion'];
		}
		$grupoVector=array("Modo de acción sobre las plagas :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['organismos_nocivos'];
		}
		$grupoVector=array("Organismos nocivos controlados :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['mecanismo_accion'];
		}
		$grupoVector=array("Mecanismo de acción :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['ambito_aplicacion'];
		}
		$grupoVector=array("Ámbito de aplicación previsto :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['condiciones_fitosanitarias'];
		}
		$grupoVector=array("Condiciones fitosanitarias y ambientales para ser usado :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['resistencia'];
		}
		$grupoVector=array("Resistencia (información sobre desarrollo de resistencia y estrategias de monitoreo) :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['hoja_seguridad'];
		}
		$grupoVector=array("Hoja de seguridad en español elaborada por el fabricante :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$hoja->escribirParrafos($str,'',$numero++,'ASPECTOS RELACIONADOS A SU UTILIDAD :','',$vectorPuntos);
		//******************
		$y=$doc->GetY();
		$doc->writeHTML($str);
		$str='';
		$vectorPuntos=array();
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['metodo_sustancia'];
		}
		$grupoVector=array("Método analítico para la determinación de la sustancia activa pura :",'Referencia: '.$item['metodo_sustancia_ref'],$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['metodo_degradacion'];
		}
		$grupoVector=array("Métodos analíticos para la determinación de productos de degradación, isómeros, impurezas (de importancia toxicológica y ecotoxicológica) y de aditivos  :",'Referencia: '.$item['metodo_degradacion_ref'],$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['metodo_residuos'];
		}
		$grupoVector=array("Método analítico para la determinación de residuos en plantas tratadas, productos agrícolas, alimentos procesados, suelo y agua :",'Referencia: '.$item['metodo_residuos_ref'],$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[$item['ingrediente_activo'].': ']=$item['metodo_aire'];
		}
		$grupoVector=array("Métodos analíticos para aire, tejidos y fluidos animales o humanos  :",'Referencia: '.$item['metodo_aire_ref'],$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$hoja->escribirParrafos($str,'',$numero++,'METODOS ANALÍTICOS :','',$vectorPuntos);
		$y=$doc->GetY();
		$doc->writeHTML($str);
		//************************** SECCION B ****************************************************
		$doc->Ln();
		$doc->Cell($xfull,5,'B. DEL PRODUCTO FORMULADO',0,1,'');
		$str="";
		$numero=1;
		$vectorPuntos=array();
		$itemsVector=array();
		$itemsVector['Razón Social: ']=$operador['razon_social'];
		$itemsVector['Dirección: ']=$operador['direccion'];
		$itemsVector['Teléfono: ']=$operador['telefono_uno'];
		$itemsVector['Email: ']=$operador['correo'];
		$grupoVector=array("Nombre y domicilio del solicitante :",'',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$grupoItems=array();
		foreach ($formuladores as $key=>$item){
			$itemsVector=array();
			$itemsVector['Razón Social: ']=$item['nombre'];
			$itemsVector['Dirección: ']=$item['direccion'];
			$itemsVector['Teléfono: ']=$item['telefono_uno'];
			$itemsVector['Email: ']=$item['correo'];
			$grupoItems[]=array($item['identificador'],'',$itemsVector);
		}
		$grupoVector=array("Nombre y domicilio del formulador :",'',$grupoItems);
		$vectorPuntos[]=$grupoVector;
		$vectorPuntos[]=array("Nombre del producto  :",$datos['producto_nombre'],array());
		$itemsVector=array();
		foreach ($ingredientesDossier as $key=>$item){
			$itemsVector[]=	$item['ingrediente_activo'];
		}
		$grupoVector=array("Nombre de la sustancia activa y especificaciones de calidad del ítem A) 1 y 2, y documento del fabricante de la misma, autorizándolo a que se utilice su información en apoyo del Registro del formulado, cuando sea aplicable :",'Se considerara el número de ingredientes activos declarados',$itemsVector);
		$vectorPuntos[]=$grupoVector;
		$vectorPuntos[]=array("Clase de uso a que se destina  :",$protocolo['uso_propuesto'],array());
		$formulacion=array();
		if($protocolo['id_protocolo']!=null)
			$formulacion=$ce->obtenerFormulacion($conexion,$protocolo['id_protocolo']);
		$vectorPuntos[]=array("Tipo de formulación   :",$formulacion['formulacion'],array());
		$hoja->escribirParrafos($str,'',$numero++,'DESCRIPCIÓN GENERAL :','',$vectorPuntos);
		$doc->writeHTML($str);
		//**************************************************************************************************************************
		$vectorPuntos=array();
		$str='';
		$grupoItems='Contenido de sustancia(s) activa(s), grado técnico, expresado en % p/p o p/v. Certificado analítico de composición, expedido por un laboratorio reconocido por la Autoridad Nacional Competente o acreditado a nivel nacional o subregional, según corresponda, o por el laboratorio del fabricante';
		$vectorPuntos[]=array($grupoItems,$datos['composicion_sustancias'],array('Referencia: '.$datos['composicion_sustancias_ref']));
		$grupoItems='Contenido y naturaleza de los demás componentes incluidos en la formulación. Certificado analítico de composición, expedido por un laboratorio reconocido por la Autoridad Nacional Competente o acreditado';
		$vectorPuntos[]=array($grupoItems,$datos['composicion_naturaleza'],array('Referencia: '.$datos['composicion_naturaleza_ref']));
		$grupoItems='Método de análisis para determinación del contenido de sustancia(s) activa(s)';
		$vectorPuntos[]=array($grupoItems,$datos['composicion_metodo'],array('Referencia: '.$datos['composicion_metodo_ref']));
		$hoja->escribirParrafos($str,'',$numero++,'COMPOSICIÓN :','',$vectorPuntos);
		$doc->writeHTML($str);
		//**************************************************************************************************************************
		$vectorPuntos=array();
		$itemsGrupo=array();
		$str='';
		$estadoFisico=$ce->obtenerItemDelCatalogo($conexion,'P2C4',$datos['estado_fisico']);
		$itemsGrupo[]=array('Estado físico: ',$estadoFisico['nombre'],array());
		$itemsGrupo[]=array('Color: ',$datos['color'],array('Referencia: '.$datos['color_ref']));
		$itemsGrupo[]=array('Olor: ',$datos['olor'],array('Referencia: '.$datos['olor_ref']));
		$vectorPuntos[]=array('Aspecto','',$itemsGrupo);
		$grupoItems='Estabilidad en el almacenamiento (respecto de su composición y a las propiedades físicas relacionadas con el uso)';
		$vectorPuntos[]=array($grupoItems,$datos['estabilidad'],array('Referencia: '.$datos['estabilidad_ref']));
		$grupoItems='Densidad relativa';
		$vectorPuntos[]=array($grupoItems,$datos['densidad'],array('Referencia: '.$datos['densidad_ref']));
		$grupoItems='';
		if($datos['estado_fisico']=='EFC_LIQU'){
			if(intval( $datos['punto_inflamacion'])<21)
				$grupoItems='MUY INFLAMABLE, ';
			else if(intval( $datos['punto_inflamacion'])<55)
				$grupoItems='INFLAMABLE, ';
			else
				$grupoItems='';
			$grupoItems=$grupoItems.'Punto de inflamación: '.$datos['punto_inflamacion'].' ºC';
		}else{
			if($datos['inflamacion_es_solido']=='t')
				$grupoItems="INFLAMABLE";
			else
				$grupoItems="NO INFLAMABLE";
		}
		$vectorPuntos[]=array('Inflamabilidad',$grupoItems,array('Referencia: '.$datos['inflamacion_adjunto']));
		$vectorPuntos[]=array('pH',$datos['ph'],array('Referencia: '.$datos['ph_ref']));
		$grupoItems='';
		if($datos['es_explosivo']=='t')
			$grupoItems="EXPLOSIVO";
		else
			$grupoItems="NO EXPLOSIVO";
		$vectorPuntos[]=array('Explosividad',$grupoItems,array('Referencia: '.$datos['explosivo_referencia']));
		$hoja->escribirParrafos($str,'',$numero++,'PROPIEDADES FÍSICAS Y QUÍMICAS :','',$vectorPuntos);
		$doc->writeHTML($str);
		//**************************************************************************************************************************
		$vectorPuntos=array();
		$itemsGrupo=array();
		$str='';
		$vectorPuntos[]=array('Humedad y humectabilidad (para los polvos dispersables)',$datos['humedad'],array('Referencia: '.$datos['humedad_ref']));
		$vectorPuntos[]=array('Persistencia de espuma (para los formulados que se aplican en el agua)',$datos['persistencia'],array('Referencia: '.$datos['persistencia_ref']));
		$vectorPuntos[]=array('Suspensibilidad para los polvos dispersables y los concentrados en suspensión',$datos['suspensibilidad'],array('Referencia: '.$datos['suspensibilidad_ref']));
		$vectorPuntos[]=array('Análisis granulométricos en húmedo/tenor de polvo (para los polvos dispersables y los concentrados en suspensión)',$datos['granulometria_humedo'],array('Referencia: '.$datos['granulometria_humedo_ref']));
		$vectorPuntos[]=array('Análisis granulométrico en seco (para gránulos y polvos)',$datos['granulometria_seco_ref'],array('Referencia: '.$datos['granulometria_seco_ref']));
		$vectorPuntos[]=array('Estabilidad de la emulsión',$datos['estabilidad_emulsion'],array('Referencia: '.$datos['estabilidad_emulsion_ref']));
		$grupoItems='';
		if($datos['es_corrosivo']=='t')
			$grupoItems='PRODUCTO CORROSIVO';
		else
			$grupoItems='PRODUCTO NO CORROSIVO';
		$vectorPuntos[]=array('Corrosividad',$grupoItems,array('Referencia: '.$datos['corrosivo_ref']));
		$vectorPuntos[]=array('Incompatibilidad conocida con otros productos',$datos['incompatibilidad'],array('Referencia: '.$datos['incompatibilidad_ref']));
		$vectorPuntos[]=array('Densidad a 20°C en g/ml (para formulaciones líquidas)',$datos['densidad'],array('Referencia: '.$datos['densidad_ref']));
		$grupoItems='N/A';
		if($datos['estado_fisico']=='EFC_LIQU'){
			$grupoItems=$datos['punto_inflamacion'].' ºC';
		}
		$vectorPuntos[]=array('Punto de inflamación (aceites y soluciones)',$grupoItems,array());
		if($datos['estado_fisico']=='EFC_LIQU')
			$vectorPuntos[]=array('Viscosidad (para suspensiones y emulsiones)',$datos['viscosidad'],array('Referencia: '.$datos['viscosidad_ref']));
		else
			$vectorPuntos[]=array('Viscosidad (para suspensiones y emulsiones)','N/A',array('Referencia: '.'N/A'));
		$vectorPuntos[]=array('Índice de sulfonación (aceites)',$datos['sulfonacion'],array('Referencia: '.$datos['sulfonacion_ref']));
		$vectorPuntos[]=array('Dispersión (para gránulos dispersables)',$datos['dispersion'],array('Referencia: '.$datos['dispersion_ref']));
		$vectorPuntos[]=array('Desprendimiento de gas (sólo para gránulos generadores de gas u otros productos similares)',$datos['desprendimiento'],array('Referencia: '.$datos['desprendimiento_ref']));
		$vectorPuntos[]=array('Soltura o fluidez para polvos secos',$datos['soltura'],array('Referencia: '.$datos['soltura_ref']));
		$vectorPuntos[]=array('Indice de yodo e índice de saponificación (para aceites vegetales)',$datos['indice_yodo'],array('Referencia: '.$datos['indice_yodo_ref']));
		$vectorPuntos[]=array('pH',$datos['ph'],array('Referencia: '.$datos['ph_ref']));
		$hoja->escribirParrafos($str,'',$numero++,'PROPIEDADES FÍSICAS Y QUÍMICAS :','',$vectorPuntos);
		$doc->writeHTML($str);
		//**************************************************************************************************************************
		$vectorPuntos=array();
		$itemsGrupo=array();
		$str='';
		$vectorPuntos[]=array('Ámbito de aplicación',$informe['ambito'],array('Referencia: '.$datos['explosivo_referencia']));
		$vectorPuntos[]=array('Efectos sobre plagas y cultivos',$informe['efecto_plagas'],array());
		$finalAprobado=array();
		foreach($informesAprobados as $final){
			$finalAprobado[]=$final['condiciones'];
		}
		$vectorPuntos[]=array('Condiciones en que el producto puede ser utilizado','',$finalAprobado);
		
		$vectorPuntos[]=array('Dosis',$informe['dosis'].' '.$informe['dosis_unidad'],array());
		$vectorPuntos[]=array('Número y momentos de aplicación',$informe['numero_aplicacion'],array());
		$vectorPuntos[]=array('Métodos de aplicación',$informe['metodo_aplicacion'],array());
		$vectorPuntos[]=array('Instrucciones de uso',$informe['instrucciones'],array());
		$vectorPuntos[]=array('Fecha de reingreso al área tratada',$datos['reingreso'],array());
		$vectorPuntos[]=array('Períodos de carencia o espera',$datos['carencia'],array('Referencia: '.$datos['carencia_ref']));
		$vectorPuntos[]=array('Efectos sobre cultivos sucesivos',$datos['efectos_cultivos'],array('Referencia: '.$datos['efectos_cultivos_ref']));
		$vectorPuntos[]=array('Fitotoxicidad',$informe['fitotoxicidad'],array());
		$vectorPuntos[]=array('Usos propuestos y aprobados en otros países, especialmente en la Subregión Andina','',array());
		$vectorPuntos[]=array('Estado de registro en la Subregión Andina y en terceros países','',array());
		$vectorPuntos[]=array('Informe sobre resultados de ensayos de eficacia realizados en el país según Protocolo consignado en el Manual Técnico con una antigüedad no mayor de 5 años','',array());
		$hoja->escribirParrafos($str,'',$numero++,'DATOS SOBRE APLICACIÓN DEL PRODUCTO FORMULADO :','',$vectorPuntos);
		$doc->writeHTML($str);
		//**************************************************************************************************************************
		$vectorPuntos=array();
		$itemsGrupo=array();
		$str='';
		$hoja->escribirParrafos($str,'',$numero++,'ETIQUETADO DEL PRODUCTO FORMULADO :','',$vectorPuntos);
		$doc->writeHTML($str);
		//**************************************************************************************************************************
		$vectorPuntos=array();
		$itemsGrupo=array();
		$str='';
		$itemsGrupo[]=array('Tipo',$datos['envase_tipo'],array('Referencia: '.$datos['envase_tipo_ref']));
		$itemsGrupo[]=array('Material',$datos['envase_material'],array('Referencia: '.$datos['envase_material_ref']));
		$itemsGrupo[]=array('Capacidad',$datos['envase_capacidad'],array('Referencia: '.$datos['envase_capacidad_ref']));
		$itemsGrupo[]=array('Resistencia',$datos['envase_resistencia'],array('Referencia: '.$datos['envase_resistencia_ref']));
		$vectorPuntos[]=array('Envases','',$itemsGrupo);
		$itemsGrupo=array();
		$itemsGrupo[]=array('Tipo',$datos['embalaje_tipo'],array('Referencia: '.$datos['embalaje_tipo_ref']));
		$itemsGrupo[]=array('Material',$datos['embalaje_material'],array('Referencia: '.$datos['embalaje_material_ref']));
		$itemsGrupo[]=array('Capacidad',$datos['embalaje_capacidad'],array('Referencia: '.$datos['embalaje_capacidad_ref']));
		$itemsGrupo[]=array('Resistencia',$datos['embalaje_resistencia'],array('Referencia: '.$datos['embalaje_resistencia_ref']));
		$vectorPuntos[]=array('Embalajes','',$itemsGrupo);
		$vectorPuntos[]=array('Acción del producto sobre el material de los envases',$datos['accion_envases'],array('Referencia: '.$datos['accion_envases_ref']));
		$vectorPuntos[]=array('Procedimientos para la descontaminación y destrucción de los envases',$datos['destruccion_envaces'],array('Referencia: '.$datos['destruccion_envaces_ref']));
		$hoja->escribirParrafos($str,'',$numero++,'ENVASES Y EMBALAJES PROPUESTOS PARA EL PRODUCTO FORMULADO :','',$vectorPuntos);
		$doc->writeHTML($str);
		//**************************************************************************************************************************
		$vectorPuntos=array();
		$itemsGrupo=array();
		$str='';
		$vectorPuntos[]=array('Procedimientos para la destrucción de la sustancia activa y para la descontaminación',$datos['sobra_destruccion'],array('Referencia: '.$datos['sobra_destruccion_ref']));
		$vectorPuntos[]=array('Métodos de disposición final de los residuos',$datos['sobra_residuos'],array('Referencia: '.$datos['sobra_residuos_ref']));
		$vectorPuntos[]=array('Posibilidades de recuperación (si se dispone)',$datos['sobra_recuperacion'],array('Referencia: '.$datos['sobra_recuperacion_ref']));
		$vectorPuntos[]=array('Posibilidades de neutralización',$datos['sobra_neutralizacion'],array('Referencia: '.$datos['sobra_neutralizacion_ref']));
		$vectorPuntos[]=array('Incineración controlada (condiciones)',$datos['sobra_incineracion'],array('Referencia: '.$datos['sobra_incineracion_ref']));
		$vectorPuntos[]=array('Depuración de las aguas',$datos['sobra_depuracion'],array('Referencia: '.$datos['sobra_depuracion_ref']));
		$vectorPuntos[]=array('Métodos recomendados y precauciones de manejo durante su manipulación, almacenamiento, transporte y en caso de incendio',$datos['sobra_precauciones'],array('Referencia: '.$datos['sobra_precauciones_ref']));
		$vectorPuntos[]=array('En caso de incendio, productos de reacción y gases de combustión',$datos['sobra_incendio'],array('Referencia: '.$datos['sobra_incendio_ref']));
		$vectorPuntos[]=array('Información sobre equipo de protección individual',$datos['sobra_equipo'],array('Referencia: '.$datos['sobra_equipo_ref']));
		$vectorPuntos[]=array('Procedimientos de limpieza del equipo de aplicación',$datos['sobra_limpieza'],array('Referencia: '.$datos['sobra_limpieza_ref']));
		$hoja->escribirParrafos($str,'',$numero++,'DATOS SOBRE EL MANEJO DE SOBRANTES DEL PRODUCTO FORMULADO :','',$vectorPuntos);
		$doc->writeHTML($str);
		//**************************************************************************************************************************
		$vectorPuntos=array();
		$itemsGrupo=array();
		$str='';
		$vectorPuntos[]=array('Datos de residuos obtenidos en base a ensayos protocolizados, según las normas internacionales (Directrices de FAO para el establecimiento de Límites Máximos de Residuos (LMRs). (Según lo establecido en el Manual Técnico)',$datos['residuo_obtenidos'],array('Referencia: '.$datos['residuo_obtenidos_ref']));
		$hoja->escribirParrafos($str,'',$numero++,'DATOS SOBRE LOS RESIDUOS DEL PRODUCTO FORMULADO :','',$vectorPuntos);
		$doc->writeHTML($str);
		$numero=12;
		$str='';
		$vectorPuntos=array();
		$vectorPuntos[]=array('Datos relativos a disolventes, emulsionantes, adhesivos, estabilizantes, colorantes y toda otra sustancia componente de la formulación, de importancia toxicológica y ecotoxicológica',$datos['residuo_disolventes_hoja'],array('Referencia: '.$datos['residuo_disolventes_ref']));
		$hoja->escribirParrafos($str,'',$numero++,'','',$vectorPuntos);
		$doc->writeHTML($str);
		$str='';
		$vectorPuntos=array();
		$vectorPuntos[]=array('Hoja de seguridad en español elaborada por el fabricante o formulador',$datos['residuo_hoja'],array('Referencia: '.$datos['residuo_hoja_ref']));
		$hoja->escribirParrafos($str,'',$numero++,'','',$vectorPuntos);
		$doc->writeHTML($str);
		$str='';
		$vectorPuntos=array();
		$vectorPuntos[]=array('Resumen de la evaluación del producto (grado técnico y formulado). Síntesis de la interpretación técnica-científica de la información química del plaguicida agrícola, correlacionada con la información resultante de los estudios de eficacia toxicológicos, ecotoxicológicos y ambientales',$datos['residuo_evaluacion'],array('Referencia: '.$datos['residuo_evaluacion_ref']));
		$hoja->escribirParrafos($str,'',$numero++,'','',$vectorPuntos);
		$doc->writeHTML($str);
		//******************************* FIN DE LA EDICION ****************************************************************************************
		$paths=$ce->obtenerRutaAnexos($conexion,'dossierPlaguicida');
		$doc->Output($paths['rutaFisica'].'/'.$fileName, 'F');
		$mensaje['datos'] = $paths['ruta'].'/'.$fileName;
		ob_end_clean();
		$mensaje['mensaje'] = 'Archivo generado';
		$mensaje['estado'] = 'exito';
		return $mensaje;
	}
	
	public function generarCertificado($conexion,$id_solicitud,$firmante,$firmanteCargo){
		ob_start();
		$mensaje=array();
		$mensaje['mensaje'] = 'Error generando documento';
		$mensaje['estado'] = 'NO';
		$identificador='';
		$conexion = new Conexion();
		$ce = new ControladorEnsayoEficacia();
		$co = new ControladorRegistroOperador();
		$cr=new ControladorRequisitos();
		$cg=new ControladorDossierPlaguicida();
		$datos=array();
		$operador=array();
		$fabricantes=array();
		$formuladores=array();
		$protocolo=array();
		$informe=array();
		if($id_solicitud!=null && $id_solicitud!='_nuevo'){
			$datos=$cg->obtenerSolicitud($conexion, $id_solicitud);
			$identificador=$datos['identificador'];						//El duenio del documento
			$protocolo=$ce->obtenerProtocoloDesdeExpediente($conexion,$datos['protocolo']);
			//busca los datos del operador
			$res = $co->buscarOperador($conexion, $datos['identificador']);
			$operador = pg_fetch_assoc($res);
			$fabricantes=$cg->obtenerFabricantes($conexion,$id_solicitud,'F');
			$formuladores=$cg->obtenerFabricantes($conexion,$id_solicitud,'R');
			$informe=$ce->obtenerInformeFinalPorExpediente($conexion,$datos['id_informe_final']);
		}
		$fileName='CDG_'.$identificador."_".$id_solicitud.'.pdf';
		//************************************************** INICIO ***********************************************************
		$margen_superior=50;
		$margen_inferior=15;
		$margen_izquierdo=20;
		$margen_derecho=17;
		$x=$margen_izquierdo;
		$doc=new PdfCertificado('P','mm','A4',true,'UTF-8');
		$tipoLetra='times';
		//******************************************* FIRMA *************************************************************************
		$datosCertificado=$ce->obtenerDatosCertificado();
		$doc->setSignature($datosCertificado['rutaCertificado'], $datosCertificado['rutaCertificado'], $datosCertificado['password'], '',1, $datosCertificado['info']);
		$doc->SetLineWidth(0.1);
		$doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
		$doc->SetAutoPageBreak(TRUE, $margen_inferior);
		$doc->AddPage();
		$doc->SetFont($tipoLetra, '', 9);
		$xfull=$doc->getPageWidth()-$margen_izquierdo-$margen_derecho;
		$hoja=new Hoja();
		//****************************** INICIA *************************************
		$doc->SetTextColor();
		
		$doc->Cell($xfull,12,"CERTIFICADO DE REGISTRO NACIONAL DE PLAGUICIDAS QUÍMICOS",0,1,'C',false,0,0,true,'C','C');
		$doc->SetFont($tipoLetra, '', 8);
		$vectorPuntos='En cumplimiento con lo establecido en la Decisión 804 de la Comisión de la Comunidad Andina, se otorga el presente Certificado de Registro Nacional de plaguicidas, con vigencia indefinida';
		$textoHtml='<p style="border:1px solid black; padding:10px; text-align:justify; background-color:lightgray;">'.$vectorPuntos.'</p>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$doc->GetY(),$textoHtml,0,1);
		$doc->Cell($xfull,2,"",0,1,'C',false,0,0,true,'C','C');
		//******************************************************************
		$y=$doc->GetY();
		$textoHtml='<b>NOMBRE COMERCIAL: </b><u>'. strtoupper($datos['producto_nombre']).'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');
		$y=$doc->GetY();
		$str='';
		$vectorPuntos=array();
		$id_producto_madre=0;
		if($datos['es_clon']=='t'){
			//Cuando es CLON mustra los ingredientes del producto madre
			$items=$ce->obtenerIaXregistro($conexion,$datos['clon_registro_madre']);
		}
		else{	//caso contrario muestra los ingredientes del Ensayo
			$items=$ce->obtenerIngredientesActivos($conexion,$protocolo['id_protocolo']);
		}
		$ingredientes=array();
		$formulacion='';
		foreach ($items as $key=>$item){
			if(!in_array($item['ingrediente_activo'],$ingredientes)){
				$ingredientes[]=$item['ingrediente_activo'];
				$formulacion=$item['formulacion'];
				$id_producto_madre=$item['id_producto'];
			}
		}
		$usos='';
		if($datos['es_clon']=='t'){
			$respuesta=$cr->mostrarDatosGeneralesDeProducto($conexion,$id_producto_madre);
			$items=array();
			while($fila = pg_fetch_assoc($respuesta)){
				$items[]=$fila['subtipo'];
			}
			$usos=join(', ',$items);
		}
		else{
			$items=$ce->obtenerFormulaciones($conexion,'SI');
			$items= array_filter($items, function ($elemento) use ($protocolo) { return (trim( $elemento['id_formulacion']) == $protocolo['plaguicida_formulacion']); } );
			$items=current($items);
			$formulacion=$items['formulacion'];
			$usos=$protocolo['uso_propuesto'];
		}
		if($datos['es_clon']=='t'){
			$composiciones=$ce->obtenerIaXregistro($conexion,$datos['clon_registro_madre']);
		}else{
			$composiciones=$cg->obtenerIngredientesSolicitud($conexion,$id_solicitud);
		}
		$grupoVector=array();
		foreach($composiciones as $clave=>$valor){
			$grupoVector[$valor['ingrediente_activo']]=$valor['concentracion'].' '.$valor['unidad'];
		}
		$vectorPuntos[]=array('INGREDIENTE ACTIVO',$grupoVector);
		$itemHeader=array('CONCENTRACIÓN');
		$hoja->escribirTabla($str,$numero++,1,'COMPOSICIÓN DECLARADA:', $vectorPuntos,$itemHeader);
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$str='';
		$empresas=array();
		foreach($fabricantes as $key=>$valor){
			$empresas[]=$valor['nombre'].' - '.strtoupper($valor['pais']);
		}
		$hoja->escribirParrafoLibre($str,'FABRICANTE(S)', $empresas);
		$empresas=array();
		foreach($formuladores as $key=>$valor){
			$empresas[]=$valor['nombre'].' - '.strtoupper($valor['pais']);
		}
		$hoja->escribirParrafoLibre($str,'FORMULADOR(ES)', $empresas);
		$y=$doc->GetY();
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$str,0,1,false,true,'L');
		$y=$doc->GetY();
		$textoHtml='<b>USO AUTORIZADO: </b><u>'. $usos.'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');
		$itemHeader=array();
		$itemHeader[]=array('titulo'=>'CULTIVO','ancho'=>25);
		$itemHeader[]=array('titulo'=>'PLAGA','ancho'=>50);
		$itemHeader[]=array('titulo'=>'DOSIS','ancho'=>25);
		$cultivos=$ce->obtenerProductosXSubTipo($conexion,'CULTIVOS');
		$items=array();
		if($datos['es_clon']=='t'){
			$listaUsos=$cr->listarUsos($conexion,$id_producto_madre);
			$listaCultivos=array();
			$listaPlagas=array();
			while($item=pg_fetch_assoc($listaUsos)){
			$cultivo = array_filter($cultivos, function ($elemento) use ($item) { return ( $elemento['id_producto'] == $item['id_aplicacion_producto']); } );
				$cultivo=current($cultivo);
				$listaCultivos[]=$cultivo['nombre_comun'].' (<i>'.$cultivo['nombre_cientifico'].'</i>)';
				$plagas=$cr->abrirUsoInocuidad($conexion,$item['id_uso']);
				while($fila = pg_fetch_assoc($plagas)){
					$listaPlagas[]=$fila['nombre_comun_uso'].' (<i>'.$fila['nombre_uso'].'</i>)';
				}
			}
			$listaCultivos=join('<br/>',$listaCultivos);
			
			$dosis=$cr->buscarProductoInocuidad($conexion,$id_producto_madre);
			$datosMadre=array();
			if(pg_num_rows($dosis)>0)
				$datosMadre=pg_fetch_assoc($dosis,0);
			$items[]=array($listaCultivos,$listaPlagas,$datosMadre['dosis'].' '.$datosMadre['unidad_dosis']);
		}
		else{
			if($protocolo['cultivo_menor']=='t'){
			$cultivos = array_filter($cultivos, function ($elemento)  { return trim((strtolower( $elemento['numero_registro'])) == 'cm'); } );
			}
			$cultivo = array_filter($cultivos, function ($elemento) use ($protocolo) { return trim((strtolower( $elemento['id_producto'])) == $protocolo['cultivo']); } );
			$plagasHtml=array();
			if($protocolo==null || $protocolo['id_protocolo']==null){
			}
			else{
				$plagas=$ce->obtenerPlagasProtocolo($conexion,$protocolo['id_protocolo']);
				foreach($plagas as $plaga){
					$plagasHtml[]=$plaga['nombre2'].' (<i>'.$plaga['nombre'].'</i>)';
				}
			}
			$cultivo=array_values($cultivo);
			$cultivo=$cultivo[0];
			$items[]=array($cultivo['nombre_comun'].' (<i>'.$cultivo['nombre_cientifico'].'</i>)',$plagasHtml,$informe['dosis'].' '.$informe['dosis_unidad']);
		}
		$textoHtml='';
		$hoja->escribirTabla3($textoHtml,'','','',$items,$itemHeader);
		$y=$doc->GetY();
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');
		$y=$doc->GetY();
		$textoHtml='<b>TIPO DE FORMULACIÓN: </b><u>'. $formulacion.'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');
		$y=$doc->GetY();
		$items=$cr->listarCategoriaToxicologica($conexion,'IAP');
		$categoria='';
		while ($item = pg_fetch_assoc($items)){
			if(intval($item['id_categoria_toxicologica'])==intval($datos['id_categoria_toxicologica'])){
				$categoria=$item['categoria_toxicologica'];
				break;
			}
		}
		$textoHtml='<b>CLASIFICACIÓN TOXICOLÓGICA: </b><u>'. $categoria.'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');
		$y=$doc->GetY();
		$textoHtml='<b>INSCRITO EN EL REGISTRO NACIONAL DE PLAGUICIDAS DE USO AGRÍCOLA: </b>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');
		$y=$doc->GetY();
		if($datos['es_clon']=='t'){
			$datos['id_certificado']=$ce->obtenerRegistroNuevoClon($conexion,$datos['clon_registro_madre']);
		}
		else{
			$prefijo=trim($protocolo['uso']).'-'.trim($datos['normativa']);
			$datos['id_certificado']=$ce->obtenerRegistro($conexion,'dossierPlaguicida',$prefijo);
		}
		$textoHtml='<b>CON EL No: <u>'.$datos['id_certificado'] .'</u></b>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');
		$y=$doc->GetY();
		$textoHtml='<b>TITULAR DE REGISTRO: </b><u>'. $operador['razon_social'].'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');
		$date=new DateTime();
		$sdate=$date->format('Y-m-d');
		$datos['fecha_inscripcion']= $date;
		$y=$doc->GetY();
		$textoHtml='<b>FECHA DE EMISIÓN: </b><u>'. $sdate.'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');
		$y=$doc->GetY();
		$y=$y+10;
		$xm=$xfull/3;
		$doc->SetAbsXY($xm,$y);
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,'<i><u>Documento firmado electrónicamente</u></i>',0,1,false,true,'C');
		$doc->Cell($xfull,5,strtoupper($firmante),0,1,'C');
		$doc->Cell($xfull,5,strtoupper( $firmanteCargo),0,1,'C');
		$doc->Cell($xfull,5,"AGENCIA DE REGULACIÓN Y CONTROL FITO Y ZOOSANITARIO – AGROCALIDAD",0,1,'C');
		$y=$doc->GetY();
		$y=$y+10;
		$paths=$ce->obtenerRutaAnexos($conexion,'dossierPlaguicida');
		$rutaQR=$paths['rutaUrl'].'/'.$fileName;
		$style = array(
'border' => 2,
'vpadding' => 'auto',
'hpadding' => 'auto',
'fgcolor' => array(0,0,0),
'bgcolor' => false, 
'module_width' => 1, 
'module_height' => 1 
		);
		$doc->write2DBarcode($rutaQR, 'QRCODE,H', 20, $y, 50, 50, $style, 'N');
		// area para validar firma
		$doc->Image('../ensayoEficacia/img/logo_agrocalidad.gif', 100, $y, 50, 50, 'GIF');
		$doc->setSignatureAppearance(100, $y, 50, 50);
		$doc->Output($paths['rutaFisica'].'/'.$fileName, 'F');
		$mensaje['datos'] = $paths['ruta'].'/'.$fileName;
		ob_end_clean();
		$mensaje['mensaje'] = 'Archivo generado';
		$mensaje['estado'] = 'exito';
		$mensaje['id_certificado']=$datos['id_certificado'];
		return $mensaje;
	}
	
	public function generarPuntosMinimos($conexion,$id_solicitud,$firmante,$firmanteCargo,$noCertificado=''){
		ob_start();
		$ce = new ControladorEnsayoEficacia();
		$co = new ControladorRegistroOperador();
		$cr=new ControladorRequisitos();
		$cg=new ControladorDossierPlaguicida();
		$identificador='';
		$datos=array();
		$protocolo=array();
		$fabricantes=array();
		if($id_solicitud!=null && $id_solicitud!='_nuevo'){
			$datos=$cg->obtenerSolicitud($conexion, $id_solicitud);
			$protocolo=$ce->obtenerProtocoloDesdeExpediente($conexion,$datos['protocolo']);
			$etiqueta=$cg->obtenerEtiquetaSolicitud($conexion, $id_solicitud);
			$identificador=$datos['identificador'];						//El duenio del documento
			$informe=$ce->obtenerInformeFinalPorExpediente($conexion,$datos['id_informe_final']);
			//busca los datos del operador
			$res = $co->buscarOperador($conexion, $datos['identificador']);
			$operador = pg_fetch_assoc($res);
			$fabricantes=$cg->obtenerFabricantes($conexion,$id_solicitud,'F');
			$formuladores=$cg->obtenerFabricantes($conexion,$id_solicitud,'R');
		}
		$fileName='DG_EPM_'.$identificador."_".$id_solicitud.'.pdf';
		//************************************************** INICIO ***********************************************************
		$margen_superior=50;
		$margen_inferior=15;
		$margen_izquierdo=20;
		$margen_derecho=17;
		$doc=new PdfCertificado('P','mm','A4',true,'UTF-8');
		//******************************************* FIRMA *************************************************************************
		$datosCertificado=$ce->obtenerDatosCertificado();
		$doc->setSignature($datosCertificado['rutaCertificado'], $datosCertificado['rutaCertificado'], $datosCertificado['password'], '',1, $datosCertificado['info']);
		//******************************************* FIN FIRMA *********************************************************************
		$doc->SetLineWidth(0.1);
		$doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
		$doc->SetAutoPageBreak(TRUE, $margen_inferior);
		$doc->AddPage();
		$xfull=$doc->getPageWidth()-$margen_izquierdo-$margen_derecho;
		$hoja=new Hoja();
		$tipoLetra='times';
		//****************************** INICIA *************************************
		$doc->SetTextColor();
		$doc->SetFont('times', 'B', 9);
		$doc->Cell($xfull,9,"PUNTOS MÍNIMOS DE LA ETIQUETA",0,1,'C',false,0,0,true,'C','C');
		$doc->SetFont($tipoLetra, '', 8);
		$doc->Cell($xfull,5,"",0,1,'C',false,0,0,true,'C','C');
		//******************************************************************
		$y=$doc->GetY();
		$textoHtml='<b>Nombre del Producto: </b><u>'. strtoupper($datos['producto_nombre']).'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');
		$id_producto_madre=0;
		if($datos['es_clon']=='t'){
			//Cuando es CLON mustra los ingredientes del producto madre
			$items=$ce->obtenerIaXregistro($conexion,$datos['clon_registro_madre']);
		}
		else{	//caso contrario muestra los ingredientes del Ensayo
			$items=$ce->obtenerIngredientesActivos($conexion,$protocolo['id_protocolo']);
		}
		$ingredientes=array();
		$formulacion='';
		foreach ($items as $key=>$item){
			if(!in_array($item['ingrediente_activo'],$ingredientes)){
				$ingredientes[]=$item['ingrediente_activo'];
				$formulacion=$item['formulacion'];
				$id_producto_madre=$item['id_producto'];
			}
		}
		$usos='';
		if($datos['es_clon']=='t'){
			$respuesta=$cr->mostrarDatosGeneralesDeProducto($conexion,$id_producto_madre);
			$items=array();
			while($fila = pg_fetch_assoc($respuesta)){
				$items[]=$fila['subtipo'];
			}
			$usos=join(', ',$items);
		}
		else{
			$items=$ce->obtenerFormulaciones($conexion,'SI');
			$items= array_filter($items, function ($elemento) use ($protocolo) { return (trim( $elemento['id_formulacion']) == $protocolo['plaguicida_formulacion']); } );
			$items=current($items);
			$formulacion=$items['formulacion'];
			$usos=$protocolo['uso_propuesto'];
		}
		$y=$doc->GetY();
		
		$textoHtml='<b>Subtipo de producto: </b><u>'. $usos.'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');
		$y=$doc->GetY();
		$textoHtml='<b>Formulación: </b><u>'.$formulacion.'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');
		$y=$doc->GetY();
		$x=$margen_izquierdo;
		$str='';
		$vectorPuntos=array();
		if($datos['es_clon']=='t'){
			$composiciones=$ce->obtenerIaXregistro($conexion,$datos['clon_registro_madre']);
		}else{
			$composiciones=$cg->obtenerIngredientesSolicitud($conexion,$id_solicitud);
		}
		$grupoVector=array();
		foreach($composiciones as $clave=>$valor){
			$grupoVector[$valor['ingrediente_activo']]=$valor['concentracion'].' '.$valor['codigo'];
		}
		$vectorPuntos[]=array('INGREDIENTE ACTIVO',$grupoVector);
		$itemHeader=array('CONCENTRACIÓN');
		$hoja->escribirTabla($str,$numero++,1,'Composición:', $vectorPuntos,$itemHeader);
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();
		$x=$margen_izquierdo;
		$str='';
		$vectorPuntos=array();
		$composiciones=$cg->obtenerAditivosToxicologicos($conexion,$id_solicitud);
		$grupoVector=array();
		foreach($composiciones as $clave=>$valor){
			$grupoVector[$valor['nombre']]=$valor['cantidad'].' '.$valor['codigo'];
		}
		$vectorPuntos[]=array('ADITIVO',$grupoVector);
		$itemHeader=array('CONCENTRACIÓN');
		$hoja->escribirTabla($str,$numero++,1,'Aditivos de importancia toxicológica:', $vectorPuntos,$itemHeader);
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();
		
		$textoHtml='<b>Número de Registro: <u>'.$noCertificado.'</u></b>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');
		//**********************************************************************************************************************************
		$y=$doc->GetY();
		$textoHtml='<b>Titular del registro: </b><u>'. $operador['razon_social'].'</u>';
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');
		$empresas=array();
		foreach($formuladores as $key=>$valor){
			$empresas[]=$valor['nombre'].' - '.strtoupper($valor['pais']);
		}
		$str='';
		$hoja->escribirParrafoLibre($str,'FORMULADOR(ES):', $empresas);
		$y=$doc->GetY();
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$str,0,1,false,true,'L');
		//**********************************************************************************************************************************
		$y=$doc->GetY();
		$y=$y+4;
		$str="<b>LEA CUIDADOSAMENTE ESTA ETIQUETA ANTES DE USAR ESTE PRODUCTO</b>";
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1,false,true,'C');
		$y=$doc->GetY();
		$str="<b>MANTÉNGASE BAJO LLAVE FUERA DEL ALCANCE DE LOS NIÑOS</b>";
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1,false,true,'C');
		$y=$doc->GetY();
		$str="<b>Precauciones de uso y aplicación:</b>";
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();
		$y=$doc->GetY();
		$str=$etiqueta['precaucion_uso'];
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'Medidas relativas a la seguridad:',array($etiqueta['medidas_seguridad']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();
		$y=$y+4;
		$str="<b>EL MAL USO PUEDE CAUSAR DAÑOS A LA SALUD Y AL AMBIENTE</b>";
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1,false,true,'C');
		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'Almacenamiento y manejo del producto:',array($etiqueta['almacen_manejo']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'Medidas relativas a primeros auxilios:',array($etiqueta['medidas_auxilio']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'Nota para el médico tratante:',array($etiqueta['nota_medico']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();
		$y=$y+4;
		$str="<b>".$etiqueta['rotulo_veneno']."</b>";
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1,false,true,'C');
		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'Medidas relativas para la disposición de envases vacíos:',array($etiqueta['medidas_envases']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'Medidas relativas para la protección del ambiente:',array($etiqueta['medidas_ambiente']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'Instrucciones de uso y manejo:',array($etiqueta['instruccion_uso']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'Modo de empleo:',array($etiqueta['modo_empleo']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		//******************************************************************************
		$y=$doc->GetY();
		$y=$y+4;
		$str="<b>CONSULTE CON UN INGENIERO AGRÓNOMO</b>";
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1,false,true,'C');
		$itemHeader=array();
		$itemHeader[]=array('titulo'=>'CULTIVO','ancho'=>25);
		$itemHeader[]=array('titulo'=>'PLAGA','ancho'=>50);
		$itemHeader[]=array('titulo'=>'DOSIS','ancho'=>25);
		$cultivos=$ce->obtenerProductosXSubTipo($conexion,'CULTIVOS');
		$items=array();
		if($datos['es_clon']=='t'){
			$listaUsos=$cr->listarUsos($conexion,$id_producto_madre);
			$listaCultivos=array();
			$listaPlagas=array();
			while($item=pg_fetch_assoc($listaUsos)){
			$cultivo = array_filter($cultivos, function ($elemento) use ($item) { return ( $elemento['id_producto'] == $item['id_aplicacion_producto']); } );
				$cultivo=current($cultivo);
				$listaCultivos[]=$cultivo['nombre_comun'].' (<i>'.$cultivo['nombre_cientifico'].'</i>)';
				$plagas=$cr->abrirUsoInocuidad($conexion,$item['id_uso']);
				while($fila = pg_fetch_assoc($plagas)){
					$listaPlagas[]=$fila['nombre_comun_uso'].' (<i>'.$fila['nombre_uso'].'</i>)';
				}
			}
			$listaCultivos=join('<br/>',$listaCultivos);
			
			$dosis=$cr->buscarProductoInocuidad($conexion,$id_producto_madre);
			$datosMadre=array();
			if(pg_num_rows($dosis)>0)
				$datosMadre=pg_fetch_assoc($dosis,0);
			$items[]=array($listaCultivos,$listaPlagas,$datosMadre['dosis'].' '.$datosMadre['unidad_dosis']);
		}
		else{
			if($protocolo['cultivo_menor']=='t'){
			$cultivos = array_filter($cultivos, function ($elemento)  { return trim((strtolower( $elemento['numero_registro'])) == 'cm'); } );
			}
			$cultivo = array_filter($cultivos, function ($elemento) use ($protocolo) { return trim((strtolower( $elemento['id_producto'])) == $protocolo['cultivo']); } );
			$plagasHtml=array();
			if($protocolo==null || $protocolo['id_protocolo']==null){
			}
			else{
				$plagas=$ce->obtenerPlagasProtocolo($conexion,$protocolo['id_protocolo']);
				foreach($plagas as $plaga){
					$plagasHtml[]=$plaga['nombre2'].' (<i>'.$plaga['nombre'].'</i>)';
				}
			}
			$cultivo=array_values($cultivo);
			$cultivo=$cultivo[0];
			$items[]=array($cultivo['nombre_comun'].' (<i>'.$cultivo['nombre_cientifico'].'</i>)',$plagasHtml,$informe['dosis'].' '.$informe['dosis_unidad']);
		}
		$textoHtml='';
		$hoja->escribirTabla3($textoHtml,'','','COMPLEJO CULTIVO PLAGA',$items,$itemHeader);
		$y=$doc->GetY();
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,$textoHtml,0,1,false,true,'L');
		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'ÉPOCA Y FRECUENCIA DE APLICACIÓN:',array($etiqueta['epoca_aplicacion']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'PERÍODO DE REINGRESO:',array($etiqueta['periodo_reingreso']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'FITOTOXICIDAD:',array($etiqueta['fitoxicidad']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'COMPATIBILIDAD:',array($etiqueta['compatibilidad']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();
		$str='';
		$hoja->escribirParrafoLibre($str,'RESPONSABILIDAD:',array($etiqueta['responsabilidad']));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();
		$str='';
		$items=$cr->listarCategoriaToxicologica($conexion,'IAP');
		$categoria='';
		while ($item = pg_fetch_assoc($items)){
			if(intval($item['id_categoria_toxicologica'])==intval($etiqueta['id_categoria_toxicologica'])){
				$categoria=$item['categoria_toxicologica'];
				break;
			}
		}
		$hoja->escribirParrafoLibre($str,'CATEGORÍA TOXICOLÓGICA:',array($categoria));
		$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
		$y=$doc->GetY();
		$y=$y+10;
		$xm=$xfull/3;
		$doc->SetAbsXY($xm,$y);
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,'<i><u>Documento firmado electrónicamente</u></i>',0,1,false,true,'C');
		$doc->Cell($xfull,5,strtoupper($firmante),0,1,'C');
		$doc->Cell($xfull,5,strtoupper( $firmanteCargo),0,1,'C');
		$doc->Cell($xfull,5,"AGENCIA DE REGULACIÓN Y CONTROL FITO Y ZOOSANITARIO – AGROCALIDAD",0,1,'C');
		$y=$doc->GetY();
		$yMax=$doc->getPageHeight()-$margen_inferior;
		if($y+55>$yMax)
			$doc->AddPage();
		$doc->Cell($xfull,2,"",0,1,'C',false,0,0,true,'C','C');
		$y=$doc->GetY();
		$paths=$ce->obtenerRutaAnexos($conexion,'dossierPlaguicida');
		$rutaQR=$paths['rutaUrl'].'/'.$fileName;
		$style = array(
'border' => 2,
'vpadding' => 'auto',
'hpadding' => 'auto',
'fgcolor' => array(0,0,0),
'bgcolor' => false, 
'module_width' => 1, 
'module_height' => 1 
		);
		$doc->write2DBarcode($rutaQR, 'QRCODE,H', 20, $y, 50, 50, $style, 'N');
		// area para validar firma
		$doc->Image('../ensayoEficacia/img/logo_agrocalidad.gif', 100, $y, 50, 50, 'GIF');
		$doc->setSignatureAppearance(100, $y, 50, 50);
		$doc->Output($paths['rutaFisica'].'/'.$fileName, 'F');
		$mensaje['datos'] = $paths['ruta'].'/'.$fileName;
		ob_end_clean();
		$mensaje['mensaje'] = 'Archivo generado';
		$mensaje['estado'] = 'exito';
		return $mensaje;
	}

	
	public function subirProducto($conexion,$idSolicitud,$idCertificado,$fechaRegistro,$tipo_aplicacion,$usuario,$usuarioDatos){
		ob_start();
		$ce = new ControladorEnsayoEficacia();
		$cr = new ControladorRequisitos();
		$cc = new ControladorCatalogos();
		$cg=new ControladorDossierPlaguicida();
		$ca = new ControladorAuditoria();
			
		$dossier=$cg->obtenerSolicitud($conexion,$idSolicitud);
		//verifico que no se repita el nombre
		$registro=$dossier['clon_registro_madre'];
		$protocolo=$dossier['protocolo'];
		$partidaArancelaria='';
		$subTipoProducto=array();
		$presentaciones=array();
		$unidadMedida='KG';
		$idCategoriaToxicologica=0;
		$CategoriaToxicologica='';
		$idFormulacion=0;

		$esClon=false;
		if(($dossier['normativa']=='NA') && ($dossier['es_clon']=='t') && ($registro!=null) && (strlen(trim($registro))>0)){
			$subTipoProducto=$ce->obtenerSubTipoXregristo($conexion,$registro,'RIA-%');
			$partidaArancelaria=$subTipoProducto['partida_arancelaria'];
			$esClon=true;
		}
		else{
			$subTipoProducto=$ce->obtenerSubTipoXprotocolo($conexion,$protocolo,'RIA-%');
			//busca la presentacion base
			$presentaciones=$cg->obtenerPresentaciones($conexion,$idSolicitud);
			$presentacion = array_filter($presentaciones, function ($elemento)  { return trim($elemento['presentacion_tipo']) == 'P_PA_003'; } );
			$presentacion=current($presentacion);
			$partidaArancelaria=$presentacion['partida_arancelaria'];
			
		}
		if(($subTipoProducto==false) || (count($subTipoProducto)==0))
			throw new Exception('No se encontro No. de registro o protocolo'); 
		
		$idSubtipoProducto=$subTipoProducto['id_subtipo_producto'];
		$nombreProducto=$dossier['producto_nombre'];
		$producto = $cc->buscarProductoXNombre($conexion,$idSubtipoProducto,$nombreProducto);
		$empresa=$dossier['identificador'];
		$declaracionVenta='';
		$observaciones='';
		//verifico que no se repita el registro
		$numeroTotalRegistro = pg_num_rows($cr->buscarNombreRegistroProducto($conexion,$idCertificado));
		if(pg_num_rows($producto) == 0){
				
			if($numeroTotalRegistro == 0) {
				$mensajesAuditoria=array();
				if($esClon){
					//registra un clon			

					if($partidaArancelaria != '' || $partidaArancelaria != 0){
						$qCodigoProducto = $cc->obtenerCodigoProducto($conexion, $partidaArancelaria);
						$codigoProducto = str_pad(pg_fetch_result($qCodigoProducto, 0, 'codigo'), 4, "0", STR_PAD_LEFT);
					}else{
						$codigoProducto = 0;
					}
					$nombreCientifico='';
					
					$idProducto = pg_fetch_result($cr->guardarNuevoProducto($conexion, $nombreProducto, $nombreCientifico, $codigoProducto,  $partidaArancelaria, $idSubtipoProducto, $subTipoProducto['ruta'], $subTipoProducto['unidad_medida'], 'NO', 'NO', $usuario),0,'id_producto');					
					$mensajesAuditoria[]='ha creado el producto CLON con id '.$idProducto.' de nombre '.$nombreProducto;

					//Busca al producto madre
					$idProductoMadre=$subTipoProducto['id_producto'];
					$productoInocuidad=pg_fetch_assoc( $cr->buscarProductoInocuidad($conexion,$idProductoMadre));
					$idCategoriaToxicologica=$productoInocuidad['id_categoria_toxicologica'];
					$CategoriaToxicologica=$productoInocuidad['categoria_toxicologica'];
					
					$idFormulacion=$productoInocuidad['id_formulacion'];
					$nombreFormulacion=$productoInocuidad['formulacion'];
					
					$dosis=$productoInocuidad['dosis'];
					$unidadMedidaDosis=$productoInocuidad['unidad_dosis'];
					$periodoCarencia=$productoInocuidad['periodo_carencia_retiro'];
					$periodoReingreso=$productoInocuidad['periodo_reingreso'];
					$observaciones=$productoInocuidad['observacion'];
					$declaracionVenta=$productoInocuidad['declaracion_venta'];

					if($fechaRegistro == '') $fechaRegistro = $fechaActual = date('Y-m-d');
					
					$cr->guardarProductoInocuidad($conexion, $idProducto, $idFormulacion,$nombreFormulacion , $idCertificado, $dosis, $unidadMedidaDosis, $periodoCarencia, $periodoReingreso, $observaciones,$idCategoriaToxicologica,$CategoriaToxicologica,$fechaRegistro,$empresa,$declaracionVenta);
					$mensajesAuditoria[]='ha creado el producto CLON inocuidad con id '.$idProducto.' con registro '.$idCertificado;

					//sube los ingredientes activos				
					$vector=$cr->listarComposicionProductosInocuidad($conexion,$idProductoMadre);

					while ($item = pg_fetch_assoc($vector)){
						$idIngredienteActivo=$item['id_ingrediente_activo'];
						if(pg_num_rows($cr->buscarComposicion($conexion, $idProducto, $idIngredienteActivo))==0){
								
							$cr -> guardarProductoInocuidadTMP($conexion, $idProducto);
							$cr -> guardarComposicion($conexion, $idProducto, $idIngredienteActivo,$item['ingrediente_activo'],$item['concentracion'],$item['unidad_medida']);	
							$mensajesAuditoria[]='ha asociado el producto CLON con id '.$idProducto.' con la concentracion '.$item['ingrediente_activo'].' '.$item['concentracion'].$item['unidad_medida'];								
						}
					}
				
					//sube las presentaciones
					$vector=$cr->listarCodigoInocuidad($conexion,$idProductoMadre);
					while ($item = pg_fetch_assoc($vector)){
						$qSubcodigo = $cc->obtenerCodigoInocuidad($conexion, $idProducto);
						$subcodigo = str_pad(pg_fetch_result($qSubcodigo, 0, 'codigo'), 4, "0", STR_PAD_LEFT);
						$presentacion=$item['presentacion'];
						$unidad=$item['unidad_medida'];							
						if(pg_num_rows($cr->buscarCodigoInocuidad($conexion, $idProducto, $presentacion,$unidad))==0){								
							$cr -> guardarProductoInocuidadTMP($conexion, $idProducto);
							$cr -> guardarNuevoCodigoInocuidad($conexion, $idProducto,$subcodigo, $presentacion, $unidad);							
							$mensajesAuditoria[]='ha asociado al producto CLON con id '.$idProducto.' la presentación '.$presentacion;									
						}						
					}

					//sube los codigos complementarios y suplementarios	
					$vector=$cr->listarCodigoComplementarioSuplementario($conexion,$idProductoMadre);	
					while ($item = pg_fetch_assoc($vector)){
						$codigoComplementario=$item['codigo_complementario'];
						$codigoSuplementario=$item['codigo_suplementario'];
						if(pg_num_rows($cr->buscarCodigoComplementarioSuplementario($conexion, $idProducto, $codigoComplementario, $codigoSuplementario))==0){
							$cr -> guardarNuevoCodigoComplementarioSuplementario($conexion, $idProducto, $codigoComplementario, $codigoSuplementario);									
							$mensajesAuditoria[]='ha asociado al producto CLON con id '.$idProducto.' el codigo complementaio '.$codigoComplementario.' y el codigo suplementario '.$codigoSuplementario;								
						}										
					}

					//sube los fabricantes
					$vector=$cr->listarFabricanteFormulador($conexion,$idProductoMadre);	
					while ($item = pg_fetch_assoc($vector)){
						$formulador=$item['nombre'];
						$idPaisOrigen=$item['id_pais_origen'];
						$nombrePaisFabricante=$item['pais_origen'];							
						if(pg_num_rows($cr->buscarPaisformuladorFabricante($conexion,$formulador, $idPaisOrigen, $idProducto))==0){								
							$cr -> guardarProductoInocuidadTMP($conexion, $idProducto);
							$codigos = $cr -> guardarNuevoFabricanteFormulador($conexion, $idProducto,$formulador,$idPaisOrigen, $nombrePaisFabricante);	
							$mensajesAuditoria[]='ha asociado al producto CLON con id '.$idProducto.' al fabricante '.$formulador;															
						}												
					}

					//Sube los usos
					$vector=$cr->listarUsos($conexion,$idProductoMadre);	
					while ($uso = pg_fetch_assoc($vector)){
						$idUso=$uso['id_uso'];
						$nombreUso=$uso['nombre_uso'];
						$idCultivo=$uso['id_aplicacion_producto'];
						if(pg_num_rows($cr->buscarUsoProducto($conexion, $idProducto,$idUso, $idCultivo))==0){
							$cr ->guardarNuevoUso($conexion, $idProducto, $idUso,$idCultivo);
							$mensajesAuditoria[]='ha asociado al producto CLON con id '.$idProducto.' el uso '.$nombreUso.' al cultivo '.$uso['nombre_comun'];											
						}							
						
					}
					
				}
				else{
					//Registra con ensayo de eficacia
					$archivo=$dossier['ruta_dossier'];
					$etiqueta=$cg->obtenerEtiquetaSolicitud($conexion,$idSolicitud);
					$idCategoriaToxicologica=$etiqueta['id_categoria_toxicologica'];
					$CategoriaToxicologica=pg_fetch_result($ce->obtenerCategoriaToxicologica($conexion,$idCategoriaToxicologica),0,'categoria_toxicologica');
					$informeFinal=$ce->obtenerInformeFinalPorExpediente($conexion,$dossier['id_informe_final']);
					$datosProtocolo=$ce->obtenerProtocoloDesdeExpediente($conexion,$protocolo);
					$idFormulacion=$datosProtocolo['plaguicida_formulacion'];
					$nombreFormulacion=pg_fetch_result($ce->obtenerFormulacionActual($conexion,$idFormulacion),0,'formulacion');

					$cultivo=pg_fetch_assoc( $cc->obtenerNombreProducto($conexion,$datosProtocolo['cultivo']));
					$dosis=$informeFinal['dosis'].' para '.$cultivo['nombre_comun'];
					$unidadMedidaDosis=$informeFinal['dosis_unidad'];
					$periodoReingreso=trim($etiqueta['periodo_reingreso']);
					$periodoCarencia=trim($dossier['carencia']);

					if($partidaArancelaria != '' || $partidaArancelaria != 0){
						$qCodigoProducto = $cc->obtenerCodigoProducto($conexion, $partidaArancelaria);
						$codigoProducto = str_pad(pg_fetch_result($qCodigoProducto, 0, 'codigo'), 4, "0", STR_PAD_LEFT);
					}else{
						$codigoProducto = 0;
					}
					$nombreCientifico='';

					//limita la extención de los campos del modulo de registros
					$periodoReingreso=$this->limitarCaracteres($periodoReingreso,2046);	//limita a periodo de reingreso a 2046
					$periodoCarencia=$this->limitarCaracteres($periodoCarencia,1022);	//limita a periodo de reingreso a 1022
					$dosis=$this->limitarCaracteres($dosis,510);	//limita a periodo de reingreso a 510
					$observaciones =$this->limitarCaracteres($observaciones,1022);	//limita observaciones a 1022
					
					$idProducto = pg_fetch_result($cr->guardarNuevoProducto($conexion, $nombreProducto, $nombreCientifico, $codigoProducto,  $partidaArancelaria, $idSubtipoProducto, $archivo, $unidadMedida, 'NO', 'NO', $usuario),0,'id_producto');
					
					$mensajesAuditoria[]='ha creado el producto con id '.$idProducto.' de nombre '.$nombreProducto;
					if ($idCategoriaToxicologica == '') $idCategoriaToxicologica = 0;
					if ($idFormulacion == '') $idFormulacion = 0;
					if($fechaRegistro == '') $fechaRegistro = $fechaActual = date('Y-m-d');
					
					$cr->guardarProductoInocuidad($conexion, $idProducto, $idFormulacion,$nombreFormulacion , $idCertificado, $dosis, $unidadMedidaDosis, $periodoCarencia, $periodoReingreso, $observaciones,$idCategoriaToxicologica,$CategoriaToxicologica,$fechaRegistro,$empresa,$declaracionVenta);
					$mensajesAuditoria[]='ha creado el producto inocuidad con id '.$idProducto.' con registro '.$idCertificado;
					
					//sube los ingredientes activos				
					$vector=$cg->obtenerIngredientesSolicitud($conexion,$idSolicitud);
					if(count($vector)>0){
						$ingredientes=array();
						foreach($vector as $item){
							$idIngredienteActivo=$item['id_ingrediente_activo'];
							if(pg_num_rows($cr->buscarComposicion($conexion, $idProducto, $idIngredienteActivo))==0){
								
								$cr -> guardarProductoInocuidadTMP($conexion, $idProducto);
								$cr -> guardarComposicion($conexion, $idProducto, $idIngredienteActivo,$item['ingrediente_activo'],$item['concentracion'],$item['codigo']);
								$ingredientes[]=$item['ingrediente_activo'];	
								
								$mensajesAuditoria[]='ha asociado el producto con id '.$idProducto.' con la concentracion '.$item['ingrediente_activo'].' '.$item['concentracion'].$item['codigo'];
								
							}
						}
						/*Comentado ya que campo no es necesario EJAR
						 * $ingredienteActivo=join(' + ',$ingredientes);
						$cr -> actualizarComposicionProducto($conexion,$idProducto,$ingredienteActivo);*/
					}

					//sube las presentaciones
					$codigos=array();
					//$vector=$cg->obtenerPresentaciones($conexion,$idSolicitud);
					if(count($presentaciones)>0){
						foreach($presentaciones as $item){
							$qSubcodigo = $cc->obtenerCodigoInocuidad($conexion, $idProducto);
							$subcodigo = str_pad(pg_fetch_result($qSubcodigo, 0, 'codigo'), 4, "0", STR_PAD_LEFT);
							$presentacion=$item['cantidad'];
							$unidad=$item['codigo'];
							
							if(pg_num_rows($cr->buscarCodigoInocuidad($conexion, $idProducto, $presentacion,$unidad))==0){
								
								$cr -> guardarProductoInocuidadTMP($conexion, $idProducto);
								$cr -> guardarNuevoCodigoInocuidad($conexion, $idProducto,$subcodigo, $presentacion, $unidad);
								$codigos[]=array('codigo_complementario'=>str_pad($item['codigo_complementario'], 4, '0', STR_PAD_LEFT),'codigo_suplementario'=>str_pad($item['codigo_suplementario'], 4, '0', STR_PAD_LEFT));

								$mensajesAuditoria[]='ha asociado al producto con id '.$idProducto.' la presentación '.$presentacion;
									
							}
						}
						
					}

					//sube los codigos complementarios y suplementarios					
					if(count($codigos)>0){
						foreach($codigos as $item){
							$codigoComplementario=$item['codigo_complementario'];
							$codigoSuplementario=$item['codigo_suplementario'];
							if(pg_num_rows($cr->buscarCodigoComplementarioSuplementario($conexion, $idProducto, $codigoComplementario, $codigoSuplementario))==0){
								$cr -> guardarNuevoCodigoComplementarioSuplementario($conexion, $idProducto, $codigoComplementario, $codigoSuplementario);	
								
								$mensajesAuditoria[]='ha asociado al producto con id '.$idProducto.' el codigo complementaio '.$codigoComplementario.' y el codigo suplementario '.$codigoSuplementario;
								
							}
						}					
					}

					//sube los fabricantes
					$vector=$cg->obtenerFabricantes($conexion,$idSolicitud,'F');	
					foreach($vector as $fabricante){
						if(count($fabricante['manufacturadores'])>0){
							$vector=array_merge($vector,$fabricante['manufacturadores']);						
						}
					}
					
					if(count($vector)>0){
						foreach($vector as $item){
							$formulador=$item['nombre'];
							$idPaisOrigen=$item['id_pais'];
							$nombrePaisFabricante=$item['pais'];
							
							if(pg_num_rows($cr->buscarPaisformuladorFabricante($conexion,$formulador, $idPaisOrigen, $idProducto))==0){
								
								$cr -> guardarProductoInocuidadTMP($conexion, $idProducto);
								$codigos = $cr -> guardarNuevoFabricanteFormulador($conexion, $idProducto,$formulador,$idPaisOrigen, $nombrePaisFabricante);	
								$mensajesAuditoria[]='ha asociado al producto con id '.$idProducto.' al fabricante '.$formulador;
															
							}
							
						}						
					}

					//sube los formuladores
					$vector=$cg->obtenerFabricantes($conexion,$idSolicitud,'R');					
					foreach($vector as $fabricante){
						if(count($fabricante['manufacturadores'])>0){
							$vector=array_merge($vector,$fabricante['manufacturadores']);						
						}
					}
					if(count($vector)>0){
						foreach($vector as $item){
							$formulador=$item['nombre'];
							$idPaisOrigen=$item['id_pais'];
							$nombrePaisFabricante=$item['pais'];
							
							if(pg_num_rows($cr->buscarPaisformuladorFabricante($conexion,$formulador, $idPaisOrigen, $idProducto))==0){
								
								$cr -> guardarProductoInocuidadTMP($conexion, $idProducto);
								$codigos = $cr -> guardarNuevoFabricanteFormulador($conexion, $idProducto,$formulador,$idPaisOrigen, $nombrePaisFabricante);	
								$mensajesAuditoria[]='ha asociado al producto con id '.$idProducto.' al formulador '.$formulador;
															
							}
							
						}						
					}

					//Sube los usos
					if(count($cultivo)>0){
						$idCultivo=$cultivo['id_producto'];
						$plagas=$ce->obtenerPlagasProtocolo($conexion,$datosProtocolo['id_protocolo']);
						foreach($plagas as $plaga){
							$idUso=$plaga['plaga_codigo'];
							$nombreUso=$plaga['nombre'];
							if(pg_num_rows($cr->buscarUsoProducto($conexion, $idProducto,$idUso, $idCultivo))==0){
								$cr ->guardarNuevoUso($conexion, $idProducto, $idUso,$idCultivo);

								$mensajesAuditoria[]='ha asociado al producto con id '.$idProducto.' el uso '.$nombreUso.' al cultivo '.$cultivo['nombre_comun'];
											
							}							
						}
					}
					
				}
				
				/*AUDITORIA*/
				$qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto, $tipo_aplicacion);
				$transaccion = pg_fetch_assoc($qTransaccion);
								
				if($transaccion['id_transaccion'] == ''){
					$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
					$qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
				}
				foreach($mensajesAuditoria as $auditoria){							
					$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$usuario,'El usuario <b>' . $usuarioDatos . '</b> '.$auditoria);											
				}
				/*FIN AUDITORIA*/
				

			}				
		}
		
		ob_end_clean();
	}
	
	public function limitarCaracteres($textoOriginal,$largoMaximo){
			$nuevoTexto=trim($textoOriginal);
			if(strlen($nuevoTexto)>$largoMaximo){
				$nuevoTexto=substr($nuevoTexto,0,$largoMaximo);
			}
			return $nuevoTexto;
		}

}

?>


