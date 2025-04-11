<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        return Job::with(['user','company'])->latest()->paginate(10);
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


    $job = Job::create($validatedData);
    return $job;
      // $job = $request->user()->posts()->create($validatedData);
       //return  ['post' => $post, 'user' => $post->user];
    }


    public function show(Job $job)
    {
        return  $job->load('company');
    }

    public function update(Request $request, Job $job)
    {
        //Gate::authorize('modify', $job);

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
        //Gate::authorize('modify', $company);

        $job->delete();

        return ['message' => 'The job was deleted'];
    }
}
