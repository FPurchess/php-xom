<?php

require("XmlObj.php");
require("XmlObjectMapperException.php");

/**
 * @author Florian Purchess
 */
class XmlObjectMapper {

    /**
     * @var SimpleXMLElement
     */
    private $xml;

    /**
     * @param $file
     */
    public function __construct($file) {
        $this->xml = simplexml_load_file($file);
    }

    /**
     * @param string $query
     * @param string $class
     * @return object
     */
    public function getObject($query, $class) {
        $nodes = $this->xml->xpath($query);
        if (!isset($nodes[0])) return null;

        $node = $nodes[0];
        $obj = new $class;

        if (!$obj instanceof XmlObj) {
            throw new XmlObjectMapperException();
        }

        foreach ($node->attributes() as $key => $value) {
            $obj->mapAttribute($key, (string) $value);
        }

        foreach ($node->children() as $child) {
            if ($child->count() == 0) {
                $obj->mapAttribute($child->getName(), (string) $child);
            }
        }

        return $obj;
    }

}