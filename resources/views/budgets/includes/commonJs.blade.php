<script type="text/javascript">

  if (typeof itemsArray == 'undefined')
    var itemsArray = [];

  var onUpdate = false;
  var onUpdateDeleteAll = false;
  var notes = false;
  var data = {};

  $(document).ready(function() {

    $(document).on('click','.saveBudget',function(evt){
      evt.preventDefault();

      if (onUpdate)
        notes = $('textarea[name="notes"]').val();

      if (typeof itemsArray === 'undefined' || itemsArray.length === 0) {
        if (onUpdate) {

          onUpdateDeleteAll = true;

        } else {

          return util.showPopup('{!! @trans("aroaden.no_add_treatments") !!}', false);

        }
      }

      data.itemsArray = itemsArray;
      data.onUpdate = onUpdate;
      data.onUpdateDeleteAll = onUpdateDeleteAll;
      data.notes = notes;
      data.idpat = '{!! $idpat !!}';
      data.uniqid = '{!! $uniqid !!}';
      data.created_at = '{!! $created_at !!}';

      var ajax_data = {
        url  : '{!! url("/$main_route") !!}',
        data : data
      };

      util.processAjaxReturnsJson(ajax_data).done(function(response) {
        if (response.error) {

          util.showPopup(response.msg, false);
          return util.reload();

        } else {

          util.showPopup();

          if (!onUpdate)
            return util.redirectTo('{!! url("/$main_route/$idpat") !!}');

        }
      });
    });

    $(document).on('click','.delLine',function(evt){
      evt.preventDefault();

      var tr_content = $(this).closest("tr");
      var id = tr_content.attr('id');
      var splitId = id.split("_");
      var onlyId = splitId[1];

      itemsArray.forEach( function(elementObj, index, array) {
        if (elementObj['idser'] == onlyId)
          itemsArray.splice(index, 1);
      });

      tr_content.remove();
    });
    
    $(document).on('click','.addLine',function(evt){
      evt.preventDefault();

      var idser = $(this).attr('data-idser');
      var name = $(this).attr('data-name');
      var price = $(this).attr('data-price');

      var units = $(this).closest("tr").find("input[name='units']").val();
      var msg = "Tratamiento: " + name + ", ya ha sido añadido.";

      try {

        itemsArray.forEach( function(elementObj, key, array) {
          if (elementObj.idser == idser)
            throw msg;
        });

        var obj = {
          'idser' : idser,
          'units' : units
        };

        itemsArray.push(obj);

        newRowContent = '  <tr class="fonsi13" id="budgetId_' + idser + '">';
        newRowContent += '   <td class="wid180">' + name + '</td>';
        newRowContent += '   <td class="wid70 textcent">' + units + '</td>';
        newRowContent += '   <td class="wid70 textcent">' + price +' '+ Alocale.currency_symbol +'</td>';
        newRowContent += '   <td class="wid50">';
        newRowContent += '     <div class="btn-group">';
        newRowContent += '       <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">';
        newRowContent += '         <i class="fa fa-times"></i>  <span class="caret"></span>';
        newRowContent += '       </button>';
        newRowContent += '       <ul class="dropdown-menu" role="menu">';
        newRowContent += '         <li> <button type="button" class="delLine"> <i class="fa fa-times"></i> Borrar</button></li> ';
        newRowContent += '       </ul>';
        newRowContent += '     </div>';
        newRowContent += '   </td>';
        newRowContent += '   <td class="wid50"></td>';
        newRowContent += ' </tr>';

        $("#items_list").append(newRowContent);

      } catch(err) {

        return util.showPopup(err, false, 2500);

      }
     });
  });

</script>