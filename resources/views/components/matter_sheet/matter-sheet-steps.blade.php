@hasanyrole('corporate')
<div class="mx-4 p-4">
    <div class="flex items-center">
        <a href="{{ route('profile.business.home') }}" class="flex items-center {{ Route::current()->getName() == 'profile.business.home' ? 'text-white' : 'text-gray-500'}} relative">
        <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 {{ Route::current()->getName() == 'profile.business.home' ? 'bg-teal-600 border-teal-600' : 'border-gray-300'}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark ">
                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                </svg>
            </div>
            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase {{ Route::current()->getName() == 'profile.business.home' ? 'text-teal-600' : 'text-gray-500'}}">{{ __('Business Details') }}</div>
        </a>
        <div class="flex-auto border-t-2 transition duration-500 ease-in-out {{ Route::current()->getName() == 'profile.business.additional-details' ? 'border-teal-600' : 'border-gray-300' }}"></div>

        <a href="{{ route('profile.business.additional-details') }}" class="flex items-center {{ Route::current()->getName() == 'profile.business.additional-details' ? 'text-white' : 'text-gray-500'}} relative">
            <div class="rounded-full transition duration-500 eaprofile.business.additional-detailsse-in-out h-12 w-12 py-3 border-2 {{ Route::current()->getName() == 'profile.business.additional-details' ? 'bg-teal-600 border-teal-600' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus ">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <line x1="20" y1="8" x2="20" y2="14"></line>
                    <line x1="23" y1="11" x2="17" y2="11"></line>
                </svg>
            </div>
            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase {{ Route::current()->getName() == 'profile.business.additional-details' ? 'text-teal-600' : 'text-gray-500'}}">{{ __('Additional Details') }}</div>
        </a>
        <div class="flex-auto border-t-2 transition duration-500 ease-in-out {{ Route::current()->getName() == 'profile.business.certifications-awards' ? 'border-teal-600' : 'border-gray-300' }}"></div>
        <a href="{{ route('profile.business.certifications-awards') }}" class="flex items-center {{ Route::current()->getName() == 'profile.business.certifications-awards' ? 'text-white' : 'text-gray-500'}} relative">
            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 {{ Route::current()->getName() == 'profile.business.certifications-awards' ? 'bg-teal-600 border-teal-600' : 'border-gray-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail ">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
            </div>
        <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-gray-500">{{ __('Certifcations & Awards') }}</div>
        </a>
        <div class="flex-auto border-t-2 transition duration-500 ease-in-out {{ Route::current()->getName() == 'product.interest.home' ? 'border-teal-600' : 'border-gray-300'}}"></div>
        <a href="{{route('product.interest.home')}}" class="flex items-center {{ Route::current()->getName() == 'product.interest.home' ? 'text-white' : 'text-gray-500'}} relative">
            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 {{ Route::current()->getName() == 'product.interest.home' ? 'bg-teal-600 border-teal-600' : 'border-gray-300'}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database ">
                    <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                    <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                </svg>
            </div>
            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase {{ Route::current()->getName() == 'matter_sheet.home' ? 'text-teal-600' : 'text-gray-500'}}">{{ __('Matter Sheet') }}</div>
        </a>
    </div>
</div>
@endhasanyrole


@hasanyrole('business')
<div class="mx-4 p-4">
    <div class="flex items-center">
        <a href="{{ route('profile.business.home') }}" class="flex items-center {{ Route::current()->getName() == 'profile.business.home' ? 'text-white' : 'text-gray-500'}} relative">
        <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 {{ Route::current()->getName() == 'profile.business.home' ? 'bg-teal-600 border-teal-600' : 'border-gray-300'}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark ">
                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                </svg>
            </div>
            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase {{ Route::current()->getName() == 'profile.business.home' ? 'text-teal-600' : 'text-gray-500'}}">{{ __('Business Details') }}</div>
        </a>



        <div class="flex-auto border-t-2 transition duration-500 ease-in-out {{ Route::current()->getName() == 'product.interest.home' ? 'border-teal-600' : 'border-gray-300'}}"></div>
        <a href="{{route('product.interest.home')}}" class="flex items-center {{ Route::current()->getName() == 'product.interest.home' ? 'text-white' : 'text-gray-500'}} relative">
            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 {{ Route::current()->getName() == 'product.interest.home' ? 'bg-teal-600 border-teal-600' : 'border-gray-300'}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database ">
                    <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                    <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                </svg>
            </div>
            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase {{ Route::current()->getName() == 'product.interest.home' ? 'text-teal-600' : 'text-gray-500'}}">{{ __('Products of Interest') }}</div>
        </a>
    </div>
</div>
@endhasanyrole







