
  <script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/moment-es.js') }}"></script>

  <link rel="stylesheet" href="{{ asset('assets/datetimepicker/css/datetimepicker.min.css') }}" />
  <script type="text/javascript" src="{{ asset('assets/datetimepicker/js/datetimepicker.min.js') }}"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      if($("#datepicker1").length > 0)
        $.getScript("{{ asset('assets/datetimepicker/datepicker1.js') }}");

      if($("#datepicker2").length > 0)
        $.getScript("{{ asset('assets/datetimepicker/datepicker2.js') }}");

      if($("#timepicker1").length > 0)
        $.getScript("{{ asset('assets/datetimepicker/timepicker1.js') }}");
    });
  </script>