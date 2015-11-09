<?php
/**
 * testProjectController class file.
 * 
 * @author alan.marble
 */

/**
 * testProjectController provides the basic controller class, to be extended by controllers
 * as needed.  It contains the basic methods for handling actions and rendering views.
 * 
 * @property string $layout name of the layout file to use for rendering views.  Layout files
 * are located in the root/views/default folder.
 *
 */
class testProjectController {
	
	protected $layout = 'default';
	
	public $accessControl = array();
	
	public function checkAccess($action) {
		$allow = true;
		if ($user = Auth::User()) {
			$user = $user->id;
		} else {
			$user = 0;
		}
		if (array_key_exists($action, $this->accessControl)) {
			$action = $this->accessControl[$action];
			$access = strtolower($action[0]);
			$users = $action[1];
			switch ($access) {
				case 'allow' :
						$allow = false;
						if ($users === '*') $allow = true;
						if ($users === '@' && $user != 0) $allow = true;
						if (is_array($users) && in_array($user, $users)) $allow = true;
					break;
				case 'deny' :
						$allow = true;
						if ($users === '*') $allow = false;
						if ($users === '@' && $user != 0) $allow = false; 
						if (is_array($users) && in_array($user, $users)) $allow = false;
					break;
			}	
		}
		return $allow;
	}
	
	/**
	 * Displays an error page when an invalid action is supplied to the controller
	 * @param string $action name of invalid action
	 */
	public function invalidAction($action) {	
		$this->render(array('default', 'pageNotFound'));
	}
	
	public function actionNotAllowed() {
		$this->render(array('default', 'notAllowed'));
	}
	
	/**
	 * Renders a view.  The view will be rendered within the layout file specified by the controller.
	 * @param string $view name of the view file
	 * @param array $data optional array of data to pass to the view file.  The array keys should correspond
	 * to the variable names used in the view file, while the values correspond to the value 
	 */
	public function render($view, $data = null, $layout = null) {
		if (is_array($view)) {
			$controller = $view[0];
			$view = $view[1];
		} else {
			$controller = $this->name;
		}
		$view = PROJECTROOT."/views/$controller/$view.php";
		ob_start();
		if (is_array($data)) extract($data);
		include($view);
		$content = ob_get_clean();
		if (!$layout) $layout = $this->layout;
		$layout = PROJECTROOT."/views/layouts/{$layout}.php";
		include($layout);
	}
	
	public function renderApi($success, $data = array(), $errors = array()) {
		$response = array();
		$response['result'] = $success ? 'success' : 'fail';
		if ($data) {
			$response['count'] = count($data);
			$response['data'] = $data;
		}
		if ($errors) {
			$response['errors'] = $errors;
		}
		echo json_encode($response);
	}
	
	/**
	 * Renders a partial view.  The view will be rendered independently, ie. not embedded within a layout.
	 * @param string $view name of the view file
	 * @param array $data optional array of data to pass to the view file.  The array keys should correspond
	 * to the variable names used in the view file, while the values correspond to the value 
	 */
	public function renderPartial($view, $data = null) {
		if (is_array($view)) {
			$controller = $view[0];
			$view = $view[1];
		} else {
			$controller = $this->name;
		}
		$view = PROJECTROOT."/views/$controller/$view.php";
		if (is_array($data)) extract($data);
		include($view);
	}
	
	/**
	 * Redirects the browser to another page and cleanly terminates processing of the current script.
	 * @param string $url relative URL to redirect to
	 */
	public function redirect($url) {
		header('Location: ' . HTMLROOT . $url);
		die();
	}
	
	/**
	 * Runs the controller.  The controller will look for an action specified in the query string, and then
	 * process the commands within a method named actionName, substituting Name for the name of the action.
	 * If none is found, it will call the default actionIndex method.  If a nonexistent action is specified,
	 * then the invalidAction method will be called.
	 */
	public function run() {
		if (isset($_GET['action'])) {
			$action = strtolower($_GET['action']);
		} else {
			$action = 'index';
		}
		
		if ($this->checkAccess($action)) {
			$action = "action" . ucwords($action);
		} else {
			$action = "actionNotAllowed";
		}
		
		if (method_exists($this, $action)) {
			$this->$action();
		} else {
			$this->invalidAction($action);
		}
	}
}