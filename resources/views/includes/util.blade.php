<script>

var defaulId = 'ajax_content';
var defaulTableId = 'item_list';
var lastRoute = '';

var routes = {
  patients_route: "{{ $patients_route }}",
  invoices_route: "{{ $invoices_route }}",
  budgets_route: "{{ $budgets_route }}",
  company_route: "{{ $company_route }}",
  appointments_route: "{{ $appointments_route }}",
  staff_route: "{{ $staff_route }}",
  services_route: "{{ $services_route }}",
  accounting_route: "{{ $accounting_route }}",
  treatments_route: "{{ $treatments_route }}",      
  settings_route: "{{ $settings_route }}"
};

$( document ).ajaxError(function(event, jqxhr, settings, thrownError) {
  event.preventDefault();
  event.stopPropagation();


     console.log('------------ thrownError ------------------');
     console.dir(thrownError);
     console.log('------------ thrownError ------------------');



     console.log('------------ settings ------------------');
     console.dir(settings);
     console.log('------------ settings ------------------');


  if (thrownError == "Forbidden") {
    var obj = {     
      url: lastRoute
    };

    util.processAjaxReturnsHtml(obj);

    return util.showPopup("{{ Lang::get('aroaden.deny_access') }}", false);
  }

  return util.showPopup("{{ Lang::get('aroaden.error_message') }} - ajaxError", false);
});

$(".del_btn").click(function(event) {
    event.stopPropagation();
    event.preventDefault();

    var $this = $(this);
    util.confirmAlert($this);
});

var util = {

  processAjaxReturnsHtml: function(obj) {
    var _this = this;

    var id = (obj.id == undefined) ? defaulId : obj.id;
    var data = (obj.data == undefined) ? false : obj.data;
    var method = (obj.method == undefined) ? "GET" : obj.method;
    var popup = (obj.popup == undefined) ? false : obj.popup;

    _this.showLoadingGif();

    var ajax_data = {
      method : method,
      url  : obj.url,
      dataType: "html",
      data : data
    };

    $.ajax(ajax_data).done(function(response) {
      _this.showContentOnPage(false, response);

      if (popup)
        _this.showPopup("{{ Lang::get('aroaden.success_message') }}");

      _this.getSettings();
    });
  },

  processAjaxReturnsJson: function(obj) {
    var data = (obj.data == undefined) ? false : obj.data;
    var method = (obj.method == undefined) ? "POST" : obj.method;
    var processData = (obj.processData == undefined) ? false : obj.processData;
    var contentType = (obj.contentType == undefined) ? false : obj.contentType;
    var cache = (obj.cache == undefined) ? false : obj.cache;

    var ajax_data = {
      method : method,
      url  : obj.url,
      dataType: "json",
      data : data,
      processData : processData,
      contentType : contentType,
      cache : cache      
    };


     console.log('------------ ajax_data ------------------');
     console.dir(ajax_data);


    

    return $.ajax(ajax_data);
  },

  showPopup: function(msg, success = true, time = 1200) {
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
        type: 'error',
        allowOutsideClick: true,
      });
    }
  },

  renderTemplateOnPage: function(id, templateId, data) {
    var _this = this; 

    var templateHandlebars = $("#" + templateId).html();
    var compileTemplate = Handlebars.compile(templateHandlebars);
    var compiledHtml = compileTemplate(data);

    _this.showContentOnPage(id, compiledHtml);
  },

  showContentOnPage: function(id, content, error) {
    var id = (id == false) ? defaulId : id;
    var error = (error == undefined) ? false : error;

    if (error)
      content = '<p class="text-danger">' + content + '</p>';

    $('#'+id).empty();
    $('#'+id).html(content).fadeIn('slow');
  },

  getSettings: function() {
    var _this = this;

    var ajax_data = {
      method : "GET",
      url  : "settings/jsonSettings",
      dataType: "json"
    };

    _this.processAjaxReturnsJson(ajax_data).done(function(response) {
      document.title = response.page_title;
    });
  },

  showLoadingGif: function(id) {
    var id = (id == undefined) ? defaulId : id;
    var loading = '<img class="center" src="/assets/img/loading.gif"/>';

    $('#'+id).empty();
    $('#'+id).html(loading);
  },

  showSearchText: function() {
    var searched = ' <span class="label label-primary">{{ Lang::get('aroaden.searched_text') }} ' + $('#string').val() + '</span>';
    $('#searched').prepend(searched);
  },

  getTodayDate: function() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() +1;
    var yyyy = today.getFullYear();

    if(dd < 10)
      dd = '0' + dd

    if(mm < 10)
      mm = '0' + mm

    today = yyyy + '-' + mm + '-' + dd;

    return today;
  },

  multiply: function(num1, num2) {
    var num1 = parseInt(num1, 10);
    var num2 = parseInt(num2, 10);
    var total = num1 * num2;

    return total;
  },

  confirmAlert: function($this) {
    swal({
      title: '{{ Lang::get('aroaden.are_you_sure') }}',
      type: 'warning',
      showCancelButton: true,
      allowOutsideClick: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: '{{ Lang::get('aroaden.yes') }}',
      cancelButtonText: '{{ Lang::get('aroaden.no') }}',
      confirmButtonClass: 'confirm-class',
      cancelButtonClass: 'cancel-class',
    }).then(
      function(isConfirm) {
        if (isConfirm) $this.closest('form').submit();
      }
    );
  },

}

</script>