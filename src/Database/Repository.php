<?php

namespace App\Database;

final class Repository
{
    private $activeRecord;
    private bool $viewMode;

    /**
     * Store parameters to be replaced in sql statements 
     * On pattern, key => value
     * 
     * @var array
     */
    protected $viewParameters = [];

    function __construct($class, $viewMode = false)
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

    //injeção de dependencia
    function load(Criteria $criteria)
    {
        //instancia a instrução de SELECT
        $entityName = constant($this->activeRecord . '::TABLENAME');
        $entity = $this->viewMode ? '(' . file_get_string_sql($entityName) . ')' : $entityName;
        $sql = "SELECT * FROM {$entity}";

        //Obtêm a cláusula  WHERE da classe criteria.
        if ($criteria) {
            $expression = $criteria->dump(); //resultado da expressão de filter 
            if ($expression) {
                $sql .= ' WHERE ' . $expression;
            }

            //Obtêm as propriedades do criterio
            $order = $criteria->getProperty('order');
            $limit = $criteria->getProperty('limit');
            $offset = $criteria->getProperty('offset');

            //Obtêm a ordenação do SELECT
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

        if ($conn = Transaction::get()) {
            Transaction::log($sql);

            $result = $conn->query($sql);
            $results = array();
            if ($result) {
                while ($row = $result->fetchObject($this->activeRecord)) {
                    $results[] = $row;
                }
            }
            return $results;
        } else {
            throw new \Exception("Não há conexão ativa");
        }
    }

    function delete(Criteria $criteria)
    {
        $expression = $criteria->dump();
        $sql = "DELETE FROM " . constant($this->activeRecord . '::TABLENAME');
        if ($expression) {
            $sql .= ' WHERE ' . $expression;
        }
        //obtem transação ativa
        if ($conn = Transaction::get()) {
            Transaction::log($sql); // registra mensagem de log
            $result = $conn->exec($sql); //executa a instrção de delete
            return $result;
        } else {
            throw new \Exception("Não há conexão ativa");
        }
    }

    function count(Criteria $criteria)
    {
        $expression = $criteria->dump();
        $sql = "SELECT COUNT(*) FROM " . constant($this->activeRecord . '::TABLENAME');
        if ($expression) {
            $sql .= ' WHERE ' . $expression;
        }

        //obtem conextão ativa
        if ($conn = Transaction::get()) {
            Transaction::log($sql);
            $result = $conn->query($sql);
            if ($result) {
                $row = $result->fetch();
            }
            return $row[0];
        } else {
            throw new \Exception("Não há conexão ativa");
        }
    }
}
