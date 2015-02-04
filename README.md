# quick-db
This is a lightweight PHP class designed to set up your database connection quickly, and to make running queries easier.

Getting Started:
1. Download and open the zip file containing the db.php file.
2. Copy and paste db.php to where ever you want it to go.
3. Open db.php and set your development database credentials. If you want, you can set your (live) production credentials as well.
4. That's it! Follow the example below for how-to-use suggestions.

	EXAMPLE OF USE:
----------------------------------------
	require 'db.php';
	$db = array(); // so you can have multiple database connections if you need to
	$db['db_name'] = new Db('db_name','dev');

	if ( $things = $db['db_name']->processQuery( 'SELECT * FROM `thing`' ) ) {
		while ( $thing = $things->fetch_array( MYSQLI_ASSOC ) ) {
			...
		}
	}
	
	// ...and when you're done... (not mandatory, obviously)
	unset($db['db_name']);
