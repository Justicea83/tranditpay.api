<?php

namespace App\Dto;

abstract class BaseDto
{
    public int $id;

    /**
     * @param int $id
     * @return BaseDto
     */
    public ?string $date;

    public function setId(int $id): BaseDto
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string|null $date
     * @return BaseDto
     */
    public function setDate(?string $date): BaseDto
    {
        $this->date = $date;
        return $this;
    }

    abstract public function mapFromModel($model);
}
