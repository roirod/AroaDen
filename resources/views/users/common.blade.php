
        <div id="saveform">
          <form class="saveform" v-on:submit.prevent="onSubmit">
            {!! csrf_field() !!}

            @if (!$is_create_view)
              <input type="hidden" name="_method" value="PUT">
            @endif

            @include('form_fields.common_alternative')
          </form>
        </div>

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
                var data = $("form.saveform").serialize();

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
