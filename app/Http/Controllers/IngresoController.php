<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\IngresoFormRequest;
use App\Ingreso;
use App\Configuracion;
use App\DetalleIngreso;
use DB;

use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class IngresoController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }


    //funcion que nos permite extraer todos los datos de la tabla ingresos y posteriormente ser enviados a la vista
    public function index(Request $request)
    {
        if($request)
        {
            //valores que se extraen para ordenar las compras
             $valor=Configuracion::findOrFail(1);
             $val=$valor->orden;
             if($val==1){
               $orden="fecha_hora";
             }
             else{
             $orden="total";
              }

            $query=trim($request->get('searchText'));
            $ingresos=DB::table('ingreso as i')
            ->join('proveedores as p','i.idproveedor','=','p.idproveedor')
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
            ->select('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))
            ->where('i.num_comprobante','LIKE','%'.$query.'%')
            ->orderBy($orden,'desc')
            ->groupBy('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
            ->paginate(7);
            //enviado datos a la vista index enviando los ingresos extraidos de la base de datos 
            return view('compra.ingreso.index',["ingresos"=>$ingresos,"searchText"=>$query]); 
        }
    }

     //funcion que nos permite redireccionar a la vista para crear una nueva compra
    public function create()
    {
        $proveedores=DB::table('proveedores')->get();
        $articulos=DB::table('articulo as art')
            ->select(DB::raw('CONCAT(art.codigo," ",art.nombre) as articulo'), 'art.idarticulo')
            ->where('art.estado','=','Activo')
            ->get();
        return view("compra.ingreso.create",["proveedores"=>$proveedores,"articulos"=>$articulos]);
    }

    //funcion que permite crear una nueva compra los datos son extraidos de la vista  create
     public function store (IngresoFormRequest $request)
    {
       try {
            DB::beginTransaction();

            //permite llenar datos a la tabla ingreso
            $ingreso = new Ingreso;

            $ingreso->idproveedor=$request->get('idproveedor');
            $ingreso->tipo_comprobante=$request->get('tipo_comprobante');
            $ingreso->serie_comprobante=$request->get('serie_comprobante');
            $ingreso->num_comprobante=$request->get('num_comprobante');
          

            $mytime = Carbon::now('America/Lima');
            $ingreso->fecha_hora=$mytime->toDateTimeString();
            $ingreso->impuesto = '18';
            $ingreso->estado = 'A';
            $ingreso->save();

            $idarticulo = $request->get('idarticulo');
            $cantidad = $request->get('cantidad');
            $precio_compra = $request->get('precio_compra');
            $precio_venta = $request->get('precio_venta');

            $cont = 0;
            //bucle que nos permite a registrar los detalles de ventas
            while ($cont<count($idarticulo)) {
                $detalle=new DetalleIngreso();
                $detalle->idingreso=$ingreso->idingreso;
                $detalle->idarticulo=$idarticulo[$cont];
                $detalle->cantidad=$cantidad[$cont];
                $detalle->precio_compra=$precio_compra[$cont];
                $detalle->precio_venta=$precio_venta[$cont];
                $detalle->save();
                $cont=$cont+1;
            }

            DB::commit();
        } catch (Exception $e) 
        {
            DB::rollback();
        }

        return Redirect::to('compra/ingreso');
    }

    //funcion que nos permite ver el detalle de las compras
    public function show($id)
    {        $ingreso=DB::table('ingreso as i')
            ->join('proveedores as p','i.idproveedor','=','p.idproveedor')
            ->join('detalle_ingreso as di','i.idingreso','=','di.idingreso')
            ->select('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado',DB::raw('sum(di.cantidad*precio_compra) as total'))
            ->groupBy('i.idingreso','i.fecha_hora','p.nombre','i.tipo_comprobante','i.serie_comprobante','i.num_comprobante','i.impuesto','i.estado')
            ->where('i.idingreso','=',$id)
            ->first(); // Arriba ya se utilizo group by, acá utilizar first para traer únicamente el primero.

        $detalles=DB::table('detalle_ingreso as d')
            ->join('articulo as a','d.idarticulo','=','a.idarticulo')
            ->select('a.nombre as articulo','d.cantidad','d.precio_compra','d.precio_venta')
            ->where('d.idingreso','=',$id)
            ->get();
        return view("compra.ingreso.show",["ingreso"=>$ingreso,"detalles"=>$detalles]);
    }

    //funcion que nos permite eliminar una compra
    public function destroy($id)
    {
        $ingreso=Ingreso::findOrFail($id);
        $ingreso->Estado='C';
        $ingreso->update();
        return Redirect::to('compra/ingreso');
    }

 }


