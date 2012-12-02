<?php
namespace Mylen\JQueryFileUploadBundle\Services;


interface IUploadHandlerFactory
{
    public function createUploadHandler($options, $initialize);   
}
