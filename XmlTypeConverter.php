<?php

/**
 * @author Florian Purchess
 */
class XmlTypeConverter {

    /**
     * @param SimpleXMLElement $node
     * @return array|bool|int|string
     */
    public function convertXmlElement($node) {
        $type = (string) $node->attributes()->type;

        if ($type == 'int') {
            return (int) $node;
        }

        if ($type == 'bool') {
            return ((string) $node) == 'true';
        }

        if ($type == 'array') {
            $values = array();

            $name = substr($node->getName(), 0, -1);

            foreach ($node->children() as $child) {
                $key = $child->getName();

                if ($key == $name) {
                    $values[] = $this->convertXmlElement($child);
                } else {
                    $values[$key] = $this->convertXmlElement($child);
                }
            }

            return $values;
        }

        return (string) $node;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param SimpleXMLElement $parent
     * @return \SimpleXMLElement
     */
    public function convertAttribute($key, $value, &$parent) {
        if (is_int($value)) {
            $node = $parent->addChild($key, $value);
            $node->addAttribute('type', 'int');

            return $node;
        }

        if (is_bool($value)) {
            $node = $parent->addChild($key, $value ? 'true' : 'false');
            $node->addAttribute('type', 'bool');

            return $node;
        }

        if (is_array($value)) {
            $node = $parent->addChild($key);
            $node->addAttribute('type', 'array');

            $title = substr($key, 0, -1);

            foreach ($value as $name => $data) {
                if (is_int($name)) {
                    $this->convertAttribute($title, $data, $node);
                } else {
                    $this->convertAttribute($name, $data, $node);
                }
            }

            return $node;
        }

        $node = $parent->addChild($key, (string) $value);
        $node->addAttribute('type', 'string');

        return $node;
    }

}
