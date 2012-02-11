<?php

/**
 * @author Florian Purchess
 */
class XmlObj {

    /**
     * @param $name
     * @param $value
     */
    public function mapAttribute($name, $value) {
        $this->{$name} = $value;
    }

}
