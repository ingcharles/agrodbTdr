<?php

/**
 * http://php-java-bridge.sourceforge.net/pjb/index.php
 */

namespace Agrodb\Core;

/**
 * Conecta PHP con JAVA
 */
class Javabridge {

    function __construct($usuario, $tipoUsuario) {
        require_once(URL_JAVA_INI);
        $this->propiedades($usuario, $tipoUsuario);
    }

    /**
     * Ejecuta método de un Servlet
     * @return type
     */
    public function exec() {
        return java_context()->getServlet();
    }

    private function propiedades($usuario, $tipoUsuario, $template = "") {
        $this->exec()->setBdServidor(DB_HOST); //IP del servidor de la base de datos
        $this->exec()->setBdPuerto(DB_PORT); //Puerto de base de datos
        $this->exec()->setBdUsuario(DB_USER); //Usuario de la base de datos
        $this->exec()->setBdClave(DB_PASS); //Clase de la base de datos
        $this->exec()->setBdNombre(DB_NAME); //Nombre de la base de datos
        $this->exec()->setAppUsuario($usuario); //Identificador
        $this->exec()->setAppTipoUsuario($tipoUsuario); //INTERNO O  EXTERNO
        $this->exec()->setRutaBaseReportes(JAVA_URL_REPORTES); //carpeta donde guardar o reportes generado desde java 
        $this->exec()->setRutaBaseFirmas(JAVA_URL_FIRMAS);  
        $this->exec()->setRutaLogo(JAVA_URL_LOGO); 
    }

}
