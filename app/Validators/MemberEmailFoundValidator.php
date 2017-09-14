<?php
/**
 * Validator to test that the value isn't present
 *
 */

namespace App\Validators;

use Illuminate\Contracts\Validation\Validator;
use App\Contracts\Services\MemberServiceContract;

/**
 * Class MemberEmailFoundValidator
 * @package App\Validators
 */
class MemberEmailFoundValidator
{
    /**
     * @$repository MemberRepositoryContract
     */
    protected $memberService;

    public function __construct(MemberServiceContract $memberService)
    {
        $this->memberService = $memberService;
    }

    /**
     * This is invoked by the validator rule 'member_email_found'
     *
     * @param $attribute string the attribute name that is validating
     * @param $value mixed the value that we're testing
     * @param $parameters array
     * @return bool
     */
    public function validate($attribute, $value, $parameters = []) {
        return $this->memberService->isValidMemberEmailAddress($value);
    }
}