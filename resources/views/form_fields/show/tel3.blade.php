	<div class="col-sm-3 pad4">
		<i class="fa fa-circle-o fa-min"></i> 
    {{ @trans('aroaden.tele3') }}: 

    @php

      $tel3 = trim($object->tel3);

    @endphp

    @if ($tel3 != '')
      <span class="bggrey pad4">
       {{ $object->tel3 }} 
      </span>
    @endif
  </div> 