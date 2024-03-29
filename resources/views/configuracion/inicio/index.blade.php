@extends('layouts.admin')
@section('contenido')



<form action="{{route('inicio.store')}}" method="post" autocomplete="off">
    
        
     <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
         <div class="form-group">
               <label>Conexión RENIEC y SUNAT</label>
                <select name="api" class="form-control">
                  
							   
			        @if ($conexion==1)
                    <option value="0" selected>DESACTIVADO</option>
                    <option value="1" selected>ACTIVADO</option>
				   
                    @else
                     <option value="1" selected>ACTIVADO</option>
                     <option value="0" selected>DESACTIVADO</option>
                    @endif
                </select>
          </div>
         <div class="form-group">
               <label>ORDEN de COMPRAS</label>
                <select name="ordenamiento" class="form-control">

                    <!--identificador por fecha=1 y monto=0-->
			        @if ($orden==1)
                    <option value="0" selected>POR MONTO</option>
                     <option value="1" selected>POR FECHAS</option>
				    
                    @else
                    <option value="1" selected>POR FECHAS</option>
                    <option value="0" selected>POR MONTO</option>
                    @endif
                </select>
          </div>
          <button class="btn btn-primary" type="submit">Guardar</button> 
     </div>

                      
     
     <div>
        <input type="hidden" name="_token" value="{{csrf_token()}}" />
     </div>
</form>


<style>
.switchBtn {
    position: relative;
    display: inline-block;
    width: 110px;
    height: 34px;
}
.switchBtn input {display:none;}
.slide {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color:#1E8449;
    -webkit-transition: .4s;
    transition: .4s;
    padding: 8px;
    color: #fff;
}
.slide:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 78px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
}
input:checked + .slide {
    background-color: #5D6D7E  ;
    padding-left: 40px;
}
input:focus + .slide {
    box-shadow: 0 0 1px #01aeed;
}
input:checked + .slide:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
    left: -20px;
}
.slide.round {
    border-radius: 34px;
}
.slide.round:before {
    border-radius: 50%;
}
</style>

@endsection
