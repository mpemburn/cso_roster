<?php
namespace App\Repositories;

use App\Contracts\Repositories\DuesRepositoryContract;
use App\Helpers\Format;
use Validator;

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

        $rules = [
            'calendar_year' => 'required',
            'paid_date' => 'required',
            'paid_amount' => 'required',
        ];
        // Validate user input.  Send them errors and let them try again if they fail
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()];
        } else {
            $data['paid_date'] = Format::formatDateForMySql($data['paid_date']);
            $result = $thisDuesPayment->fill($data)->save();
            $response = [
                'status' => $result,
                'data' => $data
            ];
        }

        return $response;
    }
}