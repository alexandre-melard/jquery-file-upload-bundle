<?php

namespace BlueImp\JQueryFileUploadBundle\Services;

use BlueImp\JQueryFileUploadBundle\Services\IFileUploader;
use BlueImp\JQueryFileUploadBundle\Services\IFileManager;
use BlueImp\FileUpload\UploadHandler;

class FileUploader implements IFileUploader
{
    protected $fileBasePath;
    protected $webBasePath;

    /** @var \Symfony\Component\HttpFoundation\Request */
    protected $request;

    /** @var IFileManager */
    protected $fileManager;
    protected $allowedExtensions;
    protected $sizes;
    protected $originals;

    public function __construct(
        $fileBasePath = null,
        $webBasePath = null,
        $request = null,
        $fileManager = null,
        $allowedExtensions = null,
        $sizes = null,
        $originals = null
    )
    {
        $this->fileBasePath = $fileBasePath;
        $this->webBasePath = $webBasePath;
        $this->request = $request;
        $this->fileManager = $fileManager;
        $this->allowedExtensions = $allowedExtensions;
        $this->sizes = $sizes;
        $this->originals = $originals;
    }

    /**
     * {@inheritdoc }
     */
    public function getFiles($folder, $fullPath = false)
    {
        return $this->fileManager->getFiles($folder, $fullPath);
    }

    /**
     * {@inheritdoc }
     */
    public function removeFiles($folder)
    {
        return $this->fileManager->removeFiles($folder);
    }

    /**
     * {@inheritdoc }
     */
    public function syncFiles($to, $from, $options = null)
    {
        return $this->fileManager->syncFiles($to, $from, $options);
    }

    /**
     * {@inheritdoc }
     */
    public function handleFileUpload($folder)
    {
        // Build a regular expression like /(\.gif|\.jpg|\.jpeg|\.png)$/i
        $allowedExtensionsRegex = '/(' . implode('|', array_map(function ($extension) {
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

        new UploadHandler(
            array(
                'upload_dir' => $uploadDir,
                'upload_url' => $webPath . '/' . $originals['folder'] . '/',
                'script_url' => $this->request->getUri(),
                'image_versions' => $sizes,
                'accept_file_types' => $allowedExtensionsRegex
            ));

        // Without this Symfony will try to respond; the BlueImp upload handler class already did,
        // so it's time to hush up
        exit(0);
    }
}
