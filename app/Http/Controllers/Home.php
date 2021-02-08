<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\LastAccess;
use Illuminate\Support\Carbon;
use App\User;

class Home extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $user = Auth::user();
      $lastaccess = Carbon::now()->timezone('America/Mexico_City')->format('Y-m-d H:i:s');
      $last = new LastAccess();
      $last->user_id = $user->id;
      $last->login = $lastaccess;
      $last->save();

      $ll = LastAccess::where('user_id', $user->id)->limit(2)->orderBy('id','desc')->get();

      foreach($ll as $dd)
      {
        User::where('id', $user->id)->update(['last_access' => $dd->login]);
      }

      return redirect('/inicio');
    }

    public function invcaptcha()
    {
        $user = Auth::user();
        $telefono = User::where('id', $user->id)->first()->telefono;


        return view('/invcaptcha', [
          'telefono' => $telefono
        ]);
    }

    public function inicio()
    {
      $pageConfigs = [
        'mainLayoutType' => 'vertical',
          'pageHeader' => false,
          'pageName' => 'Inicio'
      ];


      $json = file_get_contents(storage_path('products-export.json'));
      $objs = json_decode($json,true);
      return view('/home', [
          'pageConfigs' => $pageConfigs,
          'products' => $objs['products']

      ]);
    }



    public function __construct()
    {
      $this->middleware('auth');
      $this->middleware('checkStatus');
      }


    public function GetApp()
    {
      /*$result = DB::select('select * from drv_data
              LEFT JOIN tra_warehouse on warehouse=warehouseid
              where warehouse_name in('$warehouse') and appointment::date >= '{$fdate}'::date and appointment::date <= '{$sdate}'::date');*/

      $fdate = '2019-01-01';
      $sdate = '2020-03-17';
      $result = DB::select("select * from drv_data
                LEFT JOIN tra_warehouse on warehouse=warehouseid
                where appointment::date >= '$fdate' ");


    return datatables()->of($result)
    ->addColumn('Type', function($query) {
      if($query->type == 0)
      {
        $type = '<button type="button" class="btn btn-flat-success mr-1 mb-1">Delivery</button>';
      }elseif($query->type == 1)
      {
        $type = '<button type="button" class="btn btn-flat-warning mr-1 mb-1">Pick Up</button>';
      }elseif($query->type == 2)
      {
        $type = '<button type="button" class="btn btn-flat-primary mr-1 mb-1">Down Time</button>';
      }
            return $type;
    })

    ->rawColumns(['Type'])
    ->toJson();

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
        //
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
