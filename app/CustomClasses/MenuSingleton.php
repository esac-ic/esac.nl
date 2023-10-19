<?php

namespace App\CustomClasses;

use App\MenuItem;

class MenuSingleton
{
    private $_menuItems; //a list of the menu items wich aren't sub menu items.
    private $_subMenuItems = array(); // containts the submenu items from a menu item
    const MENUSINGLETON = "MenuSingleton";

    public function __construct()
    {
        $menu = new MenuItem();
        $this->_menuItems = $menu->sortMeunuList($menu->getMenuItems());
        foreach ($this->_menuItems as $menuItem) {
            $this->_subMenuItems[$menuItem->id] = $menu->sortMeunuList($menu->getSubMenuItems($menuItem->id));
        }
    }

    public function getMenuItems()
    {
        return $this->_menuItems;
    }

    public function getSubMenuItem($menuId)
    {
        if (array_key_exists($menuId, $this->_subMenuItems)) {
            return $this->_subMenuItems[$menuId];
        } else {
            return array();
        }
    }
}
