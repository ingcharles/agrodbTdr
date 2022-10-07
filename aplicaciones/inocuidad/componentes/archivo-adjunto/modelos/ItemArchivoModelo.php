<?php
/**
 * User: ccarrera
 * Clase Entidad que representa la tabla ic_adjunto_model
 */

class ItemArchivoModelo
{
private $id;
private $nombreTabla;
private $registro;

    /**
     * ItemArchivoModelo constructor.
     * @param $id
     * @param $nombreTabla
     * @param $registro
     */
    public function __construct($id, $nombreTabla, $registro)
    {
        $this->id = $id;
        $this->nombreTabla = $nombreTabla;
        $this->registro = $registro;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNombreTabla()
    {
        return $this->nombreTabla;
    }

    /**
     * @param mixed $nombreTabla
     */
    public function setNombreTabla($nombreTabla)
    {
        $this->nombreTabla = $nombreTabla;
    }

    /**
     * @return mixed
     */
    public function getRegistro()
    {
        return $this->registro;
    }

    /**
     * @param mixed $registro
     */
    public function setRegistro($registro)
    {
        $this->registro = $registro;
    }


}