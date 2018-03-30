<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\Repositories\MemberRepositoryContract;
use App\Contracts\Services\MemberServiceContract;
use Auth;
use Illuminate\Support\Facades\Hash;

class MembersController extends Controller
{
    /**
     * @$repository MemberRepositoryContract
     */
    protected $repository;

    /**
     * @$memberService MemberServiceContract
     */
    protected $memberService;

    /**
     * MembersController constructor.
     * @param MemberRepositoryContract $repository
     */
    public function __construct(MemberRepositoryContract $memberRepository, MemberServiceContract $memberService)
    {
        $this->repository = $memberRepository;
        $this->memberService = $memberService;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activeMembers = $this->repository->findAll([
            'is_active', true],
            ['contacts', 'dues'],
            ['last_name', 'asc', 'first_name', 'asc']
        );

        return view('members_list', ['members' => $activeMembers]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function details($id = 0)
    {
        $memberDetails = $this->repository->getDetails($id);

        return view('member_edit', $memberDetails);
    }

    /**
     * Retrieve list of Contacts associated with this member in HTML format
     *
     * @param $memberId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function retrieveContacts($memberId)
    {
        $contacts = $this->repository->retrieveContacts($memberId);

        return view('partials.member_contacts', ['contacts' => $contacts]);
    }

    /**
     * Retrieve list of Dues payments associated with this member in HTML format
     *
     * @param $memberId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function retrieveDues($memberId)
    {
        $dues = $this->repository->retrieveDues($memberId);

        return view('partials.member_dues', ['dues' => $dues]);
    }

    /**
     * Retrieve list of Dues payments associated with this member in HTML format
     *
     * @param $memberId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function retrieveRoles($memberId)
    {
        $roles = $this->repository->retrieveRoles($memberId);

        return view('partials.board_roles', ['roles' => $roles]);
    }

    public function reactMain()
    {
        return view('react.main');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = $this->repository->store($request);

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = 0)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = $this->repository->save($request, $id);

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function resetProfilePassword(Request $request)
    {
        $memberId = $this->memberService->getMemberIdFromUserId(Auth::user()->id);

        return view('auth/passwords/profile_reset', ['member_id' => $memberId]);
    }

    public function setNewPassword(Request $request)
    {
        $response = $this->memberService->resetUserPassword($request);

        return response()->json($response);
    }

    public function passwordResetSuccess()
    {
        return view('auth/passwords/reset_success', []);
    }

}
