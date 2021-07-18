<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Vehicle;
use App\Traits\CompanyTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VehicleController extends Controller
{
    use CompanyTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->query('search')){

            return response()->json(Vehicle::where('company_id', $this->getSelectedCompany()->id)
                ->where('name', 'like', '%'.$request->query('search').'%')
                ->limit(6)
                ->get());
        }
        return response()->json(Vehicle::where('company_id', $this->getSelectedCompany()->id)->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'bail|required|max:255',
            'maximum_capacity' => 'bail|required|gte:1',
            'photo' => 'bail|nullable|mimes:.png, .jpeg, .jpg, .gif',
        ];
        $attributes = [
            'name' => 'nome',
            'brand' => 'marca',
            'maximum_capacity' => 'Capacidade máxima',
            'model' => 'Modelo',
            'photo' => 'Fotografia',
        ];
        $validator = Validator::make($request->all(), [], $rules, $attributes);
        if ($validator->fails()) {
            return response()->json($validator, 422);
        }

        $company = $this->getSelectedCompany();
        $inputs = $request->only(['name', 'brand', 'model', 'maximum_capacity', 'model', 'photo_path']);

        $vehicle = new vehicle($inputs);
        $vehicle->company_id = $company->id;
        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $filename = Str::random(4) . time() . '.' . $file->getClientOriginalExtension();
            $path = 'public/img/' . $filename;
            Storage::disk('local')->put($path, file_get_contents($file));
            $vehicle->photo_path = 'storage/img/' . $filename;
        }
        $vehicle->save();

        return response()->json($vehicle, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $find = Vehicle::findOrFail($id);
        return response()->json($find);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $rules = [
            'name' => 'bail|required|max:255',
            'maximum_capacity' => 'bail|required|gte:1',
            'photo' => 'bail|nullable|mimes:.png, .jpeg, .jpg, .gif',
        ];
        $attributes = [
            'name' => 'nome',
            'brand' => 'marca',
            'maximum_capacity' => 'Capacidade máxima',
            'model' => 'Modelo',
            'photo' => 'Fotografia',
        ];
        $validator = Validator::make($request->all(), [], $rules, $attributes);
        if ($validator->fails()) {
            return response()->json($validator, 422);
        }

        $inputs = $request->only(['name', 'brand', 'model', 'maximum_capacity', 'model', 'photo_path']);

        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update( $inputs);
        if ($request->hasfile('photo')) {
            $deletePath = Str::replaceFirst('storage', 'public', $vehicle->photo_path);
            Storage::disk('local')->delete($deletePath);

            $file = $request->file('photo');
            $filename = Str::random(4) . time() . '.' . $file->getClientOriginalExtension();
            $path = 'public/img/' . $filename;
            Storage::disk('local')->put($path, file_get_contents($file));
            $vehicle->photo_path = 'storage/img/' . $filename;
        }
        $vehicle->save();

        return response()->json($vehicle, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $deletePath = Str::replaceFirst('storage', 'public', $vehicle->photo_path);
        Storage::disk('local')->delete($deletePath);
        $vehicle->delete();
       return response()->json("ok", 200);
    }
}
