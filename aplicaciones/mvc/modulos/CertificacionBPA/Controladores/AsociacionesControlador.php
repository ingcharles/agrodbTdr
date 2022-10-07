<?php
/**
 * Controlador Asociaciones
 *
 * Este archivo controla la lógica del negocio del modelo:  AsociacionesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-03-23
 * @uses    AsociacionesControlador
 * @package CertificacionBPA
 * @subpackage Controladores
 */
namespace Agrodb\CertificacionBPA\Controladores;

use Agrodb\CertificacionBPA\Modelos\AsociacionesLogicaNegocio;
use Agrodb\CertificacionBPA\Modelos\AsociacionesModelo;

use Agrodb\CertificacionBPA\Modelos\MiembrosAsociacionesLogicaNegocio;
use Agrodb\CertificacionBPA\Modelos\MiembrosAsociacionesModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class AsociacionesControlador extends BaseControlador
{

    private $lNegocioAsociaciones = null;
    private $modeloAsociaciones = null;
    
    private $lNegocioMiembrosAsociaciones = null;
    private $modeloMiembrosAsociaciones = null;

    private $accion = null;
    private $formulario = null;
    private $asociacion = null;
    private $urlPdf = null; 
    
    private $productosMiembrosAsociacion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        $this->lNegocioAsociaciones = new AsociacionesLogicaNegocio();
        $this->modeloAsociaciones = new AsociacionesModelo();
        
        $this->lNegocioMiembrosAsociaciones = new MiembrosAsociacionesLogicaNegocio();
        $this->modeloMiembrosAsociaciones = new MiembrosAsociacionesModelo();
        
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
        $query = "identificador_operador = '".$_SESSION['usuario']."'";
        
        $modeloAsociaciones = $this->lNegocioAsociaciones->buscarLista($query);
        $this->tablaHtmlAsociaciones($modeloAsociaciones);
        
        require APP . 'CertificacionBPA/vistas/listaAsociacionesVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nueva Asociación";
        $this->formulario = 'nuevo';
        
        $this->consultarAsociaciones($_SESSION['usuario']);
        $this->construirProductosMiembrosAsociacion(0);
        
        require APP . 'CertificacionBPA/vistas/formularioAsociacionesVista.php';
    }
    
    /**
     * Método para listar la información del usuario logueado
     */
    public function consultarAsociaciones($identificador)
    {
        $query = "identificador_operador = '".$identificador."'";
        
        $asociaciones = $this->lNegocioAsociaciones->buscarLista($query);
        
        if(isset($asociaciones->current()->id_asociacion)){
            $fila = $asociaciones->current();
            
            $this->asociacion = $fila->id_asociacion;
        }
    }

    /**
     * Método para registrar en la base de datos -Asociaciones
     */
    public function guardar()
    {
        if ($_POST["id_asociacion"] === ''){
            $estado = 'exito';
            $mensaje = 'Asociación generada con éxito';
            $contenido = '';
            
            $contenido = $this->lNegocioAsociaciones->guardar($_POST);
            
            echo json_encode(array('estado' => $estado, 'mensaje' => $mensaje, 'contenido' => $contenido));
            
        }else{
            $_POST["id_asociacion"] = $this->lNegocioAsociaciones->guardar($_POST);
            Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
        }
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Asociaciones
     */
    public function editar()
    {
        $this->accion = "Editar Asociación";
        $this->formulario = 'abrir';
        
        $this->modeloAsociaciones = $this->lNegocioAsociaciones->buscar($_POST['id']);
        $this->construirProductosMiembrosAsociacion($_POST['id']);
        require APP . 'CertificacionBPA/vistas/formularioAsociacionesVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Asociaciones
     */
    public function borrar()
    {
        $this->lNegocioAsociaciones->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Asociaciones
     */
    public function tablaHtmlAsociaciones($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_asociacion'] . '"
                        		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'CertificacionBPA/Asociaciones"
                        		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                        		  data-destino="detalleItem">
                		    <td>' . ++ $contador . '</td>
                            <td>' . $fila['identificador'] . '</td>
                		    <td style="white - space:nowrap; "><b>' . $fila['razon_social'] . '</b></td>
                            <td>' . date('Y-m-d',strtotime($fila['fecha_creacion'])) . '</td>
                    </tr>'
                );
            }
        }
    }
    
    /**
     * Método para validar el identificador de la asociación
     * */
    public function validarIdentificadorAsociacion($identificador)
    {
        $validacion = "Fallo";
        $nombre="El número de cédula o RUC ingresado está disponible.";
        
        $query = "identificador ilike '%".$identificador."%'";
        
        $identificadorAsociacion = $this->lNegocioAsociaciones->buscarLista($query);
        
        if(isset($identificadorAsociacion->current()->id_asociacion)){
            $datos = $identificadorAsociacion->current()->id_asociacion;
            
            if(strlen(trim($datos)) > 0){
                $validacion = "Exito";
                $nombre="El número de cédula o RUC ingresado ya se encuentra registrado.";
            }
        }
        
        echo json_encode(array('nombre' => $nombre, 'validacion' => $validacion));
    }
    
    /**
     * Método para validar la razón social de la asociación
     * */
    public function validarNombreAsociacion($razonSocial)
    {
        $validacion = "Fallo";
        $nombre="La razón social se encuentra disponible.";
        
        $query = "upper(trim(razon_social)) = upper('".trim($razonSocial)."')";
        
        $razonSocialAsociacion = $this->lNegocioAsociaciones->buscarLista($query);
        
        if(isset($razonSocialAsociacion->current()->id_asociacion)){
            $datos = $razonSocialAsociacion->current()->id_asociacion;
            
            if(strlen(trim($datos)) > 0){
                $validacion = "Exito";
                $nombre="La razón social ingresada ya se encuentra registrada.";
            }
        }
        
        echo json_encode(array('nombre' => $nombre, 'validacion' => $validacion));
    }
    
    /**
     * Método para construir input que muestre los datos de los productos de la asociacion
     * */
    public function construirProductosMiembrosAsociacion($idAsociacion)
    {
        $this->productosMiembrosAsociacion = "";
        $qProductosMiembrosAsociacion = $this->lNegocioMiembrosAsociaciones->obtenerProductosMiembrosAsociacionPorIdAsociacion($idAsociacion);
                
        if(isset($qProductosMiembrosAsociacion->current()->productos_miembros_asociacion)){
            $this->productosMiembrosAsociacion = '<textarea name="productos_miembros_asociacion" disabled="disabled">' . $qProductosMiembrosAsociacion->current()->productos_miembros_asociacion . '</textarea>';            
        }else{
            $this->productosMiembrosAsociacion = '<textarea name="productos_miembros_asociacion" disabled="disabled">S/R</textarea>';
        }
        
        
        return $this->productosMiembrosAsociacion;
        
    }
    
}