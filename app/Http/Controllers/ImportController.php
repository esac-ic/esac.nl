<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use phpDocumentor\Reflection\Types\Null_;

class ImportController extends Controller
{
    private $_kind_of_member_lookup = [
    "lid" => "member",
    "buitengewoon lid" => "extraordinary_member" ,
    "reunist" => "reunist",
    "erelid" => "honorary_member" ,
    "lid van verdienste" => "member_of_merit",
    "trainer" => "trainer",
    "relatie" => "relationship"
    ];
    private $_country_lookup = [
        "United Kingdom"    =>  "GB",
        "Turkije" => "TR",
        "Turkey" => "TR",
        "Tsjechië" => "CZ",
        "Spanje" => "ES",
        "Spain" => "ES",
        "Singapore" => "SG",
        "Russia" => "RU",
        "Rusland" => "RU",
        "Roemenië" => "RO",
        "Roemenie" => "RO",
        "Poland" => "PL",
        "Oostenrijk" => "AT",
        "Oman" => "OM",
        "Norway" => "NO",
        "Nederland" => "NL",
        "NL" => "NL",
        "Mexico" => "MX",
        "Luxemburg" => "LU",
        "Italy" => "IT",
        "Italië" => "IT",
        "Ireland" => "IE",
        "India" => "IN",
        "Hungary" => "HU",
        "Griekenland" => "GR",
        "Greece" => "GR",
        "Germany" => "DE",
        "Deutschland" => "DE",
        "Duitsland" => "DE",
        "België" => "BE",
        "Belgium" => "BE",
        "Frankrijk" => "FR",
        "France" => "FR",
    ];
    private $_emails = array();


    public function importUsers()
    {
        $this->importActiveUsers();
        $this->importOldMembers();
        return "user import done";
    }

    private function importActiveUsers(){
       $file_n = storage_path('actieve_members.csv');
        $file = fopen($file_n, "r");
        $counter = 1;
        while (($data = fgetcsv($file, 2000, ",")) != false) {
            if(count($data) === 1){
                $data = explode(',',$data[0]);
            }
            if ($data[37] != null && $data[37] != "" && !in_array($data[37], $this->_emails)) {
                $user = new User();
                $user->firstname = $data[1];
                $user->preposition = $data[2];
                $user->lastname = $data[3];
                $user->initials = $data[4];
                $adress = explode(' ', $data[5]); //first part is the street second housnumber
                if (count($adress) >= 2) {
                    $addressLength = count($adress);
                    $user->street = implode(" ",array_slice($adress,0,$addressLength - 1));
                    $user->houseNumber = $adress[$addressLength - 1];
                } else {
                    $user->street = "";
                    $user->houseNumber = "";
                }
                $user->zipcode = $data[6];
                $user->city = $data[7];
                $user->country = $data[8] != "" && in_array($data[8], $this->_country_lookup) ? $this->_country_lookup[$data[8]] : "NL";
                $user->phonenumber = $data[9];
                $user->phonenumber_alt = $data[10];
                $birthDay = $data[16] != "0000-00-00" & $data[16] != "NULL" ? new \DateTime($data[16]) : Carbon::now();
                $user->birthDay = Carbon::createFromFormat('d-m-Y H:i', $birthDay->format('d-m-Y') . ' ' . $birthDay->format('H:i'));;
                $user->gender = ($data[17] != 'm') ? 'female' : 'man';
                $user->email = ($data[37] != null && $data[37] != "" && !in_array($data[37], $this->_emails)) ? $data[37] : "oldMember" . $counter;
                $user->created_at = $data[19] != "0000-00-00" & $data[19] != "NULL" ? new \DateTime($data[19]) : Carbon::now();
                $user->kind_of_member = ($data[21] != "NULL" & $data[21] != "") ? $this->_kind_of_member_lookup[$data[21]] : $this->_kind_of_member_lookup['lid'];
                $user->nkbv_nr = ($data[22] != "NULL") ? $data[22] : 0;
                $user->climbingCard = $data[23] === -1 ? false : true;
                $user->travelInsurance = false;
                $user->studendNumber = $data[24];
                $user->study = $data[25];
                $user->IBAN = $data[27];
                $user->BIC = $data[28];
                $user->incasso = $data[29] === -1 ? false : true;
                $user->remark = $data[31];

                //lid af datum toevoegen als die is gezet
                if ($data[20] != "0000-00-00" && $data[20] != "NULL") {
                    $user->lid_af = $data[20];
                }

                $user->password = "";
                $emergencyAdress = explode(' ', $data[11]); //first part is the street second housnumber
                if (count($emergencyAdress) >= 2) {
                    $addressLength = count($emergencyAdress);
                    $user->emergencystreet = implode(" ",array_slice($emergencyAdress,0,$addressLength - 1));
                    $user->emergencyHouseNumber = $emergencyAdress[$addressLength - 1];
                } else {
                    $user->emergencystreet = "";
                    $user->emergencyHouseNumber = "";
                }
                $user->emergencyNumber = $data[15];
                $user->emergencycity = $data[13];
                $user->emergencyzipcode = $data[12];
                $user->emergencycountry = $data[14] != "" && in_array($data[14], $this->_country_lookup) ? $this->_country_lookup[$data[14]] : "NL";
                $user->save();
                $counter++;
                array_push($this->_emails, $data[37]);
            } else {
                if(in_array($data[37], $this->_emails)){
                    echo $data[37];
                    echo '<br>';
                }
            }
        }
        fclose($file);
    }

