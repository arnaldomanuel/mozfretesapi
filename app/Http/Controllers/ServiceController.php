<?php

namespace App\Http\Controllers;

use App\Models\Load;
use App\Models\Service;
use App\Traits\CompanyTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    use CompanyTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Service::where('company_id', $this->getSelectedCompany()->id)->orderBy('id', 'desc')->get(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'bail|required|max:255',
            'file' => 'bail|nullable|mimes:.png, .jpeg, .jpg, .gif, .pdf',
        ];
        $attributes = [
            'name' => 'nome',
            'file' => 'PDF ou foto',
        ];
        $validator = Validator::make($request->all(), [], $rules, $attributes);
        if ($validator->fails()) {
            return response()->json($validator, 422);
        }
        $service = new Service();
        $company = $this->getSelectedCompany();
        $service->company_id=$company->id;
        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $filename = Str::random(4) . time() . '.' . $file->getClientOriginalExtension();

            if (Str::contains(strtolower($file->getClientOriginalExtension()), 'pdf')){
                $path = 'public/pdf/' . $filename;
                $service->pdf_booklet = 'storage/pdf/' . $filename;
            }else {
                $path = 'public/service/img/' . $filename;
                $service->photo = 'storage/service/img/' . $filename;
            }
            Storage::disk('local')->put($path, file_get_contents($file));
        }
        $service->title= $request->title;
        $service->save();
        return response()->json('done', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        if($service->photo){
            $deletePath = Str::replaceFirst('storage', 'public', $service->photo);
        } else {
            $deletePath = Str::replaceFirst('storage', 'public', $service->pdf_booklet);
        }
        Storage::disk('local')->delete($deletePath);
        $service->delete();
        return response()->json("ok", 200);
    }
}
