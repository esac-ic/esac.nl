<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MovePageContentFromFileToDb extends Migration
{
    private $_FILEPATH = "resources/views/pages/";
    private $_STARTCONTENTTEXT = "{{--Start of custom text--}}";
    private $_ENDCONTENTTEXT = "{{--End of custom text--}}";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (\App\MenuItem::all() as $menuItem){
            try {
                $content = $this->getContent($menuItem->urlName);
                $text = new \App\Text(['NL_text' => $content['content_nl'],'EN_text' => $content['content_en']]);;
                $text->save();
                $menuItem->content_id = $text->id;
                $menuItem->save();
            } catch (Exception $exception){

            }

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

    private function getContent($id){
        $contentFile_nl = file_get_contents($this->_FILEPATH . $id . '_nl.blade.php');
        $contentFile_en = file_get_contents($this->_FILEPATH . $id . '_en.blade.php');

        $data = array();
        $startpos = strpos($contentFile_nl,$this->_STARTCONTENTTEXT) + strlen($this->_STARTCONTENTTEXT);
        $length = strlen($contentFile_nl) - $startpos - (strlen($contentFile_nl) -strpos($contentFile_nl,$this->_ENDCONTENTTEXT) );
        $data['content_nl'] = substr($contentFile_nl,$startpos,$length);
        $startpos = strpos($contentFile_en,$this->_STARTCONTENTTEXT) + strlen($this->_STARTCONTENTTEXT);
        $length = strlen($contentFile_en) - $startpos - (strlen($contentFile_en) -strpos($contentFile_en,$this->_ENDCONTENTTEXT) );
        $data['content_en'] = substr($contentFile_en,$startpos,$length);

        return $data;

    }
}
