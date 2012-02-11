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
    protected $authors;
    protected $publishDate;

    public function someMethod($a) {
        return $a;
    }
}

// instanciate the mapper
$mapper = new XmlObjectMapper("example.xml");

// fetch all objects
$allBooks = $mapper->getObjects("//book", 'Book');

// pretty printing of the result
echo '<pre>' . print_r($allBooks, true) . '</pre>';

// fetch only one objects
$firstBook = $mapper->getObject("//book", 'Book');

// pretty printing of the result
echo '<pre>' . print_r($firstBook, true) . '</pre>';