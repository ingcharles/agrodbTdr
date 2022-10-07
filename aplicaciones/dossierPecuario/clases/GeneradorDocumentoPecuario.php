<?php
session_start();

	
	require_once '../../clases/ControladorRegistroOperador.php';
	
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorAuditoria.php';

	require_once '../../clases/ControladorEnsayoEficacia.php';
	require_once '../../clases/ControladorDossierPecuario.php';

	require_once '../ensayoEficacia/clases/Hoja.php';
	require_once '../ensayoEficacia/clases/PdfStandar.php';
	require_once '../ensayoEficacia/clases/PdfCertificado.php';

	require_once '../ensayoEficacia/clases/Conversiones.php';

	class GeneradorDocumentoPecuario{

		public function generarDossier($conexion,$id_solicitud,$esBorrador=false){
			ob_start();
			$mensaje=array();
			$mensaje['mensaje'] = 'Error generando documento';
			$mensaje['estado'] = 'NO';


			$identificador= $_SESSION['usuario'];


			$ce = new ControladorEnsayoEficacia();
			$cr = new ControladorRegistroOperador();
			$cc = new ControladorCatalogos();
			$cp=new ControladorDossierPecuario();

			$subtipoProductos=$ce->obtenerSubTiposProductos ($conexion, 'IAV','TIPO_VETERINARIO');
			$clasificaciones=$cp->obtenerClasificacionesDeSubtipos ($conexion);
			$puntoClasificacion="";
			$datos=array();
			$fabricantes=array();

			$codigoSubTipo='';
			$nombreSubTipo='';

			$anexos=array();

			if($id_solicitud!=null && $id_solicitud!='_nuevo'){

				$datos=$cp->obtenerSolicitud($conexion, $id_solicitud);
				$identificador=$datos['identificador'];						//El duenio del documento

				//construye punto 2
				foreach($subtipoProductos as $key=>$valor){
					if($datos['id_subtipo_producto']==$valor["id_subtipo_producto"]){
						$codigoSubTipo=$valor["codificacion_subtipo_producto"];
						$nombreSubTipo=$valor["nombre"];
						break;
					}
				}
				foreach($clasificaciones as $key=>$valor){
					if($datos['id_clasificacion_subtipo']==$valor["id_clasificacion_subtipo"]){
						$puntoClasificacion=$valor["nombre"];
						break;
					}
				}

				$fabricantes=$cp->obtenerFabricantesDossier($conexion,$id_solicitud);

				$anexos=$cp->listarArchivosAnexos($conexion,$id_solicitud);
			}
			$res = $cr->buscarOperador($conexion, $identificador);
			$operador = pg_fetch_assoc($res);

			$fileName='DP_'.$identificador."_".$id_solicitud.'.pdf';

			//************************************************** INICIO ***********************************************************

			$margen_superior=40;
			$margen_inferior=20;
			$margen_izquierdo=20;
			$margen_derecho=17;

			$x=$margen_izquierdo;
			$y=$margen_superior;

			
			$doc=new PdfStandar('P','mm','A4',true,'UTF-8');

			if($esBorrador)
				$doc->PonerBorrador(true);
			else
				$doc->PonerBorrador(false);

			$doc->SetLineWidth(0.1);

			$doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho,true);

			$doc->SetHeaderMargin(0);
			$doc->SetFooterMargin(0);


			$doc->SetAutoPageBreak(TRUE, $margen_inferior);
			$doc->setImageScale(PDF_IMAGE_SCALE_RATIO);

			$doc->AddPage();

			$doc->SetFont('times', '', 9);

			$xfull=$doc->getPageWidth()-$margen_izquierdo-$margen_derecho;

			$hoja=new Hoja();

			//****************************** INICIA *************************************
			$doc->SetTextColor();
			$doc->SetFont('times', 'B', 12);
			$doc->SetAbsXY($x,$y);
			$doc->Cell($xfull,12,"SOLICITUD DE REGISTRO DE PRODUCTOS VETERINARIOS",0,1,'C',false,0,0,true,'C','C');
			$doc->Cell($xfull,15,$nombreSubTipo,0,1,'C',false,0,0,true,'C','C');

			$doc->SetFont('times', '', 10);


			$str="";
			$numero=1;

			$hoja->escribirSeccionSimple($str,$numero++,'NOMBRE COMERCIAL DEL PRODUCTO :', array($datos['nombre']));
			$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);

			$hoja->escribirSeccionSimple($str,$numero++,'CLASIFICACION :', array($puntoClasificacion));
			$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			$str="";
			$f=array('Nombre:'.$operador['razon_social'],"Dirección: ".$operador['direccion'].' '.$datos['direccion_referencia'].', con RUC '.$operador['identificador']);
			$f[]='Número de registro oficial: '.$datos['registro_oficial'];
			//*********
			$nombreSitio=$cp->obtenerOperadorConSitiosAreas($conexion, $identificador);
			$datosMismoFabricante=array();
			foreach($nombreSitio['sitios'] as $key=>$valor){
				if($datos['id_sitio']==$valor['id_sitio']){

					foreach($valor['areas'] as $k=>$v){
						if($datos['id_area']==$v['id_area']){
							$representanteTecnico=$v['representates_tecnicos'][0];
							$datosMismoFabricante=array('Responsable técnico: '.$representanteTecnico['nombre_representante'].' '.$representanteTecnico['identificacion_representante'],array('Profesión: '.$representanteTecnico['titulo_academico'],'Matrícula No.:'.$datos['tecnico_matricula']));
							$f[]=$datosMismoFabricante;
							break;
						}
					}
					break;
					
				}
			}
			$hoja->escribirSeccionNumerada($str,$numero++,'SOLICITANTE :', $f);
			$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			//*****************************************************************************************************

			$str='';
			$f=array();
			$f_contrato=array();
			$f_fabricante=array();
			$nombre='';

			foreach($fabricantes as $sitioElegido){
				$h=array();
				$nombre='Nombre: '.$sitioElegido['empresa'];
				if($sitioElegido['tipo']!='E'){
					$nombre=$nombre.' con RUC '.$sitioElegido['identificador'];
					$nombreSitio=$cp->obtenerOperadorConSitiosAreas($conexion, $sitioElegido['identificador']);
					foreach($nombreSitio['sitios'] as $key=>$valor){
						if($sitioElegido['id_sitio']==$valor['id_sitio']){
							$h[]='Domicilio: '.$valor['direccion'].', Parroquia '.$valor['parroquia'].', Canton '.$valor['canton'].', Provincia '.$valor['provincia'].'. Teléfono:'.$valor['telefono'];
							$h[]='Número de registro oficial: '.$sitioElegido['registro_oficial'];
							break;
						}
					}
				}
				else{
					$h[]='Domicilio: '.$sitioElegido['direccion'];
					$h[]='Número de registro oficial: '.$sitioElegido['registro_oficial'];
				}
				$h[]=array('Responsable técnico: '.$sitioElegido['tecnico_nombre'].' '.$sitioElegido['ci_tecnico'],array('Profesión: '.$sitioElegido['tecnico_titulo'],'Matrícula No.:'.$sitioElegido['tecnico_registro']));
				$f[]=array($nombre,$h);
				//**********************************************************
				if($sitioElegido['tipo']!='C'){
					$f_contrato[]=array($nombre,$h);
				}
				else{
					$f_fabricante[]=array($nombre,$h);
				}
			}
			if($codigoSubTipo!='RIP-SP'){
				$hoja->escribirSeccionNumerada($str,$numero++,'ESTABLECIMIENTO ELABORADOR : ', $f);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}
			else{
				$hoja->escribirSeccionNumerada($str,$numero++,'ESTABLECIMIENTO FABRICANTE : ', $f_fabricante);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
				$str="";
				$hoja->escribirSeccionNumerada($str,$numero++,'ESTABLECIMIENTO ELABORADOR POR CONTRATO : ', $f_contrato);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}
			$str='';
			if($codigoSubTipo=='RIP-BIO'){		//Biologicos
				$hoja->escribirSeccionSimple($str,$numero++,'DEFINICIÓN DE LA LÍNEA BIOLÓGICA :', array($datos['linea_biologica']));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}
			$str='';
			if($codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-COS' || $codigoSubTipo=='RIP-DAS' || $codigoSubTipo=='RIP-DIN'){		//Farmacologicos
				$h='';
				$formulaciones=$cp->obtenerFormulacionesPorArea ($conexion,'IAV');
				foreach($formulaciones as $key=>$value){
					if($value['id_formulacion']==$datos['id_formulacion']){
						$h=$value['formulacion'];
						break;
					}
				}
				$hoja->escribirSeccionSimple($str,$numero++,'FORMA FARMACEÚTICA :', array($h));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}

			//**************************************************************************************************************************
			$str='';
			$f_composicion=array();

			$f=array();
			$composiciones=$cp->obtenerComposicionProducto($conexion,$id_solicitud);
			$gruposIa=$ce->listarElementosCatalogo($conexion,'IA_GRUPO');
			foreach($gruposIa as $key=>$grupo){
				$h=array();
				foreach($composiciones as $clave=>$valor){
					if($valor['grupo']==$grupo['codigo']){
						$h[$valor['ingrediente_activo']]=$valor['cantidad'].' '.$valor['codigo'];
					}
				}
				$f[]=array($grupo['nombre'],$h);
			}
			$itemHeader=array('Cantidad');
			$f_composicion=$f;
			if($codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-BIO' || $codigoSubTipo=='RIP-AM' || $codigoSubTipo=='RIP-AC'|| $codigoSubTipo=='RIP-AD'|| $codigoSubTipo=='RIP-SP'||$codigoSubTipo=='RIP-COS' || $codigoSubTipo=='RIP-SNK' || $codigoSubTipo=='RIP-DAS'  || $codigoSubTipo=='RIP-DIN'){
				$hoja->escribirTabla($str,$numero++,1,'COMPOSICIÓN DEL PRODUCTO :', $f,$itemHeader);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}

			//*******************************************************************************************************
			$str='';
			if($codigoSubTipo!='RIP-KD'){
				$hoja->escribirSeccionSimple($str,$numero++,'MODO DE FABRICACIÓN DEL PRODUCTO: ', array($datos["modo_fabricacion"]));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}
			//******************

			$f=array();
			if($codigoSubTipo!='RIP-DIN')
				$f[]='Especificaciones : '.$datos["especificacion"];
			if($codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-BIO' ||$codigoSubTipo=='RIP-COS' || $codigoSubTipo=='RIP-DAS'){
				$f[]='pH : '.$datos["ph"];
				$f[]='Viscosidad : '.$datos["viscosidad"];
			}
			$presentacionesSolicitud=$cp->obtenerPresentacion($conexion,$id_solicitud);
			$h=array();
			foreach($presentacionesSolicitud as $key=>$presentacion){
				$h[]=$presentacion['presentacion'].' '.$presentacion['cantidad'].' '.$presentacion['unidad'].' : '.$presentacion['descripcion'];
			}
			$f[]=array('Presentaciones',$h);
			$f_presentaciones=$h;
			if($codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-BIO' ||$codigoSubTipo=='RIP-COS' ||$codigoSubTipo=='RIP-KD'|| $codigoSubTipo=='RIP-DAS'){
				$str="";
				$hoja->escribirSeccionNumerada($str,$numero++,'ESPECIFICACIONES DEL PRODUCTO: ', $f);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}

			//***********************************************************************************************
			$str='';
			if($codigoSubTipo=='RIP-KD'){
				$hoja->escribirTabla($str,$numero++,1,'COMPOSICIÓN DE LA FÓRMULA (ANTÍGENOS, ANTICUERPOS/ANTICUERPOS MONOCLONALES/POLICLONALES, U.I., OTROS) :', $f_composicion,$itemHeader);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
				$str='';
				$hoja->escribirSeccionSimple($str,$numero++,'MODO DE ELABORACIÓN : ', array($datos["modo_fabricacion"]));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
				$str='';
				$hoja->escribirSeccionSimple($str,$numero++,'CARACTERÍSTICAS DEL PRODUCTO :', $f_presentaciones);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
				$str='';
				$hoja->escribirSeccionSimple($str,$numero++,'CONTROLES SOBRE EL PRODUCTO DE DIAGNÓSTICO DE USO VETERINARIO :', array($datos["control_producto"]));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}


			//************************************************************************************************
			$str='';
			if($codigoSubTipo=='RIP-BIO'){


				$numUno=1;
				$str=	'<dl>';

				$str=	$str.'<dt>'.'<b>'.$numero.'. '.'CONTROLES SOBRE EL PRODUCTO VETERINARIO BIOLOGICO TERMINADO:</b>'.'</dt>';

				$str=	$str.'<dt>'.$numero.'.'.$numUno.'.'.'Control de calidad y pureza'.'</dt>';
				$numDos=1;
				$str=	$str.'<dt>'.$numero.'.'.$numUno.'.'.$numDos.'.'.'Pruebas biológicas'.'</dt>';
				$str=	$str.'<dt>'.$datos["prueba_biologica"].'</dt>';
				$str=	$str.'<dd>'.'Identidad: '.$datos["identidad"].'</dd>';
				$str=	$str.'<dd>'.'Esterilidad: '.$datos["esterilidad"].'</dd>';
				$str=	$str.'<dd>'.'Ausencia de agentes extraños: '.$datos["agentes_extra"].'</dd>';

				$str=	$str.'<dt>'.$numero.'.'.$numUno.'.'.++$numDos.'.'.'Pruebas físico-químicas'.'</dt>';
				$str=	$str.'<dd>'.'Humedad residual: '.$datos["humedad"].'</dd>';
				$str=	$str.'<dd>'.'Estabilización de la emulsión: '.$datos["estabilidad"].'</dd>';

				$str=	$str.'<dt></dt>';
				$str=	$str.'<dt>'.$numero.'.'.++$numUno.'.'.'Control de inocuidad'.'</dt>';
				$str=	$str.'<dt>'.$datos["inocuidad"].'</dt>';

				$str=	$str.'<dt></dt>';
				$str=	$str.'<dt>'.$numero.'.'.++$numUno.'.'.'Control de inactivación o modificación antigénica'.'</dt>';
				$str=	$str.'<dt>'.$datos["inactivacion"].'</dt>';

				$temp="N/A";
				foreach($anexos as $key=>$anexo){
					if($anexo['tipo']=='AP_CEIP'){
						$temp=$anexo['referencia'];
						break;
					}
				}
				$str=	$str.'<dt></dt>';
				$str=	$str.'<dt>'.$numero.'.'.++$numUno.'.'.'Control de eficacia inmunológica y potencia'.'</dt>';
				$str=	$str.'<dt>'.$temp.'</dt>';
				$str=	$str.'</dl>';
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
				$numero++;
			}



			//*******************************************
			$str='';
			$dosisProducto=$cp->obtenerDosis($conexion,$id_solicitud);
			if($codigoSubTipo=='RIP-BIO' || $codigoSubTipo=='RIP-KD'){

				$hoja->escribirSeccionSimple($str,$numero++,'ESPECIES ANIMALES A LAS QUE SE DESTINA :', array_column( $dosisProducto,'especie'));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
				$str='';
				$hoja->escribirParrafoLibre($str,'Bibliografía:', array_column( $dosisProducto,'referencia'));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
				if($codigoSubTipo=='RIP-KD'){
					$str='';
					$hoja->escribirParrafoLibre($str,'Detección de anticuerpos de vacunación o infección :', array($datos['deteccion_anticuerpos']));
					$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
					$str='';
					$hoja->escribirParrafoLibre($str,'Determinación de microorganismo (virus, bacterias, hongos), antígenos de campo o vacunal, recomendaciones para determinar serotipos :', array($datos['microorganismos']));
					$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
					$str='';
					$hoja->escribirParrafoLibre($str,'Resultados e interpretaciones :', array($datos['interpretacion']));
					$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
				}
			}

			//*************************************************************************************************************
			$str='';
			if($codigoSubTipo=='RIP-KD'){
				$hoja->escribirSeccionSimple($str,$numero++,'OTROS : ', array($datos["observaciones"]));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
				$str='';
				$hoja->escribirSeccionSimple($str,$numero++,'MODO DE USO DEL PRODUCTO :', array($datos["modo_uso"]));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}
			if($codigoSubTipo=='RIP-DIN'){
				$str='';
				$hoja->escribirSeccionSimple($str,$numero++,'MODO DE USO DEL PRODUCTO :', array($datos["modo_uso"]));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}
			//*******************************************

			$str='';
			$temp='';
			if($codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-AM' || $codigoSubTipo=='RIP-AC'|| $codigoSubTipo=='RIP-AD'|| $codigoSubTipo=='RIP-SP'||$codigoSubTipo=='RIP-COS' || $codigoSubTipo=='RIP-SNK' || $codigoSubTipo=='RIP-DAS' ){
				foreach($anexos as $key=>$anexo){
					if($anexo['tipo']=='AP_MC'){
						$temp=$anexo['referencia'];
						break;
					}
				}
				$hoja->escribirSeccionSimple($str,$numero++,'MÉTODOS DE CONTROL Y EVALUACIÓN :', array('Referencia: '.$temp));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}

			//************************************************************************
			$str='';
			if($codigoSubTipo=='RIP-AM' || $codigoSubTipo=='RIP-AC' || $codigoSubTipo=='RIP-AD' || $codigoSubTipo=='RIP-SP' || $codigoSubTipo=='RIP-SNK' ||$codigoSubTipo=='RIP-DIN'){
				$hoja->escribirSeccionSimple($str,$numero++,'PRESENTACIÓN COMERCIAL DEL PRODUCTO Y CARACTERÍSTICAS DE SU EMPAQUE :', $f_presentaciones);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}

			//*******************************************
			$str='';
			$f_dosis=array();
			$f=array();
			$h=array();
			foreach($dosisProducto as $key=>$valor){
				
				$temp='Via de administración: '.$valor['via'].' '.$valor['cantidad'].' '.$valor['unidad1'];
				$temp=$temp.' por '.$valor['peso'].' '.$valor['unidad2'];
				$temp=$temp.' cada '.$valor['duracion'].' '.$valor['unidad3'];
				$h[]=array($valor['especie'],$temp);
				$f_dosis[$valor['especie']]=$temp;
				
			}

			$f[]=array('Dosificación','',$f_dosis);

			$codificacionUsos=$ce->listarElementosCatalogoEx($conexion,'P_USOS');
			$res=$cc->listarUsosPorArea($conexion,'IAV');
			$catalogoUsos=array();
			while ($fila = pg_fetch_assoc($res)){
				$catalogoUsos[] = $fila;
			}

			$temp=explode(',', $datos["usos"]);
			$h='';
			if(sizeof( $temp)>0){
				foreach($temp as $key=>$valor){
					switch($valor){
						case 'F':
						case 'O':
							foreach($codificacionUsos as $k=>$item){
								if($item['nombre3']==$codigoSubTipo)
									$h=$h.', '.$item['nombre2'];
							}
							break;
						default:
							foreach($catalogoUsos as $k=>$item){
								if($item['id_uso']==$valor)
									$h=$h.', '.$item['nombre_uso'];
							}
					}

				}
			}
			$h=trim($h,',');
			$f['Usos']=$h;

			if($codigoSubTipo=='RIP-BIO'){
				$str="";
				$hoja->escribirParrafoNumerada($str,$numero++,'DOSIFICACIÓN Y USO :','', $f);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}
			else if($codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-AM' || $codigoSubTipo=='RIP-AC'  || $codigoSubTipo=='RIP-AD' || $codigoSubTipo=='RIP-COS'  || $codigoSubTipo=='RIP-SP' || $codigoSubTipo=='RIP-SNK' || $codigoSubTipo=='RIP-DAS'  || $codigoSubTipo=='RIP-DIN'){
				$str="";
				$hoja->escribirParrafoNumerada($str,$numero++,'INDICACIONES DE USO :',$h, '');
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}


			//*****************************************************
			$f=array();

			$h=array();
			foreach($dosisProducto as $key=>$valor){
				$temp='Via de administración '.$valor['via'].': '.$valor['detalle'];


				$f[$valor['especie']]=$temp;
				$h[]=$valor['referencia'];
			}
			if($codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-BIO'  || $codigoSubTipo=='RIP-COS'  || $codigoSubTipo=='RIP-DIN'){
				$str="";
				$hoja->escribirParrafoNumerada($str,$numero++,'VÍAS DE ADMINISTRACIÓN Y FORMA DE APLICACIÓN :','', $f);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
				$str='';
				$hoja->escribirParrafoLibre($str,'Bibliografía:', $h);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}

			//**********************************************************************************
			if($codigoSubTipo=='RIP-DAS'){
				$str="";
				$hoja->escribirParrafoNumerada($str,$numero++,'DOSIS, USOS, ACCIÓN :','', $f_dosis);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}

			//***********************************************************************
			if($codigoSubTipo=='RIP-DAS'){
				$str='';
				$hoja->escribirSeccionSimple($str,$numero++,'FORMA DE APLICACIÓN :', array($datos['modo_uso']));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);

			}

			//*****************************************************
			$str='';
			$catalogoUnidadesTiempo=$ce->listarElementosCatalogo($conexion,'P_TIEMPO');
			if($codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-BIO' || $codigoSubTipo=='RIP-COS' || $codigoSubTipo=='RIP-DAS'){
				$f=array();
				$f1='';
				$f2='';

				$h='';
				if($datos['requiere_preparacion']=='t'){
					foreach($catalogoUnidadesTiempo as $key=>$valor){
						if($valor['codigo']==$datos['preparacion_unidad']){
							$h=$valor['nombre'];
							break;
						}
					}
					$f1=$datos['preparacion_duracion'].' '.$h;
					$f2=$datos['preparacion_descripcion'];

				}
				else{
					$f1="No aplica";
					$f2="No aplica";
				}
				$str="";
				if($codigoSubTipo=='RIP-BIO'){

					$f['Duración máxima para su uso correcto']=$f1;
					$f['Preparación del producto para su uso correcto']=$f2;
					$hoja->escribirParrafoNumerada($str,$numero++,'PREPARACIÓN DEL PRODUCTO PARA SU USO CORRECTO :','', $f);
					$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
				}
				if($codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-DAS'){
					$hoja->escribirParrafoNumerada($str,$numero++,'PREPARACIÓN DEL PRODUCTO PARA SU USO CORRECTO :',$f2, '');
					$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
					$str="";
					$hoja->escribirParrafoNumerada($str,$numero++,'DURACIÓN MÁXIMA DESPUÉS DE SU RECONSTITUCIÓN O PREPARACIÓN :',$f1, '');
					$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);

				}
				if($codigoSubTipo=='RIP-COS'){
					$str='';
					$hoja->escribirParrafoNumerada($str,$numero++,'PREPARACIÓN DEL PRODUCTO PARA SU USO CORRECTO :',$f2, '');
					$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
				}
			}

			//*************************************************************
			$str="";
			if($codigoSubTipo=='RIP-FAR'){
				$hoja->escribirParrafoNumerada($str,$numero++,'DOSIFICACIÓN :','', $f_dosis);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}
			if($codigoSubTipo=='RIP-AM' || $codigoSubTipo=='RIP-AC'  || $codigoSubTipo=='RIP-AD'  || $codigoSubTipo=='RIP-SP'){
				$hoja->escribirParrafoNumerada($str,$numero++,'DOSIFICACIÓN O RECOMENDACIÓN DE USO :','', $f_dosis);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}
			if($codigoSubTipo=='RIP-COS'){
				$hoja->escribirParrafoNumerada($str,$numero++,'APLICACIÓN Y USO DEL PRODUCTO :','', $f_dosis);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}

			//***********************************************************************
			$str="";
			if($codigoSubTipo=='RIP-FAR'){
				$hoja->escribirSeccionSimple($str,$numero++,'FARMACOCINÉTICA DEL PRODUCTO – BIODISPONILIDAD (RESUMEN) :', array($datos['farmacocinetica']));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}
			//***********************************************************************
			$str="";
			if($codigoSubTipo=='RIP-FAR'){
				$hoja->escribirSeccionSimple($str,$numero++,'FARMACODINAMIA DEL PRODUCTO :', array($datos['farmacodinamica']));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);

			}
			//***********************************************************************
			$str="";
			if($codigoSubTipo=='RIP-AM'){
				$f=array();
				$f[]=array('Farmacocinética: ',$datos['farmacocinetica']);
				$f[]=array('Farmacodinámica: ',$datos['farmacodinamica']);
				$hoja->escribirParrafoNumerada($str,$numero++,'BIODISPONIBILIDAD DEL MEDICAMENTO :','', $f);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);

			}

			//*****************************************************
			$str="";
			$h='';
			$h1='';
			$f=array();
			if($codigoSubTipo=='RIP-BIO'){
				foreach($catalogoUnidadesTiempo as $key=>$valor){
					if($valor['codigo']==$datos['inmunidad_unidad']){
						$h=$valor['nombre'];
						break;
					}
				}
				foreach($catalogoUnidadesTiempo as $key=>$valor){
					if($valor['codigo']==$datos['inmunidad_min_unidad']){
						$h1=$valor['nombre'];
						break;
					}
				}
				$f['Duración mínima de la inmunidad']=$datos['inmunidad_min'].' '.$h1;
				$str="";
				$hoja->escribirParrafoNumerada($str,$numero++,'TIEMPO NECESARIO PARA CONFERIR INMUNIDAD Y DURACIÓN DE LA MISMA :',$datos['inmunidad'].' '.$h, $f);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
				$str='';
				$hoja->escribirParrafoLibre($str,'Bibliografía:', array($datos['inmunidad_ref']));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}

			//***********************************************************************
			$str='';
			if($codigoSubTipo=='RIP-DAS'){
				$hoja->escribirSeccionSimple($str,$numero++,'SITIO Y MECANISMO DE ACCIÓN :', array($datos['mecanismo_accion']));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);

			}
			//*******************************************************************************************
			$str="";
			if($codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-BIO' || $codigoSubTipo=='RIP-AM' || $codigoSubTipo=='RIP-AC' || $codigoSubTipo=='RIP-AD' || $codigoSubTipo=='RIP-SP' || $codigoSubTipo=='RIP-COS' || $codigoSubTipo=='RIP-DAS'){		//Farmacologicos
				$h='';
				$formulaciones=$cp->obtenerFormulacionesPorArea ($conexion,'IAV');
				foreach($formulaciones as $key=>$value){
					if($value['id_formulacion']==$datos['id_formulacion']){
						$h=$value['formulacion'];
						break;
					}
				}
				$hoja->escribirSeccionSimple($str,$numero++,'EFECTOS COLATERALES POSIBLE LOCALES O GENERALES IMCOMPATIBILIDADES Y ANTAGONISMOS:', array($datos['efectos_colaterales']));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
				$str='';
				$hoja->escribirParrafoLibre($str,'Bibliografía:', array($datos['efectos_colaterales_referencia']));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}

			//******************************************************************************************************************
			$str="";
			if($codigoSubTipo=='RIP-FAR'  || $codigoSubTipo=='RIP-AD' || $codigoSubTipo=='RIP-COS' || $codigoSubTipo=='RIP-DAS'){
				$hoja->escribirSeccionSimple($str,$numero++,'TOXICIDAD :', array($datos['sobredosis'],$datos['toxicidad']));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}
			//****************************
			$str='';
			if($codigoSubTipo=='RIP-BIO' || $codigoSubTipo=='RIP-KD'){
				$f=array();
				$hoja->escribirSeccionSimple($str,$numero++,'LIMITE MÁXIMO Y MÍNIMO DE TEMPERATURA PARA SU CONSERVACIÓN  :',array('Límite mínimo: '.$datos['almacenar_minimo'].' °C','Límite máximo: '.$datos['almacenar_maximo'].' °C'));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}
			//********************************************************************
			$str="";
			$h='';
			if($codigoSubTipo=='RIP-BIO'  || $codigoSubTipo=='RIP-KD'){
				foreach($catalogoUnidadesTiempo as $key=>$valor){
					if($valor['codigo']==$datos['validez_unidad']){
						$h=$valor['nombre'];
						break;
					}
				}
				$hoja->escribirParrafoNumerada($str,$numero++,'PERIODO DE VALIDEZ :',$datos['validez'].' '.$h, '');
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}

			//******************************************************************************************************************
			$str="";
			$h='';
			if($codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-AM' || $codigoSubTipo=='RIP-AC' || $codigoSubTipo=='RIP-AD'  || $codigoSubTipo=='RIP-SP'){

				$efectosSolicitud=$cp->obtenerEfectosNoDeseados($conexion,$id_solicitud);
				$f=array();
				$h=array();
				foreach($efectosSolicitud as $k=>$item){
					$h[]=array($item['nombre'],$item['descripcion'],$item['referencia']);

				}
				$f[]=array('',$h);
				$itemHeader=array('Efecto','Descripción','Referencia');
				$hoja->escribirTablaStandar($str,$numero++,1,'EFECTOS BIOLÓGICOS NO DESEADOS :', $f,$itemHeader);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);

			}

			//******************************************************************************************************************
			$str='';
			if($codigoSubTipo=='RIP-SP'){
				$hoja->escribirSeccionSimple($str,$numero++,'TOXICIDAD Y SOBREDOSIS EN LOS ANIMALES :', array($datos['sobredosis'],$datos['toxicidad']));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}
			//*******************************************
			$str="";
			if($codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-BIO' ||  $codigoSubTipo=='RIP-DIN'){
				$f=array();
				$periodosRetirosSolicitud=$cp->obtenerPeriodosDeRetiro($conexion,$id_solicitud);
				foreach($periodosRetirosSolicitud as $key=>$valor){
					$temp=$valor['tiempo'].' '.$valor['unidad'];

					$f[$valor['especie']]=$temp;
				}
				$hoja->escribirParrafoNumerada($str,$numero++,'TIEMPO DE RETIRO :','', $f);
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}

			//*******************************************
			$str="";
			if($codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-AD' || $codigoSubTipo=='RIP-AM' || $codigoSubTipo=='RIP-AD' || $codigoSubTipo=='RIP-DIN'){
				$hoja->escribirSeccionSimple($str,$numero++,'CONTROL SOBRE RESIDUOS DE MEDICAMENTOS :', array($datos['residuos']));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}

			//*******************************************
			$str="";
			if(!($codigoSubTipo=='RIP-COS' || $codigoSubTipo=='RIP-SNK' )){
				$hoja->escribirSeccionSimple($str,$numero++,'PRECAUCIONES GENERALES:', array($datos['precauciones']));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
				$str='';
				$hoja->escribirParrafoLibre($str,'Bibliografía:', array($datos['precauciones_ref']));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}
			//*******************************************
			$str="";
			if($codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-AD' || $codigoSubTipo=='RIP-AM' || $codigoSubTipo=='RIP-AC' || $codigoSubTipo=='RIP-SP' || $codigoSubTipo=='RIP-COS'  || $codigoSubTipo=='STPV_KID'){
				$hoja->escribirSeccionSimple($str,$numero++,'CAUSAS QUE PUEDAN HACER VARIAR LA CALIDAD DEL PRODUCTO :', array($datos['calidad']));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}

			//********************************************************************************************
			$str="";
			if($codigoSubTipo=='RIP-SNK'){
				$hoja->escribirSeccionSimple($str,$numero++,'PRECAUCIONES GENERALES Y CAUSAS QUE PUEDAN HACER VARIAR LA CALIDAD DEL PRODUCTO :', array($datos['precauciones'],$datos['calidad']));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}


			//*******************************************
			$str="";
			if( $codigoSubTipo=='RIP-FAR'  || $codigoSubTipo=='RIP-AC'  || $codigoSubTipo=='RIP-AD'  || $codigoSubTipo=='RIP-COS' || $codigoSubTipo=='RIP-SP' || $codigoSubTipo=='RIP-SNK' || $codigoSubTipo=='RIP-DAS' || $codigoSubTipo=='RIP-DIN'){
				$f1='Temperatura mínima de almacenamiento: '.$datos['almacenar_minimo'].' °C';
				$f2='Temperatura máxima de almacenamiento: '.$datos['almacenar_maximo'].' °C';
				$hoja->escribirSeccionSimple($str,$numero++,'CONSERVACIÓN DEL PRODUCTO :', array($datos['conservacion'],$f1,$f2));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}

			//*************************************************************************
			$str="";
			$h='';
			if($codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-AM' || $codigoSubTipo=='RIP-AC' || $codigoSubTipo=='RIP-AD'  || $codigoSubTipo=='RIP-COS' || $codigoSubTipo=='RIP-SP' || $codigoSubTipo=='RIP-SNK' || $codigoSubTipo=='RIP-DAS' || $codigoSubTipo=='RIP-DIN'){
				foreach($catalogoUnidadesTiempo as $key=>$valor){
					if($valor['codigo']==$datos['validez_unidad']){
						$h=$valor['nombre'];
						break;
					}
				}
				$hoja->escribirParrafoNumerada($str,$numero++,'PERIODO DE VALIDEZ :',$datos['validez'].' '.$h, '');
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}

			//*************************************************************************
			$str="";
			if($codigoSubTipo=='RIP-KD'){
				$hoja->escribirSeccionSimple($str,$numero++,'FORMA Y MÉTODO DE ELIMINACIÓN DE LOS ENVASES :', array($datos['eliminacion_envases']));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
				$str='';
				$hoja->escribirSeccionSimple($str,$numero++,'RIESGO PARA LA SALUD PÚBLICA Y EL AMBIENTE :', array($datos['riesgo']));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}

			//****************************************************************
			$str="";
			$temp='';
			foreach($anexos as $key=>$anexo){
				if($anexo['tipo']=='AP_RAI'){
					$temp=$anexo['referencia'];
					break;
				}
			}
			$hoja->escribirSeccionSimple($str,$numero++,'ROTULADO/ARTES FINALES E INSERTOS ADJUNTOS:', array('Referencia: '.$temp));
			$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);

			//****************************************************************
			$str="";
			if($codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-AM' || $codigoSubTipo=='RIP-AC' || $codigoSubTipo=='RIP-AD'  || $codigoSubTipo=='RIP-COS' || $codigoSubTipo=='RIP-SP' || $codigoSubTipo=='RIP-SNK' || $codigoSubTipo=='RIP-DAS'){
				$temp='';
				foreach($anexos as $key=>$anexo){
					if($anexo['tipo']=='AP_PETC'){
						$temp=$anexo['referencia'];
						break;
					}
				}
				$hoja->escribirSeccionSimple($str,$numero++,'PRUEBA DE EFICACIA / TRABAJOS CIENTÍFICOS Y MONOGRAFÍAS:', array('Referencia: '.$temp));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}
			$str='';
			if( $codigoSubTipo=='RIP-KD'){
				$temp='';
				foreach($anexos as $key=>$anexo){
					if($anexo['tipo']=='AP_PRVA'){
						$temp=$anexo['referencia'];
						break;
					}
				}
				$hoja->escribirSeccionSimple($str,$numero++,'PRUEBAS DE VALIDACIÓN DE LOS KITS O CERTIFICADOS DE ANÁLISIS DE CADA COMPONENTE UTILIZADO EN LA PRUEBA :', array('Referencia: '.$temp));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}
			$str='';
			if($codigoSubTipo=='RIP-BIO' || $codigoSubTipo=='RIP-KD'){
				$temp='';
				foreach($anexos as $key=>$anexo){
					if($anexo['tipo']=='AP_PETC'){
						$temp=$anexo['referencia'];
						break;
					}
				}
				$hoja->escribirSeccionSimple($str,$numero++,'TRABAJOS CIENTÍFICOS Y MONOGRAFÍAS :', array('Referencia: '.$temp));
				$doc->writeHTMLCell($xfull,5,$x,$doc->GetY(),$str,0,1);
			}
			//*******************************************

			$y=$doc->GetY();
			$y=$y+10;
			$doc->SetAbsXY($x,$y);
			$doc->Cell($xfull,5,'La presente tiene carácter de declaración jurada',0,0,'C');
			$y=10+$doc->GetY();
			$doc->SetAbsXY($x,$y);
			$doc->Cell($xfull,5,'Documento generado mediante sistema GUIA',0,0,'C');

			
			$y=20+$doc->GetY();
			$doc->SetAbsXY($x,$y);
			$doc->Cell($xfull,5,'El Solicitante',0,0,'C');
			$y=5+$doc->GetY();
			$doc->SetAbsXY($x,$y);
			
			$doc->writeHTMLCell($xfull,5,$x,$y,'<p style="text-align:center;text-decoration-line:overline">'.$operador['razon_social'].'</p>',0,1);

			$y=15+$doc->GetY();
			$doc->SetAbsXY($x,$y);
			$doc->Cell($xfull,5,'Representante Técnico',0,0,'C');
			$y=5+$doc->GetY();
			$doc->SetAbsXY($x,$y);
			$representanteTecnico=$cp->obtenerRepresentanteTecnico($conexion,$datos['id_area'],$datos['ci_representante_tecnico']);
			$doc->writeHTMLCell($xfull,5,$x,$y,'<p style="text-align:center;text-decoration-line:overline">'.$representanteTecnico['nombre_representante'].'</p>',0,1);
			



			//******************************* FIN DE LA EDICION ****************************************************************************************

			$paths=$ce->obtenerRutaAnexos($conexion,'dossierPecuario');
			$doc->Output($paths['rutaFisica'].'/'.$fileName, 'F');
			ob_end_clean();
			
			$ce->constrirRutas($mensaje,$paths,$fileName);

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

			$esModificacion=false;


			
			$ce = new ControladorEnsayoEficacia();
			$cr = new ControladorRegistroOperador();
			$cc = new ControladorCatalogos();
			$cp=new ControladorDossierPecuario();

			$subtipoProductos=$ce->obtenerSubTiposProductos ($conexion, 'IAV','TIPO_VETERINARIO');
			$clasificaciones=$cp->obtenerClasificacionesDeSubtipos ($conexion);
			$puntoClasificacion="";
			$datos=array();
			$operador=array();
			$fabricantes=array();


			$codigoSubTipo='';
			$nombreSubTipo='';



			if($id_solicitud!=null && $id_solicitud!='_nuevo'){

				$datos=$cp->obtenerSolicitud($conexion, $id_solicitud);
				$identificador=$datos['identificador'];						//El duenio del documento

				//busca los datos del operador
				$res = $cr->buscarOperador($conexion, $datos['identificador']);
				$operador = pg_fetch_assoc($res);
				//construye punto 2
				foreach($subtipoProductos as $key=>$valor){
					if($datos['id_subtipo_producto']==$valor["id_subtipo_producto"]){
						$codigoSubTipo=$valor["codificacion_subtipo_producto"];
						$nombreSubTipo=$valor["nombre"];
						break;
					}
				}
				foreach($clasificaciones as $key=>$valor){
					if($datos['id_clasificacion_subtipo']==$valor["id_clasificacion_subtipo"]){
						$puntoClasificacion=$valor["nombre"];
						break;
					}
				}

				$fabricantes=$cp->obtenerFabricantesDossier($conexion,$id_solicitud);


			}
			$res = $cr->buscarOperador($conexion, $identificador);
			$operador = pg_fetch_assoc($res);



			$fileName='CDP_'.$identificador."_".$id_solicitud.'.pdf';



			//************************************************** INICIO ***********************************************************

			$margen_superior=50;
			$margen_inferior=10;
			$margen_izquierdo=20;
			$margen_derecho=17;

			
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
			$doc->SetFont('times', 'B', 9);
		
			$doc->Cell($xfull,9,"CERTIFICADO DE REGISTRO DE PRODUCTO DE USO VETERINARIO",0,1,'C',false,0,0,true,'C','C');

			$doc->SetFont($tipoLetra, '', 8);


			$doc->Ln();
			$f='EN QUITO, A LOS ';
			$fecha=new DateTime();
			$f=$f.$fecha->format('j');
			$f=$f.' DÍAS DEL MES DE ';
			
			$index=intval($fecha->format('n'));
			$index--;
			$conversion=new Conversiones();
			if($index>=0){
				$f=$f.strtoupper($conversion->mes($index));
			}
			else{
				$f=$f.strtoupper($fecha->format('F'));
			}

			$f=$f.' DEL AÑO ';
			$f=$f.$fecha->format('Y');
			$f=$f.' , EN CUMPLIMIENTO A LO ESTABLECIDO EN LA DECISIÓN 483 DE LA COMUNIDAD ANDINA, PUBLICADA EN EL REGISTRO OFICIAL Nº 257 DEL 01 DE FEBRERO DEL AÑO 2001, LA AGENCIA DE REGULACIÓN Y CONTROL FITO Y ZOOSANITARIO – AGROCALIDAD, OTORGA EL PRESENTE CERTIFICADO DE REGISTRO AL PRODUCTO DE USO VETERINARIO QUE SE DETALLA A CONTINUACIÓN:';
			$textoHtml='<p style="border:1px solid black; padding:10px; text-align:justify; background-color:lightgray;">'.$f.'</p>';
			$doc->writeHTMLCell(0,5,$margen_izquierdo,$doc->GetY(),$textoHtml,0,1);

			$doc->Cell($xfull,5,"",0,1,'C',false,0,0,true,'C','C');


			$datos['id_certificado']=$ce->obtenerRegistro($conexion,'dossierPecuario',$codigoSubTipo);
			///******************************************************************


			$str='<table cellspacing="0" cellpadding="1" border="0">';
			$hh=array();
			$hh[]=array('NOMBRE : '=>'<b><span style="font-size:14px">'. strtoupper( $datos['nombre']).'</span></b>');
			$hh[]=array('NÚMERO DE REGISTRO : '=>'<b><span style="font-size:14px">'.$datos['id_certificado'].'</span></b>');
			$hh[]=array('TIPO DE PRODUCTO : '=>$datos['tipo_producto']);
			$hh[]=array('CLASIFICACIÓN : '=>$puntoClasificacion);

			$codificacionUsos=$ce->listarElementosCatalogoEx($conexion,'P_USOS');
			$res=$cc->listarUsosPorArea($conexion,'IAV');
			$catalogoUsos=array();
			while ($fila = pg_fetch_assoc($res)){
				$catalogoUsos[] = $fila;
			}

			$temp=explode(',', $datos["usos"]);
			$h='';
			if(sizeof( $temp)>0){
				foreach($temp as $key=>$valor){
					switch($valor){
						case 'F':
						case 'O':
							foreach($codificacionUsos as $k=>$item){
								if($item['nombre3']==$codigoSubTipo)
									$h=$h.', '.$item['nombre2'];
							}
							break;
						default:
							foreach($catalogoUsos as $k=>$item){
								if($item['id_uso']==$valor)
									$h=$h.', '.$item['nombre_uso'];
							}
					}

				}
			}
			$h=trim($h,',');

			$hh[]=array('INDICACIONES DE USO : '=>$h);

			$h='';
			$formulaciones=$cp->obtenerFormulacionesPorArea ($conexion,'IAV');
			foreach($formulaciones as $key=>$value){
				if($value['id_formulacion']==$datos['id_formulacion']){
					$h=$value['formulacion'];
					break;
				}
			}
			$hh[]=array('FORMA FARMACEÚTICA : '=>$h);

			$dosisProducto=$cp->obtenerDosis($conexion,$id_solicitud);

			$h='';
			$f=array();
			foreach($dosisProducto as $key=>$valor){
				if(!array_key_exists($valor['id_especie'],$f))
					$f[$valor['id_especie']]=$valor['especie'];
			}
			$h=implode(',',array_values($f));
			$hh[]=array('ESPECIE(S) DE DESTINO : '=>$h);

			$periodosRetirosSolicitud=$cp->obtenerPeriodosDeRetiro($conexion,$id_solicitud);
			$h='';
			$f=array();
			foreach($periodosRetirosSolicitud as $key=>$valor){
				$f[]=$valor['especie'].': '.$valor['consumible'].' '.$valor['tiempo'].' '.$valor['unidad'];

			}
			$h=implode(', ',$f);
			if($h!='')
				$hh[]=array('PERIODOS DE RETIRO : '=>$h);

			$items=$ce->obtenerItemDelCatalogo($conexion,'P_TIEMPO',$datos['validez_unidad']);

			$hh[]=array('PERIODO DE VIDA UTIL : '=>$datos['validez'].' '.$items['nombre']);

			$presentacionesSolicitud=$cp->obtenerPresentacion($conexion,$id_solicitud);
			$h='';
			foreach($presentacionesSolicitud as $key=>$presentacion){
				$h=$h.', '.$presentacion['presentacion'].' '.$presentacion['cantidad'].' '.$presentacion['unidad'];
			}
			$h=trim($h,',');
			$hh[]=array('PRESENTACIONES COMERCIALES  Y TIPO DE ENVASE : '=>$h);

			
			$items=$ce->obtenerItemDelCatalogoEx($conexion,'PC_DE_VE',$datos['declaracion_venta']);
			$hh[]=array('DECLARACIÓN DE VENTA : '=>$items['nombre']);


			$empresas=array();
			foreach($fabricantes as $key=>$valor){
				$empresas[]=$valor['empresa'].' - '.strtoupper($valor['pais']);

			}

			$etiqueta=true;
			foreach($empresas as $empresa){
				if($etiqueta){
					$hh[]=array('FABRICATE(S) : '=>$empresa);
					$etiqueta=false;
				}
				else{
					$hh[]=array(' '=>$empresa);
				}
			}

			$hh[]=array('TITULAR DE REGISTRO EN EL ECUADOR : '=>$operador['razon_social']);
			$fecha=new DateTime();



			$hh[]=array('FECHA DE INSCRIPCION DEL PRODUCTO : '=>$fecha->format("Y-m-d"));

			if($esModificacion){
				$hh[]=array('FECHA ÚLTIMA MODIFICACIÓN : '=>'');
			}

			$hoja->escribirItemsTablaTitulo($str,$hh);
			$str=$str.'</table>';


			$y=$doc->GetY();
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
			$y=$y+10;

			$paths=$ce->obtenerRutaAnexos($conexion,'dossierPecuario');
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
			$doc->write2DBarcode($rutaQR, 'QRCODE,H', 20, $y, 30, 30, $style, 'N');

			// area para validar firma
			$doc->Image('../ensayoEficacia/img/logo_agrocalidad.gif', 100, $y, 30, 30, 'GIF');
			$doc->setSignatureAppearance(100, $y, 30, 30);

			////////////////////////////////////////Hoja 2 con tabla y firma////////////////////////////////
			    $str='';
			    $doc->AddPage();
			    $f=array();
			    $composiciones=$cp->obtenerComposicionProducto($conexion,$id_solicitud);
			    $gruposIa=$ce->listarElementosCatalogo($conexion,'IA_GRUPO');
			    foreach($gruposIa as $key=>$grupo){
			        $h=array();
			        foreach($composiciones as $clave=>$valor){
			            if($valor['grupo']==$grupo['codigo']){
			                $h[$valor['ingrediente_activo']]=$valor['cantidad'].' '.$valor['codigo'];
			            }
			        }
			        $f[]=array($grupo['nombre'],$h);
			    }
			    $itemHeader=array('Cantidad');
			    $f_composicion=$f;
			    $unidadesMedida=$ce->obtenerUnidadesMedida($conexion,'DP_COMP');
			    $cadaUnidad='';
			    foreach($unidadesMedida as $key=>$valor){
			        if($valor['id_unidad_medida']==$datos['producto_unidad']){
			            $cadaUnidad=$valor['codigo'];
			            break;
			        }
			    }
			    $cada='Cada '.$datos['producto_cantidad'].' '. $cadaUnidad.' contine:';
			    //if($codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-BIO' || $codigoSubTipo=='RIP-AM' || $codigoSubTipo=='RIP-AC'|| $codigoSubTipo=='RIP-AD'|| $codigoSubTipo=='RIP-SP'||$codigoSubTipo=='RIP-COS' || $codigoSubTipo=='RIP-SNK' || $codigoSubTipo=='RIP-DAS'  || $codigoSubTipo=='RIP-DIN'){
			        $hoja->escribirTabla($str,$numero++,1,'COMPOSICIÓN DECLARADA, '.$cada, $f,$itemHeader);
			        
			    //}
			    $y=$doc->GetY();
			    $y=$y+10;
			    $doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);
			    
			    $str='';
			    $y=$doc->GetY();
			    $y=$y+10;
			    $doc->writeHTMLCell(0,5,$margen_izquierdo,$y,'<i><u>Documento firmado electrónicamente</u></i>',0,1,false,true,'C');
			    $doc->Cell($xfull,5,strtoupper($firmante),0,1,'C');
			    $doc->Cell($xfull,5,strtoupper( $firmanteCargo),0,1,'C');
			    $doc->Cell($xfull,5,"AGENCIA DE REGULACIÓN Y CONTROL FITO Y ZOOSANITARIO – AGROCALIDAD",0,1,'C');
			    
			    
			


			//******************************* FIN DE LA EDICION ****************************************************************************************

			$doc->Output($paths['rutaFisica'].'/'.$fileName, 'F');

			
			$ce->constrirRutas($mensaje,$paths,$fileName);

			
			ob_end_clean();

			$mensaje['mensaje'] = 'Archivo generado';
			$mensaje['estado'] = 'exito';
			$mensaje['id_certificado']=$datos['id_certificado'];

			return $mensaje;
		}

		public function generarPuntosMinimos($conexion,$id_solicitud,$firmante,$firmanteCargo,$noRegistro){
			ob_start();
			$ce = new ControladorEnsayoEficacia();
			$cr = new ControladorRegistroOperador();
			$cc = new ControladorCatalogos();
			$cp=new ControladorDossierPecuario();

			$subtipoProductos=$ce->obtenerSubTiposProductos ($conexion, 'IAV','TIPO_VETERINARIO');

			$identificador='';
			$datos=array();

			$codigoSubTipo='';

			$fabricantes=array();

			if($id_solicitud!=null && $id_solicitud!='_nuevo'){

				$datos=$cp->obtenerSolicitud($conexion, $id_solicitud);
				$identificador=$datos['identificador'];						//El duenio del documento

				//busca los datos del operador
				$res = $cr->buscarOperador($conexion, $datos['identificador']);
				$operador = pg_fetch_assoc($res);
				//construye punto 2
				foreach($subtipoProductos as $key=>$valor){
					if($datos['id_subtipo_producto']==$valor["id_subtipo_producto"]){
						$codigoSubTipo=$valor["codificacion_subtipo_producto"];

						break;
					}
				}

				$fabricantes=$cp->obtenerFabricantesDossier($conexion,$id_solicitud);

			}
			$res = $cr->buscarOperador($conexion, $identificador);
			$operador = pg_fetch_assoc($res);

			$fileName='DP_EPM_'.$identificador."_".$id_solicitud.'.pdf';

			//************************************************** INICIO ***********************************************************

			$margen_superior=50;
			$margen_inferior=10;
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

			$datos['id_certificado']=$noRegistro;
			///******************************************************************


			$str='<table cellspacing="0" cellpadding="1" border="0">';
			$hh=array();
			$hh[]=array('NOMBRE : '=>'<b><span style="font-size:14px">'.$datos['nombre'].'</span></b>');
			$hh[]=array('NÚMERO DE REGISTRO : '=>'<b><span style="font-size:14px">'.$datos['id_certificado'].'</span></b>');

			$hh[]=array(' '=>' ');

			$codificacionUsos=$ce->listarElementosCatalogoEx($conexion,'P_USOS');
			$res=$cc->listarUsosPorArea($conexion,'IAV');
			$catalogoUsos=array();
			while ($fila = pg_fetch_assoc($res)){
				$catalogoUsos[] = $fila;
			}

			$temp=explode(',', $datos["usos"]);
			$h='';
			if(sizeof( $temp)>0){
				foreach($temp as $key=>$valor){
					switch($valor){
						case 'F':
						case 'O':
							foreach($codificacionUsos as $k=>$item){
								if($item['nombre3']==$codigoSubTipo)
									$h=$h.', '.$item['nombre2'];
							}
							break;
						default:
							foreach($catalogoUsos as $k=>$item){
								if($item['id_uso']==$valor)
									$h=$h.', '.$item['nombre_uso'];
							}
					}

				}
			}
			$h=trim($h,',');

			$hh[]=array('INDICACIONES DE USO : '=>$h);

			$hoja->escribirItemsTablaTitulo($str,$hh);
			$str=$str.'</table>';

			$f=array();
			$composiciones=$cp->obtenerComposicionProducto($conexion,$id_solicitud);
			$gruposIa=$ce->listarElementosCatalogo($conexion,'IA_GRUPO');
			foreach($gruposIa as $key=>$grupo){
				if($grupo['codigo']=='IA_EXCO')
					continue;	//Excluye los Exipientes y coadyuvantes
				//Busca  solo los items del grupo
				$items = array_filter($composiciones, function ($var) use ($grupo) {
					return ($var['grupo'] == $grupo['codigo']);
				});
				//los ordena de mayor a menor
				$cantidades=array();
				foreach ($items as $clave => $fila) {
					$cantidades[$clave] = $fila['cantidad'];
				}
				array_multisort($cantidades, SORT_DESC, $items);
				$h=array();
				foreach($items as $clave=>$valor){
					if($valor['grupo']==$grupo['codigo']){
						if($grupo['codigo']=='IA_INGR')
							$h[$valor['ingrediente_activo']]='';	//Para ingredientes no muestra la cantidad
						else
							$h[$valor['ingrediente_activo']]=$valor['cantidad'].' '.$valor['codigo'];
					}
				}
				$f[]=array($grupo['nombre'],$h);
			}
			$itemHeader=array('Cantidad');

			if($codigoSubTipo=='RIP-FAR' || $codigoSubTipo=='RIP-BIO' || $codigoSubTipo=='RIP-AM' || $codigoSubTipo=='RIP-AC'|| $codigoSubTipo=='RIP-AD'|| $codigoSubTipo=='RIP-SP'||$codigoSubTipo=='RIP-COS' || $codigoSubTipo=='RIP-SNK' || $codigoSubTipo=='RIP-DAS'  || $codigoSubTipo=='RIP-DIN'){
				$unidadesMedida=$ce->obtenerUnidadesMedida($conexion,'DP_COMP');
				$cadaUnidad='';
				foreach($unidadesMedida as $key=>$valor){
					if($valor['id_unidad_medida']==$datos['producto_unidad']){
						$cadaUnidad=$valor['codigo'];
						break;
					}
				}
				$cada='Cada '.$datos['producto_cantidad'].' '. $cadaUnidad.' contine:';
				$hoja->escribirTabla($str,$numero++,1,'COMPOSICIÓN DEL PRODUCTO, '.$cada, $f,$itemHeader);

			}

			$items=array();
			$presentacionesSolicitud=$cp->obtenerPresentacion($conexion,$id_solicitud);
			$h='';
			foreach($presentacionesSolicitud as $key=>$presentacion){

				$items[]=$presentacion['presentacion'].' '.$presentacion['cantidad'].' '.$presentacion['unidad'];
			}

			$hoja->escribirParrafoLibre($str,'PRESENTACIONES COMERCIALES  Y TIPO DE ENVASE :',$items);


			$dosis=array();

			$h=array();
			$dosisProducto=$cp->obtenerDosis($conexion,$id_solicitud);
			foreach($dosisProducto as $key=>$valor){
				$temp=$valor['especie'].': Via de administración: '.$valor['via'].' '.$valor['cantidad'].' '.$valor['unidad1'];
				$temp=$temp.' por '.$valor['peso'].' '.$valor['unidad2'];
				$temp=$temp.' cada '.$valor['duracion'].' '.$valor['unidad3'];
				$temp=$temp.'; '.$valor['detalle'];
				$dosis[]=$temp;
			}
			$hoja->escribirParrafoLibre($str,'ESPECIE ANIMAL VÍA DE ADMINISTRACIÓN Y DOSIS :',$dosis);


			if($datos['efectos_colaterales']!=null && $datos['efectos_colaterales']!='0' && strlen( trim($datos['efectos_colaterales']))>0)
				$hoja->escribirParrafoLibre($str,'EFECTOS COLATERALES POSIBLE LOCALES O GENERALES IMCOMPATIBILIDADES Y ANTAGONISMOS :',array($datos['efectos_colaterales']));
			if($datos['precauciones']!=null && $datos['precauciones']!='0' && strlen( trim($datos['precauciones']))>0)
				$hoja->escribirParrafoLibre($str,'PRECAUCIONES GENERALES :',array($datos['precauciones']));
			if($datos['sobredosis']!=null && $datos['sobredosis']!='0' && strlen( trim($datos['sobredosis']))>0)
				$hoja->escribirParrafoLibre($str,'INTOXICACIÓN Y SOBREDOSIS EN ANIMALES :', array($datos['sobredosis']));
			if($datos['toxicidad']!=null && $datos['toxicidad']!='0' && strlen( trim($datos['toxicidad']))>0)
				$hoja->escribirParrafoLibre($str,'TOXICIDAD EN EL HOMBRE :', array($datos['toxicidad']));
			if($datos['tiene_categoria_toxicologica']=='t'){

				$categoriasToxicologicas=$cp->obtenerCatagoriasToxicologicas($conexion,'IAV');
				foreach($categoriasToxicologicas as $clave=>$valor){
					if($valor['id_categoria_toxicologica']=$datos['categoria_toxicologica']){
						$hoja->escribirParrafoLibre($str,'CATEGORÍA TOXICOLÓGICA :', array($valor['categoria_toxicologica']));
						break;
					}
				}

			}


			$empresas=array();
			foreach($fabricantes as $key=>$valor){
				$empresas[]=$valor['empresa'].', '.$valor['direccion'].' - '.strtoupper($valor['pais']);
			}
			$hoja->escribirParrafoLibre($str,'FABRICANTE(S)', $empresas);

			$items=array();
			$items[]='Razon social : '.$operador['razon_social'];
			$items[]='Provincia : '.$operador['provincia'];
			$items[]='Cantón : '.$operador['canton'];
			$items[]='Parroquia : '.$operador['parroquia'];
			$items[]='Dirección : '.$operador['direccion'];
			$hoja->escribirParrafoLibre($str,'TITULAR DE REGISTRO EN EL ECUADOR :', $items);



			$hoja->escribirParrafoLibre($str,'CONSERVACIÓN DEL PRODUCTO :', array($datos['conservacion']));

			$periodosRetirosSolicitud=$cp->obtenerPeriodosDeRetiro($conexion,$id_solicitud);
			$h='';
			$items=array();
			foreach($periodosRetirosSolicitud as $key=>$valor){
				$items[]=$valor['especie'].': '.$valor['consumible'].' '.$valor['tiempo'].' '.$valor['unidad'];

			}
			if(sizeof($items)>0)
				$hoja->escribirParrafoLibre($str,'PERIODOS DE RETIRO :', $items);

			$items=$ce->obtenerItemDelCatalogoEx($conexion,'PC_DE_VE',$datos['declaracion_venta']);
			$hoja->escribirParrafoLibre($str,'DECLARACIÓN DE VENTA :', array($items['nombre']));

			$items=array();
			$items[]='Producto de uso Veterinario';
			$items[]='Número de serie lote o partida';
			$items[]='Fecha de Vencimiento';
			$items[]='Mantenerlo fuera del alcance de los niños';

			if($codigoSubTipo=='RIP-DIN')
				$items[]='Prohibida su comercialización en establecimientos de expendio de productos agropecuarios y/o veterinarios';
			$hoja->escribirParrafoLibre($str,'FRASES OBLIGATORIAS :',$items);


			$y=$doc->GetY();
			$doc->writeHTMLCell($xfull,5,$x,$y,$str,0,1);

			$y=$doc->GetY();
			$y=$y+5;

			$xm=$xfull/3;

			$doc->SetAbsXY($xm,$y);

			$doc->writeHTMLCell(0,5,$margen_izquierdo,$y,'<i><u>Documento firmado electrónicamente</u></i>',0,1,false,true,'C');

			$doc->Cell($xfull,5,strtoupper($firmante),0,1,'C');
			$doc->Cell($xfull,5,strtoupper( $firmanteCargo),0,1,'C');
			$doc->Cell($xfull,5,"AGENCIA DE REGULACIÓN Y CONTROL FITO Y ZOOSANITARIO – AGROCALIDAD",0,1,'C');

			$y=$doc->GetY();
			$y=$y+5;

			$paths=$ce->obtenerRutaAnexos($conexion,'dossierPecuario');
			$rutaQR=$paths['rutaUrl'].'/'.$fileName;
			$style = array(
			 'border' => 2,
			 'vpadding' => 'auto',
			 'hpadding' => 'auto',
			 'fgcolor' => array(0,0,0),
			 'bgcolor' => false, //array(255,255,255)
			 'module_width' => 1,
			 'module_height' => 1
			);
			$doc->write2DBarcode($rutaQR, 'QRCODE,H', 20, $y, 30, 30, $style, 'N');

			$doc->Image('../ensayoEficacia/img/logo_agrocalidad.gif', 100, $y, 30, 30, 'GIF');

			// define active area for signature appearance
			$doc->setSignatureAppearance(150, $y, 30, 30);

			//******************************* FIN DE LA EDICION ****************************************************************************************

			$doc->Output($paths['rutaFisica'].'/'.$fileName, 'F');

			
			$ce->constrirRutas($mensaje,$paths,$fileName);

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
			$cp=new ControladorDossierPecuario();
			$ca = new ControladorAuditoria();
			
			$dossier=$cp->obtenerSolicitud($conexion,$idSolicitud);
			//verifico que no se repita el nombre
			$idSubtipoProducto=$dossier['id_subtipo_producto'];
			$nombreProducto=$dossier['nombre'];
			$producto = $cc->buscarProductoXNombre($conexion,$idSubtipoProducto,$nombreProducto);
			//verifico que no se repita el registro
			$numeroTotalRegistro = pg_num_rows($cr->buscarNombreRegistroProducto($conexion,$idCertificado));
			if(pg_num_rows($producto) == 0){
				
				if($numeroTotalRegistro == 0) {
					$partidaArancelaria=$dossier['partida_arancelaria'];
					$archivo=$dossier['ruta_dossier'];	//artes y rotulado
					
					$unidadMedida=pg_fetch_result($cc->obtenerUnidadMedida($conexion,$dossier['producto_unidad']),0,'codigo');
					$idCategoriaToxicologica=$dossier['categoria_toxicologica'];					
					$CategoriaToxicologica=pg_fetch_result($ce->obtenerCategoriaToxicologica($conexion,$idCategoriaToxicologica),0,'categoria_toxicologica');
					$idFormulacion=$dossier['id_formulacion'];					
					$nombreFormulacion=pg_fetch_result($ce->obtenerFormulacionActual($conexion,$idFormulacion),0,'formulacion');
					$empresa=$dossier['identificador'];
					$declaraciones=$ce->obtenerItemDelCatalogoEx($conexion,'PC_DE_VE',$dossier['declaracion_venta']);
					$declaracionVenta=$declaraciones['nombre2'];

					$observaciones=$dossier['observaciones'];

					$especiesDosier=array();
					//consultar que se debe poner alli
					$vector=$cp->obtenerDosis($conexion,$idSolicitud);
					$dosis="";
					$unidadMedidaDosis="";
					$vectorDosis=array();
					foreach($vector as $item){
						$vectorDosis[]=$item['especie'].' ('.$item['via'].') '.$item['cantidad'].$item['unidad1'].' por '.$item['peso'].$item['unidad2'].' cada '.$item['duracion'].$item['unidad3'];
						$unidadMedidaDosis=$item['unidad1'];
						$especiesDosier[]=$item['id_especie'];
					}
					$dosis=join('; ',$vectorDosis);
					$periodoReingreso="";
					$periodoCarencia="";				
					$vector=$cp->obtenerPeriodosDeRetiro($conexion,$idSolicitud);
					$vectorDosis=array();
					foreach($vector as $item){
						$vectorDosis[]=$item['especie'].' ('.$item['consumible'].') '.$item['tiempo'].$item['unidad'];
					}
					$periodoCarencia=join('; ',$vectorDosis);
					
					if($partidaArancelaria != '' || $partidaArancelaria != 0){
						$qCodigoProducto = $cc->obtenerCodigoProducto($conexion, $partidaArancelaria);
						$codigoProducto = str_pad(pg_fetch_result($qCodigoProducto, 0, 'codigo'), 4, "0", STR_PAD_LEFT);
					}else{
						$codigoProducto = 0;
					}
					$nombreCientifico='';
					
					//--$idProducto = pg_fetch_result($cr->guardarNuevoProducto($conexion, $nombreProducto, $nombreCientifico, $codigoProducto,  $partidaArancelaria, $idSubtipoProducto, $archivo, $unidadMedida, 'NO', 'NO', $identificador),0,'id_producto');
					//$idProducto = pg_fetch_result($cr->guardarNuevoProducto($conexion, $nombreProducto, $nombreCientifico, $codigoProducto,  $partidaArancelaria, $idSubtipoProducto, $archivo, $unidadMedida, 'NO', 'NO', $usuario),0,'id_producto');
					$idProducto = pg_fetch_result($cr->guardarNuevoProducto($conexion, $nombreProducto, $nombreCientifico, $codigoProducto, $partidaArancelaria, $idSubtipoProducto, $archivo, $unidadMedida, 'NO', 'NO', $usuario, 'NO'),0,'id_producto');
					
					$mensajesAuditoria=array();
					$mensajesAuditoria[]='ha creado el producto con id '.$idProducto.' de nombre '.$nombreProducto;
					if ($idCategoriaToxicologica == '') $idCategoriaToxicologica = 0;
					if ($idFormulacion == '') $idFormulacion = 0;
					if($fechaRegistro == '') $fechaRegistro = $fechaActual = date('Y-m-d');

					//limita la extención de los campos del modulo de registros
					$periodoReingreso=$this->limitarCaracteres($periodoReingreso,2046);	//limita a periodo de reingreso a 2046
					$periodoCarencia=$this->limitarCaracteres($periodoCarencia,1022);	//limita a periodo de reingreso a 1022
					$dosis=$this->limitarCaracteres($dosis,510);	//limita a periodo de reingreso a 510
					$observaciones =$this->limitarCaracteres($observaciones,1022);	//limita observaciones a 1022
				
					$cr->guardarProductoInocuidad($conexion, $idProducto, $idFormulacion, $nombreFormulacion, $idCertificado, $dosis, $unidadMedidaDosis, $periodoCarencia, $periodoReingreso, $observaciones, $idCategoriaToxicologica, $CategoriaToxicologica, $fechaRegistro, $empresa, $declaracionVenta);
					
					$mensajesAuditoria[]='ha creado el producto inocuidad con id '.$idProducto.' con registro '.$idCertificado;
					//sube los ingredientes activos				
					$vector=$cp->obtenerComposicionProducto($conexion,$idSolicitud);
					if(count($vector)>0){
						$ingredientes=array();
						foreach($vector as $item){
							$idIngredienteActivo=$item['id_ingrediente_activo'];
							if(pg_num_rows($cr->buscarComposicion($conexion, $idProducto, $idIngredienteActivo, null))==0){
								
								$cr -> guardarProductoInocuidadTMP($conexion, $idProducto);
								$cr -> guardarComposicion($conexion, $idProducto, $idIngredienteActivo,$item['ingrediente_activo'],$item['cantidad'],$item['codigo'], null, '', '');
								$ingredientes[]=$item['ingrediente_activo'];	
								
								$mensajesAuditoria[]='ha asociado el producto con id '.$idProducto.' con la concentracion '.$item['ingrediente_activo'].' '.$item['cantidad'].$item['codigo'];
								
							}
						}
						/* Comentado ya que campo no es necesario EJAR
						 * $ingredienteActivo=join(' + ',$ingredientes);
						$cr -> actualizarComposicionProducto($conexion,$idProducto,$ingredienteActivo);*/ 
					}

					//sube las presentaciones
					$vector=$cp->obtenerPresentacion($conexion,$idSolicitud);
					if(count($vector)>0){
						foreach($vector as $item){
							$qSubcodigo = $cc->obtenerCodigoInocuidad($conexion, $idProducto);
							$subcodigo = str_pad(pg_fetch_result($qSubcodigo, 0, 'codigo'), 4, "0", STR_PAD_LEFT);
							$presentacion=$item['presentacion'];
							$unidad=$item['codigo'];
							
							if(pg_num_rows($cr->buscarCodigoInocuidad($conexion, $idProducto, $presentacion,$unidad))==0){
								
								$cr -> guardarProductoInocuidadTMP($conexion, $idProducto);
								$cr -> guardarNuevoCodigoInocuidad($conexion, $idProducto,$subcodigo, $presentacion, $unidad);

								$mensajesAuditoria[]='ha asociado al producto con id '.$idProducto.' la presentación '.$presentacion;
									
							}
						}
						
					}

					//sube los codigos complementarios y suplementarios
					$vector=$cp->listarCodigoComplementarioSuplementario($conexion,$idSolicitud);
					if(pg_num_rows($vector)>0){
						while($item = pg_fetch_assoc($vector)){
							$codigoComplementario=$item['codigo_complementario'];
							$codigoSuplementario=$item['codigo_suplementario'];
							if(pg_num_rows($cr->buscarCodigoComplementarioSuplementario($conexion, $idProducto, $codigoComplementario, $codigoSuplementario))==0){
								$cr -> guardarNuevoCodigoComplementarioSuplementario($conexion, $idProducto, $codigoComplementario, $codigoSuplementario);	
								
								$mensajesAuditoria[]='ha asociado al producto con id '.$idProducto.' el codigo complementaio '.$codigoComplementario.' y el codigo suplementario '.$codigoSuplementario;
								
							}
						}					
					}

					//sube los fabricantes y formuladores
					$vector=$cp->obtenerFabricantesDossier($conexion,$idSolicitud);
					if(count($vector)>0){
						foreach($vector as $item){
							$formulador=$item['empresa'];
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
					
					$vector=explode(',',$dossier['usos']);
					if(count($vector)>0){
						foreach($especiesDosier as $codigoEspecie){
							//recupera el codigo de enlace de la especie
							$itemEspecie=$ce->obtenerItemDelCatalogoEx($conexion,'P_ESPECI',$codigoEspecie);
							$especieEnlace=$itemEspecie['nombre2'];
							if(($especieEnlace!=null) && (strlen(trim($especieEnlace))>0)){
								$query=$cc->obtenerEspecieXcodigo($conexion,$especieEnlace);
								if(pg_num_rows($query)>0){
									$especie=pg_fetch_assoc($query,0);
									$idEspecie=$especie['id_especies'];
									$nombreEspecie=$especie['nombre'];
									
									foreach($vector as $item){
										$idUso="";
										try{
											$idUso=trim($item);
											$uso=array();
											//verifica los usos fijos u opcionales
											if($idUso=='F' || $idUso=='O'){
												//recupera el uso según el subtipo
												
												$iavUsos=$cp->obtenerUsosPorClasificacion($conexion,$dossier['codificacion_subtipo_producto']);
												$uso=current($iavUsos);
												$idUso=$uso['id_uso'];
											}
											else{
												$usos=$cr->abrirUsoInocuidad($conexion,$idUso);
												$uso=pg_fetch_assoc($usos,0);
											}
											$nombreUso=$uso['nombre_uso'];
											if(pg_num_rows($cr->buscarUsoProductoEspecie($conexion, $idProducto,$idUso, $idEspecie, 'Especie'))==0){
												$cr -> guardarNuevoUsoEspecie($conexion, $idProducto, $idUso,$idEspecie, 'Especie');

												$mensajesAuditoria[]='ha asociado al producto con id '.$idProducto.' el uso '.$nombreUso.' a la especie '.$nombreEspecie;
												
											}
										}catch(Exception $e){
											$mensajesAuditoria[]='No se pudo asociar al producto con id '.$idProducto.' el uso '.$idUso.' a la especie '.$nombreEspecie;
										}
									}
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
			//return $retorno;
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


