<?php

namespace App\Dto;

abstract class BaseDto
{
    abstract public function mapFromModel($model);
}
