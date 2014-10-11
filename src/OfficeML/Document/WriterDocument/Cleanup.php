<?php

namespace OfficeML\Document\WriterDocument;

use OfficeML\XMLHelper;

class Cleanup extends XMLHelper
{
    /**
     * @var \DOMDocument
     */
    private $document;
    /**
     * @var \DOMXPath
     */
    private $xpath;


    private $paragraphQuery;
    private $runQuery;
    private $runPropertyQuery;
    private $textQuery;

    public function __construct(\DOMDocument $document)
    {
        $this->document = $document;
        $this->xpath = new \DOMXPath($document);
    }

    private function findGroupIndex($style, array $groups)
    {
        foreach ($groups as $index => $group) {
            if (in_array($style, $group) === true) {
                return $index;
            }
        }

        return null;
    }

    private function inSameGroup($style1, $style2, array $groups)
    {
        foreach ($groups as $group) {
            if (in_array($style1, $group) === true && in_array($style2, $group) === true) {
                return true;
            }
        }

        return false;
    }

    public function cleanup()
    {
        // prepare data
        $styleNodeList = $this->xpath->query('//style:style');
        $equalityMap = $this->equalityMap($styleNodeList);

        //
        //$this->groupText($equalityMap);
    }

    private function groupText(\DOMNodeList $nodeList, array $equalityMap)
    {
        foreach ($nodeList as $node) {
            
        }
    }

    public function equalityMap(\DOMNodeList $nodeList, $nameAttribute = 'style:name')
    {
        $map = array();
        $helper = new XMLHelper();

        //for ($i = 0; $i < $nodeCount - 1; $i++) { TODO Change later
            //for ($j = $i + 1; $j < $nodeCount; $j++) {

        // compare element with each other
        $nodeCount = $nodeList->length;
        for ($i = 0; $i < $nodeCount; $i++) {
            for ($j = 0; $j < $nodeCount; $j++) {

                if ($i === $j) {
                    continue;
                }

                /** @var $style1 \DOMElement */
                $style1 = $nodeList->item($i);
                /** @var $style2 \DOMElement */
                $style2 = $nodeList->item($j);

                $styleProperties1 = $this->getPropertiesNode($style1);
                $styleProperties2 = $this->getPropertiesNode($style2);
                $styleName1 = $style1->getAttribute($nameAttribute);
                $styleName2 = $style2->getAttribute($nameAttribute);

                $isEqual = $helper->deepEqual($styleProperties1, $styleProperties2);

                // get style group
                $groupIndex = $this->findGroupIndex($styleName1, $map);

                // or create new one
                if ($groupIndex === null) {
                    $map[] = array($styleName1);

                    end($map);
                    $groupIndex = key($map);
                }

                // add second style into group
                if ($isEqual === true && in_array($styleName2, $map[$groupIndex]) === false) {
                    $map[$groupIndex][] = $styleName2;
                }
            }
        }

        return $map;
    }

    private function getPropertiesNode(\DOMNode $node)
    {
        $xpath = new \DOMXPath($node->ownerDocument);
        $nodeList = $xpath->query('style:text-properties', $node);

        return $nodeList->item(0);
    }

    public function hardcoreCleanup() // TODO Move into document specific class
    {
        // reset locale
        $nodeList = $this->xpath->query('//style:text-properties[@fo:language]');
        /** @var $styleNode \DOMElement */
        foreach ($nodeList as $styleNode) {
            $styleNode->removeAttribute('fo:language');
        }

        $nodeList = $this->xpath->query('//style:text-properties[@fo:country]');
        /** @var $styleNode \DOMElement */
        foreach ($nodeList as $styleNode) {
            $styleNode->removeAttribute('fo:country');
        }
    }
}