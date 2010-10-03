<?php

global $hkFC;


function hkFC_is_comment_featured($comment){
	$comment = get_comment($comment);
	if(empty($comment) || is_wp_error($comment)) return null;
	$comment_id = $comment->comment_ID;

	
	$featured = '1' == get_metadata('comment', $comment_id, 'featured', true);
	$featured = apply_filters( 'hkFC_is_comment_featured', $featured, $comment_id );
	
	return $featured;
}

function hkFC_is_comment_buried($comment){
	$comment = get_comment($comment);
	if(empty($comment) || is_wp_error($comment)) return null;
	$comment_id = $comment->comment_ID;

	
	$buried = '1' == get_metadata('comment', $comment_id, 'buried', true);
	$buried = apply_filters( 'hkFC_is_comment_buried', $buried, $comment_id );
	
	return $buried;
}

function hkFC_is_comment_children_buried($comment){
	$comment = get_comment($comment);
	if(empty($comment) || is_wp_error($comment)) return null;
	$comment_id = $comment->comment_ID;

	
	$children_buried = '1' == get_metadata('comment', $comment_id, 'children_buried', true);
	$children_buried = apply_filters( 'hkFC_is_comment_children_buried', $children_buried, $comment_id );
	
	return $children_buried;
}







class HkFC extends HkFC_HkTools{

	public $debug=false;
	protected $startup=true;
	



	public function __construct(){
		parent::__construct();
		
		global $hkFC_Op;
		$this->op=$hkFC_Op;
		
	}
	
	public function startup(){

		add_action('comment_post', array($this,'postingComment'),20);
		add_action('edit_comment', array($this,'editingComment'),20);
		
		add_action('admin_menu', array($this,'register_meta_box'));
		add_filter('comment_row_actions', array($this,'comment_row_actions'));
		add_action('admin_init', array($this,'requestFromCommentsListPage'));
		
		add_filter('comment_class', array($this,'comment_class'));
	
	}
//


	function comment_class($classes){

		$comment = get_comment($null);
		if(empty($comment) || is_wp_error($comment)) return $classes;
		
		$comment_class_featured	= apply_filters( 'hkFC_comment_class_featured',	"featured");
		$comment_class_buried	= apply_filters( 'hkFC_comment_class_buried',	"buried");
		$comment_class_children_buried	= apply_filters(
					'hkFC_comment_class_children_buried',	"children_buried");


		if(hkFC_is_comment_featured($comment->comment_ID) && !empty($comment_class_featured))
			$classes[]=$comment_class_featured;
			
		if(hkFC_is_comment_buried($comment->comment_ID) && !empty($comment_class_buried))
			$classes[]=$comment_class_buried;
			
		if(hkFC_is_comment_children_buried($comment->comment_ID) &&
			!empty($comment_class_children_buried))
						$classes[]=$comment_class_children_buried;



		return $classes;
	}







	public function postingComment($comment_id){
		$featured = $_POST['hikari-featured'];
		$buried = $_POST['hikari-buried'];
		$children_buried = $_POST['hikari-children-buried'];
		
		$this->setFeatured($comment_id,$featured);
		$this->setBuried($comment_id,$buried);
		$this->setChildrenBuried($comment_id,$children_buried);
	}

	public function editingComment($comment){

		$comment = get_comment($comment);
		if(empty($comment) || is_wp_error($comment)) return;
		$comment_id = $comment->comment_ID;
		
	
		if(!wp_verify_nonce( $_POST['HkFC_nonce'], plugin_basename(__FILE__).$comment->comment_ID.'-HkFC_update' ))
			return;

		if( !current_user_can('edit_post', $comment->comment_post_ID) )
			return;
			
			
			
		$featuredOld = hkFC_is_comment_featured($comment);
		$featuredNew = $_POST['hikari-featured'];
		
		$buriedOld = hkFC_is_comment_buried($comment);
		$buriedNew = $_POST['hikari-buried'];
		
		$childrenBuriedOld = hkFC_is_comment_buried($comment);
		$childrenBuriedNew = $_POST['hikari-children-buried'];
		
		
		
		if(!$featuredNew){
			if($featuredOld) $this->setFeatured($comment_id,false);
		}
		else{
			$this->setFeatured($comment_id,$featuredNew);
		}

		if(!$buriedNew){
			if($buriedOld) $this->setBuried($comment_id,false);
		}
		else{
			$this->setBuried($comment_id,$buriedNew);
		}

		if(!$childrenBuriedNew){
			if($childrenBuriedOld) $this->setChildrenBuried($comment_id,false);
		}
		else{
			$this->setChildrenBuried($comment_id,$childrenBuriedNew);
		}
	
	}



