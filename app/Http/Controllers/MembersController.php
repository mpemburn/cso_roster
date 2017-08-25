<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\MemberRepositoryContract;
use Illuminate\Support\Facades\Auth;
use App\Models\State;

class MembersController extends Controller
{
    /**
     * @var MemberRepositoryContract
     */
    protected $repository;

    /**
     * MembersController constructor.
     * @param MemberRepositoryContract $repository
     */
    public function __construct(MemberRepositoryContract $repository)
    {
        $this->repository = $repository;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $thisMember = $this->repository->find($id);
        $data = [
            'can_edit' => true,
            'user_id' => Auth::user()->id,
            'member' => $thisMember,
            'prefix_list' => [],
            'suffix_list' => [],
            'state_list' => State::where('local', 1)->pluck('name', 'code')->prepend('Select', ''),
            'is_active' => true,
        ];
        return view('member_edit', $data);
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
        //
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
