PHPStamp
=========

PHPStamp is a *prototype* of a simple PHP templating library for XML-based Office documents.  
Aim of this software is to provide native XML way of templating this formats as an altenative of treating DOM document as string for regex replacing, which has a lot of downsides.  
Basically this library tries to clean messy WYSIWYG-generated code and create reusable XSL stylesheet from it.  

Features
----
  - Current version supports only Microsoft Office Open XML .docx format. Odt support WIP.
  - Configurable brackets for tags-placeholders.
  - Basic extension system which helps generating block of content such as Cells or ListItems.
  - Caching XSL template to filesystem.

Requirements
----
Library requires PHP 5.3+ with DOM, XSL and Zip extensions.  
Also depends on ```doctrine2/Lexer``` Composer package.

Installation
----
Just install it through composer.
```json
{
	"minimum-stability": "dev",
	"require": {
		"shadz3rg/php-stamp": "dev-master"
  	}
}
```

Usage
----

#####Template.  

![alt tag](https://dl.dropboxusercontent.com/u/108509650/step1.png)  
```php
<?php
    require 'vendor/autoload.php';
    
    use PHPStamp\Templator;
    use PHPStamp\Document\WordDocument;
    
    $cachePath = 'path/to/writable/directory/';
    $templator = new Templator($cachePath);
    
    $documentPath = 'path/to/document.docx';
    $document = new WordDocument($documentPath);
    
    $values = array(
        'library' => 'PHPStamp 0.1',
        'simpleValue' => 'I am simple value',
        'nested' => array(
            'firstValue' => 'First child value',
            'secondValue' => 'Second child value'
        ),
        'header' => 'test of a table row',
        'students' => array(
            array('id' => 1, 'name' => 'Student 1', 'mark' => '10'),
            array('id' => 2, 'name' => 'Student 2', 'mark' => '4'),
            array('id' => 3, 'name' => 'Student 3', 'mark' => '7')
        ),
        'maxMark' => 10,
        'todo' => array(
            'TODO 1',
            'TODO 2',
            'TODO 3'
        )
    );
    $result = $templator->render($document, $values);
    $result->download();
```

#####Result.  

![alt tag](https://dl.dropboxusercontent.com/u/108509650/step2.png)

Version
----

Still just experement :3

