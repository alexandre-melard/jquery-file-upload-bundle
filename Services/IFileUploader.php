<?php

namespace Mylen\JQueryFileUploadBundle\Services;

interface IFileUploader
{
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
    public function handleFileUpload($folder);
    
}
