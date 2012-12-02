<?php

namespace Mylen\JQueryFileUploadBundle\Services;

use Mylen\JQueryFileUploadBundle\Services\UploadHandler;

class UploadHandlerFactory implements IUploadHandlerFactory
{
    public function createUploadHandler($options, $initialize) {
        return new UploadHandler($options, $initialize);
    }
}
