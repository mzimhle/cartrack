<?php

require_once 'abstract/crud.php';

class Animal extends Crud {
	// table for this class
    protected $_table = Table::ANIMAL;
	/**
	 * Our constructor
     * @return void
	 */	
	public function __construct() {
		// Get the global connection string.
		parent::__construct($this->_table);
	}
}

?>