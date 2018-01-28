<script>
    
var util = {

  processAjax: function(obj) {
    var _this = this;
    var cancel = false;

    var id = obj.id == null ? cancel = true : obj.id;
    var url = obj.url == null ? cancel = true : obj.url;
    var data = obj.data == null ? false : obj.data;
    var type = obj.type == null ? "GET" : obj.type;
    var dataType = obj.dataType == null ? "html" : obj.type;
    var popup = obj.popup == null ? false : obj.popup;

    if (cancel) {
      _this.showPopup("{{ Lang::get('aroaden.error_message') }}");
    }

    _this.showLoadingGif(id);

    var ajax_data = {
      type : type,
      url  : url,
      dataType: dataType,
      data : data
    };

    $.ajax(ajax_data).done(function(response) {

      $('#'+id).empty();
      $('#'+id).hide().html(response).fadeIn();

      if (popup) {
        _this.showPopup("{{ Lang::get('aroaden.success_message') }}");
      }

    }).fail(function() {

      $('#'+id).empty();
      $('#'+id).hide().html('<h3>{{ Lang::get('aroaden.error_message') }}</h3>').fadeIn();

    });
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

  showPopup: function(msg, success = true, timer = 1000) {
    if (success) {
      swal({
        title: msg,
        type: 'success',
        showConfirmButton: false,             
        timer: timer
      });
    } else {
      swal({
        text: msg,
        type: 'warning',
        showConfirmButton: false
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
    var loading = '<img src="/assets/img/loading.gif"/>';
    $('#'+id).empty();
    $('#'+id).html(loading);
  },

}

</script>