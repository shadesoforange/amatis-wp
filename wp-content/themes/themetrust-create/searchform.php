<?php
/**
 * @package create
 */
?>

<?php $search_text = __('Type and press enter to search.', 'create'); ?>
<form method="get" id="searchform"   action="<?php bloginfo('url'); ?>/">
<input type="text" class="search" placeholder="<?php echo $search_text; ?>" name="s" id="s" />
<input type="hidden" id="searchsubmit" />
</form>