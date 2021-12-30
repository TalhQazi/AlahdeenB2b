<div class="overflow-x-auto mt-6" id="users">
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                <th class="px-4 py-2 bg-gray-200 " style="background-color:#f8f8f8">{{ __('ID') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Buyer Name') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Plan') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Amount') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Payment Method') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Status') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Subscription Date') }}</th>
                <th class="px-4 py-2 " style="background-color:#f8f8f8">{{ __('Controls') }}</th>
            </tr>
        </thead>
        <tbody class="text-sm font-normal text-gray-700 search_results">
            @foreach ($subscription_orders->data as $subscription)
            <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                <td class="px-4 py-4">{{$subscription->id}}</td>
                <td class="px-4 py-4">{{$subscription->user->name}}</td>
                <td class="px-4 py-4">{{$subscription->plan->name}}</td>
                <td class="px-4 py-4">{{$subscription->total_amount}}</td>
                <td class="px-4 py-4">{{$subscription->payment_method->name}}</td>
                <td class="px-4 py-4">{{$subscription->status}}</td>
                <td class="px-4 py-4">{{$subscription->created_at}}</td>
                <td class="px-4 py-4">
                    <?php
                        $editRoute = route('admin.subscriptions.payment_status.update', ['subscription_order' => $subscription->id]);
                        $editTitle = __("Update Payment Status");

                    ?>

                    <span title="{{$editTitle}}" data-user-id="{{ $subscription->user->id }}" data-subs-id="{{ $subscription->id }}" class="mx-0.5 edit_payment_status" data-target-url="{{$editRoute}}" data-amount="{{$subscription->total_amount}}" data-status="{{$subscription->status}}">
                        <i class="fa fa-pencil mx-0.5"></i>
                    </span>
                </td>
            </tr>


            @endforeach
        </tbody>
    </table>
    <div id="pagination" class="w-full flex justify-center border-t border-gray-100 pt-4 items-center">
        {{$paginator}}
    </div>
</div>