    public function importOldMembers(){
        $file_n = storage_path('old_members.csv');
        $file = fopen($file_n, "r");
        $counter = 1;
        while (($data = fgetcsv($file, 2000, ",")) != false) {
            if(count($data) === 1){
                $data = explode(',',$data[0]);
            }
            if ($data[20] != "0000-00-00" && $data[20] != "NULL") {
                $user = new User();
                $user->firstname = $data[1];
                $user->preposition = $data[2];
                $user->lastname = $data[3];
                $user->initials = $data[4];
                $adress = explode(' ', $data[5]); //first part is the street second housnumber
                if (count($adress) >= 2) {
                    $user->street = $adress[0];
                    $user->houseNumber = $adress[1];
                } else {
                    $user->street = "";
                    $user->houseNumber = "";
                }
                $user->zipcode = $data[6];
                $user->city = $data[7];
                $user->country = $data[8] != "" && in_array($data[8], $this->_country_lookup) ? $this->_country_lookup[$data[8]] : "NL";
                $user->phonenumber = $data[9];
                $user->phonenumber_alt = $data[10];
                $birthDay = $data[16] != "0000-00-00" & $data[16] != "NULL" ? new \DateTime($data[16]) : Carbon::now();
                $user->birthDay = Carbon::createFromFormat('d-m-Y H:i', $birthDay->format('d-m-Y') . ' ' . $birthDay->format('H:i'));;
                $user->gender = ($data[17] != 'm') ? 'female' : 'man';
                $user->email = ($data[18] != null && $data[18] != "" && !in_array($data[18], $this->_emails)) ? $data[18] : "oldMember" . $counter;
                $user->created_at = $data[19] != "0000-00-00" & $data[19] != "NULL" ? new \DateTime($data[19]) : Carbon::now();
                $user->kind_of_member = ($data[21] != "NULL" & $data[21] != "") ? $this->_kind_of_member_lookup[$data[21]] : $this->_kind_of_member_lookup['lid'];
                $user->nkbv_nr = ($data[22] != "NULL") ? $data[22] : 0;
                $user->climbingCard = $data[23] === -1 ? false : true;
                $user->travelInsurance = false;
                $user->studendNumber = $data[24];
                $user->study = $data[25];
                $user->IBAN = $data[27];
                $user->BIC = $data[28];
                $user->incasso = $data[29] === -1 ? false : true;
                $user->remark = $data[31];

                //lid af datum toevoegen als die is gezet
                if ($data[20] != "0000-00-00" && $data[20] != "NULL") {
                    $user->lid_af = $data[20];
                }

                $user->password = "";
                $emergencyAdress = explode(' ', $data[11]); //first part is the street second housnumber
                if (count($emergencyAdress) >= 2) {
                    $user->emergencystreet = $emergencyAdress[0];
                    $user->emergencyHouseNumber = $emergencyAdress[1];
                } else {
                    $user->emergencystreet = "";
                    $user->emergencyHouseNumber = "";
                }
                $user->emergencyNumber = $data[15];
                $user->emergencycity = $data[13];
                $user->emergencyzipcode = $data[12];
                $user->emergencycountry = $data[14] != "" && in_array($data[14], $this->_country_lookup) ? $this->_country_lookup[$data[14]] : "NL";
                $user->save();
                $counter++;
                array_push($this->_emails, $data[18]);
            } else {
                if(in_array($data[18], $this->_emails)){
                    echo $data[18];
                }
            }
        }
        fclose($file);
    }
}
