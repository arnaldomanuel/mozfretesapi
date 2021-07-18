<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Company::all());
    }

    public function filterCount(Request  $request){
        $filter=$request->query('filter');
        $company_type=$request->query('company_type')=='undefined'?'':$request->query('company_type');
        return response(Company::where([
                    ['company_type', 'like',"%$company_type%"]
        ])
                                ->orWhere('telephone', 'like',"%$filter%")
            ->orWhere('name', 'like', "%$filter%")
                                ->orWhere('address', 'like',"%$filter%")
                                ->orWhere('address1', 'like',"%$filter%")
                                ->orWhere('address2', 'like',"%$filter%")

                                ->count());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Company::findOrFail($id), 200);
    }

    public function getUserCompanyDetails(){
        $user = auth()->user();
        $company = Company::where('user_id', $user->id)->first();
        return response()->json($company, 200);
    }


   public function filterSearch(Request $request){
       $filter=$request->query('filter');
       $company_type=$request->query('company_type')=='undefined'?'':$request->query('company_type');

       return response()->json(Company::where([
            ['company_type', 'like',"%$company_type%"]
        ])
            ->orWhere('telephone', 'like',"%$filter%")
            ->orWhere('name', 'like', "%$filter%")
            ->orWhere('address', 'like',"%$filter%")
            ->orWhere('address1', 'like',"%$filter%")
            ->orWhere('address2', 'like',"%$filter%")
            ->offset($request->query('startRow'))
            ->limit($request->query('fetchCount'))
            ->orderBy($request->query('sortBy'))
            ->get()
           );
   }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $company->update(json_decode(json_encode($request->all()), true)["data"]);
        return response()->json($company, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
