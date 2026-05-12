<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventTransactionExport implements FromQuery, WithHeadings
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $request = $this->request;

        $query = DB::connection('mysql2')
            ->table('event_transactions')
            ->leftJoin('learners', 'event_transactions.learner_id', '=', 'learners.id')
            ->leftJoin('yuwaah_event_masters', 'event_transactions.event_category', '=', 'yuwaah_event_masters.id')
            ->leftJoin('yuwaah_event_type', 'yuwaah_event_masters.event_type_id', '=', 'yuwaah_event_type.id')
            ->leftJoin('yuwaah_sakhi', 'event_transactions.ys_id', '=', 'yuwaah_sakhi.id')
            ->leftJoin(DB::raw("
                (
                    SELECT c1.*
                    FROM yuwaah_backend.event_transaction_comments c1
                    INNER JOIN (
                        SELECT event_transaction_id, MAX(id) as max_id
                        FROM yuwaah_backend.event_transaction_comments
                        GROUP BY event_transaction_id
                    ) c2 ON c1.id = c2.max_id
                ) as latest_comments
            "), 'latest_comments.event_transaction_id', '=', 'event_transactions.id')
            ->where('yuwaah_sakhi.csc_id', '!=', 'Sandbox_Testing')
            ->whereNotNull('event_transactions.review_status')
            ->whereNotNull('event_transactions.event_date_submitted')
            ->whereNotNull('event_transactions.learner_id')
            ->select(
                'event_transactions.id',
                'event_transactions.review_status',
                'yuwaah_event_type.name as event_name',
                'yuwaah_event_masters.event_category',
                'event_transactions.beneficiary_name',
                'event_transactions.beneficiary_phone_number',
                'learners.PROGRAM_CODE',
                'learners.PROGRAM_STATE',
                'learners.PROGRAM_DISTRICT',
                'yuwaah_sakhi.name as agent_name',
                'yuwaah_sakhi.sakhi_id',
                'yuwaah_event_masters.document_1',
                'yuwaah_event_masters.document_2',
                'yuwaah_event_masters.document_3',
                'event_transactions.created_at',
                'event_transactions.event_date_submitted',
                'event_transactions.event_value',
                'event_transactions.field_type',
                'event_transactions.comment',
                'event_transactions.updated_at',
                'latest_comments.status as latest_status',
                'latest_comments.comment as latest_comment',
                'latest_comments.comment_type as latest_comment_type'
            )
            ->orderBy('event_transactions.id', 'desc');

        if ($request->filled('submit')) {

            if ($request->filled('status')) {
                $query->where('event_transactions.review_status', $request->status);
            }

            if ($request->filled('event_type') && (int) $request->event_type > 0) {
                $query->where('yuwaah_event_type.id', $request->event_type);
            }

            if ($request->filled('event_category') && (int) $request->event_category > 0) {
                $query->where('event_transactions.event_category', $request->event_category);
            }

            if ($request->filled('from_date') && $request->filled('to_date')) {
                $query->whereBetween('event_transactions.event_date_submitted', [
                    $request->from_date . ' 00:00:00',
                    $request->to_date . ' 23:59:59',
                ]);
            }

            if ($request->filled('submitted_date')) {
                $query->whereDate('event_transactions.event_date_submitted', $request->submitted_date);
            }

            if ($request->filled('benificiery_name')) {
                $query->where(
                    'event_transactions.beneficiary_name',
                    'like',
                    '%' . $request->benificiery_name . '%'
                );
            }

            if ($request->filled('benificiery_mobile')) {
                $query->where(
                    'event_transactions.beneficiary_phone_number',
                    'like',
                    '%' . $request->benificiery_mobile . '%'
                );
            }

            if ($request->filled('program_code')) {
                $query->where('learners.PROGRAM_CODE', $request->program_code);
            }

            if ($request->filled('sakhi_id')) {
                $query->where(
                    'yuwaah_sakhi.sakhi_id',
                    'like',
                    '%' . $request->sakhi_id . '%'
                );
            }

            if ($request->filled('search_text')) {
                $search = $request->search_text;

                $query->where(function ($q) use ($search) {
                    $q->where('event_transactions.beneficiary_name', 'like', '%' . $search . '%')
                        ->orWhere('event_transactions.beneficiary_phone_number', 'like', '%' . $search . '%')
                        ->orWhere('event_transactions.event_value', 'like', '%' . $search . '%')
                        ->orWhere('yuwaah_sakhi.sakhi_id', 'like', '%' . $search . '%')
                        ->orWhere('learners.PROGRAM_CODE', 'like', '%' . $search . '%');
                });
            }
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Event Status',
            'Event Name',
            'Event Category',
            'Beneficiary Name',
            'Number',
            'Program Code',
            'Program State',
            'Program City',
            'Agent Name',
            'Agent ID',
            'Required Document 1',
            'Required Document 2',
            'Required Document 3',
            'Event Created',
            'Event Submitted',
            'Event Value',
            'Field Type',
            'Comment',
            'Last Updated',
            'Latest Status',
            'Latest Comment',
            'Latest Comment Type',
        ];
    }
}