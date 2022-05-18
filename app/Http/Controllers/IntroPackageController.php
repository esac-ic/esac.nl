<?php

namespace App\Http\Controllers;

use App\IntroPackage;
use App\Repositories\RepositorieFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException as ValidationExceptionAlias;

class IntroPackageController extends Controller
{
    private $_introPackageRepository;
    private $_applicationFormRepository;


    /**
     * IntroPackageController constructor.
     */
    public function __construct(RepositorieFactory $repositoryFactory)
    {
        $this->_introPackageRepository = $repositoryFactory->getRepositorie(RepositorieFactory::$INTROPACKAGEREPOKEY);
        $this->_applicationFormRepository = $repositoryFactory->getRepositorie(RepositorieFactory::$APPLICATIONFORMREPOKEY);

        $this->middleware('auth');
        $this->middleware('authorize:'
            . \Config::get('constants.Content_administrator') .','
            . \Config::get('constants.Activity_administrator')
        );
    }

    /**
     * Validate request input
     *
     * @param Request $request
     * @throws ValidationExceptionAlias
     */
    private function validateInput(Request $request){
        $this->validate($request, [
            'NL_text' => 'required',
            'EN_text' => 'required',
            'deadline' => 'required|date',
            'application_form_id' => 'required|integer',
        ]);
    }

    /**
     * Display a listing of intro packages.
     *
     * @return Response
     */
    public function index()
    {
        $packages = $this->_introPackageRepository->all();

        return view('beheer.intro.packages.index', [
            'packages' => $packages,
        ]);
    }

    /**
     * Show the form for creating a new intro package.
     *
     * @return Response
     */
    public function create()
    {
        $applicationForms = $this->_applicationFormRepository->all(array("name","id"));

        return view('beheer.intro.packages.create_edit', [
            'package' => null,
            'applicationForms' => $applicationForms,
        ]);
    }

    /**
     * Store a newly created intro package in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationExceptionAlias
     */
    public function store(Request $request)
    {
        $this->validateInput($request);
        $this->_introPackageRepository->create($request->all());

        Session::flash("message", ('intro.packageAdded'));

        return redirect()->route('beheer.intro.packages.index');
    }

    /**
     * Display the specified intro package.
     *
     * @param IntroPackage $package
     * @return Response
     */
    public function show(IntroPackage $package)
    {
        return view('beheer.intro.packages.show', [
            'package' => $package,
        ]);
    }

    /**
     * Show the form for editing the specified intro package.
     *
     * @param IntroPackage $package
     * @return Response
     */
    public function edit(IntroPackage $package)
    {
        $applicationForms = $this->_applicationFormRepository->all(array("name","id"));

        return view('beheer.intro.packages.create_edit', [
            'package' => $package,
            'applicationForms' => $applicationForms,
        ]);
    }

    /**
     * Update the specified intro package in storage.
     *
     * @param Request $request
     * @param IntroPackage $package
     * @return Response
     * @throws ValidationExceptionAlias
     */
    public function update(Request $request, IntroPackage $package)
    {
        $this->validateInput($request);
        $this->_introPackageRepository->update($package->id, $request->all());

        Session::flash("message", ('intro.packageEdited'));

        return redirect()->route('beheer.intro.packages.index');
    }

    /**
     * Remove the specified intro package from storage.
     *
     * @param IntroPackage $package
     * @return Response
     */
    public function destroy(IntroPackage $package)
    {
        $this->_introPackageRepository->delete($package->id);

        Session::flash("message", ('intro.packageDeleted'));

        return redirect()->route('beheer.intro.packages.index');
    }
}
