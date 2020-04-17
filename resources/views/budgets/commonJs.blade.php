<script type="text/javascript">

  if (typeof budgetArray == 'undefined')
    var budgetArray = [];

  var onUpdate = false;
  var onUpdateDeleteAll = false;
  var budgettext = false;

  $(document).ready(function() {

    $(document).on('click','.saveBudget',function(evt){
      evt.preventDefault();

      if (onUpdate)
        budgettext = $('textarea[name="budgettext"]').val();

      if (typeof budgetArray === 'undefined' || budgetArray.length === 0) {
        if (onUpdate) {

          onUpdateDeleteAll = true;

        } else {

          return util.showPopup('{!! @trans("aroaden.no_add_treatments") !!}', false, 2500);

        }
      }

      var ajax_data = {
        url  :   '{!! url("/$main_route") !!}',
        data : {
          'budgetArray' : budgetArray,
          'onUpdate': onUpdate,
          'onUpdateDeleteAll': onUpdateDeleteAll,
          'budgettext': budgettext,
          'uniqid': '{!! $uniqid !!}'
        }
      };

      util.processAjaxReturnsJson(ajax_data).done(function(response) {
        if (response.error) {

          util.showPopup(response.msg, false, 5000);

        } else {

          util.showPopup();

          if (!onUpdate)
            return util.redirectTo('{!! url("/$main_route/$idpat") !!}');

        }
      });
    });

    $(document).on('click','.delBudgetLine',function(evt){
      evt.preventDefault();

      var tr_content = $(this).closest("tr");
      var id = tr_content.attr('id');
      var splitId = id.split("_");
      var onlyId = splitId[1];

      budgetArray.forEach( function(elementObj, index, array) {
        if (elementObj['idser'] == onlyId)
          budgetArray.splice(index, 1);
      });

      tr_content.remove();
    });
    
    $(document).on('click','.addBudgetLine',function(evt){
      evt.preventDefault();

      var new_url = $("#new_url").attr("value");

      var idpat = $(this).attr('data-idpat');
      var uniqid = $(this).attr('data-uniqid');
      var idser = $(this).attr('data-idser');
      var name = $(this).attr('data-name');
      var price = $(this).attr('data-price');
      var tax = $(this).attr('data-tax');
      var created_at = $(this).attr('data-created_at');

      var units = $(this).closest("tr").find("input[name='units']").val();
      var msg = "Tratamiento: " + name + ", ya ha sido añadido.";

      try {

        budgetArray.forEach( function(elementObj, key, array) {
          if (elementObj.idser == idser)
            throw msg;
        });

        var obj = {
          'idpat' : idpat,
          'uniqid' : uniqid,
          'idser' : idser,
          'units' : units,
          'price' : price,
          'tax' : tax,
          'created_at' : created_at
        };

        budgetArray.push(obj);

        newRowContent = '  <tr class="fonsi13" id="budgetId_' + idser + '">';
        newRowContent += '   <td class="wid180">' + name + '</td>';
        newRowContent += '   <td class="wid70 textcent">' + units + '</td>';
        newRowContent += '   <td class="wid70 textcent">' + price + ' € </td>';
        newRowContent += '   <td class="wid50">';
        newRowContent += '     <div class="btn-group">';
        newRowContent += '       <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">';
        newRowContent += '         <i class="fa fa-times"></i>  <span class="caret"></span>';
        newRowContent += '       </button>';
        newRowContent += '       <ul class="dropdown-menu" role="menu">';
        newRowContent += '         <li> <button type="button" class="delBudgetLine"> <i class="fa fa-times"></i> Borrar</button></li> ';
        newRowContent += '       </ul>';
        newRowContent += '     </div>';
        newRowContent += '   </td>';
        newRowContent += '   <td class="wid50"></td>';
        newRowContent += ' </tr>';

        $("#budgets_list").append(newRowContent);

      } catch(err) {

        return util.showPopup(err, false, 2500);

      }
     });
  });

</script>