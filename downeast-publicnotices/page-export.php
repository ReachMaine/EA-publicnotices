<?php
/*
 *Template name: Export Notices
 * author: zig
 * date: 9 jul 18
 * version: 1.2
 * based on oshine default full width template for displaying pages.
 *
*/

if ( 'GET' == $_SERVER['REQUEST_METHOD'] && !empty( $_GET['action'] ) && $_GET['action'] == 'getexport' ) {
    $indate = $_GET['targetday'];
    $in_date_date = new DateTime($indate);
    $datestr = $in_date_date->format("Ymd");
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
get_header();
global $be_themes_data;
while(have_posts()): the_post();
	$be_pb_class = 'page-builder';
	$be_pb_disabled = get_post_meta( $post->ID, '_be_pb_disable', true );
	$sticky_sections = get_post_meta( $post->ID, 'be_themes_sticky_sections', true );
	$sticky_scroll_type = get_post_meta( $post->ID, 'be_themes_sticky_scroll_type', true );
	$sticky_overlay = get_post_meta( $post->ID, 'be_themes_sticky_overlay', true );
	if( !function_exists( 'is_edited_with_tatsu' ) || !is_edited_with_tatsu( get_the_ID() ) ) {
		$be_pb_class = 'be-wrap no-page-builder';
		get_template_part( 'page-breadcrumb' );
	} ?>
	<div id="content" class="no-sidebar-page">
		<div id="content-wrap" class="<?php echo $be_pb_class; ?>">
			<section id="page-content">

				<div class="clearfix<?php echo ( ( isset( $sticky_sections ) && !empty( $sticky_sections ) ) ? " be-sections-wrap" : ""); ?>" <?php echo ( isset( $sticky_sections ) && !empty( $sticky_sections ) ) ? ( 'data-sticky-scroll = "' . $sticky_scroll_type . '" data-sticky-overlay = "' . $sticky_overlay . '"'  ) : ""; ?> >
					<form method="get" action="<?php the_permalink(); ?>">
						<p>Enter date for Public Notices export:<br></p>
						<input type="date" name="targetday" min="2000-01-02" style="max-width:300px;"><br>
						<input type="submit" name="getexport">
						<input name="action" type="hidden" id="action" value="getexport" />
					</form>


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
									// trying to get rid of quotes & bullets and Heidi  Noël special char

									//$trans = get_html_translation_table(HTML_ENTITIES);
									//$post_text = strtr($post_text, $trans); // convert speical chars to htlm equivalents, gets some, I think, do this first...
									//$post_text =  htmlentities($post_text); // try to strip special chars....

									// trying direct approach for some things....
									$post_text =  str_replace("©", '&copy;', $post_text); // no sure about this one.
									$post_text =  str_replace("ë", 'e', $post_text);  // yep works, if dont do the strtr
									// these below dont really work.
									$post_text = str_replace ('“', '&ldquo;', $post_text);
									$post_text = str_replace ('”', '&rdquo;', $post_text);
									$post_text = str_replace ('‘', '&lsquo;', $post_text);
									$post_text = str_replace ('’', '&rsquo;', $post_text);
									if ($post_text) { // if stripping html leaves nothing left (image only)
										$out_html .= '<notice><subcategory_id>17</subcategory_id>';
										$out_html .= '<date>'.$pnotice->p_date.'</date>';
										$out_html .= '<text>';
										$out_html .= $pnotice->post_title.' ';
										$out_html .= $post_text;
										$out_html .= '</text>';
										$out_html .= '</notice>
					'; // include CRLF
										$pcount = $pcount + 1;
									}
									$count = $count + 1;
								} // end for
								echo  "<p>Found ".$pcount." of (".$count.") public notices for ".$indate."</p>";
								?>
								<button onClick ="downloadPN()" style="margin-right: 5px;">Download</button>
								<button onClick ="downloadEAPN()" style="margin-right: 5px;">Download EA </button>
								<button onClick ="downloadMDIPN()">Download MDI</button>
								<?php
								echo '<div id="public-notice-out" style="display: none;">';
								echo $out_html;
								echo '</div>';

						} else {
								echo "<p>No results for given date. <br>Be sure to use pub date and that public notices are dated by pub date</p>";
							}
						} ?>
					</div>
				</div> <!--  End Page Content -->

			</section>
		</div>
	</div>	<?php
endwhile;
get_footer(); ?>

<div id="MDI_sig" style="display: none; visibility:hidden">
<username>mdislander</username>
<password>hancock</password>
</div>
<div id="EA_sig" style="display: none; visibility:hidden">
<username>eamerican</username>
<password>hancock</password>
</div>
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
function downloadEAPN(){
    var a = document.body.appendChild(
        document.createElement("a")
    );
    a.download = "mpn_upload_11.<?php echo $datestr.".xml"; ?>";
    a.href ="data:text/html," + "<xml>" + document.getElementById("EA_sig").innerHTML + document.getElementById("public-notice-out").innerHTML + "</xml>";
    a.click();
}
function downloadMDIPN(){
    var a = document.body.appendChild(
        document.createElement("a")
    );
    a.download = "mpn_upload_20.<?php echo $datestr.".xml"; ?>";
    a.href = "data:text/html,"  + "<xml>" + document.getElementById("MDI_sig").innerHTML + document.getElementById("public-notice-out").innerHTML + "</xml>";
    a.click();
}
</script>
