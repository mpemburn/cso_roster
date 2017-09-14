<?php
/**
 * Validator to test that the value isn't present
 *
 */

namespace App\Validators;

use Illuminate\Contracts\Validation\Validator;
use App\Contracts\Repositories\MemberRepositoryContract;

/**
 * Class MemberEmailFoundValidator
 * @package App\Validators
 */
class MemberEmailFoundValidator
{
    /**
     * @$repository MemberRepositoryContract
     */
    protected $repository;

    public function __construct(MemberRepositoryContract $memberRepositoryContract)
    {
        $this->repository = $memberRepositoryContract;
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
        return $this->repository->isValidMemberEmailAddress($value);
    }
}