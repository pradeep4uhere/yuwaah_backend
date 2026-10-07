<?php

namespace App\Imports;

use App\Models\Learner;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\LearnerImportFailure;
use Illuminate\Support\Str;

class LearnerImport implements
    ToCollection,
    WithHeadingRow,
    WithChunkReading,
    SkipsEmptyRows
{
    protected string $programCode;

    protected bool $previewOnly = false;

    protected ?int $importLogId = null;

    protected int $currentRowNumber = 1;


    protected int $totalRows = 0;
    protected int $successCount = 0;
    protected int $duplicateCount = 0;
    protected int $failedCount = 0;

    protected array $failedRows = [];
    protected array $duplicateRows = [];

    /*
    |--------------------------------------------------------------------------
    | Identifiers already accepted from this Excel file
    |--------------------------------------------------------------------------
    */

    protected array $seenCandidateIds = [];
    protected array $seenEmails = [];
    protected array $seenMobiles = [];

    protected array $pendingFailureRows = [];

   

    public function __construct(
        string $programCode,
        bool $previewOnly = false,
        ?int $importLogId = null
    ) {
        $this->programCode = trim($programCode);
        $this->previewOnly = $previewOnly;
        $this->importLogId = $importLogId;
    }

    /*
    |--------------------------------------------------------------------------
    | MAIN CHUNK PROCESSOR
    |--------------------------------------------------------------------------
    */

    public function collection(Collection $rows): void
    {
        if ($rows->isEmpty()) {
            return;
        }

        $preparedRows = [];

        /*
        |--------------------------------------------------------------------------
        | First pass: normalize + validate
        |--------------------------------------------------------------------------
        */

        foreach ($rows as $index => $rawRow) {

            $this->totalRows++;

            $rowNumber = ++$this->currentRowNumber;

            $data = $this->normalizeRow(
                $rawRow->toArray()
            );

            try {

                $candidateId = $this->clean(
                    $data['candidate_id'] ?? null
                );

                $firstName = $this->clean(
                    $data['first_name'] ?? null
                );

                $lastName = $this->clean(
                    $data['last_name'] ?? null
                );

                $email = $this->clean(
                    $data['email'] ?? null
                );

                if (empty($email)) {

                    $firstName = $this->clean($data['first_name'] ?? 'learner');
                    $mobile = $this->clean($data['mobile_number'] ?? '');
                
                    $firstName = strtolower($firstName);
                    $firstName = preg_replace('/[^a-z0-9]+/', '', $firstName);
                
                    $mobile = preg_replace('/\D+/', '', $mobile);
                
                    if ($mobile !== '') {
                        $email = $firstName . '.' . $mobile . '@yuthhubpartner.org';
                    } else {
                        $email = $firstName . '.' . strtolower(Str::random(10)) . '@yuthhubpartner.org';
                    }
                }

                $mobile = $this->clean(
                    $data['mobile_number'] ?? null
                );

                /*
                |--------------------------------------------------------------------------
                | Required fields
                |--------------------------------------------------------------------------
                */

                if ($firstName === '') {
                    $this->failRow(
                        $rowNumber,
                        $data,
                        'First name is required.'
                    );

                    continue;
                }

                

                if (
                    $candidateId === '' &&
                    $email === '' &&
                    $mobile === ''
                ) {
                    $this->failRow(
                        $rowNumber,
                        $data,
                        'At least one unique identifier is required: candidate_id, email or mobile number.'
                    );

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Email validation
                |--------------------------------------------------------------------------
                */

                if (
                    $email !== '' &&
                    !filter_var($email, FILTER_VALIDATE_EMAIL)
                ) {
                    $this->failRow(
                        $rowNumber,
                        $data,
                        'Invalid email address.'
                    );

                    continue;
                }

                $normalizedMobile = $this->normalizeMobile(
                    $mobile
                );

                $preparedRows[] = [
                    'row_number' => $rowNumber,
                    'data' => $data,

                    'candidate_id' => $candidateId,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'mobile' => $mobile,
                    'normalized_mobile' => $normalizedMobile,
                ];

            } catch (\Throwable $e) {

                $this->failRow(
                    $rowNumber,
                    $data,
                    $e->getMessage()
                );

                Log::error(
                    'Learner Excel Row Preparation Failed',
                    [
                        'row' => $rowNumber,
                        'error' => $e->getMessage(),
                    ]
                );
            }
        }

        if (empty($preparedRows)) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Collect identifiers for ONE bulk DB query
        |--------------------------------------------------------------------------
        */

        $candidateIds = [];
        $emails = [];
        $mobiles = [];

        foreach ($preparedRows as $item) {

            if ($item['candidate_id'] !== '') {
                $candidateIds[] = $item['candidate_id'];
            }

            if ($item['email'] !== '') {
                $emails[] = strtolower(
                    $item['email']
                );
            }

            if ($item['normalized_mobile']) {
                $mobiles[] = $item['normalized_mobile'];
            }
        }

        $candidateIds = array_values(
            array_unique($candidateIds)
        );

        $emails = array_values(
            array_unique($emails)
        );

        $mobiles = array_values(
            array_unique($mobiles)
        );

        /*
        |--------------------------------------------------------------------------
        | ONE bulk duplicate query
        |--------------------------------------------------------------------------
        */

        $existingLearners = Learner::query()
            ->select([
                'id',
                'external_id',
                'email',
                'USER_EMAIL',
                'normalized_mobile',
            ])
            ->where(function ($query) use (
                $candidateIds,
                $emails,
                $mobiles
            ) {

                if (!empty($candidateIds)) {
                    $query->whereIn(
                        'external_id',
                        $candidateIds
                    );
                }

                if (!empty($emails)) {

                    if (!empty($candidateIds)) {
                        $query->orWhereIn(
                            'email',
                            $emails
                        );
                    } else {
                        $query->whereIn(
                            'email',
                            $emails
                        );
                    }

                    $query->orWhereIn(
                        'USER_EMAIL',
                        $emails
                    );
                }

                if (!empty($mobiles)) {

                    if (
                        !empty($candidateIds) ||
                        !empty($emails)
                    ) {
                        $query->orWhereIn(
                            'normalized_mobile',
                            $mobiles
                        );
                    } else {
                        $query->whereIn(
                            'normalized_mobile',
                            $mobiles
                        );
                    }
                }

            })
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Build PHP lookup maps
        |--------------------------------------------------------------------------
        */

        $existingByCandidate = [];
        $existingByEmail = [];
        $existingByUserEmail = [];
        $existingByMobile = [];

        foreach ($existingLearners as $learner) {

            if (
                $learner->external_id !== null &&
                $learner->external_id !== ''
            ) {
                $existingByCandidate[
                    (string) $learner->external_id
                ] = $learner;
            }

            if (
                $learner->email !== null &&
                $learner->email !== ''
            ) {
                $existingByEmail[
                    strtolower(
                        trim($learner->email)
                    )
                ] = $learner;
            }

            if (
                $learner->USER_EMAIL !== null &&
                $learner->USER_EMAIL !== ''
            ) {
                $existingByUserEmail[
                    strtolower(
                        trim($learner->USER_EMAIL)
                    )
                ] = $learner;
            }

            if (
                $learner->normalized_mobile !== null &&
                $learner->normalized_mobile !== ''
            ) {
                $existingByMobile[
                    (string) $learner->normalized_mobile
                ] = $learner;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Build INSERT batch
        |--------------------------------------------------------------------------
        */

        $insertBatch = [];

        $insertMeta = [];

        foreach ($preparedRows as $item) {

            $rowNumber = $item['row_number'];

            $candidateId = $item['candidate_id'];
            $email = $item['email'];
            $emailKey = strtolower($email);
            $normalizedMobile = $item['normalized_mobile'];

            /*
            |--------------------------------------------------------------------------
            | 1. Candidate ID duplicate
            |--------------------------------------------------------------------------
            */

            if ($candidateId !== '') {

                if (
                    isset(
                        $existingByCandidate[
                            $candidateId
                        ]
                    )
                ) {

                    $learner =
                        $existingByCandidate[
                            $candidateId
                        ];

                    $this->addDuplicate(
                        $rowNumber,
                        $item['data'],
                        $candidateId,
                        $email,
                        $item['mobile'],
                        'Candidate ID already exists.',
                        $learner->id
                    );

                    continue;
                }

                if (
                    isset(
                        $this->seenCandidateIds[
                            $candidateId
                        ]
                    )
                ) {

                    $this->addDuplicate(
                        $rowNumber,
                        $item['data'],
                        $candidateId,
                        $email,
                        $item['mobile'],
                        'Candidate ID appears more than once in this file.',
                        null
                    );

                    continue;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 2. Email duplicate
            |--------------------------------------------------------------------------
            */

            if ($email !== '') {

                if (
                    isset(
                        $existingByEmail[
                            $emailKey
                        ]
                    )
                ) {

                    $learner =
                        $existingByEmail[
                            $emailKey
                        ];

                    $this->addDuplicate(
                        $rowNumber,
                        $item['data'],
                        $candidateId,
                        $email,
                        $item['mobile'],
                        'Email already exists.',
                        $learner->id
                    );

                    continue;
                }

                if (
                    isset(
                        $existingByUserEmail[
                            $emailKey
                        ]
                    )
                ) {

                    $learner =
                        $existingByUserEmail[
                            $emailKey
                        ];

                    $this->addDuplicate(
                        $rowNumber,
                        $item['data'],
                        $candidateId,
                        $email,
                        $item['mobile'],
                        'Email already exists.',
                        $learner->id
                    );

                    continue;
                }

                if (
                    isset(
                        $this->seenEmails[
                            $emailKey
                        ]
                    )
                ) {

                    $this->addDuplicate(
                        $rowNumber,
                        $item['data'],
                        $candidateId,
                        $email,
                        $item['mobile'],
                        'Email appears more than once in this file.',
                        null
                    );

                    continue;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 3. Mobile duplicate
            |--------------------------------------------------------------------------
            */

            if ($normalizedMobile) {

                if (
                    isset(
                        $existingByMobile[
                            $normalizedMobile
                        ]
                    )
                ) {

                    $learner =
                        $existingByMobile[
                            $normalizedMobile
                        ];

                    $this->addDuplicate(
                        $rowNumber,
                        $item['data'],
                        $candidateId,
                        $email,
                        $item['mobile'],
                        'Mobile number already exists.',
                        $learner->id
                    );

                    continue;
                }

                if (
                    isset(
                        $this->seenMobiles[
                            $normalizedMobile
                        ]
                    )
                ) {

                    $this->addDuplicate(
                        $rowNumber,
                        $item['data'],
                        $candidateId,
                        $email,
                        $item['mobile'],
                        'Mobile number appears more than once in this file.',
                        null
                    );

                    continue;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Prepare learner record
            |--------------------------------------------------------------------------
            */

            $learnerData = [
                'external_id' => $candidateId ?: null,

                'first_name' => $item['first_name'],

                'last_name' => $item['last_name'],

                'gender' => $this->mapGender(
                    $item['data']['gender'] ?? null
                ),

                'email' => $email ?: null,

                'primary_phone_number' =>
                    $item['mobile'] ?: null,

                'institution' =>
                    $this->clean(
                        $item['data']['tp_name'] ?? null
                    ) ?: null,

                'UNIT_INSTITUTE' =>
                    $this->clean(
                        $item['data']['tp_name'] ?? null
                    ) ?: null,

                'PROGRAM_STATE' =>
                    $this->clean(
                        $item['data']['state'] ?? null
                    ) ?: null,

                'PROGRAM_DISTRICT' =>
                    $this->clean(
                        $item['data']['district'] ?? null
                    ) ?: null,

                'PROGRAM_CODE' =>
                    $this->programCode,

                'course_completed' =>
                    $this->mapCertified(
                        $item['data']['iscertified'] ?? null
                    ),

                'status' => 'Active',

                'interested_in_opportunities' => 0,

                'no_of_pathway_completed' => 0,

                'no_of_pathway_enrolled' => 0,

                'no_of_applied_jobs' => 0,

                'no_of_applied_courses' => 0,

                'create_date' => now(),
            ];

            /*
            |--------------------------------------------------------------------------
            | Preview
            |--------------------------------------------------------------------------
            */

            if ($this->previewOnly) {

                $this->successCount++;

                $this->rememberIdentifiers(
                    $candidateId,
                    $email,
                    $normalizedMobile
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Add to bulk INSERT
            |--------------------------------------------------------------------------
            */

            $insertBatch[] = $learnerData;

            $insertMeta[] = [
                'row_number' => $rowNumber,
                'data' => $item['data'],
                'candidate_id' => $candidateId,
                'email' => $email,
                'mobile' => $item['mobile'],
                'normalized_mobile' => $normalizedMobile,
            ];

            /*
            |--------------------------------------------------------------------------
            | Mark identifiers immediately.
            |--------------------------------------------------------------------------
            |
            | This prevents duplicates inside the same chunk.
            |
            */

            $this->rememberIdentifiers(
                $candidateId,
                $email,
                $normalizedMobile
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Preview ends here
        |--------------------------------------------------------------------------
        */

        if ($this->previewOnly) {
            return;
        }

        if (empty($insertBatch)) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | BULK INSERT
        |--------------------------------------------------------------------------
        */

        try {

            Learner::query()->insert(
                $insertBatch
            );

            $this->successCount += count(
                $insertBatch
            );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | If bulk insert fails, fallback to row-wise insert
            |--------------------------------------------------------------------------
            |
            | This is only a safety fallback. Normal successful imports
            | will use the fast bulk insert above.
            |
            */

            Log::error(
                'Learner bulk insert failed. Falling back to row inserts.',
                [
                    'program_code' => $this->programCode,
                    'rows' => count($insertBatch),
                    'error' => $e->getMessage(),
                ]
            );

            foreach (
                $insertBatch as $index => $learnerData
            ) {

                $meta = $insertMeta[$index];

                try {

                    Learner::create(
                        $learnerData
                    );

                    $this->successCount++;

                } catch (\Throwable $rowException) {

                    $this->failedCount++;

                    $failure = [
                        'row' => $meta['row_number'],
                    
                        'candidate_id' =>
                            $meta['candidate_id'],
                    
                        'email' =>
                            $meta['email'],
                    
                        'mobile' =>
                            $meta['mobile'],
                    
                        'reason' =>
                            $rowException->getMessage(),
                    
                        'data' =>
                            $meta['data'],
                    ];
                    
                    $this->failedRows[] = $failure;
                    
                    if (
                        !$this->previewOnly &&
                        $this->importLogId
                    ) {
                        $this->pendingFailureRows[] = $failure;
                    }
                }
            }
        }
        $this->savePendingFailures();
    }



    protected function savePendingFailures(): void
    {

        \Log::info('savePendingFailures called', [
            'previewOnly' => $this->previewOnly,
            'importLogId' => $this->importLogId,
            'pendingFailureRowsCount' => count($this->pendingFailureRows),
        ]);

        if (
            $this->previewOnly ||
            !$this->importLogId ||
            empty($this->pendingFailureRows)
        ) {
            return;
        }

        $now = now();

        $batch = [];

        foreach ($this->pendingFailureRows as $failure) {

            $batch[] = [
                'learner_import_id' => $this->importLogId,

                'row_number' =>
                    $failure['row'],

                'candidate_id' =>
                    $failure['candidate_id'] ?: null,

                'email' =>
                    $failure['email'] ?: null,

                'mobile_number' =>
                    $failure['mobile'] ?: null,

                'reason' =>
                    $failure['reason'],

                'row_data' =>
                    json_encode(
                        $failure['data'],
                        JSON_UNESCAPED_UNICODE
                    ),

                'created_at' => $now,

                'updated_at' => $now,
            ];
        }

        LearnerImportFailure::insert($batch);

        $this->pendingFailureRows = [];
    }


    /*
    |--------------------------------------------------------------------------
    | Add duplicate
    |--------------------------------------------------------------------------
    */

    protected function addDuplicate(
        int $rowNumber,
        array $data,
        string $candidateId,
        string $email,
        string $mobile,
        string $reason,
        ?int $learnerId
    ): void {
        $this->duplicateCount++;
    
        $duplicate = [
            'row' => $rowNumber,
            'candidate_id' => $candidateId,
            'email' => $email,
            'mobile' => $mobile,
            'reason' => $reason,
            'learner_id' => $learnerId,
        ];
    
        $this->duplicateRows[] = $duplicate;
    
        if (
            !$this->previewOnly &&
            $this->importLogId
        ) {
            $this->pendingFailureRows[] = [
                'row' => $rowNumber,
                'candidate_id' => $candidateId,
                'email' => $email,
                'mobile' => $mobile,
                'reason' => $reason,
                'data' => $data,
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Remember identifiers
    |--------------------------------------------------------------------------
    */

    protected function rememberIdentifiers(
        string $candidateId,
        string $email,
        ?string $mobile
    ): void {

        if ($candidateId !== '') {
            $this->seenCandidateIds[
                $candidateId
            ] = true;
        }

        if ($email !== '') {

            $this->seenEmails[
                strtolower($email)
            ] = true;
        }

        if ($mobile) {

            $this->seenMobiles[
                $mobile
            ] = true;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Normalize row
    |--------------------------------------------------------------------------
    */

    protected function normalizeRow(array $data): array
    {
        $normalized = [];

        foreach ($data as $key => $value) {

            $key = trim((string) $key);

            $key = strtolower($key);

            $key = str_replace(
                [' ', '-', '.'],
                '_',
                $key
            );

            $normalized[$key] = $value;
        }

        return $normalized;
    }

    /*
    |--------------------------------------------------------------------------
    | Clean value
    |--------------------------------------------------------------------------
    */

    protected function clean($value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_array($value)) {
            return '';
        }

        return trim(
            preg_replace(
                '/\s+/',
                ' ',
                (string) $value
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Normalize mobile
    |--------------------------------------------------------------------------
    */

    protected function normalizeMobile(
        ?string $mobile
    ): ?string {

        if (!$mobile) {
            return null;
        }

        $mobile = preg_replace(
            '/[^0-9]/',
            '',
            $mobile
        );

        if (!$mobile) {
            return null;
        }

        if (
            strlen($mobile) === 12 &&
            str_starts_with($mobile, '91')
        ) {
            $mobile = substr(
                $mobile,
                2
            );
        }

        if (strlen($mobile) > 10) {
            $mobile = substr(
                $mobile,
                -10
            );
        }

        if (strlen($mobile) !== 10) {
            return null;
        }

        return $mobile;
    }

    /*
    |--------------------------------------------------------------------------
    | Gender
    |--------------------------------------------------------------------------
    */

    protected function mapGender($value): string
    {
        $value = $this->clean($value);

        if ($value === '1') {
            return 'Male';
        }

        if ($value === '2') {
            return 'Female';
        }

        $valueLower = strtolower($value);

        if (
            in_array(
                $valueLower,
                ['male', 'm'],
                true
            )
        ) {
            return 'Male';
        }

        if (
            in_array(
                $valueLower,
                ['female', 'f'],
                true
            )
        ) {
            return 'Female';
        }

        return 'Other';
    }

    /*
    |--------------------------------------------------------------------------
    | Certified
    |--------------------------------------------------------------------------
    */

    protected function mapCertified($value): int
    {
        $value = $this->clean($value);

        $valueLower = strtolower($value);

        if (
            in_array(
                $valueLower,
                [
                    '1',
                    'yes',
                    'y',
                    'true',
                    'certified',
                    'completed',
                ],
                true
            )
        ) {
            return 1;
        }

        return 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Failed row
    |--------------------------------------------------------------------------
    */

    protected function failRow(
        int $rowNumber,
        array $data,
        string $reason
    ): void {
    
        $this->failedCount++;
    
        $candidateId = $this->clean(
            $data['candidate_id'] ?? null
        );
    
        $email = $this->clean(
            $data['email'] ?? null
        );

        
    
        $mobile = $this->clean(
            $data['mobile_number'] ?? null
        );
    
        $failure = [
            'row' => $rowNumber,
    
            'candidate_id' => $candidateId,
    
            'email' => $email,
    
            'mobile' => $mobile,
    
            'reason' => $reason,
    
            'data' => $data,
        ];
    
        $this->failedRows[] = $failure;
    
        /*
        |--------------------------------------------------------------------------
        | Save failure immediately to DB in bulk later
        |--------------------------------------------------------------------------
        */
    
        if (
            !$this->previewOnly &&
            $this->importLogId
        ) {
            $this->pendingFailureRows[] = $failure;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Chunk size
    |--------------------------------------------------------------------------
    */

    public function chunkSize(): int
    {
        return 500;
    }

    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */

    public function getTotalRows(): int
    {
        return $this->totalRows;
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function getDuplicateCount(): int
    {
        return $this->duplicateCount;
    }

    public function getFailedCount(): int
    {
        return $this->failedCount;
    }

    public function getFailedRows(): array
    {
        return $this->failedRows;
    }

    public function getDuplicateRows(): array
    {
        return $this->duplicateRows;
    }

    public function getSummary(): array
    {
        return [
            'total' => $this->totalRows,

            'successful' =>
                $this->successCount,

            'duplicates' =>
                $this->duplicateCount,

            'failed' =>
                $this->failedCount,

            'failed_rows' =>
                $this->failedRows,

            'duplicate_rows' =>
                $this->duplicateRows,
        ];
    }
}