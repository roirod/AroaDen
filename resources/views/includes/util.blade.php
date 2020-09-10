
@if ($is_create_view)
  <script type="text/javascript">
    var is_create_view = true;
  </script>
@else
  <script type="text/javascript">
    var is_create_view = false;
  </script>
@endif

<script type="text/javascript">

  var routes = <?php echo json_encode($routes); ?>;
  var Alocale = <?php echo json_encode($Alocale); ?>;
  var Acurrency = <?php echo json_encode($_SESSION["Acurrency"]); ?>;

  var defaulId = 'ajax_content';
  var defaulTableId = 'item_list';
  var currentId = '';
  var selector = '';
  var currentContent = '';
  var mainUrl = '/' + "{{ $main_route }}";
  var mainUrlAjax = mainUrl + '/ajaxIndex';
  var lastRoute = '';
  var redirectRoute = '';
  var error = false;
  var msg = false;

  axios.interceptors.response.use(function (res) {
    return res;
  }, function (err) {
    if (err.response.status == 422) {
      location.href = "#form_errors";

      util.resetFormErrors();

      setTimeout(function(){
        vm_form_errors.object = err.response.data.errors;
      }, 200);
    }
  });

  $(document).ajaxError(function(event, jqXHR, settings, thrownError) {
    event.preventDefault();
    event.stopPropagation();

    console.log('---------------- ajaxError thrownError  ----------------------------');
    console.dir(thrownError);

    console.log('---------------- ajaxError jqXHR.status  ----------------------------');
    console.dir(jqXHR.status);

    if (jqXHR.status == 401)
      return util.redirectTo("/login");

    if (jqXHR.status == 422) {
      location.href = "#form_errors";

      util.resetFormErrors();

      setTimeout(function(){
        vm_form_errors.object = jqXHR.responseJSON.errors;
      }, 200);
    }

    if (thrownError == "Forbidden") {
      return util.showPopup("{{ Lang::get('aroaden.deny_access') }}", false);
    }

    //return util.showPopup("ajax Error", false);
  });

  var util = {

    setPageTitle: function(page_title) {
      document.title = page_title;
    },

    resetFormErrors: function() {
      if (vm_form_errors.object != false)
        vm_form_errors.object = false;
    },

    renderTable: function(obj) {
      setTimeout(function(){
        defaulTableId.dataTable(obj);
      }, 180);
    },

    destroyTable: function() {
      defaulTableId.dataTable().fnDestroy();
    },

    reload: function() {
      setTimeout(function(){  
        return window.location.reload();
      }, 1600);
    },

    redirectTo: function(string) {
      var string = (string == undefined) ? redirectRoute : string;

      setTimeout(function(){  
        return window.location.href = string;
      }, 1200);
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
              _this.showPopup();

            _this.getSettings();
          }
        }
      };

      console.log('---------------- processAjaxReturnsHtml ajax_data  ----------------------------');
      console.dir(ajax_data);
      console.log('--------------------------------------------');

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

      console.log('---------------- processAjaxReturnsJson ajax_data  ----------------------------');
      console.dir(ajax_data);
      console.log('--------------------------------------------');

      return $.ajax(ajax_data);
    },

    showPopup: function(msg, success, time) {
      var msg = (msg == undefined) ? "{{ Lang::get('aroaden.success_message') }}" : msg;
      var success = (success == undefined) ? true : success;
      var time = (time == undefined) ? 1200 : time;

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

        time = 3500;
       
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

      var id = (id == undefined) ? defaulId : id;
      var error = (error == undefined) ? false : error;

      console.log('---------------- showContentOnPage id  ----------------------------');
      console.dir(id);
      console.log('--------------------------------------------');

      if (error)
        content = '<p class="text-danger">' + content + '</p>';

      _this.showLoadingGif(id);

      window.setTimeout(function () {
        $('#'+id).empty();
        $('#'+id).html(content);
      }, 400);  
    },

    changeText: function(id, content, error) {
      var _this = this;

      var id = (id == undefined) ? currentId : id;
      var error = (error == undefined) ? false : error;

      console.log('---------------- changeText id  ----------------------------');
      console.dir(id);
      console.log('--------------------------------------------');

      if (error)
        return _this.showPopup("{{ Lang::get('aroaden.error_message') }}", false);

      window.setTimeout(function () {
        $('#'+id).text(content);
      }, 400);  
    },

    showLoadingGif: function(id) {
      var id = (id == undefined) ? defaulId : id;
      var loading = '<img class="center" src="/assets/img/loading.gif"/>';

      console.log('---------------- showLoadingGif id  ----------------------------');
      console.dir(id);
      console.log('--------------------------------------------');

      $('#'+id).empty();
      $('#'+id).html(loading);
    },

    getSettings: function() {
      var _this = this;

      var ajax_data = {
        method : "GET",
        url  : "/" + routes.settings + "/jsonSettings"
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

    multiply: function(num1, num2, printFormat) {
      var _this = this;

      var printFormat = (printFormat == undefined) ? true : false;

      var num1 = parseFloat(num1);
      var num2 = parseFloat(num2);
      var total = num1 * num2;

      total = _this.round2Dec(total);

      if (printFormat)
        return _this.printFormat(total);

      return total;
    },

    round2Dec: function(num) {
      num = Math.round((num + Number.EPSILON) * 100) / 100;

      return num;
    },

    calcTotal: function(price, tax, printFormat) {
      var _this = this;

      var printFormat = (printFormat == undefined) ? true : false;

      tax = parseFloat(tax);
      price = parseFloat(price);
      var total = ((price * tax) / 100) + price;

      total = _this.round2Dec(total);

      if (printFormat)
        total = _this.printFormat(total);

      return total;
    },

    printFormat: function(number) {
      var _this = this;

      number = parseFloat(number);

      return _this.numberFormat(number, Alocale.frac_digits, Alocale.decimal_point, Alocale.thousands_sep);
    },

    convertToOperate: function(number) {
      var str = number.toString();
      var res = str.split(Alocale.thousands_sep).join("");
      res = res.replace(Alocale.decimal_point, ".");

      return res;
    },

    numberFormat: function(number, decimals, dec_point, thousands_sep) {
      number = number.toFixed(decimals);
      var nstr = number.toString();
      nstr += '';
      x = nstr.split('.');

      x1 = x[0];
      x2 = x.length > 1 ? dec_point + x[1] : '';
      var rgx = /(\d+)(\d{3})/;

      while (rgx.test(x1))
        x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');

      return x1 + x2;
    },

    validateCurrency: function(currency) {
      var _this = this;
      var regexp = RegExp(Acurrency.regexp);

      try {

        if (!regexp.test(currency))
          throw "{{ Lang::get('aroaden.currency_format_error') }}";

      } catch (err) {

        msg = err;
        _this.appendMsg();
        throw "";

      }
    },

    appendMsg: function() {
      var content = '';
      content = '<div class="alert alert-danger absolute fonsi14">';
      content +=   msg;
      content += '</div>';

      return $(content).insertAfter(selector).fadeOut(2600).queue(function() { $(this).remove(); });
    },

    onEditResource: function($this) {
      var _this = this;

      var attributes = $this[0].attributes;
      var url = attributes['href'].value;

      console.log('---------------- onEditResource attributes  ----------------------------');
      console.dir(attributes);
      console.log('--------------------------------------------');

      _this.redirectTo(url); 
    },

    confirmDeleteAlert: function($this) {
      var _this = this;

      var form = $this.closest('form');
      var data = form.serialize();
      var attributes = form[0].attributes;
      var url = (attributes['action'] == undefined) ? false : attributes['action'].value;
      var removeTr = (attributes['data-removeTr'] == undefined) ? false : attributes['data-removeTr'].value;
      var htmlContent = (attributes['data-htmlContent'] == undefined) ? false : attributes['data-htmlContent'].value;
      var count = (attributes['data-count'] == undefined) ? false : attributes['data-count'].value;
      var redirect = (attributes['data-redirect'] == undefined) ? false : attributes['data-redirect'].value;

      console.log('---------------- confirmDeleteAlert attributes  ----------------------------');
      console.dir(attributes);
      console.log('--------------------------------------------');

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

              console.log('---------------- confirmDeleteAlert response  error----------------------------');
              console.dir(response.error);
              console.log('--------------------------------------------');
              
              console.log('---------------- confirmDeleteAlert response  htmlContent----------------------------');
              console.dir(response.htmlContent);
              console.log('--------------------------------------------');

              console.log('---------------- confirmDeleteAlert response  count----------------------------');
              console.dir(response.count);
              console.log('--------------------------------------------');

              if (response.error)
                return _this.showPopup(response.msg, false);

              if (count == 'true')
                _this.changeText(undefined, response.count);

              if (htmlContent == 'true')
                _this.showContentOnPage(undefined, response.htmlContent);

              if (removeTr == 'true')
                $this.closest('tr').remove();

              if (redirect == 'true')
                _this.redirectTo(); 

              return _this.showPopup();
            });
          }
        }
      );
    }

  }

</script>