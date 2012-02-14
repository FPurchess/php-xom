<?php

/**
 * @author Florian Purchess
 */
class XmlObj {

    /**
     * @return array
     */
    public function getAttributes() {
        return get_object_vars($this);
    }

    /**
     * @param $name
     * @param $value
     */
    public function mapAttribute($name, $value) {
        $this->{$name} = $value;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key) {
        return $this->{$key};
    }

}
