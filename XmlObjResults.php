<?php

class XmlObjResults implements Iterator {

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
     * @param $key
     * @param $value
     * @return XmlObjResults
     */
    public function whereEquals($key, $value) {
        foreach($this->results as $id => $object) {
            if (!$object->getAttribute($key) == $value) {
                unset($this->results[$id]);
            }
        }

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
