<?php

namespace BlueImp\JQueryFileUploadBundle\Services;

interface IResponseContainer
{
    public function getReadFile();

    public function getBody();

    public function getHeader();
}
