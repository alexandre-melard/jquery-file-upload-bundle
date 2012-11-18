<?php

namespace Mylen\JQueryFileUploadBundle\Services;

interface IFileUploader
{
    /**
     * Get a list of files already present. The 'folder' option is required.
     * @param   string  $folder
     * @param   bool    $fullPath
     * @return  array
     */
    public function getFiles($folder, $fullPath = false);

    /**
     * Remove the folder specified by 'folder' and its contents.
     * @param   string  $folder
     */
    public function removeFiles($folder);

    /**
     * Sync existing files from one folder to another.
     * As with the 'folder' option elsewhere, these are appended
     * to the file_base_path for you, missing parent folders are created, etc. If
     * 'fromFolder' does not exist no error is reported as this is common if no files
     * have been uploaded. If there are files and the sync reports errors an exception
     * is thrown.
     * @param string    $to         the source folder
     * @param string    $from       the target folder
     * @param array     $options    An array of boolean options
     *                              Valid options are:
     *                              - $options['override'] Whether to override an existing file on copy or not (see copy())
     *                              - $options['copy_on_windows'] Whether to copy files instead of links on Windows (see symlink())
     *                              - $options['delete'] Default true Whether to delete files that are not in the source directory
     *                              - $options['remove_from_folder'] Default false Whether to delete from folder
     */
    public function syncFiles($to, $from, $options = null);

}
