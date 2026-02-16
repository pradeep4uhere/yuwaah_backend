@if(session('success_value'))
    <div class="alert alert-success">
        {{ session('success_value') }}
    </div>
@endif
<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Event Details') }}&nbsp;[#{{$event_transactions->id}}]
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ $event_transactions?->description ?? '' }}
        </p>
    </header>
    <table class="table table-striped table-bordered" >
                    <tbody>
                    <tr style="font-size:13px">
                        <td nowrap="nowrap"><strong>Event Name</strong></td>
                        <td nowrap="nowrap">{{$event_transactions->event_name}}</td>
                        <td nowrap="nowrap"><strong>Event Category</strong></td>
                        <td nowrap="nowrap">{{$event_transactions->event_master_category}}</td>
                    </tr>
                    <tr style="font-size:13px">
                        <td nowrap="nowrap"><strong>Beneficiary Name</strong></td>
                        <td nowrap="nowrap">{{$event_transactions->beneficiary_name}}</td>
                        <td nowrap="nowrap"><strong>Beneficiary Number</strong></td>
                        <td nowrap="nowrap">{{$event_transactions->beneficiary_phone_number}}</td>
                    </tr>
                    <tr style="font-size:13px">
                        <td nowrap="nowrap"><strong>Beneficiary State</strong></td>
                        <td nowrap="nowrap">{{$event_transactions->PROGRAM_STATE}}</td>
                        <td nowrap="nowrap"><strong>Beneficiary District</strong></td>
                        <td nowrap="nowrap">{{$event_transactions->PROGRAM_DISTRICT}}</td>
                    </tr>
                   
                    <tr style="font-size:13px">
                        <td nowrap="nowrap"><strong>Event Value</strong></td>
                        <td nowrap="nowrap">{{$event_transactions->event_value}}</td>
                        <td nowrap="nowrap"><strong>Monthly Salary / Income (INR)</strong></td>
                        <td nowrap="nowrap">{{$event_transactions->event_value}}</td>
                    </tr>
                    <tr style="font-size:13px">
                        <td nowrap="nowrap"><strong>Document Required</strong></td>
                        <td nowrap="nowrap">{{$event_transactions->document_1}}, 
                            {{$event_transactions->document_2}}, 
                        {{$event_transactions->document_3}}</td>
                        <td nowrap="nowrap"><strong>Uploaded Document</strong></td>
                        <td nowrap="nowrap">
                            @php
                                $documents = [];
                                if (!empty($event_transactions->uploaded_doc_links)) {
                                    $decoded = json_decode($event_transactions->uploaded_doc_links, true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                        $documents = $decoded;
                                    } else {
                                        $documents = [$event_transactions->uploaded_doc_links]; // fallback if not JSON
                                    }
                                }
                            @endphp
                            @php
                                $remoteBaseUrl = env('FRONT_END_URL'); // your external file server base URL
                            @endphp
                            @if(count($documents) > 0)
                                @foreach($documents as $key => $doc)
                                    <a href="{{ $remoteBaseUrl . ltrim('storage'.'/'.$doc, '/') }}" target="_blank" class="d-block text-primary">
                                        {{ is_string($key) ? $key : 'Document' }} 📎
                                    </a>
                                @endforeach
                            @else
                                <span class="text-muted">No Documents</span>
                            @endif
                        </td>
                    </tr>
                    <tr style="font-size:13px">
                        <td nowrap="nowrap"><strong>Event Created</strong></td>
                        <td nowrap="nowrap">{{$event_transactions->event_date_created}}</td>
                        <td nowrap="nowrap"><strong>Event Submitted</strong></td>
                        <td nowrap="nowrap">{{$event_transactions->event_date_submitted}} &nbsp; ✅</td>
                    </tr>
                    <tr style="font-size:13px">
                        <td nowrap="nowrap"><strong>Field Agent Name</strong></td>
                        <td nowrap="nowrap">{{$event_transactions->field_agent_name}} [ {{$event_transactions->field_agent_id}} ]</td>
                        <td nowrap="nowrap"><strong>Field Agent Number</strong></td>
                        <td nowrap="nowrap">{{$event_transactions->field_agent_contact}}</td>
                    </tr>
                   
                    <tr style="font-size:13px">
                        <td nowrap="nowrap"><strong>Function Type</strong></td>
                        <td nowrap="nowrap" colspan="0">{{$event_transactions->field_type}}</td>
                        <td nowrap="nowrap"><strong>Industry  Type</strong></td>
                        <td nowrap="nowrap" colspan="0">{{$event_transactions->industry_type}}</td>
                    </tr>
                    <tr style="font-size:13px">
                        <td nowrap="nowrap"><strong>Event Transaction Status</strong></td>
                        <td nowrap="nowrap" 
                            @if($event_transactions->review_status == 'Rejected')
                                style="color: red; font-weight: bold;"
                            @elseif($event_transactions->review_status == 'Accepted')
                                style="color: green; font-weight: bold;"
                            @elseif($event_transactions->review_status == 'Pending')
                                style="color: lightred; font-weight: bold;"
                            @elseif($event_transactions->review_status == '')
                                style="color: black; font-weight: bold;"
                            @endif
                        >
                            {{ ($event_transactions->review_status=='')?'Open': $event_transactions->review_status}} 
                        </td>
                        <td nowrap="nowrap"><strong>Update Monthly Salary / Income (INR)</strong></td>
                        <td nowrap="nowrap">
                            <form action="" method="post">
                                @csrf
                                <input type="number" name="fee_per_completed_transaction" class="primary" value="{{$event_transactions->event_value}}" style="padding:2px"/>
                                <button type="submit" class="btn btn-sm btn-primary">Update Value</button>
                            </form></td>
                    </tr>
                </tbody>
        </table>
</section>
