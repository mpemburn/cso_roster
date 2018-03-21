<?php

namespace App\Http\Controllers;

use App\Contracts\Services\MemberServiceContract;
use App\Contracts\Repositories\MemberRepositoryContract;
use Illuminate\Http\Request;
use App\Contracts\Repositories\DuesRepositoryContract;

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
        $result = ['success' => false];
        if (is_string($email) && is_string($zip)) {
            $member = $this->memberService->getMemberFromEmailAndZip($email, $zip);
            //var_dump($member);
            if (!is_null($member)) {
                $result = [
                    'success' => true,
                    'data' => $member
                ];
            }
        }

        return json_encode($result);
    }

    /** TODO: Consolidate this with the above
     * @param $email
     * @param $zip
     * @return null
     */
    public function verifyMember(Request $request)
    {
        $data = $request->all();
        $result = ['success' => false];

        if (isset($data['member_email']) && isset($data['member_zip'])) {
            $member = $this->memberService->getMemberFromEmailAndZip($data['member_email'], $data['member_zip']);
            if (!is_null($member)) {
                $result = [
                    'success' => true,
                    'data' => $member
                ];
            }
        }

        return json_encode($result);
    }

    public function createOrUpdateMember(MemberRepositoryContract $repository, Request $request)
    {
        $data = $request->all();
        $memberId = (isset($data['id'])) ? $data['id'] : 0;
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'address_1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'contact_name' => 'required',
            'contact_phone' => 'required',
        ];


        $response = $repository->save($request, $memberId, $rules);

        if (!is_null($response)) {
            return $response;
        } else {

        }
    }

    public function newMemberJoin(MemberRepositoryContract $memberRepository, DuesRepositoryContract $duesRepository, Request $request)
    {
        $data = $request->all();

        $result = $memberRepository->create($data);

        return json_encode($result);

    }

    public function saveDuesPaymentForMember(MemberRepositoryContract $memberRepository, DuesRepositoryContract $duesRepository, Request $request)
    {
        $data = $request->all();

        $member = $this->memberService->getMemberFromEmailAndZip($data['email'], $data['zip']);

        $request->request->add(['member_id' => $member->id]);
        $result = $duesRepository->makePayment($data, $member->id);

        return json_encode($result);

    }
}
