<?php

require_once 'class/member.php';
require_once 'class/animal.php';
require_once 'enum/table.php';

class Request {
	// Object to be used after we get our 
	public $_object = null;
	/**
	 * Constructor to get the object to be used.
	 *
	 * @param string $table
     * @return void
	 */
    public function __construct(string $table)
    {
        if ($table == Table::MEMBER) {
			$this->_object = new Member();
		} else if($table == Table::ANIMAL) {
			$this->_object = new Animal();
        } else {
            echo json_encode(array('code' => 500, 'message' => "Table to be used not correct, please add a correct table"));
			exit;
		}
    }
}
?>