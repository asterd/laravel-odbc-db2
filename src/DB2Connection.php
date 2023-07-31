<?php namespace Opb\LaravelOdbcDb2;

use Illuminate\Support\Arr;
use PDO;
use Illuminate\Database\Connection;

class DB2Connection extends Connection
{
    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * The name of the default schema.
     *
     * @var string
     */
    protected $defaultSchema;

    public function __construct(array $config)
    {
        $this->config = $config;

        // Build the connection string
        $dsn = $this->getDsn($config);

        // You can pass options directly to the MongoClient constructor
        $options = Arr::get($config, 'options', []);

        // Create the connection
        $username = Arr::get($config, 'username');

        $password = Arr::get($config, 'password');

        $this->pdo = new PDO($dsn, $username, $password, $options);

        if (isset($config['schema']))
        {
            $this->pdo->prepare('set schema'.$config['schema'])->execute();
        }

        $this->currentSchema = $this->defaultSchema = strtoupper($config['schema']);

        $this->useDefaultQueryGrammar();

        $this->useDefaultPostProcessor();
    }

    /**
     * Get the name of the default schema.
     *
     * @return string
     */
    public function getDefaultSchema()
    {
        return $this->defaultSchema;
    }

    /**
     * Reset to default the current schema.
     *
     * @return string
     */
    public function resetCurrentSchema()
    {
        $this->setCurrentSchema($this->getDefaultSchema());
    }

    /**
     * Set the name of the current schema.
     *
     * @return string
     */
    public function setCurrentSchema($schema)
    {
        //$this->currentSchema = $schema;
        $this->statement('SET SCHEMA ?', [strtoupper($schema)]);
    }

    /**
     * Get a schema builder instance for the connection.
     *
     * @return Schema\Builder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new Schema\Builder($this);
    }

    /**
     * @return \Illuminate\Database\Grammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new Query\DB2Grammar);
    }

    /**
     * Default grammar for specified Schema
     * @return \Illuminate\Database\Grammar
     */
    protected function getDefaultSchemaGrammar()
    {

        return $this->withTablePrefix(new Schema\DB2Grammar);
    }

    /**
     * Get the default post processor instance.
     *
     * @return Query\DB2Processor
     */
    protected function getDefaultPostProcessor()
    {
        return new Query\DB2Processor;
    }

    /**
     * Get the DSN string for a host / port configuration.
     *
     * @param  array  $config
     * @return string
     */
    protected function getDsn(array $config)
    {
        $driver = Arr::pull($config['dsn_params'], 'driver');

        $dsn = "odbc:DRIVER={".$driver."};";

        foreach($config['dsn_params'] as $key => $val) {
            $dsn .= "{$key}={$val};";
        }

        return $dsn;
    }

}
