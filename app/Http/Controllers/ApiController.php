<?php

namespace App\Http\Controllers;

use App\Contracts\Services\MemberServiceContract;
use Illuminate\Http\Request;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    protected $memberService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MemberServiceContract $memberService)
    {
        $this->memberService = $memberService;
    }


    /**
     * @param $email
     * @return json|null
     */
    public function getUserFromEmail($email)
    {
        if (is_string($email)) {
            $member = $this->memberService->getMemberFromEmail($email);

            if (!is_null($member)) {
                return $member->toJson();
            }
        }

        return null;
    }

    public function createOrUpdateMember(Request $request)
    {
        $data = $request->all();
        $member = $this->memberService->getMemberFromEmail($data['email']);

        if (!is_null($member)) {
            return $member->toJson();
        } else {

        }
    }
}
