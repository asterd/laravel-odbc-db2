<?php namespace Opb\LaravelOdbcDb2;

use PDO;
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
        $driver = array_pull($config['dsn_params'], 'DRIVER');

        $dsn = "odbc:DRIVER={".$driver."};";

        foreach($config['dsn_params'] as $key => $val) $dsn .= "{$key}={$val};";

        return $dsn;
    }
}
