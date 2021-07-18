<?php
namespace App\Traits;

use App\Models\Company;

trait CompanyTrait{
    public function getSelectedCompany(){
        return Company::where('user_id', auth()->user()->id)->first();
    }
}
