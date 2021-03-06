<?php
namespace App\Repositories;

use App\Contracts\Repositories\MemberRepositoryContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Contact;
use App\Models\BoardRole;
use App\Models\BoardRoleTitle;
use App\Models\State;
use App\Models\Prefix;
use App\Models\Suffix;
use App\Models\Relationship;
use App\Helpers\Format;
use App\Helpers\Date;

use App\Events\MemberJoined;


/**
 * Class MemberRepository
 * @package App\Repositories
 */
class MemberRepository extends AbstractRepository implements MemberRepositoryContract
{
    public function create(array $data = [])
    {
        $success = false;

        $rules = $this->model->rules;

        $data = $this->fixPhones($data);
        $data = $this->fixTimes($data);

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()];
        } else {
            $data['member_since'] = date('Y-m-d H:i:s', time());
            $member = parent::create($data);
            event(new MemberJoined($member, $data));
            $success = true;

        }

        return ['success' => $success];
    }


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
            'title_list' => BoardRoleTitle::pluck('title', 'id')->prepend('Select', ''),
            'calendar_year_list' => Date::calendarYearList($fiveYearsAgo, 20, ['Select', '']),
            'helmet_fund_list' => [0 => 'No', '1' => 'Yes'],
            'contacts' => $thisMember->contacts,
            'dues' => $thisMember->dues,
            'roles' => $thisMember->roles,
            'contact' => new Contact(),
            'role' => new BoardRole(),
            'dues' => $thisMember->dues,
            'is_active' => ($thisMember->is_active == 1),
        ];

        return $data;
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

    /**
     * @param $memberId
     * @return mixed
     */
    public function retrieveRoles($memberId)
    {
        $thisMember = $this->model->findOrNew($memberId);

        return $thisMember->roles;
    }

    /**
     * @param $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function save($request, $id, $rules = null)
    {
        $data = $request->all();

        $thisMember = $this->model->findOrNew($id);

        if (!empty($data)) {
            $data = $this->fixPhones($data);
            $data = $this->fixTimes($data);
            $data['is_active'] = (isset($data['is_active'])) ? 1 : 0;
        }

        // Validate user input.  Send them errors and let them try again if they fail
        if (is_null($rules)) {
            $rules = $thisMember->rules;
        }
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()];
        } else {
            $result = $thisMember->fill($data)->update();
            $data['member_id'] = $thisMember->id;
            $response = [
                'status' => $result,
                'member_id' => $thisMember->id,
                'is_new' => ($id == 0),
                'data' => $data
            ];
        }

        return $response;
    }

    /**
     * @param $request
     */
    public function store($request)
    {
        $response = $this->save($request, 0);

        return $response;
    }

    protected function fixPhones($data)
    {
        $data['cell_phone'] = (isset($data['cell_phone'])) ? Format::rawPhone($data['cell_phone']) : '';
        $data['home_phone'] = (isset($data['home_phone'])) ? Format::rawPhone($data['home_phone']) : '';
        $data['contact_phone'] = (isset($data['contact_phone'])) ? Format::rawPhone($data['contact_phone']) : '';

        return $data;
    }

    protected function fixTimes($data)
    {
        $timeNow = Format::formatDateForMySql(time());
        $data['member_since_date'] = (empty($data['member_since_date'])) ? $timeNow : Format::formatDateForMySql($data['member_since_date']);
        $data['google_group_date'] = (empty($data['google_group_date'])) ? $timeNow : Format::formatDateForMySql($data['google_group_date']);

        return $data;
    }
}