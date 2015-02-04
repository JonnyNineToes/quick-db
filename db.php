<?php

	class Db { 
		private $setting = array( 
			'development' => array( // enter your development environment db credentials here
				'domain' => 'localhost', 
				'username' => 'root', 
				'password' => 'root'
			), 
			'production' => array( // enter your production environment db credentials here
				'domain' => '', 
				'username' => '', 
				'password' => ''
			), 
			'database' => NULL, // this variable is supplied by you when you instantiate this class
			'connection' => NULL // the actual connection to the database
		);

		// when instantiating this class, you need to supply the db name
		// environment is optional, but defaults to "development"
		public function __construct ($database, $environment = 'development') {
			// store user-supplied database name in class member variables
			$this->setting['database'] = $database;
			
			// reduce environment string to lowercase, then remove all non-alpha characters
			$environment = preg_replace('/[^a-z]+/', '', strtolower($environment));
			
			// this will allow you to use shorthand forms of your environment name when instantianting this class
			// supplying "d", or "dev", instead of typing out "development" will still work
			if (in_array($environment, array('development', 'dev', 'd', ''))) { // <--- Notice: if they pass a blank string as their environment, it counts as "development"
				// then CORRECTLY set environment variable
				$environment = 'development';
			// do the same for production
			} elseif (in_array($environment, array('production', 'prod', 'p'))) { 				
				$environment = 'production';
			// if the supplied environment doesn't match anything...
			} else {
				// stop and spit out error message
				exit ('Invalid environment. Choose "development" or "production".');
			}
			// create new database connection.
			// which set of supplied credentials gets used depends on the environment variable, see the above array in $settings
			$this->setting['connection'] = new mysqli($this->setting[$environment]['domain'], $this->setting[$environment]['username'], $this->setting[$environment]['password'], $this->setting['database']);
			// if there is an error encountered in connecting...
			if ($this->setting['connection']->connect_errno) {
				// stop and returnt the error message and number
				exit('Failed to connect to MySQL' . PHP_EOL . 'Error #: ' . $this->setting['connection']->connect_errno . PHP_EOL . 'Message: ' . $this->setting['connection']->connect_error . PHP_EOL);
			}
		}
		
		// takes a query and processes it
		// first argument is a query string, second is whether or not you want that query output to the report - useful for debugging and testing
		public function processQuery ($query, $output = FALSE) { 
			// trim whitespace off query
			$query = trim($query); 
			
			// if output is TRUE...
			if($output){
				// ...spit query out to page
				echo $query;
			}
			
			// if query is successful...
			if ($result = $this->setting['connection']->query($query)) { 
				// is it a resource?...
				if ($result instanceof mysqli_result) { 
					// does the result set contain 1 or more rows?...
					if ($result->num_rows >= 1) { 
						// then just return the result set
						$return = $result;
					// or are there zero rows in the result set?...
					} else { 
						// return a FALSE
						$return = FALSE;
						// unset results
						$result->free_result();
					}
				// is it a boolean?...
				} else if (is_bool($result)) { 
					// is it TRUE?...
					if ($result === TRUE) { 
						// if the affected rows are 1 or more...
						if ($this->setting['connection']->affected_rows >= 1) { 
							$return = TRUE;
						// if the affected rows are 0...
						} else { 
							$return = FALSE;
							$result->free_result();
						}
					// or is it FALSE?...
					} else { 
						$return = FALSE;
						$result->free_result();
					}
				// or is it something unspecified?...
				} else { 
					// then just return the result, so it can be analyzed
					$return = $result;
				}
			// if result is false due to error...
			} else 	{ 
				$return = FALSE;
			}
			// if there was a query error... 
			if ($this->setting['connection']->error) { 
				// stop and spit out an error message
				exit('Error: (#' . $this->setting['connection']->errno . ') ' . $this->setting['connection']->error . PHP_EOL);
			}
			// return the results
			return $return; 
		}
		
		// SHUT 'ER DOWN!!!...
		public function __destruct () {
			// close database connection
			$this->setting['connection']->close();
		}
	}
