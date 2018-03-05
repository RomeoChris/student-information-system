<?php

namespace App\Core\DataTable;


use DataTable\SSP;
use App\Core\Collection\AppCollection;

class DataTable
{
    private $table;
    private $config;
    private $columns;
    private $identifier;

    public function __construct(AppCollection $databaseConfig)
    {
        $this->config = $databaseConfig;
    }

    public function getData() :array
    {
        return SSP::simple($_GET, $this->getMySqlDetails(), $this->table, $this->identifier, $this->columns);
    }

    public function setTable(string $table = '') :void
    {
        $this->table = $table;
    }

    public function setColumns(array $columns = []) :void
    {
        $this->columns = $columns;
    }

    public function setPrimaryKey(string $key = '') :void
    {
        $this->identifier = $key;
    }

    private function getMySqlDetails() :array
    {
        return [
            'db'   => $this->config->getString('database'),
            'user' => $this->config->getString('username'),
            'pass' => $this->config->getString('password'),
            'host' => $this->config->getString('hostname')
        ];
    }
}
