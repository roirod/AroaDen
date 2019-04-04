$(function () {
  var time = $("div#timepicker1").find('input').val();

  if (time == '')
    time = '12:00';

  $('#timepicker1').datetimepicker({
    format: 'HH:mm',
    locale: 'es'
  });

  $("div#timepicker1").find('input').val(time);
});