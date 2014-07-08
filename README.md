# FileGen

A small tool to aid in the creation of (complicated) file and directory
layouts.

```php
use Naneau\FileGen\Structure;
use Naneau\FileGen\Generator;

// Specify a structure to be generated
$structure = new Structure;
$structure
    ->directory('foo')
    ->file('bar/baz', 'these are the file contents');
    ->link('/some/file/on/disk', 'qux');

// Generate the structure
$generator = new Generator('/output/directory');
$generator->generate($structure);
