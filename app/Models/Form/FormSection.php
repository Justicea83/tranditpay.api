<?php

namespace App\Models\Form;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property mixed $id
 * @property mixed $description
 * @property mixed $title
 * @property Collection $fields
 */
class FormSection extends Model
{
    use HasFactory;

    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class);
    }
}
