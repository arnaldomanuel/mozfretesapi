<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FreightJourney extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const NOVO = 'Novo';
    public $with=['vehicle'];


    protected $fillable=['description', 'from_location', 'to_location', 'from_date', 'to_date', 'vehicle_id',
        'price_set', 'price_negotiate','phone_journey', 'status'];

    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }
}
