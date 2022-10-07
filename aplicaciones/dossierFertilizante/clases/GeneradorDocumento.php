<?php

require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierFertilizante.php';


require_once '../ensayoEficacia/clases/Hoja.php';
require_once '../ensayoEficacia/clases/PdfStandar.php';

	class GeneradorDocumento{

		public function generarSolicitudRegistro($conexion,$DocumentoLegal,$id_solicitud){
			ob_start();
			$mensaje=array();
			$mensaje['mensaje'] = 'Error generando documento';
			$mensaje['estado'] = 'NO';

			$esBorrador=true;
			if($DocumentoLegal==='SI')
				$esBorrador=false;


			$ce = new ControladorEnsayoEficacia();
			$cr = new ControladorRegistroOperador();
			//$cc = new ControladorCatalogos();
			$cf=new ControladorDossierFertilizante();

			$datos=array();
			$operador=array();


			if($id_solicitud!=null && $id_solicitud!='_nuevo'){

				$datos=$cf->obtenerSolicitud($conexion, $id_solicitud);
				$identificador=$datos['identificador'];						//El duenio del documento

				$fabricantes=$cf->obtenerFabricantesDossier($conexion, $id_solicitud);

			
				$res = $cr->buscarOperador($conexion, $identificador);
				$operador = pg_fetch_assoc($res);
				$fileName='DFS_'.$identificador."_".$id_solicitud.'.pdf';

			}
			else{
				
				return $mensaje;
			}


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

			
			$strTitulo=$strTitulo.', en cumplimiento a lo dispuesto por las normas nacionales, solicito el Registro del producto: ';
			$strTitulo=$strTitulo.'"'.$datos['producto_nombre'].'"';

			$strTitulo=$strTitulo.'</div>';
			$x=$margen_izquierdo;
			$y=$doc->GetY();
			$doc->writeHTMLCell($xfull,5,$x,$y,$strTitulo,0,1);

			$doc->Cell($xfull,5,'Al efecto, consigno la siguiente información y el expediente que anexo:',0,1,'');


			$str='';

			$str=	$str.'<ol type="a">';

			$str=$str.'<li><p>ACTIVIDAD DEL SOLICITANTE : <br/>';
			$items = $ce->obtenerOperacionesDelOperador($conexion,$identificador,'IAF');
			$stri='';
			foreach ($items as $item){
				$stri=', '.$stri.$item['operacion'];
			}
			$stri=substr($stri,2);
			$str=$str.$stri.'</p></li>';

			$str=$str.'<li><p>DIRECCION DE LAS INSTALACIONES : <br/>';
			$str=$str.$operador['direccion'].', '.$operador['parroquia'].', '.$operador['canton'].', '.$operador['provincia'].', '.$operador['telefono_uno'].', '.$operador['correo'];
			$str=$str.'</p></li>';


			$str=$str.'<li><p>NOMBRE Y DIRECCIÓN DE LA (S) EMPRESA(S) FABRICANTE(S) O FORMULADORA(S): <br/>';
			$stri='';
			foreach ($fabricantes as $item){
				$stri=$stri.$item['empresa'].', '.$item['direccion'].'. ';
			}
			
			$str=$str.$stri;
			$str=$str.'</p></li>';

			$str=$str.'<li><p>NOMBRE DEL PRODUCTO : <br/>';

			$str=$str.$datos['producto_nombre'];
			$str=$str.'</p></li>';

			$str=$str.'<li><p>NOMBRE DEL INGREDIENTE ACTIVO: <br/>';
			$stri='';

			$items=$cf->obtenerComposicionProducto($conexion,$id_solicitud);
			$ingrediente_activo= array_column($items,'nombre');
			$ingrediente_activo=array_values( $ingrediente_activo);
			$ingrediente_activo=join(', ', $ingrediente_activo);

			$str=$str.$ingrediente_activo;
			$str=$str.'</p></li>';
			$str=$str.'<li><p>PAÍS(ES) DE ORIGEN: <br/>';

			$items=$cf->obtenerFabricantesDossier($conexion,$id_solicitud);
			$ingredientes_paises= array_column($items,'empresa');
			$ingredientes_paises=array_values( $ingredientes_paises);
			$ingredientes_paises=join(', ', $ingredientes_paises);
			$str=$str.$ingredientes_paises;
			$str=$str.'</p></li>';

			$str=$str.'<li><p>USO(S) PROPUESTO(S): <br/>';

			$items = $ce->listarElementosCatalogo($conexion,'P3C2');
			$usos='';
			foreach($items as $item){
				if($item['codigo']==$datos['uso'])
					$usos=$item['nombre'];
			}
			$str=$str.$usos;
			$str=$str.'</p></li>';

			$str=$str.'<li><p>TIPO Y CODIGO DE FORMULACIÓN: <br/>';

			$str=$str.'N/A';
			$str=$str.'</p></li>';

			$str=$str.'<li><p>PAÍS(ES) DE PROCEDENCIA: <br/>';

			$str=$str.'N/A';
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
			$paths=$ce->obtenerRutaAnexos($conexion,'dossierFertilizante');
			$doc->Output($paths['rutaFisica'].'/'.$fileName, 'F');
			$mensaje['datos'] = $paths['ruta'].'/'.$fileName;


			ob_end_clean();

			$mensaje['mensaje'] = 'Archivo generado';
			$mensaje['estado'] = 'exito';

			return $mensaje;

		}


	}


?>


