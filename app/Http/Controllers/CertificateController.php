<?php

namespace App\Http\Controllers;

use App\Certificate;
use App\Repositories\RepositorieFactory;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    private $_certificateRepository;

    public function __construct(RepositorieFactory $repositorieFactory)
    {
        $this->_certificateRepository = $repositorieFactory->getRepositorie(RepositorieFactory::$CERTIFICATEREPOKEY);
        $this->middleware('auth');
        $this->middleware('authorize:'.\Config::get('constants.Administrator') .',' . \Config::get('constants.Certificate_administrator'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $certificates = $this->_certificateRepository->all(array("id","name"));

        return view("beheer.certificate.index", compact("certificates"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fields = ['title' => get('certificate.add'),
            'method' => 'POST',
            'url' => '/certificates',];
        $certificate = null;
        return view("beheer.certificate.create_edit", compact('certificate','fields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateData($request);

        $this->_certificateRepository->create($request->all());

        \Session::flash("message", get('certificate.added'));
        return redirect('/certificates');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Certificate $certificate)
    {
        return view('beheer.certificate.show', compact('certificate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Certificate $certificate)
    {
        $fields = ['title' => get('certificate.add'),
            'method' => 'PATCH',
            'url' => '/certificates/' . $certificate->id,];

        return view("beheer.certificate.create_edit", compact('certificate','fields'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Certificate $certificate)
    {
        $this->validateData($request);

        $this->_certificateRepository->update($certificate->id, $request->all());

        \Session::flash("message", get('certificate.edited'));
        return redirect('/certificates');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Certificate $certificate)
    {
        $this->_certificateRepository->delete($certificate->id);

        \Session::flash("message", get('certificate.deleted'));
        return redirect('/certificates');
    }

    private function validateData(Request $reqeust){
        $this->validate($reqeust,[
            'duration' => 'numeric|min:0',
            'EN_text' => 'required',
            'NL_text' => 'required',
            'abbreviation' => 'required',
        ]);
    }
}
