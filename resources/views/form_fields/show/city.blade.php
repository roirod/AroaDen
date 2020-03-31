	<div class="col-sm-5 pad4"> 
		<i class="fa fa-circle-o fa-min"></i> 
    {{ @trans('aroaden.city') }}: 

    @php

      $city = trim($object->city);

    @endphp

    @if ($city != '')
      <span class="bggrey pad4">
       {{ $object->city }} 
      </span>
    @endif
  </div> 