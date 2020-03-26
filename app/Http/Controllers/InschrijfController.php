<?php

namespace App\Http\Controllers;

use App\AgendaItem;
use App\Exports\AgendaRegistrationExport;
use App\Models\ApplicationForm\ApplicationResponse;
use App\repositories\InschrijvenRepository;
use App\User;
use App\Notifications\AgendaSubscribed;
use Illuminate\Http\Request;
use App\CustomClasses\MenuSingleton;
use App\repositories\RepositorieFactory;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class InschrijfController extends Controller
{

/*
 * todo show store application information
 */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $_menu;
    private $_MenuItemRepository;
    private $_ApplicationResponse;
    private $_InschrijvenRepository;
    private $_userRepository;

    public function __construct(MenuSingleton $menuSingleton, RepositorieFactory $repositorieFactory)
    {
        $this->middleware('auth');
        $this->middleware('authorize:'.\Config::get('constants.Content_administrator') .',' . \Config::get('constants.Activity_administrator'))
            ->except([
                'showPersonalRegistrationForm',
                'savePersonalRegistrationForm',
                'destroy',
                'showApplicationFormInformation',
            ]);

        $this->_menu = $menuSingleton;
        $this->_MenuItemRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$MENUREPOKEY);
        //$this->_ApplicationResponse = new ApplicationResponse();
        $this->_InschrijvenRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$INSCHRIJVENREPOKEY);
        $this->_userRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$USERREPOKEY);
    }



    public function exportUsers(int $agendaId, InschrijvenRepository $inschrijvenRepository){
        $agendaItem = AgendaItem::findOrFail($agendaId);
        return Excel::download(
            new AgendaRegistrationExport($inschrijvenRepository, $agendaItem),
            preg_replace('/[^a-zA-Z0-9]+/', '-', $agendaItem->agendaItemTitle->text()) . '.xlsx'
        );

    }

}
