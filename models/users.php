<?php
/**
 * categories model class file
 * 
 * @author alan.marble
 */

/**
 * categories model class
 * 
 * The categories model class contains the basic definition for the categories model, with some
 * methods overriding the base testProjectModel class for extra functionality.
 * 
 */
class users extends testProjectModel {
	
	protected $attributes = array(
		'id' => '', 
		'username' => '', 
		'email' => '', 
		'password' => '', 
		'token' => '',
	);
	
	protected $validators = array(

	);
	
	protected $pk = 'id';
	
	protected $name = "users";
	
	/**
	 * Checks if the category is new, and sets the created and modified times appropriately,
	 * prior to saving.
	 * @see testProjectModel::beforeSave()
	 */
	public function beforeSave() {
		if ($this->isNew) { //Things to do before user creation
			$this->created = date("Y-m-d H:i:s");
			$this->modified = $this->created;
			$this->hashPassword();
		} else {
			$this->modified = date("Y-m-d H:i:s");
		}
		return true;
	}
	
	public function afterSave() {
		if ($this->isNew) { //Things to do after new user creation
			$status = new statuses(array('name' => 'Pending (ordered)', 'user' => $this->id));
			$status->save();
			$status = new statuses(array('name' => 'Owned', 'user' => $this->id));
			$status->save();
			$status = new statuses(array('name' => 'Listed', 'user' => $this->id));
			$status->save();
			$status = new statuses(array('name' => 'Sold', 'user' => $this->id));
			$status->save();
		}
	}
	
	public function hashPassword() {
		$this->passwordSalt = substr(base64_encode(mcrypt_create_iv(64)), 0, 64);
		$this->passwordHash = hash('sha256', $this->passwordSalt . $this->password);
		unset($this->attributes['password']);
		unset($this->attributes['repeatPassword']);
	}
	
	public function login($username, $password) {
		$model = $this->getByAttribute('username', $username);
		if ($model) {
			if (hash('sha256', $model->passwordSalt . $password) == $model->passwordHash) {
				$model->sessionToken = substr(base64_encode(mcrypt_create_iv(64)), 0, 64);
				$model->save();
				Auth::login($model->sessionToken);
				return $model;
			}
		}
		return false;
	}
	
	public function getColumns() {
		$query = Query::build()->
					select('column')->
					from('userColumns')->
					where('user', '=', 'user', true)->
					bind('user', $this->id)->
					get();
		$columns = array();
		foreach ($query as $row) {
			$columns[] = $row['column'];
		}
		return array_reverse($columns);
	}
	
	public function setColumns($columns) {
		if (!is_array($columns)) $columns = explode(',', $columns);
		$query = Query::build()->
				from('userColumns')->
				where('user', '=', 'user', true)->
				bind('user', $this->id)->
				delete();
		foreach ($columns as $column) {
			$column = trim($column);
			$query = Query::build()->
					from('userColumns')->
					set('user', 'user', true)->
					set('column', 'column', true)->
					bind('user', $this->id)-> 
					bind('column', $column)->
					insert();
		}
	}
	
}