<?php

namespace app\services\readers;

interface ReaderInterface
{
    public function read(string $data);
}