<div id="sideBar"
    class="relative flex flex-col flex-wrap bg-white border-r border-gray-300 p-6 flex-none w-64 md:-ml-64 md:fixed md:top-0 md:z-30 md:h-screen md:shadow-xl animated faster">

    <div class="flex flex-col">
        <div class="text-right hidden md:block mb-4">
            <button id="sideBarHideBtn">
                <i class="fad fa-times-circle"></i>
            </button>
        </div>

        <a class="mb-2" href="javasacript:void(0);"
            class="capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            {{ __('Main Website') }}
        </a>
        @if (Session::get('user_type') == 'seller')
            <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider"> {{ __('Subscriptions') }} </p>
            <a href="{{ route('subscription.home') }}"
                class="capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-list fa-md mr-2"></i>
                {{ __('List') }}
            </a>

            <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">{{ __('Products') }}</p>
            <a href="{{ route('product.home') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-list fa-md mr-2"></i>
                {{ __('List') }}
            </a>
            <a href="{{ route('product.create') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-plus fa-md mr-2"></i>
                {{ __('Add') }}
            </a>

            <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">{{ __('Catalogs') }}</p>
            <a href="{{ route('catalog.home') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-list fa-md mr-2"></i>
                {{ __('List') }}
            </a>
            <a href="{{ route('catalog.create') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-plus fa-md mr-2"></i>
                {{ __('Add') }}
            </a>

            <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">{{ __('Warehouse') }}</p>
            <a href="{{ route('warehouse.home') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-list fa-md mr-2"></i>
                {{ __('List') }}
            </a>
            <a href="{{ route('warehouse.create') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-plus fa-md mr-2"></i>
                {{ __('Add') }}
            </a>
            <a href="{{ route('warehousebookings.invoices') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-list fa-md mr-2"></i>
                {{ __('Booking Invoices') }}
            </a>
            <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">{{ __('Khata') }}</p>
            <a href="{{ route('khata.client.home') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-users fa-md mr-2"></i>
                {{ __('Manage Clients') }}
            </a>
            <a href="{{ route('khata.invoice.home') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-file-invoice-dollar fa-md mr-2"></i>
                {{ __('Manage Invoices') }}
            </a>
            <a href="{{ route('khata.challan.home') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-file-invoice fa-md mr-2"></i>
                {{ __('Manage Delivery Challans') }}
            </a>
            <a href="{{ route('khata.inventory.home') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-file-invoice fa-md mr-2"></i>
                {{ __('Inventory Management') }}
            </a>
            <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">{{ __('Manage Leads') }}</p>
            <a href="{{ route('product-buy-requirement.home') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-list fa-md mr-2"></i>
                {{ __('Buy Leads') }}
            </a>

        @endif

        @if (Session::get('user_type') == 'buyer')

            <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">{{ __('Warehouse') }}</p>
            <a href="{{ route('warehousebookings.index') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-list fa-md mr-2"></i>
                {{ __('Requested Bookings') }}
            </a>
            <a href="{{ route('warehousebookings.invoices') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-list fa-md mr-2"></i>
                {{ __('Booking Invoices') }}
            </a>

            <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">{{ __('Khata') }}</p>
            <a href="{{ route('khata.client.home') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-users fa-md mr-2"></i>
                {{ __('Manage Clients') }}
            </a>
            <a href="{{ route('khata.invoice.home') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-file-invoice-dollar fa-md mr-2"></i>
                {{ __('Manage Invoices') }}
            </a>
            <a href="{{ route('khata.purchase-order.home') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-bags-shopping fa-md mr-2"></i>
                {{ __('Manage Purchase Orders') }}
            </a>
            <a href="{{ route('khata.challan.home') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-file-invoice fa-md mr-2"></i>
                {{ __('View Delivery Challans') }}
            </a>
            <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">{{ __('Manage Leads') }}</p>
            <a href="{{ route('product-buy-requirement.home') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-list fa-md mr-2"></i>
                {{ __('View Buying Requests') }}
            </a>
        @endif

        @hasanyrole('admin|super-admin')

        <p class="uppercase text-xs text-gray-600 mb-4 tracking-wider">{{ __('Users') }}</p>
        <a href="{{ route('admin.users.home') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-list text-xs mr-2"></i>
            {{ __('List') }}
        </a>

        <p class="uppercase text-xs text-gray-600 my-4 tracking-wider">{{ __('Subscriptions') }}</p>
        <a href="{{ route('admin.subscriptions.home') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-list text-xs mr-2"></i>
            {{ __('List') }}
        </a>

        <p class="uppercase text-xs text-gray-600 my-4 tracking-wider">{{ __('Category') }}</p>
        <a href="{{ route('admin.category.home') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-list text-xs mr-2"></i>
            {{ __('List') }}
        </a>
        <a href="{{ route('admin.category.create') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-plus text-xs mr-2"></i>
            {{ __('Add') }}
        </a>
        <a href="{{ route('admin.category.display-order') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-plus text-xs mr-2"></i>
            {{ __('Home Page Display') }}
        </a>

        <p class="uppercase text-xs text-gray-600 my-4 tracking-wider">{{ __('Products') }}</p>
        <a href="{{ route('admin.product.home') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-list text-xs mr-2"></i>
            {{ __('List') }}
        </a>

        <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">{{ __('Warehouse') }}</p>
        <a href="{{ route('admin.warehouse.home') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-list text-xs mr-2"></i>
            {{ __('List') }}
        </a>
        <a href="{{ route('admin.warehousebookings.home') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-list text-xs mr-2"></i>
            {{ __('Booking Requests') }}
        </a>
        <a href="{{ route('admin.warehousebookings.invoices') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-list text-xs mr-2"></i>
            {{ __('Booking Invoices') }}
        </a>

        <p class="uppercase text-xs text-gray-600 my-4 tracking-wider">{{ __('Testimonials') }}</p>
        <a href="{{ route('admin.testimonials.home') }}"
            class="mb-3 capitalize font-medyium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-list text-xs mr-2"></i>
            {{ __('List') }}
        </a>

        <p class="uppercase text-xs text-gray-600 my-4 tracking-wider">{{ __('Advertisments') }}</p>
        <a href="{{ route('admin.advertisments.home') }}"
            class="mb-3 capitalize font-medyium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-list text-xs mr-2"></i>
            {{ __('List') }}
        </a> <a href="{{ route('admin.advertisments.create') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-plus text-xs mr-2"></i>
            {{ __('Add') }}
        </a>
        

        @endhasanyrole

        <p class="mb-2 uppercase text-xs text-gray-600 my-4 tracking-wider"> {{ __('Matter Sheet') }} </p>

        <a href="{{ route('matter-sheet.matter_sheet.listing') }}"
            class="mb-2 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-list fa-md mr-2"></i>
            {{ __('Matter Sheet Listings') }}
        </a>

        <a href="{{ route('matter-sheet.home') }}"
            class="mb-2 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-list fa-md mr-2"></i>
            {{ __('Details') }}
        </a>
        @if(is_super_admin())
            <a href="{{ route('matter-sheet.additionalDetails') }}"
                class="mb-2 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-list fa-md mr-2"></i>
                {{ __('Additional Details') }}
            </a>
        @endif
        

        <a href="{{ route('matter-sheet.company.setting.page') }}"
            class="mb-2 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-list fa-md mr-2"></i>
            {{ __('Company Page Settings') }}
        </a>
        @if(is_super_admin())
            <a href="{{ route('matter-sheet.certificateAndAwards') }}"
                class="mb-2 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-list fa-md mr-2"></i>
                {{ __('Certificate & Award') }}
            </a>
        @endif

        <a href="{{ route('matter-sheet.directorProfile') }}"
            class="mb-2 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-list fa-md mr-2"></i>
            {{ __('Director Profile') }}
        </a>

        <a href="{{ route('matter-sheet.productUpload') }}"
            class="mb-2 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-list fa-md mr-2"></i>
            {{ __('Upload Product') }}
        </a>

        

        <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">{{ __('Logistics') }}</p>
        @if(is_super_admin())
            <a href="{{ route('admin.logistics.drivers.index') }}"
                class="mb-3 capitalize font-medyium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-list text-xs mr-2"></i>
                {{ __('Drivers') }}
            </a>
        @endif
        <a href="{{ route('logistics.booking_request.index') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-list fa-md mr-2"></i>
            {{ __('Your Booking Requests') }}
        </a>
        <a href="{{ route('logistics.booking_request.create') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-list fa-md mr-2"></i>
            {{ __('Request for shipping') }}
        </a>

        @if (Session::get('user_type') == 'driver')
            <a href="{{ route('logistics.drivers.about') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-list fa-md mr-2"></i>
                {{ __('About and Vehicle') }}
            </a>
            
            <a href="{{ route('logistics.drivers.available_rides') }}"
                class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
                <i class="fad fa-list fa-md mr-2"></i>
                {{ __('Available Rides') }}
            </a>
        @endif

    </div>
</div>
