  
  <div class="row">
    <div class="col-sm-12">
      <fieldset>

        <legend>
          {!! $legend !!}
        </legend>

        @include('form_fields.show.form_errors')

        <div id="saveform">
          <form id="form" ref="form" v-on:submit.prevent="onSubmit">
            {!! csrf_field() !!}

            @if (!$is_create_view)
              <input type="hidden" name="_method" value="PUT">
            @endif

            @if ($is_create_view && isset($idnav))
              <input type="hidden" name="idpat" value="{{ $idnav }}">
            @endif

            @include('form_fields.common_alternative')
          </form>
        </div>

      </fieldset>
    </div>
  </div>

  <script type="text/javascript">
    var rq_url;
  </script>

  @if ($is_create_view)

    <script type="text/javascript">
      var rq_url = '{!! url("/$main_route") !!}';
    </script>

  @else

    <script type="text/javascript">
      var rq_url = '{!! url("/$main_route/$id") !!}';
    </script>

  @endif

  <script type="text/javascript">

    var vmsaveform = new Vue({
      el: '#saveform',
      methods: {
        onSubmit: function() {
          const formData = new FormData(this.$refs['form']);
          const data = {};

          for (let [key, val] of formData.entries()) {
            Object.assign(data, { [key]: val })
          }

          axios.post(rq_url, data).then(function (res) {
            if (res.data.error)
              return util.showPopup(res.data.msg, false);

            util.showPopup();
            util.redirectTo(res.data.redirectTo);
          });
        }
      }
    });

  </script>


