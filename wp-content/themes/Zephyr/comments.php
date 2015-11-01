<?php

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');

if ( post_password_required() ) {
	return;
}

$comments_number = get_comments_number();
?>
<div id="comments" class="w-comments">
<?php if ( $comments_number > 0 ) { ?>
	<h4 class="w-comments-title">
		<?php
		$comments_label = sprintf( _n( '%s comment', '%s comments', $comments_number, 'us' ), $comments_number );
		$comments_label .= '. <a href="#respond">' . __( 'Leave new', 'us' ) . '</a>';
		comments_number( __( 'No comments', 'us' ), $comments_label, $comments_label );
		?>
	</h4>

	<div class="w-comments-list">
		<?php wp_list_comments(array( 'callback' => 'us_comment_start', 'end-callback' => 'us_comment_end', 'walker' => new Walker_Comments_US() )); ?>
	</div>

	<div class="w-comments-pagination">
		<?php previous_comments_link() ?>
		<?php next_comments_link() ?>
	</div>
<?php } ?>
<?php if ( comments_open() ) : ?>

	<div id="respond" class="w-comments-form">
		<?php /* <h4 class="w-comments-form-title"><?php comment_form_title(__('Leave comment', 'us'), __('Leave comment', 'us')); ?></h4> */ ?>
		<?php if ( get_option('comment_registration') && !is_user_logged_in() ) { ?>
			<div class="w-comments-form-text"><?php printf(__('You must be %slogged in%s to post a comment.', 'us'), '<a href="'.wp_login_url( get_permalink() ).'">', '</a>'); ?></div>
		<?php } else {
			$commenter = wp_get_current_commenter();
			$req = get_option( 'require_name_email' );
			$aria_req = ( $req ? " aria-required='true'" : '' );

			$comment_form_fields = array(
				'author' =>
					'<p class="comment-form-author w-form-row"><span class="w-form-field">' .
					'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
					'" size="30"' . $aria_req . ' />
					<i class="mdfi_social_person"></i>
					<label class="w-form-field-label" for="author">' . __( 'Name', 'us' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label>
					<span class="w-form-field-bar"></span></span></p>',

				'email' =>
					'<p class="comment-form-email w-form-row"><span class="w-form-field">' .
					'<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
					'" size="30"' . $aria_req . ' />
					<i class="mdfi_communication_email"></i>
					<label class="w-form-field-label" for="email">' . __( 'Email', 'us' ) .( $req ? ' <span class="required">*</span>' : '' ) . '</label>
					<span class="w-form-field-bar"></span></span></p>',

				'url' =>
					'<p class="comment-form-url w-form-row"><span class="w-form-field">' .
					'<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
					'" size="30" />
					<i class="mdfi_content_link"></i>
					<label class="w-form-field-label" for="url">' . __( 'Website', 'us' ) . '</label>
					<span class="w-form-field-bar"></span></span></p>',
			);
			$comment_form_args = array(
				'comment_field' =>  '<p class="comment-form-comment w-form-row"><span class="w-form-field">
					<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true">' .
					'</textarea>
					<i class="mdfi_content_create"></i>
					<label class="w-form-field-label" for="comment">' . _x( 'Comment', 'noun', 'us' ) .
					'</label>
					<span class="w-form-field-bar"></span></span></p>',
				'fields' => apply_filters( 'comment_form_default_fields', $comment_form_fields ),
			);
			comment_form($comment_form_args);
		} ?>

	</div>
<?php endif;?>
</div>
