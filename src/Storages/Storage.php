<?php

namespace App\Storages;


use App\Models\IModel;
use App\Core\Database\IDatabase;
use App\Conversions\IConversion;
use App\Core\Collection\AppCollection;

class Storage implements IStorage
{
    private $model;
    private $idField;
    private $database;
    private $tableName;
    private $conversion;

    public function __construct(
        IModel $model,
        IDatabase $database,
        IConversion $conversion
    )
    {
        $this->model = $model;
        $this->database = $database;
        $this->conversion = $conversion;
        $this->idField = $conversion->getIdField();
        $this->tableName = $conversion->getTableName();
    }

    public function getById(int $id = 0) :IModel
    {
        $where = $this->idField . ' = ? LIMIT 1';
        $data = $this->database->getWhere($this->tableName, $where, [$id]);
        return $this->model->convertToModel(new AppCollection($data));
    }

    public function save(IModel $model, int $limit = 1) :bool
    {
        $where = $this->idField . ' = ' . $model->getIdentifier() . ' LIMIT ' . $limit;
        if (!$model->isSaved())
            return $this->database->save($this->tableName, $model->save());
        return $this->database->update($this->tableName, $model->save(), $where);
    }

    public function getAll() :array
    {
        return $this->database->selectAll($this->tableName) ?? [];
    }

    public function delete(IModel $model, int $limit = 1) :bool
    {
        $where = $this->idField . ' = ? LIMIT ' . $limit;
        if (!$model->isSaved()) return true;
        return $this->database->delete($this->tableName, $where, [$model->getIdentifier()]);
    }

    public function getWhere(string $where, array $values = []) :array
    {
        return $this->database->getWhere($this->tableName, $where, $values) ?? [];
    }

    public function countAll() :int
    {
        $queryString = 'SELECT COUNT(' . $this->idField . ') FROM ' . $this->tableName;
        return $this->database->rowCount($queryString) ?? 0;
    }

    public function count(string $queryString) :int
    {
        return $this->database->rowCount($queryString) ?? 0;
    }

    public function convertToModel(AppCollection $collection) :IModel
    {
        return $this->model->convertToModel($collection);
    }

    public function getEmptyModel() :IModel
    {
        return $this->model;
    }
}
