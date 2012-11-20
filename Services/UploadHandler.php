<?php
/**
 * Created by JetBrains PhpStorm.
 * User: A140980
 * Date: 17/11/12
 * Time: 23:22
 * To change this template use File | Settings | File Templates.
 */
namespace Mylen\JQueryFileUploadBundle\Services;

use UploadHandler as BaseUploadHandler;

class UploadHandler extends BaseUploadHandler implements IResponseContainer
{
    protected $type = 200;
    protected $body = '';
    protected $header = array();
    protected $readfile = null;

    protected function readfile($file_path) {
        $this->readfile = $file_path;
    }

    protected function body($str) {
        $this->body .= $str;
    }

    protected function header($str) {
        if (strchr($str, ':')) {
            $head = explode(':', $str);
            array_push($this->header, array($head[0]=>$head[1]));
        } else {
            if (strstr($str, '403'))
                $this->type = 403;
            else if (strstr($str, '405'))
                $this->type = 405;
        }
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

    public function getType()
    {
        return $this->type;
    }

}
