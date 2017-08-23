<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contracts\MemberRepositoryContract;
use App\Contracts\DuesRepositoryContract;
use App\Models\Member;

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
        'Emergency Contact Phone ' => 'emergency_phone_1'
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
    public function __construct(MemberRepositoryContract $memberRepository, DuesRepositoryContract $duesRepository)
    {
        $this->memberRepository = $memberRepository;
        $this->duesRepository = $duesRepository;
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
                $member = $this->memberRepository->create($memberData);
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
                if ($mappedField == 'emergency_phone_1') {
                    $value = preg_replace('/[^0-9\/]+/', '', $value);
                    if (strlen($value) > 10) {
                        $value1 = substr($value, 0, 10);
                        $value2 = substr($value, -10);
                        $memberData['emergency_phone_2'] = $value2;
                        $value = $value1;
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
}
