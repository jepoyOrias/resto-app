<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TimeBetween implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $pickupDate = Carbon::parse($value);
        $pickupTime = Carbon::createFromTime($pickupDate->hour,$pickupDate->minute,$pickupDate->second);
       
        $openingTime = Carbon::createFromTimeString('9:00:00');
        $closingTime = Carbon::createFromTimeString('19:00:00');
        if(!($pickupTime->between($openingTime, $closingTime))){
            $fail('Please choose a time between 9am to 7pm.');
        }
    }
}
