<script>
    
var util = {

  processAjaxReturnsHtml: function(obj) {
    var _this = this;
    var cancel = false;

    var id = obj.id == null ? cancel = true : obj.id;
    var data = obj.data == null ? false : obj.data;
    var type = obj.type == null ? "GET" : obj.type;
    var popup = obj.popup == null ? false : obj.popup;

    _this.showLoadingGif(id);

    var ajax_data = {
      type : type,
      url  : obj.url,
      dataType: "html",
      data : data
    };

    $.ajax(ajax_data).done(function(response) {

      $('#'+id).empty();
      $('#'+id).hide().html(response).fadeIn();

      if (popup) {
        _this.showPopup("{{ Lang::get('aroaden.success_message') }}");
      }

    }).fail(function() {

      _this.showPopup("{{ Lang::get('aroaden.error_message') }}", false);

    });
  },

  processAjaxReturnsJson: function(obj) {
    var data = obj.data || false;
    var type = obj.type || "POST";

    var ajax_data = {
      type : type,
      url  : obj.url,
      dataType: "json",
      data : data
    };

    return $.ajax(ajax_data);
  },

  getTodayDate: function() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() +1;
    var yyyy = today.getFullYear();

    if(dd < 10) {
      dd = '0' + dd
    } 

    if(mm < 10) {
      mm = '0' + mm
    } 

    today = yyyy + '-' + mm + '-' + dd;

    return today;
  },

  showPopup: function(msg, success = true, time = 1000) {
    if (success) {
      swal({
        title: msg,
        type: 'success',
        showConfirmButton: false,             
        timer: time
      });
    } else {
      swal({
        text: msg,
        type: 'warning',
        showConfirmButton: false,
        timer: time
      });
    }
  },

  multiply: function(num1, num2) {
    var num1 = parseInt(num1, 10);
    var num2 = parseInt(num2, 10);
    var total = num1 * num2;

    return total;
  },

  showLoadingGif: function(id) {
    var loading = '<img class="center" src="/assets/img/loading.gif"/>';
    $('#'+id).empty();
    $('#'+id).html(loading);
  },

}

</script>