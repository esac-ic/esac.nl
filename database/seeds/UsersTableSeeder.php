<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //inserting test data
        $user = new \App\User();
        $user->email = "test@esac.nl";
        $user->password = bcrypt("test");
        $user->firstname = "Piet";
        $user->preposition = "van";
        $user->lastname = "Jansen";
        $user->street = "Kerkstraat";
        $user->houseNumber = 34;
        $user->city = "test";
        $user->zipcode = "5301jh";
        $user->country = "NL";
        $user->phonenumber = "123456789";
        $user->phonenumber_alt = "987654321";
        $user->emergencyNumber = "147258369";
        $user->emergencyHouseNumber = "19";
        $user->emergencystreet = "Kerk straat";
        $user->emergencycity = "Eindhoven";
        $user->emergencyzipcode = "3633IK";
        $user->emergencycountry = "NL";
        $user->birthDay = \Carbon\Carbon::now()->subYear(20);
        $user->gender = "man";
        $user->kind_of_member = "member";
        $user->IBAN = "NL55 RABO 0107331020";
        $user->BIC = "";
        $user->incasso = false;
        $user->remark = "Ik ben een test gebruiker";
        $user->save();

        //add rol
        $user->roles()->attach(['1','2','3']); //id for Administrator
        $user->save();

        //inserting test data
        $user = new \App\User();
        $user->email = "member@esac.nl";
        $user->password = bcrypt("test");
        $user->firstname = "Piet1";
        $user->preposition = "van";
        $user->lastname = "Jansen";
        $user->street = "Kerkstraat";
        $user->houseNumber = 34;
        $user->city = "test";
        $user->zipcode = "5301jh";
        $user->country = "Nederland";
        $user->phonenumber = "123456789";
        $user->phonenumber_alt = "987654321";
        $user->emergencyNumber = "147258369";
        $user->emergencyHouseNumber = "19";
        $user->emergencystreet = "Kerk straat";
        $user->emergencycity = "Eindhoven";
        $user->emergencyzipcode = "3633IK";
        $user->emergencycountry = "NL";
        $user->birthDay = \Carbon\Carbon::now()->subYear(20);
        $user->gender = "man";
        $user->kind_of_member = "member";
        $user->IBAN = "NL55 RABO 0107331020";
        $user->BIC = "";
        $user->incasso = false;
        $user->remark = "Ik ben een test gebruiker";
        $user->save();

        //inserting test data
        $user = new \App\User();
        $user->email = "pending@esac.nl";
        $user->password = bcrypt("test");
        $user->firstname = "Piet1";
        $user->preposition = "van";
        $user->lastname = "Jansen";
        $user->street = "Kerkstraat";
        $user->houseNumber = 34;
        $user->city = "test";
        $user->zipcode = "5301jh";
        $user->country = "Nederland";
        $user->phonenumber = "123456789";
        $user->phonenumber_alt = "987654321";
        $user->emergencyNumber = "147258369";
        $user->emergencyHouseNumber = "19";
        $user->emergencystreet = "Kerk straat";
        $user->emergencycity = "Eindhoven";
        $user->emergencyzipcode = "3633IK";
        $user->emergencycountry = "NL";
        $user->birthDay = \Carbon\Carbon::now()->subYear(20);
        $user->gender = "man";
        $user->kind_of_member = "member";
        $user->IBAN = "NL55 RABO 0107331020";
        $user->BIC = "";
        $user->incasso = false;
        $user->remark = "Ik ben een test gebruiker";
        $user->pending_user = Carbon\Carbon::now();
        $user->save();

        $userRegistrationInfo = new \App\Models\User\UserRegistrationInfo();
        $userRegistrationInfo->intro_package = true;
        $userRegistrationInfo->shirt_size = "s";
        $userRegistrationInfo->toprope_course = true;
        $userRegistrationInfo->intro_weekend_date = null;
        $userRegistrationInfo->user_id = $user->id;
        $userRegistrationInfo->save();
    }
}
