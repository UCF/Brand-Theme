<?php
$search_query = isset( $_GET['s'] ) ? htmlentities( $_GET['s'] ) : '';
?>
<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
	<label for="s">Search:</label>
	<input type="text" value="<?php echo $search_query; ?>" name="s" class="form-control search-field" id="s" placeholder="Enter your search term here...">
	<button type="submit" class="search-submit btn btn-primary">Search</button>
</form>
