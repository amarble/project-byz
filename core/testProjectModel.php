<?php
/**
 * testProjectModel class file
 * 
 * @author alan.marble
 */

/**
 * testProjectModel provides the basic model class, to be extended by models as needed in the
 * application.  It contains a set of basic methods for CRUD functionality.
 * 
 * @property array $attributes attribute values (name => value)
 * @property string $pk name of the attribute used as the primary key in the database
 * @property boolean $isNew indicates whether a model has been saved to the database or not
 * @property string $name the name of the database table storing the data for this model
 * @property array $relationships list of related models 
 *
 */
class testProjectModel {
	
	protected $attributes = array();
	protected $validators = array();
	protected $validationErrors = array();
	protected $pk;
	protected $isNew = true;
	protected $name;
	protected $relationships = array();
	protected $deep = false;
	protected $filterByUser = true;
	
	public function getValidationErrors() {
		return $this->validationErrors;
	}
	
	public function validate() {
		$errors = array();
		foreach ($this->validators as $attribute => $validatorList) {
			foreach ($validatorList as $validator) {
				$validatorMethod = 'validate' . ucwords($validator);
				if (method_exists($this, $validatorMethod)) {
					$error = $this->$validatorMethod($attribute);
					if ($error) {
						$errors[] = $error;
					}
				} else {
					throw new Exception('Unknown validator : ' . $validator);
				}
			}
		}
		if ($errors) {
			$this->validationErrors = $errors;
			return false;
		} else {
			return true;
		}
	}

	public function validateRequired($attribute, $message = 'Missing parameter : attribute') {
		if (!isset($this->attributes[$attribute]) || !$this->attributes[$attribute]) {
			return str_replace('attribute', $attribute, $message);
		} else {
			return false;
		}
	}
	
	public function validateNumeric($attribute, $message = 'attribute must be numeric') {
		if (!is_numeric($this->attributes[$attribute])) {
			return str_replace('attribute', $attribute, $message);
		} else {
			return false;
		}
	}
	
	public function __construct($attributes = array(), $deep = false) {
		$this->attributes = $attributes;
		$this->deep = $deep;
	}
	
	/**
	 * __get magic method
	 * @param string $attribute name of attribute to retrieve
	 * @return mixed the value of the attribute if it exists in the $attributes array, or null
	 * if it does not.  If the attribute is used as a foreign key linking to a related model it
	 * will return the appropriate model rather than a value.
	 * 
	 */
	public function __get($attribute) {
		if (array_key_exists($attribute, $this->relationships) && $this->deep) {
			if ($this->relationships[$attribute]['relationship'] == 'belongsTo') {
				$model = $this->relationships[$attribute]['model'];
				$foreignKey = $this->attributes[$attribute];
				return $model::model()->getByPK($foreignKey);

			} else {
				return null;
			}
		} else if (is_array($this->attributes[$attribute])) { 
			return $this->attributes[$attribute];
		} else {
			return $this->attributes[$attribute];
		}
	}
	
	/**
	 * __set magic method
	 * @param string $attribute name of attribute
	 * @param mixed $value value to set
	 */
	public function __set($attribute, $value) {
		$this->attributes[$attribute] = htmlspecialchars($value);
	}
	
	/**
	 * Method that is called before deleting a record
	 * Override this method if special processing is required before deleting a record.  If 
	 * this method returns false, the delete will be aborted.
	 * @return bool whether or not the deletion of the record should proceed
	 */
	public function beforeDelete() {
		return true;
	}
	
	/**
	 * Deletes the record associated with this model from the database.  Before deleting, the
	 * beforeDelete() method is called.  If it returns anything other than true, this method
	 * will return false without deleting the record.
	 * @return mixed false if aborted, or the number of records affected by the delete
	 */
	public function delete() {
		if (!$this->beforeDelete()) return false;
		return testProject::DBH()->exec("DELETE FROM {$this->name} WHERE id = {$this->id}");
	}
	
