	<div class="col-sm-3 pad4">
		<i class="fa fa-circle-o fa-min"></i> 
    {{ @trans('aroaden.tele2') }}: 

    @php

      $tel2 = trim($object->tel2);

    @endphp

    @if ($tel2 != '')
      <span class="bggrey pad4">
       {{ $object->tel2 }} 
      </span>
    @endif
  </div>