	public function setFeatured($comment_id, $featured){
		if( !current_user_can('edit_post', $comment->comment_post_ID) )
			return;

	
		$featured = apply_filters( 'HkFC_comment_featured_save_pre', $featured, $comment_id );
		
		if($featured)
			return update_metadata('comment', $comment_id, 'featured', "1");
		else
			return delete_metadata('comment', $comment_id, 'featured','',false);
	}

	public function setBuried($comment_id, $buried){
		if( !current_user_can('edit_post', $comment->comment_post_ID) )
			return;

	
		$buried = apply_filters( 'HkFC_comment_buried_save_pre', $buried, $comment_id );
		
		if($buried)
			return update_metadata('comment', $comment_id, 'buried', "1");
		else
			return delete_metadata('comment', $comment_id, 'buried','',false);
	}

	public function setChildrenBuried($comment_id, $children_buried){
		if( !current_user_can('edit_post', $comment->comment_post_ID) )
			return;

	
		$children_buried = apply_filters( 'HkFC_comment_children_buried_save_pre', $children_buried, $comment_id );
		
		if($children_buried)
			return update_metadata('comment', $comment_id, 'children_buried', "1");
		else
			return delete_metadata('comment', $comment_id, 'children_buried','',false);
	}






	public function register_meta_box(){
		add_meta_box('HkFC_comment_meta_box', 'Feature Comments', array($this,'admin_edit_box'), 'comment', 'normal');
	}

	public function admin_edit_box(){
		global $comment;
		$featured = hkFC_is_comment_featured($comment);
		$buried = hkFC_is_comment_buried($comment);
		$children_buried = hkFC_is_comment_children_buried($comment);
?>

<p>
	<input type="hidden" name="HkFC_nonce" id="HkFC_nonce" value="<?php echo wp_create_nonce(
			plugin_basename(__FILE__).$comment->comment_ID.'-HkFC_update' ); ?>" />
	<input type="checkbox" name="hikari-featured" id="hikari-featured" <?php checked($featured,true); ?> />
	<label for="hikari-featured">Featured</label>
	<br />
	<input type="checkbox" name="hikari-buried" id="hikari-buried" <?php checked($buried,true); ?> />
	<label for="hikari-buried">Buried</label>
	<br />
	<input type="checkbox" name="hikari-children-buried" id="hikari-children-buried" <?php checked($children_buried,true); ?> />
	<label for="hikari-buried">Threaded Children/Nested Buried</label>
</p>

<?php

	}

