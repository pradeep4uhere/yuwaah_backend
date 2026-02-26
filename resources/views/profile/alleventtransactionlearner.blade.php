<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Event Transaction') }}&nbsp;[{{$pagination->total()}}]
        </h2>
    </x-slot>
   <style>
    .form-control {
        width: 100%;
        padding: 8px 12px;
        font-size: 14px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        transition: all 0.2s ease-in-out;
        box-shadow: none;
    }

    .form-control:focus {
        border-color: #0d6efd;
        outline: none;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
    }
    </style>
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
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Return</option>
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
                        <th scope="col"><input type="date"  name="from_date" class="form-control" value="{{ request('from_date') }}" /></th>
                        <th scope="col"><input type="date" class="form-control" name="to_date" class="form-control" value="{{ request('to_date') }}" /></th>
                        <th scope="col"><input type="text" class="form-control" name="benificiery_name" class="form-control" value="{{ request('benificiery_name') }}"/></th>
                        <th scope="col" nowrap="nowrap"><input type="text" class="form-control" name="benificiery_mobile" value="{{ request('benificiery_mobile') }}" /></th>
                       
                        </tr>
                        <tr>
                        <th scope="col">Event Submitted Date</th>
                        <th scope="col">Agent ID</th>
                        <th scope="col">Program Code</th>
                        <th scope="col">Event Number</th>
                        <th scope="col">&nbsp;</th>
                        <th scope="col">&nbsp;</th>
                        <th scope="col" nowrap="nowrap">&nbsp;</th>
                        <th scope="col">&nbsp;</th>
                        <th scope="col">&nbsp;</th>
                        </tr>
                        <tr>
                        <td scope="col" nowrap="nowrap"><input type="date" class="form-control" name="submitted_date" value="{{ request('submitted_date') }}"/></td>
                        <td scope="col"><input  class="form-control" type="text" name="sakhi_id"  value="{{ request('sakhi_id') }}"/></td>
                        <td scope="col">
                            <select name="program_code" class="form-control">
                                <option value="">All Program Code</option>
                                @foreach($programCode as $item)
                                <option value="{{ $item->name }}"
                                    @selected(request('program_code') == $item->name)>
                                    {{ $item->name }}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td scope="col"><input  class="form-control" type="text" name="id"  value="{{ request('id') }}"/></td>
                        <td>&nbsp;</td>
                        <td scope="col" nowrap="nowrap"><input type="submit" name="submit" class="btn  btn-primary" value="Search"/>
                         &nbsp;<a href="{{ url()->current() }}" class="btn btn-danger">Clear Filter</a></td>
                        <td scope="col">
                            <!-- <a href="{{ route('export-event-transactions',request()->query()) }}" class="btn " style="display:flex;align-items:center;">
                                <img src="{{ asset('download.png') }}" height="25" width="25" style="margin-right:8px;" />
                                Export Event Transaction
                            </a> -->
                        </td>  

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
                    <?php //dd($learners);
                    foreach($learners as $learner) {  
                        //dd($learnerItem->events) ?>
                    <div style="margin-bottom:12px; width:1978px; padding:10px; border:1px solid #ddd; border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);">
                    <div style="font-weight:bold; font-size:15px;">
                        {{ $learner->first_name }} {{ $learner->last_name }}
                        ({{ $learner->events->count() }} events)
                    </div>
                    <table class="table" style="width:  font-size: 14px; white-space: nowrap;">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Event Status</th>
                        <th scope="col">Program Code</th>
                        <th scope="col">Event Name</th>
                        <th scope="col">Event Category</th>
                        <th scope="col">Field Agent</th>
                        <th scope="col">Field Agent ID</th>
                        <th scope="col">Required Docs</th>
                      
                        <th scope="col">State</th>
                        <th scope="col">District</th>
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
                    @forelse($learner->events as $item)
                        <?php //dd($item); ?>
                        @php 
                            // Status Badge Color
                            $status = $item->review_status;
                            $statusClass = match($status) {
                                'Accepted' => 'text-success fw-bold',
                                'Rejected' => 'text-danger fw-bold',
                                'Pending'  => 'text-warning fw-bold',
                                default    => 'text-dark fw-bold'
                            };

                            // Submitted Color
                            $submittedClass = $item->event_date_submitted ? 'text-success fw-bold' : 'text-danger fw-bold';

                            // Decode Documents
                            $documents = [];
                            if (!empty($item->uploaded_doc_links)) {
                                $decoded = json_decode($item->uploaded_doc_links, true);
                                $documents = is_array($decoded) ? $decoded : [$item->uploaded_doc_links];
                            }

                            $remoteBaseUrl = env('FRONT_END_URL');
                        @endphp

                        <tr class="align-middle small">

                            <!-- ID -->
                            <th scope="row">
                                <a href="{{ route('event.edit',['id'=>$item->id]) }}">
                                    {{ $item->id }}
                                </a>
                            </th>

                            <!-- Status -->
                            <td class="{{ $statusClass }}">
                                {{ $status == 'Pending' ? 'Return' : $status }}
                            </td>

                            <td>{{ $item->PROGRAM_CODE }}</td>
                            <td>{{ $item->event_name }}</td>
                            <td>{{ $item->event_category }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->sakhi_id }}</td>

                            <!-- Required Documents -->
                            <td>
                                {{ $item->document_1 }}<br>
                                {{ $item->document_2 }}<br>
                                {{ $item->document_3 }}
                            </td>

                            <td>{{ $item->PROGRAM_STATE }}</td>
                            <td>{{ $item->PROGRAM_DISTRICT }}</td>
                            <td>{{ $item->updated_at }}</td>

                            <!-- Submitted Date -->
                            <td class="{{ $submittedClass }}">
                                {{ $item->event_date_submitted ?: 'NA' }}
                            </td>

                            <td>₹ {{ number_format($item->event_value, 2) }}</td>

                            <!-- Uploaded Documents -->
                            <td>
                                @if(count($documents))
                                    @foreach($documents as $doc)
                                        <a href="{{ $remoteBaseUrl . 'storage/' . $doc }}"
                                        target="_blank"
                                        class="d-block text-primary">
                                            📎 View
                                        </a>
                                    @endforeach
                                @else
                                    <span class="text-muted">No Documents</span>
                                @endif
                            </td>

                            <td>{{ $item->comment }}</td>
                            <td>{{ $item->created_at }}</td>

                            <!-- Action -->
                             <?php //dd($learner);?>
                            <td>
                                <a href="{{ route('event.edit',['id'=>$item->id]) }}"
                                class="btn btn-sm btn-primary">
                                View Event
                                </a>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="20" class="text-center text-muted">
                                No Records Found
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                    </table>
                    </div>
                    <?php } ?>
                    {{ $pagination->appends(request()->query())->links() }}
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
