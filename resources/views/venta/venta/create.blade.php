@extends ('layouts.admin')
@section ('contenido')

<!--8-36 2:33-->
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <h3>Nueva Venta</h3>
        @if (count($errors)>0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
              
        <form action="{{route('venta.store')}}" method="post" autocomplete="off">
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label for="nombre">Nombre </label>
                    <!--nombre es recibido por CategoriaFormRequest en la funcion rules() 8-36 8:08 y tambien sera usado por CategoriaController en el metodo store() -->
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre.." />
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label>Categoria</label>
                        <select name="idcategoria" class="form-control"><!--con el name se valida en el ArticuloFormRequest-->
							    <!--voy a recibir todas las categorias en una variable $categorias desde el metodo create de ArticuloController-->
							    @foreach ($categorias as $cat)
								    <option value="{{$venta->idventa}}">{{$venta->nombre}}</option>
							    @endforeach
						    </select>
                </div>
            </div>

             <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label for="codigo">Código</label>
                    <input type="text" name="codigo" class="form-control" placeholder="Codigo del artículo.." />
                </div>
             </div>

             <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="text" name="stock" class="form-control" placeholder="Cantidad del articulo" />
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label for="descripcion">Descripcion</label>
                    <!--nombre es recibido por CategoriaFormRequest en la funcion rules()-->
                    <input type="text" name="descripcion" class="form-control" placeholder="Descripcion.." />
                </div>
            </div>
             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Guardar</button>
                    <input type='reset' class="btn btn-danger" value='Cancelar' onclick="location.href = '{{ route('venta.index')}}'" />
                </div>
            </div>
                <input type="hidden" name="_token" value="{{csrf_token()}}" />
           </div>

        </form>          
    
@endsection
