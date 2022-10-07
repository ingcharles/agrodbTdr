<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorFitosanitario.php';
require_once '../../clases/ControladorZoosanitarioExportacion.php';
require_once 'fpdf.php';

class PDF extends FPDF
{
	function Header(){
		// Logo Magap, escudo, Agrocalidad
		//$this->Image('http://181.112.155.173/agrodb/aplicaciones/general/img/Membrete.jpg',25,8,160);
		$this->Image('http://192.168.20.9/agrodb/aplicaciones/general/img/Membrete.jpg',25,8,160);
		//$this->Image('http://localhost/agrodb/aplicaciones/general/img/Membrete.jpg',25,8,160);
		
		$this->Ln(20);

	}
	
	function Body($idSolicitud, $tipo){
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$cc = new ControladorCatalogos();
		
		$this->Cell(50);
		$this->SetFont('Times','B',10);
		
		if($tipo == 'Importación'){
			$ci = new ControladorImportaciones();
			$qRegistro = $ci->abrirImportacionReporte($conexion, $idSolicitud);
			$tipoRequisito = 'Importación';
			$ruta = 'origen';
			
			if ($qRegistro[0]['idArea'] == 'SA'){
				$this->Cell(0,30,utf8_decode('PERMISO ZOOSANITARIO PARA LA IMPORTACIÓN'),0,0);
				$this->Ln(5);
				$this->Cell(60);
				$this->Cell(10,30,utf8_decode('IMPORTATION ZOOSANITARY PERMIT'),0,0);
			}else if ($qRegistro[0]['idArea'] == 'SV'){
				$this->Cell(0,30,utf8_decode('PERMISO FITOSANITARIO PARA LA IMPORTACIÓN'),0,0);
				$this->Ln(5);
				$this->Cell(60);
				$this->Cell(10,30,utf8_decode('IMPORTATION FITOSANITARY PERMIT'),0,0);
			}else if ($qRegistro[0]['idArea'] == 'IAP'){
				$this->Cell(0,30,utf8_decode('PERMISO PARA LA IMPORTACIÓN DE PRODUCTOS PLAGICIDAS'),0,0);
				$this->Ln(5);
				$this->Cell(60);
				$this->Cell(10,30,utf8_decode('PESTICIDE PRODUCTS IMPORTATION PERMIT'),0,0);
			}else if ($qRegistro[0]['idArea'] == 'IAV'){
				$this->Cell(0,30,utf8_decode('PERMISO PARA LA IMPORTACIÓN DE PRODUCTOS VETERINARIOS'),0,0);
				$this->Ln(5);
				$this->Cell(60);
				$this->Cell(10,30,utf8_decode('VETERINARY PRODUCTS IMPORTATION PERMIT'),0,0);
			}
			
		}
		
		if($tipo == 'Fitosanitario'){
			$cf = new ControladorFitosanitario();
			$qRegistro = $cf->abrirFitoExportacionReporte($conexion, $idSolicitud);
			$tipoRequisito = 'Exportación';
			$ruta = 'destino';
			
			$this->Cell(0,30,utf8_decode('PERMISO FITOSANITARIO PARA LA IMPORTACIÓN'),0,0);
			$this->Ln(5);
			$this->Cell(60);
			$this->Cell(0,10,utf8_decode('IMPORTATION FITOSANITARY PERMIT'),0,0);
		}
		
		if($tipo == 'Zoosanitario'){
			$cz = new ControladorZoosanitarioExportacion();
			$qRegistro = $cz->abrirZooExportacionReporte($conexion, $idSolicitud);
			$tipoRequisito = 'Exportación';
			$ruta = 'destino';
				
			$this->Cell(0,30,utf8_decode('PERMISO ZOOSANITARIO PARA LA EXPORTACIÓN'),0,0);
			$this->Ln(5);
			$this->Cell(60);
			$this->Cell(0,30,utf8_decode('EXPORTATION ZOOSANITARY PERMIT'),0,0);
		}
		
		$this->Ln(5);
		
		$this->Cell(140);
		
		$this->Cell(0,50,utf8_decode('N°: '.$qRegistro[0]['idVue']),0,0);
		// Salto de línea
		$this->Ln(5);

		// Salto de línea
		$this->Ln(30);
		
		if($tipo == 'Fitosanitario'){
			$header = array(utf8_decode('Exportador'), utf8_decode('Razón Social'), 'Nombre del Producto', utf8_decode('Bultos'));
			
			$ancho = array(25, 65, 60, 40);
			$this->SetWidths($ancho);
			
			// Cabeceras
			$this->Row($header);
			
		    // Datos
		    $qProveedores = $cf->listarReporteProveedoresProductos($conexion, $idSolicitud);
		    
		    $totalBultos = 0;
		    
		    foreach($qProveedores as $proveedores){
		    	$totalBultos += $proveedores['numeroBultos'];
		    	
		    	$this->Row(array($proveedores['identificadorOperador'],utf8_decode($proveedores['razonSocial']),utf8_decode($proveedores['nombreProducto']),number_format($proveedores['numeroBultos']) . ' ' .$proveedores['unidadBultos']));
		    }
		    
		    $this->Row(array('','','Total',$totalBultos));
		    
		}else if ($tipo == 'Importación'){
			foreach ($qRegistro as $registro){
				$this->SetFillColor(200,220,255);
				$this->SetFont('Times','I',12);
				
				$res = $cc ->obtenerTipoSubtipoXProductos($conexion, $registro['idProducto']);
				$tipoSubtipo = pg_fetch_assoc($res);
				
				//Tipo
				$this->Cell(0,6,utf8_decode('Tipo de producto: '.$tipoSubtipo['nombre_tipo']),0,1,'L',true);
				// Salto de línea
				//$this->Ln(4);
				//Subtipo
				$this->Cell(0,6,utf8_decode('Subtipo de producto: '.$tipoSubtipo['nombre_subtipo']),0,1,'L',true);
				// Salto de línea
				//$this->Ln(4);
				//Producto
				$this->Cell(0,6,utf8_decode('Nombre del Producto: '.$registro['nombreProducto']),0,1,'L',true);
				// Salto de línea
				//$this->Ln(4);
				// Unidad
				//$this->Cell(0,6,utf8_decode('Unidades: '.$registro['unidad']),0,1,'L',true);
				// Salto de línea
				//$this->Ln(4);
				// Peso neto
				$this->Cell(0,6,utf8_decode('Peso neto: '.$registro['peso'].' ' .$registro['unidadMedida']),0,1,'L',true);
				// Salto de línea
				//$this->Ln(4);
				// Pais origen
				$this->Cell(0,6,utf8_decode('País origen: '.$registro['pais']),0,1,'L',true);
				// Salto de línea
				$this->Ln(4);
					
				$qRequisitos = $cr->listarRequisitosProducto($conexion, $registro['idPais'], $registro['idProducto'], $tipoRequisito);
				$this->SetFont('Times','',10);
			
				for ($i=0; $i<count($qRequisitos);$i++){
					$j=$i+1;
					$this->MultiCell(0,5,utf8_decode('R'.$j.'- '.$qRequisitos[$i]['detalleImpreso']),0,1);
					$this->Ln(1);
				}
					
				//$this->Cell(50);
				$this->Ln(5);
			}
			
			if($qRegistro[0]['idPaisEmbarque'] != $qRegistro[0]['idPais']){
				$this->SetFillColor(200,220,255);
				$this->SetFont('Times','I',12);
					
				$res = $cr ->abrirRequisitoXCodigo($conexion, 'RP-ORG-EMB');
				$requisitoEspecial = pg_fetch_assoc($res);
					
				//Requisito especial pais origen - embarque
				$this->Cell(0,6,utf8_decode('Requisito adicional'),0,1,'L',true);
				$this->SetFont('Times','',10);
				$this->Ln(4);
				$this->MultiCell(0,5,utf8_decode($requisitoEspecial['detalle_impreso']),0,1);
				$this->Ln(1);
			}
			
		}else{
			foreach ($qRegistro as $registro){
				$this->SetFillColor(200,220,255);
				$this->SetFont('Times','I',12);
				// Título
				$this->Cell(0,6,utf8_decode($registro['nombreProducto']),0,1,'L',true);
				// Salto de línea
				$this->Ln(4);
					
				$qRequisitos = $cr->listarRequisitosProducto($conexion, $registro['idPais'], $registro['idProducto'], $tipoRequisito);
				$this->SetFont('Times','',10);
					
				for ($i=0; $i<count($qRequisitos);$i++){
					$this->MultiCell(0,5,utf8_decode($qRequisitos[$i]['detalleImpreso']),0,1);
				}
					
				$this->Cell(50);
			}
		}
	}
	
