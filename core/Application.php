<?php
/**
 * testProject class file
 * 
 * @author alan.marble
 */

/**
 * Gets the root directory of the project
 */
define("PROJECTROOT", dirname(__FILE__) . "/..");

/**
 * Gets the root HTML for the project
 */
//define("HTMLROOT", dirname($_SERVER['SCRIPT_NAME']) . '/');
define("HTMLROOT", 'http://agdragon.net/test_project/');

require_once("config.php");

/**
 * Base testProject application class.  Contains some static functions and global settings that will 
 * be used by the test project.
 */
class testProject {
	
	private static $DBH;
	
	/**
	 * Gets a connection to the database using the PDO drivers
	 * @return PDO instance of the connection to the database
	 */
	public static function DBH() {
		if (!self::$DBH)

			self::$DBH = new PDO("mysql:host=" . config::DBHOST . ";dbname=" . config::DBDATABASE, config::DBUSER, config::DBPASSWORD);

		return self::$DBH;
	}
	
	/**
	 * Autoloading function
	 * @param string $class name of class to autoload
	 */
	public static function autoload($class) {
		$classfile = $class . ".php";
		$root = dirname(__FILE__) . "/..";
		$directories = array(
			"$root/core/",
			"$root/models/",
			"$root/controllers/",
			"$root/components/",
		);
		foreach ($directories as $directory) {
			$path = $directory . $classfile;
			if (file_exists($path)) {
				include($path);
				return;
			}
		}
	}
	
	/**
	 * Creates an instance of testProject
	 * @param mixed $conf configuration settings.
	 * If a string, it should contain the path to a file that contains the configuration settings
	 * If an array, it should contain the actual configuration settings
	 * @return testProject
	 */
	public static function create($conf = null) {
		return new testProject($conf);
	}
	
	/**
	 * Sets an alert message that can be retrieved later.  If a previous alert has been stored, calling this
	 * method again will overwrite the existing message.
	 * @param string $message the text of the alert message
	 * @param string $class the class of the alert message, valid values are info, success, warning and danger
	 */
	public static function setAlert($message, $class) {
		$_SESSION['alert']['message'] = $message;
		$_SESSION['alert']['class'] = $class;
	}
	
	/**
	 * Retrieves an alert message stored by the setAlert() method.  This will also remove it from storage so
	 * that it is not accidentally retrieved twice.
	 * @return mixed either a message array (message => class) or null if none
	 */
	public static function getAlert() {
		if (!empty($_SESSION['alert'])) {
			$alert = $_SESSION['alert'];
			unset ($_SESSION['alert']);
		} else {
			$alert = false;
		}
		return $alert;
	}
	
	/**
	 * Runs the application, loading the appropriate controller based on the query string.  If none is given,
	 * the defaultController will be run.
	 */
	public function run() {
		if (isset($_GET['controller'])) {
			$controllerName = $_GET['controller'] . 'Controller';
			$controller = new $controllerName;
		} else {
			$controller = new defaultController;
		}
		$controller->run();
	}

}