
  @include('form_fields.form_js')

  <link rel="stylesheet" href="{{ asset('assets/slimselect/slimselect.min.css') }}" />
  <script type="text/javascript" src="{{ asset('assets/slimselect/slimselect.min.js') }}"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      new SlimSelect({
        select: '#staff',
        deselectLabel: '<span class="red fonsi18">✖</span>',        
        placeholder: "{{ Lang::get('aroaden.select_one_or_more') }}",
        searchText: "{{ Lang::get('aroaden.search') }}",
        searchPlaceholder: "{{ Lang::get('aroaden.search') }}",
        searchFocus: true,
        searchHighlight: true,
        closeOnSelect: false
      });
    });
  </script>

  <script type="text/javascript">

    selector = 'input[name="paid"]';

    var msg = "{{ Lang::get('aroaden.multiply_units_price') }}";
    var append = ' <a id="multiply_units_price" class="pad4 bgwi fuengrisoscu" title="'+msg+'"><i class="fa fa-lg fa-close"></i></a>';
    $(selector).parent().find('label').append(append);

    var msg = "{{ Lang::get('aroaden.put_zero') }}";
    var append = ' <a id="put_zero" class="pad4 bgwi fuengrisoscu" title="'+msg+'"><i class="fa fa-close fa-lg text-danger"></i></a>';
    $(selector).parent().find('label').append(append);

    $('#put_zero').click(function () {
      $(selector).val(0);
    });

    var price;
    var tax;

    function getPaid() {
      var units = $('input[name="units"]').val();
      var total_amount = util.calcTotal(price, tax, false);
      var paid = util.multiply(units, total_amount);    

      $(selector).val(paid);
    }

    $('form.save_form').submit(function (evt) {
      evt.preventDefault();
      evt.stopPropagation();

      saveData();
    });

    function saveData() {
      var data = $("form.save_form").serialize();
      var action = $("form.save_form").attr('action');

      var paid = $(selector).val();
      util.validateCurrency(paid);

      var ajax_data = {
        url  : action,
        data : data
      };

      util.processAjaxReturnsJson(ajax_data).done(function(response) {
        if (response.error)
          return util.showPopup(response.msg, false, 3500);

        util.showPopup();
        return util.redirectTo();
      });
    }

  </script>
