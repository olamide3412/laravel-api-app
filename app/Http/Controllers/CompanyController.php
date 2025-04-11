<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CompanyController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except:['index','show'])
        ];
    }
    public function index(Request $request)
    {
        $isPaginate = request('paginate', false);
        if($isPaginate){
            $companies = Company::with('user')->latest()->paginate(5);
        } else{
            $companies = Company::with('user')->get();
        }
        return response()->json($companies);
        //return Company::with('user')->latest()->paginate(5);
    }


    public function store(Request $request)
    {
       $validatedData =  $request->validate([
                        'name' => ['required','string'],
                        'description' => ['required'],
                        'contactEmail' => ['required','email'],
                        'contactPhone' => ['required', 'string']
                    ]);

    // if ($request->user()){
    //     $company = $request->user()->companies()->create($validatedData);
    // }else{
    //     $company = Company::create($validatedData);
    // }

    $company = $request->user()->companies()->create($validatedData);

    return $company->load('user');
    }


    public function show(Company $company)
    {
        return  $company;
    }

    public function update(Request $request, Company $company)
    {
        //Gate::authorize('modify', $company);

        $validatedData =  $request->validate([
                'name' => ['required','string'],
                'description' => ['required'],
                'contactEmail' => ['required','email'],
                'contactPhone' => ['required', 'string']
            ]);


        $company->update($validatedData);
        return  $company;
    }


    public function destroy(Company $company)
    {
        //Gate::authorize('modify', $company);

        $company->delete();

        return ['message' => 'The company was deleted'];
    }
}
