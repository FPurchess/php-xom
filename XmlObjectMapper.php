<?php

require("XmlObj.php");
require("XmlObjResults.php");
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
     * @var string
     */
    private $file;

    /**
     * @var array
     */
    private $objects = array();


    /**
     * @param $file
     */
    public function __construct($file) {
        $this->load($file);
    }

    /**
     *
     */
    public function __destruct() {
        $this->save();
    }

    /**
     * @param $file
     */
    public function load($file) {
        $this->file = $file;
        $this->xml = simplexml_load_file($file);

        $rootElements = $this->xml->children();
        foreach ($rootElements as $rootElement) {
            foreach ($rootElement as $node) {
                $this->mapNode($node);
            }
        }
    }

    /**
     * @return boolean
     */
    public function save() {
        $this->xml = new SimpleXMLElement("<content/>");

        foreach ($this->objects as $class => $objects) {
            $class = strtolower($class);
            $rootElements = $this->xml->addChild($class . 's');

            foreach ($objects as $object) {
                $node = $rootElements->addChild($class);
                $this->mapObject($node, $object);
            }
        }

        return $this->xml->asXML($this->file);
    }

    /**
     * @param string $class
     * @return array
     */
    public function getObjects($class) {
        if (array_key_exists($class, $this->objects)) {
            return $this->objects[$class];
        }

        return null;
    }

    /**
     * @param $object
     */
    public function persist(&$object) {
        $this->objects[get_class($object)][] = $object;
    }

    /**
     * @param string $class
     * @return XmlObjResults
     */
    public function get($class) {
        if (array_key_exists($class, $this->objects)) {
            return new XmlObjResults($this->objects[$class]);
        }

        return new XmlObjResults(array());
    }

    /**
     * @param SimpleXMLElement $node
     * @param XmlObj $object
     */
    private function mapObject($node, $object) {
        $values = $object->getAttributes();

        foreach ($values as $key => $value) {
            if (is_array($value)) {
                $item = $node->addChild($key);
                $this->mapArray($item, $value);
            } else {
                $node->addChild($key, (string)$value);
            }
        }
    }

    /**
     * @param SimpleXMLElement $node
     * @param array $array
     */
    private function mapArray($node, $array) {
        $type = $node->getName();
        $type = substr($type, 0, count($type) - 2);

        foreach ($array as $key => $value) {
            $node->addChild(is_numeric($key) ? $type : $key, (string)$value);
        }
    }

    /**
     * @param SimpleXMLElement $node
     * @return mixed
     */
    private function mapNode($node) {
        $class = ucfirst($node->getName());
        $obj = new $class;

        if (!class_exists($class)) {
            throw new XmlObjectMapperException("Class '" . $class . "' does not exist");
        }

        if (in_array('XmlObj', class_implements($class))) {
            throw new XmlObjectMapperException("Class does not implement XmlObj");
        }

        foreach ($node->attributes() as $key => $value) {
            $obj->mapAttribute($key, (string)$value);
        }

        foreach ($node->children() as $child) {
            $obj->mapAttribute($child->getName(), $this->mapChild($child));
        }

        $this->objects[$class][] = $obj;
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