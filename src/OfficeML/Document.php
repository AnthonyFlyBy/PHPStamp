<?php
namespace OfficeML;

class Document
{
    /**
     * @var string
     */
    public $documentName;
    /**
     * @var string
     */
    public $documentPath;

    /**
     * @param string $filePath
     * @throws Exception\ArgumentsException
     */
    public function __construct($filePath) {
        if (!file_exists($filePath)) {
            throw new Exception\ArgumentsException('File not found');
        }

        $this->documentPath = $filePath;
        $this->documentName = pathinfo($this->documentPath, PATHINFO_FILENAME);
    }

    /**
     * Extract main content file from document.
     * @param string $to
     * @param string $contentPath
     * @param bool $overwrite
     * @return string
     * @throws Exception\ArgumentsException
     */
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