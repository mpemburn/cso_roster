<?php
/**
 * Validator to compare existing password with one supplied by user
 *
 */

namespace App\Validators;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class OldPasswordValidator
 * @package App\Validators
 */
class MatchesOldPasswordValidator
{
    /**
     * This is invoked by the validator rule 'invalid_pattern'
     *
     * @param $attribute string the attribute name that is validating
     * @param $value mixed the value that we're testing
     * @param $parameters array
     * @return bool
     */
    public function validate($attribute, $value, $parameters = []) {
        $oldPassword = $value; //$request->old_password;
        $currentPassword = Auth::user()->password;

        return (Hash::check($oldPassword, $currentPassword));
    }
}