<?php

namespace Itpi\Actions\Fortify;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        if (!empty($user->password)) {
            // Validate Rules
            $validator = Validator::make(request()->all(), [
                'current_password' => ['required', 'string'],
                'password' => $this->passwordRules(),
                'password_confirmation' => ['required']
            ]);

            // Validate After
            $validator->after(function ($validator) use ($user, $input) {
                if (!isset($input['current_password']) || !Hash::check($input['current_password'], $user->password)) {
                    $validator->errors()->add('current_password', "Current password doesn't match");
                }
            });
        } else {
            // Validate Rules
            $validator = Validator::make(request()->all(), [
                'password' => $this->passwordRules(),
                'password_confirmation' => ['required']
            ]);
        }

        // Set tab active
        if ($validator->fails()) {
            session()->flash('tab', 'edit_password');
        }

        // Validate
        $validator->validate();

        // Update password
        $update = $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();

        // Check
        if ($update) {
            session()->flash('toast-success', 'Password telah diubah');
        } else {
            session()->flash('toast-error', 'Gagal mengubah password !');
        }

        // Set tab active
        session()->flash('tab', 'edit_password');
    }
}
