@extends('layouts.master')

@section('page')
    @parent
    <div class="card col-span-2 xl:col-span-1">
        <div class="card-header">{{ __('Subscribed Notifications List') }}</div>
        <div class="bg-white pb-4 px-4 rounded-md w-full">
            <div class="overflow-x-auto mt-6" id="categories">
                <table class="table-auto border-collapse w-full">
                    <thead>
                        <tr class="rounded-lg text-sm font-medium text-gray-700 text-left" style="font-size: 0.9674rem">
                        <th class="px-4 py-2 bg-gray-200 " style="background-color:#f8f8f8">{{ __('SR. #') }}</th>
                        <th class="px-4 py-2" style="background-color:#f8f8f8">{{ __('Notification Type') }}</th>
                        <th class="px-4 py-2" style="background-color:#f8f8f8">{{ __('Subscribed') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-normal text-gray-700 search_results">
                        @foreach ($notification_types as $notification_type)
                        <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                            <td class="px-4 py-4">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4">{{ $notification_type->title }}</td>
                            <td class="px-4 py-4">
                                @if(!empty($subscribed_notification_ids) && in_array($notification_type->id, $subscribed_notification_ids))
                                    <input class="shadow-inner rounded-md py-3 px-4 leading-tight is_subscribed" id="is_subscribed_{{$notification_type->id}}" type="checkbox" checked data-type-id="{{$notification_type->id}}">
                                @else
                                    <input class="shadow-inner rounded-md py-3 px-4 leading-tight is_subscribed" id="is_subscribed_{{$notification_type->id}}" type="checkbox" data-type-id="{{$notification_type->id}}">
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>

@endsection

@push('scripts')
    <script>
        var base_url = '{{ config('app.url') }}';
    </script>
    <script type="text/javascript" src="{{ asset(('js/pages/notifications_subscription.js')) }}"></script>
@endpush
