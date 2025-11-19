<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Event Transaction') }}
        </h2>
    </x-slot>
    <div class="py-12">
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
                        <th scope="col">Required Docs</th>
                        <th scope="col">Number</th>
                        <th scope="col">Beneficiary Name</th>
                        <th scope="col" nowrap="nowrap">Event Created</th>
                        <th scope="col" nowrap="nowrap">Event Submitted</th>
                        <th scope="col" nowrap="nowrap">Event Value</th>
                        <th scope="col">Document</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Created</th>
                        <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if($event_transactions){ foreach($event_transactions as $item){ ?>
                    <tr style="font-size:13px">
                        <th scope="row">{{$item->id}}</th>
                        <td nowrap="nowrap" style="font-weight: bold; color: {{ $item->review_status == 'Accepted' ? 'green' : 'red' }}">
                        {{$item->review_status}}</td>
                        <td nowrap="nowrap">{{$item->event_name}}</td>
                        <td nowrap="nowrap">{{$item->event_master_category}}</td>
                        <td nowrap="nowrap">{{$item->document_1}}<br>{{$item->document_2}}<br>{{$item->document_3}}</td>
                        <td nowrap="nowrap">{{$item->beneficiary_phone_number}}</td>
                        <td nowrap="nowrap">{{$item->beneficiary_name}}</td>
                        <td nowrap="nowrap">{{$item->event_date_created}}</td>
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
                    {{ $event_transactions->links() }}
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
