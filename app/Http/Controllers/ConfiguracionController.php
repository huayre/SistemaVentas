<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Configuracion;
use DB;
use Illuminate\Support\Facades\Redirect;

class ConfiguracionController extends Controller
{
   
   public function index(Request $request)
    {
      //extraer valor de la conexion
      $valor=Configuracion::findOrFail(1);
      $conexion=$valor->conexion;
      $orden=$valor->orden;
      //extraer valor del orden de compra
      
      
      return view('configuracion.inicio.index',["conexion"=>$conexion,"orden"=>$orden]);
        
    }

    //funcion que nos permite actualizar los campos de la tabla configuracion
     public function store(Request $request)
    {
      

      //actualizar configuracion de la conexion
         
           $configuracion=Configuracion::findOrFail(1);
           $configuracion->conexion=$request->get('api');
           $configuracion->orden=$request->get('ordenamiento');
           $configuracion->update();

            //redireccionamineto  al index principal de configuracion
           return Redirect::to('configuracion/inicio');

         
      
    }
}
