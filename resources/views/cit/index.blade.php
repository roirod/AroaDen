@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')

<meta name="_token" content="{!!csrf_token()!!}"/>

<div class="row">
  <div class="col-sm-12">
  	<form role="form" class="form" action="{{ url('/Citas/list') }}" method="post">
  		{!! csrf_field() !!}

  		<div class="input-group">
  		 	<span class="input-group-btn pad4"> <p> &nbsp;<i class="fa fa-clock-o"></i> Citas: </p> </span>
  		  <div class="col-sm-2"> 
  		    <span class="input-group-btn"> 
  		      <select name="selec" class="form-control busca_class">
  		        <option value="hoy" selected>hoy</option> 
  		        <option value="1semana">+1 semana</option>
  		        <option value="1mes">+1 mes</option>
  		        <option value="3mes">+3 meses</option> 
  		        <option value="1ano">+1 año</option>
  		        <option value="menos1mes">-1 mes</option>
  		        <option value="menos3mes">-3 meses</option>
  		        <option value="menos1ano">-1 año</option>
  		        <option value="menos5ano">-5 años</option>
  		        <option value="menos20ano">-20 años</option>
  		        <option value="todas">todas</option>
  		      </select> 
  		    </span>
  		  </div>
</div>  </form>  </div>  </div>

<div class="row">
  <div class="col-sm-12">
    <form role="form" class="form" action="{{ url('/Citas/list') }}" method="post">
      {!! csrf_field() !!}

      <input type="hidden" name="selec" value="rango">

      <div class="input-group pad10"> 
        <span class="input-group-btn pad4">  <p> Fecha de: </p> </span>
        <div class="col-sm-4"> 
          <input name="fechde" type="date" class="busca_class" autofocus required>
        </div>
        <div class="col-sm-1">
          <span class="input-group-btn pad4">  <p> hasta: </p> </span> 
        </div>
        <div class="col-sm-4 input-group">
          <input name="fechha" type="date" class="busca_class" required>
        </div>
      </div>
    
    </form>
</div> </div>


<div class="row">
  <div class="col-sm-12" id="item_list">

  @if ($count === 0)

    <h3> No hay citas para <span class="label label-success fonsi16"> hoy </span> </h3>

  @else

    <p>
      <span class="text-muted"> Citas de: </span>
      <span class="label label-success fonsi16"> hoy </span>
    </p>

    <div class="panel panel-default"> 
      <table class="table">
         <tr class="fonsi16 success">
             <td class="wid50"></td>
             <td class="wid290">Paciente</td>
             <td class="wid110">Hora</td>
             <td class="wid110">Día</td>
             <td class="wid230">Notas</td>
         </tr>
       </table>
 
      <div class="box400">

        <table class="table table-hover">
 
          @foreach ($citas as $cita)
            <tr>
                <td class="wid50">
                  <a href="{{url("/Pacientes/$cita->idpac")}}" target="_blank" class="btn btn-default" role="button">
                    <i class="fa fa-hand-pointer-o"></i>
                  </a> 
                </td>

                <td class="wid290"> 
                  <a href="{{url("/Pacientes/$cita->idpac")}}" class="pad4" target="_blank">
                      {{$cita->apepac}}, {{$cita->nompac}} 
                  </a>
                </td>

                <td class="wid110"> {{ substr( $cita->horacit, 0, -3 ) }} </td>
                <td class="wid110"> {{date('d-m-Y', strtotime($cita->diacit) )}} </td>
                <td class="wid230"> {{$cita->notas}} </td>
            </tr>
          @endforeach

        </table>
      </div> </div> 

    @endif

</div> </div>


@endsection
	 
@section('js')
    @parent

	  <script type="text/javascript" src="{{ asset('assets/js/minified/polyfiller.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/main.js') }}"></script>
	  
@endsection

@section('footer_script')

  <script>
    
    $(document).ready(function() {
      $.ajaxSetup({
          headers: { 
            'X-CSRF-Token' : $('meta[name=_token]').attr('content')
          }
      }); 

      $(".busca_class").on('change', function(evt) {
        var data = $(this).parents('form').serialize();

        var selec = $(this).parents('form').find("input[name=selec]").val();
        var fechde = $(this).parents('form').find("input[name=fechde]").val();
        var fechha = $(this).parents('form').find("input[name=fechha]").val();

        if (selec == 'rango') {

          if (fechde == '' || fechha == '' || typeof fechde == 'undefined' || typeof fechha == 'undefined') {

            var message = '<h3 class="text-danger"> Introduzca dos fechas válidas en  Fecha de: y hasta: </h3>';
            $('#item_list').html(message);
            return;

          } else {

            sendAjaxRequest(data);
            
          }

        } else {

          sendAjaxRequest(data);
        }

        evt.preventDefault();
        evt.stopPropagation();
      });


      function sendAjaxRequest(data) {

        var message = '<img src="/assets/img/loading.gif" /> &nbsp; &nbsp; <span class="text-muted"> Cargando... </span>';
        $('#item_list').html(message);

        $.ajax({
            type : 'POST',
            url  : '/Citas/list',
            dataType: "json",
            data : data,

        }).done(function(response) {
          var html = '';

          if (response.msg !== false) {
            html = '<h3 class="text-danger">' + response.msg + '</h3>';

          } else {
            html = '<p class="text-muted">' + response.citas_de + '.</p>';
            html += '<div class="panel panel-default">';
            html += '   <table class="table">';
            html += '     <tr class="fonsi16 success">';
            html += '       <td class="wid50"> &nbsp; </td>';
            html += '       <td class="wid290"> Paciente </td>';
            html += '       <td class="wid110"> Hora </td>';
            html += '       <td class="wid110"> Día </td>';
            html += '       <td class="wid230"> Notas </td>';
            html += '     </tr>';
            html += '   </table>';
            html += '  <div class="box400">';
            html += '    <table class="table table-hover">';

            $.each(response.citas, function(index, object){
              html += '  <tr>';
              html += '    <td class="wid50">';
              html += '      <a href="/Pacientes/'+object.idpac+'" target="_blank" class="btn btn-default" role="button">';
              html += '        <i class="fa fa-hand-pointer-o"></i>';
              html += '      </a>';
              html += '    </td>';
              html += '    <td class="wid290">';
              html += '      <a href="/Pacientes/'+object.idpac+'" class="pad4" target="_blank">';
              html +=           object.apepac + ', ' + object.nompac;
              html += '      </a>';
              html += '    </td>';
              html += '    <td class="wid110">' + object.horacit.slice(0, -3); + '</td>';
              html += '    <td class="wid110">' + object.diacit.split("-").reverse().join("-") + '</td>';
              html += '    <td class="wid230">' + object.notas + '</td>';
              html += '  </tr>';
            });

            html += '    </table>';
            html += '  </div> </div>';
            html += ' </div> </div>';               
          }

          $("#item_list").html(html);                 
        }).fail(function() {
          $("#item_list").html('<h3> Hubo un problema. </h3>');
        });

      }

    });

  </script>

@endsection