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
     * Handles a file upload. Call this from an action, after validating the user's
     * right to upload and delete files and determining your 'folder' option. A good
     * example:
     *
     * $id = $this->getRequest()->get('id');
     * // Validate the id, make sure it's just an integer, validate the user's right to edit that
     * // object, then...
     * $this->get('punkave.file_upload').handleFileUpload(array('folder' => 'photos/' . $id))
     *
     * DOES NOT RETURN. The response is generated in native PHP by Mylen's UploadHandler class.
     *
     * Note that if %file_uploader.file_path%/$folder already contains files, the user is
     * permitted to delete those in addition to uploading more. This is why we use a
     * separate folder for each object's associated files.
     *
     * Any passed options are merged with the service parameters. You must specify
     * the 'folder' option to distinguish this set of uploaded files
     * from others.
     *
     * @param   string              $folder     The folder to upload/delete or retrieve the files from
     * @return  IResponseContainer              Contains the header, body and/or the file to send
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

        $uploadHandler = new UploadHandler(
            array(
                'upload_dir' => $uploadDir,
                'upload_url' => $webPath . '/' . $originals['folder'] . '/',
                'script_url' => $this->request->getUri(),
                'image_versions' => $sizes,
                'accept_file_types' => $allowedExtensionsRegex
            ),
            false);
        return $uploadHandler;
    }
}
