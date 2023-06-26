<?php

namespace App\Http\Requests\Admin;

use App\Rules\DateBetween;
use App\Rules\TimeBetween;
use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' =>'required',
            'last_name' =>'required',
            'email' =>'required|email',
            'phone_number' =>'required',
            'guest_number' =>'required',
            'table_id'=>'required',
            'reservation_date' =>['required','date', new DateBetween],
            'reservation_time' =>['required'],
        ];
    }
}
