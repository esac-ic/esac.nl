<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 14-2-2017
 * Time: 18:55
 */

namespace App\repositories;

use App\ApplicationFormRow;

class RepositorieFactory{

    private $_repositoryList = array();

    //Repository keys
    public static $USERREPOKEY = 'User';
    public static $ROLREPOKEY = "Rol";
    public static $TEXTREPOKEY = "Text";
    public static $MENUREPOKEY = "Menu";
    public static $CERTIFICATEREPOKEY = "Certificaat";
    public static $AGENDAITEMREPOKEY = "AgendaItem";
    public static $NEWSITEMREPOKEY = "NewsItem";
    public static $AGENDAITEMRECATEGORYPOKEY = "AgendaItemCategory";
    public static $APPLICATIONFORMREPOKEY = "ApplicationForm";
    public static $APPLICATIONFORMROWREPOKEY = "ApplicationFormRow";
    public static $ZEKERINGENREPOKEY = "Zekeringen";
    public static $INSCHRIJVENREPOKEY = "InschrijvenRepository";
    public static $BOOKREPOKEY = "BookRepository"; 
    public static $FEITEMREPOKEY = "FrontEndControllerRepository";
    public static $PHOTOALBUMREPOKEY = "PhotoAlbum";
    public static $PHOTOREPOKEY = "Photo";


    public function __construct() {
        $textRepository = new TextRepository();
        $applicationFormRowRepository = new ApplicationFormRowRepository($textRepository);

        $this->_repositoryList = [
            RepositorieFactory::$USERREPOKEY    => new \App\repositories\UserRepository(),
            RepositorieFactory::$ROLREPOKEY     => new RolRepository($textRepository),
            RepositorieFactory::$TEXTREPOKEY    => $textRepository,
            RepositorieFactory::$MENUREPOKEY    => new MenuRepository($textRepository),
            RepositorieFactory::$CERTIFICATEREPOKEY    => new CertificateRepository($textRepository),
            RepositorieFactory::$AGENDAITEMREPOKEY  => new AgendaItemRepository($textRepository),
            RepositorieFactory::$AGENDAITEMRECATEGORYPOKEY => new AgendaItemCategorieRepository($textRepository),
            RepositorieFactory::$APPLICATIONFORMREPOKEY  => new ApplicationFormRepository($textRepository,$applicationFormRowRepository),
            RepositorieFactory::$APPLICATIONFORMROWREPOKEY  => $applicationFormRowRepository,
            RepositorieFactory::$NEWSITEMREPOKEY  => new NewsItemRepository($textRepository),
            RepositorieFactory::$ZEKERINGENREPOKEY  => new ZekeringenRepository(),
            RepositorieFactory::$INSCHRIJVENREPOKEY => new InschrijvenRepository(),
            RepositorieFactory::$FEITEMREPOKEY => new FrontEndControllerRepository(),
            RepositorieFactory::$BOOKREPOKEY => new BookRepository($textRepository), 
            RepositorieFactory::$PHOTOALBUMREPOKEY => new PhotoAlbumRepository(),
            RepositorieFactory::$PHOTOREPOKEY => new PhotoRepository(),
        ];
    }

    public function getRepositorie($type){
        if(array_key_exists($type,$this->_repositoryList)){
            return $this->_repositoryList[$type];
        } else {
            return null;
        }

    }
}


