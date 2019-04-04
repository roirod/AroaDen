$(function () {
  var date = $("div#datepicker2").find('input').val();

  if (date == '')
    date = util.getTodayDateDDMMYYYY();

  $('#datepicker2').datetimepicker({
    format : 'DD-MM-YYYY',
    locale: 'es',
    daysOfWeekDisabled : [0],
    showTodayButton: true,
    showClear: true,
    useCurrent: true
  });

  $("div#datepicker2").find('input').val(date);
});