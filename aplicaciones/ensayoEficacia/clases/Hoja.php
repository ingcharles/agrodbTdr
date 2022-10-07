<?php

	class Hoja{
	    
	    private $tipoLetra='times';//	'dejavusans';
	    private $estiloLetra='';
	    private $medidaLetraMinima=7;
	    private $medidaLetraNormal=9;
	    private $medidaLetraMaximo=12;
	    private $margen_superior=20;
	    private $margen_inferior=15;
	    private $margen_izquierdo=20;
	    
	    private $ymax=0;
	    
	    public function MargenSuperior($margen=20){
	        $this->margen_superior=$margen;
	    }
	    public function MargenInferior($margen=15){
	        $this->margen_inferior=$margen;
	    }
	    public function MargenIzquierdo($margen=20){
	        $this->margen_izquierdo=$margen;
	    }
	    public function PonerTipoLetra($tipoLetra){
	        $this->tipoLetra=$tipoLetra;
	    }
	    public function PonerEstiloLetra($estiloLetra){
	        $this->estiloLetra=$estiloLetra;
	    }
	    public function PonerMedidaLetraMinima($medida){
	        $this->medidaLetraMinima=$medida;
	    }
	    public function PonerMedidaLetraNormal($medida){
	        $this->medidaLetraNormal=$medida;
	    }
	    public function PonerMedidaLetraMaximo($medida){
	        $this->medidaLetraMaximo=$medida;
	    }
	    
	    public function getYmax(){
	        return $this->ymax;
	    }
	    public function setYmax($yMax){
	        $this->ymax=$yMax;
	    }
	    
	    //******************************  ENSAYOS DE EFICACIA  ***************************
	    
	    public function escribirPdfCuadroSimple(TCPDF &$doc,&$xactual,&$yactual,$anchoCuadro,$ymin,$filas,$xcuadro,$punto,$puntoTexto,$dato,$esLineaFinal=false,$esNumerico=false,$marco=true){
	        
	        $yh=$doc->getPageHeight()-$this->margen_superior-$this->margen_inferior;
	        $yAlto=3+(1+$filas)*$ymin;
	        
	        if($yactual+$yAlto>$yh){
	            $doc->AddPage();
	            $yactual=$this->margen_superior;
	            $this->ymax=$yactual;
	        }
	        
	        $doc->SetAbsXY($xactual,$yactual);															//Retorna al punto inicial
	        $doc->SetFont($this->tipoLetra,'B',$this->medidaLetraMinima);	//Pone el font a minimo
	        $doc->Cell($xcuadro,$ymin,$punto,1,0,'C',false,0,0,true,'T','C');								//Dibuja el cuadro pequenio para el numeral y coloca el texto de [punto]
	        $doc->Cell($anchoCuadro-$xcuadro-2,$ymin,$puntoTexto,0,1,'L',false,0,0,true,'T','C');	//Escribe el texto a continuación del cuadro
	        $doc->SetAbsXY($xactual+1,$yactual+4);																	//Baja una linea y se ubica al inicio del cuadro contenedor
	        $doc->SetFont($this->tipoLetra,$this->estiloLetra,$this->medidaLetraNormal);	//Pone el font a normal
	        if($dato==null)
	            $dato="";
	            $strMostrar=trim($dato);
	            if(!$esNumerico && ($strMostrar==null || $strMostrar=='0' || $strMostrar==''))
	                $strMostrar='N/A';
	                
	                $doc->MultiCell($anchoCuadro-$xcuadro-2,4*$filas,$strMostrar,0,'L',false,1,'','',true,0,false,true,0,'M');
	                $yAlto=$doc->GetY();
	                if($yAlto>$this->ymax)
	                    $this->ymax=$doc->GetY();
	                    if($marco){
	                        $doc->SetAbsXY($xactual,$yactual);																	//Se ubica en la posicion de inicio
	                        $doc->Cell($anchoCuadro,$this->ymax-$yactual,'',1,1,'L',false,0,0,true,'T','T');		//Dibuja el cuadro de contorno del bloque
	                    }
	                    
	                    if($esLineaFinal){
	                        
	                    }else{
	                        
	                        $xactual=$xactual+$anchoCuadro;
	                    }
	                    
	    }
	    
	    public function escribirPdfCuadroMuliple(TCPDF &$doc,&$xactual,&$yactual,$anchoCuadro,$ymin,$filas,$xcuadro,$punto,$puntoTexto,$dato,$campoMostrar,$esLineaFinal=false,$esNumerico=false){
	        
	        $yh=$doc->getPageHeight()-$this->margen_superior-$this->margen_inferior;
	        $yAlto=3+(1+$filas)*$ymin;
	        
	        if($yactual+$yAlto>$yh){
	            $doc->AddPage();
	            $yactual=$this->margen_superior;
	            $this->ymax=$yactual;
	        }
	        $doc->SetAbsXY($xactual,$yactual);
	        $doc->Cell($anchoCuadro,(1+$filas)*$ymin,'',1,0,'L',false,0,0,true,'T','T');
	        $doc->SetAbsXY($xactual,$yactual);
	        $doc->SetFont($this->tipoLetra,'B',$this->medidaLetraMinima);
	        $doc->Cell($xcuadro,$ymin,$punto,1,0,'C',false,0,0,true,'T','C');
	        $doc->Cell($anchoCuadro-$xcuadro-2,$ymin,$puntoTexto,0,1,'L',false,0,0,true,'T','C');
	        $doc->SetAbsXY($xactual+1,$yactual+4);
	        $doc->SetFont($this->tipoLetra,$this->estiloLetra,$this->medidaLetraNormal);
	        $pos=1;
	        if($esLineaFinal){
	            foreach($dato as $key=>$item){
	                $doc->SetAbsXY($xactual+1,$yactual+4*$pos);
	                try{
	                    $strMostrar=trim($item[$campoMostrar]);
	                    if(!$esNumerico && ($strMostrar==null || $strMostrar=='0' || $strMostrar==''))
	                        $strMostrar='N/A';
	                        $doc->Cell($anchoCuadro-$xcuadro-2,4,$strMostrar,0,0,'L',false,0,0,true,'T','B');
	                }catch(Exception $e){}
	                $pos++;
	            }
	            $doc->Ln();
	        }else{
	            foreach($dato as $key=>$item){
	                $doc->SetAbsXY($xactual+1,$yactual+4*$pos);
	                try{
	                    $strMostrar=trim($item[$campoMostrar]);
	                    if(!$esNumerico && ($strMostrar==null || $strMostrar=='0' || $strMostrar==''))
	                        $strMostrar='N/A';
	                        $doc->Cell($anchoCuadro-$xcuadro-2,4,$strMostrar,0,0,'L',false,0,0,true,'T','B');
	                }catch(Exception $e){}
	                $pos++;
	            }
	            $xactual=$xactual+$anchoCuadro;
	        }
	        $yAlto=$doc->GetY();
	        if($yAlto>$this->ymax)
	            $this->ymax=$doc->GetY();
	    }
	    
	    public function escribirPdfCuadroMulipleVector(TCPDF &$doc,&$xactual,&$yactual,$anchoCuadro,$ymin,$filas,$xcuadro,$punto,$puntoTexto,$vector,$esLineaFinal=false,$esNumerico=false,$mostrarNumeracion=true,$mostrarCuadro=true){
	        $yh=$doc->getPageHeight()-$this->margen_superior-$this->margen_inferior;
	        $yAlto=3+(1+$filas)*$ymin;
	        $y=$doc->GetY();
	        if($yactual+$yAlto>$yh){
	            $doc->AddPage();
	            $yactual=$this->margen_superior;
	            $this->ymax=$yactual;
	        }
	        
	        $doc->SetAbsXY($xactual,$yactual);
	        if($mostrarCuadro)
	            $doc->Cell($anchoCuadro,(1+$filas)*$ymin,'',1,0,'L',false,0,0,true,'T','T');				//Cuadro general
	            if($mostrarNumeracion){
	                $doc->SetAbsXY($xactual,$yactual);
	                $doc->SetFont($this->tipoLetra,'B',$this->medidaLetraMinima);
	                $doc->Cell($xcuadro,$ymin,$punto,1,0,'C',false,0,0,true,'T','C');							//El cuadro de numeración
	            }
	            else{
	                $doc->SetFont($this->tipoLetra,'B',$this->medidaLetraNormal);
	            }
	            $doc->Cell($anchoCuadro-$xcuadro-2,$ymin,$puntoTexto,0,1,'L',false,0,0,true,'T','C');	//El texto del cuadro de numeración
	            $doc->SetAbsXY($xactual+1,$yactual+4);
	            $doc->SetFont($this->tipoLetra,$this->estiloLetra,$this->medidaLetraNormal);
	            $pos=1;
	            if($esLineaFinal){
	                foreach($vector as $key=>$item){
	                    $y=$yactual+4*$pos;
	                    $doc->SetAbsXY($xactual+1,$y);
	                    try{
	                        //muestra campo en el margen
	                        $doc->SetFont($this->tipoLetra,'B',$this->medidaLetraNormal);
	                        
	                        $doc->Write(4,$key);
	                        $doc->SetFont($this->tipoLetra,$this->estiloLetra,$this->medidaLetraNormal);
	                        $strMostrar=trim($item);
	                        if(!$esNumerico && ($strMostrar==null || $strMostrar=='0' || $strMostrar==''))
	                            $strMostrar='N/A';
	                            
	                            $doc->Write(4,$strMostrar);
	                    }catch(Exception $e){}
	                    $pos++;
	                }
	                $doc->Ln();
	            }else{
	                foreach($vector as $key=>$item){
	                    $doc->SetAbsXY($xactual+1,$yactual+4*$pos);
	                    try{
	                        //muestra campo en el margen
	                        $doc->SetFont($this->tipoLetra,'B',$this->medidaLetraNormal);
	                        
	                        $doc->Write(4,$key);
	                        $doc->SetFont($this->tipoLetra,$this->estiloLetra,$this->medidaLetraNormal);
	                        $strMostrar=trim($item);
	                        if(!$esNumerico && ($strMostrar==null || $strMostrar=='0' || $strMostrar==''))
	                            $strMostrar='N/A';
	                            $doc->Write(4,$strMostrar);
	                            
	                    }catch(Exception $e){}
	                    $pos++;
	                }
	                $xactual=$xactual+$anchoCuadro;
	            }
	            $yAlto=$doc->GetY();
	            if($yAlto>$this->ymax)
	                $this->ymax=$doc->GetY();
	    }
	    
	    //********************************************************************************

		public function buscarEnArray($clave,$valorClave,$columnaRetorno,$array){
			foreach($array as $value){
				if($value[$clave]==$valorClave){
					return $value[$columnaRetorno];
				}
			}
			return '';
		}

		public function escribirItemsTablaTitulo(&$str,$items,$conNegrilla=false){

			foreach($items as $valor){
				$apunta=key($valor);
				$str=	$str."<tr>";
				$str=	$str.'<td style="padding-left:3px;"><b>'.$apunta."</b></td>";
				if($conNegrilla){
					$str=	$str.'<td style="padding-left:3px; font:bold 16px arial;">'.$valor[$apunta]."</td>";
				}
				else{
					$str=	$str.'<td style="padding-left:3px;">'.$valor[$apunta]."</td>";
				}
				$str=	$str."</tr>";

			}

		}

		public function escribirTabla(&$str,$numero,$numeroSiguiente,$encabezado,$items,$itemsHeader=null,$esNumerada=false,$esSubitem=false){
			$temp='';
			if($esNumerada){
				$temp=$numero;
				if($esSubitem){
					$temp=$temp.'.'.$numeroSiguiente;
				}
				$temp=$temp.'. ';
			}
			$temp=$temp.'<b>'.$encabezado.'</b>';

			$str=	$str."<dl>";
			$str=$str.'<dt>'.$temp.'</dt>';
			$numeroSecundario=1;
			foreach($items as $grupo){
				if($grupo[1]==null)
					continue;

				$str=	$str."<dt>";

				$str=	$str.'<table border="1"  cellpadding="3">';
				if($itemsHeader!=null){
					$str=	$str."<thead>";
					$str=	$str.'<tr bgcolor = "lightgray">';
					$str=	$str.'<th style="width:200px; padding-left:13px;">'.$grupo[0]."</th>";

					foreach($itemsHeader as $clave=>$item){
						$str=	$str.'<th style="width:150px; padding-left:13px;">'.$item."</th>";
					}
					$str=	$str."</tr>";
					$str=	$str."</thead>";
				}
				foreach($grupo[1] as $clave=>$valor){
					$str=	$str."<tr>";
					$str=	$str.'<td style="width:200px; padding-left:13px;">'.$clave."</td>";
					$str=	$str.'<td style="width:150px; padding-left:13px;">'.$valor."</td>";
					$str=	$str."</tr>";
				}
				$str=	$str."</table>";
				$str=	$str."</dt>";
				$numeroSecundario++;
			}

			$str=	$str."</dl>";

		}

		public function escribirTabla3(&$str,$numero,$numeroSiguiente,$encabezado,$items,$itemsHeader=null,$esNumerada=false,$esSubitem=false){
			$temp='';
			if($esNumerada){
				$temp=$numero;
				if($esSubitem){
					$temp=$temp.'.'.$numeroSiguiente;
				}
				$temp=$temp.'. ';
			}
			$temp=$temp.'<b>'.$encabezado.'</b>';

			$str=	$str."<dl>";
			if($encabezado!=null)
				$str=	$str."<dt>".$temp.'</dt>';
			$str=	$str."<dt>";
			$str=	$str.'<table border="1" cellpadding="3" width=100%>';
			if($itemsHeader!=null){
				$str=	$str."<thead>";
				$str=	$str.'<tr bgcolor = "lightgray">';
				$str=	$str.'<th style="text-align:center; width:'.$itemsHeader[0]['ancho'].'%; padding-left:3px;">'.$itemsHeader[0]['titulo']."</th>";
				$str=	$str.'<th style="text-align:center; width:'.$itemsHeader[1]['ancho'].'%; padding-left:3px;">'.$itemsHeader[1]['titulo']."</th>";
				$str=	$str.'<th style="text-align:center; width:'.$itemsHeader[2]['ancho'].'%; padding-left:3px;">'.$itemsHeader[2]['titulo']."</th>";
				$str=	$str."</tr>";
				$str=	$str."</thead>";
			}

			foreach($items as $grupo){
				$str=	$str."<tr>";
				$strAncho='';
				if($itemsHeader[0]!=null)
					$strAncho='width:'.$itemsHeader[0]['ancho'].'%;';
				$str=	$str.'<td style="text-align:center; '.$strAncho.' padding-left:3px;">'.$grupo[0]."</td>";
				$strAncho='';
				if($itemsHeader[1]!=null)
					$strAncho='width:'.$itemsHeader[1]['ancho'].'%;';
				$str=	$str.'<td style="text-align:center; '.$strAncho.' padding-left:3px;">';
				$str=	$str.'<table>';
				foreach($grupo[1] as $valor){
					$str=	$str.'<td style="text-align:center;padding-left:3px;">'.$valor."</td>";
				}
				$str=	$str.'</table>';
				$str=	$str."</td>";
				$strAncho='';
				if($itemsHeader[2]!=null)
					$strAncho='width:'.$itemsHeader[2]['ancho'].'%;';
				$str=	$str.'<td style="text-align:center; '.$strAncho.' padding-left:3px;">'.$grupo[2]."</td>";
				$str=	$str."</tr>";

			}
			$str=	$str."</table>";
			$str=	$str."</dt>";
			$str=	$str."</dl>";

		}

		public function escribirTabla4(&$str,$numero,$numeroSiguiente,$encabezado,$items,$itemsHeader=null,$esNumerada=false,$esSubitem=false){
			$temp='';
			if($esNumerada){
				$temp=$numero;
				if($esSubitem){
					$temp=$temp.'.'.$numeroSiguiente;
				}
				$temp=$temp.'. ';
			}
			$temp=$temp.'<b>'.$encabezado.'</b>';

			$str=	$str."<dl>";
			if($encabezado!=null)
				$str=	$str."<dt>".$temp.'</dt>';
			$str=	$str."<dt>";
			$str=	$str.'<table border="1" cellpadding="3" width="100%">';
			if($itemsHeader!=null){
				$str=	$str."<thead>";
				$str=	$str.'<tr bgcolor = "lightgray">';
				$str=	$str.'<th style="text-align:center; width:'.$itemsHeader[0]['ancho'].'%; padding-left:3px;">'.$itemsHeader[0]['titulo']."</th>";
				$str=	$str.'<th style="text-align:center; width:'.$itemsHeader[1]['ancho'].'%; padding-left:3px;">'.$itemsHeader[1]['titulo']."</th>";
				$str=	$str.'<th style="text-align:center; width:'.$itemsHeader[2]['ancho'].'%; padding-left:3px;">'.$itemsHeader[2]['titulo']."</th>";
				$str=	$str.'<th style="text-align:center; width:'.$itemsHeader[3]['ancho'].'%; padding-left:3px;">'.$itemsHeader[3]['titulo']."</th>";
				$str=	$str."</tr>";
				$str=	$str."</thead>";
			}

			foreach($items as $grupo){
				$str=	$str."<tr>";
				$strAncho='';
				if($itemsHeader[0]!=null)
					$strAncho='width:'.$itemsHeader[0]['ancho'].'%;';
				$str=	$str.'<td style="text-align:center; '.$strAncho.' padding-left:3px;">'.$grupo[0]."</td>";
				$strAncho='';
				if($itemsHeader[1]!=null)
					$strAncho='width:'.$itemsHeader[1]['ancho'].'%;';
				$str=	$str.'<td style="text-align:center; '.$strAncho.' padding-left:3px;">'.$grupo[1]."</td>";
				$strAncho='';
				if($itemsHeader[2]!=null)
					$strAncho='width:'.$itemsHeader[2]['ancho'].'%;';
				$str=	$str.'<td style="text-align:center; '.$strAncho.' padding-left:3px;">'.$grupo[2]."</td>";
				$strAncho='';
				if($itemsHeader[3]!=null)
					$strAncho='width:'.$itemsHeader[3]['ancho'].'%;';
				$str=	$str.'<td style="text-align:center; '.$strAncho.' padding-left:3px;">'.$grupo[3]."</td>";
				$str=	$str."</tr>";

			}
			$str=	$str."</table>";
			$str=	$str."</dt>";
			$str=	$str."</dl>";

		}

		public function escribirTablaStandar(&$str,$numero,$numeroSiguiente,$encabezado,$items,$itemsHeader=null,$esNumerada=true,$esSubitem=false){

			$temp='';
			if($esNumerada){
				$temp=$numero;
				if($esSubitem){
					$temp=$temp.'.'.$numeroSiguiente;
				}
			}
			$temp=$temp.'. '.$encabezado;

			$str=	"<dl>";
			$str=	$str."<dt>";
			$str=	$str.'<b>'.$temp.'</b>';
			$str=	$str."</dt>";
			$numeroSecundario=1;
			foreach($items as $key=>$grupo){

				$str=	$str."<dt>";

				$str=	$str.'<table border="1">';
				$str=	$str.'<caption>'.$grupo[0].'</caption>';

				if($itemsHeader!=null){
					$str=	$str."<thead>";
					$str=	$str.'<tr bgcolor = "lightgray">';
					foreach($itemsHeader as $clave=>$item){
						$str=	$str.'<th style="padding-left:3px;">'.$item."</th>";
					}
					$str=	$str."</tr>";
					$str=	$str."</thead>";
				}
				foreach($grupo[1] as $clave=>$valor){
					$str=	$str."<tr>";
					foreach($valor as $k=>$columna){
						$str=	$str.'<td style="padding-left:3px;">'.$columna."</td>";
					}

					$str=	$str."</tr>";
				}
				$str=	$str."</table>";
				$str=	$str."</dt>";
				$numeroSecundario++;
			}

			$str=	$str."</dl>";
			
		}

		public function escribirParrafoLibre(&$str,$encabezado,$items){

			$str=	$str."<dl>";
			$str=	$str."<dt><b>".$encabezado."</b></dt>";
			foreach($items as $key=>$valor){
				try{
					$str=	$str."<dd>";
					$str=	$str.$valor;
					$str=	$str."</dd>";
				}catch(Exception $e){}
			}

			$str=	$str."</dl>";


		}

		public function escribirParrafoNumerada(&$str,$numero,$encabezado,$encabezadoDetalle,$items){

			$str=	$str."<dl>";
			$str=	$str."<dt>";
			$str=	$str.'<b>'.$numero.'. '.$encabezado.'</b>';
			$str=	$str."</dt>";
			$str=	$str."<dd>";
			$str=	$str.$encabezadoDetalle;
			$str=	$str."</dd>";
			$numeroSecundario=1;
			foreach($items as $key=>$valor){
			   $strNumero=$numero.'. '.$numeroSecundario;
			   if(is_array( $valor)){
			      $this->escribirParrafoNumerada($str,$strNumero,$valor[0],$valor[1],$valor[2]);
			   }
			   else{
			      try{
			         if(is_numeric($key)){
			            $str=	$str."<dt>";
			            $str=	$str.$strNumero.'. '.$valor;
			            $str=	$str."</dt>";
			         }
			         else{
			            $str=	$str."<dt>";
			            $str=	$str.$strNumero.'. '.$key;
			            $str=	$str."</dt>";
			            $str=	$str."<dd>";
			            
							$str=	$str.$valor;
			            $str=	$str."</dd>";
			         }
			      }catch(Exception $e){}
			   }
			   $numeroSecundario++;
			}
			
			$str=	$str."</dl>";

		}

		public function escribirParrafos(&$str,$numeroStr,$numero,$encabezado,$encabezadoDetalle,$items,$esNumerada=false,$esPrincipal=true,$claveNegrilla=true){
			
			$encabezadoDetalle=trim($encabezadoDetalle);
			if($esPrincipal)
				$str=	$str."<div>";
		   $str=$str.'<dl>';
		   if($numeroStr==null || $numeroStr=='')
		      $str=$str.'<dt><b>'.$numero.'. '.$encabezado.'</b>';
		   else
		      $str=$str.'<dt><b>'.$numeroStr.'. '.$encabezado.'</b>';
			$str=$str.'</dt>';
			if($encabezadoDetalle!=null && $encabezadoDetalle!=''){
				$str=	$str."<dd>";
				$str=	$str.$encabezadoDetalle;
				$str=	$str."</dd>";
			}
		   $numeroSecundario=1;
		   foreach($items as $key=>$valor){
		      if($numeroStr==null || $numeroStr=='')
		         $strNumero=$numero.'. '.$numeroSecundario;
		      else
		         $strNumero=$numeroStr.'. '.$numeroSecundario;
		      if(is_array( $valor)){
					$str=$str.'<dt>';
					$this->escribirParrafos($str,$strNumero,$numeroSecundario,$valor[0],$valor[1],$valor[2],$esNumerada,false);
					$str=$str.'</dt>';
		      }
		      else{
		         try{

		            if(is_numeric($key)){
		               $str=	$str."<dd>";
		               if($esNumerada)
		                  $str=	$str.$strNumero.'. ';
		               $str=	$str.$valor;
		               $str=	$str."</dd>";
		            }
		            else{
		               $str=	$str."<dd>";

							$ss='';
		               if($esNumerada)
		                  $ss=$strNumero.'. ';
							if($claveNegrilla)
								$ss=$ss.'<b>'.$key.'</b>';
							else
								$ss=$ss.$key;
		               $str=	$str.$ss;


		               $str=	$str.$valor;
		               $str=	$str."</dd>";
		            }

		         }catch(Exception $e){}
		      }
		      $numeroSecundario++;
		   }

		   $str=	$str."</dl>";
			if($esPrincipal)
				$str=	$str."</div>";
		}

		public function escribirSeccionSimple(&$str,$numero,$encabezado,$items){

			$str="<dl>";
			$str=	$str."<dt>";
			$str=	$str.'<b>'.$numero.'. '.$encabezado.'</b>';
			$str=	$str."</dt>";
			foreach($items as $valor){
				try{
					$str=	$str."<dt>";
					$str=	$str.$valor;
					$str=	$str."</dt>";
				}catch(Exception $e){}
			}
			$str=	$str."</dl>";

		}

		public function escribirSeccionNumerada(&$str,$numero,$encabezado,$items,$esNumerada=true){

			$str=	$str."<dl nobr='true'>";
			$str=	$str."<dt nobr='true'>";
			$str=	$str.'<b>'.$numero.'. '.$encabezado.'</b>';
			$str=	$str."</dt>";
			$numeroSecundario=1;
			$strNumero=$numero.'. '.$numeroSecundario;
			if(!is_array( $items)){
				try{
					if($esNumerada){
						$str=	$str."<dt nobr='true'>";
						$str=	$str.$strNumero.'. '.$items;
						$str=	$str."</dt>";
					}
					else{
						$str=	$str."<dd nobr='true'>";
						$str=	$str.$items;
						$str=	$str."</dd>";
					}
				}catch(Exception $e){}
			}
			foreach($items as $valor){
				$strNumero=$numero.'. '.$numeroSecundario;
				if(is_array( $valor)){
					$str=	$str."<dt>";
					$this->escribirSeccionNumerada($str,$strNumero,$valor[0],$valor[1]);
					$str=	$str."</dt>";
				}
				else{
					try{
						$str=	$str."<dt nobr='true'>";
						$str=	$str.$strNumero.'. '.$valor;
						$str=	$str."</dt>";
					}catch(Exception $e){}
				}
				$numeroSecundario++;
			}

			$str=	$str."</dl>";


		}

	}


?>


