<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'telephone', 'website', 'address', 'email1', 'email2', 'telephone1',
        'cellphone', 'logo','whatsapp', 'address1', 'address2', 'address3', 'company_type', 'user_id'];

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function vehicles(){
        return $this->hasMany(Vehicle::class);
    }
    public function loads(){
        return $this->hasMany(Load::class);
    }
    public function services(){
        return $this->hasMany(Service::class);
    }
    public function freightJourneys(){
        return $this->hasMany(FreightJourney::class);
    }
}
