<?php
/**
 * Lógica del negocio de CatastroPredioEquidosEspecieModelo
 *
 * Este archivo se complementa con el archivo CatastroPredioEquidosEspecieControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-16
 * @uses    CatastroPredioEquidosEspecieLogicaNegocio
 * @package ProgramasControlOficial
 * @subpackage Modelos
 */
namespace Agrodb\ProgramasControlOficial\Modelos;

use Agrodb\ProgramasControlOficial\Modelos\IModelo;

/*use Agrodb\PasaporteEquino\Modelos\EquinosLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\EquinosModelo;*/

use Agrodb\PasaporteEquino\Modelos\RegistroMovimientosLogicaNegocio;
use Agrodb\PasaporteEquino\Modelos\RegistroMovimientosModelo;

use Agrodb\Catalogos\Modelos\EspeciesLogicaNegocio;
use Agrodb\Catalogos\Modelos\EspeciesModelo;

use Agrodb\Catalogos\Modelos\RazaLogicaNegocio;
use Agrodb\Catalogos\Modelos\RazaModelo;

use Agrodb\Catalogos\Modelos\CategoriaEspecieLogicaNegocio;
use Agrodb\Catalogos\Modelos\CategoriaEspecieModelo;

class CatastroPredioEquidosEspecieLogicaNegocio implements IModelo
{

    private $modeloCatastroPredioEquidosEspecie = null;

    /*private $lNegocioEquinos = null;
    private $modeloEquinos = null;*/
    
    private $lNegocioRegistroMovimientos = null;
    private $modeloRegistroMovimientos = null;

    private $lNegocioEspecies = null;
    private $modeloEspecies = null;

    private $lNegocioRaza = null;
    private $modeloRaza = null;

    private $lNegocioCategoriaEspecie = null;
    private $modeloCategoriaEspecie = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloCatastroPredioEquidosEspecie = new CatastroPredioEquidosEspecieModelo();

        /*$this->lNegocioEquinos = new EquinosLogicaNegocio();
        $this->modeloEquinos = new EquinosModelo();*/
        
        $this->lNegocioRegistroMovimientos = new RegistroMovimientosLogicaNegocio();
        $this->modeloRegistroMovimientos = new RegistroMovimientosModelo();

        $this->lNegocioEspecies = new EspeciesLogicaNegocio();
        $this->modeloEspecies = new EspeciesModelo();

        $this->lNegocioRaza = new RazaLogicaNegocio();
        $this->modeloRaza = new RazaModelo();

