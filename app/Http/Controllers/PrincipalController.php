<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Cliente;


class PrincipalController extends Controller
{

   //nos permite mostrar la entrada principal del sistema
    public function index(){
    $fecha=Carbon::now();
    

    return view('principal',["fecha"=>$fecha]);
  }
}
