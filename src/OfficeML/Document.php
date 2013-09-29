<?php
namespace OfficeML;

class Document
{
    public $documentName;
    public $documentPath;

    public function __construct($filePath) {
        if (!is_file($filePath)) {
            throw new Exception\ArgumentsException('File not found');
        }

        $this->documentPath = $filePath;
        $this->documentName = pathinfo($this->documentPath, PATHINFO_FILENAME);
    }

    public function extract($to, $contentPath, $overwrite = false) {
        $filePath = $to . $this->documentName . '/' . $contentPath;

        if (!file_exists($filePath) || $overwrite === true) {
            $zip = new \ZipArchive();

            // Wow
            if ($zip->open($this->documentPath) !== true) {
                throw new Exception\ArgumentsException('Document not zip');
            }

            if ($zip->extractTo($to . $this->documentName, $contentPath) === false) {
                throw new Exception\ArgumentsException('Destination not reachable');
            }
        }
        return $filePath;
    }
}