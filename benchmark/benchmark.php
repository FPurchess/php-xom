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


    public function getId() {
        return $this->id;
    }

    public function setAuthors($authors) {
        $this->authors = $authors;
    }

    public function getAuthors() {
        return $this->authors;
    }

    public function setPublishDate($publishDate) {
        $this->publishDate = $publishDate;
    }

    public function getPublishDate() {
        return $this->publishDate;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getTitle() {
        return $this->title;
    }
}

// instanciate the mapper
$mapper = new XmlObjectMapper(__DIR__ . "/example.xml");

// fetch all objects
$allBooks = $mapper->getObjects('Book');

// pretty printing of the result
echo '<pre>' . print_r($allBooks, true) . '</pre>';

// note that all objects are in persistance-context by default
// this changes will be saved automatically
$allBooks[1]->setPublishDate("2012-02-" . rand(10,31));

// this is how to perform a search
$book = $mapper
    ->get("Book")
    ->whereEquals('title', 'Chains and Stuff')
    ->getSingleResult();

if ($book) {
    echo "found your book titled '" . $book->getTitle() . "', written on " . $book->getPublishDate() . ' by ' . implode(' & ', $book->getAuthors());
} else {
    echo "Sorry, book not found";
}