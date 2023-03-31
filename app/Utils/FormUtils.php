<?php

namespace App\Utils;

class FormUtils
{
    const SHORT_ANSWER = 'short_answer';
    const PARAGRAPH = 'paragraph';
    const  MULTIPLE_CHOICES = 'multiple_choice';
    const  CHECKBOXES = 'checkboxes';
    const   DROPDOWN = 'dropdown';
    const  DATE = 'date';
    const   TIME = 'time';

    const WITH_OPTIONS = [
        self::MULTIPLE_CHOICES,
        self::CHECKBOXES,
        self::DROPDOWN,
    ];
}
