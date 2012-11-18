<?php
/**
 * Created by JetBrains PhpStorm.
 * User: A140980
 * Date: 17/11/12
 * Time: 23:22
 * To change this template use File | Settings | File Templates.
 */
namespace BlueImp\JQueryFileUploadBundle\Services;

use UploadHandler as BaseUploadHandler;

class UploadHandler extends BaseUploadHandler implements IResponseContainer
{
    protected $body;
    protected $header;
    protected $readfile;

    protected function readfile($file_path) {
        $this->readfile = $file_path;
    }

    protected function body($str) {
        $this->body .= $str;
    }

    protected function header($str) {
        $this->header .= $str;
    }

    public function getReadFile()
    {
        return $this->readfile;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getHeader()
    {
        return $this->header;
    }
}
