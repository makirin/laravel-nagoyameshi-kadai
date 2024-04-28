<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index()
    {
        $id = 1;
        $company = Company::find($id);

        return view('admin.company.index', compact('company'));
    }

    public function edit(Company $company)
    {
        $id = 1;
        $company = Company::find($id);   
        return view('admin.company.edit', compact('company'));
    }

    public function update(Request $request, Company $company){
        $request->validate([
            'name' => 'required',
            'postal_code' => 'required|integer|digits:7',
            'address' => 'required',
            'representative' => 'required',
            'establishment_date' => 'required',
            'capital' => 'required',
            'business' => 'required',
            'number_of_employees' => 'required',
        ]);

        $company = Company::find(1); 
        $company->update(['name' => $request->input('name')]);
        $company->update(['postal_code' => $request->input('postal_code')]);
        $company->update(['address' => $request->input('address')]);
        $company->update(['representative' => $request->input('representative')]);
        $company->update(['establishment_date' => $request->input('establishment_date')]);
        $company->update(['capital' => $request->input('capital')]);
        $company->update(['business' => $request->input('business')]);
        $company->update(['number_of_employees' => $request->input('number_of_employees')]);

        return redirect()->route('admin.company.update', compact('company'))->with('flash_message', '会社概要を編集しました。');
    }    
}
