<?php

namespace App\Models\Form;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormResponse extends Model
{
    use HasFactory;

    protected $fillable = ['form_id', 'reference', 'user_id'];

    public function fieldResponses(): HasMany
    {
        return $this->hasMany(FormFieldResponse::class);
    }
}
