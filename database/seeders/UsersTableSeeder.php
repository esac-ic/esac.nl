<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
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
        $user = new User();
        $user->email = "test@esac.nl";
        $user->password = bcrypt("test");
        $user->firstname = "Piet";
        $user->preposition = "van";
        $user->lastname = "Jansen";
        $user->street = "Kerkstraat";
        $user->house_number = 34;
        $user->city = "test";
        $user->zipcode = "5301jh";
        $user->country = "NL";
        $user->phone_number = "123456789";
        $user->phone_number_alt = "987654321";
        $user->emergency_number = "147258369";
        $user->emergency_house_number = "19";
        $user->emergency_street = "Kerk straat";
        $user->emergency_city = "Eindhoven";
        $user->emergency_zipcode = "3633IK";
        $user->emergency_country = "NL";
        $user->birthday = Carbon::now()->subYears(20);
        $user->kind_of_member = "member";
        $user->iban = "NL55 RABO 0107331020";
        $user->bic = "";
        $user->incasso = false;
        $user->remark = "Ik ben een test gebruiker";
        $user->save();

        //add rol
        $user->roles()->attach(['1', '2', '3']); //id for Administrator
        $user->save();

        //inserting test data
        $user = new User();
        $user->email = "member@esac.nl";
        $user->password = bcrypt("test");
        $user->firstname = "Piet1";
        $user->preposition = "van";
        $user->lastname = "Jansen";
        $user->street = "Kerkstraat";
        $user->house_number = 34;
        $user->city = "test";
        $user->zipcode = "5301jh";
        $user->country = "Nederland";
        $user->phone_number = "123456789";
        $user->phone_number_alt = "987654321";
        $user->emergency_number = "147258369";
        $user->emergency_house_number = "19";
        $user->emergency_street = "Kerk straat";
        $user->emergency_city = "Eindhoven";
        $user->emergency_zipcode = "3633IK";
        $user->emergency_country = "NL";
        $user->birthday = Carbon::now()->subYears(20);
        $user->kind_of_member = "member";
        $user->iban = "NL55 RABO 0107331020";
        $user->bic = "";
        $user->incasso = false;
        $user->remark = "Ik ben een test gebruiker";
        $user->save();

        //inserting test data
        $user = new User();
        $user->email = "pending@esac.nl";
        $user->password = bcrypt("test");
        $user->firstname = "Piet1";
        $user->preposition = "van";
        $user->lastname = "Jansen";
        $user->street = "Kerkstraat";
        $user->house_number = 34;
        $user->city = "test";
        $user->zipcode = "5301jh";
        $user->country = "Nederland";
        $user->phone_number = "123456789";
        $user->phone_number_alt = "987654321";
        $user->emergency_number = "147258369";
        $user->emergency_house_number = "19";
        $user->emergency_street = "Kerk straat";
        $user->emergency_city = "Eindhoven";
        $user->emergency_zipcode = "3633IK";
        $user->emergency_country = "NL";
        $user->birthday = Carbon::now()->subYears(20);
        $user->kind_of_member = "member";
        $user->iban = "NL55 RABO 0107331020";
        $user->bic = "";
        $user->incasso = false;
        $user->remark = "Ik ben een test gebruiker";
        $user->pending_user = Carbon::now();
        $user->save();
        
        //random fake users
        User::factory()->count(10)->create();
        User::factory()->member()->count(10)->create();
        User::factory()->reunist()->count(10)->create();
        User::factory()->lidAf()->count(10)->create();
        User::factory()->pending()->count(10)->create();
    }
}
