<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\CategoriaFormRequest;
use DB;

use App\Repositories\Categoria\RositorioCategoria;

class CategoriaController extends Controller
{
    protected  $RepCategoria;
    public function __construct(RositorioCategoria $RepCategoria)
    {
        $this->RepCategoria=$RepCategoria;
      $this->middleware('auth');
    }

    public  function index(){

       $Categoria=$this->RepCategoria->getAll();
       $Categoria=Categoria::paginate(10);

       return view('almacen.categoria.index',["categorias"=>$Categoria]);
    }
    public function create(){
        return view("almacen.categoria.create");
    }

    public function store(CategoriaFormRequest $request){

        $categoria=new Categoria;
        $categoria->nombre=$request->get('nombre');
        $categoria->descripcion=$request->get('descripcion');
        $categoria->condicion='1';
        $categoria->save();

        return Redirect::to('almacen/categoria');

    }

     //funcion que nos permite crear una nueva categoria  ingresando los datos extraidos de la vista
    public function show($id)
    {
        return view("almacen.categoria.show",["categoria"=>Categoria::findOrFail($id)]);
    }


      //funcion que nos permite redireccionar a la vista editar  y tiene como parametro el id de la categoria a modificar
    public function edit($id){

        return view("almacen.categoria.edit",["categoria"=>Categoria::findOrFail($id)]);

    }

     //funcion que nos permite  actualizar los los datos modificados de  una categoria-- los datos se extraer de la vista edit
    public function update(CategoriaFormRequest $request,$id){
        $categoria= Categoria::findOrFail($id);
        $categoria->nombre=$request->get('nombre');
        $categoria->descripcion=$request->get('descripcion');
        $categoria->update();

        return Redirect::to('almacen/categoria');

    }


    //funcion que nos permite eliminar una categoria el id se extrae del modal delete
    public function destroy($id){

        $categoria=Categoria::findOrFail($id);
        $categoria->condicion='0';
        $categoria->update();

        return Redirect::to('almacen/categoria');

    }
}
