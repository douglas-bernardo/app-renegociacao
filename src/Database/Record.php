<?php

namespace App\Database;

use Exception;

/**
 * Implements a active record pattern class
 */
abstract class Record
{
    /**
     * Store object data
     *
     * @var array
     */
    protected array $data = [];

    /**
     * Record constructor.
     * @param int|null $id
     * @throws Exception
     */
    public function __construct(int $id = NULL)
    {
        if ($id) {
            $object = $this->load($id);
            if ($object) {
                $this->fromArray($object->toArray());
            }
        }
    }

    /**
     *
     */
    public function __clone()
    {
        unset($this->data['id']);
    }

    /**
     * This magical method has two functions:
     * When an object is invoked with a method that matches the 
     * string 'set_. $ prop ', 
     * this method will be executed.
     * If the above condition does not occur, a new property is
     * stored in the data array.
     *
     * @param mixed $prop
     * @param mixed $value
     * @return void
     */
    public function __set($prop, $value)
    {
        if (method_exists($this, 'set_' . $prop)) {
            call_user_func(array($this, 'set_' . $prop), $value);
        } else {
            if ($value ==  NULL) {
                unset($this->data[$prop]);
            } else {
                $this->data[$prop] = $value;
            }
        }
    }

    /**
     * Undocumented function
     *
     * @param mixed $prop
     * @return void
     */
    public function __get($prop)
    {
        if (method_exists($this, 'get_' . $prop)) {
            return call_user_func(array($this, 'get_' . $prop));
        } else {
            if (isset($this->data[$prop])) {
                return $this->data[$prop];
            }
        }
    }

    /**
     * @param $prop
     * @return bool
     */
    public function __isset($prop)
    {
        return isset($this->data[$prop]);
    }

    /**
     * @param $prop
     */
    public function __unset($prop): void
    {
        unset($this->data[$prop]);
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        $class = get_class($this);
        return constant("{$class}::TABLENAME");
    }

    /**
     * @param $data
     * @return $this
     */
    public function fromArray($data): Record
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function store()
    {
        $prepare = $this->prepare($this->data);

        if (empty($this->data['id']) or (!$this->load($this->id))) {

            if (empty($this->data['id'])) {
                $this->id = $this->getLast() + 1;
                $prepare['id'] = $this->id;
            }

            $sql = "INSERT INTO {$this->getEntity()} " .
                '(' . implode(',', array_keys($prepare)) . ')' .
                ' VALUES ' .
                '(' . implode(',', array_values($prepare)) . ')';
        } else {

            $sql = "UPDATE {$this->getEntity()}";

            if ($prepare) {
                foreach ($prepare as $column => $value) {
                    if ($column !== 'id') {
                        $set[] = "{$column} = {$value}";
                    }
                }
            }
            $sql .= ' SET ' . implode(', ', $set);
            $sql .= ' WHERE id=' . (int) $this->data['id'];
        }

        if ($conn = Transaction::get()) {
            Transaction::log($sql);
            return $conn->exec($sql);
        } else {
            throw new Exception("There is no active transaction.");
        }
    }

    /**
     * @param $id
     * @return Record|null
     * @throws Exception
     */
    public function load($id): ? Record
    {
        $sql = "SELECT * FROM {$this->getEntity()}";
        $sql .= ' WHERE id=' . (int) $id;
        if ($conn = Transaction::get()) {
            Transaction::log($sql);
            $result = $conn->query($sql);
            if (!$result) {
                return null;
            }

            $object = $result->fetchObject(get_class($this));
            if (!$object) {
                return null;
            }
            return $object;
        } else {
            throw new Exception("There is no active transaction.");
        }
    }

    /**
     * @param $param
     * @param $value
     * @return Record|null
     * @throws Exception
     */
    public function loadBy($param, $value): ? Record
    {
        $sql = "SELECT * FROM {$this->getEntity()}";
        $sql .= " WHERE {$param} = " . $this->escape($value);
        if ($conn = Transaction::get()) {
            Transaction::log($sql);
            $result = $conn->query($sql);
            if (!$result) {
                return null;
            }

            $object = $result->fetchObject(get_class($this));
            if (!$object) {
                return null;
            }
            return $object;
        } else {
            throw new Exception("There is no active transaction.");
        }
    }

    /**
     * @param null $id
     * @return mixed
     * @throws Exception
     */
    public function delete($id = NULL)
    {
        $id = $id ?? $this->id;
        $sql = "DELETE FROM {$this->getEntity()}";
        $sql .= ' WHERE id=' . (int)$id;
        if ($conn = Transaction::get()) {
            Transaction::log($sql);
            return $conn->exec($sql);
        } else {
            throw new Exception("There is no active transaction.");
        }
    }

    /**
     * @param $id
     * @return Record|null
     */
    public static function find($id): ? Record
    {
        $classname = get_called_class();
        $ar = new $classname;
        return $ar->load($id);
    }

    /**
     * @throws Exception
     */
    public static function all(): array
    {
        $classname = get_called_class();
        $rep = new Repository($classname);
        return $rep->load(new Criteria);
    }

    public function getLast(): int
    {
        if ($conn = Transaction::get()) {
            $sql = "SELECT max(id) FROM {$this->getEntity()}";
            Transaction::log($sql);
            $result = $conn->query($sql);
            $row = $result->fetch();
            return (int) $row[0];
        } else {
            throw new Exception("There is no active transaction.");
        }
    }

    /**
     * @param $data
     * @return array
     */
    public function prepare($data): array
    {
        $prepared = array();
        foreach ($data as $key => $value) {
            if (is_scalar($value)) {
                $prepared[$key] = $this->escape($value);
            }
        }
        return $prepared;
    }

    /**
     * @param $value
     * @return mixed|string
     */
    public function escape($value)
    {
        if (is_string($value) and (!empty($value))) {
            $value = addslashes($value);
            return "'$value'";
        } else if (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        } else if ($value !== '') {
            return $value;
        } else {
            return "NULL";
        }
    }

    /**
     * @return array
     */
    public function __toString()
    {
        return $this->data;
    }
}
