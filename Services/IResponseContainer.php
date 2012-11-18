<?php

namespace Mylen\JQueryFileUploadBundle\Services;

interface IResponseContainer
{
    public function getReadFile();

    public function getBody();

    public function getHeader();

    public function getType();
}
