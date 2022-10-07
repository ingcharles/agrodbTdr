<?php
/**
 * Controlador Beneficiarios
 *
 * Este archivo controla la lógica del negocio del modelo:  BeneficiariosModelo y  Vistas
 *
 * @author  AGROCALIDAD
 * @date   2020-01-03
 * @uses    BeneficiariosControlador
 * @package RegistroEntregaProductos
 * @subpackage Controladores
 */
namespace Agrodb\RegistroEntregaProductos\Controladores;

use Agrodb\RegistroEntregaProductos\Modelos\BeneficiariosLogicaNegocio;
use Agrodb\RegistroEntregaProductos\Modelos\BeneficiariosModelo;

class BeneficiariosControlador extends BaseControlador
{

    private $lNegocioBeneficiarios = null;
    private $modeloBeneficiarios = null;

    private $accion = null;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        
        $this->lNegocioBeneficiarios = new BeneficiariosLogicaNegocio();
        $this->modeloBeneficiarios = new BeneficiariosModelo();
        
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
        $modeloBeneficiarios = $this->lNegocioBeneficiarios->buscarBeneficiarios();
        $this->tablaHtmlBeneficiarios($modeloBeneficiarios);
        require APP . 'RegistroEntregaProductos/vistas/listaBeneficiariosVista.php';
    }

    /**
     * Método para desplegar el formulario vacio
     */
    public function nuevo()
    {
        $this->accion = "Nuevo Beneficiarios";
        require APP . 'RegistroEntregaProductos/vistas/formularioBeneficiariosVista.php';
    }

    /**
     * Método para registrar en la base de datos -Beneficiarios
     */
    public function guardar()
    {
        $this->lNegocioBeneficiarios->guardar($_POST);
    }

    /**
     * Obtenemos los datos del registro seleccionado para editar - Tabla: Beneficiarios
     */
    public function editar()
    {
        $this->accion = "Editar Beneficiarios";
        $this->modeloBeneficiarios = $this->lNegocioBeneficiarios->buscar($_POST["id"]);
        require APP . 'RegistroEntregaProductos/vistas/formularioBeneficiariosVista.php';
    }

    /**
     * Método para borrar un registro en la base de datos - Beneficiarios
     */
    public function borrar()
    {
        $this->lNegocioBeneficiarios->borrar($_POST['elementos']);
    }

    /**
     * Construye el código HTML para desplegar la lista de - Beneficiarios
     */
    public function tablaHtmlBeneficiarios($tabla)
    {
        {
            $contador = 0;
            foreach ($tabla as $fila) {
                $this->itemsFiltrados[] = array(
                    '<tr id="' . $fila['id_beneficiario'] . '"
                    		  class="item" data-rutaAplicacion="' . URL_MVC_FOLDER . 'RegistroEntregaProductos\beneficiarios"
                    		  data-opcion="editar" ondragstart="drag(event)" draggable="true"
                    		  data-destino="detalleItem">
                    		  <td>' . ++ $contador . '</td>
                    	<td style="white - space:nowrap; "><b>' . $fila['id_beneficiario'] . '</b></td>
                        <td>' . $fila['identificador'] . '</td>
                        <td>' . $fila['nombre'] . '</td>
                        <td>' . $fila['apellido'] . '</td>
                    </tr>'
                );
            }
        }
    }
    
    /**
     * Método para el nombre del beneficiario
     * */
    public function obtenerNombreBeneficiario($identificador)
    {
        $validacion = "Fallo";
        $nombre="El número de cédula no existe.";
        
        $query = "identificador = '$identificador'";
        
        $usuario = $this->lNegocioBeneficiarios->buscarLista($query);
        
        if($usuario->current() != null){
            $nombre = $usuario->current()->nombre;
            $apellido = $usuario->current()->apellido;
            $direccion = $usuario->current()->direccion;
            $telefono = $usuario->current()->telefono;
            $correo = $usuario->current()->correo_electronico;
            
            $validacion = "Exito";
            
            echo json_encode(array('nombre' => $nombre, 'apellido' => $apellido,'direccion' => $direccion,
                                   'telefono' => $telefono,'correo' => $correo, 'validacion' => $validacion));
        }else{
            echo json_encode(array('nombre' => $nombre, 'validacion' => $validacion));
        }
    }
}
