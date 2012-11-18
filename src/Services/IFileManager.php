<?php
/**
 * Created by JetBrains PhpStorm.
 * User: A140980
 * Date: 16/11/12
 * Time: 21:01
 * To change this template use File | Settings | File Templates.
 */
namespace BlueImp\JQueryFileUploadBundle\Services;

interface IFileManager
{

    /**
     * Get a list of files already present.
     * @param   string    $folder
     * @param   boolean   $fullPath
     * @return  array
     */
    public function getFiles($folder, $fullPath = null);

    /**
     * Remove the folder and its contents.
     * @param   string  $folder
     * @return  mixed
     * @throws  \Symfony\Component\Filesystem\Exception\IOException
     */
    public function removeFiles($folder);

    /**
     * Sync existing files from one folder to another. The 'fromFolder' and 'toFolder'
     * options are required. As with the 'folder' option elsewhere, these are appended
     * to the file_base_path for you, missing parent folders are created, etc. If
     * 'fromFolder' does not exist no error is reported as this is common if no files
     * have been uploaded. If there are files and the sync reports errors an exception
     * is thrown.
     *
     * If you pass consistent options to this method and handleFileUpload with
     * regard to paths, then you will get consistent results.
     * @param string    $from
     * @param string    $to
     * @param array     $options   An array of boolean options
     *                          Valid options are:
     *                             - $options['override'] Whether to override an existing file on copy or not (see copy())
     *                             - $options['copy_on_windows'] Whether to copy files instead of links on Windows (see symlink())
     *                             - $options['delete'] Default true Whether to delete files that are not in the source directory
     *                             - $options['remove_from_folder'] Default false Whether to delete from folder
     * @return mixed
     */
    public function syncFiles($from, $to, $options = null);
}
