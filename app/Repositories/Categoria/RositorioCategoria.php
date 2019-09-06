<?php


namespace App\Repositories\Categoria;


 use App\Categoria;
 use App\Repositories\BaseRepository;

 class  RositorioCategoria extends  BaseRepository
{


     public function GetModel()
     {
         return new Categoria();
     }
 }