        $this->lNegocioCategoriaEspecie = new CategoriaEspecieLogicaNegocio();
        $this->modeloCategoriaEspecie = new CategoriaEspecieModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new CatastroPredioEquidosEspecieModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdCatastroPredioEquidosEspecie() != null && $tablaModelo->getIdCatastroPredioEquidosEspecie() > 0) {
            return $this->modeloCatastroPredioEquidosEspecie->actualizar($datosBd, $tablaModelo->getIdCatastroPredioEquidosEspecie());
        } else {
            unset($datosBd["id_catastro_predio_equidos_especie"]);
            return $this->modeloCatastroPredioEquidosEspecie->guardar($datosBd);
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
        $this->modeloCatastroPredioEquidosEspecie->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return CatastroPredioEquidosEspecieModelo
     */
    public function buscar($id)
    {
        return $this->modeloCatastroPredioEquidosEspecie->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloCatastroPredioEquidosEspecie->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloCatastroPredioEquidosEspecie->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarCatastroPredioEquidosEspecie()
    {
        $consulta = "SELECT * FROM " . $this->modeloCatastroPredioEquidosEspecie->getEsquema() . ". catastro_predio_equidos_especie";
        return $this->modeloCatastroPredioEquidosEspecie->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca las especies registradas en un predio
     *
     * @return array|ResultSet
     */
    public function buscarEspeciesXPredio($idPredio)
    {
        $consulta = "   SELECT
                        	distinct cpee.id_especie, cpee.nombre_especie
                        FROM 
                        	g_programas_control_oficial.catastro_predio_equidos_especie cpee
                            INNER JOIN g_catalogos.especies e ON cpee.id_especie = e.id_especies
                        WHERE
                        	cpee.id_catastro_predio_equidos = $idPredio and
                            e.codigo in ('EQUIN', 'EQUID') and
	                        cpee.numero_animales > 0;";

        return $this->modeloCatastroPredioEquidosEspecie->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca las razas de especies registradas en un predio
     *
     * @return array|ResultSet
     */
    public function comboRazasXEspecieXPredio($idPredio, $idEspecie)
    {
        $consulta = "   SELECT
                        	distinct cpee.id_raza, cpee.nombre_raza
                        FROM
                        	g_programas_control_oficial.catastro_predio_equidos_especie cpee
                        WHERE
                        	cpee.id_catastro_predio_equidos = $idPredio and
                            cpee.id_especie = $idEspecie and
	                        cpee.numero_animales > 0;";

        return $this->modeloCatastroPredioEquidosEspecie->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca las categorías de especies registradas en un predio
     *
     * @return array|ResultSet
     */
    public function comboCategoriasXEspecieXPredio($idPredio, $idEspecie, $idRaza)
    {
        $consulta = "   SELECT
                        	distinct cpee.id_categoria, cpee.nombre_categoria
                        FROM
                        	g_programas_control_oficial.catastro_predio_equidos_especie cpee
                        WHERE
                        	cpee.id_catastro_predio_equidos = $idPredio and
                            cpee.id_especie = $idEspecie and
                            cpee.id_raza = $idRaza and
	                        cpee.numero_animales > 0;";

        return $this->modeloCatastroPredioEquidosEspecie->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca las categorías de especies registradas en un predio
     *
     * @return array|ResultSet
     */
    public function obtenerNumeroEquinosCategoriasXEspecieXPredio($idPredio, $idEspecie, $idRaza, $idCategoria)
    {
        $consulta = "   SELECT
                        	cpee.id_catastro_predio_equidos_especie, cpee.id_catastro_predio_equidos, cpee.numero_animales
                        FROM
                        	g_programas_control_oficial.catastro_predio_equidos_especie cpee
                        WHERE
                        	cpee.id_catastro_predio_equidos = $idPredio and
                            cpee.id_especie = $idEspecie and
                            cpee.id_raza = $idRaza and
                            cpee.id_categoria = $idCategoria and
	                        cpee.numero_animales > 0;";
        //print_r($consulta);
        return $this->modeloCatastroPredioEquidosEspecie->ejecutarSqlNativo($consulta);
    }

    /**
     * Funcionamiento para traslado de equino de un predio a otro y guardado de datos
     *
     * @param array $datos
     * @return int
     */
    public function actualizarCatastro(Array $datos)
    {
        $validacion = array(
            'bandera' => false,
            'estado' => "Fallo",
            'mensaje' => "Ocurrió un error al guardar la información del traslado de equino",
            'contenido' => null
        );

        $bandera = false;

        // Buscar el registro del equino pra obtener los parámetros
        /*$equino = $this->lNegocioEquinos->buscar($datos['id_equino']);*/
        
        $idPredio = $datos['id_ubicacion_actual'];
        $idPredioDestino = $datos['id_ubicacion_destino'];
        $idEspecie = $datos['id_especie'];
        $idRaza = $datos['id_raza'];
        $idCategoria = $datos['id_categoria'];
        
        $especie = $this->lNegocioEspecies->buscar($idEspecie);
        $raza = $this->lNegocioRaza->buscar($idRaza);
        $categoria = $this->lNegocioCategoriaEspecie->buscar($idCategoria);
        
        $nombreEspecie = $especie->nombre;
        $nombreRaza = $raza->raza;
        $nombreCategoria = $categoria->categoriaEspecie;

        // Buscar el equino en el catastro en el predio de origen
        $ubicacionOrigen = $this->obtenerNumeroEquinosCategoriasXEspecieXPredio($idPredio, $idEspecie, $idRaza, $idCategoria);

        if (isset($ubicacionOrigen->current()->id_catastro_predio_equidos_especie) and $ubicacionOrigen->current()->id_catastro_predio_equidos_especie != '') {// != ''

            if ($ubicacionOrigen->current()->numero_animales > 0) {
                $numeroTotalOrigen = $ubicacionOrigen->current()->numero_animales;
                $numeroActualOrigen = $numeroTotalOrigen - 1;
                
                $arrayParametros = array(
                    'id_catastro_predio_equidos_especie' => $ubicacionOrigen->current()->id_catastro_predio_equidos_especie,
                    'numero_animales' =>  $numeroActualOrigen
                );

                $idCatastro = $this->guardar($arrayParametros);

                if ($idCatastro > 0) {
                    $bandera = true;
                    $validacion['mensaje'] = ' Se ha disminuido el número de equinos del predio origen con idcatastro ' . $idPredio . '.';
                    
                    //Guardar registro de auditoría
                    /*$arrayParametros = array(
                        'id_movilizacion' => $datos['idMovilizacion'],
                        'identificador' => $_SESSION['usuario'],
                        'id_equino' => $datos['id_equino'],
                        'id_catastro_predio_equidos_origen' => $ubicacionOrigen->current()->id_catastro_predio_equidos,
                        'id_catastro_predio_equidos_especie_origen' => $ubicacionOrigen->current()->id_catastro_predio_equidos_especie,
                        'numero_total_origen' => $numeroTotalOrigen,
                        'numero_actual_origen' => $numeroActualOrigen,
                        'id_catastro_predio_equidos_destino' => $ubicacionOrigen->current()->id_catastro_predio_equidos,
                        'id_catastro_predio_equidos_especie_destino' => $ubicacionOrigen->current()->id_catastro_predio_equidos_especie,
                        'numero_total_destino' => $numeroTotalOrigen,
                        'numero_actual_destino' => $numeroActualOrigen,
                        'motivo' => 'Movilización equino'
                    );
                    
                    $this->lNegocioRegistroMovimientos->guardar($arrayParametros);*/
                    
                } else {
                    $validacion['bandera'] = false;
                    $validacion['estado'] = 'Fallo';
                    $validacion['mensaje'] .= ' No se ha disminuido el número de equinos.';
                    $bandera = false;
                }
            } else {
                $validacion['bandera'] = false;
                $validacion['estado'] = 'Fallo';
                $validacion['mensaje'] .= ' No se disponen de animales en el predio.';
                $bandera = false;
            }
        } else {
            $validacion['bandera'] = false;
            $validacion['estado'] = 'Fallo';
            $validacion['mensaje'] .= ' No se ha encontrado el registro del equino.';
            $bandera = false;
        }

        if ($bandera) {
            // Buscar registro del tipo de equino en el catastro en el predio de destino
            $ubicacionDestino = $this->obtenerNumeroEquinosCategoriasXEspecieXPredio($idPredioDestino, $idEspecie, $idRaza, $idCategoria);

            if (isset($ubicacionDestino->current()->id_catastro_predio_equidos_especie) and $ubicacionDestino->current()->id_catastro_predio_equidos_especie != '') {//*

                $numeroTotalDestino = $ubicacionDestino->current()->numero_animales;
                $numeroActualDestino = $numeroTotalDestino + 1;
                
                $arrayParametros = array(
                    'id_catastro_predio_equidos_especie' => $ubicacionDestino->current()->id_catastro_predio_equidos_especie,
                    'numero_animales' => $numeroActualDestino
                );

                $idCatastroDestino = $this->guardar($arrayParametros);

                if ($idCatastroDestino > 0) {
                    $bandera = true;
                    $validacion['bandera'] = true;
                    $validacion['estado'] = 'exito';
                    $validacion['mensaje'] .= ' Se ha incrementado el número de equinos del predio origen con id catastro ' . $idPredioDestino . '.';
                    
                    //Guardar registro de auditoría
                    $arrayParametros = array(
                        'id_movilizacion' => $datos['idMovilizacion'],
                        'identificador' => $_SESSION['usuario'],
                        'nombre_emisor' => $datos['nombre_emisor'],
                        'tipo_usuario' => $datos['tipo_usuario'],
                        'id_equino' => $datos['id_equino'],
                        'id_catastro_predio_equidos_origen' => $ubicacionOrigen->current()->id_catastro_predio_equidos,
                        'id_catastro_predio_equidos_especie_origen' => $ubicacionOrigen->current()->id_catastro_predio_equidos_especie,
                        'numero_total_origen' => $numeroTotalOrigen,
                        'numero_actual_origen' => $numeroActualOrigen,
                        'id_catastro_predio_equidos_destino' => $ubicacionDestino->current()->id_catastro_predio_equidos,
                        'id_catastro_predio_equidos_especie_destino' => $ubicacionDestino->current()->id_catastro_predio_equidos_especie,
                        'numero_total_destino' => $numeroTotalDestino,
                        'numero_actual_destino' => $numeroActualDestino,
                        'motivo' => 'Movilización equino'
                    );
                    
                    $idAuditoria = $this->lNegocioRegistroMovimientos->guardar($arrayParametros);
                    
                    if ($idAuditoria > 0) {
                        $bandera = true;
                        $validacion['bandera'] = true;
                        $validacion['estado'] = 'exito';
                        $validacion['mensaje'] .= ' Se ha creado el registro de auditoría con id ' . $idAuditoria . '.';
                        
                    } else {
                        $validacion['bandera'] = false;
                        $validacion['estado'] = 'Fallo';
                        $validacion['mensaje'] .= 'No se ha creado el registro de auditoría. Revise los cambios manualmente.';
                        $bandera = false;
                    }
                    
                } else {
                    $validacion['bandera'] = false;
                    $validacion['estado'] = 'Fallo';
                    $validacion['mensaje'] .= 'No se ha aumentado el número de equinos.';
                    $bandera = false;
                }
            } else {
                $arrayParametros = array(
                    'identificador' => $_SESSION['usuario'],
                    'id_catastro_predio_equidos' => $idPredioDestino,
                    'id_especie' => $idEspecie,
                    'nombre_especie' => $nombreEspecie,
                    'id_raza' => $idRaza,
                    'nombre_raza' => $nombreRaza,
                    'id_categoria' => $idCategoria,
                    'nombre_categoria' => $nombreCategoria,
                    'numero_animales' => 1
                );

                $idCatastroDestino = $this->guardar($arrayParametros);

                if ($idCatastroDestino > 0) {
                    $bandera = true;
                    $validacion['bandera'] = true;
                    $validacion['estado'] = 'exito';
                    $validacion['mensaje'] .= ' Se ha creado el registro de especie con id ' . $idCatastroDestino . '.';
                    
                    //Guardar registro de auditoría
                    $arrayParametros = array(
                        'id_movilizacion' => $datos['idMovilizacion'],
                        'identificador' => $_SESSION['usuario'],
                        'nombre_emisor' => $datos['nombre_emisor'],
                        'tipo_usuario' => $datos['tipo_usuario'],
                        'id_equino' => $datos['id_equino'],
                        'id_catastro_predio_equidos_origen' => $ubicacionOrigen->current()->id_catastro_predio_equidos,//*
                        'id_catastro_predio_equidos_especie_origen' => $ubicacionOrigen->current()->id_catastro_predio_equidos_especie,
                        'numero_total_origen' => $numeroTotalOrigen,
                        'numero_actual_origen' => $numeroActualOrigen,
                        'id_catastro_predio_equidos_destino' => $idPredioDestino,
                        'id_catastro_predio_equidos_especie_destino' => $idCatastroDestino,
                        'numero_total_destino' => 0,
                        'numero_actual_destino' => 1,
                        'motivo' => 'Movilización equino'
                    );
                    
                    $idAuditoria = $this->lNegocioRegistroMovimientos->guardar($arrayParametros);
                    
                    if ($idAuditoria > 0) {
                        $bandera = true;
                        $validacion['bandera'] = true;
                        $validacion['estado'] = 'exito';
                        $validacion['mensaje'] .= ' Se ha creado el registro de auditoría con id ' . $idAuditoria . '.';
                        
                    } else {
                        $validacion['bandera'] = false;
                        $validacion['estado'] = 'Fallo';
                        $validacion['mensaje'] .= 'No se ha creado el registro de auditoría. Revise los cambios manualmente.';
                        $bandera = false;
                    }
                } else {
                    $validacion['bandera'] = false;
                    $validacion['estado'] = 'Fallo';
                    $validacion['mensaje'] .= 'No se ha aumentado el número de equinos.';
                    $bandera = false;
                }
            }
        }

        return $validacion;
    }
}