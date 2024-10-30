jQuery(document).ready(function($) {

  $('<?php echo $button_selector ?>').click(function(e) {
    e.preventDefault();
    $('<?php echo $container_selector ?>').slideUp(<?php echo $close_time ?>, function () {
      FLBuilderLayout._initModuleAnimations();
    });
    Cookies.set('<?php echo $cookie_name ?>', Date(), {
      expires: <?php echo $cookie_expires ?>,
      domain: '<?php echo $cookie_domain ?>',
      secure: true
    });
  });

  if (Cookies.get('<?php echo $cookie_name ?>') === undefined) {
    $('<?php echo $container_selector ?> ').show();
  }

});
