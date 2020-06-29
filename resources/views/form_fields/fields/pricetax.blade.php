
<div class="form-group col-sm-2">
  <label class="control-label text-left mar10">{{ @trans('aroaden.price') }}</label>
  <input type="text" id="total_amount" class="form-control bggrey" readonly="">
</div>

<script type="text/javascript">
  var tax;
  var price;
  selector = 'input[name="price"]';

  $(document).ready(function() {
    function calcTotal() {
      tax = $('select[name=tax] option').filter(':selected').val();
      price = $(selector).val().trim();

      util.validateCurrency(price);

      price = util.convertToOperate(price);
      var total = util.calcTotal(price, tax);
      total = total + " " + Alocale.currency_symbol;

      $("#total_amount").val(total);
    }

    $(selector).on('change keyup', calcTotal);

    $('select[name="tax"]').on('change keyup', calcTotal);

    calcTotal();
  });

</script>