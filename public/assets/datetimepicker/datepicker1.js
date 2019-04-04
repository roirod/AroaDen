$(function () {
  var date = $("div#datepicker1").find('input').val();

  if (date == '')
    date = util.getTodayDateDDMMYYYY();

  $('#datepicker1').datetimepicker({
    format : 'DD-MM-YYYY',
    locale: 'es',
    daysOfWeekDisabled : [0],
    showTodayButton: true,
    showClear: true,
    useCurrent: true
  });

  $("div#datepicker1").find('input').val(date);
});