//





	public function comment_row_actions($actions){
		global $comment, $post, $approve_nonce;


		if(hkFC_is_comment_featured($comment)){
			$unfeature_url = esc_url( "comment.php?action=unfeatureComment&amp;p=$post->ID&amp;c=$comment->comment_ID&amp;$approve_nonce" );
			$actions['unfeature'] = "<a href='$unfeature_url' class='dim:the-comment-list:comment-$comment->comment_ID:unapproved:e7e7d3:e7e7d3:new=unapproved vim-u' title='Unfeature this comment'>Unfeature</a>";
		}else{
			$feature_url = esc_url( "comment.php?action=featureComment&amp;p=$post->ID&amp;c=$comment->comment_ID&amp;$approve_nonce" );
			$actions['feature'] = "<a href='$feature_url' class='dim:the-comment-list:comment-$comment->comment_ID:unapproved:e7e7d3:e7e7d3:new=approved vim-a' title='Feature this comment'>Feature</a>";
		}

		if(hkFC_is_comment_buried($comment)){
			$unbury_url = esc_url( "comment.php?action=unburyComment&amp;p=$post->ID&amp;c=$comment->comment_ID&amp;$approve_nonce" );
			$actions['unbury'] = "<a href='$unbury_url' class='dim:the-comment-list:comment-$comment->comment_ID:unapproved:e7e7d3:e7e7d3:new=unapproved vim-u' title='Unbury this comment'>Unbury</a>";
		}else{
			$bury_url = esc_url( "comment.php?action=buryComment&amp;p=$post->ID&amp;c=$comment->comment_ID&amp;$approve_nonce" );
			$actions['bury'] = "<a href='$bury_url' class='dim:the-comment-list:comment-$comment->comment_ID:unapproved:e7e7d3:e7e7d3:new=approved vim-a' title='Bury this comment'>Bury</a>";
		}
		
		if(hkFC_is_comment_children_buried($comment)){
			$children_unbury_url = esc_url( "comment.php?action=unburyChildrenComment&amp;p=$post->ID&amp;c=$comment->comment_ID&amp;$approve_nonce" );
			$actions['children_unbury'] = "<a href='$children_unbury_url' class='dim:the-comment-list:comment-$comment->comment_ID:unapproved:e7e7d3:e7e7d3:new=unapproved vim-u' title='Unbury children of this comment'>Unbury children</a>";
		}else{
			$children_bury_url = esc_url( "comment.php?action=buryChildrenComment&amp;p=$post->ID&amp;c=$comment->comment_ID&amp;$approve_nonce" );
			$actions['children_bury'] = "<a href='$children_bury_url' class='dim:the-comment-list:comment-$comment->comment_ID:unapproved:e7e7d3:e7e7d3:new=approved vim-a' title='Bury children of this comment'>Bury children</a>";
		}
						
		return $actions;
	}

	public function requestFromCommentsListPage(){
		if(
				'featureComment' === $_GET['action']		||
				'unfeatureComment' === $_GET['action']		||
				'buryComment' === $_GET['action']			||
				'unburyComment' === $_GET['action']			||
				'buryChildrenComment' === $_GET['action']	||
				'unburyChildrenComment' === $_GET['action']
			){
			
			$comment_id = absint( $_GET['c'] );
			$comment_obj = get_comment($comment_id);
			
			if(empty($comment_obj) || is_wp_error($comment_obj))
				comment_footer_die( __('Oops, no comment with this ID.') . $comment_id .  ". <a href='javascript:history.go(-1)'>".__('Go back').'</a>');
			
			if ( !current_user_can('edit_post', $comment->comment_post_ID) )
				comment_footer_die( __('You are not allowed to edit comments on this post.') );
			
			switch($_GET['action'])
			{
				case 'featureComment':
					$this->setFeatured($comment_id,true);
				break;
				
				case 'unfeatureComment':
				$this->setFeatured($comment_id,false);
				break;
				
				case 'buryComment':
					$this->setBuried($comment_id,true);
				break;
				
				case 'unburyComment':
					$this->setBuried($comment_id,false);
				break;
				
				case 'buryChildrenComment':
					$this->setChildrenBuried($comment_id,true);
				break;
				
				case 'unburyChildrenComment':
					$this->setChildrenBuried($comment_id,false);
				break;
			}
			
			$noredir = isset($_REQUEST['noredir']);
			if ( '' != wp_get_referer() && false == $noredir && false === strpos(wp_get_referer(), 'comment.php') )
				$redir = wp_get_referer();
			elseif ( '' != wp_get_original_referer() && false == $noredir )
				$redir = wp_get_original_referer();
			else
				$redir = admin_url('edit-comments.php');
				
			wp_redirect($redir);
			die;
		}
	}



}



add_action('plugins_loaded', 'HkFC_instance');

function HkFC_instance(){
//	global $hkFC_Op;
//	$hkFC_Op = new HkFC_Op();

	global $hkFC;
	$hkFC = new HkFC();
}

