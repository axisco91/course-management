<?php

namespace App\Http\Controllers;
use App\Exports\CompaniesExport;
use App\Imports\CompaniesImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class CompanyController extends Controller
{
    public function index() {

        return view('companies.index');
    }

    public function edit($id){
        return view('companies.update', compact('id'));
    }

    public function create(){
        return view('companies.create');
    }

    public function view($id){
        return view('companies.view', compact('id'));
    }

    public function export(){
        return (new CompaniesExport())->download('empresas.xlsx');
    }

    public function editPotential($id){
        return view('companies.update-potential', compact('id'));
    }

    public function createPotential(){
        return view('companies.create-potential');
    }

    public function viewPotential($id){
        return view('companies.view-potential', compact('id'));
    }

    public function import(){
        Excel::import(new CompaniesImport, storage_path('companies.xlsx'));
    }

    public function observations($id){
        return view('companies.observations', compact('id'));
    }
}
