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
  var currentId = '';  
  var currentContent = '';
  var mainUrl = '/' + "{{ $main_route }}";
  var mainUrlAjax = mainUrl + '/ajaxIndex';
  var lastRoute = '';
  var redirectRoute = '';
  var error = false;

  // datatables staff
  var aLengthMenu = [
    [15, 25, 50, 100],
    [15, 25, 50, 100]
  ];

  var iDisplayLength = 15;

  $(document).ajaxError(function(event, jqXHR, settings, thrownError) {
    event.preventDefault();
    event.stopPropagation();

      console.log('---------------- ajaxError ----------------------------');

    if (jqXHR.status == 401)
      return util.redirectTo("/login");
          
    if (thrownError == "Forbidden") {
      return util.showPopup("{{ Lang::get('aroaden.deny_access') }}", false, 2500);
    }

    return util.showPopup("ajax Error", false, 2500);
  });

  var util = {

    redirectTo: function(string) {
      return window.location.href = string;
    },

    processAjaxReturnsHtml: function(obj) {
      var _this = this;

      var id = (obj.id == undefined) ? defaulId : obj.id;
      var data = (obj.data == undefined) ? false : obj.data;
      var method = (obj.method == undefined) ? "GET" : obj.method;
      var popup = (obj.popup == undefined) ? false : obj.popup;

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

      return $.ajax(ajax_data);
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

    loadMainUrlContent: function(url) {
      var _this = this;

      var urlToLoad = (url != undefined) ? url : mainUrlAjax;

      var obj = {
        url  : urlToLoad,
      };

      window.history.replaceState('Object', 'Title', mainUrl);

      _this.processAjaxReturnsHtml(obj);
    },

    restoreMainContent: function() {
      var _this = this;

      return _this.showContentOnPage(defaulId, currentContent);
    },

    showPopup: function(msg, success, time) {
      var time = (time == undefined) ? 1200 : time;
      var success = (success == undefined) ? true : success;

      console.log('---------------- showPopup msg  ----------------------------');
      console.dir(msg);
      console.log('--------------------------------------------');

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
          allowOutsideClick: true,
          timer: time
        });

      }
    },

    renderTemplateOnPage: function(id, templateId, data) {
      var _this = this; 

      var templateHandlebars = $("#" + templateId).html();
      var compileTemplate = Handlebars.compile(templateHandlebars);
      var compiledHtml = compileTemplate(data);

      return _this.showContentOnPage(id, compiledHtml);
    },

    showContentOnPage: function(id, content, error) {
      var _this = this;

      var id = (id == false) ? defaulId : id;
      var error = (error == undefined) ? false : error;

      if (error)
        content = '<p class="text-danger">' + content + '</p>';

      _this.showLoadingGif(id);

      window.setTimeout(function () {
        $('#'+id).empty();
        $('#'+id).html(content);
      }, 400);  
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
        url  : "/" + routes.settings_route + "/jsonSettings"
      };

      _this.processAjaxReturnsJson(ajax_data).done(function(response) {
        document.title = response.page_title;
      });
    },

    checkPermissions: function(action) {
      var _this = this;

      var ajax_data = {
        data : { action: action },
        method : "GET",
        url  : "/" + routes.settings_route + "/checkPermissions"
      };

      return _this.processAjaxReturnsJson(ajax_data);
    },

    showSearchText: function() {
      if (document.getElementById('searched')) {
        var searched = ' <span class="label label-primary">{{ Lang::get('aroaden.searched_text') }} ' + $('#string').val() + '</span>';
        $('#searched').prepend(searched);
      }
    },

    getTodayDateDDMMYYYY: function() {
      var today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth() +1;
      var yyyy = today.getFullYear();

      if(dd < 10)
        dd = '0' + dd

      if(mm < 10)
        mm = '0' + mm

      today = dd + '-' + mm + '-' + yyyy;

      return today;
    },

    multiply: function(num1, num2) {
      var num1 = parseInt(num1, 10);
      var num2 = parseInt(num2, 10);
      var total = num1 * num2;

      return total;
    },

    onEditResource: function($this) {
      var _this = this;

      var attributes = $this[0].attributes;
      var url = attributes['href'].value;
      var checkpermissions = attributes['data-checkpermissions'].value;

      _this.checkPermissions(checkpermissions).done(function(response) {
        if (response.permission) {

          window.location.href = url;

        } else {

          return _this.showPopup("{{ Lang::get('aroaden.deny_access') }}", false, 2500);

        }
      });
    },

    confirmDeleteAlert: function($this) {
      var _this = this;

      var form = $this.closest('form');
      var data = form.serialize();
      var attributes = form[0].attributes;
      var url = attributes['action'].value;
      var removeTr = attributes['data-removeTr'].value;
      var htmlContent = attributes['data-htmlContent'].value;

      var tr_content = $this.closest('tr');

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
        cancelButtonClass: 'cancel-class'

      }).then(

        function(isConfirm) {
          if (isConfirm) {
            var ajax_data = {
              url  : url,
              data : data
            };

            _this.processAjaxReturnsJson(ajax_data).done(function(response) {
              if (response.error)
                return _this.showPopup(response.msg, false, 2500);

              if (htmlContent == 'true')
                _this.showContentOnPage(defaulId, response.htmlContent);

              if (removeTr == 'true') {

                tr_content.remove();

              } else {

                setTimeout(function(){  _this.redirectTo(redirectRoute); }, 1100);

              }

              return _this.showPopup(response.msg);
            });
          }
        }
      );
    }

  }

</script>