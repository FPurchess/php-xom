<?php

require("XmlObj.php");
require("XmlObjResults.php");
require("XmlTypeConverter.php");
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
     * @var bool
     */
    private $autosave;

    /**
     * @var XmlTypeConverter
     */
    private $typeConverter;


    /**
     * @param string $file
     * @param bool $autosave
     */
    public function __construct($file, $autosave = true) {
        $this->autosave = $autosave;
        $this->typeConverter = new XmlTypeConverter();
        $this->load($file);
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
                $this->nodeToObject($node);
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
                $this->objectToNode($node, $object);
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
    private function objectToNode($node, $object) {
        $attributes = $object->getAttributes();

        foreach ($attributes as $key => $value) {
            $this->typeConverter->convertAttribute($key, $value, $node);
        }
    }

    /**
     * @param SimpleXMLElement $node
     * @return mixed
     */
    private function nodeToObject($node) {
        $class = ucfirst($node->getName());
        $obj = new $class;

        if (!class_exists($class)) {
            throw new XmlObjectMapperException("Class '" . $class . "' does not exist");
        }

        if (in_array('XmlObj', class_implements($class))) {
            throw new XmlObjectMapperException("Class does not implement XmlObj");
        }

        foreach ($node->children() as $child) {
            $value = $this->typeConverter->convertXmlElement($child);
            $obj->mapAttribute($child->getName(), $value);
        }

        $this->objects[$class][] = $obj;
    }


    public function __destruct() {
        if ($this->autosave) {
            $this->save();
        }
    }

}