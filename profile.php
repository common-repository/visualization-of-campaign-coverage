<?php
/**
 * Profile (infobox) dropin template
 * @since .1
 * @package ccviz
 */

//call API to get necessary data 
$timeline = $daylifeAPI->call("topic", "getTimeline", array("topic_id" => $topic_id, "start_time"=> "2010-04-01", "end_time"=>"2010-10-31"));
$quotes = $daylifeAPI->call("topic", "getRelatedQuotes", array("topic_id" => $topic_id, 'limit' => 1));
$images = $daylifeAPI->call("topic", "getRelatedImages", array("topic_id" => $topic_id, "limit" => 5));

//format and output...
?>
<style type="text/css">
#mentions_chart table, #blog_mentions_chart table {
	border:0px;
	margin:0px;
}

#mentions_chart tr td, #blog_mentions_chart tr td {
	border:0px;
	padding:0px;
}
</style>
<script type='text/javascript' src='https://www.google.com/jsapi'></script>
    <script type='text/javascript'>
      google.load('visualization', '1', {'packages':['annotatedtimeline']});
	google.setOnLoadCallback(drawArticleChart);
	google.setOnLoadCallback(drawBlogChart);
      function drawArticleChart() {
        var data = new google.visualization.DataTable();
		data.addColumn('date', 'Date');
		data.addColumn('number', 'Article Mentions');
		data.addRows([
			<?php
			 $days = count($timeline['daily_counts']);
			 for($i=0; $i < $days; $i++) {
				$timestamp = $timeline['daily_counts'][$i]["timestamp_epoch"];
				$date["year"] = date("o", $timestamp);
				$date["month"] = date("n", $timestamp)-1;
				$date["day"] = date("j", $timestamp);
				print "[ new Date(".$date['year'].", ".$date['month'].",".$date["day"]."), ".$timeline['daily_counts'][$i]["article_count"]."]";
				if ($i != $days-1) {
					print ",";
				}
			}
			?>
			]);
			var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('mentions_chart'));
			chart.draw(data, {displayZoomButtons:false, colors:["#551A8B"], scaleType:'maximized'});
	}
	
	function drawBlogChart() {
        var data = new google.visualization.DataTable();
		data.addColumn('date', 'Date');
		data.addColumn('number', 'Blog Mentions');
		data.addRows([
			<?php
			 $days = count($timeline['daily_counts']);
			 for($i=0; $i < $days; $i++) {
				$timestamp = $timeline['daily_counts'][$i]["timestamp_epoch"];
				$date["year"] = date("o", $timestamp);
				$date["month"] = date("n", $timestamp)-1;
				$date["day"] = date("j", $timestamp);
				print "[ new Date(".$date['year'].", ".$date['month'].",".$date["day"]."), ".$timeline['daily_counts'][$i]["blog_count"]."]";
				if ($i != $days-1) {
					print ",";
				}
			}
			?>
			]);
			var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('blog_mentions_chart'));
			chart.draw(data, {displayZoomButtons:false, colors:["#FF8C00"], scaleType:'maximized'});
	}
	</script>

</head>
<div class="candidate-module">
	<div class="profile">
        <div class="profile-photo"><img src="<?php echo get_post_meta($atts['candidate'], 'ccviz_image_url', true) ?>" alt="Candidate Profile" border="0"></div>
        <?php $data = get_post($atts['candidate']); ?>
    	<span class="name"><?php echo $data->post_title; ?></span><br />
        <span class="race">Nevada Senate</span><br />
        <span class="party-description">Democrat</span><br />
        <span class="type">Incumbant</span><br />
         <div style="clear:both;height:1px;">&nbsp;</div>
        
        <div class="section">
        <span class="money-title">Total Receipts: </span>
        <span class="money-amount">$21,427,449</span>
        </div>
        <div class="bio section">
        <div class="header"><img class="arrowDown" src="http://ben.balter.com/sandbox/hacksandhackers/wp-content/plugins/campaign-viz/arrow_down.gif">BIO</div>
      <div class=" body-text"><?php echo $data->post_content; ?> </div>
	</div>
    <div class="mentions section">
    	<div class="header"><img class="arrowDown" src="http://ben.balter.com/sandbox/hacksandhackers/wp-content/plugins/campaign-viz/arrow_down.gif">ARTICLE MENTIONS</div>
    	<div class="chart" id="mentions_chart" style="width:280px; height:175px;"></div>
    </div>
     <div class="mentions section">
    	<div class="header"><img class="arrowDown" src="http://ben.balter.com/sandbox/hacksandhackers/wp-content/plugins/campaign-viz/arrow_down.gif">BLOG MENTIONS</div>
    	<div class="chart" id="blog_mentions_chart" style="width:280px; height:175px;"></div>
    </div>
    <div class="quotes section">
        <div class="header"><img class="arrowDown" src="http://ben.balter.com/sandbox/hacksandhackers/wp-content/plugins/campaign-viz/arrow_down.gif">QUOTE</div>
        <div class="quote-block"><img src="<?php echo  WP_PLUGIN_URL . '/campaign-viz/'; ?>quote-begin.png" alt="&quot;"><span class="quote-text"><?php print $quotes['quote'][0]["quote_text"]; ?></span><img src="<?php echo  WP_PLUGIN_URL . '/campaign-viz/'; ?>quote-end.png" alt="&quot;"></div>
		<div style="text-align:right;"><a href="<?php print $quotes['quote'][0]["article"]["url"]; ?>">Source</a></div>
    </div>
    <div class="photos section" ><div class="header"><img class="arrowDown" src="http://ben.balter.com/sandbox/hacksandhackers/wp-content/plugins/campaign-viz/arrow_down.gif">PHOTOS</div><div class="images">
    	<?php
		foreach ($images['image'] as $image) {
			print "<a href='".$image['article']['daylife_url']."' style='border:0px; text-decoration:none;'><img style='padding:3px;' src='".$image['thumb_url']."'></a>";
		}
		?>
    </div></div>
    
</div>
<div id="credits" class="section"> 
		<em>Powered By</em><br />
		<a href="http://corp.daylife.com/" title="Daylife" Alt="Daylife"><img src="<?php echo  WP_PLUGIN_URL . '/campaign-viz/'; ?>daylife_logo.jpg" /></a>
</div>
</div>
</div>

