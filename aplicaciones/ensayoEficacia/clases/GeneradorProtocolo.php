<?php
session_start();

require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';

require_once 'PdfLibre.php';
require_once 'Hoja.php';
require_once 'Protocolo.php';



class GeneradorProtocolo
{

	public function generarProtocolo($conexion,$idProtocolo,$tituloPrevio,$esDocumentoLegal='SI'){

		ob_start();

		$mensaje=array();
		$mensaje['mensaje'] = 'Error generando documento';
		$mensaje['estado'] = 'NO';

		$esBorrador=true;
		if($esDocumentoLegal==='SI')
			$esBorrador=false;

		$ce = new ControladorEnsayoEficacia();
		$cr = new ControladorRegistroOperador();
		$cc = new ControladorCatalogos();

		//recupera el protocolo
		if($idProtocolo==null || $idProtocolo=='_nuevo'){
			ob_end_clean();
			return $mensaje;
		}

		

		$datos=$ce->obtenerProtocolo($conexion, $idProtocolo);
		$identificador=$datos['identificador'];		//El usuario actual
		$res = $cr->buscarOperador($conexion, $identificador);
		$operador = pg_fetch_assoc($res);

		$fileName="EP_".$identificador."_".$idProtocolo.'.pdf';

		$registroPlaguicida=array();
		$tratamientos=array();

		if($datos['motivo']!="MOT_REG")
			$registroPlaguicida=$ce->obtenerProductoRegistrado($conexion, $datos['plaguicida_registro']);
		$arhivosProtocolo=$ce->listarArchivosAnexos($conexion,$idProtocolo);

		$plagasDelProtocolo=$ce->obtenerPlagasProtocolo($conexion,$idProtocolo);

		//cargar tratamientos
		if($datos['tratamientos']==null || $datos['tratamientos']==0 || $datos['tratamientos']=='')
			$datos['tratamientos']=5;
		else{
			//verifico si ya hay guardado
			$tratamientos=$ce->obtenerTratamientosDosis($conexion,$idProtocolo);
		}


		//lleno de plaga
		$evaluacionesPlagas=$ce->obtenerEvaluacionesPlagas($conexion,$idProtocolo);
		$tecnicosReconocidos=$ce->obtenerTecnicosReconocidos($conexion);
		$ciTecnicosReconocidos=array();
		foreach ($tecnicosReconocidos as $key=>$item){
			$a=array();
			$a['value']=$item['identificador'];
			$a['label']='('.$item['identificador'].')'.$item['nombres'];
			$ciTecnicosReconocidos[]=$a;
		}

		$cultivosNombres = $ce->obtenerProductosXSubTipo($conexion,'CULTIVOS');

		$items=$cc->listarLocalizacion($conexion,'PROVINCIAS');
		$catalogoProvincias=array();
		while ($fila = pg_fetch_assoc($items)){
			$catalogoProvincias[] = array('codigo'=>$fila['id_localizacion'],'nombre'=>$fila['nombre']);
		}

		$items=$ce->obtenerSubTiposXcodigo($conexion,'RIA-%');
		$catalogoSubTipos=array();
		foreach ($items as $item){
			$subTipo=array();
			$subTipo['id_subtipo_producto']=$item['id_subtipo_producto'];
			$subTipo['codigo']=$item['codificacion_subtipo_producto'];
			$subTipo['nombre']=$item['nombre'];
			$catalogoSubTipos[]=$subTipo;
		}

		$formulaciones=$ce->obtenerFormulaciones($conexion,'SI');

		$numeroProtocolo='';
		if(($datos['id_expediente']==null) || (trim($datos['id_expediente'])==''))
			$numeroProtocolo=$datos['id_protocolo'];
		else
			$numeroProtocolo=$datos['id_expediente'];

		$margen_superior=20;
		$margen_inferior=15;
		$margen_izquierdo=20;
		$margen_derecho=17;

		
		$doc=new PdfLibre('L','mm','A4',true,'UTF-8');

		if($esBorrador)
		   $doc->PonerBorrador(true);
		else
		   $doc->PonerBorrador(false);

		$doc->setNumeroSolicitud($numeroProtocolo);

		$doc->SetLineWidth(0.1);



		$doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
		$doc->SetAutoPageBreak(TRUE, $margen_inferior);
		$doc->setImageScale(PDF_IMAGE_SCALE_RATIO);

		$doc->setFontSubsetting(false);

		$doc->AddPage();

		$hoja=new Hoja();
		$hoja->MargenSuperior($margen_superior);
		$hoja->MargenInferior($margen_inferior);
		$hoja->MargenIzquierdo($margen_izquierdo);

		$protocolo=new Protocolo($datos);

		$xfull=260;
		$ymin=4;
		$mx=$margen_izquierdo;
		$my=$margen_superior;
		$xcuadro=7;
		$f1=$my+17;


		//****************************** INICIA *************************************


		$doc->SetTextColor();
		$doc->SetAbsXY($mx+70,$my);
		$doc->SetFont('times', 'B', 14);
		$doc->Cell(130,0,"Evaluación y Supervición de Ensayos de Eficacia",0,0,'L',false,0,0,true,'T','C');
		
		$date=new DateTime($datos['fecha_solicitud']);
		if($date==null){
			$date=new DateTime();
		}
		$doc->SetAbsXY($mx,$f1);
		//verifica si es modificacion
		$textoFechaSolicitud="Fecha de solicitud : ";
		if($datos['es_modificacion']=='t')
			$textoFechaSolicitud="Fecha de modificación : ";
		
		$date=$date->format('Y-m-d');
		
		$doc->SetFont('times', '', 12);
		
		$doc->writeHTMLCell(80,4,$mx,$f1-1,'<b>'.$textoFechaSolicitud.'</b>'.$date,0,0,false,true);

		$doc->SetFont('times', 'B', 12);
		$doc->SetAbsXY($mx+100,$f1);
		$doc->Cell(30,4,'SOLICITUD N° :',0,0,'L',false,0,0,true,'T','B');
		$doc->SetFont('times', '', 12);
		$doc->SetAbsXY($mx+135,$f1);
		
		$doc->Cell(30,4,$numeroProtocolo,0,0,'L',false,0,0,true,'T','B');


		if($esDocumentoLegal==='SI')
		{
			$date=new DateTime($datos['fecha_aprobacion']);
			if($date==null){
				$date='';
			}
			else
				$date=$date->format('Y-m-d');
			$doc->SetFont('times', 'B', 12);
			$doc->SetAbsXY($mx+185,$f1);
			$doc->Cell(30,4,'Fecha de aprobación:',0,0,'L',false,0,0,true,'T','B');
			$doc->SetFont('times', '', 12);
			$doc->SetAbsXY($mx+225,$f1);
			$doc->Cell(30,4,$date,0,0,'L',false,0,0,true,'T','B');
		}
		//**********************  Titulo ************************************

		$f2=$my+21;
		$doc->SetFont('times', 'B', 7);
		$doc->SetAbsXY($mx,$f2);
		$doc->Cell($xfull,25,'Título del ensayo:',1,0,'L',false,0,0,true,'T','T');
		$doc->SetAbsXY($mx+7,$f2+4);
		$doc->SetFont('times', '', 12);
		$doc->MultiCell($xfull-10,25,$tituloPrevio,0,'J',false,1,'','',true,0,true);

		$doc->SetFont('times', 'B', 12);
		$doc->Ln(2);
		$doc->Cell(40,5,'I SOLICITUD',0,1,'L',false,0,0,true,'T','C');
		$doc->SetFont('times', '', 9);
		$doc->Cell($xfull,$ymin,'Datos del solicitante',1,1,'L',false,0,0,true,'T','T');

		//**********************************  01  *****************************************
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,42,$ymin,$filas,$xcuadro,'01','Tipo razón social',$operador['tipo_operador']);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,162,$ymin,$filas,$xcuadro,'02','Razón social (Según RUC)',$operador['razon_social']);
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'03','No. RUC ó cédula de identidad',$datos['identificador'],true);



		//********************************** 04 domicilio *************************************
		$doc->SetFont('times', 'B', 9);
		$doc->Cell($xfull,$ymin,'Domicilio legal',1,1,'L',false,0,0,true,'T','T');
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,84,$ymin,$filas,$xcuadro,'04','Dirección',$operador['direccion']);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,37,$ymin,$filas,$xcuadro,'05','Provincia',$operador['provincia']);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,76,$ymin,$filas,$xcuadro,'06','Cantón',$operador['canton']);
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'07','Parroquia',$operador['parroquia'],true);

		//********************************** 08 domicilio *************************************
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,84,$ymin,$filas,$xcuadro,'08','Referencia de la dirección',$datos['direccion_referencia']);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,30,$ymin,$filas,$xcuadro,'09','Teléfono',$operador['telefono_uno']);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,51,$ymin,$filas,$xcuadro,'10','Celular',$operador['celular_uno']);
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'11','Dirección electrónica',$operador['correo'],true);

		//********************************** 12******************************************************
		$doc->SetFont('times', '', 9);
		$doc->Cell($xfull,$ymin,'Representante legal',1,1,'L',false,0,0,true,'T','T');

		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,84,$ymin,$filas,$xcuadro,'12','Cédula de identidad',$datos['ci_representante_legal']);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,82,$ymin,$filas,$xcuadro,'13','Nombres completos',$operador['nombre_representante'].' '.$operador['apellido_representante']);
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'14','Correo electrónico',$datos['email_representante_legal'],true);


		//******************************** CONDICIONES EXPERIMENTALES *****************************************************
		$doc->Ln(2);
		$doc->SetFont('times', 'B', 12);
		$doc->Cell(40,5,'II CONDICIONES EXPERIMENTALES',0,1,'L',false,0,0,true,'T','C');
		$doc->SetFont('times', '', 9);
		$doc->Cell($xfull,$ymin,'Datos generales del ensayo',1,1,'L',false,0,0,true,'T','T');
		//********************************** 15 ******************************************************
		$objetivos = $ce->listarElementosCatalogo($conexion,'P1C1');
		$filas=sizeof($objetivos);
		$xactual=$mx;
		$yactual=$doc->GetY();
		$f='';
		$normativaLista = $ce->listarElementosCatalogo($conexion,'P1C30');
		foreach ($normativaLista as $key=>$item){
			if(strtoupper($item['codigo']) == strtoupper($datos['normativa'])){
				$f=$item['nombre'];
				break;
			}
		}

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,84,$ymin,$filas,$xcuadro,'15.1','Normativa aplicada',$f);
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroMuliple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'15.2','Objetivo del ensayo',$objetivos,'nombre',true);

		//********************************** 16 ******************************************************

		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();
		$f='';
		$items = $ce->listarElementosCatalogo($conexion,'P1C2');
		foreach ($items as $key=>$item){
			if(strtoupper($item['codigo']) == strtoupper($datos['motivo'])){
				$f=$item['nombre'];
				break;
			}
		}
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'16','Motivo del ensayo',$f,true);

		//********************************** 17 ******************************************************
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();
		$f='';
		foreach ($tecnicosReconocidos as $key=>$item){
			if(strtoupper($item['identificador']) == strtoupper($datos['ci_tecnico_reconocido'])){
				$f=$item['nombres'];
				break;
			}
		}

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,84,$ymin,$filas,$xcuadro,'17','No. cédula del técnico reconocido por la ANC',$datos['ci_tecnico_reconocido']);
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'18','Nombre y apellido del técnico reconocido por la ANC',$f,true);

		//********************************** 19 ******************************************************
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();
		$nomCien='';
		$nomComun='';
		foreach ($cultivosNombres as $key=>$item){
			if(strtoupper($item['id_producto']) == strtoupper($datos['cultivo'])){
				$nomCien=$item['nombre_cientifico'];
				$nomComun=$item['nombre_comun'];
				break;
			}
		}
		$f='';
		foreach ($catalogoSubTipos as $key=>$item){
			if(strtoupper($item['codigo']) == strtoupper($datos['uso'])){
				$f=$item['nombre'];
				break;
			}
		}

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,84,$ymin,$filas,$xcuadro,'19','Nombre cientifico del cultivo',$nomCien);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,84,$ymin,$filas,$xcuadro,'20','Nombre común del cultivo',$nomComun);
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'21','Uso propuesto del producto',$f,true);

		//********************************** 22 ******************************************************
		if(count($plagasDelProtocolo)>0)
			$filas=sizeof($plagasDelProtocolo);
		else
			$filas=1;
		$filas=4*$filas;

		$xactual=$mx;
		$yactual=$doc->GetY();
		
		$f=array();
		$fp=array();

		//Escribe encabezado
		$strPunto='.';
		if(count($plagasDelProtocolo)==0){
			$strPunto='N/A';
			$filas=1;
		}

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,62,$ymin,$filas,$xcuadro,'22','Nombre cientifico de la plaga',$strPunto,false,false,false);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,53,$ymin,$filas,$xcuadro,'23','Nombre común de la plaga',$strPunto,false,false,false);
		$paso=$xfull-$xactual+$mx;

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'24','Identificación de la plaga(s) (nombre cientifico y taxones principales)',$strPunto,false,false,false);
		$doc->Ln();
		
		
		$contador=1;
		foreach ($plagasDelProtocolo as $key=>$plaga){
			$xactual=$mx;
			
			$filas=4;
			$datoPlaga=array();
			
			$datoPlaga['Clase : ']=$plaga['clase'];

			$datoPlaga['Orden : ']=$plaga['orden'];
			$datoPlaga['Familia : ']=$plaga['familia'];
			$datoPlaga['Genero : ']=$plaga['genero'];
			$hoja->escribirPdfCuadroMulipleVector($doc,$xactual,$yactual,62,$ymin,$filas,$xcuadro,'22','',array($contador.')'=>$plaga['nombre']),false,false,false);
			$nombreComun=$plaga['nombre2'];
			if(($datos['uso']=='RIA-F') && ($datos['complejo_fungico']=='t'))
				$nombreComun=$plaga['nombre_fungico'];
			$hoja->escribirPdfCuadroMulipleVector($doc,$xactual,$yactual,53,$ymin,$filas,$xcuadro,'23','',array($contador.')'=>ucfirst($nombreComun)),false,false,false);
			$paso=$xfull-$xactual+$mx;
			$hoja->escribirPdfCuadroMulipleVector($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'24','',$datoPlaga,false,false,false);
			$doc->Ln();
			$yactual=$doc->GetY();
			$contador++;
		}


		//********************************** 25 ******************************************************
		$yh=$doc->getPageHeight()-$margen_superior-$margen_inferior;
		$count=sizeof($plagasDelProtocolo);

		$yAlto=$ymin+$ymin;
		if($yactual+$yAlto>$yh){
			$doc->AddPage();
			$yactual=$margen_superior;
		}

		$cien='';

		$xactual=$mx;
		$yactual=$doc->GetY();
		$yantes=$yactual;
		$paso=$xfull-$xactual+$mx;
		

		$doc->SetFont('times', 'B', 7);
		$doc->SetAbsXY($xactual,$yactual);
		$doc->Cell($xcuadro,$ymin,'25',1,0,'L',false,0,0,true,'T','T');
		$doc->Cell($paso-$xcuadro-2,$ymin,'Biología de la plaga',0,1,'L',false,0,0,true,'T','T');
		$doc->SetFont('times', '', 9);
		$yactual=$yantes;
		if($count==0){
			$xactual=$mx;
			$doc->SetAbsXY($xactual,$yactual+4);
			$doc->MultiCell($xfull-2,4,'N/A',0,'L',false,1,'','',true);
		}
		$str='<br/>';
		$pos=1;
		foreach ($plagasDelProtocolo as $key=>$plaga){
		
			$cien=$plaga['nombre'];
			$str=$str.'<br/><b>'.$pos.') '.$cien.'</b>';
			$str=$str.'<br/><b>Ciclo : </b>'.$plaga['ciclo'];
			$str=$str.'<br/><b>Habito : </b>'.$plaga['habito'];
			$str=$str.'<br/><b>Comportamiento : </b>'.$plaga['comportamiento'];
			$str=$str.'<br/><b>Estadío : </b>'.$plaga['estadio'];

			$doc->writeHTMLCell(0, 8, $xactual, $yactual, $str, 1, 1,  0, true, 'J',  true);
			$str='';

			$yactual=$doc->GetY();
			$pos++;
		}

		//********************************** 26 ******************************************************
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();
		$f='';
		$items = $ce->listarElementosCatalogo($conexion,'P1C9');
		foreach ($items as $key=>$item){
			if(strtoupper($item['codigo']) == strtoupper($datos['condicion_experimento'])){
				$f=$item['nombre'];
				break;
			}
		}
		$zonas="";
		$numZonas=1;
		if($datos['normativa']=="NA" && $datos['motivo']=="MOT_REG"){
			$numZonas=2;
			$zonas='En dos zonas diferentes';
		}
		else
			$zonas='Una zona';
		$doc->SetAbsXY($xactual,$yactual);
		$hoja->setYmax($yactual);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,107,$ymin,$filas,$xcuadro,'26','Condición del experimento',$f);
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'27','Ubicación geográfica y características agro ecológicas',$zonas,true);

		//********************************** 28 ******************************************************
		$doc->Cell($xfull,$ymin,'Indicar los lugares considerados para realizar los ensayos',1,1,'L',false,0,0,true,'T','T');
		//********************************** 28 ******************************************************

		$f=array();
		$zonaGeo=$ce->obtenerProtocoloZonas($conexion,$idProtocolo);
		foreach ($zonaGeo as $key=>$z){

			if($z['provincia']==null ||$z['provincia']=="" || $z['provincia']==0)
				continue;
			$f[]=array('provincia_nombre'=>$z['provincia_nombre'],'canton_nombre'=>$z['canton_nombre'],'parroquia_nombre'=>$z['parroquia_nombre'],'fecha'=>'');
		}
		$filas=sizeof($f);
		$xactual=$mx;
		$yactual=$doc->GetY();

		$hoja->escribirPdfCuadroMuliple($doc,$xactual,$yactual,42,$ymin,$filas,$xcuadro,'28','Provincia',$f,'provincia_nombre');
		$hoja->escribirPdfCuadroMuliple($doc,$xactual,$yactual,65,$ymin,$filas,$xcuadro,'29','Cantón',$f,'canton_nombre');


		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroMuliple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'30','Parroquia',$f,'parroquia_nombre',true);

		//********************************** Punto 32 *************************************
		$doc->Cell($xfull,$ymin,'Diseño del experimento',1,1,'L',false,0,0,true,'T','T');

		//********************************** 32 ******************************************************
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,48,$ymin,$filas,$xcuadro,'32.1','Diseño del experimento',substr($datos['diseno_experimento'],4));
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,58,$ymin,$filas,$xcuadro,'32.2','Otro diseño (especificar)',$datos['diseno_otro']);
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'33','Tamaño de la parcela','Area total: '.$datos['parcela_total'].' m2        Area de la unidad: '.$datos['parcela_unidad'].' m2        Area util: '.$datos['parcela_util'].' m2',true);

		//********************************** 34 ******************************************************
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();

		$repeticiones=$datos['repeticiones'];
		$numObservaciones=$datos['observaciones'];
		if($datos['diseno_experimento']=='DEX_DCA')
			$repeticiones="N/A";
		else
			$numObservaciones="N/A";

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,48,$ymin,$filas,$xcuadro,'34','No. de tratamientos',$datos['tratamientos']);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,58,$ymin,$filas,$xcuadro,'35.1','No. de repeticiones',$repeticiones);
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'35.2','No. de observaciones',$numObservaciones,true);

		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'36','Otra información no considerada en esta sección',$datos['experimento_otra_info'],true);

		//******************************** APLICACION DE LOS TRATAMIENTOS *****************************************************

		$doc->SetFont('times', 'B', 12);
		$doc->Cell(100,5,'III APLICACION DE LOS TRATAMIENTOS',0,1,'L',false,0,0,true,'T','C');
		//********************************** Punto 37 *************************************
		$doc->SetFont('times', '', 9);
		$doc->Cell($xfull,$ymin,'Plaguicida en prueba (bajo evaluación)',1,1,'L',false,0,0,true,'T','T');

		//********************************** 37 ******************************************************
		$xactual=$mx;
		$yactual=$doc->GetY();
		$f=array();
		$iaNombre='';
		if($datos['motivo']=="MOT_REG"){
			$ias=$ce->obtenerIaDelProtocolo($conexion,$idProtocolo);
			if(count($ias)>0)
				$filas=sizeof($ias);
			else
				$filas=1;

			foreach ($ias as $key=>$item){
				$f[]=array('grupo_quimico'=>$item['grupo_quimico']);
				$iaNombre=$iaNombre.' + '.$item['ingrediente_activo'].' '.$item['concentracion'].' '.strtolower($item['codigo']);
			}
			if(strlen($iaNombre)>2)
				$iaNombre=substr($iaNombre,3);
			$fp='';
			foreach ($formulaciones as $key=>$item){
				if(strtoupper($item['id_formulacion']) == strtoupper($datos['plaguicida_formulacion'])){
					$fp=$item['sigla'];
					break;
				}
			}
			if(strlen( $iaNombre)>0)
				$iaNombre=$iaNombre.', '.$fp;
		}
		else{
			$ias=$registroPlaguicida['composicion'];
			if(count($ias)>0)
				$filas=sizeof($ias);
			else
				$filas=1;

			$f=$ias;
			$iaNombre=$protocolo->ObtenerComposicion($ias);
			if(strlen($iaNombre)>0)
				$iaNombre=$iaNombre.', '.$protocolo->ObtenerCodigoFormulacion($formulaciones,$registroPlaguicida['producto'][0]['id_formulacion']);


		}

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,64,$ymin,$filas,$xcuadro,'37.1','Tipo de plaguicida',$datos['plaguicida_tipo']);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,65,$ymin,$filas,$xcuadro,'37.2','No. de registro',$datos['plaguicida_registro']);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,65,$ymin,$filas,$xcuadro,'38','Nombre del plaguicida',$datos['plaguicida_nombre']);
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroMuliple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'39','Grupo(s) químico(s)',$f,'grupo_quimico',true);

		$xactual=$mx;
		$yactual=$doc->GetY();
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'40','Ingrediente(s) activo(s)',$iaNombre,true);

		//********************************** 41 ******************************************************
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();
		$f='';
		$catalogoModosAccion = $ce->listarElementosCatalogo($conexion,'P1C14');
		foreach ($catalogoModosAccion  as $key=>$item){
			if(substr_count($datos['plaguicida_modo_accion'], $item['codigo']) > 0)
				$f=$f.', '.$item['nombre'];
		}
		if(strlen($f)>2)
			$f=substr($f,2);


		$paisOrigen=array();
		try{
			$paisOrigen=pg_fetch_assoc( $cc->obtenerLocalizacion($conexion,$datos['plaguicida_pais_origen']),0);
		}catch(Exception $e){}
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,64,$ymin,$filas,$xcuadro,'41','Formulador',$datos['plaguicida_formulador']);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,30,$ymin,$filas,$xcuadro,'42','País de origen',$paisOrigen['nombre']);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,50,$ymin,$filas,$xcuadro,'43','No. de lote',$datos['plaguicida_no_lote']);

		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'44','Modo de acción',$f,true);

		//********************************** 45 ******************************************************
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,188,$ymin,$filas,$xcuadro,'45','Mecanismo de acción',$datos['plaguicida_mecanismo']);
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'46','Considera plaguicida de referencia ?',$datos['pr_tiene']=='t'?'SI':'NO',true);
		//********************************** 47 ******************************************************
		$filas=1;
		$xactual=$mx;

		$yactual=$hoja->getYmax();

		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'47','En caso de no utilizar plaguicida de referencia, indicar la razon',$datos['pr_tiene_razon'],true);



		//********************************************************** Punto 48 ****************************************************************

		$doc->Cell($xfull,$ymin,'Datos del plaguicida de referencia',1,1,'L',false,0,0,true,'T','T');

		$xactual=$mx;
		$yactual=$doc->GetY();

		$plagicidaRegistrado=$ce->obtenerProductoRegistrado($conexion,$datos['pr_registro']);
		$ias=$plagicidaRegistrado['composicion'];
		$composicion=$protocolo->ObtenerComposicion($ias);
		if(strlen($composicion)>0)
			$composicion=$composicion.', '.$protocolo->ObtenerCodigoFormulacion($formulaciones,$plagicidaRegistrado['producto'][0]['id_formulacion']);

		if(count($ias)>0)
			$filas=sizeof($ias);
		else
			$filas=1;

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,42,$ymin,$filas,$xcuadro,48,'No. de registro',$datos['pr_registro']);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,58,$ymin,$filas,$xcuadro,49,'Nombre del plaguicida',$plagicidaRegistrado['producto'][0]['nombre_comun']);
		$hoja->escribirPdfCuadroMuliple($doc,$xactual,$yactual,58,$ymin,$filas,$xcuadro,50,'Grupo(s) químico(s)',$ias,'grupo_quimico');
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'51','Ingrediene(s) activo(s)',$composicion,true);

		//********************************************************** Punto 52 ****************************************************************
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();
		$f=array();
		foreach ($plagicidaRegistrado['fabricantes'] as $key=>$item){
			if($datos['pr_formulador']==$item['id_fabricante_formulador']){
				$f=$item;
				break;
			}
		}
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,114,$ymin,$filas,$xcuadro,'52','Formulador',$f['nombre']);
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'53','Pais de origen',$f['pais_origen'],true);

		//********************************************************** Punto 54 ****************************************************************
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();

		$nombresModoAccion=$protocolo->ObenerNombresDeChecked($catalogoModosAccion,$datos['pr_modo_accion']);

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,114,$ymin,$filas,$xcuadro,'54','Modo de acción',$nombresModoAccion);
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'55','Mecanismo de acción',$datos['pr_mecanismo'],true);

		//********************************************************** Punto 54 ****************************************************************
		$doc->Cell($xfull,$ymin,'Coadyuvante / Producto',1,1,'L',false,0,0,true,'T','T');

		$filas=2;
		$xactual=$mx;
		$yactual=$doc->GetY();

		$cyRegistrado=$ce->obtenerProductoRegistrado($conexion,$datos['cp_registro']);
		$ias=$cyRegistrado['composicion'];
		$composicion=$protocolo->ObtenerComposicion($ias);
		if($composicion==null || trim($composicion)=='')
			$composicion='';
		else
			$composicion=$composicion.', '.$protocolo->ObtenerCodigoFormulacion($formulaciones,$cyRegistrado['producto'][0]['formulacion']);
		$fp='';
		foreach ($cyRegistrado['fabricantes'] as $key=>$item){
			$fp=$fp.', '.$item['pais_origen'];
		}
		if(strlen($fp)>2)
			$fp=substr($fp,2);
		$f=array();

		$f=array(array('dato'=>$cyRegistrado['producto'][0]['nombre_comun']));

		if(count($ias)>0)
			$filas=sizeof($ias);
		else
			$filas=1;

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,42,$ymin,$filas,$xcuadro,'57.1','No. de registro',$datos['cp_registro']);
		$hoja->escribirPdfCuadroMuliple($doc,$xactual,$yactual,58,$ymin,$filas,$xcuadro,'57.2','Nombre comercial',$f,'dato');
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'58','Dosis del coadyuvante/producto',$composicion,true);

		//********************************************************** Punto 59 ****************************************************************


		$filas=2;
		$xactual=$mx;
		$yactual=$doc->GetY();

		$items = $ce->listarElementosCatalogo($conexion,'P1C15');
		$f='';
		foreach ($items as $key=>$item){
			if(strtoupper($item['codigo']) == strtoupper($datos['tipo_aplicacion'])){
				$f=$item['nombre'];
				break;
			}
		}

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,190,$ymin,$filas,$xcuadro,'59','Tipo de aplicación',$f);
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'60','Describir otro tipo de aplicación(de ser el caso)',$datos['tipo_aplicacion_otro'],true);

		//********************************************************** Punto 61 ****************************************************************
		$filas=2;
		$xactual=$mx;
		$yactual=$doc->GetY();

		$items = $ce->listarElementosCatalogo($conexion,'P1C16');
		$f='';
		foreach ($items as $key=>$item){
			if(strtoupper($item['codigo']) == strtoupper($datos['equipo_usado'])){
				$f=$item['nombre'];
				break;
			}
		}

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,90,$ymin,$filas,$xcuadro,'61','Tipo de equipo usado',$f);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,100,$ymin,$filas,$xcuadro,'62','Describir otro equipo usado (de ser el caso)',$datos['equipo_usado_otro']);

		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'63','Tipo de boquilla',$datos['tipo_boquilla'],true);

		//********************************************************** Punto 64 ****************************************************************
		$filas=3;
		$xactual=$mx;
		$yactual=$doc->GetY();

		$items = $ce->listarElementosCatalogo($conexion,'P1C17');
		$f='';
		foreach ($items as $key=>$item){
			if(strtoupper($item['codigo']) == strtoupper($datos['momento_aplicacion'])){
				$f=$item['nombre'];
				break;
			}
		}

		$fp='';
		$respuesta=$cc->listarUnidadesMedidaXTipo($conexion,'composicion');
		while ($item = pg_fetch_assoc($respuesta)){
			if(strtoupper($item['id_unidad_medida']) == strtoupper($datos['unidad_dosis'])){
				$fp=$item['codigo'];
				break;
			}
		}



		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,55,$ymin,$filas,$xcuadro,'64','Momento de aplicación del plaguicida',$f);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,50,$ymin,$filas,$xcuadro,'','Fenología del cultivo',$datos['aplicacion_fenologia']);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,50,$ymin,$filas,$xcuadro,'','Umbral económico de la plaga',$datos['aplicacion_umbral']);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,50,$ymin,$filas,$xcuadro,'','Intervalo de aplicación [días]',$datos['aplicacion_intervalo']);
		$xanterior=$xactual;
		$filas=1;
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'65','Unidades de la dosis del plaguicida',$fp,true);
		$xactual=$xanterior;
		$yactual=$yactual+2*$ymin;

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'66','Otras unidades(de ser el caso)',$datos['unidad_dosis_otro'],true);

		//********************************************************** Punto 67 ****************************************************************

		$filas=1+sizeof($tratamientos);
		$items = $ce->listarElementosCatalogo($conexion,'P1C20');
		$f=sizeof($items);
		if($f>$filas)
			$filas=$f;
		$yactual=$doc->GetY();
		$yh=$doc->getPageHeight()-$margen_superior-$margen_inferior;
		$yAlto=3+(1+$filas)*$ymin;

		if($yactual+$yAlto>$yh){
			$doc->AddPage();
			$yactual=$margen_superior;

		}

		$xactual=$mx;

		$yantes=$yactual;
		$yactual=$yactual+$ymin;

		$doc->SetAbsXY($xactual,$yactual);
		$doc->Cell(90,$ymin,'TRATAMIENTO',1,0,'L',false,0,0,true,'T','T');
		$doc->Cell(65,$ymin,'DOSIS',1,1,'L',false,0,0,true,'T','T');
		foreach ($tratamientos as $key=>$item){
			$doc->Cell(90,$ymin,'Tratamiento T'.$item['codigo'],1,0,'L',false,0,0,true,'T','T');
			$doc->Cell(65,$ymin,number_format($item['dosis'],2).' '.$fp,1,1,'L',false,0,0,true,'T','T');
		}
		$ybajo=$doc->GetY();
		$items = $ce->listarElementosCatalogo($conexion,'P1C20');
		$yactual=$yantes;

		$hoja->setYmax($doc->GetY());

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,155,$ymin,$filas,$xcuadro,'67','Dosis y volúmenes','.');
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroMuliple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'68','Usos de equipos de protección',$items,'nombre',true);

		//********************************************************** Punto 68 ****************************************************************
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();
		if($yactual<$ybajo)
			$yactual=$ybajo;

		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'69','Indicar otro equipo de uso de protección',$datos['equipo_proteccion_otro'],true);

		//********************************************************** Punto 70 ****************************************************************
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();

		$items = $ce->listarElementosCatalogo($conexion,'P1C21');
		$f='';
		foreach ($items as $key=>$item){
			if(strtoupper($item['codigo']) == strtoupper($datos['estadio'])){
				$f=$item['nombre'];
				break;
			}
		}

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,152,$ymin,$filas,$xcuadro,'70','Aplicación según estadío del insecto o ácaro',$f);

		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'71','Indicar otro',$datos['estadio_otro'],true);

		//********************************************************** Punto 72 ****************************************************************
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();
		$f='';
		$fp='';
		if($datos['uso']=="RIA-H"){	//Herbicida
			$items = $ce->listarElementosCatalogo($conexion,'P1C23');
			foreach ($items as $key=>$item){
				if(strtoupper($item['codigo']) == strtoupper($datos['aplicacion_herbicida'])){
					$f=$item['nombre'];
					break;
				}
			}
		}
		else if($datos['uso']=="RIA-F"){	//Herbicida
			$items = $ce->listarElementosCatalogo($conexion,'P1C22');
			foreach ($items as $key=>$item){
				if(strtoupper($item['codigo']) == strtoupper($datos['aplicacion_funguicida'])){
					$fp=$item['nombre'];
					break;
				}
			}
		}


		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,130,$ymin,$filas,$xcuadro,'72','Aplicación del funguicida',$fp);
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'73','Aplicación del herbicida',$f,true);

		//********************************************************** Punto 74 ****************************************************************
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();

		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'74','Otra información no considerada en esta sección',$datos['modo_aplicacion_info'],true);

		//********************************************************** SECCION II ****************************************************************
		$doc->Ln(2);
		$doc->SetFont('times', 'B', 12);
		$doc->Cell($xfull,$ymin,'IV MODO DE EVALUACION, DE REGISTRO DE DATOS Y MEDICIONES',1,1,'L',false,0,0,true,'T','T');
		$doc->SetFont('times', '', 9);
		$doc->Cell($xfull,$ymin,'Datos meteorológicos del aire y del suelo',1,1,'L',false,0,0,true,'T','T');

		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();

		$items = $ce->listarElementosCatalogo($conexion,'P1C24');
		$f=$protocolo->ObenerNombresDeChecked($items,$datos['condicion_suelo']);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,110,$ymin,$filas,$xcuadro,'75','Condición del suelo',$f);
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'76','Indicar otra condición del suelo a considerar (de ser el caso)',$datos['condicion_suelo_otro'],true);

		//********************************************************** 77 ****************************************************************
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();

		$items = $ce->listarElementosCatalogo($conexion,'P1C25');
		$f=$protocolo->ObenerNombresDeChecked($items,$datos['condicion_ambiental']);


		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,110,$ymin,$filas,$xcuadro,'77','Condiciones ambienales',$f);
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'78','Indicar otra condición ambiental a considerar (de ser el caso)',$datos['condicion_ambiental_otro'],true);

		//********************************************************** 79 ****************************************************************
		$doc->Cell($xfull,$ymin,'Método, momento y frecuencia de evaluación',1,1,'L',false,0,0,true,'T','T');

		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();

		$items = $ce->listarElementosCatalogo($conexion,'P1C32');
		$f='';
		foreach ($items as $key=>$item){
			if(strtoupper($item['codigo']) == strtoupper($datos['muestreo_unidad'])){
				$f=$item['nombre'];
				break;
			}
		}

		if($datos['muestreo_unidad']=="UMC_OTRO"){
			$f=$f.': '.$datos['muestreo_unidad_otro'];
		}
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,86,$ymin,$filas,$xcuadro,'79','Unidad(es) de muestreo considerada(s)',$f);
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,76,$ymin,$filas,$xcuadro,'80','No. de unidades de muestreo por planta',$datos['muestreo_planta']);

		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'81','No. de unidades de muestreo por unidad experimental',$datos['muestreo_experimento'],true);

		//********************************************************** 82 ****************************************************************
		$doc->Cell($xfull,$ymin,'Evaluación de la(s) plaga(s)',1,1,'L',false,0,0,true,'T','T');
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,100,$ymin,$filas,$xcuadro,'82','Número de evaluaciones e intervalo de las mismas, expresado en días',sizeof($evaluacionesPlagas).' evaluaciones');
		$paso=$xfull-$xactual+$mx;

		$f=$datos['plaga_eval_escala']=='t'?'SI':'NO';
		if($datos['plaga_eval_escala_ref']!=null && $datos['plaga_eval_escala_ref']!='0')
			$f=$f.': '.$datos['plaga_eval_escala_ref'];
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'83','Escala de evaluación(en caso de utilizar, la misma debe incluirse en anexos)',$f,true);
		//****************

		$filas=1+sizeof($evaluacionesPlagas);
		$xactual=$mx;
		$yactual=$doc->GetY();
		$yantes=$yactual;

		$doc->SetAbsXY($xactual,$yactual);
		$doc->Cell(70,$ymin,'EVALUACIÓN',1,0,'L',false,0,0,true,'T','T');
		$doc->Cell(50,$ymin,'INTERVALO',1,0,'L',false,0,0,true,'T','T');
		$doc->Cell($xfull-70-50,$ymin,'OBSERVACIONES',1,1,'L',false,0,0,true,'T','T');

		foreach ($evaluacionesPlagas as $key=>$item){
			$doc->Cell(70,$ymin,$item['nombre'],1,0,'L',false,0,0,true,'T','T');
			$doc->Cell(50,$ymin,$item['intervalo'],1,0,'L',false,0,0,true,'T','T');
			$doc->Cell($xfull-70-50,$ymin, $ce->chequearStringNulo($item['observacion']),1,1,'L',false,0,0,true,'T','T');
		}
		$ybajo=$doc->GetY();
		$doc->SetAbsXY($xactual,$yantes);
		$doc->Cell($xfull,$ybajo-$yantes,'',1,1,'L',false,0,0,true,'T','T');

		//****************************************** 84 ************************************
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,100,$ymin,$filas,$xcuadro,'84','Descripción de la escala en caso de utilizarla(indicar la referencia bibliografica)',$datos['plaga_eval_escala_diseno']);
		$items = $ce->listarElementosCatalogo($conexion,'P1C27');
		$f='';
		foreach ($items as $key=>$item){
			if(strtoupper($item['codigo']) == strtoupper($datos['plaga_eval_variable'])){
				$f=$item['nombre'];
				break;
			}
		}
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,80,$ymin,$filas,$xcuadro,'85.1','Variables a evaluar',$f);
		$items = $ce->listarElementosCatalogo($conexion,'P1C28');
		$f='';
		foreach ($items as $key=>$item){
			if(strtoupper($item['codigo']) == strtoupper($datos['plaga_eval_eficacia'])){
				$f=$item['nombre'];
				break;
			}
		}
		if(strtoupper($datos['plaga_eval_eficacia'])=='VEE_OTRO')
			$f=$f.': '.$datos['plaga_eval_eficacia_otro'];

		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'85.2','Eficacia',$f,true);

		//********************************************************** Punto 86 ****************************************************************
		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();

		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'86','Otra información no considerada en esta sección',$datos['plaga_eval_info'],true);

		//********************************************************** Punto 87 ****************************************************************
		$items = $ce->listarElementosCatalogo($conexion,'P1C29');
		$filas=sizeof($items);
		$xactual=$mx;
		$yactual=$doc->GetY();
		$paso=$xfull-$xactual+$mx;
		$hoja->escribirPdfCuadroMuliple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'87','Información y evaluaciones adicionales que se remitirá en el informe final',$items,'nombre',true);

		//********************************************************** ANEXOS ****************************************************************

		$filas=1;
		$xactual=$mx;
		$yactual=$doc->GetY();

		$paso=$xfull-$xactual+$mx;
		$filas=1+sizeof($arhivosProtocolo);

		$hoja->escribirPdfCuadroSimple($doc,$xactual,$yactual,$paso,$ymin,$filas,$xcuadro,'88','Documentos adjuntos (Escala de evaluación, factura(s), ficha técnica(s), permiso de importacion de la muestra, respaldos bibliográficos','.',true);
		//****************

		$xactual=$mx;

		$yactual=$yactual+$ymin;

		$doc->SetAbsXY($xactual,$yactual);
		$doc->Cell(70,$ymin,'TIPO',1,0,'L',false,0,0,true,'T','T');
		$doc->Cell($xfull-70,$ymin,'REFERENCIA',1,1,'L',false,0,0,true,'T','T');

		foreach ($arhivosProtocolo as $key=>$item){
			$doc->Cell(70,$ymin,$item['nombre'],1,0,'L',false,0,0,true,'T','T');
			$doc->Cell($xfull-70,$ymin,$item['referencia'],1,1,'L',false,0,0,true,'T','T');
		}


		$y=$doc->GetY();
		$y=$y+10;

		$xm=$xfull/3;

		$doc->SetAbsXY($xm,$y);

		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,'<i><u>Documento generado por el sistema GUIA</u></i>',0,1,false,true,'C');
		$firmante=$operador['nombre_representante'].' '.$operador['apellido_representante'];
		$doc->SetFont('times', '', 9);
		$doc->Cell($xfull,5,strtoupper($firmante),0,1,'C');



		$paths=$ce->obtenerRutaAnexos($conexion,'ensayoEficacia');
		$doc->Output($paths['rutaFisica'].'/'.$fileName, 'F');

		unset($hoja);
		$doc->_destroy(true);
		unset($doc);

		ob_end_clean();

		$mensaje['datos'] = $paths['ruta'].'/'.$fileName;
		$mensaje['mensaje'] = 'Archivo generado';
		$mensaje['estado'] = 'exito';

		return $mensaje;

	}

	public function generarInforme($conexion,$id_documento,$esDocumentoLegal='SI'){
		$mensaje=array();
		$mensaje['mensaje'] = 'Error generando documento';
		$mensaje['estado'] = 'NO';
		
		$esBorrador=true;

		if($esDocumentoLegal==='SI'){
			$esBorrador=false;
		}

		$ce = new ControladorEnsayoEficacia();
		$cr = new ControladorRegistroOperador();
		$cc = new ControladorCatalogos();

		//recupera el protocolo
		if($id_documento==null || $id_documento=='_nuevo'){
			try{
				$conexion->desconectar();
			}catch(Exception $e){}
			return $mensaje;
		}
		
		//obtengo el informe
		$informe=$ce->obtenerInformeFinalEnsayo($conexion,$id_documento);
		//obtengo el protocolo en base el informe
		$datos=$ce->obtenerProtocoloDesdeInforme($conexion,$id_documento);
		$idProtocolo=$datos['id_protocolo'];
		$identificador=$datos['identificador'];		

		$tituloPrevio=$ce->generarTituloDelEnsayo($conexion, $idProtocolo,true);

		$res = $cr->buscarOperador($conexion, $identificador);
		$operador = pg_fetch_assoc($res);

		$fileName="IF_".$identificador."_".$id_documento.'.pdf';


		$registroPlaguicida=array();
		if($datos['motivo']!="MOT_REG")
			$registroPlaguicida=$ce->obtenerProductoRegistrado($conexion, $datos['plaguicida_registro']);
		
		$plagasDelProtocolo=$ce->obtenerPlagasProtocolo($conexion,$idProtocolo);

		$tecnicosReconocidos=$ce->obtenerTecnicosReconocidos($conexion);
		$ciTecnicosReconocidos=array();
		foreach ($tecnicosReconocidos as $key=>$item){
			$a=array();
			$a['value']=$item['identificador'];
			$a['label']='('.$item['identificador'].')'.$item['nombres'];
			$ciTecnicosReconocidos[]=$a;
		}
		
		$cultivosNombres = $ce->obtenerProductosXSubTipo($conexion,'CULTIVOS');		//615);

		$items=$cc->listarLocalizacion($conexion,'PROVINCIAS');
		$catalogoProvincias=array();
		while ($fila = pg_fetch_assoc($items)){
			$catalogoProvincias[] = array('codigo'=>$fila['id_localizacion'],'nombre'=>$fila['nombre']);
		}

		$items=$ce->obtenerSubTiposXcodigo($conexion,'RIA-%');
		$catalogoSubTipos=array();
		foreach ($items as $item){
			$subTipo=array();
			$subTipo['id_subtipo_producto']=$item['id_subtipo_producto'];
			$subTipo['codigo']=$item['codificacion_subtipo_producto'];
			$subTipo['nombre']=$item['nombre'];
			$catalogoSubTipos[]=$subTipo;
		}

		
		$formulaciones=$ce->obtenerFormulaciones($conexion,'SI');

		$margen_superior=20;
		$margen_inferior=15;
		$margen_izquierdo=20;
		$margen_derecho=17;

		
		$doc=new PdfLibre('P','mm','A4',true,'UTF-8');

		if($esBorrador)
			$doc->PonerBorrador(true);
		else
			$doc->PonerBorrador(false);

		$doc->SetLineWidth(0.1);
		$doc->AddPage();
		$doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
		$doc->SetAutoPageBreak(TRUE, $margen_inferior);
		$doc->setImageScale(PDF_IMAGE_SCALE_RATIO);

		$doc->SetFont('times', '', 9);

		$hoja=new Hoja();
		$hoja->MargenSuperior($margen_superior);
		$hoja->MargenInferior($margen_inferior);
		$hoja->MargenIzquierdo($margen_izquierdo);

		$protocolo=new Protocolo($datos);

		$xfull=160;

		$mx=$margen_izquierdo;
		$my=$margen_superior;
	
		$f1=$my+15;


		//****************************** INICIA *************************************
		$doc->SetTextColor();
		$doc->SetAbsXY($mx,$my+5);
		$doc->SetFont('times', 'B', 14);
		$doc->Cell($xfull,5,strtoupper( "Informe final resumen"),0,0,'C',false,0,0,true,'T','C');
		$doc->SetFont('times', 'B', 12);
		if($informe['fecha_solicitud']==null)
			$date=new DateTime();
		else{
			$date=new DateTime($informe['fecha_solicitud']);
			if($date==null){
				$date=new DateTime();
			}
		}
		

		$doc->SetAbsXY($mx,$f1);
		$doc->Cell(30,4,'Fecha de solicitud:',0,0,'L',false,0,0,true,'T','B');
		$date=$date->format('Y-m-d');
		
		$doc->SetFont('times', '', 12);
		$doc->SetAbsXY($mx+35,$f1);
		$doc->Cell(30,4,$date,0,0,'L',false,0,0,true,'T','B');

		//Fecha de aprobación
		

		if(!$esBorrador)
		{			
			if($informe['fecha_aprobacion']==null)
				$date=new DateTime();
			else{
				$date=new DateTime($informe['fecha_aprobacion']);
				if($date==null){
					$date=new DateTime();
				}
			}
			$date=$date->format('Y-m-d');

			$doc->SetFont('times', 'B', 12);
			$doc->SetAbsXY($mx+100,$f1);
			$doc->Cell(30,4,'Fecha de aprobación:',0,0,'L',false,0,0,true,'T','B');
			$doc->SetFont('times', '', 12);
			$doc->SetAbsXY($mx+140,$f1);
			$doc->Cell(30,4,$date,0,0,'L',false,0,0,true,'T','B');
		}

		$f1=$f1+11;
		$doc->SetFont('times', 'B', 12);
		$doc->SetAbsXY($mx+50,$f1);
		$doc->Cell(30,4,'SOLICITUD N°:',0,0,'L',false,0,0,true,'T','B');
		$doc->SetFont('times', '', 12);
		$doc->SetAbsXY($mx+85,$f1);
		$numeroInforme='';
		if($informe['id_expediente']==null)
			$numeroInforme=$informe['id_informe'];
		else
			$numeroInforme=$informe['id_expediente'];
			
		$doc->Cell(30,4,$numeroInforme,0,0,'L',false,0,0,true,'T','B');

		$doc->Ln(2);

		
		//**********************  Titulo ************************************


		$doc->Ln(4);
		$doc->writeHTML($tituloPrevio, true, 0, true, true);

		//******************************************* 2018 ****************************************************
		$doc->SetFont('times', '', 10);

		$doc->ln(2);
		$html="<ol>";
		$html=$html."<li value='1'>";
		$html=$html."Datos del solicitante<br/>";
		$html=$html.$datos['identificador']." ".$operador['razon_social'].", Dirección: ".$operador['direccion'].", Provincia: ".$operador['provincia'].", Cantón: ".$operador['canton'].", telefono ".$operador['telefono_uno'].", correo ".$operador['correo'];
		$html=$html."<br/></li>";


		//******************************************* 2018 ****************************************************


		$objetivos = $ce->listarElementosCatalogo($conexion,'P1C1');
		
		$xactual=$mx;
		
		$f='';
		$normativaLista = $ce->listarElementosCatalogo($conexion,'P1C30');
		foreach ($normativaLista as $key=>$item){
			if(strtoupper($item['codigo']) == strtoupper($datos['normativa'])){
				$f=$item['nombre'];
				break;
			}
		}
		$paso=$xfull-$xactual+$mx;

		$html=$html."<li value='2'>";
		$html=$html."Normativa aplicada<br/>";
		$html=$html.$f;
		$html=$html."<br/></li>";

		//********************************** 16 ******************************************************

		
		$xactual=$mx;
		
		$f='';
		$items = $ce->listarElementosCatalogo($conexion,'P1C2');
		foreach ($items as $key=>$item){
			if(strtoupper($item['codigo']) == strtoupper($datos['motivo'])){
				$f=$item['nombre'];
				break;
			}
		}
		$paso=$xfull-$xactual+$mx;

		$html=$html."<li value='3'>";
		$html=$html."Motivo del informe final<br/>";
		$html=$html.$f;
		$html=$html."<br/></li>";

		//********************************** 37 ******************************************************
		$xactual=$mx;
		
		$f=array();
		$iaNombre='';
		if($datos['motivo']=="MOT_REG"){
			$ias=$ce->obtenerIaDelProtocolo($conexion,$idProtocolo);
			

			foreach ($ias as $key=>$item){
				$f[]=array('grupo_quimico'=>$item['grupo_quimico']);
				$iaNombre=$iaNombre.' + '.$item['ingrediente_activo'].' '.$item['concentracion'].' '.strtolower($item['codigo']);
			}
			if(strlen($iaNombre)>2)
				$iaNombre=substr($iaNombre,3);
			$fp='';
			foreach ($formulaciones as $key=>$item){
				if(strtoupper($item['id_formulacion']) == strtoupper($datos['plaguicida_formulacion'])){
					$fp=$item['sigla'];
					break;
				}
			}
			if(strlen( $iaNombre)>0)
				$iaNombre=$iaNombre.', '.$fp;
		}
		else{
			$ias=$registroPlaguicida['composicion'];
			
			$f=$ias;
			$iaNombre=$protocolo->ObtenerComposicion($ias);
			if(strlen($iaNombre)>0)
				$iaNombre=$iaNombre.', '.$protocolo->ObtenerCodigoFormulacion($formulaciones,$registroPlaguicida['producto'][0]['id_formulacion']);


		}

		$html=$html."<li value='4'>";
		$html=$html."Nombre del producto<br/>";
		$html=$html.$datos['plaguicida_nombre'];
		$html=$html."<br/></li>";

		$html=$html."<li value='5'>";
		$html=$html."Tipo de producto<br/>";
		$html=$html.$datos['plaguicida_tipo'];
		$html=$html."<br/></li>";

		$html=$html."<li value='6'>";
		$html=$html."Características del producto<br/>";
		$html=$html.$informe['caracteristica'];
		$html=$html."<br/></li>";

		$html=$html."<li value='7'>";
		$html=$html."Objetivo del ensayo<br/>";
		$objetivos = $ce->listarElementosCatalogo($conexion,'P1C1');

		foreach ($objetivos as $key=>$item){
			$html=$html.$item['nombre']."<br/>";
		}

		$html=$html."<br/></li>";


		//********************************** 19 ******************************************************
	
		$xactual=$mx;
		
		$nomCien='';
		$nomComun='';
		foreach ($cultivosNombres as $key=>$item){
			if(strtoupper($item['id_producto']) == strtoupper($datos['cultivo'])){
				$nomCien=$item['nombre_cientifico'];
				$nomComun=$item['nombre_comun'];
				break;
			}
		}
		$f='';
		foreach ($catalogoSubTipos as $key=>$item){
			if(strtoupper($item['codigo']) == strtoupper($datos['uso'])){
				$f=$item['nombre'];
				break;
			}
		}

		$html=$html."<li value='8'>";
		$html=$html."Cultivo<br/>";
		$html=$html.$nomComun."(<i>".$nomCien."</i>)";
		$html=$html."<br/></li>";

		$html=$html."<li value='9'>";
		$html=$html."Plaga<br/>";
		if(count($plagasDelProtocolo)==0){
			$html=$html."N/A";
		}
		else{
			foreach ($plagasDelProtocolo as $key=>$item){
				$nombreComun=$item['nombre2'];
				if(($datos['uso']=='RIA-F') && ($datos['complejo_fungico']=='t'))
					$nombreComun=$item['nombre_fungico'];

				$html=$html.$nombreComun.'(<i>'.$item['nombre']."</i>)<br/>";
			}
		}
		$html=$html."<br/></li>";


		$html=$html."<li value='10'>";
		$html=$html."Provincia<br/>";
		$html=$html.$informe['provincia'];
		$html=$html."<br/></li>";


		$html=$html."<li value='11'>";
		$html=$html."Cantón<br/>";
		$html=$html.$informe['canton'];
		$html=$html."<br/></li>";


		$html=$html."<li value='12'>";
		$html=$html."Ámbito de aplicación<br/>";
		$html=$html.$informe['ambito'];
		$html=$html."<br/></li>";


		$html=$html."<li value='13'>";
		$html=$html."Efectos sobre plagas y cultivos<br/>";
		$html=$html.$informe['efecto_plagas'];
		$html=$html."<br/></li>";


		$html=$html."<li value='14'>";
		$html=$html."Condiciones en que el producto puede ser utilizado<br/>";
		$html=$html.$informe['condiciones'];
		$html=$html."<br/></li>";


		$html=$html."<li value='15'>";
		$html=$html."Métodos de aplicación<br/>";
		$html=$html.$informe['metodo_aplicacion'];
		$html=$html."<br/></li>";


		$html=$html."<li value='16'>";
		$html=$html."Instrucciones de uso<br/>";
		$html=$html.$informe['instrucciones'];
		$html=$html."<br/></li>";


		$html=$html."<li value='17'>";
		$html=$html."Número y frecuencia de aplicaciones<br/>";
		$html=$html.$informe['numero_aplicacion'];
		$html=$html."<br/></li>";

		$html=$html."<li value='18'>";
		$html=$html."Cuadro(s) de cálculo de la eficacia<br/>";
		$html=$html.$informe['eficacia'];
		$html=$html."<br/></li>";
				
		
		$respuesta=$ce->obtenerMatrizEficaciaEvaluacion($conexion,$datos['id_protocolo'],$datos['plaga_eval_eficacia'],$informe['id_informe']);		
		$str='';
		$hoja->escribirTablaStandar($str,'','','', array(array('', $respuesta['items'])),$respuesta['encabezado'],false);
		$html=$html.$str;

		$str='';
		$encabezadoCalculo=$respuesta['encabezado'];
		$encabezadoCalculo[]="Promedio";
		$hoja->escribirTablaStandar($str,'','','', array(array('', $respuesta['calculos'])),$encabezadoCalculo,false);
		$html=$html.$str;

		$html=$html."<li value='19'>";
		$html=$html."Dosis aprobada del producto<br/>";
		$html=$html.$informe['dosis'].' '.$informe['dosis_unidad'];
		$html=$html."<br/></li>";

		$html=$html."<li value='20'>";
		$html=$html."Gasto de agua<br/>";
		$html=$html.$informe['gasto_agua'].' l/ha';
		$html=$html."<br/></li>";

		$html=$html."<li value='21'>";
		$html=$html."Fitotoxicidad<br/>";
		$html=$html.$informe['fitotoxicidad'];
		$html=$html."<br/></li>";

		$html=$html."<li value='22'>";
		$html=$html."Conclusiones<br/>";
		$html=$html.$informe['conclusiones'];
		$html=$html."<br/></li>";

		$html=$html."<li value='23'>";
		$html=$html."Recomendaciones<br/>";
		$html=$html.$informe['recomendaciones'];
		$html=$html."<br/></li>";

		$html=$html."</ol>";
		$doc->writeHTML($html, true,false, true, true);


		$y=$doc->GetY();
		$y=$y+10;

		$xm=$xfull/3;

		$doc->SetAbsXY($xm,$y);

		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,'<i><u>Documento generado por el sistema GUIA</u></i>',0,1,false,true,'C');
		$firmante=$operador['nombre_representante'].' '.$operador['apellido_representante'];
		$doc->SetFont('times', '', 9);
		$y=$doc->GetY();
		$y=$y+5;
		$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,'<i>'.strtoupper($firmante).'</i>',0,1,false,true,'C');
		

		$paths=$ce->obtenerRutaAnexos($conexion,'ensayoEficacia');
		$doc->Output($paths['rutaFisica'].'/'.$fileName, 'F');

		$mensaje['datos'] = $paths['ruta'].'/'.$fileName;
		$mensaje['mensaje'] = 'Archivo generado';
		$mensaje['estado'] = 'exito';

		
		return $mensaje;
	}

}


?>