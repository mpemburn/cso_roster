<?php
namespace App\Repositories;

use App\Contracts\Repositories\ContactRepositoryContract;

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
        $contact['member_id'] = $thisContact->members[0]->id;

        $response = [
            'success' => $thisContact->exists,
            'data' => $contact
        ];

        return response()->json($response);
    }
}