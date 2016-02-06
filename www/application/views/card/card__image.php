<?php
$sizes = (!isset($landscape) || $landscape === FALSE) ? 'height="285" width="199"' : 'height="199" width="285"';
?>
<img class="item-media card-picture" src="<?php echo $image ; ?>" alt="" <?php echo $sizes ; ?> />