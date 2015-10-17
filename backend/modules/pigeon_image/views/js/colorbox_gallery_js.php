<?php
use yii\helpers\Url;
?>
<script>
$(document).ready(function(e) 
{
	$(".colorbox_gallery").colorbox(
	{
		href: function()
		{
			return "<?= Url::to(["/pigeon-image/image/image-lightbox"]); ?>?IDalbum="+$(this).data("idalbum")+"&IDimage="+$(this).data("idimage");
		}, 
		width : function() 
		{
			if($(window).width()>1200)
				return "1200px";
			else 
				return "90%";
		},
		height : function() 
		{
			if($(window).height()>600)
				return "600px";
			else 
				return "100%";
		},		
		rel: "group1",
		onComplete: function()
		{
			// centerContent();
		},
	});
	
});
</script>
