<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\Repositories\MemberRepositoryContract;

class MembersController extends Controller
{
    /**
     * @var MemberRepositoryContract
     */
    protected $repository;

    /**
     * @var MemberServiceContract
     */
    protected $memberService;

    /**
     * MembersController constructor.
     * @param MemberRepositoryContract $repository
     */
    public function __construct(MemberRepositoryContract $memberRepository)
    {
        $this->repository = $memberRepository;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activeMembers = $this->repository->findAll([],[], 200, []);
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

    public function retrieveContacts($memberId)
    {
        $contacts = $this->repository->retrieveContacts($memberId);

        $contactsHtml = view('partials.member_contacts', ['contacts' => $contacts]);
//        $response = [
//            'success' => true,
//            'data' => $contactsHtml
//        ];
        return $contactsHtml;
//        return response()->json($response);

    }

    public function retrieveDues($memberId)
    {
        $dues = $this->repository->retrieveDues($memberId);

        return view('partials.member_dues', ['dues' => $dues]);
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
        $foo = 'bar';
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
        return $this->repository->save($request, $id);
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
        return view('auth/passwords/profile_reset', ['token' => csrf_token()]);
    }

    public function setNewPassword(Request $request)
    {
        return 'Reset password, now?';
    }

}
