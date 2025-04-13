<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class JobController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except:['index','show'])
        ];
    }
    public function index(Request $request)
    {
        $query = Job::with(['user', 'company'])->latest();

        // Apply filters based on query params
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        return $query->paginate(10);
        //return Job::with(['user','company'])->latest()->paginate(10);
    }


    public function store(Request $request)
    {
       $validatedData =  $request->validate([
                        'company_id' => ['required','exists:companies,id'],
                        'type' => ['required','string'],
                        'title' => ['required','string'],
                        'description' => ['required'],
                        'salary' => ['required'],
                        'location' => ['required','string'],
                    ]);

    $job = $request->user()->jobs()->create($validatedData);
    //$job = Job::create($validatedData);
    return $job;
    }


    public function show(Job $job)
    {
        return  $job->load('company');
    }

    public function update(Request $request, Job $job)
    {
        Gate::authorize('modify', $job);

        $validatedData =  $request->validate([
                'company_id' => ['required','exists:companies,id'],
                'type' => ['required','string'],
                'title' => ['required','string'],
                'description' => ['required'],
                'salary' => ['required'],
                'location' => ['required','string'],

            ]);


        $job->update($validatedData);
        return  $job;
    }


    public function destroy(Job $job)
    {
        Gate::authorize('modify', $job);

        $job->delete();

        return ['message' => 'The job was deleted'];
    }
}
