<?php

/**
 * For more info about namespaces plase @see http://php.net/manual/en/language.namespaces.importing.php
 */
namespace Agrodb\Core;

class Router{

	/** @var null The controller */
	private $url_module = null;

	/** @var null The controller */
	private $url_controller = null;

	/** @var null The method (of the above controller), often also named "action" */
	private $url_action = null;

	/** @var array URL parameters */
	private $url_params = array();

	/**
	 * "Start" the application:
	 * Analyze the URL elements and calls the according controller/method or the fallback
	 */
	public function __construct($modulo = null, $controlador = null, $accion = null){
		if (PHP_SAPI !== 'cli'){
			// Creamos un array con las partes de la $url
			$this->splitUrl();

			// Verificamos si existe el módulo
			if (! $this->url_module){
				// Si no existe el módulo en la url se direcciona a la página de incio de la versión anterior del GUIA
				$page = new \Agrodb\Controladores\InicioControlador();
				$page->index();
			}elseif (file_exists(APP . ucfirst($this->url_module) . '/Controladores/')){ // Existe el módulo?
			                                                                             // Si existe el módulo verificamos si existe el controlador en la url
				if (! $this->url_controller){

					// Si no existe el controlador, verificamos si existe el controlador IndexControlador (Dafault)
					if (file_exists(APP . ucfirst($this->url_module) . '/Controladores/IndexControlador.php')){
						$controller = "\\Agrodb\\" . ucfirst($this->url_module) . "\\Controladores\\IndexControlador";
						$this->url_controller = new $controller();
					}else{
						$page = new \Agrodb\Controladores\InicioControlador();
						$page->index();
					}
				}elseif (file_exists(APP . ucfirst($this->url_module) . '/Controladores/' . ucfirst($this->url_controller) . 'Controlador.php')){

					// Existe el módulo y controlador, creamos un objecto de la clase del controlador
					$controller = "\\Agrodb\\" . ucfirst($this->url_module) . "\\Controladores\\" . ucfirst($this->url_controller) . 'Controlador';
					$this->url_controller = new $controller();

					// Validamos si el método existe, en el controlador
					if (method_exists($this->url_controller, $this->url_action) && is_callable(array(
						$this->url_controller,
						$this->url_action))){

						if (! empty($this->url_params)){
							// Si existe parámetros los pasamos a método
							call_user_func_array(array(
								$this->url_controller,
								$this->url_action), $this->url_params);
						}else{
							// caso contrario llamamos al método sin parámetros
							$this->url_controller->{$this->url_action}();
						}
					}else{
						// si no existe validamos si existe el método index
						if (method_exists($this->url_controller, "index") && is_callable(array(
							$this->url_controller,
							"index"))){
							$this->url_controller->index();
						}else{
							header('location: ' . URL . 'error');
						}
					}
				}else{
					header('location: ' . URL . 'error');
				}
			}else{
				header('location: ' . URL . 'error');
			}
			if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev'){
				// echo '<a href="' . URL . ' " target="_blank" style="float:right"> Ver log</a><br>';
			}
		}else{
			$this->ejecutarProcesoAutomaticoCli($modulo, $controlador, $accion);
		}
	}

	/**
	 * Obtenemos y dividimos la URL, en Módulo, Controlador, Método y parámetros
	 */
	private function splitUrl(){
		if (isset($_SERVER['REQUEST_URI'])){

			$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // no selecciona los argmentos tipo ?h=n
			$path = str_replace(".php", "", $path);
			$pos = mb_stripos($_SERVER['REQUEST_URI'], "aplicacion");
			$url = substr($path, $pos + 17); // posición donde inicia la palabra aplicaición y restamos 17 posiciones para quitar aplicaciones/mvc/
			$url = trim($url, '/');
			$url = filter_var($url, FILTER_SANITIZE_URL);
			$url = explode('/', urldecode($url));
			// Nombre del Módulo
			$this->url_module = isset($url[0]) ? $url[0] : null;
			// Nombre del Controlador
			$this->url_controller = isset($url[1]) ? $url[1] : null;
			// Nombre del Método
			$this->url_action = isset($url[2]) ? $url[2] : null;

			// Borramos módulo, controlador, método. Dejamos los parámetros
			unset($url[0], $url[1], $url[2]);

			// Ponemos en un array los parametros tipo GET
			$this->url_params = array_values($url);

			// Impresión para depurar
			
			
			  /*echo '<br>Módulo: ' . $this->url_module . '<br>';
			  echo 'Controlador: ' . $this->url_controller . '<br>';
			  echo 'Método: ' . $this->url_action . '<br>';
			  echo 'Parámetros: ' . print_r($this->url_params, true) . '<br>';*/
		
			// exit();
		}
	}

	/**
	 * inicializar el proceso de cli
	 */
	private function ejecutarProcesoAutomaticoCli($modulo, $controlador, $accion){
		$controller = "\\Agrodb\\" . ucfirst($modulo) . "\\Controladores\\" . ucfirst($controlador) . 'Controlador';
		$this->url_controller = new $controller();
		$this->url_controller->{$accion}();
	}
}
