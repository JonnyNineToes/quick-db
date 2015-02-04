# quick-db

This is a lightweight PHP class designed to set up your database connections quickly, and to make running queries easier.

## Getting Started:

1.	Download and open the .zip file containing the **db.php** file.
2.	Move, or copy and paste the **db.php** file to where ever you want it to go in your project.
3.	Open **db.php** and set your development and production (live) database credentials.
4.	That's it! Follow the example below for how-to-use suggestions.

## Example of Use:

	require 'db.php';
	$db = array(); // so you can have multiple database connections if you need to
	$db['db_name'] = new Db('db_name', 'dev');

	if ( $things = $db['db_name']->query( 'SELECT * FROM `thing`' ) ) {
		while ( $thing = $things->fetch_array( MYSQLI_ASSOC ) ) {
			...
		}
	}
	
	// ...and when you're done... (not mandatory, obviously)
	unset($db['db_name']);
