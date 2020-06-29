
<div class="form-group col-sm-2"> 
  <label class="control-label text-left mar10">{{ Lang::get('aroaden.place') }}</label>
  @if ($is_create_view)

    <input type="text" class="form-control" name="place" value="{{ $company->company_city }}" maxlength="111" required>

  @else

    <input type="text" class="form-control" name="place" value="{!! $place !!}" maxlength="111" required> 

  @endif
</div>