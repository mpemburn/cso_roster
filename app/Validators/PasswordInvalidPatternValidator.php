<?php
/**
 * Validator to test that the value isn't present
 *
 */

namespace App\Validators;

use Illuminate\Contracts\Validation\Validator;

/**
 * Class PasswordInvalidPatternValidator
 * @package App\Validators
 */
class PasswordInvalidPatternValidator
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
        $has_spaces = (preg_match('/\s/',$value) > 0);
        $has_lowercase = ($value != strtoupper($value));
        $has_uppercase = ($value != strtolower($value));
        $has_nonalpha = (!ctype_alpha($value));

        return (!$has_spaces && $has_lowercase && $has_uppercase && $has_nonalpha);
    }
}