
<div class="form-group col-sm-2">  
  <label class="control-label text-left mar10">{{ @trans('aroaden.price') }}</label>
  <p class="pad6 bggrey" id="total_amount">
  </p>
</div>

<script type="text/javascript">

  $(document).ready(function() {
    function calcTotal() {
      var tax = $('select[name=tax] option').filter(':selected').val();
      var price = $('input[name="price"]').val();

      util.validateCurrency(price);
      price = util.convertToOperate(price);
      var total_amount = util.calcTotal(price, tax);

      $("#total_amount").text(total_amount + " " + Alocale.currency_symbol);
    }

    $('input[name="price"]').on('change keyup', calcTotal);

    $('select[name="tax"]').on('change keyup', calcTotal);

    calcTotal();
  });

</script>