<?php 
	$sidebar = screenplan_sidebar_primary();
	$layout = screenplan_layout_class();
	if ( $layout != 'col-1c'):
?>

	<div class="sidebar s1">
		
		<div class="sidebar-content">

			<?php dynamic_sidebar($sidebar); ?>
			
		</div><!--/.sidebar-content-->
		
	</div><!--/.sidebar-->
	
<?php endif; ?>