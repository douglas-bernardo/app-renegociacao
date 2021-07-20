<?php

namespace App\Shared\Infra\Database;

use App\Shared\Errors\ApiException;
use Exception;

/**
 * Class Repository
 * @package App\Database
 */
final class Repository
{
    /**
     * @var string
     */
    private string $activeRecord;
    /**
     * @var bool
     */
    private bool $viewMode;

    /**
     * Store parameters to be replaced in sql statements
     * On pattern, key => value
     *
     * @var array
     */
    protected array $viewParameters = [];

    /**
     * Repository constructor.
     * @param $class
     * @param bool $viewMode
     */
    function __construct($class, bool $viewMode = false)
    {
        $this->activeRecord = $class;
        $this->viewMode = $viewMode;
    }

    /**
     * @param string $param
     * @param $value
     */
    public function addViewParameter(string $param, $value): void
    {
        if (!isset($this->viewParameters[$param])) {
            $this->viewParameters[$param] = $value;
        }
    }


    /**
     * @param Criteria $criteria
     * @return array
     * @throws Exception
     */
    function load(Criteria $criteria): array
    {
        $entityName = constant($this->activeRecord . '::TABLENAME');
        $entity = $this->viewMode ? ('(' . file_get_string_sql($entityName) . ') AS vw') : $entityName;
        $sql = "SELECT * FROM {$entity}";

        if ($criteria) {
            $expression = $criteria->dump();
            if ($expression) {
                $sql .= ' WHERE ' . $expression;
            }

            $order = $criteria->getProperty('order');
            $limit = $criteria->getProperty('limit');
            $offset = $criteria->getProperty('offset');

            if ($order) {
                $sql .= ' ORDER BY ' . $order;
            }
            if ($limit) {
                $sql .= ' LIMIT ' . $limit;
            }
            if ($offset) {
                $sql .= ' OFFSET ' . $offset;
            }
        }

        if ($this->viewParameters) {
            foreach ($this->viewParameters as $param => $value) {
                $sql = str_replace($param, $value, $sql);
            }
        }

        if (!$conn = Transaction::get()) {
            throw new Exception("There is no active connection");
        }

        try {
            Transaction::log($sql);
            $result = $conn->query($sql);
            $results = array();
            if ($result) {
                while ($row = $result->fetchObject($this->activeRecord)) {
                    $results[] = $row;
                }
            }
            return $results;
        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage(), 500);
        }
    }

    /**
     * @param Criteria $criteria
     * @return false|int
     * @throws Exception
     */
    function delete(Criteria $criteria)
    {
        $expression = $criteria->dump();
        $sql = "DELETE FROM " . constant($this->activeRecord . '::TABLENAME');
        if ($expression) {
            $sql .= ' WHERE ' . $expression;
        }

        if (!$conn = Transaction::get()) {
            throw new Exception("There is no active connection");
        }

        Transaction::log($sql);
        return $conn->exec($sql);
    }

    /**
     * @param Criteria $criteria
     * @return mixed
     * @throws Exception
     */
    function count(Criteria $criteria)
    {
        $expression = $criteria->dump();
        $entityName = constant($this->activeRecord . '::TABLENAME');
        $entity = $this->viewMode ? ('(' . file_get_string_sql($entityName) . ') AS vw') : $entityName;
        $sql = "SELECT COUNT(*) FROM {$entity}";

        if ($expression) {
            $sql .= ' WHERE ' . $expression;
        }

        $row = array();
        if ($conn = Transaction::get()) {
            Transaction::log($sql);
            $result = $conn->query($sql);
            if ($result) {
                $row = $result->fetch();
            }
            return $row[0];
        } else {
            throw new Exception("There is no active connection");
        }
    }
}
