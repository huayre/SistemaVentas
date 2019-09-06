<?php


namespace App\Repositories;


abstract class  BaseRepository
{
    abstract public function GetModel();

    public function find($id)
    {
        return $this->GetModel()->find($id);
    }

    public function getAll()
    {
        return $this->GetModel()->all();
    }

    public function Create($data)
    {
        return $this->GetModel()->create($data);
    }

    public function update($object, $data)
    {
        $object->fill($data);
        $object->save();

        return $object;
    }

    public function delete($object)
    {
        $object->delete();
    }

}

