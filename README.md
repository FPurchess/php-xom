# php-xom #
php-xom is an experiment of a xml-object-mapper written in php.
Currently there is nothing but a simple proof of the possibility
and it is truely everything else, than nice coding.

## reason ##
I'm often forced to have a data-layer crafting smaller applications.
Because databases such as MySql often require setup and a lot more
code, I sometimes choose file over database.

## A word on architecture ##
Reflection would have come in handy, but due it's bad performance
I tried to avoid it. This is also a point why I refused object weaving
during runtime.

## What's next ##
To really use it as a data-abstraction-layer, the process of mapping
an object back to a file is missing. I'd like to know it being forked
and used as well.

## License ##
Feel free to do whatever you want as long as you give credits :)
