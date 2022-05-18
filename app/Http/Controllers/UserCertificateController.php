<?php

namespace App\Http\Controllers;

use App\Certificate;
use App\Repositories\RepositorieFactory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class UserCertificateController extends Controller
{
    private $_userRepository;
    private $_certificateRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RepositorieFactory $repositoryFactory)
    {
        $this->middleware('auth');
        $this->middleware('authorize:'.\Config::get('constants.Administrator') .',' . \Config::get('constants.Certificate_administrator'));
        $this->_userRepository = $repositoryFactory->getRepositorie(RepositorieFactory::$USERREPOKEY);
        $this->_certificateRepository = $repositoryFactory->getRepositorie(RepositorieFactory::$CERTIFICATEREPOKEY);
    }

    public function addCertificate(Request $request, User $user){
        $fields = ['title' => ('user.addCertificate'),
            'method' => 'POST',
            'url' => '/users/'. $user->id . '/addCertificate'];

        $certificates = array();

        $userCertificates = $this->_userRepository->getUserCertificates($user->id);

        foreach ( $this->_certificateRepository->all() as $cetificate){
            if(!$userCertificates->contains($cetificate)){
                $certificates[$cetificate->id] = $cetificate->certificateName->text();
            }
        }

        $userCertificate = null;

        return view('beheer.user.addCertificate', compact(["fields","certificates","userCertificate"]));
    }

    public function saveCertificate(Request $request, User $user){
        $this->validateInput($request);
        $this->_userRepository->addCertificate($user->id,$request->all());

        return redirect('/users/'. $user->id);
    }

    public function editUserCertificate(Request $request, User $user, Certificate $certificate){
        $fields = ['title' => ('user.editCertificate'),
            'method' => 'PATCH',
            'url' => '/users/'. $user->id . '/addCertificate/'. $certificate->id];

        $certificates = array();

        foreach ( $this->_certificateRepository->all() as $cetificate){
            $certificates[$cetificate->id] = $cetificate->certificateName->text();
        }

        $userCertificate = $user->certificates()->find($certificate->id);

        return view('beheer.user.addCertificate', compact(["fields","certificates","userCertificate"]));
    }

    public function updateUserCertificate(Request $request, User $user, Certificate $certificate){
        $this->validateInput($request);
        $user->certificates()->detach($certificate->id);
        $this->_userRepository->addCertificate($user->id,$request->all());
        return redirect('/users/'. $user->id);
    }

    public function deleteUserCertificate(Request $request, User $user, Certificate $certificate){
        $user->certificates()->detach($certificate->id);

        return redirect('/users/'. $user->id);
    }

    private function validateInput(Request $request){
        $this->validate($request,[
            'certificate_id' => 'required|numeric|min:0',
            'startDate' => 'required|date',
        ]);
    }
}
