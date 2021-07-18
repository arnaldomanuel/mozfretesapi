<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=['name', 'brand', 'model', 'maximum_capacity', 'photo_path', 'company_id'];

    public function company(){
        return $this->belongsTo(Company::class);
    }
}