	/**
	 * Method that is called before saving a record
	 * Override this method if special processing is required before saving a record.  If 
	 * this method returns false, the save will be aborted.
	 * @return bool whether or not the saving of the record should proceed
	 */
	public function beforeSave() {
		return $this->validate();
	}
	
	public function afterSave() {
		return null;
	}
	
	
	/**
	 * Saves the model to the database.
	 * Depending on the value of the $isNew property, a new record will be inserted into the database
	 * for the model, or the existing entry will be updated.  If the beforeSave() method returns false,
	 * this method will be terminated and return false without saving the record.
	 * @return bool whether the save was successful or not
	 */
	public function save() {
		if (!$this->beforeSave()) return false;
		$fieldsToUpdate = $this->attributes;
		unset($fieldsToUpdate[$this->pk]);
		$fields = array_keys($fieldsToUpdate);
		if ($this->isNew) {
			$fieldNames = implode(', ', $fields);
			$fieldValues = implode(', :', $fields);
			$STH = testProject::DBH()->prepare("INSERT INTO {$this->name} ($fieldNames) VALUES (:$fieldValues)");
			if ($STH->execute($fieldsToUpdate)) {
				$this->{$this->pk} = testProject::DBH()->lastInsertId(); 
				$this->afterSave();
				$this->setIsNew(false);
				return true;
			} else {
				var_dump($STH->errorInfo());
				return false;
			}
		} else {
			$fieldList = array();
			foreach ($fieldsToUpdate as $fieldName => $fieldValue) {
				$fieldList[] = "$fieldName = :$fieldName";
			}
			$fieldList = implode(', ', $fieldList);
			$STH = testProject::DBH()->prepare("UPDATE {$this->name} SET $fieldList WHERE {$this->pk} = :{$this->pk}");
			if ($STH->execute($this->attributes)) {
				$this->afterSave();
				return true;
			} else {
				return false;
			}
		}
	}
	
	/**
	 * Sets the attributes of the current record based on an input array
	 * @param array $attributes array of (attribute => value) to set
	 */
	public function setAttributes($attributes = array()) {
		foreach ($attributes as $attribute => $value) {
			$this->attributes[$attribute] = $value;
		}
	}
	
	/**
	 * Gets a single record from the database
	 * @param string $query formatted SQL query  
	 * @param array $params optional array of parameter values to use if the query contains bound parameters
	 * @return model matching the query or null if not found
	 */
	public function get($query, $params = null) {
		$STH = testProject::DBH()->prepare($query);
		if (!empty($params)) {
			$STH->execute($params);
		} else {
			$STH->execute();
		}
		if ($attributes = $STH->fetch(PDO::FETCH_ASSOC)) {
			$model = new static($attributes);
			$model->setIsNew(false);
			return $model;
		} else {
			return null;
		}
	}
	
	/**
	 * Returns all matching records from the database.
	 * @param string $query optional formatted SQL query.  If omitted, all records from the table will be returned
	 * @param array $params optional array of parameter values to use if the query contains bound parameters
	 * @param array $pagination optional array of pagination parameters, see documentation of getPagination method 
	 * @return array of models of matched records, or an empty array if none found
	 */
	public function getAll($conditions = null, $params = null, $pagination = array()) {
		$query = Query::build()->
					select('*')->
					from($this->name)->
					bind($this->pk, $pk);
		if ($this->filterByUser) $query = $query->where('user', '=', Auth::User()->id);
		if ((!empty($pagination['sortAttribute'])) && ($pagination['sortDirection'] != 'NONE')) {
			$query = $query->order($pagination['sortAttribute'], $pagination['sortDirection']); 
		}
		$attributes = $query->get();
		if ($attributes) {
			$models = array();
			foreach ($attributes as $attributeSet) {
				$model = new static($attributeSet, $this->deep);
				$model->setIsNew(false);
				$models[] = $model;
			}
			return $models;
		} else {
			return null;
		}
	
		$query = "SELECT * FROM {$this->name}";
		if (!empty($conditions)) {
			$query .= " WHERE $conditions";
		}
		if (!empty($pagination)) {
			$query .= $this->getPagination($pagination);
		}

		$STH = testProject::DBH()->prepare($query);
		
		if (!empty($params)) {
			$STH->execute($params);
		} else {
			$STH->execute();
		}
		$result = array();
		while ($attributes = $STH->fetch(PDO::FETCH_ASSOC)) {
			$model = new static($attributes, $this->deep);
			$model->setIsNew(false);
			$result[] = $model;
		}
		return $result;
	}
	
