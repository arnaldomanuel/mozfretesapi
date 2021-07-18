<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\FreightJourney;
use App\Models\Load;
use App\Models\Vehicle;
use App\Traits\CompanyTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoadController extends Controller
{
    use CompanyTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Load::where('company_id', $this->getSelectedCompany()->id)->get(), 200);
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
            'from_location' => 'bail|required|min:5',
            'to_location' => 'bail|required|min:5',
            'from_date' => 'bail|required',
            'to_date' => 'bail|required',
            'weight' => 'bail|required|gte:0',
            'picture' => 'bail|nullable|mimes:.png, .jpeg, .jpg, .gif',
        ];
        $attributes = [
            'name' => 'nome',
            'description' => 'descrição',
            'from_location' => 'Local de partida',
            'to_location' => 'Local de destino',
            'from_date' => 'Data de partida',
            'to_date' => 'Data de destino',
            'phone_journey' => 'Contacto de viagem',
            'weight' => 'Peso de carga',
            'picture' => 'Fotografia',
        ];
        $inputsFromRequest = json_decode($request->load, true);

        $validator = Validator::make($inputsFromRequest, [], $rules, $attributes);
        if ($validator->fails()) {
            return response()->json($validator, 422);
        }

        $company = $this->getSelectedCompany();

        $load = new Load($inputsFromRequest);
        $load->company_id=$company->id;
        if ($request->hasfile('picture')) {
            $file = $request->file('picture');
            $filename = Str::random(4) . time() . '.' . $file->getClientOriginalExtension();
            $path = 'public/img/' . $filename;
            Storage::disk('local')->put($path, file_get_contents($file));
            $load->picture = 'storage/img/' . $filename;
        }
        $load->save();

        return response()->json($load, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Load::findorFail($id), 200);
    }

    public function filterCount(Request  $request){
        $filter=$request->query('filter');
        return response(Load::where([
            ['name', 'like', "%$filter%"],
            ['status', '=',$request->query('status')]
        ])
            ->orWhere('from_location', 'like',"%$filter%")
            ->orWhere('to_location', 'like',"%$filter%")
            ->orWhere('description', 'like',"%$filter%")
            ->count());
    }

    public function filterSearch(Request $request){
        $filter=$request->query('filter');
        return response()->json(Load::where([
            ['name', 'like', "%$filter%"],
            ['status', '=',$request->query('status')]
        ])
            ->orWhere('from_location', 'like',"%$filter%")
            ->orWhere('to_location', 'like',"%$filter%")
            ->orWhere('description', 'like',"%$filter%")
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
        $rules = [
            'from_location' => 'bail|required|min:5',
            'to_location' => 'bail|required|min:5',
            'from_date' => 'bail|required',
            'to_date' => 'bail|required',
            'weight' => 'bail|required|gte:0',
            'picture' => 'bail|nullable|mimes:.png, .jpeg, .jpg, .gif',
        ];
        $attributes = [
            'name' => 'nome',
            'description' => 'descrição',
            'from_location' => 'Local de partida',
            'to_location' => 'Local de destino',
            'from_date' => 'Data de partida',
            'to_date' => 'Data de destino',
            'phone_journey' => 'Contacto de viagem',
            'weight' => 'Peso de carga',
            'picture' => 'Fotografia',
        ];
        $inputsFromRequest = json_decode($request->load, true);

        $validator = Validator::make($inputsFromRequest, [], $rules, $attributes);
        if ($validator->fails()) {
            return response()->json($validator, 422);
        }

        $load = Load::findOrFail($id);
        $load->update($inputsFromRequest);
        if ($request->hasfile('picture')) {
            $deletePath = Str::replaceFirst('storage', 'public', $load->picture);
            Storage::disk('local')->delete($deletePath);

            $file = $request->file('picture');
            $filename = Str::random(4) . time() . '.' . $file->getClientOriginalExtension();
            $path = 'public/img/' . $filename;
            Storage::disk('local')->put($path, file_get_contents($file));
            $load->picture = 'storage/img/' . $filename;
        }
        $load->save();

        return response()->json($load, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $load = Load::findOrFail($id);
        $deletePath = Str::replaceFirst('storage', 'public', $load->picture);
        Storage::disk('local')->delete($deletePath);
        $load->delete();
        return response()->json("ok", 200);
    }
}
