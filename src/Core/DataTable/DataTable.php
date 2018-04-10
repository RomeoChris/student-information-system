<?php

namespace App\Core\DataTable;


use DataTable\SSP;
use Symfony\Component\HttpFoundation\ParameterBag;

class DataTable
{
    private $table;
    private $config;
    private $columns;
    private $identifier;

    public function __construct(ParameterBag $databaseConfig)
    {
        $this->config = $databaseConfig;
    }

    public function getData() :array
    {
        return SSP::simple($_GET, $this->getMySqlDetails(), $this->table, $this->identifier, $this->columns);
    }

    public function setTable(string $table) :void
    {
        $this->table = $table;
    }

    public function setColumns(array $columns = []) :void
    {
        $this->columns = $columns;
    }

    public function setPrimaryKey(string $key = 'id') :void
    {
        $this->identifier = $key;
    }

    private function getMySqlDetails() :array
    {
        return [
            'db'   => $this->config->get('database', ''),
            'user' => $this->config->get('username', ''),
            'pass' => $this->config->get('password', ''),
            'host' => $this->config->get('hostname', '')
        ];
    }
}
