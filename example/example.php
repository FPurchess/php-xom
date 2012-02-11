<?php
/**
 * @author Florian Purchess
 */

require("../XmlObjectMapper.php");

// This is our example class with some attributes,
// that shall be mapped by our xml-object-mapper
class Book extends XmlObj {
    protected $id;
    protected $title;
    protected $author;
    protected $publishDate;

    public function someMethod($a) {
        return $a;
    }
}

// instanciate the mapper
$mapper = new XmlObjectMapper("example.xml");

// fetch an object - if there are more than one, get the first
$myBook = $mapper->getObject("//book", 'Book');

// pretty printing of the result
echo '<pre>' . print_r($myBook, true) . '</pre>';