	/**
	 * Returns all the number of matching records found in the database
	 * @param string $query optional formatted SQL query.  If omitted, all records from the table will be returned
	 * @param array $params optional array of parameter values to use if the query contains bound parameters
	 * @return integer number of matching records found in the database
	 */	
	public function getCount($conditions = null, $params = null) {
		$query = Query::build()->
					from($this->name)->
					bind($this->pk, $pk);
		if ($this->filterByUser) $query = $query->where('user', '=', Auth::User()->id);

		$count = $query->count();
		
		return $count;
		
		$query = "SELECT COUNT(*) FROM {$this->name}";
		if (!empty($conditions)) {
			$query .= " WHERE $conditions";
		}
		$STH = testProject::DBH()->prepare($query);
		if (!empty($params)) {
			$STH->execute($params);
		} else {
			$STH->execute();
		}
		$result = $STH->fetchColumn();
		return $result;
	}
	
	/**
	 * Gets a record matching primary key value
	 * @param mixed $pk string or numeric value to search for in primary key
	 * @return model matching the search or null if not found
	 */
	public function getByPK($pk) {
		$query = Query::build()->
					select('*')->
					from($this->name)->
					where($this->pk, '=', $this->pk, true)->
					bind($this->pk, $pk);
		if ($this->filterByUser) $query = $query->where('user', '=', Auth::User()->id);
		$attributes = $query->getOne();
		if ($attributes) {
			$model = new static($attributes, $this->deep);
			$model->setIsNew(false);
			return $model;
		} else {
			return null;
		}
	}
	
	/**
	 * Gets a record matching a specific attribute and value
	 * @param string $attribute name of the attribute to match against
	 * @param mixed $value value to match
	 * @return model matching the search or null if not found
	 */
	public function getByAttribute($attribute, $value) {
		$query = "SELECT * FROM {$this->name} WHERE $attribute = :$attribute";
		$params = array($attribute => $value);
		return $this->get($query, $params);
	}
	
	public function getByAttribues($attributes) {
		$conditions = array();
		$params = array();
		foreach ($attributes as $attribute => $value) {
			$conditions[] = "$attribute = :$attribute";
		}
		$conditions = implode(" AND ", $conditions);
		$query = "SELECT * FROM {$this->name} WHERE $conditions";
		return $this->get($query, $attributes);
	}
	
	/**
	 * Sets the $isNew property
	 * @param boolean $isNew value to save
	 */
	public function setIsNew($isNew) {
		$this->isNew = $isNew;
	}
	
	/**
	 * Creates SQL syntax for pagination
	 * @param array $pagination the pagination parameters in a multidimensional array of the following structure:
	 * $pagination = array(
	 *     'sortAttribute' => 'nameOfAttributeToSort',
	 *     'sortDirection' => 'ASC|DESC',
	 *     'limit' => array(
	 *          0,
	 *          10,
	 *     )
	 * );
	 * @return string formatted SQL string with ORDER BY and/or LIMIT for pagination
	 */
	protected function getPagination($pagination) {
		$result = '';
		if ((!empty($pagination['sortAttribute'])) && ($pagination['sortDirection'] != 'NONE')) {
			$result .= " ORDER BY {$pagination['sortAttribute']} {$pagination['sortDirection']}";
		}
		if (!empty($pagination['limit'])) {
			$limit = $pagination['limit'][0] * $pagination['limit'][1];
			$result .= " LIMIT $limit, {$pagination['limit'][1]}";
		}
		return $result;
	}
	
	/**
	 * Generates an instance of the current model.  Useful for various get calls.  
	 * @return model instance of the current model
	 */
	public static function model($deep = false) {
		$model = new static('', $deep);
		return $model;
	}

	
}