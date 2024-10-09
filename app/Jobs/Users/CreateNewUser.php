<?php

namespace Itpi\Jobs\Users;

use Itpi\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Validator;

class CreateNewUser implements ShouldQueue
{

    public User $user;

    public array $attributes;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $attributes)
    {
        $this->user = new User();
        $this->attributes = Validator::make($attributes, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|confirmed',
        ])->validate();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->fill($this->attributes);
        $this->user->save();
    }
}
