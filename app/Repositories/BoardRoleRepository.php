<?php
namespace App\Repositories;

use App\Contracts\Repositories\BoardRoleRepositoryContract;
use App\Helpers\Format;
use Validator;
use App\Models\Member;

/**
 * Class BoardRoleRepository
 * @package App\Repositories
 */
class BoardRoleRepository extends AbstractRepository implements BoardRoleRepositoryContract
{
    public function show($id = 0)
    {
        $thisBoardRole = $this->model->findOrNew($id);
        $role = $thisBoardRole->toArray();
        $role['role_id'] = $role['id'];
        $role['role_member_id'] = $role['member_id'];
        $role['start_date'] = Format::formatDate(Format::SHORT_DATE, $role['start_date']);
        $role['end_date'] = Format::formatDate(Format::SHORT_DATE, $role['end_date']);

        $response = [
            'success' => $thisBoardRole->exists,
            'data' => $role
        ];

        return $response;
    }

    public function save($request, $id)
    {
        $data = $request->all();
        $thisBoardRole = $this->model->findOrNew($id);

        // Validate user input.  Send them errors and let them try again if they fail
        $rules = $thisBoardRole->rules;
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()];
        } else {
            $data['start_date'] = Format::formatDateForMySql($data['start_date']);
            $data['end_date'] = Format::formatDateForMySql($data['end_date']);
            $result = $thisBoardRole->fill($data)->save();
            if ($data['id'] == 0) {
                $thisMember = Member::find($data['member_id']);
                $thisMember->roles()->save($thisBoardRole);
            }
            $response = [
                'status' => $result,
                'data' => $data
            ];
        }

        return $response;
    }
    
    public function delete($id)
    {
        $thisBoardRole = $this->model->find($id);

        $thisMember = Member::find($thisBoardRole->member_id);

        $thisBoardRole->delete($id);

        $remainingRoles = $thisMember->roles;
        return $remainingRoles;
    }
}