<?php

namespace App\repositories;

use App\Models\ApplicationForm\ApplicationResponse;
use App\Models\ApplicationForm\ApplicationResponseRow;
use Illuminate\Support\Collection;

class InschrijvenRepository implements IRepository
{
    public function create(array $data)
    {

    }

    public function update($id, array $data)
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        ApplicationResponse::destroy($id);
    }

    public function find($id, $columns = array('*'))
    {
        // TODO: Implement find() method.
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        // TODO: Implement findBy() method.
    }

    public function all($columns = array('*'))
    {

    }


    public function getExportData($Agenda_id){
        $users = $this->getUsers($Agenda_id);
        $activeUsers = array();
        $selectedElements = array(
            "firstname",
            "preposition",
            "lastname",
            "street",
            "houseNumber",
            "city",
            "email",
            "phonenumber"
        );
        $selectedElements = array_merge($selectedElements,$users["customfields"]);

        foreach ($users["userdata"] as $user) {
            $userline = array();
            foreach ($selectedElements as $element) {
                $userline[$element] = $user[$element];
            }
            array_push($activeUsers, $userline);
        }

        return new Collection($activeUsers);

//        // Generate and return the spreadsheet
//        Excel::create($users["agendaitem"], function($excel) use ($activeUsers){
//
//            // Build the spreadsheet, passing in the payments array
//            $excel->sheet(trans('forms.inschrijvingen'), function($sheet) use ($activeUsers) {
//
//                $sheet->fromArray($activeUsers);
//            });
//
//        })->download('xls');
    }


    public function getRegisterdusers($agendaId){
        return ApplicationResponse::where('agenda_id','=',$agendaId)->select('user_id')->get();
    }


}