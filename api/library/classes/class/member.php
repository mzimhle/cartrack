<?php

require_once 'abstract/crud.php';

class Member extends Crud {
	// table for this class
    protected $_table = Table::MEMBER;
	/**
	 * Our constructor
     * @return void
	 */	
	public function __construct() {
		// Get the global connection string.
		parent::__construct($this->_table);
	}
	/**
	 * Get record by cellphone number
	 *
	 * @param string $cell
	 * @param int|null $id
     * @return array
	 */
    public function getByCellphone(string $cell, int $id = null): array {
		// Lets check if we checking for a record or about to add a new one.
		if($id == null) {
			return $this->fetch("select * from {$this->_table} where cellphone = '$1';", true, array($this->validateNumber($cell)));
		} else {
			return $this->fetch("select * from {$this->_table} where cellphone = '$1' and id != $2;", true, array($this->validateNumber($cell), $id));
		}
    }
	/**
	 * Get record by email address
	 *
	 * @param string $email
	 * @param int|null $id
     * @return array
	 */
    public function getByEmail(string $email, int $id = null): array {
		// Lets check if we checking for a record or about to add a new one.
		if($id == null) {
			return $this->fetch("select * from {$this->_table} where email = '$1';", true, array($this->validateEmail($cell)));
		} else {
			return $this->fetch("select * from {$this->_table} where email = '$1' and id != $2;", true, array($this->validateEmail($cell), $id));
		}
    }	
	/**
	 * Validate email address if any has been given.
	 *
	 * @param string $string
     * @return string
	 */	
	protected function validateEmail($string) {
		if(!filter_var(pg_escape_string($string), FILTER_VALIDATE_EMAIL)) {
			return '';
		} else {
			return pg_escape_string(trim($string));
		}
	}
	/**
	 * Validate cellphone number, we want it to be a south african number that starts with a 0 and is ONLY 10 digits
	 *
	 * @param string $string
     * @return string
	 */	
	public function validateNumber($string) {
		if(preg_match('/^0[0-9]{9}$/', $this->onlyNumber(trim(pg_escape_string($string))))) {
			return $this->onlyNumber(trim(pg_escape_string($string)));
		} else {
			return '';
		}
	}
	/**
	 * Clean up the cellphone number vefore validating it
	 *
	 * @param string $string
     * @return string
	 */	
	private function onlyNumber($string) {
		// Remove some weird characters if any have been added. 
		$string = preg_replace('/\D/',"", strip_tags($string));		
		return $string;
	}	
}

?>