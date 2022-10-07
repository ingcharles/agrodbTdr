<?php
/**
 * Lógica del negocio de SecuenciaRevisionModelo
 *
 * Este archivo se complementa con el archivo SecuenciaRevisionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-21
 * @uses    SecuenciaRevisionLogicaNegocio
 * @package DossierPecuario
 * @subpackage Modelos
 */
namespace Agrodb\DossierPecuario\Modelos;

use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;

use Agrodb\Catalogos\Modelos\LocalizacionLogicaNegocio;

use Agrodb\GUath\Modelos\FichaEmpleadoLogicaNegocio;

use Agrodb\DossierPecuario\Modelos\IModelo;

class SecuenciaRevisionLogicaNegocio implements IModelo
{

    private $modeloSecuenciaRevision = null;
    private $lNegocioOperadores = null;
    private $lNegocioLocalizacion = null;
    
    private $lNegocioFichaEmpleado = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloSecuenciaRevision = new SecuenciaRevisionModelo();
        
        $this->lNegocioOperadores = new OperadoresLogicaNegocio();
        $this->lNegocioLocalizacion = new LocalizacionLogicaNegocio();
        
        $this->lNegocioFichaEmpleado = new FichaEmpleadoLogicaNegocio();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        switch ($datos['estado_revision']) {
            case 'Creado':
                $datos['accion'] = "El usuario ha creado una solicitud";
                break;

            case 'pago':
                $datos['accion'] = "El usuario remitió la solicitud a pago";
                break;

            case 'Recibido':
                $datos['accion'] = "Financiero remitió la solicitud a Registros";
                break;
                
            case 'EnTramite':
                if($datos['perfil'] == 'Operador'){
                    $datos['accion'] = "El Usuario remitió la respuesta a las observaciones";
                } else{
                    $datos['accion'] = "El Administrador asignó la solicitud a un técnico";
                }
                break;

            case 'Subsanacion':
            case 'Aprobado':
            case 'Rechazado':
                $datos['accion'] = "El Técnico revisó la solicitud";
                break;

            default:
                $datos['accion'] = "Ha ocurrido un error";
                break;
        }

        $tablaModelo = new SecuenciaRevisionModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdSecuenciaRevision() != null && $tablaModelo->getIdSecuenciaRevision() > 0) {
            return $this->modeloSecuenciaRevision->actualizar($datosBd, $tablaModelo->getIdSecuenciaRevision());
        } else {
            unset($datosBd["id_secuencia_revision"]);
            return $this->modeloSecuenciaRevision->guardar($datosBd);
        }
    }

    /**
     * Borra el registro actual
     *
     * @param
     *            string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloSecuenciaRevision->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return SecuenciaRevisionModelo
     */
    public function buscar($id)
    {
        return $this->modeloSecuenciaRevision->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloSecuenciaRevision->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloSecuenciaRevision->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarSecuenciaRevision()
    {
        $consulta = "SELECT * FROM " . $this->modeloSecuenciaRevision->getEsquema() . ". secuencia_revision";
        return $this->modeloSecuenciaRevision->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Función para guardar el histórico de cambios de estado / reporte
     */
    public function guardarHistoricoUsuario($idSolicitud, $estadoSolicitud)
    {
        $consulta = "identificador='".$_SESSION['usuario']."'";

        $usuario = $this->lNegocioOperadores->buscarLista($consulta);
        $fila = $usuario->current();
        
        $datosProvincia = $this->lNegocioLocalizacion->buscarProvinciaXNombre($fila->provincia);
        
        $arrayParametros = array(   'id_solicitud' =>  $idSolicitud,
                                    'identificador_ejecutor' =>  $fila->identificador,
                                    'nombre_ejecutor' =>  $fila->razon_social,
                                    'perfil' =>  'Operador',
                                    'id_provincia' => $datosProvincia->current()->id_localizacion,
                                    'provincia' => $fila->provincia,
                                    'estado_revision' =>  $estadoSolicitud
                                );
        
        
        $this->guardar($arrayParametros);
    }
    
    /**
     * Función para guardar el histórico de cambios de estado / reporte
     */
    public function guardarHistoricoTecnico($idSolicitud, $estadoSolicitud, $observacion)
    {
        $arrayParametros = array(   'id_solicitud' =>  $idSolicitud,
                                    'identificador_ejecutor' =>  $_SESSION['usuario'],
                                    'nombre_ejecutor' =>  $_SESSION['datosUsuario'],
                                    'perfil' =>  'Tecnico',
                                    'id_provincia' => $_SESSION['idProvincia'],
                                    'provincia' => $_SESSION['nombreProvincia'],
                                    'estado_revision' =>  $estadoSolicitud,
                                    'observacion_revision' => $observacion
                                );
        
        $this->guardar($arrayParametros);
    }
    
    /**
     * Función para guardar el histórico de cambios de estado / reporte
     */
    public function guardarHistoricoAdministrador($idSolicitud, $estadoSolicitud, $identificadorTecnico)
    {
        $usuario = $this->lNegocioFichaEmpleado->buscar($identificadorTecnico);
        
        
        $arrayParametros = array(   'id_solicitud' =>  $idSolicitud,
                                    'identificador_ejecutor' =>  $_SESSION['usuario'],
                                    'nombre_ejecutor' =>  $_SESSION['datosUsuario'],
                                    'perfil' =>  'Administrador',
                                    'id_provincia' => $_SESSION['idProvincia'],
                                    'provincia' => $_SESSION['nombreProvincia'],
                                    'estado_revision' =>  $estadoSolicitud,
                                    'identificador_tecnico_asignado' => $identificadorTecnico,
                                    'nombre_tecnico_asignado' => $usuario->nombre . ' ' . $usuario->apellido
                                );
        
        $this->guardar($arrayParametros);
    }
}