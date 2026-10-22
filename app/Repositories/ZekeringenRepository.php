<?php
/**
 * Created by PhpStorm.
 * User: IA02UI
 * Date: 15-6-2017
 * Time: 20:39
 */

namespace App\Repositories;


use App\Models\Zekering;
use Illuminate\Support\Facades\Auth;

class ZekeringenRepository implements IRepository
{

    public function create(array $data): void
    {
        $zekering = new Zekering();
        $zekering->text = $data["text"];
        $zekering->created_by = Auth::user()->id;
        $zekering->score = 1;
    
        if (array_key_exists('parent', $data)) {
            $zekering->parent_id = $data["parent"];
            $zekering->has_parent = True;
        } else {
            $zekering->has_parent = False;
        }
    
        $zekering->save();
    }

    public function update($id, array $data): void
    {
        // TODO: Implement update() method.
    }

    public function delete($id): void
    {
        Zekering::destroy($id);
    }

    public function find($id, $columns = ['*']): void
    {
        // TODO: Implement find() method.
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        return Zekering::where($field, '=',$value)->orderBy('id', 'desc');
    }

    public function all($columns = ['*'])
    {
        return Zekering::query()->orderBy('parent_id', 'desc')->get();
    }
}