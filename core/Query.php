<?php

class Query {

	private $selects = array();
	private $wheres = array();
	private $tables = array();
	private $orders = array();
	private $groups = array();
	private $updates = array();
	private $bindings = array();
	private $sets = array();
	private $delete = false;
	
	public function bind($parameter, $value) {
		$this->bindings[$parameter] = $value;
		return $this;
	}
	
	public static function build() {
		$query = new static();
		return $query;
	}
	
	public function count() {
		$tables = implode(', ', $this->tables);
		$query = "SELECT COUNT(*) AS count FROM $tables";
		
		if (count($this->wheres) > 0) {
			$wheres = implode(' AND ', $this->wheres);
			$query .= " WHERE $wheres";
		}
		
		$STH = testProject::DBH()->prepare($query);
		if (count($this->bindings) > 0) {
			$STH->execute($this->bindings);
		} else {
			$STH->execute();
		}
		
		$row = $STH->fetch(PDO::FETCH_ASSOC);
		
		return $row['count'];
	}
	
	public function delete() {
		$tables = implode(', ', $this->tables);
		$query = "DELETE FROM $tables";
		if (count($this->wheres) > 0) {
			$wheres = implode(' AND ', $this->wheres);
			$query .= " WHERE $wheres";
		}
		$STH = testProject::DBH()->prepare($query);
		if (count($this->bindings) > 0) {
			$STH->execute($this->bindings);
		} else {
			$STH->execute();
		}
		
		return $STH;
	}
	
	public function from($table, $alias = null) {
		if ($alias) $table = "$table $alias";
		$this->tables[] = $table;
		return $this;
	}
	
	public function get($debug = false) {
		$STH = $this->run($debug);
		$results = array();
		while ($result = $STH->fetch(PDO::FETCH_ASSOC)) {
			$results[] = $result;
		}
		if (count($results) < 1) return false;
		return $results;
	}
	
	public function getOne() {
		$result = $this->get();
		if (is_array($result)) return $result[0];
		return false;
	}
	
	public function group($column, $table = null) {
		if ($table) $column = "{$table}.{$column}";
	}
	
	public function insert() {
		$tables = implode(', ', $this->tables);
		$sets = implode(', ', $this->sets);
		$query = "INSERT INTO $tables SET $sets";
		$STH = testProject::DBH()->prepare($query);
		if (count($this->bindings) > 0) {
			$STH->execute($this->bindings);
		} else {
			$STH->execute();
		}
		
		return $STH;
	}
	
	public function select($column, $alias = null, $table = null) {
		if ($alias) { 
			$column = "`$column` AS $alias";
		} else if ($column !== '*') {
			$column = "`$column`";
		}
		if ($table) $column = "`{$table}`.{$column}";
		$this->selects[] = $column;
		return $this;
	}
	
	public function order($column, $order = "ASC", $table = null) {
		$order = "$column $order";
		if ($table) $order = "{$table}.{$order}";
		$this->orders[] = $order;
		return $this;
		
	} 
	
	public function run($debug = false) {
		$query = '';
		if (count($this->selects) > 0) { //Build a select query
			$selects = implode(', ', $this->selects);
			$tables = implode(', ', $this->tables);
			$query = "SELECT $selects FROM $tables";
		}
		
		if (count($this->wheres) > 0) {
			$wheres = implode(' AND ', $this->wheres);
			$query .= " WHERE $wheres";
		}
		
		if (count($this->orders) > 0) {
			$orders = implode(', ', $this->orders);
			$query .= " ORDER BY $orders";
		}
		
		if ($debug) var_dump($query);
		if ($debug) var_dump($this->bindings);
		
		$STH = testProject::DBH()->prepare($query);
		if (count($this->bindings) > 0) {
			$STH->execute($this->bindings);
		} else {
			$STH->execute();
		}
		
		return $STH;
	}
	
	public function set($column, $value, $bind = false, $table = null) {
		if($bind) {
			$set = "`$column` = :{$value}";
		} else {
			$set = "`$column` = $value";
		}
		if ($table) $set = "`{$table}`.{$set}";
		$this->sets[] = $set;
		return $this;
	}
	
	public function update() {
		
	}
	
	public function where($column, $condition, $value, $bind = false, $table = null) {
		if ($bind) {
			$where = "$column $condition :{$value}";
		} else {
			$where = "$column $condition $value";
		}
		if ($table) $where = "{$table}.{$where}";
		$this->wheres[] = $where;
		return $this;
	}

}