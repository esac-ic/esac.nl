<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 17-2-2017
 * Time: 17:42
 */

namespace App\Repositories;

use App\Text;

class TextRepository implements IRepository
{

    public function create(array $data)
    {
        $text = new Text($data);
        $text->save();

        return $text;
    }

    public function update($id, array $data)
    {
        $text = $this->find($id);
        $text->update($data);
        $text->save();
        return $text;
    }

    public function delete($id)
    {
        $this->find($id)->delete();
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return Text::where($field, '=', $value)->get($columns);
    }

    public function all($columns = array('*'))
    {
        return Text::all($columns);
    }
}
