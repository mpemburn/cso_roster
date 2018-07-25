<?php
namespace App\Repositories;

use App\Contracts\Repositories\GuestRepositoryContract;
use TheIconic\NameParser\Parser;
use Illuminate\Support\Facades\Validator;

/**
 * Class GuestRepository
 * @package App\Repositories
 */
class GuestRepository extends AbstractRepository implements GuestRepositoryContract
{
    public function create(array $data = [])
    {
        $success = false;
        $response = null;
        $isNew = null;

        $parser = new Parser();
        $guestData = [];

        $parsed = $parser->parse($data['guest_name']);

        $guestData['first_name'] = $parsed->getFirstname();
        $guestData['last_name'] = $parsed->getLastname();
        $guestData['phone'] = (isset($data['guest_phone'])) ? $data['guest_phone'] : null;


        $rules = $this->model->rules;

        $validator = Validator::make($guestData, $rules);

        if ($validator->fails()) {
            $response = ['errors' => $validator->errors()];
        } else {
            $response = $this->model->firstOrCreate($guestData);
            $isNew = ($this->isNewRecord($response)) ? true : false;
            $success = true;

        }

        return [
            'success' => $success,
            'response' => $response,
            'isNew' => $isNew,
        ];
    }

    public function show($id)
    {
        // TODO: Implement show() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
}