<?php

namespace App\Extensions\Passwords;

use Closure;
use Illuminate\Auth\Passwords\PasswordBroker as BasePasswordBroker;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use UnexpectedValueException;
use App\Models\User;

class PasswordBroker extends BasePasswordBroker
{
 /**
     * Send a password reset link to a user.
     *
     * @param  array  $credentials
     * @return string
     */
    public function sendResetLink(array $credentials, Closure $callback = null)
    {
        // First we will check to see if we found a user at the given credentials and
        // if we did not we will redirect back to this current URI with a piece of
        // "flash" data in the session to indicate to the developers the errors.

        $user = $this->getUser($credentials);

       if (is_null($user)) {
           return static::INVALID_USER;
       }

        // Once we have the reset token, we are ready to send the message out to this
        // user with a link to reset their password. We will then redirect back to
        // the current URI having nothing set in the session to indicate errors.
        if(!is_null($user)) {
            $user->sendPasswordResetNotification(
                $this->tokens->create($user)
            );
        }

        return static::RESET_LINK_SENT;
    }

    /**
     * Get the user for the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\CanResetPassword|null
     *
     * @throws \UnexpectedValueException
     */
    public function getUser(array $credentials)
    {
        $credentials = Arr::except($credentials, ['token']);

        // match input value with both phone and email
        $user = User::where('email', $credentials['email'])->orWhere('phone', 'like', "%".$credentials['email'])->first();

        if ($user && ! $user instanceof CanResetPasswordContract) {
            throw new UnexpectedValueException('User must implement CanResetPassword interface.');
        }

        return $user;
    }
}
