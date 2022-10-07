<?php

namespace Agrodb\Log\Modelos;

class LogSistemaLogicaNegocio extends \SQLite3 {

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct() {
        
    }

    /**
     * Busca todos los 25 últimos registros
     *
     * @return array|ResultSet
     */
    public function verLog($tabla) {

        $this->open(MVC . '/Log/log_agrocalidad.db');
        $query = 'SELECT * FROM ' . $tabla . ' ORDER BY id desc LIMIT 25 ';
 $i=1;
        $results = $this->query($query);
        while ($row = $results->fetchArray()) {
            echo $row['id'] . '==>';
            echo $row['fecha'] . '<br>';
            echo $row['evento'] . '<hr>';
        }
        return $results;
    }

}
