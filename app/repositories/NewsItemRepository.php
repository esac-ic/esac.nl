<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 3-5-2017
 * Time: 16:30
 */

namespace App\repositories;


use App\NewsItem;

class NewsItemRepository implements IRepository
{

    private $_textRepository;

    /**
     * NewsItemRepository constructor.
     * @param $_textRepository
     */
    public function __construct(TextRepository $_textRepository)
    {
        $this->_textRepository = $_textRepository;
    }

    public function create(array $data)
    {
        $title = $this->_textRepository->create(['NL_text' => $data['NL_title'], 'EN_text' => $data['EN_title']]);
        $text = $this->_textRepository->create($data);

        $newsItem = new NewsItem();
        $newsItem->title = $title->id;
        $newsItem->text = $text->id;
        $newsItem->createdBy = \Auth::user()->id;
        $newsItem->save();

        return $newsItem;
    }

    public function update($id, array $data)
    {
        $newsItem = $this->find($id);

        //update text
        $this->_textRepository->update($newsItem->title,['NL_text' => $data['NL_title'], 'EN_text' => $data['EN_title']]);
        $this->_textRepository->update($newsItem->text,$data);

        return $newsItem;
    }

    public function delete($id)
    {
        $newsItem = $this->find($id);
        $newsItem->delete();

        $this->_textRepository->delete($newsItem->title);
        $this->_textRepository->delete($newsItem->text);
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns)->first();
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return NewsItem::where($field, '=',$value)->get($columns);
    }

    public function all($columns = array('*'))
    {
        return NewsItem::all($columns);
    }

    public function getLastXNewsItems( $limit){
        return NewsItem::orderBy('id', 'desc')->take($limit)->get();
    }
}