<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckReservation extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'reservation_date',
        'phone_number',
        'table_id',
        'guest_number',
    ];

    protected $casts = [ 'reservation_date'=>'datetime'];

    public function table(){
        return $this->belongsTo(Table::class);
    }

}
