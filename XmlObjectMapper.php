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
     * @return array
     * @throws XmlObjectMapperException
     */
    public function getObjects($query, $class) {
        if (in_array('XmlObj', class_implements($class))) {
            throw new XmlObjectMapperException("Class does not implement XmlObj");
        }

        $objects = array();
        $nodes = $this->xml->xpath($query);

        foreach ($nodes as $node) {
            $objects[] = $this->mapNode($node, $class);
        }

        return $objects;
    }

    /**
     * @param $query
     * @param $class
     * @return mixed|null
     * @throws XmlObjectMapperException
     */
    public function getObject($query, $class) {
        if (in_array('XmlObj', class_implements($class))) {
            throw new XmlObjectMapperException("Class does not implement XmlObj");
        }

        $nodes = $this->xml->xpath($query);
        if (!isset($nodes[0])) return null;

        return $this->mapNode($nodes[0], $class);
    }

    /**
     * @param \SimpleXMLElement $node
     * @param $class
     * @return mixed
     */
    private function mapNode(SimpleXMLElement $node, $class) {
        $obj = new $class;

        foreach ($node->attributes() as $key => $value) {
            $obj->mapAttribute($key, (string)$value);
        }

        foreach ($node->children() as $child) {
            $obj->mapAttribute($child->getName(), $this->mapChild($child));
        }

        return $obj;
    }

    /**
     * @param $node
     * @return array|string
     */
    private function mapChild($node) {
        if (is_object($node) && $node->count()) {
            $values = array();
            foreach ($node->children() as $child) {
                $values[] = $this->mapChild($child);
            }
            return $values;
        }

        return (string)$node;
    }

}