<?php

namespace App\Services\Collection;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ICollectionService
{
    public function loadCollection(string $type,array $payload) : Collection|Model;
    public function getCollectionTypes() : array;
}
