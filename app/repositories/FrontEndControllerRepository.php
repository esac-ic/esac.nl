<?php
/**
 * Created by PhpStorm.
 * User: Wouter Ligtenberg
 * Date: 27-8-2017
 * Time: 14:47
 */

namespace App\repositories;

use App\FrontEndReplacement;

class FrontEndControllerRepository implements IRepository
{

    /**
     * NewsItemRepository constructor.
     * @param $_textRepository
     */

    public function create(array $data)
    {

        $item = new FrontEndReplacement();
        $item->word = $data['word'];
        $item->replacement = $data['replacement'];
        $item->email = $data['email'];
        $item->save();
        return True;
    }

    public function delete($id)
    {
        FrontEndReplacement::destroy($id);
        return True;
    }


    public function all($columns = array('*'))
    {
        return NewsItem::all($columns);
    }

    public function update($id, array $data)
    {
        //
    }

    public function find($id, $columns = array('*'))
    {
        //
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        //
    }
}