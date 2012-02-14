<?php
/**
 * @author Florian Purchess
 */

require("../XmlObjectMapper.php");

// This is our example class with some attributes,
// that shall be mapped by our xml-object-mapper
class Book {
    private $id;
    private $title;
    private $authors;
    private $publishDate;


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

$start = microtime();

// instanciate the mapper
$mapper = new XmlObjectMapper(__DIR__ . "/benchmark.xml");

$end = microtime();
echo '<h2>Initalized in ' . ($end - $start) . '</h2>';



$start = microtime();

// fetch all objects
$allBooks = $mapper->getObjects('Book');

$end = microtime();
echo '<h2>Fetched all Books in ' . ($end - $start) . '</h2>';



$start = microtime();

// this is how to perform a search
$book = $mapper
    ->get("Book")
    ->whereEquals('title', 'Chains and Stuff')
    ->getSingleResult();

$end = microtime();
echo '<h2>Searched for Books in ' . ($end - $start) . '</h2>';



$start = microtime();

$mapper->save();

$end = microtime();
echo '<h2>Saved all Books in in ' . ($end - $start) . '</h2>';