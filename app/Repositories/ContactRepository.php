<?php
namespace App\Repositories;

use App\Contracts\Repositories\ContactRepositoryContract;
use Validator;
use App\Helpers\Format;

/**
 * Class ContactRepository
 * @package App\Repositories
 */
class ContactRepository extends AbstractRepository implements ContactRepositoryContract
{
    public function show($id = 0)
    {
        $thisContact = $this->model->findOrNew($id);

        $contact = $thisContact->toArray();
        $contact['contact_member_id'] = $thisContact->members[0]->id;
        $contact['contact_id'] = $contact['id'];

        $response = [
            'success' => $thisContact->exists,
            'data' => $contact
        ];

        return response()->json($response);
    }

    public function save($request, $id)
    {
        $data = $request->all();
        $thisContact = $this->model->findOrNew($id);

        $data['phone_one'] = Format::rawPhone($data['phone_one']);
        $data['phone_two'] = Format::rawPhone($data['phone_two']);
        $data['work_phone'] = Format::rawPhone($data['work_phone']);

        $rules = [
            'name' => 'required',
            'phone_one' => 'required',
        ];
        // Validate user input.  Send them errors and let them try again if they fail
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()];
        } else {
            $result = $thisContact->fill($data)->save();
            $response = [
                'status' => $result,
                'data' => $data
            ];
        }

        return response()->json(['response' => $response]);
    }
}