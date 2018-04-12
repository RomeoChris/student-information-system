<?php

namespace App\DataTable;


use DataTable\SSP;

class DataTable implements DataTableInterface
{
    private $table;
    private $columns;
    private $identifier;
    private $databaseHost;
    private $databaseName;
    private $databaseUser;
    private $databasePass;

    public function __construct(
        string $databaseHost,
        string $databaseName,
        string $databaseUser,
        string $databasePass
    )
    {
        $this->databaseHost = $databaseHost;
        $this->databaseName = $databaseName;
        $this->databaseUser = $databaseUser;
        $this->databasePass = $databasePass;
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
            'db'   => $this->databaseName,
            'user' => $this->databaseUser,
            'pass' => $this->databasePass,
            'host' => $this->databaseHost,
        ];
    }
}