	function Detalle($idSolicitud, $tipo){
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
	
		$this->SetFont('Times','B',10);
		
		// Movernos a la derecha
		$this->Cell(80);
		// Título
		$this->Cell(35,10,utf8_decode('REPÚBLICA DEL ECUADOR'),0,0,'C');
		// Salto de línea
		$this->Ln(5);
		
		$this->Cell(80);
		$this->Cell(40,10,utf8_decode('MINISTERIO DE AGRICULTURA, GANADERÍA, ACUACULTURA Y PESCA'),0,0,'C');
		// Salto de línea
		$this->Ln(10);
		
		$this->Cell(75);
		$this->Cell(45,15,utf8_decode('AGENCIA ECUATORIANA DE ASEGURAMIENTO DE LA CALIDAD DEL AGRO - AGROCALIDAD'),0,0,'C');
		
		// Salto de línea
		$this->Ln(15);
		$this->Cell(50);
	
		if($tipo == 'Importación'){
			$ci = new ControladorImportaciones();
			$qRegistro = $ci->abrirImportacionReporte($conexion, $idSolicitud);
			$tipoRequisito = 'Importación';
			$ruta = 'origen';
				
				
			$this->Cell(0,10,utf8_decode('PERMISO FITOSANITARIO PARA LA IMPORTACIÓN'),0,0);
			$this->Ln(5);
			$this->Cell(60);
			$this->Cell(0,10,utf8_decode('IMPORTATION FITOSANITARY PERMIT'),0,0);
		}
	
		if($tipo == 'Fitosanitario'){
			$cf = new ControladorFitosanitario();
			$qRegistro = $cf->abrirFitoExportacionReporte($conexion, $idSolicitud);
			$tipoRequisito = 'Exportación';
			$ruta = 'destino';
				
			$this->Cell(0,10,utf8_decode('ANEXO 1 CUADRO DE EXPORTADORES INSPECCIONADOS'),0,0);
			$this->Ln(5);
			$this->Cell(60);
		}
	
		if($tipo == 'Zoosanitario'){
			$cz = new ControladorZoosanitarioExportacion();
			$qRegistro = $cz->abrirZooExportacionReporte($conexion, $idSolicitud);
			$tipoRequisito = 'Exportación';
			$ruta = 'destino';
	
			$this->Cell(0,10,utf8_decode('PERMISO ZOOSANITARIO PARA LA EXPORTACIÓN'),0,0);
			$this->Ln(5);
			$this->Cell(60);
			$this->Cell(0,10,utf8_decode('EXPORTATION ZOOSANITARY PERMIT'),0,0);
		}
	
		$this->Ln(5);
		
		$this->Cell(0,30,utf8_decode('Fecha emisión: '.date('d-m-Y')),0,0);
	
		
		$this->Ln(1);
		$this->Cell(140);
		$this->Cell(50,30,utf8_decode('N°: '.$qRegistro[0]['idVue']),0,0);
		// Salto de línea
		$this->Ln(5);
	
		// Salto de línea
		$this->Ln(20);
	
		if($tipo == 'Fitosanitario'){
			$header = array(utf8_decode('Identificación'), utf8_decode('Razón Social'), utf8_decode('Nombre del Producto'), utf8_decode('Número /Descripción de bultos'));
				
			$ancho = array(25, 70, 55, 40);
			$this->SetWidths($ancho);
				
			// Cabeceras
			$this->Row($header);
				
			// Datos
			$qProveedores = $cf->listarReporteProveedoresProductos($conexion, $idSolicitud);
	
			$totalBultos = 0;
	
			foreach($qProveedores as $proveedores){
				$totalBultos += $proveedores['numeroBultos'];
			  
				$this->Row(array($proveedores['identificadorOperador'],utf8_decode($proveedores['razonSocial']),utf8_decode($proveedores['nombreProducto']),number_format($proveedores['numeroBultos']) . ' ' .$proveedores['unidadBultos']));
			}
	
			$this->Row(array('','','Total',number_format($totalBultos)));
			
			
			// Salto de línea
			$this->Ln(20);
						
			
			$header = array(utf8_decode(' '), utf8_decode('Número/Descripción de bultos'), utf8_decode('Cantidad producto/Unidad'));
			
			$ancho = array(90, 50, 50);
			$this->SetWidths($ancho);
			
			// Cabeceras
			$this->Row($header);
			
			// Datos
			$qProveedores = $cf->listarReporteTotalProductos($conexion, $idSolicitud);
			
			$totalBultos = 0;
			$totalPiezas = 0;
			
			foreach($qProveedores as $proveedores){
				$totalBultos += $proveedores['numeroBultos'];
				$totalPiezas += $proveedores['cantidadProducto'];
					
				$this->Row(array(utf8_decode($proveedores['nombreProducto']),number_format($proveedores['numeroBultos'])  . ' ' .$proveedores['unidadBultos'], number_format($proveedores['cantidadProducto']) . ' unidades'));
			}
			
			$this->Row(array('Total',number_format($totalBultos) . ' piezas',number_format($totalPiezas) . ' unidades'));
		}else{
			foreach ($qRegistro as $registro){
				$this->SetFillColor(200,220,255);
				$this->SetFont('Times','I',12);
				// Título
				$this->Cell(0,6,utf8_decode($registro['nombreProducto']),0,1,'L',true);
				// Salto de línea
				$this->Ln(4);
					
				$qRequisitos = $cr->listarRequisitosProducto($conexion, $registro['idPais'], $registro['idProducto'], $tipoRequisito);
				$this->SetFont('Times','',10);
					
				for ($i=0; $i<count($qRequisitos);$i++){
					$this->MultiCell(0,5,utf8_decode($qRequisitos[$i]['detalleImpreso']),0,1);
				}
					
				$this->Ln(8);
			}
		}
	}

	function Footer(){
		// Go to 1.5 cm from bottom
		$this->SetY(-15);
		// Select Arial italic 8
		$this->SetFont('Times','I',8);
		// Print centered page number
		$this->Cell(0,10,utf8_decode('Pág. ').$this->PageNo()."/{nb}",0,0,'C');
	}
	
	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}
	
	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}
	
	function Row($data)
	{
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			$this->Rect($x,$y,$w,$h);
			//Print the text
			$this->MultiCell($w,5,$data[$i],0,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}
	
	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}
	
	function NbLines($w,$txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			$c=$s[$i];
			if($c=="\n")
			{
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}
}
?>
