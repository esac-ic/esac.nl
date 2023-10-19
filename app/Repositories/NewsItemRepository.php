<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 3-5-2017
 * Time: 16:30
 */

namespace App\Repositories;

use App\NewsItem;

class NewsItemRepository implements IRepository
{
    public function create(array $data)
    {
        $newsItem = NewsItem::create($data);
        return $newsItem;
    }

    public function update($id, array $data)
    {
        $newsItem = $this->find($id);
        $newsItem->update($data);

        return $newsItem;
    }

    public function delete($id)
    {
        $newsItem = $this->find($id);
        $newsItem->delete();
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return NewsItem::where($field, '=', $value)->get($columns);
    }

    public function all($columns = array('*'))
    {
        return NewsItem::all($columns);
    }

    public function getLastXNewsItems($limit)
    {
        return NewsItem::orderBy('id', 'desc')->take($limit)->get();
    }
}
