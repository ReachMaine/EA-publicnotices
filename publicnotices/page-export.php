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
  global $wpdb;
  $out_html = "";
	$in_date_date = new DateTime($in_date_str);
	$datestr = $in_date_date->format("Ymd");
  $sqlreq = 'SELECT CONCAT("<notice><subcategory_id>17</subcategory_id><date>",
							DATE_FORMAT(cast(post_date AS DATE),"%m/%d/%Y"),
							"</date><text>",
              post_title,
              post_content,"</text></notice>")
              FROM ea_13_posts WHERE cast(post_date AS DATE) = cast("'.$datestr.'" as date) and post_status in ("publish", "future") and post_type = "post" limit 100';
  $sqlreq = 'SELECT DATE_FORMAT(cast(post_date AS DATE),"%m/%d/%Y") as p_date, post_title, post_content FROM ea_13_posts WHERE cast(post_date AS DATE) = cast("'.$datestr.'" as date) and post_status in ("publish", "future") and post_type = "post" limit 100';
	$sqlresult = $wpdb->get_results($sqlreq);
  return $sqlresult;
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
			<input type="date" name="targetday" min="2000-01-02" style="max-width:300px;"><br>
			<input type="submit" name="getexport">
			<input name="action" type="hidden" id="action" value="getexport" />
		</form>
<p>replacing html </p>
		<div id="export_notices">

			<?php if ($indate) {
				$pnotice_array =  get_public_notices($indate);
        if (count($pnotice_array) > 0) {

          $count = 0;
          $pcount = 0;
          $out_html = "";
          foreach($pnotice_array as $pnotice) {
            $post_text = $pnotice->post_content;
            $post_text = strip_tags($post_text); // strip out all html & php tags.
            $post_text = trim($post_text); // trim
            $post_text = trim(preg_replace('/\t+/', ' ', $post_text)); // replace tabs with a space
            $post_text = mb_convert_encoding($post_text,"HTML-ENTITIES" ); // trying

            if ($post_text) { // if stripping html leaves nothing left (image only)
              $out_html .= '<notice><subcategory_id>17</subcategory_id>';
              $out_html .= '<date>'.$pnotice->p_date.'</date>';
              //echo "<p> post title: ".$pnotice->post_title."</p>";
              $out_html .= $pnotice->post_title.' ';
              $out_html .= $post_text;
              $out_html .= '</notice>';
              $pcount = $pcount + 1;
            }
            $count = $count + 1;
          } // end for
          echo  "<p>Found ".$pcount." of (".$count.") public notices for ".$indate."</p>";
          ?>
          <button onClick ="downloadPN()">Download</button>
          <?php
          echo '<div id="public-notice-out" style="display: none;">';
          echo $out_html;
          echo '</div>';

      } else {
          echo "<p>No results for given date. <br>Be sure to use pub date and that public notices are dated by pub date</p>";
        }
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
<script>
function downloadPN(){
    var a = document.body.appendChild(
        document.createElement("a")
    );
    a.download = "fv-export.txt";
    a.href = "data:text/html," + document.getElementById("public-notice-out").innerHTML;
    a.click();
}
</script>
