<?php

namespace Mylen\JQueryFileUploadBundle\Services;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;
use Mylen\JQueryFileUploadBundle\Services\IFileManager;

class FileManager extends Filesystem implements IFileManager
{
    protected $file_base_path;

    public function __construct($file_base_path)
    {
        $this->file_base_path = $file_base_path;
    }

    /**
     * {@inheritdoc }
     */
    public function getFiles($folder, $fullPath = false)
    {
        $ret = array();
        $folder = $this->file_base_path . DIRECTORY_SEPARATOR . $folder;
        if ($this->exists($folder)) {
            $finder = new Finder();
            $finder->in($folder . DIRECTORY_SEPARATOR . 'originals');
            foreach ($finder as $entry) {
                /** @var $entry SplFileInfo */
                array_push($ret, $fullPath ? $entry->getRealPath() : $entry->getBasename());
            }
        }
        return $ret;
    }

    /**
     * {@inheritdoc }
     */
    public function removeFiles($folder)
    {
        // Remove folder, let the caller deal with an IO exception in case of error
        $this->remove($this->file_base_path . DIRECTORY_SEPARATOR . $folder);
    }

    /**
     * {@inheritdoc }
     */
    public function syncFiles($from, $to, $options = null)
    {
        // We're syncing and potentially deleting folders, so make sure
        // we were passed something - make it a little harder to accidentally
        // trash your site
        if (!strlen(trim($from))) {
            throw new \Exception("from_folder option looks empty, bailing out");
        }
        if (!strlen(trim($to))) {
            throw new \Exception("to_folder option looks empty, bailing out");
        }

        $from = $this->file_base_path . DIRECTORY_SEPARATOR . $from;
        $to = $this->file_base_path . DIRECTORY_SEPARATOR . $to;
        if ($this->exists($from)) {
            // we use delete by default to keep backward compatibility
            $this->mirror($from, $to, null, array("delete" => isset($options['delete']) ? $options['delete'] : true));
            if (isset($options['remove_from_folder']) && $options['remove_from_folder']) {
                $this->remove($from);
            }
        } else {
            // A missing from_folder is not an error. This is commonly the case
            // when syncing from something that has nothing attached to it yet, etc.
        }
    }
}
