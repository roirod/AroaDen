
<link href="{!! asset('assets/css/colorbox.css') !!}" rel="stylesheet" type="text/css">

<script type="text/javascript" src="{!! asset('assets/js/colorbox/jquery.colorbox-min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/js/colorbox/jquery.colorbox-es.js') !!}"></script>  

<script type="text/javascript">

  var idtreArray = [];
  var request_url;
  var preview;

  function onsaveData() {
    request_url = '{!! url("/$main_route") !!}';
    preview = false;

    doStaff();
  }

  function doStaff() {
    var idpat = {{ $idpat }};
    var type = '{{ $type }}';
    var updatedA = <?php echo json_encode($updatedA); ?>;      
    var serial = $('input[name="serial"]').val().trim();
    var place = $('input[name="place"]').val().trim();
    var exp_date = $('input[name="exp_date"]').val().trim();
    var notes = $('textarea[name="notes"]').val();     

    if (serial == '')
      return util.showPopup('{!! @trans("aroaden.serial") !!} - {!! @trans("aroaden.field_required") !!}', false);

    if (place == '')
      return util.showPopup('{!! @trans("aroaden.place") !!} - {!! @trans("aroaden.field_required") !!}', false);

    if (exp_date == '')
      return util.showPopup('{!! @trans("aroaden.exp_date") !!} - {!! @trans("aroaden.field_required") !!}', false);

    if (typeof idtreArray === undefined || idtreArray.length === 0)
      return util.showPopup('{!! @trans("aroaden.no_add_treatments") !!}', false);

    var ajax_data = {
      url  : request_url,
      data : {
        "_token": "{{ csrf_token() }}",
        'idtreArray' : idtreArray,
        'updatedA' : updatedA,       
        'idpat' : idpat,
        'type' : type,
        'serial' : serial,
        'place' : place,
        'exp_date' : exp_date,
        'notes': notes
      }
    };

    util.processAjaxReturnsJson(ajax_data).done(function(response) {
      if (response.error) {
        util.showPopup(response.msg, false);
        return util.reload();
      }

      if (preview) {
        return $.colorbox({
          html: response.htmlContent,
          maxWidth: '95%',
          maxHeight: '95%'
        });
      }

      util.showPopup();
      return util.redirectTo('{!! url("/$main_route/$idpat") !!}');
    });
  }

  $(document).ready(function() {
    $(document).on('click','.previewData',function(evt){
      evt.preventDefault();

      request_url = '{!! url("/$main_route") !!}/preview';
      preview = true;      
      doStaff();
    });

    $(document).on('click','.delLine',function(evt){
      evt.preventDefault();

      var tr_content = $(this).closest("tr");
      var id = tr_content.attr('id');
      var splitId = id.split("_");
      var onlyidtre = splitId[1];

      idtreArray.forEach( function(elementObj_idtre, index, array) {
        if (elementObj_idtre == onlyidtre)
          idtreArray.splice(index, 1);
      });

      tr_content.remove();
    });
    
    $(document).on('click','.addLine',function(evt){
      evt.preventDefault();

      var idtre = $(this).attr('data-idtre');
      var name = $(this).attr('data-name');
      var units = $(this).attr('data-units');
      var day = $(this).attr('data-day');

      var msg = "Tratamiento: " + name + ", ya ha sido añadido.";

      try {

        idtreArray.forEach(function(elementObj_idtre, key, array) {
          if (elementObj_idtre == idtre)
            throw msg;
        });

        idtreArray.push(idtre);

        newRowContent = '  <tr id="idtre_' + idtre + '">';
        newRowContent += '   <td class="wid120">' + name + '</td>';
        newRowContent += '   <td class="wid60 textcent">' + day + '</td>';                
        newRowContent += '   <td class="wid40 textcent">' + units + '</td>';
        newRowContent += '   <td class="wid40 textcent">';
        newRowContent += '     <button type="button" class="btn btn-sm btn-danger delLine"> <i class="fa fa-times"></i></button>';
        newRowContent += '   </td>';
        newRowContent += ' </tr>';

        $("#items_list").append(newRowContent);

      } catch(err) {

        return util.showPopup(err, false);

      }
     });
  });

</script>