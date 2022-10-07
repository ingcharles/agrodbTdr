<?php
/**
 * Controlador MiembrosAsociaciones
 *
 * Este archivo controla la lógica del negocio del modelo:  MiembrosAsociacionesModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-03-23
 * @uses    MiembrosAsociacionesControlador
 * @package CertificacionBPA
 * @subpackage Controladores
 */
namespace Agrodb\CertificacionBPA\Controladores;

use Agrodb\CertificacionBPA\Modelos\MiembrosAsociacionesLogicaNegocio;
use Agrodb\CertificacionBPA\Modelos\MiembrosAsociacionesModelo;

use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperadoresModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class MiembrosAsociacionesControlador extends BaseControlador
{
    private $lNegocioMiembrosAsociaciones = null;
    private $modeloMiembrosAsociaciones = null;
    
    private $lNegocioOperadores = null;
    private $modeloOperadores = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        
        $this->lNegocioMiembrosAsociaciones = new MiembrosAsociacionesLogicaNegocio();
        $this->modeloMiembrosAsociaciones = new MiembrosAsociacionesModelo();
        
        $this->lNegocioOperadores = new OperadoresLogicaNegocio();
        $this->modeloOperadores = new OperadoresModelo();
        
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
        $modeloMiembrosAsociaciones = $this->lNegocioMiembrosAsociaciones->buscarMiembrosAsociaciones();
        $this->tablaHtmlMiembrosAsociaciones($modeloMiembrosAsociaciones);
        require APP . 'CertificacionBPA/vistas/listaMiembrosAsociacionesVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo MiembrosAsociaciones";
        require APP . 'CertificacionBPA/vistas/formularioMiembrosAsociacionesVista.php';
    }

    /**
     * Método para registrar en la base de datos -MiembrosAsociaciones
     */
    public function guardar()
    {
        $this->lNegocioMiembrosAsociaciones->guardar($_POST);
        Mensajes::exito(Constantes::GUARDADO_CON_EXITO);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: MiembrosAsociaciones
     */
    public function editar()
    {
        $this->accion = "Editar MiembrosAsociaciones";
        $this->modeloMiembrosAsociaciones = $this->lNegocioMiembrosAsociaciones->buscar($_POST["id"]);
        require APP . 'CertificacionBPA/vistas/formularioMiembrosAsociacionesVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - MiembrosAsociaciones
     */
    public function borrar()
    {
        $this->lNegocioMiembrosAsociaciones->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - MiembrosAsociaciones
     */
    public function tablaHtmlMiembrosAsociaciones($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_miembro_asociacion'] . '"
                		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'CertificacionBPA\miembrosasociaciones"
                		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                		  data-destino="detalleItem">
            		    <td>' . ++ $contador . '</td>
            		    <td style="white - space:nowrap; "><b>' . $fila['id_miembro_asociacion'] . '</b></td>
                        <td>' . $fila['id_asociacion'] . '</td>
                        <td>' . $fila['fecha_creacion'] . '</td>
                        <td>' . $fila['identificador_miembro'] . '</td>
                    </tr>'
                );
            }
        }
    }
    
    /**
     * Método para listar los miembros registrados de una asociación
     */
    public function construirDetalleMiembros($idAsociacion)
    {
        $query = "id_asociacion = $idAsociacion";
        
        $listaDetalles = $this->lNegocioMiembrosAsociaciones->buscarLista($query);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['identificador_miembro'] != '' ? $fila['identificador_miembro'] : ''). '</td>
                            <td>' . ($fila['nombre_miembro'] != '' ? $fila['nombre_miembro'] : '') . '</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" onclick="fn_eliminarDetalle(' . $fila['id_miembro_asociacion'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
    
    /**
     * Método para validar el identificador del miembro de la asociación
     * */
    public function validarIdentificadorMiembro($identificador)
    {
        $validacion = "Fallo";
        $nombre="El número de cédula o RUC ingresado está disponible.";
        
        $query = "identificador_miembro ilike '%".$identificador."%'";
        
        $identificadorMiembro= $this->lNegocioMiembrosAsociaciones->buscarLista($query);
        
        if(isset($identificadorMiembro->current()->id_asociacion)){
            $datos = $identificadorMiembro->current()->id_asociacion;
            
            //El operador ya está registrado en una asociación
            if(strlen(trim($datos)) > 0){
                $validacion = "Exito";
                $nombre="El número de cédula o RUC ingresado ya se encuentra registrado en una asociación.";
            }
        }
        
        echo json_encode(array('nombre' => $nombre, 'validacion' => $validacion));
    }
    
    /**
     * Método para el nombre del usuario
     * */
    public function obtenerNombreOperador($identificador)
    {
        $validacion = "Fallo";
        $nombre="El operador no se encuentra registrado o no dispone de las operaciones permitidas para el registro.";
        
        $usuario = $this->lNegocioOperadores->obtenerRazonSocialOperadoresXOperacion($identificador);
        
        if(isset($usuario->current()->razon_social)){
            $datos = $usuario->current()->razon_social;
            
            if(strlen(trim($datos)) > 0){
                $nombre = $datos;
                $validacion = "Exito";
            }
        }
        
        echo json_encode(array('nombre' => $nombre, 'validacion' => $validacion));
    }
    
    /**
     * Método para buscar y desplegar la información de un operador resgistrado en la asociación
     * */
    public function buscarMiembroAsociacion(){
        
        $idAsociacion =  $_POST["idAsociacion"];
        $identificadorMiembro = $_POST["identificador"];
        $nombreMiembro = $_POST["nombre"];
        
        $query = "id_asociacion = $idAsociacion";
        
        if($identificadorMiembro != null){
            $query .= " and identificador_miembro ilike '%$identificadorMiembro%'";
        }else if($nombreMiembro != null){
            $query .= " and upper(nombre_miembro) ilike upper('%$nombreMiembro%')";
        }
        
        $listaDetalles = $this->lNegocioMiembrosAsociaciones->buscarLista($query);
        
        $i=1;
        
        $this->listaDetalles = '<table>';
        
        foreach ($listaDetalles as $fila) {
            
            $this->listaDetalles .='
                        <tr>
                            <td>' . $i++. '</td>
                            <td>' . ($fila['identificador_miembro'] != '' ? $fila['identificador_miembro'] : ''). '</td>
                            <td>' . ($fila['nombre_miembro'] != '' ? $fila['nombre_miembro'] : '') . '</td>
                            <td class="borrar"><button type="button" name="eliminar" id="eliminar" class="icono" onclick="fn_eliminarDetalle(' . $fila['id_miembro_asociacion'] . '); return false;"/></td>
                        </tr>';
        }
        
        $this->listaDetalles .= '</table>';
        
        echo $this->listaDetalles;
    }
}