<br> 

<div class="row"> 
  <div class="col-sm-12"> 
    <div class="input-group"> 
      <span class="input-group-btn pad10">  
        <p>{!! @trans('aroaden.company_data') !!}</p> 
      </span>
      <div class="btn-toolbar pad4" role="toolbar"> 
        <div class="btn-group">
          <a href="{{ url("/$company_route/$form_route") }}" role="button" id="edit_button" class="btn btn-sm btn-success">
            <i class="fa fa-edit"></i> {!! @trans('aroaden.edit') !!}
          </a>
        </div>  
</div> </div> </div> </div>

<div class="row">
  <div class="col-sm-12 fonsi15">

    @foreach ($main_loop as $item)

      <?php
        $aroaden_item_name = "aroaden.".$item['name'];
        $item_name = $item['name'];
        $item_type = $item['type'];
      ?>

      @if ($item['type'] == 'textarea')

         <br>
         <div class="form-group {{ $item['col'] }} pad10">
            {!! @trans($aroaden_item_name) !!}
            <br>
            <div class="box200">{!! nl2br(e($obj->$item_name)) !!}</div>
            <hr>
            <br>
         </div>

      @else

          <div class="form-group {{ $item['col'] }}">
            <label class="control-label text-left mar10">{!! @trans($aroaden_item_name) !!}</label>
            <input type="{!!  $item_type !!}" class="form-control" value="{!! $obj->$item_name !!}" readonly>
          </div>

      @endif

    @endforeach

 </div>
</div>

<script type="text/javascript">    
  $(document).ready(function() {
    $("#edit_button").on('click', function(evt) {
      evt.preventDefault();
      evt.stopPropagation();

      var obj = {      
        url  : $(this).attr('href')
      };

      return util.processAjaxReturnsHtml(obj);
    });
  });
</script>