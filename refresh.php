<?php
	require_once('../../../wp-load.php');
	$posts = ccviz_query();
	if ( $posts->have_posts() ) : while ( $posts->have_posts() ) : $posts->the_post();
		$daylife_id = ccviz_get_daylife_id( $post->ID );
		$candidate_info = $daylifeAPI->call('topic','getInfo',array('topic_id'=>$daylife_id,'include_wikipedia_info'=>1));
		$bio = stripslashes( $candidate_info['topic'][0]['wiki_info'][0]['abstract'] );
		$image = stripslashes( $candidate_info['topic'][0]['hero_image']['hero_image_url'] );
		$update = array();
		$update['ID'] = $post->ID;
		$update['post_content'] = $bio;
		wp_update_post($update);
		update_post_meta($post->ID,'ccviz_image_url',$image);
 	endwhile; endif;
?>