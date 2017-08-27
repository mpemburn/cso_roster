<?php
namespace App\Repositories;

use App\Contracts\Repositories\MemberRepositoryContract;
use Illuminate\Support\Facades\Auth;
use App\Models\State;
use App\Models\Prefix;
use App\Models\Suffix;

/**
 * Class MemberRepository
 * @package App\Repositories
 */
class MemberRepository extends AbstractRepository implements MemberRepositoryContract
{
    /**
     * @param $id
     * @return array
     */
    public function getDetails($id)
    {
        $thisMember = $this->model->find($id);
        $data = [
            'can_edit' => true,
            'user_id' => Auth::user()->id,
            'member' => $thisMember,
            'prefix_list' => Prefix::pluck('prefix', 'prefix')->prepend('Select', ''),
            'suffix_list' => Suffix::pluck('suffix', 'suffix')->prepend('Select', ''),
            'state_list' => State::where('local', 1)->pluck('name', 'code')->prepend('Select', ''),
            'contacts' => $thisMember->contacts,
            'dues' => $thisMember->dues,
            'is_active' => true,
        ];

        return $data;
    }
}