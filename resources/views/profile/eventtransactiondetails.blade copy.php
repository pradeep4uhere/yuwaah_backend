<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 ">
            {{ __('Event Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-12xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-12xl">
                    @include('profile.partials.event-details')
                </div>
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-12xl">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form action="{{route('event.savecomment')}}" method="post">
                @csrf
                <table class="table table-striped table-bordered" >
                    <tbody>
                   
                    <tr style="font-size:13px">
                       
                        <td nowrap="nowrap">
                        <label><strong>Enter Comment</strong></label>
                        <input type="text" name="comment" class="form-control form-control-sm" placeholder="Enter comment">
                        <input type="hidden" name="event_transaction_id" value="{{$event_transaction_id}}"/>
                        <input type="hidden" name="user_id" value="{{$event_transaction_id}}"/>
                        <input type="hidden" name="user_name" value="{{Auth::user()->name}}"/>
                        <input type="hidden" name="agent_id" value="{{$event_transactions->ys_id}}"/>
                        </td>
                        <td nowrap="nowrap" align="left" >
                            <label><strong>Status</strong></label>
                            <select name="review_status" id="review_status"  class="form-select form-select-sm">
                                <option value="Open">Open</option>
                                <option value="Return">Return</option>
                                <option value="Rejected">Rejected</option>
                                <option value="Accepted">Accepted</option>
                            </select>
                            
                        </td>
                        <td>
                        <label><strong>Comment Type</strong></label>
                        <select name="comment_type"  class="form-select form-select-sm">
                                <option value="internal">Internal</option>
                                <option value="external">External</option>
                            </select>
                        </td>
                        <td>
                        <label><strong>Function Type</strong></label>
                        <select name="field_type" class="form-control" id="function_type">
                            <option value="">Select Function</option>
                            @foreach(($functions ?? []) as $fn)
                                <option value="{{ $fn }}" @if($event_transactions->field_type == $fn) selected="selected" @endif>{{ $fn }}</option>
                            @endforeach
                        </select>
                        </td>
                        <td>
                        <label><strong> Industry Type</strong></label>
                        <select name="industry_type" class="form-control" id="industry_type">
                        <option value="">Select Industry</option>
                            @foreach($industries as $industry)
                                <option value="{{ $industry }}" @if($event_transactions->industry_type == $industry) selected="selected" @endif>{{ $industry }}</option>
                            @endforeach
                        </select>

                        </td>
                        <td nowrap="nowrap" class="align-middle">
                            <label><strong>&nbsp;&nbsp;&nbsp;</strong></label>
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </td>
                        </tr>
                    </table>
                    <h2 class="text-lg font-medium text-gray-900">
                        All comments&nbsp;
                    </h2>
                    <table class="table table-striped table-bordered" >
                    <tbody>
                    @if($commentList)
                    <tr style="font-size:13px">
                       <td nowrap="nowrap" align="left" width="1%"><strong>{{'SN'}}</strong></td>
                       <td nowrap="nowrap" align="left" width="5%"><strong>Status</strong></td>
                        <td nowrap="nowrap" align="left" width="5%"><strong>Comment Type</strong></td>
                        <td nowrap="nowrap" class="align-middle" colspan="3"><strong>Comment</strong></td>
                        <td nowrap="nowrap" align="right" width="5%" ><strong>Comment By</strong></td>
                        <td nowrap="nowrap" align="right" width="5%"><strong>Last Updated</strong></td>
                    </tr>
                    <?php $count=1; ?>
                    @foreach($commentList as $item)
                        <tr style="font-size:13px; background-color: {{ $item['comment_type'] == 'agent' ? '#FFD6D6' : 'transparent' }}">
                            <td nowrap="nowrap" align="left" width="1%"><strong>{{$count}}</strong></td>
                            <td nowrap="nowrap">{{ $item['status'] }}</td>
                            <td nowrap="nowrap">{{ $item['comment_type'] }}</td>
                            <td nowrap="nowrap"   colspan="3">{{ $item['comment'] }}</td>
                            <td nowrap="nowrap"  align="right">{{ $item['comment_type'] == 'agent' ? 'Agent- ' : '' }}{{ $item['user_name'] }}</td>
                            <td nowrap="nowrap" align="right">{{ $item['created_at'] }}</td>
                        </tr>
                    <?php $count++;?>
                    @endforeach
                    @endif
                </tbody>
                </table>
                </form>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
<script>
$("#review_status").on("change", function () {
let status = $(this).val();
let eventName = "{{ $event_transactions->event_name }}";

$("#function_type").hide().prop("disabled", true);
$("#industry_type").hide().prop("disabled", true);

if (status === "Accepted") {
    if (eventName === "Social Protection") {
        $("#industry_type").show().prop("disabled", false);
    } else {
        $("#function_type").show().prop("disabled", false);
    }
}
});
</script>
@endsection
</x-app-layout>
