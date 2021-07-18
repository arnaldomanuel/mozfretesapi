<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory;
use SoftDeletes;
    protected  $fillable=['title', 'pdf_booklet', 'photo', 'company_id'];

    public function company(){
        return $this->belongsTo(Company::class);
    }
}
