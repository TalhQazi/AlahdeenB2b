const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/pages/registration.js', 'public/js/pages')
    .js('resources/js/add_product.js', 'public/js/add_products.js')
    .js('resources/js/show_more_images.js', 'public/js')
    .js('resources/js/admin_dashboard.js','public/js/admin_dashboard.js')
    .js('resources/js/category_index.js','public/js')
    .js('resources/js/product_edit.js','public/js')
    .js('resources/js/product_documents.js','public/js')
    .js('resources/js/product_interests.js','public/js')
    .js('resources/js/product_services.js','public/js')
    .js('resources/js/business_additional_details.js','public/js')
    .js('resources/js/business_certification.js','public/js')
    .js('resources/js/pages/business_profile.js', 'public/js/pages')
    .js('resources/js/pages/create_category.js', 'public/js/pages')
    .js('resources/js/pages/products_index.js', 'public/js/pages')
    .js('resources/js/pages/users_index.js', 'public/js/pages')
    .js('resources/js/pages/admin_subscriptions_index.js', 'public/js/pages')
    .js('resources/js/pages/testimonial_index.js', 'public/js/pages')
    .js('resources/js/pages/warehouse_add.js', 'public/js/pages')
    .js('resources/js/pages/warehouse_index.js', 'public/js/pages')
    .js('resources/js/pages/warehouse_booking.js', 'public/js/pages')
    .js('resources/js/pages/admin_warehouse_booking_index.js', 'public/js/pages')
    .js('resources/js/pages/warehouse_booking_agreement.js', 'public/js/pages')
    .js('resources/js/pages/booking_agreement_view.js', 'public/js/pages')
    .js('resources/js/pages/admin_booking_invoice.js', 'public/js/pages')
    .js('resources/js/pages/warehouse_booking_index.js', 'public/js/pages')
    .js('resources/js/pages/booking_invoice_index.js', 'public/js/pages')
    .js('resources/js/pages/product_buy_req_index.js', 'public/js/pages')
    .js('resources/js/pages/lead_quotation.js', 'public/js/pages')
    .js('resources/js/pages/conversation.js', 'public/js/pages')
    .js('resources/js/components/quotation.js', 'public/js/components')
    .js('resources/js/pages/advertisment_index.js','public/js')
    .js('resources/js/pages/ads_add_edit.js','public/js/pages')
    .js('resources/js/pages/category_display.js','public/js/pages')
    .scripts('resources/js/components/ajax_search.js', 'public/js/components/ajax_search.js')
    .scripts('resources/js/components/image_reader.js', 'public/js/components/image_reader.js')
    .scripts('resources/js/components/image_upload.js', 'public/js/components/image_upload.js')
    .js('resources/js/pages/khata/manage_clients.js', 'public/js/pages')
    .js('resources/js/pages/khata/manage_invoices.js', 'public/js/pages')
    .copy('node_modules/intl-tel-input/build/js/utils.js', 'public/js/utils')
    .js('resources/js/components/chat_notifications.js', 'public/js/components')
    .js('resources/js/pages/khata/manage_challan.js', 'public/js/pages')
    .js('resources/js/pages/khata/manage_purchase_order.js', 'public/js/pages')
    .js('resources/js/pages/admin_product_index.js', 'public/js/pages')
    .js('resources/js/pages/become_a_seller.js', 'public/js/pages')
    .js('resources/js/pages/dashboard.js', 'public/js/pages')
    .js('resources/js/pages/director_profile.js', 'public/js/pages')
    .js('resources/js/pages/khata/inventory/definition.js', 'public/js/pages')
    .js('resources/js/pages/khata/inventory/definition_index.js', 'public/js/pages')
    .js('resources/js/pages/khata/inventory/pricing.js', 'public/js/pages')
    .js('resources/js/pages/khata/inventory/pricing_index.js', 'public/js/pages')
    .js('resources/js/pages/khata/inventory/stock_index.js', 'public/js/pages')
    .js('resources/js/pages/notifications_subscription.js', 'public/js/pages')
    .js('resources/js/pages/company_profile_settings.js', 'public/js/pages')
    .js('resources/js/pages/mattersheet-product.js', 'public/js/pages')
    .js('resources/js/pages/upload_matter_sheet_product.js', 'public/js/pages')
    .js('resources/js/pages/purchase_return.js', 'public/js/pages')
    .js('resources/js/pages/logistics/booking_request.js', 'public/js/pages')
    .scripts('resources/js/pages/maps/maps_comp.js', 'public/js/pages/maps_comp.js')
    .js('resources/js/pages/logistics/driver_info.js', 'public/js/pages')
    .js('resources/js/pages/logistics/city_bid.js', 'public/js/pages')
    .sass('resources/sass/app.scss', 'public/css').options({
        processCssUrls: false,
        postCss: [ tailwindcss('tailwind.config.js') ],
    })
    .styles('resources/css/admin_dashboard.css', 'public/css/admin_dashboard.css')
    .styles('resources/css/add_product.css', 'public/css/add_products.css')
    .styles('resources/css/product_of_interest.css', 'public/css/product_of_interest.css')
    .styles('resources/css/auto_complete.css', 'public/css/auto_complete.css')
    .styles('resources/css/maps_comp.css', 'public/css/maps_comp.css')
    .sass('resources/sass/pages/registration.scss', 'public/css/pages/')
    .styles('resources/css/admin_dashboard.css', 'public/css/admin_dashboard.css')
    .styles('resources/css/add_product.css', 'public/css/add_products.css')
    .sass('resources/css/common/flags.scss', 'public/css/common/')
    .sass('resources/css/common/validation_error.scss', 'public/css/common/')
    .sass('resources/css/warehouse/warehouse_add.scss', 'public/css/pages/')
    .sass('resources/sass/pages/warehouse_booking.scss', 'public/css/pages/')
    .sass('resources/sass/pages/lead_quotation.scss', 'public/css/pages/')
    .sass('resources/sass/pages/chat.scss', 'public/css/pages/')
    .sass('resources/sass/pages/khata/manage_clients.scss', 'public/css/pages/')
    .sass('resources/sass/pages/product_buy_requirement.scss', 'public/css/pages/')
    .sass('resources/css/common/message_notifications.scss', 'public/css/common/')
    .sass('resources/sass/pages/create_invoice.scss', 'public/css/pages/')
    .sass('resources/sass/pages/manage_challan.scss', 'public/css/pages/')
    .sass('resources/sass/pages/manage_purchase_order.scss', 'public/css/pages/')
    .sass('resources/sass/pages/dashboard.scss', 'public/css/pages/')
    .sass('resources/sass/pages/director_profile.scss', 'public/css/pages/')
    .sass('resources/sass/pages/business_profile.scss', 'public/css/pages/')
    .sass('resources/sass/pages/khata/inventory_management.scss', 'public/css/pages/')
    .sass('resources/sass/pages/khata/inventory/definition.scss', 'public/css/pages')
    .sass('resources/sass/pages/khata/inventory/stock_index.scss', 'public/css/pages')
    .sass('resources/sass/pages/khata/inventory_products_list.scss', 'public/css/pages/')
    .sass('resources/sass/pages/khata/inventory/pricing.scss', 'public/css/pages/')
    .sass('resources/sass/pages/products_index.scss', 'public/css/pages/')
    .sass('resources/sass/pages/logistics/driver/driver.scss', 'public/css/pages/')
    .webpackConfig(require('./webpack.config'));

// copy third party plugin images to local directory
mix.copy('node_modules/intl-tel-input/build/img/*', 'public/images/intl-tel-input/')
mix.copy('resources/images/badges/*', 'public/images/badges/')

if (mix.inProduction()) {
    mix.version();
}

