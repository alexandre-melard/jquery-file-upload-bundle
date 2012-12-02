<?php

namespace Mylen\JQueryFileUploadBundle\Services;

use Mylen\JQueryFileUploadBundle\Services\IFileUploader;
use Mylen\JQueryFileUploadBundle\Services\IFileManager;
use Mylen\JQueryFileUploadBundle\Services\UploadHandler;

class FileUploader implements IFileUploader
{
    protected $fileBasePath;
    protected $webBasePath;

    /** @var \Symfony\Component\HttpFoundation\Request */
    protected $request;

    protected $allowedExtensions;
    protected $sizes;
    protected $originals;
    protected $uploadHandlerFactory;

    public function __construct($fileBasePath = null, $webBasePath = null, $request = null, $allowedExtensions = null, $sizes = null, $originals = null)
    {
        $this->fileBasePath = $fileBasePath;
        $this->webBasePath = $webBasePath;
        $this->request = $request;
        $this->allowedExtensions = $allowedExtensions;
        $this->sizes = $sizes;
        $this->originals = $originals;
    }

    /**
     * {@inheritdoc }
     */
    public function handleFileUpload($folder)
    {
        // Build a regular expression like /(\.gif|\.jpg|\.jpeg|\.png)$/i
        $allowedExtensionsRegex = '/('
                . implode('|',
                        array_map(function ($extension)
                                {
                                    return '\.' . $extension;
                                }, $this->allowedExtensions)) . ')$/i';

        $sizes = (isset($this->sizes) && is_array($this->sizes)) ? $this->sizes : array();

        $filePath = $this->fileBasePath . '/' . $folder;
        $webPath = $this->webBasePath . '/' . $folder;

        foreach ($sizes as &$size) {
            $size['upload_dir'] = $filePath . '/' . $size['folder'] . '/';
            $size['upload_url'] = $webPath . '/' . $size['folder'] . '/';
        }

        $originals = $this->originals;

        $uploadDir = $filePath . '/' . $originals['folder'] . '/';

        foreach ($sizes as &$size) {
            @mkdir($size['upload_dir'], 0777, true);
        }

        @mkdir($uploadDir, 0777, true);

        return $this->getUploadHandlerFactory()->createUploadHandler(
                array(
                        'upload_dir' => $uploadDir, 
                        'upload_url' => $webPath . '/' . $originals['folder'] . '/', 
                        'script_url' => $this->request->getUri(),
                        'image_versions' => $sizes, 
                        'accept_file_types' => $allowedExtensionsRegex
                        ), 
                false
                );
    }
    
    public function getUploadHandlerFactory() {
        if (isset($this->uploadHandlerFactory)) {
            return $this->uploadHandlerFactory;
        } else {
            return new UploadHandlerFactory();
        }
    }
    
    public function setUploadHandlerFactory($uploadHandlerFactory) {
        $this->uploadHandlerFactory = $uploadHandlerFactory;
    }
    
    public function getFileBasePath()
    {
        return $this->fileBasePath;
    }
    
    public function getWebBasePath() {
        return $this->webBasePath;
    }
    
}
