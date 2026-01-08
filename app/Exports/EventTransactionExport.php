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
    
        $query = DB::connection('mysql2')
            ->table('event_transactions')
            ->leftJoin('yuwaah_event_masters', 'event_transactions.event_category', '=', 'yuwaah_event_masters.id')
            ->leftJoin('yuwaah_event_type', 'yuwaah_event_masters.event_type_id', '=', 'yuwaah_event_type.id')
            ->select(
                'event_transactions.id',                              // ID
                'event_transactions.review_status',                    // Event Status
                'yuwaah_event_type.name as event_name',                 // Event Name
                'yuwaah_event_masters.event_category',                  // Event Category
                'event_transactions.field_type',                        // Field Type
                'event_transactions.beneficiary_phone_number',          // Number
                'event_transactions.beneficiary_name',                  // Beneficiary Name
                'yuwaah_event_masters.date_event_created_in_master',    // Event Created
                'event_transactions.created_at',                        // Event Submitted
                'event_transactions.event_value',                       // Event Value
                'yuwaah_event_masters.document_1 as document_1',
                'yuwaah_event_masters.document_2 as document_2',
                'yuwaah_event_masters.document_3 as document_3',
                'event_transactions.comment',                           // Comment
                'event_transactions.updated_at',                        // Last Updated
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
            'Field Type',
            'Number',
            'Beneficiary Name',
            'Event Created',
            'Event Submitted',
            'Event Value',
            'Required Document 1',
            'Required Document 2',
            'Required Document 3',
            'Comment',
            'Last Updated',
        ];
    }
}
