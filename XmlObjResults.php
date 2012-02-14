<?php

class XmlObjResults implements Iterator {

    const COMPARATOR_EQUALS = 0;
    const COMPARATOR_NOT_EQUALS = 1;
    const COMPARATOR_GREATER = 2;
    const COMPARATOR_GREATER_EQUALS = 3;
    const COMPARATOR_LESS = 4;
    const COMPARATOR_LESS_EQUALS = 5;


    /**
     * @var array
     */
    private $results = array();

    /**
     * @var int
     */
    private $position = 0;


    /**
     * @param array $objects
     */
    public function __construct($objects) {
        $this->results = $objects;
    }

    /**
     * @param $value1
     * @param $value2
     * @param $type
     * @return bool
     */
    private function comparate($value1, $value2, $type) {
        switch ($type) {
            case XmlObjResults::COMPARATOR_EQUALS:
                return $value1 == $value2;
            case XmlObjResults::COMPARATOR_NOT_EQUALS:
                return $value1 != $value2;
            case XmlObjResults::COMPARATOR_GREATER:
                return $value1 < $value2;
            case XmlObjResults::COMPARATOR_GREATER_EQUALS:
                return $value1 <= $value2;
            case XmlObjResults::COMPARATOR_LESS:
                return $value1 > $value2;
            case XmlObjResults::COMPARATOR_LESS_EQUALS:
                return $value1 >= $value2;
        }

        return $value1 == $value2;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $type
     */
    private function filter($key, $value, $type) {
        foreach ($this->results as $id => $object) {
            $data = $object->getAttribute($key);
            if (!$this->comparate($value, $data, $type)) {
                unset($this->results[$id]);
            }
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return XmlObjResults
     */
    public function whereEquals($key, $value) {
        $this->filter($key, $value, XmlObjResults::COMPARATOR_EQUALS);

        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return XmlObjResults
     */
    public function whereNotEquals($key, $value) {
        $this->filter($key, $value, XmlObjResults::COMPARATOR_NOT_EQUALS);

        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return XmlObjResults
     */
    public function whereGreater($key, $value) {
        $this->filter($key, $value, XmlObjResults::COMPARATOR_GREATER);

        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return XmlObjResults
     */
    public function whereGreaterEquals($key, $value) {
        $this->filter($key, $value, XmlObjResults::COMPARATOR_GREATER_EQUALS);

        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return XmlObjResults
     */
    public function whereLess($key, $value) {
        $this->filter($key, $value, XmlObjResults::COMPARATOR_LESS);

        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return XmlObjResults
     */
    public function whereLessEquals($key, $value) {
        $this->filter($key, $value, XmlObjResults::COMPARATOR_LESS_EQUALS);

        return $this;
    }

    /**
     * @return int
     */
    public function count() {
        return count($this->results);
    }

    /**
     * @return array
     */
    public function getResultList() {
        return $this->results;
    }

    /**
     * @return XmlObj|null
     */
    public function getSingleResult() {
        if (!empty($this->results)) {
            return $this->results[0];
        }

        return null;
    }

    /**
     * @return XmlObj
     */
    public function current() {
        return $this->results[$this->position];
    }

    /**
     *
     */
    public function next() {
        ++$this->position;
    }

    /**
     * @return int
     */
    public function key() {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid() {
        return isset($this->results[$this->position]);
    }

    /**
     *
     */
    public function rewind() {
        --$this->position;
    }


}
