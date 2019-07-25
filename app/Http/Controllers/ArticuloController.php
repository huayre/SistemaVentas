<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\ArticuloFormRequest;
use App\Articulo;
use DB;


class ArticuloController extends Controller
{
    public function __construct()
    {
      //permite que los usuarios esten logeados
       $this->middleware('auth');
        
    }

    //funcion que nos permite extraer los datos de los articulos y enviar los datos a la vista
    public function index(Request $request)
    {
        if($request)
        {
            $query=trim($request->get('searchText'));//campo que queremos filtrar
            $articulos=DB::table('articulo as a')
            ->join('categoria as c','a.idcategoria','=','c.idcategoria')
            ->select('a.idarticulo','a.nombre','a.codigo','a.stock','c.nombre as categoria','a.descripcion','a.estado')
            ->where('a.nombre','LIKE','%'.$query.'%')
            ->orwhere('a.codigo','LIKE','%'.$query.'%')
            ->orderBy('a.idarticulo','desc')
            ->paginate(7);
            return view('almacen.articulo.index',["articulos"=>$articulos,"searchText"=>$query]);
        }
    }

    //funcion que nos permite redireccionar a la vista para crear un nuevo articulo
    public function create()
    {
        $categorias=DB::table('categoria')->where('condicion','=','1')->get();
        return view("almacen.articulo.create",["categorias"=>$categorias]);
    }

    //funcion que nos permite crear un nuevo articulo ingresando los datos extraidos de la vista
    public function store(ArticuloFormRequest $request)
    {
        $articulo= new Articulo;
        $articulo->idcategoria=$request->get('idcategoria');
        $articulo->codigo=$request->get('codigo');
        $articulo->nombre=$request->get('nombre');
        $articulo->stock=$request->get('stock');
        $articulo->descripcion=$request->get('descripcion');
        $articulo->estado='Activo';
        $articulo->save();
        return Redirect::to('almacen/articulo');
    }

    
    public function show($id)
    {
        return view("almacen.articulo.show",["articulo"=>Articulo::findOrFail($id)]);//Articulo tabla para traer todo del articulo
    }

    //funcion que nos permite redireccionar a la vista editar  y tiene como parametro el id del articulo  a modificar
    public function edit($id)
    {
        $articulo=Articulo::findOrFail($id);
        $categorias=DB::table('categoria')->where('condicion','=','1')->get();
        return view("almacen.articulo.edit",["articulo"=>$articulo,"categorias"=>$categorias]);
    }

    //funcion que nos permite  actualizar los los datos modificados de un articulo-- los datos se extraer de la vista edit
    public function update(ArticuloFormRequest $request,$id)
    {
        $articulo=Articulo::findOrFail($id);
        $articulo->idcategoria=$request->get('idcategoria');
        $articulo->codigo=$request->get('codigo');
        $articulo->nombre=$request->get('nombre');
        $articulo->stock=$request->get('stock');
        $articulo->descripcion=$request->get('descripcion');
        $articulo->estado='Activo';
        
        $articulo->update();
        return Redirect::to('almacen/articulo');
    }

    //funcion que nos permite eliminar un articulo el id se extrae del modal delete
    public function destroy($id)
    {
        $articulo=Articulo::findOrFail($id);
        $articulo->estado='Inactivo';
        $articulo->update();
        return Redirect::to('almacen/articulo ');
    }
}
