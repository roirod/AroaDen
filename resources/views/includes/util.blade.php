<script type="text/javascript">

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

var defaulId = 'ajax_content';
var defaulTableId = 'item_list';
var lastRoute = '';
var currentContent = '';
var currentId = '';
var mainUrl = '/' + "{{ $main_route }}";
var mainUrlAjax = '/' + "{{ $main_route }}" + '/ajaxIndex';

$(document).ajaxError(function(event, jqxhr, settings, thrownError) {
  event.preventDefault();
  event.stopPropagation();

  if (thrownError == "Forbidden") {
    util.loadMainUrlContent();

    return util.showPopup("{{ Lang::get('aroaden.deny_access') }}", false);
  }

  return util.showPopup("{{ Lang::get('aroaden.error_message') }} - ajaxError", false);
});

var util = {

  processAjaxReturnsHtml: function(obj) {
    var _this = this;

    var id = (obj.id == undefined) ? defaulId : obj.id;
    var data = (obj.data == undefined) ? false : obj.data;
    var method = (obj.method == undefined) ? "GET" : obj.method;
    var popup = (obj.popup == undefined) ? false : obj.popup;

    currentContent = $('#'+id).clone();
    currentId = id;

    _this.showLoadingGif(id);

    var ajax_data = {
      method : method,
      url  : obj.url,
      dataType: "html",
      data : data,
      statusCode: {
        200: function(response) {
          _this.showContentOnPage(id, response);

          if (popup)
            _this.showPopup("{{ Lang::get('aroaden.success_message') }}");

          _this.getSettings();
        }
      }
    };

    $.ajax(ajax_data);
  },

  loadMainUrlContent: function(url) {
    var urlToLoad = (url != undefined) ? url : mainUrl;

    window.location.href = urlToLoad;
  },

  processAjaxReturnsJson: function(obj) {
    var data = (obj.data == undefined) ? false : obj.data;
    var method = (obj.method == undefined) ? "POST" : obj.method;

    var ajax_data = {
      method : method,
      url  : obj.url,
      dataType: "json",
      data : data
    };

    if (obj.uploadFiles) {
      ajax_data.processData = false;
      ajax_data.contentType = false;
      ajax_data.cache = false;      
    }

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
        showConfirmButton: false,
        allowOutsideClick: true
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
    var _this = this;

    var id = (id == false) ? defaulId : id;
    var error = (error == undefined) ? false : error;

    if (error)
      content = '<p class="text-danger">' + content + '</p>';

    $('#'+id).empty();
    $('#'+id).hide().html(content).fadeIn(700);
  },

  showLoadingGif: function(id) {
    var id = (id == undefined) ? defaulId : id;
    var loading = '<img class="center" src="/assets/img/loading.gif"/>';

    $('#'+id).empty();
    $('#'+id).html(loading);
  },

  getSettings: function() {
    var _this = this;

    var ajax_data = {
      method : "GET",
      url  : "/settings/jsonSettings"
    };

    _this.processAjaxReturnsJson(ajax_data).done(function(response) {
      document.title = response.page_title;
    });
  },

  showSearchText: function() {
    if (document.getElementById('searched')) {
      var searched = ' <span class="label label-primary">{{ Lang::get('aroaden.searched_text') }} ' + $('#string').val() + '</span>';
      $('#searched').prepend(searched);
    }
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

  confirmDeleteAlert: function($this) {
    var _this = this;

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
        if (isConfirm) {
          var form = $this.closest('form');
          var attributes = form[0].attributes;
          var url = attributes[1].value;
          var method = attributes[0].value;
          var data = form.serialize();

          var ajax_data = {
            method : method,
            url  : url,
            data : data
          };

          _this.processAjaxReturnsJson(ajax_data).done(function(response) {




     console.log('------------ url ------------------');
     console.dir(response.url);



            _this.showPopup(response.msg);           
            _this.loadMainUrlContent(response.url);
            _this.getSettings();
          });
        }
      }
    );
  },

}

</script>