<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AccessPoints;
use Validator;
class AccessController extends Controller{

     //Insert data into database
     public function store(Request $request) 
     { 
         $public_path= public_path();

         $validator = Validator::make($request->all(), [ 
            'SSID' => 'required|string',
            'BSSID' => 'required|string',
            'LevelIM' => 'required|string',
            'EType' => 'required|string',
         
        
         ]);

         $data = [
           
            "SSID" => $request->SSID,
            "BSSID" => $request->BSSID,
  
            "LevelIM" => $request->LevelIM,
            "EType" => $request->EType,
        
        ];
       
        

         if ($validator->fails()) { 
             return response()->json(['status'=>false,'message'=>$validator->errors()->first() ], 401);   
                 
         }

         $accesspoints = AccessPoints::create($data);
         return response()->json(['Status'=>True, 'message'=>'Data Store successfully','response'=>$accesspoints]);

}

 	public function fetch(Request $request)
 	{
 		  $user = AccessPoints::all();
         return response()->json(['accesspoints'=>[$user]]);
 	}

}