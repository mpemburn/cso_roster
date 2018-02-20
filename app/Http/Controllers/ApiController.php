<?php

namespace App\Http\Controllers;

use App\Contracts\Services\MemberServiceContract;
use App\Contracts\Repositories\MemberRepositoryContract;
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
     * @param $zip
     * @return null
     */
    public function getUserFromEmailAndZip($email, $zip)
    {
        if (is_string($email)) {
            $member = $this->memberService->getMemberFromEmailAndZip($email, $zip);

            if (!is_null($member)) {
                return $member->toJson();
            }
        }

        return null;
    }

    public function createOrUpdateMember(MemberRepositoryContract $repository, Request $request)
    {
        $data = $request->all();

        $response = $repository->save($request, $data['id']);

        if (!is_null($response)) {
            return $response;
        } else {

        }
    }
}
