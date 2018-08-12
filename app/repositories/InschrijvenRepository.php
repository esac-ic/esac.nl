<?php
/**
 * Created by PhpStorm.
 * User: IA02UI
 * Date: 15-6-2017
 * Time: 20:39
 */

namespace App\repositories;


use App\AgendaItem;
use App\ApplicationResponse;
use App\ApplicationResponseRow;
use Maatwebsite\Excel\Facades\Excel;

class InschrijvenRepository implements IRepository
{
    public function create(array $data)
    {

    }

    public function update($id, array $data)
    {
        // TODO: Implement update() method.
    }

    public function store($agendaitem, $request,$userid ) {
        $inschrijving = $request->all();
        $applicationResponse = new ApplicationResponse();

        $applicationFormId = $agendaitem->application_form_id;

        $applicationResponse->agenda_id = $agendaitem->id;
        $applicationResponse->inschrijf_form_id = $applicationFormId;
        $applicationResponse->user_id = $userid;
        $applicationResponse->save();
        $applicationResponseId = $applicationResponse->id;

        //set the responserows
        unset($inschrijving['_token']);
        unset($inschrijving['user']);
        foreach($inschrijving as $key => $value) {
            $applicationResponseRow = new ApplicationResponseRow();
            $applicationResponseRow->application_response_id = $applicationResponseId;
            $applicationResponseRow->application_form_row_id = $key;
            $applicationResponseRow->value = $value;
            $applicationResponseRow->save();
        }
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

    /**
     * gets the users that signed up for an agendaitem in the format:
     *        $userdata = array(
            "agendaitem" => $agendaitem->agendaItemTitle->text(),
            "userdata" => array(),
            "agendaId" => $agendaId,
            "customfields" => array()
            );
     * @param $agendaId the id of the agenda item
     * @return array array with data, see above
     */
    public function getUsers($agendaId) {
        $agendaitem = AgendaItem::find($agendaId);

//        TODO: add certificates

//        userdata is an array with 3 items, the agenda item titel, the indexes and the double array with data
        $userdata = array(
            "agendaitem" => $agendaitem->agendaItemTitle->text(),
            "userdata" => array(),
            "agendaId" => $agendaId,
            "customfields" => array()
        );
        $application_form = $agendaitem->getApplicationForm;
        $application_rows = $application_form->getActiveApplicationFormRows;

        foreach ($application_rows as $application_row) {
            array_push($userdata["customfields"],$application_row->applicationFormRowName->text());
        }

        $applicationResponses = $agendaitem->getApplicationFormResponses;
        foreach ($applicationResponses as $index=>$response) {
            $user = $response->getApplicationResponseUser;
            $applicationResponseRows = $response->getApplicationFormResponseRows;
            foreach ($applicationResponseRows as $responseRow) {
                $columnname = $responseRow->getApplicationFormResponseRowName->applicationFormRowName->text();
                $user[$columnname] = $responseRow->value;
            }
//            Adding the signup id in the fields
            $user["_signupId"] = $response->id;
            $certificates = "";
            $user['certificate_names'] = $user->getCertificationsAbbreviations();
            array_push($userdata["userdata"],$user);

        }
        return $userdata;
    }
    public function exportUsers($Agenda_id){
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

        // Generate and return the spreadsheet
        Excel::create($users["agendaitem"], function($excel) use ($activeUsers){

            // Build the spreadsheet, passing in the payments array
            $excel->sheet(trans('forms.inschrijvingen'), function($sheet) use ($activeUsers) {

                $sheet->fromArray($activeUsers);
            });

        })->download('xls');
    }


    public function getRegisterdusers($agendaId){
        return ApplicationResponse::where('agenda_id','=',$agendaId)->select('user_id')->get();
    }

    public function getApplicationInformation($agendaId, $userId){
        $applicationResponse = ApplicationResponse::where('agenda_id','=',$agendaId)->where('user_id','=',$userId)->select('id')->get();
        return ApplicationResponseRow::where('application_response_id','=',$applicationResponse[0]->id)->get();
    }
}