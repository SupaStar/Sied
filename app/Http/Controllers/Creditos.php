<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Client;
use App\Credito;
use App\Grupo;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class Creditos extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = [
            'mainLayoutType' => 'vertical',
            'pageName' => 'Creditos'
        ];
        return view('creditos/index', compact('pageConfigs'));
    }

    /**
     * Return JSON for datatable in index.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $result = Credito::all();
        return datatables()->of($result)
            ->addColumn('actions', function ($query) {
                return '
                  <a href="/clientes/fisicas/info/' . $query->id . '" title="Información"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-eye"></i></button></a>
                  <a href="/clientes/fisicas/editar/' . $query->id . '" title="Editar"><button style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-edit primary"></i></button></a>
                  <button title="Archivar" onclick="del(' . $query->id . ');" style="z-index:999" type="button" class="btn btn-default"><i class="feather icon-trash danger"></i></button>';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function new()
    {
        $grupos = Grupo::whereDoesntHave('creditos', function (Builder $query) {
            $query->where('status', '=', 'activo');
        })->get();


        $pageConfigs = [
            'mainLayoutType' => 'vertical',
            'pageName' => 'Nuevo Credito'
        ];
        return view('creditos/new-credito', compact('grupos', 'pageConfigs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $credito = new Credito($request->all());
            $credito->save();
            if (isset($request->clientesCreditos)) {
                $credito->clientesCreditos()->createMany($request->clientesCreditos);
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' => $ex->getMessage()], 500);
        }

        return redirect('/creditos')->with('message', 'OK');
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
        //
    }
}
