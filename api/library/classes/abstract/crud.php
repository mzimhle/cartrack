<?php

require_once 'config/database.php';

abstract class Crud {
	// _connection adapter.
	private $_conn		= null;
	private $_table		= null;
	// property to output for all functions
	private $_output	= array('code' => 200, 'message' => '', 'record' => array(), 'count' => 0, 'display' => 0);
	/**
	 * Get database connection to be able to use crud.
	 *
 	 * @param string $table	 
     * @return void
	 */		 
	function __construct(string $table) {
		// Get the global _connection string.
		global $conn;		
		$this->_conn = $conn;
		$this->_table = $table;
	}
	/**
	 * Disconnect from the database after the instance of class has been destroyed.
	 *
     * @return void|array
	 */		 
	function __destruct() {
		// Cllose the database connection.
		if(!pg_close($this->_conn)) {
			echo json_encode($this->output(500, 'Could not disconnect from the database.'));
			exit;
		}
	}
	/**
	 * Insert data to a table
	 * @param array $data
     * @return array
	 */ 
	public function insert(array $data): array {		
        // add a timestamp
        $data['added'] = date('Y-m-d H:i:s');
		$res = pg_insert($this->_conn, $this->_table, $data, PG_DML_ESCAPE);
		// Check if all is well.
		if ($res) {
			$id = pg_last_oid($res);
			return $this->single($id);
		} else {
			return $this->output(500, 'No record found');
		}
    }
	/**
	 * Update the database record
	 * @param array $data
	 * @param array $where
     * @return array
	 */
    public function update(array $data, array $where): array {
        // add a timestamp
        $data['updated'] = date('Y-m-d H:i:s');
		$res = pg_update($this->_conn, $this->_table, $data, $where);
		// Check if all is well.
		if ($res) {
			return $this->output(200, 'Record successfully updated');
		} else {
			return $this->output(500, 'Record was not updated');
		}
    }
	/**
	 * Delete row(s) in the database table
	 * @param array $where
     * @return boolean
	 */
    public function delete(array $where) {
		// Return data.	
		$res = pg_delete($this->_conn, $this->_table, $where, PG_DML_ESCAPE);
		// Check if all is well.
		if ($res) {
			return $this->output(200, 'Successfullly deleted');
		} else {
			return $this->output(500, 'No record was deleted');
		}
    }
	/**
	 * Select a single record.
	 * @param integer $id
     * @return array
	 */
    public function single(int $id): array {
		// Get a single row of the database.
		$rec = pg_query($this->_conn, $this->_table, array('id' => $id), PG_DML_ESCAPE);
		if($rec) {
			while ($row = pg_fetch_assoc($rec)) {
			  return $this->output(200, '', $row);
			}
		} else {
			return $this->output(500, 'No record was found for the ID: '.$id);
		}
    }
	/**
	 * Fetch records or a single record by given query
	 *
	 * @param string $query
	 * @param bool $singlerecord	 
	 * @param array $parameters	 
     * @return array
	 */
    protected function fetch(string $query, bool $singlerecord, array $parameters = array()): array {
		// Variable to return data fetched
		$result = array();
		// Get a single row of the database.
		$rec = pg_query_params($this->_conn, $query, $parameters);
		if($rec) {
			while ($row = pg_fetch_assoc($rec)) {
				if(!$singlerecord) {
					$result[] = $row;
				} else {
					return $row;
				}
			}
		}
		return $result;
    }
	/**
	 * Get paginated results for this table.
 	 * @param integer $start
 	 * @param integer $length	 
 	 * @param array $filter	 	 
     * @return array
	 */	
	public function paginate(int $start, int $length, $filter = array()): array {
	
		$where	= 'name is not null';

		if(count($filter) > 0) {
			for($i = 0; $i < count($filter); $i++) {
				if(isset($filter[$i]['filter_search']) && trim($filter[$i]['filter_search']) != '') {
					$array = explode(" ",trim($filter[$i]['filter_search']));					
					if(count($array) > 0) {
						$where .= " and (";
						for($s = 0; $s < count($array); $s++) {
							$text = pg_escape_string($array[$s]);
							$this->sanitize($text);
							$where .= "lower(name) like lower('%$text%')";
							if(($s+1) != count($array)) {
								$where .= ' or ';
							}
						}
					}
					$where .= ")";
				}
			}
		}
		// Show the query.
		$query = "select * from {$this->_table} where $where";
		// Count the total number of records returned.
		$result_count = $this->fetch("select count(*) as query_count from ($query) as query", true);
		// Get the rows from database with the limit for pagination.
		$result = $this->fetch($query . " offset $1 limit $2", false, array($start, $length));
		// Format the data to display it.
		return $this->output(200, 'Records found', $result, $result_count['query_count'], count($result));
	}
	/**
	 * Functions used to sanitized a string on pagination method. Passed by reference
	 *
 	 * @param string &$string 
     * @return void
	 */		
    private function sanitize(&$string) { 
		$string = preg_replace("/[^a-zA-Z0-9_]+/", "", $string);
	}
	/**
	 * Functions used to sanitized an array ywith strings on pagination method. Array is passed by reference.
	 *
 	 * @param array &$array 
     * @return void
	 */		
    private function sanitizeArray(&$array) { 
		for($i = 0; $i < count($array); $i++) { 
			$array[$i] = preg_replace("/[^a-zA-Z0-9_]+/", "", $array[$i]); 
		} 
	}
	/**
	 * Out function for returning data that is in the same structure in an array for all methods in this abstract class.
	 *
 	 * @param int $code 
 	 * @param string $message 
 	 * @param array $records 
 	 * @param int $count 	 
 	 * @param int $display	 
     * @return array
	 */		
	private function output(int $code, string $message, array $records = array(), int $count = 0, int $display = 0): array {
		$this->_output['code'] = $code;
		$this->_output['message'] = $message;
		$this->_output['record'] = $records;
		// For pagination
		$this->_output['count'] = $count;
		$this->_output['display'] = $display;
		return $this->_output;
	}
}

?>