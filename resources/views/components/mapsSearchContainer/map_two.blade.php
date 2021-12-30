<div>
    @push('styles')
        <link rel="stylesheet" href="{{ asset(('css/maps_comp.css')) }}">
    @endpush

    <input id="pac-input_two" class="controls" type="text" placeholder="Search Box" />
    <div id="map_two"></div>


    @push('scripts')
             <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBJYygnGAhAWiwnrDSjFwGo3Bo5Re98lR4&callback=initAutocompleteTwo&libraries=places&v=weekly&channel=2"
                async></script>
        <script type="text/javascript" src="{{ asset(('js/pages/maps_comp.js')) }}"></script>
    @endpush

</div>
