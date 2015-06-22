<?php namespace Opb\LaravelOdbcDb2;

use Illuminate\Database\Connectors\Connector;
use Illuminate\Database\Connectors\ConnectorInterface;

class ODBCConnector extends Connector implements ConnectorInterface
{
    /**
     * Establish a database connection.
     *
     * @param  array  $config
     * @return \PDO
     */
    public function connect(array $config)
    {
        $dsn = $this->getDsn($config);

        $options = $this->getOptions($config);

        $connection = $this->createConnection($dsn, $config, $options);

        if (isset($config['schema']))
        {
            $schema = $config['schema'];

            $connection->prepare("set schema $schema")->execute();
        }

        return $connection;
    }

    /**
     * Get the DSN string for a host / port configuration.
     *
     * @param  array  $config
     * @return string
     */
    protected function getDsn(array $config)
    {
        extract($config);
        /** @var integer $port */
        /** @var string $odbc_driver */
        /** @var string $database */
        /** @var string $host */
        /** @var string $username */
        /** @var string $password */

        $port = isset($port) ? $port : 50000;

        return "odbc:DRIVER={".$odbc_driver."};DATABASE=$database;HOSTNAME=$host;PORT=$port;PROTOCOL=TCPIP;UID=$username;PWD=$password;";
    }
}

