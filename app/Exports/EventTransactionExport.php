<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\EventTransaction;
use Maatwebsite\Excel\Concerns\WithHeadings;


class EventTransactionExport implements FromCollection, WithHeadings
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $request = $this->request;
        //dd( $request);
        $query = DB::connection('mysql2')
            ->table('event_transactions')
            ->leftJoin('learners', 'event_transactions.learner_id', '=', 'learners.id')
            ->leftJoin('yuwaah_event_masters', 'event_transactions.event_category', '=', 'yuwaah_event_masters.id')
            ->leftJoin('yuwaah_event_type', 'yuwaah_event_masters.event_type_id', '=', 'yuwaah_event_type.id')
            ->leftJoin('yuwaah_sakhi', 'event_transactions.ys_id', '=', 'yuwaah_sakhi.id')
            ->leftJoin(DB::raw('
                (
                    SELECT *
                    FROM yuwaah_backend.event_transaction_comments c1
                    WHERE c1.id = (
                        SELECT MAX(c2.id)
                        FROM yuwaah_backend.event_transaction_comments c2
                        WHERE c2.event_transaction_id = c1.event_transaction_id
                    )
                ) as latest_comments
            '), 'latest_comments.event_transaction_id', '=', 'event_transactions.id')
    
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
                'yuwaah_sakhi.name',      
                'yuwaah_sakhi.sakhi_id',    
                'yuwaah_event_masters.document_1 as document_1',
                'yuwaah_event_masters.document_2 as document_2',
                'yuwaah_event_masters.document_3 as document_3',  
                'event_transactions.created_at',     
                'event_transactions.event_date_submitted',    // Event Created
                'event_transactions.event_value',                       // Event Value
                'event_transactions.field_type',                        // Field Type
                'event_transactions.comment',                           // Comment
                'event_transactions.updated_at',   
                'latest_comments.status as latest_status',
                'latest_comments.comment as latest_comment',
                'latest_comments.comment_type as latest_comment_type',
            )
            ->orderBy('event_transactions.id', 'desc');
    
        /** APPLY FILTERS (CORRECT WAY) */
        if ($request->filled('submit')) {
    
            // STATUS
            if ($request->filled('status')) {
                $query->where('event_transactions.review_status', $request->status);
            }
    
            // EVENT TYPE
            if ($request->event_type > 0) {
                $query->where('yuwaah_event_type.id', $request->event_type);
            }
    
            // EVENT CATEGORY
            if ($request->event_category > 0) {
                $query->where('event_transactions.event_category', $request->event_category);
            }
    
            // DATE RANGE
            if ($request->from_date && $request->to_date) {
                $query->whereBetween('event_transactions.created_at', [
                    $request->from_date,
                    $request->to_date
                ]);
            }
    
            // BENEFICIARY NAME
            if ($request->filled('benificiery_name')) {
                $query->where('event_transactions.beneficiary_name', 'like', "%{$request->benificiery_name}%");
            }
    
            // BENEFICIARY MOBILE
            if ($request->filled('benificiery_mobile')) {
                $query->where('event_transactions.beneficiary_phone_number', 'like', "%{$request->benificiery_mobile}%");
            }
    

            // BENEFICIARY MOBILE
            if ($request->filled('program_code')) {
                $query->where('learners.PROGRAM_CODE', 'like', "%{$request->program_code}%");
            }

            if ($request->filled('sakhi_id')) {
                $query->where('yuwaah_sakhi.sakhi_id', 'like', "%{$request->sakhi_id}%");
            }

            
    
            // GLOBAL SEARCH
            if ($request->filled('search_text')) {
                $search = $request->search_text;
                $query->where(function ($q) use ($search) {
                    $q->where('event_transactions.beneficiary_name', 'like', "%$search%")
                      ->orWhere('event_transactions.beneficiary_phone_number', 'like', "%$search%")
                      ->orWhere('event_transactions.event_value', 'like', "%$search%");
                });
            }
        }
    
        return $query->get();
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
            'Pograme State',
            'Pograme City',
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
            'Latest Comment Type'
        ];
    }
}
