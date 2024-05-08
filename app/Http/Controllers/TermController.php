<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TermController extends Controller
{
    public function index(Term $term)
    {
        $id = 1;
        $term = Term::find($id);

        return view('terms.index', compact('term'));
    }
}
