<?php
/**
 * Controlador ExportadoresProductos
 *
 * Este archivo controla la lógica del negocio del modelo:  ExportadoresProductosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-07-04
 * @uses    ExportadoresProductosControlador
 * @package CertificadoFitosanitario
 * @subpackage Controladores
 */
namespace Agrodb\CertificadoFitosanitario\Controladores;

use Agrodb\CertificadoFitosanitario\Modelos\ExportadoresProductosLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\ExportadoresProductosModelo;
use Agrodb\CertificadoFitosanitario\Modelos\CertificadoFitosanitarioLogicaNegocio;
use Agrodb\CertificadoFitosanitario\Modelos\CertificadoFitosanitarioModelo;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use Agrodb\Catalogos\Modelos\LocalizacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\LocalizacionModelo;

class ExportadoresProductosControlador extends BaseControlador
{

    private $lNegocioExportadoresProductos = null;
    private $modeloExportadoresProductos = null;
    private $lNegocioCertificadoFitosanitario = null;
    private $modeloCertificadoFitosanitario = null;
    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioExportadoresProductos = new ExportadoresProductosLogicaNegocio();
        $this->modeloExportadoresProductos = new ExportadoresProductosModelo();
        $this->lNegocioCertificadoFitosanitario = new CertificadoFitosanitarioLogicaNegocio();
        $this->modeloCertificadoFitosanitario = new CertificadoFitosanitarioModelo();
        $this->lNegocioLocalizacion = new LocalizacionLogicaNegocio();
        $this->modeloLocalizacion = new LocalizacionModelo();
        set_exception_handler(array(
            $this,
            'manejadorExcepciones'
        ));
    }

    /**
     * Método de inicio del controlador
     */
    public function index()
    {
        $modeloExportadoresProductos = $this->lNegocioExportadoresProductos->buscarExportadoresProductos();
        $this->tablaHtmlExportadoresProductos($modeloExportadoresProductos);
        require APP . 'CertificadoFitosanitario/vistas/listaExportadoresProductosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo ExportadoresProductos";
        require APP . 'CertificadoFitosanitario/vistas/formularioExportadoresProductosVista.php';
    }

    /**
     * Método para registrar en la base de datos -ExportadoresProductos
     */
    public function guardar()
    {
        $idCertificadoFitosanitario = $_POST['id_certificado_fitosanitario_exportadores_productos'];
        $idPaisDestino = $_POST['id_pais_destino'];
        $identificadorExportador = $_POST['identificador_exportador'];
        $razonSocialExportador = $_POST['razon_social_exportador'];
        $direccionExportador = $_POST['direccion_exportador'];
        $idTipoProducto = $_POST['id_tipo_producto'];
        $nombreTipoProducto = $_POST['nombre_tipo_producto'];
        $idSubtipoProducto = $_POST['id_subtipo_producto'];
        $nombreSubtipoProducto = $_POST['nombre_subtipo_producto'];
        $idProducto = $_POST['id_producto'];
        $nombreProducto = $_POST['nombre_producto'];
        $certificacionOrganica = $_POST['certificacion_organica'];
        $partidaArancelariaProducto = $_POST['partida_arancelaria_producto'];
        $cantidadComercial = $_POST['cantidad_comercial'];
        $idUnidadCantidadComercial = $_POST['id_unidad_cantidad_comercial'];
        $nombreUnidadCantidadComercial = $_POST['nombre_unidad_cantidad_comercial'];
        $pesoBruto = $_POST['peso_bruto'];
        $idUnidadPesoBruto = $_POST['id_unidad_peso_bruto'];
        $nombreUnidadPesoBruto = $_POST['nombre_unidad_peso_bruto'];
        $pesoNeto = $_POST['peso_neto'];
        $idUnidadPesoNeto = $_POST['id_unidad_peso_neto'];
        $nombreUnidadPesoNeto = $_POST['nombre_unidad_peso_neto'];
        if(isset($_POST['id_area']) && $_POST['id_area'] != ""){
            $idArea = $_POST['id_area'];
            $nombreArea = $_POST['nombre_area'];
            $codigoCentroAcopio = $_POST['codigo_centro_acopio'];
            $idProvinciaArea = $_POST['id_provincia_area'];
            $nombreProvinciaArea = $_POST['nombre_provincia_area'];
        }
        $fechaInspeccion = $_POST['fecha_inspeccion'];
        $horaInspeccion = $_POST['hora_inspeccion'];
        $idTipoTratamiento = $_POST['id_tipo_tratamiento'];
        $nombreTipoTratamiento = $_POST['nombre_tipo_tratamiento'];
        $idTratamiento = $_POST['id_tratamiento'];
        $nombreTratamiento = $_POST['nombre_tratamiento'];
        $duracionTratamiento = $_POST['duracion_tratamiento'];
        $idUnidadDuracion = $_POST['id_unidad_duracion'];
        $nombreUnidadDuracion = $_POST['nombre_unidad_duracion'];
        $temperaturaTratamiento = $_POST['temperatura_tratamiento'];
        $idUnidadTemperatura = $_POST['id_unidad_temperatura'];
        $nombreUnidadTemperatura = $_POST['nombre_unidad_temperatura'];
        $fechaTratamiento = $_POST['fecha_tratamiento'];
        $productoQuimico = $_POST['producto_quimico'];
        $concentracionTratamiento = $_POST['concentracion_tratamiento'];
        $idUnidadConcentracion = $_POST['id_unidad_concentracion'];
        $nombreUnidadConcentracion = $_POST['nombre_unidad_concentracion'];
		  
		$validacion = "Fallo";
		$resultado = "El exportador y producto ya han sido registrados.";
		
		/*echo '<pre>';
		print_r($_POST);
		echo '<pre>';*/
		
        $arrayParametros = array(
            'id_certificado_fitosanitario' => $idCertificadoFitosanitario,
            'id_pais_destino' => $idPaisDestino,
            'identificador_exportador' => $identificadorExportador,
            'razon_social_exportador' => $razonSocialExportador,
            'direccion_exportador' => $direccionExportador,
            'id_tipo_producto' => $idTipoProducto,
            'nombre_tipo_producto' => $nombreTipoProducto,
            'id_subtipo_producto' => $idSubtipoProducto,
            'nombre_subtipo_producto' => $nombreSubtipoProducto,
            'id_producto' => $idProducto,
            'nombre_producto' => $nombreProducto,
            'partida_arancelaria_producto' => $partidaArancelariaProducto,
            'cantidad_comercial' => $cantidadComercial,
            'id_unidad_cantidad_comercial' => $idUnidadCantidadComercial,
            'nombre_unidad_cantidad_comercial' => $nombreUnidadCantidadComercial,
            'peso_neto' => $pesoNeto,
            'id_unidad_peso_neto' => $idUnidadPesoNeto,
            'nombre_unidad_peso_neto' => $nombreUnidadPesoNeto,
            'estado_exportador_producto' => 'Creado'
        );
        
        if(isset($certificacionOrganica) && $certificacionOrganica != ""){
            $arrayParametros += ['certificacion_organica' => $certificacionOrganica];
        }
        
        if(isset($idTipoTratamiento) && $idTipoTratamiento != ""){
            $arrayParametros += ['id_tipo_tratamiento' => $idTipoTratamiento];
            $arrayParametros += ['nombre_tipo_tratamiento' => $nombreTipoTratamiento];
        }   
        
        if(isset($idTratamiento) && $idTratamiento != ""){
            $arrayParametros += ['id_tratamiento' => $idTratamiento];
            $arrayParametros += ['nombre_tratamiento' => $nombreTratamiento];
        } 
        
        if(isset($idUnidadDuracion) && $idUnidadDuracion != ""){
            $arrayParametros += ['id_unidad_duracion' => $idUnidadDuracion];
            $arrayParametros += ['nombre_unidad_duracion' => $nombreUnidadDuracion];
        } 
        
        if(isset($idUnidadTemperatura) && $idUnidadTemperatura != ""){
            $arrayParametros += ['id_unidad_temperatura' => $idUnidadTemperatura];
            $arrayParametros += ['nombre_unidad_temperatura' => $nombreUnidadTemperatura];
        }
        
        if(isset($idUnidadConcentracion) && $idUnidadConcentracion != ""){
            $arrayParametros += ['id_unidad_concentracion' => $idUnidadConcentracion];
            $arrayParametros += ['nombre_unidad_concentracion' => $nombreUnidadConcentracion];
        }
        
		if(isset($pesoBruto) && $pesoBruto != ""){                      
            $arrayParametros += ['peso_bruto' => $pesoBruto];
            $arrayParametros += ['id_unidad_peso_bruto' => $idUnidadPesoBruto];
            $arrayParametros += ['nombre_unidad_peso_bruto' => $nombreUnidadPesoBruto];
        }
       
        if(isset($fechaInspeccion) && $fechaInspeccion != ""){
		      $arrayParametros += ['fecha_inspeccion' => $fechaInspeccion];
		      $arrayParametros += ['hora_inspeccion' => $horaInspeccion];
		}
		
		if(isset($fechaTratamiento) && $fechaTratamiento != ""){
			$arrayParametros += ['fecha_tratamiento' => $fechaTratamiento];
		}
		
		if(isset($duracionTratamiento) && $duracionTratamiento != ""){
			$arrayParametros += ['duracion_tratamiento' => $duracionTratamiento];
		}
		
		if(isset($temperaturaTratamiento) && $temperaturaTratamiento != ""){
		    $arrayParametros += ['temperatura_tratamiento' => $temperaturaTratamiento];
		}
		
		if(isset($concentracionTratamiento) && $concentracionTratamiento != ""){
		    $arrayParametros += ['concentracion_tratamiento' => $concentracionTratamiento];
		}
		
		if(isset($productoQuimico) && $productoQuimico != ""){
		    $arrayParametros += ['producto_quimico' => $productoQuimico];
		}
		
		if(isset($codigoCentroAcopio) && $codigoCentroAcopio != ""){
		    $arrayParametros += ['id_area' => $idArea];
		    $arrayParametros += ['nombre_area' => $nombreArea];
		    $arrayParametros += ['codigo_centro_acopio' => $codigoCentroAcopio];
		    $arrayParametros += ['id_provincia_area' => $idProvinciaArea];
		    $arrayParametros += ['nombre_provincia_area' => $nombreProvinciaArea];
		}
		
		/*echo "<pre>";
		print_r($arrayParametros);
		echo "<pre>";*/
		$banderaAgregarProducto = true;		
		
		$this->modeloCertificadoFitosanitario = $this->lNegocioCertificadoFitosanitario->buscar($arrayParametros['id_certificado_fitosanitario']);
		$tipoSolicitud = $this->modeloCertificadoFitosanitario->getTipoCertificado();
		
        $verificarPaisDestino = $this->lNegocioLocalizacion->buscarPaisesPorIdLocalizacion($arrayParametros['id_pais_destino']);
        
        if($tipoSolicitud == "ornamentales"){
            if(isset($verificarPaisDestino->current()->codigo)){
              if($verificarPaisDestino->current()->codigo == "PA" || $verificarPaisDestino->current()->codigo == "RU"){	          
                  $banderaAgregarProducto = false;
              }          
            }
        }
        
		if($banderaAgregarProducto){
		
    		  $verificarExportadorProducto = $this->lNegocioExportadoresProductos->obtenerExportadoresProductos($arrayParametros);
    		  		  
    		  if(!isset($verificarExportadorProducto->current()->id_exportador_producto)){
    		      
    		      $validacion = "Exito";
    		      $resultado = "";
    		      
    		      $datosExportadorProducto = $this->lNegocioExportadoresProductos->guardar($arrayParametros);
    		      
    		      $filaExportadorProducto  = $this->generarFilaExportadorProducto($datosExportadorProducto);
    		      
    		      echo json_encode(array('validacion' => $validacion, 'resultado' => $resultado, 'filaExportadorProducto' => $filaExportadorProducto));
   		      
    		  }else{
    		      echo json_encode(array('validacion' => $validacion, 'resultado' => $resultado));    		      
    		  }
    		  
    	  }else{
    	      $validacion = "Fallo";
    	      $resultado = "No se puede agregar mas de un exportador y producto al destino " . $verificarPaisDestino->current()->nombre . ".";
    	      
    	      echo json_encode(array('validacion' => $validacion, 'resultado' => $resultado));
    	  }
		  
    }	
	/**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: ExportadoresProductos
     */
    public function editar()
    {
        $this->accion = "Editar ExportadoresProductos";
        $this->modeloExportadoresProductos = $this->lNegocioExportadoresProductos->buscar($_POST["id"]);
        require APP . 'CertificadoFitosanitario/vistas/formularioExportadoresProductosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - ExportadoresProductos
     */
    public function borrar()
    {
        $this->lNegocioExportadoresProductos->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - ExportadoresProductos
     */
    public function tablaHtmlExportadoresProductos($tabla)
    {
        $contador = 0;
        foreach ($tabla as $fila) {
            $this->itemsFiltrados[] = array(
                '<tr id="' . $fila['id_exportador_producto'] . '"
                    class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'CertificadoFitosanitario\exportadoresproductos"
                    data-opcion="editar" ondragstart="drag(event)" draggable="true"
                    data-destino="detalleItem">
                    <td>' . ++ $contador . '</td>
                    <td style="white - space:nowrap; "><b>' . $fila['id_exportador_producto'] . '</b></td>
                    <td>' . $fila['id_certificado_fitosanitario'] . '</td>
                    <td>' . $fila['identificador_exportador'] . '</td>
                    <td>' . $fila['razon_social_exportador'] . '</td>
                    </tr>'
            );
        }
    }

    /**
     * Método para actualizar datos de exportadores productos
     */
    public function actualizarDatosExportadoresProductos()
    {
        $idExportadorProducto = $_POST["idExportadorProducto"];
        $cantidadComercial = $_POST["cantidadComercial"];        
										 
        $pesoNeto = $_POST["pesoNeto"];
        $banderaPesoBruto = false;

        $arrayParametros = array(
            'idExportadorProducto' => $idExportadorProducto,
            'cantidadComercial' => $cantidadComercial,
									  
            'pesoNeto' => $pesoNeto
        );
        
        if(isset($_POST["pesoBruto"]) && $_POST["pesoBruto"] != ""){
            $pesoBruto = $_POST["pesoBruto"];
            $banderaPesoBruto = true;
            $arrayParametros += ['pesoBruto' => $pesoBruto];
        }
       
        $validacion = "Fallo";
        $banderaValidacion = true;
        $elemento = "";

        $datosExportadoresProductos = $this->lNegocioExportadoresProductos->verificarDatosCantidadesExportadoresProductos($arrayParametros);
        
        if($banderaPesoBruto){
            if ($pesoBruto <= 0) {
                $resultado = "Debe registrar una cantidad valida.";
                $banderaValidacion = false;
                $elemento = "peso_bruto";
            }            
            
            if ($pesoBruto > $datosExportadoresProductos->current()->peso_bruto) {
                $resultado = "El peso bruto no puede ser mayor al ingresado inicialmente.";
                $banderaValidacion = false;
                $elemento = "peso_bruto";
            }
        }
        
        if ($cantidadComercial <= 0) {
            $resultado = "Debe registrar una cantidad valida.";
            $banderaValidacion = false;
            $elemento = "cantidad_comercial";
        }
        
        if ($cantidadComercial > $datosExportadoresProductos->current()->cantidad_comercial) {
            $resultado = "Las cantidad comercial no puede ser mayor a la ingresada inicialmente.";
            $banderaValidacion = false;
            $elemento = "cantidad_comercial";
        }							   
			
        if ($pesoNeto <= 0) {
            $resultado = "Debe registrar una cantidad valida.";
            $banderaValidacion = false;
            $elemento = "peso_neto";
        }
        
        if ($pesoNeto > $datosExportadoresProductos->current()->peso_neto) {
            $resultado = "El peso neto no puede ser mayor al ingresado inicialmente.";
            $banderaValidacion = false;
            $elemento = "peso_neto";
        }

        if ($banderaValidacion) {

            $this->lNegocioExportadoresProductos->actualizarDatosCantidadesExportadoresProductos($arrayParametros);

            $validacion = "Exito";
            $resultado = "Registro actualizado con éxito.";

            echo json_encode(array(
                'resultado' => $resultado,
                'validacion' => $validacion
            ));
        } else {

            echo json_encode(array(
                'idExportadorProducto' => $datosExportadoresProductos->current()->id_exportador_producto,
                'elemento' => $elemento,
                'resultado' => $resultado,
                'validacion' => $validacion
            ));
        }
    }
	
	/**
	 * Método para agregar una nueva fila de exportadores productos
	 */
	public function generarFilaExportadorProducto($datosExportadorProducto)
	{
	    
	    $filaExportadorProducto = $this->lNegocioExportadoresProductos->buscar($datosExportadorProducto);
	    	    
	    $idExportadorProducto = $filaExportadorProducto->getIdExportadorProducto();
	    $identificadorExportador = $filaExportadorProducto->getIdentificadorExportador();
	    $razonSocialExportador = $filaExportadorProducto->getRazonSocialExportador();
	    $nombreProducto = $filaExportadorProducto->getNombreProducto();
	    $certificacionOrganica = $filaExportadorProducto->getCertificacionOrganica();
	    $cantidadComercial = $filaExportadorProducto->getCantidadComercial();
	    $pesoBruto = $filaExportadorProducto->getPesoBruto();
	    $pesoNeto = $filaExportadorProducto->getPesoNeto();
	    $nombreArea = $filaExportadorProducto->getNombreArea();
	    $codigoArea = $filaExportadorProducto->getCodigoCentroAcopio();
	    $fechaInspeccion = $filaExportadorProducto->getFechaInspeccion();
	    $horaInspeccion = $filaExportadorProducto->getHoraInspeccion();
	    $estadoExportadorProducto = $filaExportadorProducto->getEstadoExportadorProducto();
	    $observacionRevision = $filaExportadorProducto->getObservacionRevision();
	    $nombreTipoTratamiento = $filaExportadorProducto->getNombreTipoTratamiento();
	    $duracionTratamiento = $filaExportadorProducto->getDuracionTratamiento();
	    $nombreUnidadDuracion = $filaExportadorProducto->getNombreUnidadDuracion();
	    $fechaTaratamiento = $filaExportadorProducto->getFechaTratamiento();
	    $productoQuimico = $filaExportadorProducto->getProductoQuimico();
	    $nombreTratamiento = $filaExportadorProducto->getNombreTratamiento();
	    $temperaturaTratamiento = $filaExportadorProducto->getTemperaturaTratamiento();
	    $nombreUnidadTemperatura = $filaExportadorProducto->getNombreUnidadTemperatura();
	    $concentracionTratamiento = $filaExportadorProducto->getConcentracionTratamiento();
	    $nombreUnidadConcentracion = $filaExportadorProducto->getNombreUnidadConcentracion();
	    	    
	    if($estadoExportadorProducto == "Creado"){	   
	        $classE = "icono";
	        $onclikBorrar = "fn_eliminarDetalleExportadoresProductos('" . $idExportadorProducto . "'); return false;";
	    }
	    
	    $this->listaDetalles ='
                        <tr id="' . $idExportadorProducto . '">
                            <td>' . ($identificadorExportador != '' ? $identificadorExportador : ''). '</td>
                            <td>' . ($razonSocialExportador != '' ? $razonSocialExportador : '') . '</td>
                            <td>' . ($nombreProducto != '' ? $nombreProducto : '') . '</td>
                            <td>' . ($certificacionOrganica != '' ? $certificacionOrganica : 'N/A') . '</td>
                            <td align="center"><input name="cantidad_comercial" id="cantidad_comercial'. $idExportadorProducto .'" value="' . $cantidadComercial . '" type="text" class="validacionProducto" size="2" onchange="verificarCantidades(' . $idExportadorProducto . ', this.value, this.id, this.name)"></td>
                            <td align="center">' . ($pesoBruto != '' ? '<input name="peso_bruto" id="peso_bruto'. $idExportadorProducto .'" value="' . $pesoBruto . '" type="text" class="validacionProducto" size="2" onchange="verificarCantidades(' . $idExportadorProducto . ', this.value, this.id, this.name)">' : 'N/A' ) . '</td>
                            <td align="center"><input name="peso_neto" id="peso_neto'. $idExportadorProducto . '" value="' . $pesoNeto . '" type="text" class="validacionProducto" size="2" onchange="verificarCantidades(' . $idExportadorProducto . ', this.value, this.id, this.name)"></td>
                            <td>' . ($nombreArea != '' ? $nombreArea . ' ' . $codigoArea : 'N/A') . '</td>
                            <td>' . ($fechaInspeccion != '' ? $fechaInspeccion : 'N/A') . ' ' . ($horaInspeccion != '' ? $horaInspeccion : 'N/A'). '</td>
                            <td>' . ($estadoExportadorProducto != '' ? $estadoExportadorProducto : '') . '</td>
                            <td>' . ($observacionRevision != '' ? $observacionRevision : 'N/A') . '</td>
                            <td class="borrar"><button type="button" name="eliminar" class="' . $classE . '" onclick="'.  $onclikBorrar . '"/></td>
                            <td><button id="' . $idExportadorProducto .'" onclick="adicionalProducto(this.id)">Ver más</button></td>
                            </tr>
                            <tr id="resultadoInformacionProducto'. $idExportadorProducto .'" style="display:none;">
                            <td colspan="5">
                                <label>Tipo tratamiento: </label>' . ($nombreTipoTratamiento != "" ? $nombreTipoTratamiento : 'N/A') .
                                '</br><label>Duración tratamiento: </label>' . ($duracionTratamiento != "" ? $duracionTratamiento . ' ' . $nombreUnidadDuracion : 'N/A') .
                                '</br><label>Fecha tratamiento: </label>' . ($fechaTaratamiento != "" ? date('Y-m-d',strtotime($fechaTaratamiento)) : 'N/A') .
                                '</br><label>Producto químico: </label>' . ($productoQuimico != "" ? $productoQuimico : 'N/A') .
                                '</td>
                            <td colspan="4">
                                <label>Tratamiento: </label>' . ($nombreTratamiento != "" ? $nombreTratamiento : 'N/A') .
                                '</br><label>Temperatura: </label>' . ($temperaturaTratamiento != "" ? $temperaturaTratamiento . ' ' . $nombreUnidadTemperatura : 'N/A') .
                                '</br>'.
                                '</br><label>Concentración: </label>' . ($concentracionTratamiento != "" ? $concentracionTratamiento . ' ' . $nombreUnidadConcentracion : 'N/A') .
                                '</td>
                        </tr>';
	    
	    return $this->listaDetalles;
                                
	}
    
    /**
     * Método para verificar la cantidad comercial de producto en una reimpresion
     * */
    public function verificarCantidades(){
        
        $idExportadorProducto = $_POST["idExportadorProducto"];
        $cantidad = $_POST['cantidad'];
        $tipoCantidad = $_POST['tipoCantidad'];
        
        $arrayParametros = array(
            'idExportadorProducto' => $idExportadorProducto,
            'cantidad' => $cantidad,
            'tipoCantidad' => $tipoCantidad
        );
        //print_r($arrayParametros);
        $validacion = "Fallo";
        $resultado = "";
        
        $datosCantidadComercial = $this->lNegocioExportadoresProductos->verificarCantidades($arrayParametros);
        
        $valorCantidad = "";
        
        switch ($tipoCantidad){
            case "iCantidadComercial[]":
            case "cantidad_comercial":
                $valorCantidad = $datosCantidadComercial->current()->cantidad_comercial;
            break;
            case "iPesoBruto[]":
            case "peso_bruto":
                $valorCantidad = $datosCantidadComercial->current()->peso_bruto;
            break;
            case "iPesoNeto[]":
            case "peso_neto":
                $valorCantidad = $datosCantidadComercial->current()->peso_neto;
            break;
        }
        
        if(isset($valorCantidad)){
            if(($cantidad > 0) && trim($cantidad) != ""){
                $validacion = "Exito";
                echo json_encode(array('resultado' => $resultado,'validacion' => $validacion));               
                switch ($tipoCantidad){
                    case "cantidad_comercial":
                        $this->lNegocioExportadoresProductos->actualizarCantidades($arrayParametros);
                    break;
                    case "peso_bruto":
                        $this->lNegocioExportadoresProductos->actualizarCantidades($arrayParametros);
                    break;
                    case "peso_neto":
                        $this->lNegocioExportadoresProductos->actualizarCantidades($arrayParametros);
                    break;
                }
            }else{
                echo json_encode(array('resultado' => $resultado,
                    'validacion' => $validacion,
                    'cantidad' => $valorCantidad));
            }
        }
    }
    
}