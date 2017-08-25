<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contracts\MemberRepositoryContract;
use App\Contracts\DuesRepositoryContract;
use App\Contracts\ContactRepositoryContract;
use App\Models\Member;

/*
TRUNCATE TABLE `members`;
TRUNCATE TABLE `dues`;
TRUNCATE TABLE `contacts`;
TRUNCATE TABLE `members_contacts`;
 */
class ImportMembersFromCSVCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:import';

    /**
     * @var $memberRepository MemberRepositoryContract
     */
    protected $memberRepository;

    /**
     * @var $duesRepository DuesRepositoryContract
     */
    protected $duesRepository;

    /**
     * @var $contactRepository ContactRepositoryContract
     */
    protected $contactRepository;

    /**
     * @var array
     */
    protected $memberMap = [
        'Last Name' => 'last_name',
        'First Name' => 'first_name',
        'E-Mail Address' => 'email',
        'Member Since ' => 'member_since_date',
        'Home Phone' => 'home_phone',
        'Cell Phone' => 'cell_phone',
        'Street Address' => 'address_1',
        'City' => 'city',
        'ZIP' => 'zip',
        'GoogleGroups' => 'google_group_date',
        'Emergency Contact' => 'emergency_contact',
        'Emergency Contact Phone ' => 'emergency_phone'
    ];

    protected $duesMap = [
        'Status' => 'calendar_year',
        'Dues Paid' => 'paid_date',
        'HF' => 'helmet_fund',
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports membership data from .csv file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        MemberRepositoryContract $memberRepository,
        DuesRepositoryContract $duesRepository,
        ContactRepositoryContract $contactRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->duesRepository = $duesRepository;
        $this->contactRepository = $contactRepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $csvData = $this->importCSV();
        if (is_array($csvData)) {
            $this->processImport($csvData);
        }
    }

    protected function importCSV()
    {
        $path = storage_path('app/public');
        $csvFile = $this->getLatestCSVFilename($path);

        return $this->getCSVData($path . '/' .$csvFile);
    }

    protected function getLatestCSVFilename($path)
    {
        $dir = dir($path);
        $latestCtime = 0;
        $latestFilename = '';

        while (false !== ($entry = $dir->read())) {
            $filePath = "{$path}/{$entry}";
            if (is_file($filePath) && filectime($filePath) > $latestCtime) {
                $latestCtime = filectime($filePath);
                $latestFilename = $entry;
            }
        }

        return $latestFilename;
    }

    protected function getCSVData($pathAndFile, $delimiter = ',') {

        if (!file_exists($pathAndFile) || !is_readable($pathAndFile))
            return false;

        $header = null;
        $csvData = array();
        if (($handle = fopen($pathAndFile, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $csvData[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $csvData;
    }

    protected function processImport($csvData)
    {
        foreach ($csvData as $row) {
            $memberData = $this->getMemberDataFromRow($row);
            if (!empty($memberData)) {
                $emergencyContact = $memberData['emergency_contact'];
                $emergencyPhone = $memberData['emergency_phone'];
                unset($memberData['emergency_contact']);
                unset($memberData['emergency_phone']);
                $member = $this->memberRepository->create($memberData);
                $this->createEmergencyContacts($emergencyContact, $emergencyPhone, $member);
                $duesData = $this->getDuesDataFromRow($row, $member->id);
                $this->duesRepository->create($duesData);
            }
        }
    }

    protected function getMemberDataFromRow($row)
    {
        $memberData = [];
        foreach ($row as $field => $value){
            $value = trim($value);
            $mappedField = (isset($this->memberMap[$field])) ? $this->memberMap[$field] : null;
            if (!is_null($mappedField)) {
                if ($mappedField == 'last_name' && empty($value)) {
                    break;
                }
                if ($mappedField == 'home_phone' || $mappedField == 'cell_phone') {
                    $value = preg_replace('/[^0-9\/]+/', '', $value);
                    $value = substr($value, 0, 10);
                }
                if ($mappedField == 'member_since_date' || $mappedField == 'google_group_date') {
                    // Strip all but the numerals and slashes
                    $timestamp = strtotime($value);
                    if ($timestamp !== false) {
                        $value = date("Y-m-d H:i:s", $timestamp);
                    } else {
                        $value = null;
                    }
                }
                $memberData[$mappedField] = $value;
            }
            if (!empty($memberData)) {
                // Assume they are active member
                $memberData['is_active'] = 1;
                // Maryland is assumed to be the state, though not include in the .csv
                $memberData['state'] = 'MD';
            }
        }

        return $memberData;
    }
    
    protected function getDuesDataFromRow($row, $memberId)
    {
        $duesData = [];
        foreach ($row as $field => $value){
            $value = trim($value);
            $mappedField = (isset($this->duesMap[$field])) ? $this->duesMap[$field] : null;
            if (!is_null($mappedField)) {
                if ($mappedField == 'helmet_fund') {
                    $value = (strtolower($value) == 'y') ? 1 : 0;
                }
                if ($mappedField == 'paid_date') {
                    // Strip all but the numerals and slashes
                    $value = preg_replace('/[^0-9\/]+/', '', $value);
                    $timestamp = strtotime($value);
                    if ($timestamp !== false) {
                        $value = date("Y-m-d H:i:s", $timestamp);
                    } else {
                        $value = null;
                    }
                }
                if ($mappedField == 'calendar_year') {
                    // Strip all but the numerals and slashes
                    $value = preg_replace('/[^0-9\/]+/', '', $value);
                    $timestamp = strtotime('1/1/' . $value);
                    if ($timestamp !== false) {
                        $value = date("Y", $timestamp);
                    } else {
                        $value = null;
                    }
                }
                $duesData[$mappedField] = $value;
            }
            if (!empty($duesData)) {
                $duesData['member_id'] = $memberId;
            }
        }

        return $duesData;
    }

    protected function createEmergencyContacts($contactField, $phoneField, $member)
    {
        $contactArray = $this->extractNamesFromContactField($contactField);
        $phoneArray = $this->extractPhonesFromPhoneField($phoneField);
        $multiContacts = (count($contactArray) == count($phoneArray));
        foreach ($contactArray as $contact) {
            if (!empty($contact)) {
                $phone1 = (!empty($phoneArray)) ? current($phoneArray) : null;
                // $phone2 will only have a value if there is one contact with two phone numbers
                $phone2 = (isset($phoneArray[1]) && !$multiContacts) ? $phoneArray[1] : null;
                // Create the contact
                $extract = $this->extractRelationFromContactField($contact);
                $newContact = $this->contactRepository->create([
                    'name' => ucwords($extract['contact']),
                    'relationship' => $extract['relationship'],
                    'phone_1' => $phone1,
                    'phone_2' => $phone2
                ]);
                // Create the relationship
                $member->contacts()->save($newContact);
                next($phoneArray);
            }
        }
    }

    protected function extractNamesFromContactField($contact)
    {
        $contact = str_replace([' and ', '/'], ';', $contact);
        $found = explode(';', $contact);

        return $found;
    }

    protected function extractRelationFromContactField($contact)
    {
        $relationship = null;
        $relationships = [
            'spouse' => 'spouse',
            'wife' => 'wife',
            'husband' => 'husband',
            'father' => 'father',
            'dad' => 'father',
            'mother' => 'mother',
            'mom' => 'mother',
            'brother' => 'brother',
            'sister' => 'sister',
            'daughter' => 'daughter',
            'son' => 'son',
            'aunt' => 'aunt',
            'uncle' => 'uncle',
            'cousin' => 'cousin',
            'friend' => 'friend',
        ];
        foreach (array_keys($relationships) as $key) {
            $variants = [
                ' ' . $key,
                '(' . $key . ')',
                ' (' . $key . ')'
            ];
            foreach ($variants as $variant) {
                if (stristr($contact, $variant) !== false) {
                    $relationship = $relationships[$key];
                    $contact = str_replace($variant, '', $contact);
                }
            }
        }

        return ['contact' => $contact, 'relationship' => $relationship];
    }

    protected function extractPhonesFromPhoneField($phones)
    {
        preg_match_all('/([0-9]+)/', $phones, $matches);

        $found = [];
        $phone = '';
        foreach ($matches[0] as $match) {
            $phone .= $match;
            if (strlen($phone) >= 10) {
                $found[] = substr($phone, 0, 10);
                $phone = '';
            }
        }

        return $found;
    }
}
