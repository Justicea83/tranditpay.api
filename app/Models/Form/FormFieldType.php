<?php

namespace App\Models\Form;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $name
 */
class FormFieldType extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['title', 'name'];
}
