# laravel-odbc-db2 (Development paused)
ODBC-based DB2 driver for Laravel5 Eloquent

Thispackage will provide an ODBC-based driver for IBM's DB2 to hopefully work on LUW (Linux Unix Windows), as opposed to IBMi.

There appear to be two ways to connect to DB2. The first uses an IBM PDO extension (via pecl), but this seems to be really old and unless you want to hack around, trying to get it to compile, it is best avoided. We'll be building an ODBC driver usin unixODBC under Ubuntu 14.04.

*Details on installing DB2 Express C under Ubuntu 14.04 with no GUI*

*Details on bulding ODBC driver using unixODBC*

*Test PDO using DSN to give a 'connection succeeded' message*

Once the above has been done, we need to get it working with Laravel.

1. Install this package via composer:

    ```bash
    composer require opb/laravel-odbc-db2
    ```
2. Replace the Illuminate DatabaseServiceProvider with our own in `app.php`:

    ```php
    'providers' => [
    
    	//Illuminate\Database\DatavaseServiceProvider,
    	Opb\LaravelOdbcDb2\DatabaseServiceProvider,
    ```
3. Add our new database config into the `connections` array in `database.php`, under the `odbc` key. Note that we're using `.env` variables, and that the `odbc_driver` variable is set to what we named our driver when we created it with unixODBC:

    ```php
    	'odbc' => [
			'driver'         => 'odbc',
			'host'           => env('DB2_HOST', 'localhost'),
			'database'       => env('DB2_DATABASE', ''),
			'username'       => env('DB2_USER', 'db2inst1'),
			'password'       => env('DB2_PASSWORD', 'password'),
			'port'			 => env('DB2_PORT', 50000),
			'schema'		 => 'DB2INST1',
			'odbc_driver'    => 'DB2', // name of driver created by unixODBC
		],
    ```
4. If this is your default DB connection, remember to modify the `'default'` key in `database.php` to reflect this.
    
