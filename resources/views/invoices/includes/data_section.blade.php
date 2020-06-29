
          <div class="col-sm-5 mar10 fonsi11">
            <p class="fonsi12">
              - {!! @trans("aroaden.company") !!}
            </p>
            {!! $company->company_name !!}
            <br>    
            {!! $company->company_address !!}
            <br>
            {!! $company->company_city !!}
            <br>
            {!! $company->company_nif !!}
          </div>
          
          <div class="col-sm-5 mar10 fonsi11">
            <p class="fonsi12">
              - {!! @trans("aroaden.patient") !!}
            </p>
            {!! $object->name.' '.$object->surname !!}
            <br>
            {!! $object->address !!}
            <br>
            {!! $object->city !!}
            <br>
            {!! $object->dni !!}
          </div>