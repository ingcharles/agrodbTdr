<?php

/**
 * Lógica del negocio de  SolicitudesModelo
 *
 * Este archivo se complementa con el archivo   SolicitudesControlador.
 * https://docs.zendframework.com/zend-db/sql/
 * 
 * @author DATASTAR
 * @uses       SolicitudesLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Core\Excepciones\GuardarExcepcion;
use Agrodb\Laboratorios\Modelos\IModelo;
use Exception;
use Agrodb\FinancieroAutomatico\Modelos\FinancieroCabeceraLogicaNegocio;
use Agrodb\FinancieroAutomatico\Modelos\FinancieroDetalleLogicaNegocio;
use Agrodb\Financiero\Modelos\ClientesLogicaNegocio;
use Agrodb\Usuarios\Modelos\UsuariosPerfilesLogicaNegocio;
use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class SolicitudesLogicaNegocio implements IModelo
{

    private $modeloSolicitud = null;
    private $idSolicitud;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloSolicitud = new SolicitudesModelo();
        $this->modelo = new SolicitudesModelo();
    }

    /**
     * Creamos una solicitud en blanco y retornamos el id al modulo externo que solicitó
     * @param array $datos
     * @return type
     * @throws Exception
     */
    public function crear(Array $datos)
    {
        try {

            $tablaModelo = new SolicitudesModelo($datos);
            //verificamos si la solicitud del modulo/proceso que la solicita ya fue creada
            $where = array("modulo_externo" => $tablaModelo->getModuloExterno(), "id_proceso_externo" => $tablaModelo->getIdProcesoExterno());
            $resultado = $this->buscarLista($where);

            if ($resultado->count() <= 0)
            {

                $datosBd = $tablaModelo->getPrepararDatos();
                unset($datosBd["id_solicitud"]);
                return $this->modeloSolicitud->guardar($datosBd);
            } else
            {
                $fila = $resultado->current();
                return $fila['id_solicitud'];
            }
        } catch (GuardarExcepcion $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Creamos una solicitud en blanco 
     * @param array $datos
     * @return type
     * @throws Exception
     */
    public function crearSolicitudMultiusuario(Array $datos)
    {
        try {
            $tablaModelo = new SolicitudesModelo($datos);
            $datosBd = $tablaModelo->getPrepararDatos();
            unset($datosBd["id_solicitud"]);
            return $this->modeloSolicitud->guardar($datosBd);
        } catch (GuardarExcepcion $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        try {
            $datos['muestreo_nacional'] = ($datos['muestreo_nacional'] == '') ? 'NO' : $datos['muestreo_nacional'];
            $this->modeloSolicitud = new SolicitudesModelo($datos);
            $proceso = $this->modeloSolicitud->getAdapter()
                    ->getDriver()
                    ->getConnection();
            if (!$proceso->beginTransaction())
            {
                throw new \Exception('No se pudo iniciar la transacción en: Guardar solicitud');
            }
            $this->crearSolicitud();
            if (isset($datos['muestrasDerivar']))
            {
                $this->actualizarDerivacionMuestras($datos['muestrasDerivar']);
                $this->notificarCliente($datos);
            }
            if (isset($datos['muestrasConfirmar']))
            {
                $this->actualizarConfirmacionMuestras($datos['muestrasConfirmar']);
                $this->notificarCliente($datos);
            }
            $proceso->commit();
            return true;
        } catch (GuardarExcepcion $ex) {
            $proceso->rollback();
            throw new \Exception($ex->getMessage());
        } /* catch (Exception $exc) {
          $proceso->rollback();
          throw new \Exception($exc->getMessage());
          } */
    }

    public function notificarCliente($datos)
    {
        if (isset($datos['notificarCliente']))
        {
            if ($datos['notificarCliente'] == 'SI')
            {
                //Notificar al cliente de la solicitud original
                $notificar = new \Agrodb\Correos\Modelos\CorreosLogicaNegocio();
                $notificar->notificar($datos['id_solicitud'], $datos["usuario_guia_sol_principal"], $datos['usuario_guia'], $datos['tipo_solicitud']);
            }
        }
    }

    /**
     * Actualiza los registros de recepcion_muestras
     * @param type $muestrasDerivar
     */
    public function actualizarDerivacionMuestras($muestrasDerivar)
    {
        $idsMuestras = explode(',', $muestrasDerivar);
        foreach ($idsMuestras as $idRecepcionMuestra)
        {
            $datosRM = array(
                'id_solicitud_derivacion' => $this->idSolicitud,
                'derivada' => 'SI'
            );
            $statement = $this->modeloSolicitud->getAdapter()
                    ->getDriver()
                    ->createStatement();
            $sqlActualizar = $this->modeloSolicitud->actualizarSql('recepcion_muestras', $this->modeloSolicitud->getEsquema());
            $sqlActualizar->set($datosRM);
            $sqlActualizar->where(array('id_recepcion_muestras' => $idRecepcionMuestra));
            $sqlActualizar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
            $statement->execute();
        }
    }

    /**
     * Actualiza los registros de recepcion_muestras
     * @param type $muestrasDerivar
     */
    public function actualizarConfirmacionMuestras($muestrasDerivar)
    {
        $idsMuestras = explode(',', $muestrasDerivar);
        foreach ($idsMuestras as $idRecepcionMuestra)
        {
            $datosRM = array(
                'id_solicitud_confirmacion' => $this->idSolicitud,
                'por_confirmar' => 'SI'
            );
            $statement = $this->modeloSolicitud->getAdapter()
                    ->getDriver()
                    ->createStatement();
            $sqlActualizar = $this->modeloSolicitud->actualizarSql('recepcion_muestras', $this->modeloSolicitud->getEsquema());
            $sqlActualizar->set($datosRM);
            $sqlActualizar->where(array('id_recepcion_muestras' => $idRecepcionMuestra));
            $sqlActualizar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
            $statement->execute();
        }
    }

    /**
     * Finalizar la solicitud
     * La información para la orden de pago se guarda en 
     * - g_financiero_automatico.financiero_cabecera -> Cabecera
     * - g_financiero_automatico.financiero_detalle -> Detalle
     * Una vez insertados los datos, se dispone de un cron el cual genera la orden de pago.
     *
     * @param array $datos
     * @return int
     */
    public function guardarFinalizar(Array $datos)
    {
        try {
            $this->modeloSolicitud = new SolicitudesModelo($datos);
            $proceso = $this->modeloSolicitud->getAdapter()
                    ->getDriver()
                    ->getConnection();
            if (!$proceso->beginTransaction())
            {
                throw new \Exception('No se pudo iniciar la transacción en: Guardar solicitud');
            }

            if ($this->modeloSolicitud->getIdSolicitud() != null && $this->modeloSolicitud->getIdSolicitud() > 0)
            {
                $idPersona = null;
                //Cuando no tiene exoneracion debe facturar, enviar datos a financiero
                if ($this->modeloSolicitud->getExoneracion() == 'NO')
                {
                    //buscar el usuario recaudador de la provincia 
                    $lNUsuarioLaboratorio = new UsuarioLaboratorioLogicaNegocio();
                    $buscaUsuarioLaboratorio = $lNUsuarioLaboratorio->buscarRecaudadorDeProvincia($datos['id_laboratorios_provincia']);
                    $usuarioLaboratorio = $buscaUsuarioLaboratorio->current();
                    if (isset($usuarioLaboratorio->id_usuario_laboratorio))
                    {
                        // REGISTRAR LOS DATOS DE LA FACTURA
                        if (isset($datos['opFactTercero']))
                        {
                            if ($datos['opFactTercero'] == '1') //a nombre de un tercero
                            {
                                $lNegocioClientes = new ClientesLogicaNegocio();
                                //Obtener datos del cliente
                                $lNegocioPersonas = new PersonasLogicaNegocio();
                                $buscaCliente = $lNegocioPersonas->buscarCliente($datos['ci_ruc']);
                                $cliente = $buscaCliente->current();
                                if (empty($cliente->identificador))  //si el cliente no existe debe registrarse en cliente y persona
                                {
                                    //registrar en cliente
                                    $datosCliente = array(
                                        'identificador' => $datos['ci_ruc'],
                                        'tipo_identificacion' => $datos['tipo_identificacion'],
                                        'razon_social' => $datos['nombre'],
                                        'direccion' => $datos['direccion'],
                                        'telefono' => $datos['telefono'],
                                        'correo' => $datos['email']
                                    );
                                    $statement = $this->modeloSolicitud->getAdapter()
                                            ->getDriver()
                                            ->createStatement();
                                    $sqlInsertar = $this->modeloSolicitud->guardarSql('clientes', 'g_financiero');
                                    $sqlInsertar->columns($lNegocioClientes->columnas());
                                    $sqlInsertar->values($datosCliente, $sqlInsertar::VALUES_MERGE);
                                    $sqlInsertar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                                    $statement->execute();
                                    //registrar en persona
                                    $datosPersona = array(
                                        'identificador' => $datos['ci_ruc'],
                                        'ci_ruc' => $datos['ci_ruc'],
                                        'nombre' => $datos['nombre'],
                                        'direccion' => $datos['direccion'],
                                        'telefono' => $datos['telefono'],
                                        'email' => $datos['email'],
                                        'contacto_proforma' => NULL,
                                        'telefono_proforma' => NULL
                                    );
                                    $statement = $this->modeloSolicitud->getAdapter()
                                            ->getDriver()
                                            ->createStatement();
                                    /* ver: https://docs.zendframework.com/zend-db/sql/ */
                                    $sqlInsertar = $this->modeloSolicitud->guardarSql('personas', $this->modeloSolicitud->getEsquema());
                                    $sqlInsertar->columns($lNegocioPersonas->columnas());
                                    $sqlInsertar->values($datosPersona, $sqlInsertar::VALUES_MERGE);
                                    $sqlInsertar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                                    $statement->execute();
                                    $idPersona = $this->modeloSolicitud->adapter->driver->getLastGeneratedValue($this->modeloSolicitud->getEsquema() . '.personas_id_persona_seq');
                                    if (!$idPersona)
                                    {
                                        throw new \Exception('No se registo los datos en la tabla personas');
                                    }
                                    $identificador = $datos['ci_ruc'];
                                } else if (empty($cliente->id_persona))  //si el cliente existe pero no en persona
                                {
                                    $datosPersona = array(
                                        'identificador' => $cliente->identificador,
                                        'ci_ruc' => $cliente->identificador,
                                        'nombre' => $cliente->razon_social,
                                        'direccion' => $cliente->direccion,
                                        'telefono' => $cliente->telefono,
                                        'email' => $cliente->correo,
                                        'contacto_proforma' => NULL,
                                        'telefono_proforma' => NULL
                                    );
                                    $statement = $this->modeloSolicitud->getAdapter()
                                            ->getDriver()
                                            ->createStatement();
                                    $sqlInsertar = $this->modeloSolicitud->guardarSql('personas', $this->modeloSolicitud->getEsquema());
                                    $sqlInsertar->columns($lNegocioPersonas->columnas());
                                    $sqlInsertar->values($datosPersona, $sqlInsertar::VALUES_MERGE);
                                    $sqlInsertar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                                    $statement->execute();
                                    $idPersona = $this->modeloSolicitud->adapter->driver->getLastGeneratedValue($this->modeloSolicitud->getEsquema() . '.personas_id_persona_seq');
                                    if (!$idPersona)
                                    {
                                        throw new \Exception('No se registró los datos en la tabla personas');
                                    }
                                    $identificador = $cliente->identificador;
                                } else  //si la persona existe
                                {
                                    $identificador = $cliente->identificador;
                                    $idPersona = $cliente->id_persona;
                                }
                            } else
                            {
                                if ($datos['usuarioInterno'] === false)
                                {
                                    //si la factura va a nombre del usuario del sistema
                                    //solo registrar en g_financiero.clientes y no en g_laboratorios.personas
                                    //no se requiere el id_persona ya que existe el campo g_laboratorios.solicitudes.usuario_guia
                                    $lNegocioClientes = new ClientesLogicaNegocio();
                                    //Obtener datos del cliente
                                    $lNegocioPersonas = new PersonasLogicaNegocio();
                                    $buscaCliente = $lNegocioPersonas->buscarCliente($datos['identificador']);
                                    $cliente = $buscaCliente->current();
                                    if (empty($cliente->identificador))  //si el cliente no existe debe registrarse en cliente
                                    {
                                        //obtener los datos del usuario logueado de la tabla g_operadores.operadores
                                        $buscaOperador = new UsuariosPerfilesLogicaNegocio();
                                        $resultadoOperador = $buscaOperador->buscarDatosoperador($datos['identificador']);
                                        $fila = $resultadoOperador->current();
                                        if (!empty($fila))
                                        {
                                            if (strlen($fila->identificador) == 13)
                                            {
                                                $tipo_identificacion = Constantes::tipo_identificacion()->RUC;
                                            } else
                                            {
                                                $tipo_identificacion = Constantes::tipo_identificacion()->CEDULA;
                                            }
                                            //registrar en cliente
                                            $datosCliente = array(
                                                'identificador' => $fila->identificador,
                                                'tipo_identificacion' => $tipo_identificacion,
                                                'razon_social' => $fila->nombre_representante . " " . $fila->apellido_representante,
                                                'direccion' => $fila->direccion,
                                                'telefono' => $fila->telefono_uno,
                                                'correo' => $fila->correo
                                            );
                                            $statement = $this->modeloSolicitud->getAdapter()
                                                    ->getDriver()
                                                    ->createStatement();
                                            $sqlInsertar = $this->modeloSolicitud->guardarSql('clientes', 'g_financiero');
                                            $sqlInsertar->columns($lNegocioClientes->columnas());
                                            $sqlInsertar->values($datosCliente, $sqlInsertar::VALUES_MERGE);
                                            $sqlInsertar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                                            $statement->execute();
                                            $identificador = $datos['ci_ruc'];
                                        }
                                    }
                                    $identificador = $datos['identificador']; //usuario activo
                                } else
                                {
                                    echo Mensajes::fallo("El usuario Interno debe facturar a nombre de una tercera persona.");
                                    exit();
                                }
                            }
                        }

                        //buscar datos del cliente
                        // Guardar los datos en g_financiero_automatico.financiero_cabecera y g_financiero_automatico.financiero_detalle
                        $total = $this->buscarTotalSolicitud($this->modeloSolicitud->getIdSolicitud());
                        $datosFinan = array(
                            'total_pagar' => $total,
                            'tipo_solicitud' => Constantes::TIPO_SOLICITUD_FC,
                            'estado' => Constantes::ESTADO_FC,
                            'tabla_modulo' => Constantes::TABLA_MODULO_FC,
                            'id_solicitud_tabla' => $this->modeloSolicitud->getIdSolicitud(),
                            'provincia_firmante' => $usuarioLaboratorio->identificador, //cedula del usuario receptor
                            'id_provincia_firmante' => $usuarioLaboratorio->id_localizacion,
                            'identificador_operador' => $identificador  //cedula del cliente
                        );
                        $lNegocioFinanciero = new FinancieroCabeceraLogicaNegocio;
                        $statement = $this->modeloSolicitud->getAdapter()
                                ->getDriver()
                                ->createStatement();
                        $sqlInsertar = $this->modeloSolicitud->guardarSql('financiero_cabecera', 'g_financiero_automatico');
                        $sqlInsertar->columns($lNegocioFinanciero->columnas());
                        $sqlInsertar->values($datosFinan, $sqlInsertar::VALUES_MERGE);
                        $sqlInsertar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                        $statement->execute();
                        $idFinancieroCabecera = $this->modeloSolicitud->adapter->driver->getLastGeneratedValue('g_financiero_automatico' . '.financiero_cabecera_id_financiero_cabecera_seq');
                        if (!$idFinancieroCabecera)
                        {
                            throw new \Exception('No se registro los datos en la tabla financiero_cabecera');
                        }

                        $lNegociodetalleFinanciero = new FinancieroDetalleLogicaNegocio;
                        $sqlInsertar = null;
                        $statement = $this->modeloSolicitud->getAdapter()
                                ->getDriver()
                                ->createStatement();
                        $lNSolicitudes = new DetalleSolicitudesLogicaNegocio();
                        $buscaValores = $lNSolicitudes->listaDetalleSolicitudes($this->modeloSolicitud->getIdSolicitud());
                        foreach ($buscaValores as $fila)
                        {
                            //$iva = round($fila->valor * ("0." . Constantes::IVA), 2);
                            //$total = round($fila->valor, 2) + $iva;     //total del valor de una muestra
                            //habilitar lo siguiente si debe enviarse el total por la cantidad
                            $subtotal = round($fila->total_muestras * $fila->valor, 2);    //subtotal igual calculado que la solicitud
                            $iva = round($subtotal * ("0." . Constantes::IVA), 2);
                            $total = $subtotal + $iva;
                            $detalleFinan = array(
                                'id_financiero_cabecera' => $idFinancieroCabecera,
                                'id_servicio' => $fila->id_servicio,
                                'concepto_orden' => $fila->rama_nombre,
                                'cantidad' => $fila->total_muestras,
                                'precio_unitario' => $fila->valor,
                                'descuento' => 0, //OJO! Descuento del servicio en el caso de tener descuento, caso contrario 0
                                'iva' => $iva,
                                'total' => $total
                            );
                            /* ver: https://docs.zendframework.com/zend-db/sql/ */
                            $sqlInsertar = $this->modeloSolicitud->guardarSql('financiero_detalle', 'g_financiero_automatico');
                            $sqlInsertar->columns($lNegociodetalleFinanciero->columnas());
                            $sqlInsertar->values($detalleFinan, $sqlInsertar::VALUES_MERGE);
                            $sqlInsertar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                            $statement->execute();
                        }

                        //actualizar distribucion_muestra en solicitudes
                        $lNegocioLaboratoriosProvincia = new LaboratoriosProvinciaLogicaNegocio();
                        $buscaLaboratorioProvincia = $lNegocioLaboratoriosProvincia->buscarDisMuestraLabProvincia($datos['id_laboratorios_provincia']);
                        $fila = $buscaLaboratorioProvincia->current();
                        $datosSolicitud = array(
                            'id_distribucion_muestra' => $fila->id_distribucion_muestra
                        );
                        $statement = $this->modeloSolicitud->getAdapter()
                                ->getDriver()
                                ->createStatement();
                        $sqlActualizar = $this->modeloSolicitud->actualizarSql('solicitudes', $this->modeloSolicitud->getEsquema());
                        $sqlActualizar->set($datosSolicitud);
                        $sqlActualizar->where(array('id_solicitud' => $this->modeloSolicitud->getIdSolicitud()));
                        $sqlActualizar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                        $statement->execute();
                    } else
                    {
                        echo Mensajes::fallo("No existe un usuario recaudador para la provincia seleccionada. Favor comunicarse con el administrador del sistema.");
                        exit();
                    }
                } else
                {
                    $identificador = $datos['identificador']; //usuario activo
                }

                // Actualizar datos de la solicitud
                $datosSolicitud = array(
                    'id_persona' => $idPersona,
                    'oficio_exoneracion' => strtoupper($datos['oficio_exoneracion']),
                    'num_muestras_exoneradas' => !empty($datos['num_muestras_exoneradas']) ? $datos['num_muestras_exoneradas'] : null,
                    'estado' => Constantes::estado_SO()->ENVIADA,
                    'fecha_envio' => date('Y-m-d'),
                    'nom_archivo_oficio' => $datos['nom_archivo_oficio']
                );
                $statement = $this->modeloSolicitud->getAdapter()
                        ->getDriver()
                        ->createStatement();
                $sqlActualizar = $this->modeloSolicitud->actualizarSql('solicitudes', $this->modeloSolicitud->getEsquema());
                $sqlActualizar->set($datosSolicitud);
                $sqlActualizar->where(array('id_solicitud' => $this->modeloSolicitud->getIdSolicitud()));
                $sqlActualizar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                $statement->execute();
            }
            $proceso->commit();
            return true;
        } catch (GuardarExcepcion $ex) {
            $proceso->rollback();
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Crear la solicitud
     * 
     * @throws \Exception
     */
    private function crearSolicitud()
    {
        $comun = new \Agrodb\Core\Comun();

        //datos del formulario comleto
        $formDinamico = $this->modeloSolicitud->getDatosForm();

        // Guardamos los datos de la cabecera de la solicitud
        $tablaModelo = new SolicitudesModelo($formDinamico);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($this->modeloSolicitud->getIdSolicitud() == null)
        {
            unset($datosBd["id_solicitud"]);
            $idSolicitud = $this->modeloSolicitud->guardar($datosBd);
            if (!$idSolicitud)
            {
                throw new \Exception('No se registro los datos en la tabla solicitud');
            }
        } else
        {
            unset($datosBd["id_solicitud"]);
            unset($datosBd["usuario_guia"]);
            unset($datosBd["codigo"]);
            unset($datosBd["tipo_solicitud"]);
            $this->modeloSolicitud->actualizar($datosBd, $this->modeloSolicitud->getIdSolicitud());
            $idSolicitud = $this->modeloSolicitud->getIdSolicitud();
        }

        // Recuperamos los datos del detalle y los guardamos
        $detalleSolicitud = $this->modeloSolicitud->getDetalleSolicitud();
        $lNegociodetalleSolicitud = new DetalleSolicitudesLogicaNegocio();
        if (is_array($detalleSolicitud->getIdServicio()))
        {
            $datosDetalle = array();
            $idDatosDetalle = array();
            $idDetalleSolicitudActualizar = array();

            $statement = $this->modeloSolicitud->getAdapter()
                    ->getDriver()
                    ->createStatement();

            foreach ($detalleSolicitud->getIdServicio() as $id)
            {
                if ($detalleSolicitud->getIdDetalleSolicitud() == null)
                {
                    $datosDetalle = array(
                        'id_servicio' => (integer) $id,
                        'id_solicitud' => (integer) $idSolicitud,
                        'tiempo_estimado' => $formDinamico['tiempo'][$id],
                        'observacion' => $detalleSolicitud->getObservacion()
                    );
                    /* ver: https://docs.zendframework.com/zend-db/sql/ */
                    $sqlInsertar = $this->modeloSolicitud->guardarSql('detalle_solicitudes', $this->modeloSolicitud->getEsquema());
                    $sqlInsertar->columns($lNegociodetalleSolicitud->columnas());
                    $sqlInsertar->values($datosDetalle, $sqlInsertar::VALUES_MERGE);
                    $sqlInsertar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                    $statement->execute();
                    $idDetalleSolicitud = $this->modeloSolicitud->adapter->driver->getLastGeneratedValue($this->modeloSolicitud->getEsquema() . '.detalle_solicitudes_id_detalle_solicitud_seq');
                    $idDatosDetalle[$id] = $idDetalleSolicitud;

                    //Verificar si es un servicio predeterminado
                    if ($comun->casoEspecialServicio($id, Constantes::SER_PREDETERMINADO))
                    {
                        $idDetalleSolicitudActualizar[] = $idDetalleSolicitud;
                    }
                } else
                {
                    //no se actualiza en detalle_solicitud porque ha seleccionado un detalle para editar
                    $idDatosDetalle[$id] = $detalleSolicitud->getIdDetalleSolicitud();
                }
            }
        } else
        {
            throw new \Exception('Los tipo de análisis debe ser enviados en un Array');
        }

        $codigo_grupo = $this->generarCodigo(10);

        // Guardar los valores ingresados en tipo_analisis si existe 
        // En caso de marbetes
        if (isset($formDinamico['servicio']))
        {
            //verificar si el servicio es marbetes
            if ($comun->casoEspecialServicio($formDinamico['servicio'], Constantes::SER_MARBETES))
            {
                $total_marbetes = $formDinamico['cantidad'];
            }
        }
        $codigoUsuarioMuestra = '';

        $lNegocioTipoAnalisis = new TipoAnalisisLogicaNegocio();

        //Si el campo codigo_usu_muestra es oculto entonces solo debe guardar y no actualizar
        $res = $comun->atributosCodigoCampoMuestra($formDinamico['usuarioInterno'], $formDinamico['id_laboratorio']);
        if ($res['visible'] == 'false')
        {
            $codAux = 'M-';
            //buscar el ultimo codigo si existe
            if ($this->modeloSolicitud->getIdSolicitud() !== null & $this->modeloSolicitud->getIdSolicitud() > 0)
            {
                $buscaCodigo = $lNegocioTipoAnalisis->buscarCodigoUsuMuestra($this->modeloSolicitud->getIdSolicitud());
                $filaCodigo = $buscaCodigo->current();
                $valCod = explode('-', $filaCodigo->codigo_usu_muestra);
                $codAux = $codAux . ($valCod[1] + 1);
            } else
            {
                $codAux = 'M-1';
            }
            $codigoUsuMuestra1 = $codAux;
        }

        foreach ($formDinamico as $key => $value)
        {
            if (substr($key, 0, 7) == 'a_texto')
            {
                $idsl = explode("_", substr($key, 7));
                $codigoUsuarioMuestra = $formDinamico['codigo_usu_muestra_' . $idsl[1]][$idsl[2] - 1];
                $codigoUsuarioMuestra = preg_replace('/( ){2,}/u', ' ', $codigoUsuarioMuestra);
                $codigoUsuarioMuestra = strtoupper(trim($codigoUsuarioMuestra));
                $tipoAnalisis = array(
                    'id_detalle_solicitud' => (integer) $idDatosDetalle[$idsl[1]],
                    'id_laboratorio' => (integer) $idsl[0],
                    'numero_muestra' => (integer) $idsl[2],
                    'codigo_usu_muestra' => $codigoUsuarioMuestra,
                    'valor_usuario' => $value,
                    'codigo_agrupa' => $codigo_grupo,
                    'total_marbetes' => isset($total_marbetes) ? $total_marbetes : NULL
                );
                $idTipoAnalisis = isset($idsl[3]) ? $idsl[3] : null;
                //si existe tipo de analisis debe actualizar
                if ($idTipoAnalisis)
                {
                    $statement = $this->modeloSolicitud->getAdapter()
                            ->getDriver()
                            ->createStatement();
                    $sqlActualizar = $this->modeloSolicitud->actualizarSql('tipo_analisis', $this->modeloSolicitud->getEsquema());
                    $sqlActualizar->set($tipoAnalisis);
                    $sqlActualizar->where(array('id_tipo_analisis' => $idTipoAnalisis));
                    $sqlActualizar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                    $statement->execute();
                } else
                {
                    $statement = $this->modeloSolicitud->getAdapter()
                            ->getDriver()
                            ->createStatement();
                    if (isset($codigoUsuMuestra1))
                    {
                        $tipoAnalisis['codigo_usu_muestra'] = $codigoUsuMuestra1;
                        //$tipoAnalisis['observacion_interna'] = 'Código agregado automáticamente';
                    }
                    /* ver: https://docs.zendframework.com/zend-db/sql/ */
                    $sqlInsertar = $this->modeloSolicitud->guardarSql('tipo_analisis', $this->modeloSolicitud->getEsquema());
                    $sqlInsertar->columns($lNegocioTipoAnalisis->columnas());
                    $sqlInsertar->values($tipoAnalisis, $sqlInsertar::VALUES_MERGE);
                    $sqlInsertar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                    $statement->execute();
                }
            }
        }

        //para poner el codigo/nombre de la muestra si son servicios que se agregan por defecto, ejm DENSIDAD
        if (isset($codigoUsuMuestra1))
        {
            $statement = $this->modeloSolicitud->getAdapter()
                    ->getDriver()
                    ->createStatement();
            foreach ($idDetalleSolicitudActualizar as $idDS)
            {
                $tipoAnalisis = array(
                    'valor_usuario' => '',
                    'codigo_usu_muestra' => $codigoUsuMuestra1,
                    'observacion_interna' => 'Código agregado automáticamente'
                );
                $sqlActualizar = $this->modeloSolicitud->actualizarSql('tipo_analisis', $this->modeloSolicitud->getEsquema());
                $sqlActualizar->set($tipoAnalisis);
                $sqlActualizar->where(array('id_detalle_solicitud' => $idDS));
                $sqlActualizar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                $statement->execute();
            }
        }

        // REGISTRAR LOS DATOS EN TABLA archivos_adjuntos POR CADA detalle_solicitudes
        $lNegocioArchivosAdjuntos = new ArchivosAdjuntosLogicaNegocio();
        $sqlInsertar = null;
        $statement = $this->modeloSolicitud->getAdapter()
                ->getDriver()
                ->createStatement();
        $lNegocioServicios = new ServiciosLogicaNegocio();
        foreach ($formDinamico as $key => $value)
        {
            if ($value !== "")
            {
                if (substr($key, 0, 7) == 'p_archi') //lab_serv(nivel0)_paramServicio
                {
                    $idsParams = explode("_", substr($key, 7));
                    $idServNivel0 = $idsParams[1];
                    $idDS = "";
                    foreach ($idDatosDetalle as $idSer => $idDetalleSolicitud)
                    {
                        //buscar el nivel 0 del servicio
                        $buscaServicio = $lNegocioServicios->buscar($idSer);
                        $rama = explode(',', $buscaServicio->getRama());
                        $idS = $rama[0];
                        if ($idS === $idServNivel0)
                        {
                            $idDS = $idDetalleSolicitud;
                        }
                    }
                    if ($idDS !== "")
                    {
                        $archivosAdjuntos = array(
                            'id_parametros_servicio' => $idsParams[2],
                            'id_detalle_solicitud' => $idDS,
                            'nombre_archivo' => $value . '.pdf',
                            'fecha_subido' => date('Y-m-d')
                        );
                        /* ver: https://docs.zendframework.com/zend-db/sql/ */
                        $sqlInsertar = $this->modeloSolicitud->guardarSql('archivos_adjuntos', $this->modeloSolicitud->getEsquema());
                        $sqlInsertar->columns($lNegocioArchivosAdjuntos->columnas());
                        $sqlInsertar->values($archivosAdjuntos, $sqlInsertar::VALUES_MERGE);
                        $sqlInsertar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                        $statement->execute();
                    }
                }
            }
        }

        $this->idSolicitud = $idSolicitud;
        //guardar datos en muestras, detalle_muestras
        $this->datosMuestra($idSolicitud, $formDinamico, $codigo_grupo);
    }

    /**
     * Registra los datos en muestras y detalle_muestras
     * @param type $idSolicitud
     * @param type $datos
     * @param type $codigo_grupo
     * @throws Exception
     */
    public function datosMuestra($idSolicitud, $datos, $codigo_grupo)
    {
        // REGISTRAR LOS DATOS DEL PROPIETARIO SI NO ES EL MISMO DE LA GUIA
        $idPersona = null;
        if ($datos['opPropietario'] == '0') //en caso que requiere de otra persona
        {
            $datosPersona = new PersonasModelo();
            $datosPersona = $this->modeloSolicitud->getMuestras()->getPersona();
            $datosPersona->setIdentificador($datosPersona->getCiRuc());
            $lNegocioPersonas = new PersonasLogicaNegocio();
            $statement = $this->modeloSolicitud->getAdapter()
                    ->getDriver()
                    ->createStatement();
            //registrar la persona
            if ($this->modeloSolicitud->getMuestras()->getPersona()->getIdPersona() == null)
            {
                $lNegocioClientes = new ClientesLogicaNegocio();
                //Obtener datos del cliente
                $lNegocioPersonas = new PersonasLogicaNegocio();
                $buscaCliente = $lNegocioPersonas->buscarCliente($datos['ci_ruc']);
                $cliente = $buscaCliente->current();
                if (empty($cliente->identificador))  //si el cliente no existe debe registrarse en cliente y persona
                {
                    //registrar en cliente
                    if (strlen($datos['ci_ruc']) == 13)
                    {
                        $tipo_identificacion = Constantes::tipo_identificacion()->RUC;
                    } else
                    {
                        $tipo_identificacion = Constantes::tipo_identificacion()->CEDULA;
                    }
                    $datosCliente = array(
                        'identificador' => $datos['ci_ruc'],
                        'tipo_identificacion' => $tipo_identificacion,
                        'razon_social' => $datos['nombre'],
                        'direccion' => $datos['direccion'],
                        'telefono' => $datos['telefono'],
                        'correo' => $datos['email']
                    );
                    $statement = $this->modeloSolicitud->getAdapter()
                            ->getDriver()
                            ->createStatement();
                    $sqlInsertar = $this->modeloSolicitud->guardarSql('clientes', 'g_financiero');
                    $sqlInsertar->columns($lNegocioClientes->columnas());
                    $sqlInsertar->values($datosCliente, $sqlInsertar::VALUES_MERGE);
                    $sqlInsertar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                    $statement->execute();

                    //registrar en persona
                    $statement = $this->modeloSolicitud->getAdapter()
                            ->getDriver()
                            ->createStatement();
                    $sqlInsertar = $this->modeloSolicitud->guardarSql('personas', $this->modeloSolicitud->getEsquema());
                    $sqlInsertar->columns($lNegocioPersonas->columnas());
                    $sqlInsertar->values($lNegocioPersonas->datosPersona($datosPersona), $sqlInsertar::VALUES_MERGE);
                    $sqlInsertar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                    $statement->execute();
                    $idPersona = $this->modeloSolicitud->adapter->driver->getLastGeneratedValue($this->modeloSolicitud->getEsquema() . '.personas_id_persona_seq');
                    if (!$idPersona)
                    {
                        throw new \Exception('No se registo los datos en la tabla personas');
                    }
                } else if (empty($cliente->id_persona))  //si el cliente existe pero no en persona
                {
                    $statement = $this->modeloSolicitud->getAdapter()
                            ->getDriver()
                            ->createStatement();
                    $sqlInsertar = $this->modeloSolicitud->guardarSql('personas', $this->modeloSolicitud->getEsquema());
                    $sqlInsertar->columns($lNegocioPersonas->columnas());
                    $sqlInsertar->values($lNegocioPersonas->datosPersona($datosPersona), $sqlInsertar::VALUES_MERGE);
                    $sqlInsertar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                    $statement->execute();
                    $idPersona = $this->modeloSolicitud->adapter->driver->getLastGeneratedValue($this->modeloSolicitud->getEsquema() . '.personas_id_persona_seq');
                    if (!$idPersona)
                    {
                        throw new \Exception('No se registró los datos en la tabla personas');
                    }
                } else
                {
                    $idPersona = $cliente->id_persona;
                }
            } else //si existe el id_persona (propietario de la muestra) entonces debe actualizar
            {
                $idPersona = $this->modeloSolicitud->getMuestras()->getPersona()->getIdPersona();
                $sqlActualizar = $this->modeloSolicitud->actualizarSql('personas', $this->modeloSolicitud->getEsquema());
                $sqlActualizar->set($lNegocioPersonas->datosPersona($datosPersona));
                $sqlActualizar->where(array('id_persona' => $this->modeloSolicitud->getMuestras()->getPersona()->getIdPersona()));
                $sqlActualizar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                $statement->execute();
            }
        }

        // REGISTRAR DATOS GENERALES DE LA MUESTRA
        $statement = $this->modeloSolicitud->getAdapter()
                ->getDriver()
                ->createStatement();
        $lNegocioMuestras = new MuestrasLogicaNegocio();
        $datosMuestra = $this->modeloSolicitud->getMuestras();
        $datosMuestra->setIdSolicitud($idSolicitud);
        $datosMuestra->setIdPersona($idPersona);
        if ($this->modeloSolicitud->getMuestras()->getIdMuestra() == null)
        {
            $sqlInsertar = $this->modeloSolicitud->guardarSql('muestras', $this->modeloSolicitud->getEsquema());
            $sqlInsertar->columns($lNegocioMuestras->columnasMuestras());
            $sqlInsertar->values($lNegocioMuestras->datosMuestras($datosMuestra), $sqlInsertar::VALUES_MERGE);
            $sqlInsertar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
            $statement->execute();
            $idMuestra = $this->modeloSolicitud->adapter->driver->getLastGeneratedValue($this->modeloSolicitud->getEsquema() . '.muestras_id_muestra_seq');
        } else
        {
            $idMuestra = $this->modeloSolicitud->getMuestras()->getIdMuestra();
            $sqlActualizar = $this->modeloSolicitud->actualizarSql('muestras', $this->modeloSolicitud->getEsquema());
            $sqlActualizar->set($lNegocioMuestras->datosMuestras($datosMuestra));
            $sqlActualizar->where(array('id_muestra' => $this->modeloSolicitud->getMuestras()->getIdMuestra()));
            $sqlActualizar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
            $statement->execute();
        }

        // ingresamos los campos especificos de la muestra los mismos que inicia m_texto seguido por el id campo configurado en la tabla laboratorio
        // eliminamos todo para volver a ingresar
        $lNegociodetalleMuestra = new DetalleMuestrasLogicaNegocio();
        $statement = $this->modeloSolicitud->getAdapter()
                ->getDriver()
                ->createStatement();
        $sqlBorrar = $this->modeloSolicitud->borrarSql('detalle_muestras', $this->modeloSolicitud->getEsquema());
        $sqlBorrar->where(array('id_muestra' => $idMuestra));
        $sqlBorrar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
        $statement->execute();
        // ingresar nuevamente
        $statement = $this->modeloSolicitud->getAdapter()
                ->getDriver()
                ->createStatement();
        foreach ($datos as $key => $value)
        {
            if (substr($key, 0, 7) == 'm_texto')
            {
                $detalleMuestra = array(
                    'id_laboratorio' => (integer) substr($key, 7),
                    'id_muestra' => (integer) $idMuestra,
                    'valor_usuario' => $value,
                    'codigo_agrupa' => $codigo_grupo
                );

                /* ver: https://docs.zendframework.com/zend-db/sql/ */
                $sqlInsertar = $this->modeloSolicitud->guardarSql('detalle_muestras', $this->modeloSolicitud->getEsquema());
                $sqlInsertar->columns($lNegociodetalleMuestra->columnas());
                $sqlInsertar->values($detalleMuestra, $sqlInsertar::VALUES_MERGE);
                $sqlInsertar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                $statement->execute();
            } else if (substr($key, 0, 7) == 'm_lista')
            {
                foreach ((Array) $value as $ocpion)
                {
                    if ($ocpion > 0)
                    {
                        $detalleMuestra = array(
                            'id_laboratorio' => (integer) $ocpion, //identificador dato de la muestra que esta en tabla laboratorios
                            'id_muestra' => (integer) $idMuestra,
                            'valor_usuario' => 'check',
                            'codigo_agrupa' => $codigo_grupo
                        );

                        /* ver: https://docs.zendframework.com/zend-db/sql/ */
                        $sqlInsertar = $this->modeloSolicitud->guardarSql('detalle_muestras', $this->modeloSolicitud->getEsquema());
                        $sqlInsertar->columns($lNegociodetalleMuestra->columnas());
                        $sqlInsertar->values($detalleMuestra, $sqlInsertar::VALUES_MERGE);
                        $sqlInsertar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                        $statement->execute();
                    }
                }
            }
        }
    }

    /**
     * Llama a la funcion en base de datos para eliminar el registro
     */
    public function eliminarAnalisisRegistrado($idDetalleSolicitud)
    {
        $query = "select f_eliminar_analisis_registrado AS resultado from g_laboratorios.f_eliminar_analisis_registrado($idDetalleSolicitud);";
        return $this->modelo->ejecutarSqlNativo($query);
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
        $this->modelo->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return SolicitudesModelo
     */
    public function buscar($id)
    {
        return $this->modelo->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modelo->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modelo->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarSolicitudes($arrayParametros = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['idSolicitud']))
        {
            $arrayWhere[] = " sol.id_solicitud = {$arrayParametros['idSolicitud']}";
        }
        if (!empty($arrayParametros['identificador']))
        {
            $arrayWhere[] = " identificador = '{$arrayParametros['identificador']}'";
        }
        if (!empty($arrayParametros['estado']))
        {
            if (is_array($arrayParametros['estado']))
            {
                $arrayWhere[] = " sol.estado IN ('" . implode("','", $arrayParametros['estado']) . "')";
            } else
            {
                $arrayWhere[] = " sol.estado = '{$arrayParametros['estado']}'";
            }
        }
        if (!empty($arrayParametros['codigo']))
        {
            $arrayWhere[] = "UPPER(codigo) LIKE '%" . strtoupper($arrayParametros['codigo']) . "%'";
        }
        if ($arrayWhere)
        {
            $where = implode(' AND ', $arrayWhere);
        }
        if (!empty($where))
        {
            $where = " WHERE " . $where;
        }
        $consulta = "SELECT
        sol.id_solicitud,
        sol.codigo,
        sol.fecha_registro,
        sol.fecha_final_real,
        sol.fecha_final_estimada,
        sol.estado,
        sol.tipo_solicitud,
        ussol.id_usuarios_solicitud,
        ussol.identificador,
        ussol.tipo,
        ussol.fecha_inicio,
        ussol.fecha_fin
        FROM " . $this->modeloSolicitud->getEsquema() . ".solicitudes AS sol
        INNER JOIN " . $this->modeloSolicitud->getEsquema() . ".usuarios_solicitud AS ussol ON sol.id_solicitud = ussol.id_solicitud
        $where
        ORDER BY sol.id_solicitud DESC";
        return $this->modeloSolicitud->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna los datos de la solicitud
     * @param type $idSolicitud
     * @return type
     */
    public function buscarDatosSolicitud($idSolicitud)
    {
        $consulta = "SELECT
        sol.id_solicitud,
        sol.codigo,
        sol.fecha_registro,
        sol.muestreo_nacional,
        sol.exoneracion,
        sol.oficio_exoneracion,
        sol.estado,
        lprovmue.nombre AS prov_muestra,
        lprovlab.nombre AS prov_laboratorio
        FROM
        g_laboratorios.solicitudes AS sol
        INNER JOIN g_laboratorios.distribucion_muestras AS dismue ON dismue.id_distribucion_muestra = sol.id_distribucion_muestra
        INNER JOIN g_laboratorios.laboratorios_provincia AS labprov ON labprov.id_laboratorios_provincia = dismue.id_laboratorios_provincia
        INNER JOIN g_catalogos.localizacion AS lprovlab ON lprovlab.id_localizacion = labprov.id_localizacion
        INNER JOIN g_laboratorios.muestras AS mue ON mue.id_solicitud = sol.id_solicitud
        LEFT JOIN g_catalogos.localizacion AS lprovmue ON lprovmue.id_localizacion = mue.id_localizacion
        WHERE sol.id_solicitud = $idSolicitud";
        return $this->modeloSolicitud->ejecutarSqlNativo($consulta);
    }

    /**
     * Genera un código
     * Para tipo_analisis.codigo_agrupa
     * @param type $longitud
     * @return string
     */
    private function generarCodigo($longitud)
    {
        $key = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($pattern) - 1;
        for ($i = 0; $i < $longitud; $i ++)
        {
            $key .= $pattern{mt_rand(0, $max)};
        }
        return $key;
    }

    /**
     * Busca el valor total de la solicitud
     * @param type $idSolicitud
     * @return type
     */
    public function buscarTotalSolicitud($idSolicitud)
    {
        $consulta = "SELECT total_solicitud FROM g_laboratorios.v_valor_total_solicitud WHERE id_solicitud = $idSolicitud";
        $result = $this->modeloSolicitud->ejecutarSqlNativo($consulta);
        $fila = $result->current();
        $total = $fila->total_solicitud;
        $iva = round($total * ("0." . Constantes::IVA), 2);
        $total = $total + $iva;
        return $total;
    }

    /**
     * Retorna el detalle de la solicitud = nombre_servicio, total_muestras
     * @param type $idSolicitud
     * @return type
     */
    public function buscarDetalleSolicitud($idSolicitud)
    {
        $consulta = "SELECT
        detsol.id_detalle_solicitud,
        detsol.id_solicitud,
        dir.id_laboratorio AS id_direccion,
        dir.nombre AS nom_direccion,
        lab.id_laboratorio,
        lab.nombre AS nom_laboratorio,
        ser.id_servicio,
        ser.nombre AS nom_servicio,
        ser.rama_nombre,
        Max(tipana.numero_muestra) AS total_muestras
        FROM
        g_laboratorios.detalle_solicitudes AS detsol
        INNER JOIN g_laboratorios.servicios AS ser ON ser.id_servicio = detsol.id_servicio
        INNER JOIN g_laboratorios.tipo_analisis AS tipana ON detsol.id_detalle_solicitud = tipana.id_detalle_solicitud
        INNER JOIN g_laboratorios.laboratorios AS lab ON lab.id_laboratorio = ser.id_laboratorio
        INNER JOIN g_laboratorios.laboratorios AS dir ON dir.id_laboratorio = lab.fk_id_laboratorio
        WHERE detsol.id_solicitud = $idSolicitud
        GROUP BY
        detsol.id_detalle_solicitud,
        detsol.id_solicitud,
        dir.id_laboratorio,
        dir.nombre,
        lab.id_laboratorio,
        lab.nombre,
        ser.id_servicio,
        ser.nombre,
        ser.rama_nombre";
        return $this->modeloSolicitud->ejecutarSqlNativo($consulta);
    }

    /**
     * Rertorna el total de muestras segun la solicitud y el laboratorio
     * @param type $idSolicitud
     * @param type $idLaboratorio
     * @return type
     */
    public function totalMuestras($idSolicitud, $idLaboratorio)
    {
        $consulta = "SELECT
        g_laboratorios.detalle_solicitudes.id_servicio,
        g_laboratorios.detalle_solicitudes.id_solicitud,
        g_laboratorios.servicios.id_laboratorio,
        max(g_laboratorios.tipo_analisis.numero_muestra) as total_muestras
        FROM
        g_laboratorios.detalle_solicitudes
        INNER JOIN g_laboratorios.servicios ON g_laboratorios.servicios.id_servicio = g_laboratorios.detalle_solicitudes.id_servicio
        INNER JOIN g_laboratorios.tipo_analisis ON g_laboratorios.detalle_solicitudes.id_detalle_solicitud = g_laboratorios.tipo_analisis.id_detalle_solicitud
        WHERE
        g_laboratorios.detalle_solicitudes.id_solicitud = $idSolicitud AND
        g_laboratorios.servicios.id_laboratorio = $idLaboratorio AND
        g_laboratorios.detalle_solicitudes.estado = 'ACTIVO' AND
        g_laboratorios.tipo_analisis.estado = 'ACTIVO' 
        GROUP BY g_laboratorios.detalle_solicitudes.id_servicio,
        g_laboratorios.detalle_solicitudes.id_solicitud,
        g_laboratorios.servicios.id_laboratorio";
        return $this->modeloSolicitud->ejecutarSqlNativo($consulta);
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardarFA(Array $datos)
    {
        //OJO Validar los campos obligatoriso y los opcionales vacios poner no informa
        try {
            $this->modeloSolicitud = new SolicitudesModelo($datos);
            $proceso = $this->modeloSolicitud->getAdapter()
                    ->getDriver()
                    ->getConnection();
            if (!$proceso->beginTransaction())
            {
                throw new \Exception('No se pudo iniciar la transacción en: Guardar solicitud');
            }
            $this->crearSolicitudFA();
            $proceso->commit();
            return true;
        } catch (GuardarExcepcion $ex) {
            $proceso->rollback();
            throw new \Exception($ex->getMessage());
        } catch (Exception $exc) {
            $proceso->rollback();
            throw new \Exception($exc->getMessage());
        }
    }

    /**
     * Crear la solicitud
     * 
     * @throws \Exception
     */
    private function crearSolicitudFA()
    {
        //datos del formulario comleto
        $formDinamico = $this->modeloSolicitud->getDatosForm();

        // Guardamos los datos de la cabecera de la solicitud
        if ($this->modeloSolicitud->getIdSolicitud() == null)
        {
            $tablaModelo = new SolicitudesModelo($formDinamico);
            $datosBd = $tablaModelo->getPrepararDatos();
            unset($datosBd["id_solicitud"]);
            $idSolicitud = $this->modeloSolicitud->guardar($datosBd);
            if (!$idSolicitud)
            {
                throw new \Exception('No se registro los datos en la tabla solicitud');
            }
        } else
        {
            $idSolicitud = $this->modeloSolicitud->getIdSolicitud();
        }

        // Recuperamos los datos del detalle y los guardamos
        $detalleSolicitud = $this->modeloSolicitud->getDetalleSolicitud();
        $lNegociodetalleSolicitud = new DetalleSolicitudesLogicaNegocio();
        if (is_array($detalleSolicitud->getIdServicio()))
        {
            $datosDetalle = array();
            $idDatosDetalle = array();

            $statement = $this->modeloSolicitud->getAdapter()
                    ->getDriver()
                    ->createStatement();

            foreach ($detalleSolicitud->getIdServicio() as $id)
            {

                // buscar datos del servicio OJO PREGUNTAR
                $lNegocioServicios = new ServiciosLogicaNegocio();
                $servicio = $lNegocioServicios->buscar($id);
                $datosDetalle = array(
                    'id_servicio' => (integer) $id,
                    'id_solicitud' => (integer) $idSolicitud,
                    'tiempo_estimado' => 0,
                    'observacion' => $detalleSolicitud->getObservacion()
                );
                /* ver: https://docs.zendframework.com/zend-db/sql/ */
                $sqlInsertar = $this->modeloSolicitud->guardarSql('detalle_solicitudes', $this->modeloSolicitud->getEsquema());
                $sqlInsertar->columns($lNegociodetalleSolicitud->columnas());
                $sqlInsertar->values($datosDetalle, $sqlInsertar::VALUES_MERGE);
                $sqlInsertar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                $statement->execute();
                //$idDatosDetalle[$id] = $this->modeloSolicitud->adapter->driver->getLastGeneratedValue($this->modeloSolicitud->getEsquema() . '.detalle_solicitudes_id_detalle_solicitud_seq');
                $idDatosDetalle = $this->modeloSolicitud->adapter->driver->getLastGeneratedValue($this->modeloSolicitud->getEsquema() . '.detalle_solicitudes_id_detalle_solicitud_seq');
            }
            // GUARDAMOS EL TIPO DE ANÁLISIS DE LOS CAMPOS DESPLEGADOS DINÁMICAMENTE
            $lNegocioTipoAnalisis = new TipoAnalisisLogicaNegocio();
        } else
        {
            throw new \Exception('Los tipo de análisis debe ser enviados en un Array');
        }

        //GUARDAR LAS MUESTRAS PARA ANALISIS
        $codigo_grupo = $this->generarCodigo(10);
        //Guadamos las muestras para cada análisis
        $this->guardarDatosExcelFiebreaftosa($formDinamico, $idDatosDetalle, $codigo_grupo);
        //guardar datos en muestras, detalle_muestras
        $this->datosMuestra($idSolicitud, $formDinamico, $codigo_grupo);
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardarDatosExcelFiebreaftosa(Array $datos, $idDetalleSolicitud, $codigo_grupo)
    {
        $inicio = $datos['inicio'];
        $fin = $datos['fin'];
        $archivoExcel = APP . "Laboratorios/archivos/muestrasFA/" . $datos['archivo'] . "." . $datos['extension'];
        switch (strtolower($datos['extension']))
        {
            case 'xls':
                $tipo = 'Xls';   //Requiere formato Xls
                break;
            case 'xlsx':
                $tipo = 'Xlsx';   //Requiere formato Xlsx
                break;
            default:
                $tipo = 'Xls';   //Requiere formato Xls
                break;
        }

        try {
            $inputFileType = $tipo;
            $reader = IOFactory::createReader($inputFileType);
            $reader->setReadDataOnly(true);
            $reader->setLoadSheetsOnly(0);
            $filterSubset = new Filtro($inicio, $fin, range('A', 'B'));
            $reader->setReadFilter($filterSubset);
            $spreadsheet = $reader->load($archivoExcel);
            $loadedSheetNames = $spreadsheet->getActiveSheet();
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $datosdb = Array();

            foreach ($sheetData as $key => $value)
            {
                if ($value['A'] != "")
                {
                    $statement = $this->modeloSolicitud->getAdapter()
                            ->getDriver()
                            ->createStatement();
                    $lNegocioFiebreaftosa = new FiebreaftosaLogicaNegocio();
                    $datosdb['id_laboratorio'] = $datos['id_laboratorio'];
                    $datosdb['codigo_sifae'] = $value['A'];
                    $datosdb['codigo_laboratorio'] = "cod01";
                    $datosdb['nombre_muestra'] = $value['B'];

                    $sqlInsertar = $this->modeloSolicitud->guardarSql('fiebre_aftosa', $this->modeloSolicitud->getEsquema());
                    $sqlInsertar->columns($lNegocioFiebreaftosa->columnas());
                    $sqlInsertar->values($datosdb, $sqlInsertar::VALUES_MERGE);
                    $sqlInsertar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                    $statement->execute();

                    $statement = $this->modeloSolicitud->getAdapter()
                            ->getDriver()
                            ->createStatement();
                    $lNegocioTipoAnalisis = new TipoAnalisisLogicaNegocio();
                    $tipoAnalisis = array(
                        'id_detalle_solicitud' => (integer) $idDetalleSolicitud,
                        'id_laboratorio' => (integer) $datos['id_laboratorio'],
                        'numero_muestra' => (integer) $key,
                        'codigo_usu_muestra' => $value['A'],
                        'valor_usuario' => '0 - MUESTREO NACIONAL',
                        'codigo_agrupa' => $codigo_grupo
                    );
                    /* ver: https://docs.zendframework.com/zend-db/sql/ */
                    $sqlInsertar = $this->modeloSolicitud->guardarSql('tipo_analisis', $this->modeloSolicitud->getEsquema());
                    $sqlInsertar->columns($lNegocioTipoAnalisis->columnas());
                    $sqlInsertar->values($tipoAnalisis, $sqlInsertar::VALUES_MERGE);
                    $sqlInsertar->prepareStatement($this->modeloSolicitud->getAdapter(), $statement);
                    $statement->execute();
                }
            }
            return true;
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            die('Error al cargar el archivo de excel: ' . $e->getMessage());
        }
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarDatosMemo($memo)
    {
        $consulta = "SELECT
        sol.id_solicitud,
        sol.codigo,
        sol.fecha_registro,
        sol.exoneracion,
        sol.num_muestras_exoneradas,
        sol.oficio_exoneracion,
        Count(rmu.numero_muestra) AS num_muestras
        FROM
        g_laboratorios.solicitudes AS sol
        INNER JOIN g_laboratorios.detalle_solicitudes AS dsol ON dsol.id_solicitud = sol.id_solicitud
        INNER JOIN g_laboratorios.recepcion_muestras AS rmu ON rmu.id_detalle_solicitud = dsol.id_detalle_solicitud
        WHERE
        sol.exoneracion = 'SI' AND
        UPPER(sol.oficio_exoneracion) = '" . strtoupper($memo) . "' GROUP BY
        sol.id_solicitud";
        return $this->modeloSolicitud->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna los datos de la solicitud
     * @param type $idSolicitud
     * @return type
     */
    public function obtenerMuestrasSolicitud($idSolicitud)
    {
        $consulta = "SELECT codigo_usu_muestra
        FROM g_laboratorios.tipo_analisis ta
        JOIN g_laboratorios.detalle_solicitudes dsol ON dsol.id_detalle_solicitud = ta.id_detalle_solicitud
        WHERE dsol.id_solicitud = $idSolicitud
        GROUP BY codigo_usu_muestra";
        return $this->modeloSolicitud->ejecutarSqlNativo($consulta);
    }

    /**
     * Para añadir los servicios usado desde la ventana modal
     * @param type $idSolicitud
     * @param type $idDetalleSolicitud
     * @param type $idServicio
     * @param type $muestras
     * @return type
     */
    public function anadirServicios($idSolicitud, $idDetalleSolicitud, $idServicio, $muestras)
    {
        //validar que no este registrado el servicio en la solicitud
        $lNDetalleSolicitudes = new DetalleSolicitudesLogicaNegocio();
        $buscaDetalleSolicitud = $lNDetalleSolicitudes->buscarLista(array('id_solicitud' => $idSolicitud, 'id_servicio' => $idServicio));
        if (count($buscaDetalleSolicitud) > 0)
        {
            echo Mensajes::fallo(Constantes::INF_EXISTE_DETALLE_SOLICITUD);
            exit();
        } else
        {
            $query = "select f_anadir_servicios_solicitud AS resultado from g_laboratorios.f_anadir_servicios_solicitud($idDetalleSolicitud,$idServicio,'$muestras');";
            return $this->modelo->ejecutarSqlNativo($query);
        }
    }

}

class Filtro implements IReadFilter
{

    private $startRow = 0;
    private $endRow = 0;
    private $columns = [];

    public function __construct($startRow, $endRow, $columns)
    {
        $this->startRow = $startRow;
        $this->endRow = $endRow;
        $this->columns = $columns;
    }

    public function readCell($column, $row, $worksheetName = '')
    {
        if ($row >= $this->startRow && $row <= $this->endRow)
        {
            if (in_array($column, $this->columns))
            {
                return true;
            }
        }

        return false;
    }

}
