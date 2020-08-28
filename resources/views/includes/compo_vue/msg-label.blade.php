
  <script type="text/javascript">

    Vue.component('msg-label', {
      props: ['msg'],
      template: 
        '<transition name="fade">' +
        '  <p class="label label-success fonsi15"> @{{ msg }} </p>' +
        '</transition>'
    });
    
  </script>
