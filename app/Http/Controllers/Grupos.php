<?php

namespace App\Http\Controllers;

use App\Client;
use App\Grupo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Grupos extends Controller
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
            'pageName' => 'Grupos'
        ];
        return view('grupos/index', compact('pageConfigs'));
    }

    /**
     * Returns data for datatable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $result = Grupo::with('responsable')->get();
        return datatables()->of($result)
            ->addColumn('noClientes', function ($query) {
                return count($query->clientes);
            })
            ->addColumn('responsable', function ($query) {
                return $query->responsable->nombreCompleto;
            })
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
    public function create()
    {
        $pageConfigs = [
            'mainLayoutType' => 'vertical',
            'pageName' => 'Nuevo Grupo'
        ];
        
        $clientes = Client::doesntHave('grupo')->where('status', '<>', 'Archivado')->get();

        return view('/grupos/create', compact('pageConfigs', 'clientes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $grupo = new Grupo($request->all());
            $grupo->save();
            foreach ($request->clientes as $cliente) {
                $newcliente = Client::find($cliente);
                $grupo->clientes()->save($newcliente);
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' => $ex->getMessage()], 500);
        }

        return redirect('/grupos/')->with('message', 'OK');
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
