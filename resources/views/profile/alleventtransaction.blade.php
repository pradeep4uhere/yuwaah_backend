<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Event Transaction') }}
        </h2>
    </x-slot>
    <div class="py-4">
        <div class="max-w-12xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-12xl">
                <h2 class="font-bold text-xl text-gray-900">
                        {{ __('Event Transaction filter') }}&nbsp;
                    </h2>
                 <div style="overflow-x: auto; margin-top:12px;">
                
                    <form action="" method="get">
                    @csrf
                    <table class="table" style=" width: 100%; font-size: 15px; white-space: nowrap;">
                        <tr>
                        <th scope="col">Status</th>
                        <th scope="col">Event Type</th>
                        <th scope="col">Event Category</th>
                        <th scope="col">Submitted Date From</th>
                        <th scope="col">Submitted Date To</th>
                        <th scope="col">Beneficiary Name</th>
                        <th scope="col" nowrap="nowrap">Beneficiary Number</th>
                       
                       
                        <th scope="col">&nbsp;</th>
                        <th scope="col">&nbsp;</th>
                        </tr>
                      
                    <tr>
                        <th scope="col">
                        <select class="form-control" name="status" style=" width: 100px; font-size: 15px; white-space: nowrap;">
                            <option value="">All</option>
                            <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Accepted" {{ request('status') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                        </select>
                        </th>
                        <th scope="col">
                        <select class="form-control" name="event_type" id="event_type" style=" width: 150px; font-size: 15px; white-space: nowrap;">
                            <option value="">All</option>
                            @foreach($eventTypeArray as $item)
                                <option value="{{ $item->id }}" {{ request('event_type') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                        </th>
                        <th scope="col">
                        <select class="form-control" name="event_category" id="event_category" style="min-width:285px">
                            <option value="">All</option>
                            @foreach($eventCategoryArray ?? [] as $cat)
                                <option value="{{ $cat->id }}" {{ request('event_category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->event_category }}
                                </option>
                            @endforeach
                        </select>
                        </th>
                        <th scope="col"><input type="date" name="from_date" class="form-control"
                        value="{{ request('from_date') }}" /></th>
                        <th scope="col"><input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" /></th>
                        <th scope="col"><input type="text" name="benificiery_name" class="form-control" value="{{ request('benificiery_name') }}"/></th>
                        <th scope="col" nowrap="nowrap"><input type="text" name="benificiery_mobile" value="{{ request('benificiery_mobile') }}" /></th>
                       
                        </tr>
                        <tr>
                        <th scope="col" nowrap="nowrap">Event Submitted Date</th>
                        <th scope="col" nowrap="nowrap"><input type="date" name="submitted_date" value="{{ request('submitted_date') }}"/></th>
                        <th scope="col" nowrap="nowrap"><input type="submit" name="submit" class="btn  btn-primary" value="Search"/>&nbsp;
                        <a href="{{ url()->current() }}" class="btn btn-danger">Clear Filter</a></th>
                        <th coslapn="4">
                        <div>
                            <a href="{{ route('export-event-transactions') }}" class="btn " style="display:flex;align-items:center;">
                                <img src="{{ asset('download.png') }}" height="25" width="25" style="margin-right:8px;" />
                                Export Event Transaction
                            </a>
                        </div>
                        </th>    
                    </tr>
                    </table>
                    </form>
                   
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="py-2">
        <div class="max-w-12xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-12xl">
                 <div style="overflow-x: auto;">
                    <table class="table" style="width: 100%; font-size: 14px; white-space: nowrap;">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Event Status</th>
                        <th scope="col">Event Name</th>
                        <th scope="col">Event Category</th>
                        <th scope="col">Field Agent</th>
                        <th scope="col">Field Agent ID</th>
                        <th scope="col">Function Type</th>
                        <th scope="col">Industry Type</th>
                        <th scope="col">Required Docs</th>
                        <th scope="col">Number</th>
                        <th scope="col">Beneficiary Name</th>
                        <th scope="col">Beneficiary State</th>
                        <th scope="col">Beneficiary District</th>
                        <th scope="col" nowrap="nowrap">Event Created</th>
                        <th scope="col" nowrap="nowrap">Event Submitted</th>
                        <th scope="col" nowrap="nowrap">Event Value</th>
                        <th scope="col">Document</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Last Updated</th>
                        <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if($event_transactions){ foreach($event_transactions as $item){  if($item->review_status==''){ continue; }?>
                    
                    <tr style="font-size:13px">
                        <th scope="row"><a href="{{route('event.edit',['id'=>$item->id])}}" class="">{{$item->id}}</a></th>
                        <td nowrap="nowrap" style="font-weight: bold; color: {{ $item->review_status == 'Accepted' ? 'green' : 'red' }}">
                        {{$item->review_status}}</td>
                        <td nowrap="nowrap">{{$item->event_name}}</td>
                        <td nowrap="nowrap">{{$item->event_master_category}}</td>
                        <td nowrap="nowrap">{{$item->field_agent_name}}</td>
                        <td nowrap="nowrap">{{$item->sakhi_id}}</td>
                        <td nowrap="nowrap">{{$item->field_type}}</td>
                        <td nowrap="nowrap">{{$item->industry_type}}</td>
                        <td nowrap="nowrap">{{$item->document_1}}<br>{{$item->document_2}}<br>{{$item->document_3}}</td>
                        <td nowrap="nowrap">{{$item->beneficiary_phone_number}}</td>
                        <td nowrap="nowrap">{{$item->beneficiary_name}}</td>
                        <td nowrap="nowrap">{{$item->PROGRAM_STATE}}</td>
                        <td nowrap="nowrap">{{$item->PROGRAM_DISTRICT}}</td>
                        <td nowrap="nowrap">{{$item->updated_at}}</td>
                        <td nowrap="nowrap" style="font-weight: bold; color: {{ $item->event_date_submitted != '' ? 'green' : 'red' }}">
                            {{ $item->event_date_submitted != '' ? $item->event_date_submitted : 'NA' }}
                        </td>
                        <td nowrap="nowrap">{{$item->event_value}}</td>
                        <td nowrap="nowrap" >
                        @php
                            $documents = [];
                            if (!empty($item->uploaded_doc_links)) {
                                $decoded = json_decode($item->uploaded_doc_links, true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                    $documents = $decoded;
                                } else {
                                    $documents = [$item->uploaded_doc_links]; // fallback if not JSON
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
                        <td nowrap="nowrap">{{$item->comment}}</td>
                        <td nowrap="nowrap">{{$item->created_at}}</td>
                        <td nowrap="nowrap">
                            <a href="{{route('event.edit',['id'=>$item->id])}}" class="btn btn-sm btn-primary">View Event</a>
                            <!-- <a href="#" class="btn btn-sm btn-danger">Delete</a> -->
                        </td>
                    </tr>
                    <?php }} ?>
                </tbody>

                    </table>
                    {{ $event_transactions->appends(request()->query())->links() }}
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
