<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CompanyRepository;

class CompanyController extends Controller
{
    protected $company_repository;

    public function __construct(CompanyRepository $company_repository)
    {
        $this->company_repository = $company_repository;
    }

    public function about(){
        $company = $this->company_repository->getCompanyDetail('2');
        if (is_null($company)) {
            abort(404);
        }
        return view('company.about', ['company' => $company]);
    }
    public function recruit(){
        $company = $this->company_repository->getCompanyDetail('4');
        if (is_null($company)) {
            abort(404);
        }
        return view('company.recruit', ['company' => $company]);
    }
    public function privacy(){
        $company = $this->company_repository->getCompanyDetail('3');
        if (is_null($company)) {
            abort(404);
        }
        return view('company.privacy', ['company' => $company]);
    }
}
