<?php
namespace App\Repositories;

use App\Contracts\Repositories\DuesRepositoryContract;
use App\Helpers\Format;
use Validator;
use App\Models\Member;

/**
 * Class DuesRepository
 * @package App\Repositories
 */
class DuesRepository extends AbstractRepository implements DuesRepositoryContract
{
    public function show($id = 0)
    {
        $thisDuesPayment = $this->model->findOrNew($id);
        $payment = $thisDuesPayment->toArray();
        $payment['dues_id'] = $payment['id'];
        $payment['dues_member_id'] = $payment['member_id'];
        //$payment['paid_date'] = Format::formatDate(Format::LONG_DATE, $payment['paid_date']);

        $response = [
            'success' => $thisDuesPayment->exists,
            'data' => $payment
        ];

        return $response;
    }

    public function save($request, $id)
    {
        $data = $request->all();
        $thisDuesPayment = $this->model->findOrNew($id);

        // Validate user input.  Send them errors and let them try again if they fail
        $rules = $thisDuesPayment->rules;
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()];
        } else {
            $data['paid_date'] = Format::formatDateForMySql($data['paid_date']);
            $result = $thisDuesPayment->fill($data)->save();
            // If this is a new dues payment, we also have to create a record in the pivot table
            if ($data['id'] == 0) {
                $thisMember = Member::find($data['member_id']);
                $thisMember->dues()->save($thisDuesPayment);
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
        $thisDuesPayment = $this->model->find($id);

        $thisMember = Member::find($thisDuesPayment->member_id);

        $thisDuesPayment->delete($id);

        $remainingPayments = $thisMember->dues;
        return $remainingPayments;
    }
}