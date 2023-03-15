<?php

namespace App\Models\Form;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property mixed $id
 * @property mixed $label
 * @property mixed $required
 * @property FormFieldType $formFieldType
 * @property Collection $options
 * @property mixed|string $form_field_type_id
 * @property FormSection $formSection
 */
class FormField extends Model
{
    use HasFactory;

    public function options(): HasMany
    {
        return $this->hasMany(FormFieldOption::class);
    }

    public function formFieldType(): BelongsTo
    {
        return $this->belongsTo(FormFieldType::class, 'form_field_type_id');
    }

    public function formSection(): BelongsTo
    {
        return $this->belongsTo(FormSection::class);
    }
}
