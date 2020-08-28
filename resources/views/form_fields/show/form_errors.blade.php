 
  <div class="col-sm-12 fonsi14" id="form_errors">

    <transition name="custom-classes-transition" enter-active-class="animate__animated animate__zoomIn">
      <div v-if="showErrors">
        <div class="col-sm-6 alert alert-danger">
          <p>
            {{ @trans('aroaden.form_errors') }}
          </p>
          <br>
          <ul>         
            <li v-for="(value, key) in object">
              @{{ value[0] }}
            </li>
          </ul>
        </div>
      </div>
    </transition>

  </div>

  <script type="text/javascript">

    var vm_form_errors = new Vue({
      el: '#form_errors',
      data: {
        object: false,
        show_errors: false,
      },     
      computed: {
        showErrors: function () {
          if (this.object !== false) {
            this.show_errors = true;
          } else {
            this.show_errors = false;
          }

          return this.show_errors;
        }
      }
    });

  </script>

