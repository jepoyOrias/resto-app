<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DateBetween implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
     
        $lastDate = Carbon::now()->addWeek()->startOfDay();
        $value = Carbon::parse($value)->startOfDay();
        if(!($value >= now()->startOfDay() && $value <= $lastDate)){
            $fail('Please choose a date between a week from now');
        }
    }
}
