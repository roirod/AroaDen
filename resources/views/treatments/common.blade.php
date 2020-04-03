

  <script type="text/javascript" src="{{ asset('assets/js/modernizr.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/forgetChanges.js') }}"></script>

  <script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/moment-es.js') }}"></script>

  <link rel="stylesheet" href="{{ asset('assets/datetimepicker/css/datetimepicker.min.css') }}" />
  <script type="text/javascript" src="{{ asset('assets/datetimepicker/js/datetimepicker.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/datetimepicker/datepicker1.js') }}"></script>

  <link rel="stylesheet" href="{{ asset('assets/slimselect/slimselect.min.css') }}" />
  <script type="text/javascript" src="{{ asset('assets/slimselect/slimselect.min.js') }}"></script>

  <script type="text/javascript">

    var msg = "{{ Lang::get('aroaden.multiply_units_price') }}";
    var append = ' <a id="multiply_units_price" class="pad4 bgwi fuengrisoscu" title="'+msg+'"><i class="fa fa-lg fa-close"></i></a>';
    $('input[name="paid"]').parent().find('label').append(append);

    var msg = "{{ Lang::get('aroaden.put_zero') }}";
    var append = ' <a id="put_zero" class="pad4 bgwi fuengrisoscu" title="'+msg+'"><i class="fa fa-close fa-lg text-danger"></i></a>';
    $('input[name="paid"]').parent().find('label').append(append);

    $('#put_zero').click(function () {
      $('input[name="paid"]').val(0);
    });

    function getPaid(price) {
      var units = $('input[name="units"]').val();
      var paid = util.multiply(units, price);    

      $('input[name="paid"]').val(paid);
    }

    $(document).ready(function() {
      new SlimSelect({
        select: '#staff',
        placeholder: "{{ Lang::get('aroaden.select_one_or_more') }}",
        searchText: "{{ Lang::get('aroaden.search') }}",
        searchPlaceholder: "{{ Lang::get('aroaden.search') }}",
        searchFocus: true,
        searchHighlight: true,
        closeOnSelect: false
      });
    });

  </script>
