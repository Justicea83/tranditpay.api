<?php

namespace Database\Seeders;

use App\Models\Form\FormFieldType;
use Illuminate\Database\Seeder;

class FormFieldTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [
            [
                "name" => "short_answer",
                "title" => "Short Answer"
            ],
            [
                "name" => "paragraph",
                "title" => "Paragraph"
            ],
            [
                "name" => "multiple_choice",
                "title" => "Multiple Choice"
            ],
            [
                "name" => "checkboxes",
                "title" => "Checkboxes"
            ],
            [
                "name" => "dropdown",
                "title" => "Drop-down"
            ],
            [
                "name" => "date",
                "title" => "Date"
            ],
            [
                "name" => "time",
                "title" => "Time"
            ],
        ];

        foreach ($fields as $field) {
            FormFieldType::query()->firstOrCreate($field);
        }
    }
}
