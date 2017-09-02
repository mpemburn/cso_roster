<?php
namespace App\Repositories;

use App\Contracts\Repositories\MemberRepositoryContract;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Contact;
use App\Models\State;
use App\Models\Prefix;
use App\Models\Suffix;
use App\Models\Relationship;
use App\Helpers\Format;
use App\Helpers\Date;

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
    public function getDetails($id = 0)
    {
        $thisMember = $this->model->findOrNew($id);
        $fiveYearsAgo = date('Y', strtotime("-5 year", time()));
        $data = [
            'can_edit' => true,
            'user_id' => Auth::user()->id,
            'member' => $thisMember,
            'member_id' => ($thisMember->exists) ? $thisMember->id : 0,
            'prefix_list' => Prefix::pluck('prefix', 'prefix')->prepend('Select', ''),
            'suffix_list' => Suffix::pluck('suffix', 'suffix')->prepend('Select', ''),
            'state_list' => State::where('local', 1)->pluck('name', 'code')->prepend('Select', ''),
            'relationship_list' => Relationship::pluck('relationship', 'relationship')->prepend('Select', ''),
            'calendar_year_list' => Date::calendarYearList($fiveYearsAgo, 20),
            'helmet_fund_list' => [0 => 'No', '1' => 'Yes'],
            'contacts' => $thisMember->contacts,
            'dues' => $thisMember->dues,
            'contact' => new Contact(),
            'dues' => $thisMember->dues,
            'is_active' => true,
        ];

        return $data;
    }

    /**
     * @param $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function save($request, $id)
    {
        $data = $request->all();
        $thisMember = $this->model->findOrNew($id);

        $data['cell_phone'] = Format::rawPhone($data['cell_phone']);
        $data['home_phone'] = Format::rawPhone($data['home_phone']);

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'address_1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'email' => 'required',
        ];
        // Validate user input.  Send them errors and let them try again if they fail
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()];
        } else {
            $result = $thisMember->fill($data)->save();
            $response = [
                'status' => $result,
                'member_id' => $thisMember->id,
                'data' => $data
            ];
        }

        return response()->json(['response' => $response]);
    }

    /**
     * @param $memberId
     * @return mixed
     */
    public function retrieveContacts($memberId)
    {
        $thisMember = $this->model->findOrNew($memberId);

        return $thisMember->contacts;
    }

    /**
     * @param $memberId
     * @return mixed
     */
    public function retrieveDues($memberId)
    {
        $thisMember = $this->model->findOrNew($memberId);

        return $thisMember->dues;
    }

}