# php-xom #
php-xom is an experiment of a xml-object-mapper written in php.
I often need to have a data-layer crafting smaller applications
with a minimum of initial energy needed and the possibility of
non-techies to edit the data in an easy format. When fetching
data in an application, objects support component-oriented design
and are easy to manipulate.

## A word on architecture ##
Reflection would have come in handy, but due to it's bad performance
I tried to avoid it. Another possibility would have been weaving the
objects during runtime, which I rejected to the cause of php's non
persistent aura :)

## Basic Usage ##

See example/benchmark for some code.

### Format of the XML-Storagefile ###

Use `<content>` as a Root-Object. Assuming to have a class 'book', the class-container in xml would be `<books>`.
All object-definitions are stored as nested children in their class-container. Class-Attributes are described using
their name as the xml-key, adding a type-parameter for typesafe mapping, e.g. `<title type="string">`.

php-xom currently supports four kind of type-definitions: int, string, bool and array.

This is an example of a storage-file:
`<?xml version="1.0"?>
 <content>
     <books>
         <book>
             <id type="int">1</id>
             <title type="string">Fairy Tales and Furry Tails</title>
             <authors type="array">
                 <author type="string">Florian Purchess</author>
                 <author type="string">Peter Pan</author>
             </authors>
             <isFiction type="bool">false</isFiction>
             <publishDate type="string">2012-02-11</publishDate>
         </book>
     </books>
 </content>`

### Using the XmlObjectMapper ###

Initalize a new object-mapper
`$mapper = new XmlObjectMapper("datastorage.xml");`

You can then fetch objects using the XmlObjectResults-Set...
`$books = $mapper->get('Book');`

...and filter them using various comparation-methods:
`$books->whereEquals('title', 'Fairy Tales and Furry Tails');`

## License ##
Feel free to do whatever you want as long as you give credits :)