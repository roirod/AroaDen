	<div class="col-sm-6 pad4">
		<i class="fa fa-circle-o fa-min"></i> 
    {{ @trans('aroaden.positions') }}: 

    @php

      $staffPositionsEntries = trim($staffPositionsEntries);

    @endphp

    @if ($staffPositionsEntries != '')
      <span class="bggrey pad4">
       {{ $staffPositionsEntries }} 
      </span>
    @endif
  </div> 