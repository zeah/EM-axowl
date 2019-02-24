<?php 

defined('ABSPATH') or die('Blank Space');


final class Axowl_abfp {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->wp_hooks();
	}

	private function wp_hooks() {
		// add_action('the_post', [$this, 'ab']);
		add_filter('the_content', [$this, 'ab'], 1);
	}	


	public function ab($content) {

		if (!is_front_page()) return $content;

		$opt = get_option('em_axowl');

		if (!isset($opt['abtesting'])) return $content;

		$ab = [];

		if (isset($opt['ab_id1']) && $opt['ab_id1'] != 'Inactive') $ab[] = 'ab_id1';
		if (isset($opt['ab_id2']) && $opt['ab_id2'] != 'Inactive') $ab[] = 'ab_id2';
		if (isset($opt['ab_id3']) && $opt['ab_id3'] != 'Inactive') $ab[] = 'ab_id3';
		if (isset($opt['ab_id4']) && $opt['ab_id4'] != 'Inactive') $ab[] = 'ab_id4';

		$id = $ab[rand(0, sizeof($ab)-1)];

		$name = '';

		$post = get_post($opt[$id]);

		if (!$post) return $content;

		// $d = [];

		switch ($id) {
			case 'ab_id1': $name = $opt['ab_name1']; break;
			case 'ab_id2': $name = $opt['ab_name2']; break;
			case 'ab_id3': $name = $opt['ab_name3']; break;
			case 'ab_id4': $name = $opt['ab_name4']; break;
		}

		if (!$name) $name = $post->post_name;

		return $post->post_content.'<input type="hidden" name="abtesting-name" value="'.$name.'">';

		// return $opt[$id].'<br>'.$name;

		// $post = '';
		// foreach ($d as $key => $value) {
		// 	$post = get_post($key);
		// 	break;
		// }

		// foreach ($d as $key => $value)
		// 	if (!$value) $d[$key] = $post->post_name;


		// return print_r($id, true);

		// for ($i = 1; $i < 5; $i++)
		// 	if (isset($opt['abtest'.$i.'_id']) && $opt['abtest'.$i.'_id'] != 'Inactive') 
		// 		$arr['abtest'.$i.'_id'] = isset($opt['abtest'.$i.'_name']) ? $opt['abtest'.$i.'_name'] : '';

		// $rand = rand(0, sizeof($arr)-1);

		// // $id = $opt['abtest'.($rand+1).'_id'];
		// // $name = $opt['abtest'.($rand+1).'_name'];

		// $post = get_post($id);
		// return print_r($arr, true).'<br>'.$name.'<br>'.$id.'<br>'.sizeof($arr );

		// // if (!$post) return $content;

		// // return $post->post_content;
		// // return $id.'<br>'.$name.'<br>'.$post->post_name;
		// // return $id.'<br>'.$name;
		// // $ab = $arr[rand(0, sizeof($arr)-1)];
		// $name = '';

		// switch ($ab) {
		// 	case 'abtest1_id': $name = isset($opt['abtest1_name']) ? $opt['abtest1_name'] : ''; break;
		// 	case 'abtest2_id': $name = isset($opt['abtest2_name']) ? $opt['abtest2_name'] : ''; break;
		// 	case 'abtest3_id': $name = isset($opt['abtest3_name']) ? $opt['abtest3_name'] : ''; break;
		// 	case 'abtest4_id': $name = isset($opt['abtest4_name']) ? $opt['abtest4_name'] : ''; break;
		// }


		// if (!$name) $name = $post->post_name;
		// // if (isset($opt['abtest1_id'])) $arr[] = $opt['abtest1_id'];  
		// // if (isset($opt['abtest2_id'])) $arr[] = $opt['abtest2_id'];  
		// // if (isset($opt['abtest3_id'])) $arr[] = $opt['abtest3_id'];  
		// // if (isset($opt['abtest4_id'])) $arr[] = $opt['abtest4_id'];  

		// // global $post;

		// // $post = get_post(3962);
		// // wp_die('<xmp>'.print_r($post, true).'</xmp>');
		
		// return print_r($arr, true).'<br>'.$name.'<br>'.$ab;

		// return $post->post_content.'<input type="hidden" id="abtesting" name="abtesting-page" value="'.$name.'">';
		// return $post;

		// wp_die('<xmp>'.print_r(is_front_page(), true).'</xmp>');
		
		// $opt = get_option('em_axowl');

	    // update_option('page_on_front', 4085);
	    // update_option('show_on_front', 'page');

		// if (!is_home()) return;

		// if (isset($opt['abtesting'])) wp_die('<xmp>'.print_r($opt, true).'</xmp>');
		
	}
}