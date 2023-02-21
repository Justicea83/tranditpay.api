<?php

namespace App\Models\Copilot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CopilotRestriction extends Model
{
    use HasFactory;

    protected $fillable = ['copilot_id', 'restriction_id'];
}
