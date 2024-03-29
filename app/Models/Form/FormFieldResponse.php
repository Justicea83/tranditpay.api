<?php

namespace App\Models\Form;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormFieldResponse extends Model
{
    use HasFactory;

    protected $fillable = ['form_field_id', 'response'];
}
