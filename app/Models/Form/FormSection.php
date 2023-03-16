<?php

namespace App\Models\Form;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property mixed $id
 * @property mixed $description
 * @property mixed $title
 * @property Collection $fields
 * @property Form $form
 */
class FormSection extends Model
{
    use HasFactory;

    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class);
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
