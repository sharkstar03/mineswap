@push('script')
    <script>
        var root = document.querySelector(':root');
        root.style.setProperty('--brand-color', '{{config('basic.base_color')??'#F6A200'}}');
    </script>
@endpush
