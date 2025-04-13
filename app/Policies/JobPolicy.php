<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JobPolicy
{
    public function modify(User $user, Job $jobs): Response
    {
        return $user->id === $jobs->user_id
            ? Response::allow()
            : Response::deny();
    }
}
