
	function multi(units, price) {
	  var units = parseInt(units, 10);
	  var price = parseInt(price, 10);
	  var paid = units * price;

	  $('input[name="paid"]').val(paid);
	}

