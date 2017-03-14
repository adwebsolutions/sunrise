<?php
global $et_loop;

//print_r($et_loop);

if( empty( $et_loop['columns'] ) ) {
    $et_loop['columns'] = etheme_get_option('blog_columns');
}

if( empty( $et_loop['slider'] ) ) {
    $et_loop['slider'] = false;
}

if( empty( $et_loop['loop'] ) ) {
    $et_loop['loop'] = 0;
}

$layout = etheme_get_option('blog_layout');

if ( is_single() && $layout == 'timeline2' ) {
    $et_loop['slide_view'] = $layout;
}

if( ! empty( $et_loop['blog_layout'] ) ) {
    $layout = $et_loop['blog_layout'];
}

$size = etheme_get_option( 'blog_images_size' );

if( ! empty( $et_loop['size'] ) ) {
    $size = $et_loop['size'];
}

$et_loop['loop']++;

$cols 			= etheme_get_cols($et_loop['columns']);
$postClass 		= etheme_post_class( $cols, $layout );
$read_more 		= etheme_get_read_more();

?>

<article <?php post_class($postClass); ?> id="post-<?php the_ID(); ?>" >
    <div>
        <?php if ( $et_loop['slide_view'] == 'timeline2' ): ?>
            <div class="meta-post-timeline">
                <span class="time-day"><?php the_time('d'); ?></span>
                <span class="time-mon"><?php the_time('M'); ?></span>
            </div>
        <?php endif; ?>
        <?php etheme_post_thumb( array('size' => $size, 'in_slider' => $et_loop['slider'] ) ); ?>

        <div class="grid-post-body">
            <div class="post-heading">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php if(etheme_get_option('blog_byline')): ?>
                    <?php etheme_byline( array( 'author' => 0 ) );  ?>
                <?php endif; ?>
            </div>

            <div class="content-article">
                <?php the_excerpt();  ?>
                <?php if (etheme_get_option('read_more')): ?>
                    <a href="<?php the_permalink(); ?>"><?php echo $read_more; ?></a>
                <?php endif ?>
            </div>
        </div>
    </div>
</article>