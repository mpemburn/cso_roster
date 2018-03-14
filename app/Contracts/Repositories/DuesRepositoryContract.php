<?php
namespace App\Contracts\Repositories;

use App\Contracts\Services\MemberServiceContract;
use Illuminate\Http\Request;


/**
 * Interface DuesRepositoryContract
 * @package App\Contracts
 */
interface DuesRepositoryContract extends RepositoryContract
{
    public function show($id);

    public function save($request, $id);

    public function savePaymentForMember(Request $request, MemberServiceContract $memberService);

    public function delete($id);
}