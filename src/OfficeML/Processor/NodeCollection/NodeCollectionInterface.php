<?php
namespace OfficeML\Processor\NodeCollection;

interface NodeCollectionInterface
{
    public function __construct(\DOMXPath $xpath, array $brackets);
    public function getParagraphNodes();
    public function getPartialNodes(\DOMNode $parentNode);
    public function getTextNode(\DOMNode $partialNode);
} 