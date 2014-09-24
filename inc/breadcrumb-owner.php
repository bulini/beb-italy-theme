<?php global $context; ?>
<ol class="breadcrumb">
  <li><a href="#"><?php echo get_the_title(get_topmost_parent($_GET['prop_id'])); ?></a></li>
  <li><a href="#"><?php echo get_the_title($_GET['prop_id']); ?></a></li>
  <li class="current"><a href="#"><?php echo $context; ?></a></li>
</ol>
