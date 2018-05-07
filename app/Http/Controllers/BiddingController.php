<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

class BiddingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $biddings = \App\Bidding::with('files')->get();
        $response = ["status" => "success", "biddings" => $biddings->toArray()];
       
        return response(json_encode($response), 200, ["Content-Type" => "application/json"]);
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


}
