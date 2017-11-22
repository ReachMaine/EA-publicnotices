<?php
/*
Template name: Export Notices
* author: zig
* date: 21Nov17
* version: 1.0
* based on flatsome page-right-sidebar page template.
*/
if ( 'GET' == $_SERVER['REQUEST_METHOD'] && !empty( $_GET['action'] ) && $_GET['action'] == 'getexport' ) {
    $indate = $_GET['targetday'];
}

function get_public_notices ($in_date_str) {
  $out_html = "<p>yep</p>";
  global $wpdb;
	$in_date_date = new DateTime($in_date_str);
	$datestr = $in_date_date->format("Ymd");
	$sqlreq = 'SELECT CONCAT("<notice><subcategory_id>17</subcategory_id><date>",
							DATE_FORMAT(cast(post_date AS DATE),"%m/%d/%Y"),
							"</date><text>",
              post_title,
              post_content,"</text></notice>")
              FROM ea_13_posts WHERE cast(post_date AS DATE) = cast("'.$datestr.'" as date) and post_status in ("publish", "future") and post_type = "post" limit 100';
	$sqlresult = $wpdb->get_results($sqlreq);
  echo"<pre>";  var_dump($sqlresult);  echo "</pre>";
  return $out_html;
}


get_header(); ?>
<?php if( has_excerpt() ) { ?>
<div class="page-header">
	<?php the_excerpt(); ?>
</div>
<?php } ?>

<div class="page-wrapper page-right-sidebar">
<div class="row">

<div id="content" class="large-9 left columns" role="main">
	<div class="page-inner">
		<form method="get" action="<?php the_permalink(); ?>">
			<p>Enter date for Public Notices export:<br></p>
			<input type="date" name="targetday" min="2000-01-02"><br>
			<input type="submit" name="getexport">
			<input name="action" type="hidden" id="action" value="getexport" />
		</form>

		<p> indate is: <?php  echo $indate; ?> </p>

		<div id="export_notices">
			<?php if ($indate) {
        echo "<p>";
				echo get_public_notices($indate);
        echo "</p>";
			} ?>
		</div>

	</div><!-- .page-inner -->
</div><!-- .#content large-9 left -->

<div class="large-3 columns right">
<?php get_sidebar(); ?>
</div><!-- .sidebar -->

</div><!-- .row -->
</div><!-- .page-right-sidebar container -->

<?php get_footer(); ?>
