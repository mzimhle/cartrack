<?php

class Request  {
	private $_table = '';
	private $_config = null;
	/**
	 * Our constructor
	 *
     * @return void
	 */	
	public function __construct(string $table) {
		$this->_table = $table;
		/* Get the settings. */
		$config = parse_ini_file("config/settings.ini", true);
		// Check if settings exist for this
		if(isset($config[$_SERVER['HTTP_HOST']])) {
			$this->_config = $config[$_SERVER['HTTP_HOST']];
		} else {
			echo 'Site configuration missing...';
			exit;
		}	
	}
	/**
	 * Insert data to its respective table
	 *
	 * @param array $data
     * @return array
	 */
    public function insert(array $data) {
		// Build the query
		$url =  $this->_config['api_url_insert']. '?entity='.$this->_table;
		foreach($data as $key => $value) {
			$url .= "&$key=$value";
		}
		// Post the data.
		return $this->post($url);
    }
	/**
	 * Update data to its respective table
	 *
	 * @param array $data
     * @return array
	 */
    public function update(array $data, int $id) {
		// Build the query
		$url =  $this->_config['api_url_update']. '?entity='.$this->_table.'&id='.$id;
		foreach($data as $key => $value) {
			$url .= "&$key=$value";
		}
		// Post the data.
		return $this->post($url);
    }
	/**
	 * Update data to its respective table
	 *
	 * @param array $data
     * @return array
	 */
    public function delete(int $id) {
		// Build the query
		$url =  $this->_config['api_url_delete']. '?entity='.$this->_table.'&id='.$id;
		// Post the data.
		return $this->post($url);
    }
	/**
	 * Get a single item with 
	 *
	 * @param string $cell
	 * @param int|null $id
     * @return array|false
	 */
    public function getId(int $id): ?array {
		// Build the query
		$url =  $this->_config['api_url_view']. '?entity='.$this->_table.'&id='.$id;
		// Post the data.
		$data = $this->post($url);

		if($data) {
			return $data;
		}
		return false;
    }
	/**
	 * Get the search the select table
	 *
	 * @param string $cell
	 * @param int|null $id
     * @return array
	 */
    public function search(int $start, int $length, array $filter): array {
		// Build the query
		$url =  $this->_config['api_url_search']. '?entity='.$this->_table.'&iDisplayStart='.$start.'&iDisplayLength='.$length;
		foreach($filter as $key => $value) {
			$url .= "&$key=$value";
		}
		// Post the data.
		return $this->post($url);
    }
	/**
	 * Post function to request api using curl
	 *
	 * @param string $url
     * @return array
	 */
	private function post($url) {
		// Initiate curl
		$ch = curl_init();
		// Will return the response, if false it print the response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Set the url
		curl_setopt($ch, CURLOPT_URL, $url);
		// Execute
		$result = curl_exec($ch);
		// Closing
		curl_close($ch);
		return json_decode($result, true);
	}
}
?>