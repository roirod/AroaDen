	<div class="col-sm-6 pad4">
		<i class="fa fa-circle-o fa-min"></i>
    {{ @trans('aroaden.address') }}: 

    @php

      $address = trim($object->address);

    @endphp

    @if ($address != '')
      <span class="bggrey pad4">
       {{ $object->address }} 
      </span>
    @endif
  </div> 