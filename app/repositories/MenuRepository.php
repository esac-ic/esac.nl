<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 19-2-2017
 * Time: 19:48
 */

namespace App\repositories;


use App\MenuItem;

class MenuRepository implements IRepository
{

    private $_textRepository;

    public function __construct(TextRepository $textRepository)
    {
        $this->_textRepository = $textRepository;
    }

    public function create(array $data)
    {
        $name = $this->_textRepository->create($data);
        $content = $this->_textRepository->create(['NL_text' => $data['content_nl'], 'EN_text' => $data['content_en']]);
        $menuItem = new MenuItem();
        $menuItem->name= $name->id;
        $menuItem->content_id= $content->id;
        if($data['itemType'] === "subItem"){
            $menuItem->parent_id = $data['parentItem'];
        }
        $menuItem->urlName = $data['urlName'];
        $menuItem->after = $data['afterItem'] == -1 ? null : $data['afterItem'];
        $menuItem->login = array_key_exists("login", $data);
        $menuItem->menuItem = array_key_exists("menuItem", $data);
        //can only be set in the database
        $menuItem->deletable = true;
        $menuItem->editable = true;
        $menuItem->save();
    }

    public function update($id, array $data)
    {
        $menuItem = $this->find($id);
        $this->_textRepository->update($menuItem->name,$data);
        $this->_textRepository->update($menuItem->content_id,['NL_text' => $data['content_nl'], 'EN_text' => $data['content_en']]);

        if($data['itemType'] === "subItem"){
            $menuItem->parent_id = $data['parentItem'];
        } else {
            $menuItem->parent_id = null;
        }
        if($menuItem->editable){
            $menuItem->urlName = $data['urlName'];
        }
        $menuItem->menuItem = array_key_exists("menuItem", $data);
        $menuItem->after = $data['afterItem'] == -1 ? null : $data['afterItem'];
        $menuItem->login = array_key_exists("login", $data);
        $menuItem->save();
    }

    public function delete($id)
    {
        $menuItem = $this->find($id);
        if($menuItem->deletable) {
            //delete refrences from the other menu items
            MenuItem::where('parent_id', $menuItem->id)
                ->update(['parent_id' => null]);
            MenuItem::where('after', $menuItem->id)
                ->update(['after' => null]);

            $menuItem->delete();
            $this->_textRepository->delete($menuItem->name);
            $this->_textRepository->delete($menuItem->content_id);
        }
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id',$id,$columns);
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return MenuItem::query()->where($field,'=',$value)->first($columns);
    }

    public function all($columns = array('*'))
    {
        return MenuItem::all($columns);
    }
}