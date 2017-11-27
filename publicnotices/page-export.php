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
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['pnotices'] )  ) {
  /* download notices */
  echo "<p>should be downloading them....</p>";
      /* $fp = fopen('fv-export.txt', 'w');
      fwrite($fp, $_POST['pnotices' );
      fclose($fp); */

    /*   header('Content-Type: application/text');
      header('Content-Disposition: attachment; filename="fv-export.txt"');
      header('Pragma: no-cache');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      echo $_POST['pnotices'];
      //exit; */

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
			<input type="date" name="targetday" min="2000-01-02"><br>
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
            //$post_text = htmlspecialchars($post_text , ENT_QUOTES);
            //$schar = array("ë", "§", "©", "•","●","—","–")
            //$rchar = array("&euml;","&sect;", "&copy;", "&#8226;", "&#8226;", "&mdash;", "&ndash;");
            //$post_text = str_replace($schar, $rchar, $post_text); */
            $post_text = str_replace("•","&#8226;", $post_text );
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
          //echo "<p>thats it. </p>";
          echo  "<p>Found ".$pcount." of (".$count.") public notices for ".$indate."</p>";
          //echo "<pre>".$out_html."</pre>";
          echo "<p>here we go again....</p>";
          echo '<div id="public-notice-out">';
          echo $out_html;
          echo '</div>';
          //echo"<pre>";  var_dump($pnotice_array);  echo "</pre>";
          ?>
          <form action="<?php the_permalink(); ?>" method="post">
            <input type="hidden" name="pnotices" value="<?php echo $out_html ?>">
            <input type="submit" name="submit_parse"  value="Download ">
          </form>
          <?php



        echo "<p>Writing2....</p>";

  /*     $fp = fopen('fv-export.txt', 'w');
       fwrite($fp, $out_html );
       fclose($fp);

       header('Content-Type: application/text');
       header('Content-Disposition: attachment; filename="fv-export.txt"');
       header('Pragma: no-cache');
       header('Expires: 0');
       header('Cache-Control: must-revalidate');
       header('Pragma: public');
       header('Content-Length: ' . filesize('fv-export.txt'));
       readfile('fv-export.txt');
       exit;
*/
       //exit;
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
