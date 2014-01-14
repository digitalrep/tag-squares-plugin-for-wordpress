jQuery(document).ready(function()
{

  jQuery(window).resize(function() {
    blah();
  });
  
  function blah()
  {
    jQuery('#wid').html(jQuery(window).width());
  }

  jQuery(".tagy").hover(function() {
	jQuery(this).animate({ top:12 }, 300);
	jQuery(this).animate({ top:-8 }, 250);
	jQuery(this).animate({ top:6 }, 205);
	jQuery(this).animate({ top:-4 }, 165);
	jQuery(this).animate({ top:2 }, 105);
	jQuery(this).animate({ top:-1 }, 75);
	jQuery(this).animate({ top:0 }, 15);
  }, function() {
  });
});