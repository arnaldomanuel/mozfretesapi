<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Load extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable =['name','description', 'from_location', 'to_location', 'from_date', 'to_date', 'phone_journey','weight', 'status', 'picture'];

    public function company(){
        return $this->belongsTo(Company::class);
    }
}
