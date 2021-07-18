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

class FreightJourneyController extends Controller
{
    use CompanyTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(FreightJourney::where('company_id', $this->getSelectedCompany()->id)->orderBy('id', 'desc')->get());
    }

    public function closeFreightJourney(Request  $request){

        $freightJouney= FreightJourney::find($request->freight_journey_id);
        $freightJouney->status = 'Fechado';
        $freightJouney->save();

        return response()->json('OK');
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

        $validator = Validator::make($request->all(), [], $rules, $attributes);
        if ($validator->fails()) {
            return response()->json($validator, 422);
        }

        $fj = new FreightJourney($request->all());
        $fj->status= FreightJourney::NOVO;
        $fj->company_id=$this->getSelectedCompany()->id;
        $fj->vehicle_id=Vehicle::where('name', $request->truck_id)->first()->id;
        $fj->save();

        return response()->json($fj, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(FreightJourney::findOrFail($id));
    }

    public function filterSearch(Request $request){
        $filter=$request->query('filter');
        return response()->json(FreightJourney::where([
            ['from_location', 'like', "%$filter%"],
            ['status', '=',$request->query('status')]
        ])
            ->orWhere('from_location', 'like',"%$filter%")
            ->orWhere('to_location', 'like',"%$filter%")
            ->orWhere('phone_journey', 'like',"%$filter%")
            ->offset($request->query('startRow'))
            ->limit($request->query('fetchCount'))
            ->orderBy($request->query('sortBy'))
            ->get()
        );
    }
    public function filterCount(Request  $request){
        $filter=$request->query('filter');
        return response(FreightJourney::where([
            ['from_location', 'like', "%$filter%"],
            ['status', 'like','%'.$request->query('news').'%'],
            ['status', '=',$request->query('status')]
            ])
            ->orWhere('from_location', 'like',"%$filter%")
            ->orWhere('to_location', 'like',"%$filter%")
            ->orWhere('phone_journey', 'like',"%$filter%")
            ->count());
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

        $validator = Validator::make($request->all(), [], $rules, $attributes);
        if ($validator->fails()) {
            return response()->json($validator, 422);
        }
        $fj = FreightJourney::findOrFail($id);
        $fj->update($request->all());
        $fj->save();
        return response()->json($fj);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fj = FreightJourney::findOrFail($id);
        $fj->delete();
        return response()->json("ok", 200);
    }
}
