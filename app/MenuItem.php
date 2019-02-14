<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class MenuItem extends Model
{
    use SoftDeletes;

    const HOMEURL = 'home';
    const ZEKERINGURL = 'zekeringen';
    const SUBSCRIBEURL = 'lidworden';
    const NEWSURL = 'nieuws';
    const MEMBERLISTURL = 'ledenlijst';
    const AGENDAURL = 'agenda';
    const LIBRARYURL = 'bibliotheek';
    const PHOTOURL = 'photoalbums';

    protected $fillable = [
        'name',
        'parent_id',
        'urlName',
        'after',
        'menuItem',
        'deletable',
        'editable',
        'content_id',
    ];

    public function text(){
        return $this->hasOne('App\Text', 'id', 'name');
    }

    public function content(){
        return $this->hasOne(Text::class, 'id', 'content_id');
    }

    public function partner(){
        return $this->hasOne('App\MenuItem','id', 'parent_id');
    }

    public function afterItem(){
        return $this->hasOne('App\MenuItem','id', 'after');
    }

    //return the menu items wich aren't sub menus
    public function getMenuItems(){
        return MenuItem::query()->where('parent_id', '=', null)->where('menuItem','=',true)->get();
    }

    public function getSubMenuItems($id){
        return MenuItem::query()->where('parent_id', '=', $id)->get();
    }

    public function getPageName(){
        return 'pages.' . $this->urlName . ((App::isLocale('en') === true) ? '_en' : '_nl');
    }

    public function show(){
        if($this->login){
            if(Auth::guest()){
                return false;
            }
        }

        return true;
    }


    public function sortMeunuList($menuList){
        $newList = array();
        //get a menu item which is not after one
        $firstItem = $menuList->where('after','=',null)->first();
        if($firstItem == null){
            if(count($menuList) == 0){
                return $newList;
            }
            //there aren't any menu items witch don't have a menu after so we take the first item from te menu item
            $firstItem = $menuList[0];
        }

        array_push($newList, $firstItem);

        for($i =0; $i < count($newList); $i++ ){
            $newItems = $menuList->where('after','=',$newList[$i]->id);

            //check if there are new menu item which are after the previos menu item.
            if(count($newItems) === 0){
                $newItems = $menuList->where('after','=',null);
            }

            foreach($newItems as  $item){
                if(!in_array($item,$newList)){
                    array_push($newList, $item);
                }
            }
        }

        return $newList;
    }
}
