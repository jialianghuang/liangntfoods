<?php

namespace inventory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PricesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('prices');
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
        $validatedData = $request->validate([
            'itemno' => 'alpha_num',
        ]);
        return view('prices');
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

    public function factor(Request $request)
    {
        $input=$request->all();
        $id=$request->input('id');
        $FACTOR1=floatval($request->input('FACTOR1'));
        $FACTOR2=floatval($request->input('FACTOR2'));
        $FACTOR3=floatval($request->input('FACTOR3'));
        $FACTOR4=floatval($request->input('FACTOR4'));
        $FACTOR5=floatval($request->input('FACTOR5'));
        DB::table('pdfactor')->where('PROD_CD',$id)->update(['FACTOR1'=>$FACTOR1,'FACTOR2'=>$FACTOR2,'FACTOR3'=>$FACTOR3,'FACTOR4'=>$FACTOR4,'FACTOR5'=>$FACTOR5]);
        return redirect('/prices');
    }
}
