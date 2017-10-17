<?php
namespace App\Repositories;

use App\Contracts\Repositories\ResetPasswordRepositoryContract;
use App\Helpers\Format;
use Validator;

/**
 * Class ResetPasswordRepository
 * @package App\Repositories
 */
class ResetPasswordRepository extends AbstractRepository implements ResetPasswordRepositoryContract
{
    public function save($request, $userId)
    {
        $data = $request->all();
        $thisUser = $this->model->findOrNew($userId);

        // Validate user input.  Send them errors and let them try again if they fail
        $rules = $thisUser->reset_rules;
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()];
        } else {
//            $data['start_date'] = Format::formatDateForMySql($data['start_date']);
//            $data['end_date'] = Format::formatDateForMySql($data['end_date']);
//            $result = $thisUser->fill($data)->save();
//            if ($data['id'] == 0) {
//                $thisMember = Member::find($data['member_id']);
//                $thisMember->roles()->save($thisUser);
//            }
//            $response = [
//                'status' => $result,
//                'data' => $data
//            ];
        }

        return $response;
    }
}