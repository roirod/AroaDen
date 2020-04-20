	<div class="col-sm-3 pad4">
		<i class="fa fa-circle-o fa-min"></i> 
    {{ @trans('aroaden.tele1') }}: 

    @php

      $tel1 = trim($object->tel1);

    @endphp

    @if ($tel1 != '')
      <span class="bggrey pad4">
       {{ $object->tel1 }} 
      </span>
    @endif
  </div>