<?php

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
     * @var array
     */
    private $reflections = array();


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
            $reflection = $this->getReflection($class);

            $class = strtolower($class);
            $rootElements = $this->xml->addChild($class . 's');

            foreach ($objects as $object) {
                $node = $rootElements->addChild($class);

                foreach ($reflection->getProperties() as $property) {
                    $property->setAccessible(true);
                    $this->typeConverter->convertAttribute($property->getName(), $property->getValue($object), $node);
                }
                //$this->objectToNode($node, $object);
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
     * @param string $class
     * @return XmlObjResults
     */
    public function get($class) {
        if (array_key_exists($class, $this->objects)) {

            if (!class_exists($class)) {
                throw new XmlObjectMapperException("Class '" . $class . "' does not exist");
            }

            return new XmlObjResults($this->objects[$class], $this->getReflection($class));
        }

        return new XmlObjResults(array(), $this->getReflection($class));
    }

    /**
     * @param $object
     */
    public function persist(&$object) {
        $this->objects[get_class($object)][] = $object;
    }

    /**
     * @param string $class
     * @return ReflectionClass
     */
    private function getReflection($class) {
        if (!array_key_exists($class, $this->reflections)) {
            $this->reflections[$class] = new ReflectionClass($class);
        }

        return $this->reflections[$class];
    }

    /**
     * @param SimpleXMLElement $node
     * @return mixed
     */
    private function nodeToObject($node) {
        $class = ucfirst($node->getName());
        $reflection = $this->getReflection($class);
        $obj = $reflection->newInstance();

        foreach ($node->children() as $child) {
            $name = $child->getName();

            if ($reflection->hasProperty($name)) {
                $value = $this->typeConverter->convertXmlElement($child);

                $property = $reflection->getProperty($name);
                $property->setAccessible(true);
                $property->setValue($obj, $value);
            }
        }

        $this->objects[$class][] = $obj;
    }

    /**
     * @param boolean $autosave
     */
    public function setAutosave($autosave) {
        $this->autosave = $autosave;
    }

    /**
     * @return boolean
     */
    public function getAutosave() {
        return $this->autosave;
    }

    public function __destruct() {
        if ($this->autosave) {
            $this->save();
        }
    }

}