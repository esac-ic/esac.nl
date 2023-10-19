<?php

namespace App\Repositories;

use App\MenuItem;

class MenuRepository implements IRepository
{
    public function create(array $data)
    {
        $menuItem = new MenuItem($data);
        if ($data['itemType'] === "subItem") {
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
        $menuItem->update($data);

        if ($data['itemType'] === "subItem") {
            $menuItem->parent_id = $data['parentItem'];
        } else {
            $menuItem->parent_id = null;
        }
        if ($menuItem->editable) {
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
        if ($menuItem->deletable) {
            //delete refrences from the other menu items
            MenuItem::where('parent_id', $menuItem->id)
                ->update(['parent_id' => null]);
            MenuItem::where('after', $menuItem->id)
                ->update(['after' => null]);

            $menuItem->delete();
        }
    }

    public function find($id, $columns = array('*'))
    {
        return $this->findBy('id', $id, $columns);
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return MenuItem::query()->where($field, '=', $value)->first($columns);
    }

    public function all($columns = array('*'))
    {
        return MenuItem::all($columns);
    }
}
