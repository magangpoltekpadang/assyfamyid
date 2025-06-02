<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TimeAfter implements ValidationRule
{
    protected $otherField;
    protected $otherValue;

    public function __construct($otherField)
    {
        $this->otherField = $otherField;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Ambil nilai field lain dari input request
        $this->otherValue = request()->input($this->otherField);

        // Pastikan keduanya ada
        if (!$value || !$this->otherValue) {
            $fail("The {$attribute} must be a time after {$this->otherField}.");
            return;
        }

        // Bandingkan waktu dengan strtotime
        if (strtotime($value) <= strtotime($this->otherValue)) {
            $fail("The {$attribute} must be a time after {$this->otherField}.");
        }
    }
}
