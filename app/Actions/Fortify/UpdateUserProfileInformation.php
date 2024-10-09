<?php

namespace Itpi\Actions\Fortify;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        // Rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ]
        ];

        // Validate
        request()->validate($rules);

        // Check
        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $update = $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
            ])->save();

            if ($update) {
                session()->flash('toast-success', 'Data profile diperbarui');
            } else {
                session()->flash('toast-error', 'Gagal memperbarui data profile !');
            }
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser($user, array $input)
    {
        $update = $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null
        ])->save();

        if ($update) {
            session()->flash('toast-success', 'Data profile diperbarui');
        } else {
            session()->flash('toast-error', 'Gagal memperbarui data profile !');
        }

        $user->sendEmailVerificationNotification();
    }
}
