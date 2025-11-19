<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Learner') }}&nbsp;[{{$user->total()}}]
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-12xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <!-- Assuming Bootstrap is already included in your layout -->
                    <table class="table  " style="width:100%; font-size:14px;">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Mobile</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Date Of Birth</th>
                        <th scope="col" nowrap="nowrap">Course Complition</th>
                        <th scope="col">Status</th>
                        <th scope="col">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($user){ foreach($user as $item){  //echo "<pre>"; print_r($item) ?>
                        <tr>
                        <th scope="row">{{$item['id']}}</th>
                        <td nowrap="nowrap">{{$item['first_name']}}</td>
                        <td nowrap="nowrap">{{$item['email']}}</td>
                        <td nowrap="nowrap">{{$item['primary_phone_number']}}</td>
                        <td nowrap="nowrap">{{$item['gender']}}</td>
                        <td nowrap="nowrap">{{$item['date_of_birth']}}</td>
                        <td nowrap="nowrap">&nbsp;</td>
                        <td nowrap="nowrap">{{($item['status']=='active')?'Active':'InActive'}}</td>
                        <td nowrap="nowrap">{{$item['created_at']}}</td>
                        </tr>
                        <?php }}?>

                        <!-- More rows -->
                    </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
