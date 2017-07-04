# FileGen

[![Build Status](https://travis-ci.org/naneau/filegen.svg?branch=master)](https://travis-ci.org/naneau/filegen)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/45ba109e-f456-469c-8cf2-fb621fa3c069/mini.png)](https://insight.sensiolabs.com/projects/45ba109e-f456-469c-8cf2-fb621fa3c069)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/naneau/filegen/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/naneau/filegen/?branch=master)

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
    ->link('/some/file/somewhere', 'qux');

// Generate the structure
$generator = new Generator('/output/directory');
$generator->generate($structure);
```

will output:

```
- /output/directory/
    - foo/
    - bar/
        - baz
    - qux => /some/file/somewhere
```

## Examples

### Copy A File

Copying an existing file to a new file in the structure to be generated is
easy.

```php
use Naneau\FileGen\Structure;
use Naneau\FileGen\File\Contents\Copy;

$structure = new Structure;
$structure->file('foo', new Copy('/from/this/file'));
```

### Use a Twig Template

Files can be given content using a [Twig](http://twig.sensiolabs.org/)
template.

```php
use Naneau\FileGen\Structure;
use Naneau\FileGen\File\Contents\Twig;

// $twig = ...

// Load a template
$template = $twig->load('some_template.twig');

// Parameters for the template
$parameters = array('foo' => 'bar')

$structure = new Structure;
$structure->file('foo', new Twig($template, $parameters));
```

### Set up A Parameter Specification Alongside A Structure

In some cases you'll want to specify parameters to be used by your structure
beforehand. These parameters can then be queried for using the console helper
(see below), and used in Twig templates.

```php
use Naneau\FileGen\Generator;
use Naneau\FileGen\Structure;
use Naneau\FileGen\File\Contents\Twig;

// $twig = ...
$template = ;
$structure = new Structure;
$structure
    // A parameter "foo" is expected
    ->param('foo')

    // A bar parameter with a description
    ->param('bar', 'Please specify "bar"')

    // Can use {{ foo }} and {{ bar }}
    ->file('someFile', new Twig($twig->load('someFile.twig'));

    // Can also use {{ foo }} and {{ bar }}
    ->file('anotherFile', new Twig($twig->load('anotherFile.twig'));

// Set a default value for foo
$structure->getParameterDefinition()->get('foo')->setDefaultValue('Foo!');

// Pass values for the structure's parameters to the generator
$generator = new Generator('/output/directory', array(
    'foo' => 'foo!'
    'bar' => 12345
));

// Generate the structure
$generator->generate($structure);
```

## Console Helper

FileGen ships with a a (Symfony Console
Helper](http://symfony.com/doc/current/components/console/introduction.html#console-helpers)
that will use the [built-in question
helper](http://symfony.com/doc/current/components/console/helpers/questionhelper.html)
to ask for parameter values.

Simply add the helper to your console helper set:

```php
use Naneau\FileGen\Console\Helper\ParameterHelper;

// $application = ...
$application->getHelperSet()->set(new ParameterHelper, 'filegenParameters');
```
And use it in your commands:

```php
protected function execute(InputInterface $input, OutputInterface $output)
{
    // $structure = ...

    $helper = $this->getHelper('filegenParameters');

    // Ask for all parameters one by one
    $parameters = $helper->askParameters($structure, $input, $output);

    // Ask for a single parameter
    $fooParameter = $structure->getParameterDefinition()->get('foo');
    $fooValue = $helper->askParameter($fooParameter, $input, $output);
}
```
