<?php
/**
 * Created by PhpStorm.
 * User: Niekh
 * Date: 14-2-2017
 * Time: 18:55
 */

namespace App\Repositories;

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
    public static $ZEKERINGENREPOKEY = "Zekeringen";
    public static $BOOKREPOKEY = "BookRepository";
    public static $PHOTOALBUMREPOKEY = "PhotoAlbum";
    public static $PHOTOREPOKEY = "Photo";
    public static $INTROPACKAGEREPOKEY = "IntroPackage";


    public function __construct() {
        $textRepository = new TextRepository();

        $this->_repositoryList = [
            RepositorieFactory::$USERREPOKEY    => new \App\Repositories\UserRepository(),
            RepositorieFactory::$ROLREPOKEY     => new RolRepository($textRepository),
            RepositorieFactory::$TEXTREPOKEY    => $textRepository,
            RepositorieFactory::$MENUREPOKEY    => new MenuRepository($textRepository),
            RepositorieFactory::$CERTIFICATEREPOKEY    => new CertificateRepository($textRepository),
            RepositorieFactory::$AGENDAITEMREPOKEY  => new AgendaItemRepository($textRepository),
            RepositorieFactory::$AGENDAITEMRECATEGORYPOKEY => new AgendaItemCategorieRepository($textRepository),
            RepositorieFactory::$NEWSITEMREPOKEY  => new NewsItemRepository($textRepository),
            RepositorieFactory::$ZEKERINGENREPOKEY  => new ZekeringenRepository(),
            RepositorieFactory::$BOOKREPOKEY => new BookRepository($textRepository), 
            RepositorieFactory::$PHOTOALBUMREPOKEY => new PhotoAlbumRepository(),
            RepositorieFactory::$PHOTOREPOKEY => new PhotoRepository(),
            RepositorieFactory::$INTROPACKAGEREPOKEY => new IntroPackageRepository($textRepository),
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


