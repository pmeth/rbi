<?php
namespace Pmeth\Common;
/**
 * Description of BaseMapper
 *
 * @author Vic Metcalfe
 * https://github.com/zymsys/GTAPHPOCP
 */

class BasePDOMapper
{
    protected $pdo;
    protected $dbName;
    protected $pkName;

    public function __construct(PDO $pdo, $modelName, $dbName, $pkName)
    {
        $this->pdo = $pdo;
        $this->dbName = $dbName;
        $this->pkName = $pkName;
        $this->fields = array_keys(get_class_vars($modelName));
    }

    public function fetch($filter = '', $values = array())
    {
        $sql = "SELECT * FROM `{$this->dbName}`";
        if (!empty($filter))
        {
            $sql .= " WHERE $filter";
        }
        $statement = $this->pdo->prepare($sql);
        $statement->execute($values);
        $result = array();
        foreach($statement->fetchAll() as $row)
        {
            $result[] = $this->mapRow($row);
        }
        return $result;
    }

    private function mapRow($row)
    {
        $item = array();
        foreach ($this->fields as $field)
        {
            $item[$field] = $row[$field];
        }
        return $item;
    }

    public function insert($model)
    {
        $insertvars = get_object_vars($model);
        if (empty($insertvars[$this->pkName]))
        {
            unset($insertvars[$this->pkName]);
        }
        $sql = "INSERT INTO `{$this->dbName}` (`";
        $sql .= implode("`, `", array_keys($insertvars));
        $sql .= "`) VALUES (?";
        $sql .= str_repeat(", ?", count($insertvars) - 1);
        $sql .= ")";
        $statement = $this->pdo->prepare($sql);
        return $statement->execute(array_values($insertvars));
    }

    public function update($oldId, $model)
    {
        $sets = array();
        $setvalues = array();
        foreach ($this->fields as $fieldname) {
            $sets[] = "`$fieldname`=?";
            $setvalues[] = $model->$fieldname;
        }
        $setvalues[] = $oldId;

        $sql = "UPDATE `{$this->dbName}` SET " .
            implode(', ', $sets) .
            " WHERE `{$this->pkName}`=?";
        $statement = $this->pdo->prepare($sql);
        return $statement->execute($setvalues);
    }

    public function delete($deleteId)
    {
        $statement = $this->pdo->prepare("DELETE FROM `{$this->dbName}` WHERE `id`=?");
        return $statement->execute(array($deleteId));
    }
}
