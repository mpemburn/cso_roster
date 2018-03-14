<?php
namespace App\Repositories;

use App\Contracts\Repositories\DuesRepositoryContract;
use App\Helpers\Format;
use App\Services\MemberService;
use App\Contracts\Services\MemberServiceContract;
use Validator;
use App\Models\Member;
use Illuminate\Http\Request;

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

        $paidDate = (isset($data['paid_date'])) ? $data['paid_date'] : date('Y-m-d H:i:s', time());
        $data['paid_date'] = Format::formatDateForMySql($paidDate);
        $data['calendar_year'] = Format::formatDate('Y', $paidDate);
        $data['member_id'] = (isset($data['member_id'])) ? $data['member_id'] : $id;

        // Validate user input.  Send them errors and let them try again if they fail
        $rules = $thisDuesPayment->rules;
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()];
        } else {
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

    public function savePaymentForMember(Request $request, MemberServiceContract $memberService)
    {
        $data = $request->all();
        $data['paid_date'] = Format::formatDateForMySql(date('Y-m-d H:i:s', time()));


        if ($data['process_type'] == 'new_member') {
            //$this->
        } else {
            $member = $memberService->getMemberFromEmailAndZip($data['email'], $data['zip']);
            $result = $this->save($request, $member->id);
//            $thisDuesPayment = $this->model->findOrNew($member->id);
//            $result = $thisDuesPayment->fill($data)->save();
        }

//        $thisDuesPayment = $this->model->findOrNew($memberId);
//        $result = $thisDuesPayment->fill($data)->save();
//
//        $thisMember = Member::find($memberId);
//        $thisMember->dues()->save($thisDuesPayment);
//
//        $response = [
//            'status' => $result,
//            'data' => $data
//        ];
//
//        echo json_encode($response);
        return $result;

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