<?php

namespace OfficeML\Document\WordDocument\Expression;

use OfficeML\Exception\ExpressionException;
use OfficeML\Expression;
use OfficeML\Processor;
use OfficeML\Processor\Tag;
use OfficeML\XMLHelper;

class Cell implements Expression
{
    public function insertTemplateLogic(array $arguments, \DOMNode $node, Tag $tag)
    {
        if (count($arguments) !== 1) {
            throw new ExpressionException('Wrong arguments number, 1 needed, got ' . count($arguments));
        }

        list($rowName) = $arguments;

        $template = $node->ownerDocument;

        // find existing or initiate new table row template
        if ($this->isRowTemplateExist($rowName, $template) === false) {

            $rowTemplate = $template->createElementNS(Processor::XSL_NS, 'xsl:template');
            $rowTemplate->setAttribute('name', $rowName);

            // find row node
            $rowNode = XMLHelper::parentUntil('w:tr', $node);

            // call-template for each row
            $foreachNode = $template->createElementNS(Processor::XSL_NS, 'xsl:for-each');
            $foreachNode->setAttribute('select', '/' . Processor::VALUE_NODE . '/' . $rowName . '/item');
            $callTemplateNode = $template->createElementNS(Processor::XSL_NS, 'xsl:call-template');
            $callTemplateNode->setAttribute('name', $rowName);
            $foreachNode->appendChild($callTemplateNode);

            // insert call-template before moving
            $rowNode->parentNode->insertBefore($foreachNode, $rowNode);

            // move node into row template
            $rowTemplate->appendChild($rowNode);
            $template->documentElement->appendChild($rowTemplate);

        }

        $relativePath = $tag->getRelativePath();
        Processor::insertTemplateLogic($tag->getTextContent(), $relativePath, $node);



        return $node;
    }

    private function isRowTemplateExist($rowName, \DOMDocument $template)
    {
        $xpath = new \DOMXPath($template);
        $nodeList = $xpath->query('/xsl:stylesheet/xsl:template[@name="' . $rowName . '"]');

        if ($nodeList->length > 1) {
            throw new ExpressionException('Unexpected template count.');
        }

        return ($nodeList->length === 1);
    }
} 