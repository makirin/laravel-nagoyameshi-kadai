<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index(Company $company)
    {
        $id = 1;
        $company = Company::find($id);

        return view('company.index', compact('company'));
    }
}
