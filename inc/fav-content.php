<?php  
/*
 * @Theme Name:WebStack
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2020-02-22 21:26:05
 * @LastEditors: iowen
 * @LastEditTime: 2024-07-30 21:51:29
 * @FilePath: /WebStack/inc/fav-content.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function fav_con($mid, $visible) { ?>
        <h2 class="text-gray" style="display: inline-block;"><i class="icon-io-tag" style="margin-right: 27px;" id="term-<?php echo $mid->term_id; ?>"></i><?php echo $mid->name; ?></h2>
        <?php 
        if($visible == 2){
            echo '<div class="login-notice">'.__('此分类需登陆后查看','i_theme').'</div>';
            return;
        }
        $site_n           = io_get_option('site_n');
        $category_count   = $mid->category_count;
        $count            = $site_n;
        if($site_n == 0)  $count = min(get_option('posts_per_page'),$category_count);
        if($site_n >= 0 && $count < $category_count){
          $link = esc_url( get_term_link( $mid, 'res_category' ) );
          echo "<a class='btn-move' href='$link'>more+</a>";
        }
        ?>
        <div class="row">
        <?php   
          //定义$post为全局变量，这样之后的输出就不会是同一篇文章了
          global $post;
          //下方的posts_per_page设置最为重要
          $args = array(
            'post_type'           => 'sites',        //自定义文章类型，这里为sites
            'ignore_sticky_posts' => 1,              //忽略置顶文章
            'posts_per_page'      => $site_n,        //显示的文章数量
            'meta_key'            => '_sites_order',
            'orderby'             => array( 'meta_value_num' => 'DESC', 'ID' => 'DESC' ),
            'tax_query'           => array(
                array(
                    'taxonomy' => 'favorites',       //分类法名称
                    'field'    => 'id',              //根据分类法条款的什么字段查询，这里设置为ID
                    'terms'    => $mid->term_id,     //分类法条款，输入分类的ID，多个ID使用数组：array(1,2)
                )
            ),
          );
          $myposts = new WP_Query( $args );
          if(!$myposts->have_posts()): ?>
          <div class="col-lg-12">
            <div class="nothing"><?php _e('没有内容','i_theme') ?></div>
          </div>
          <?php
          elseif ($myposts->have_posts()): while ($myposts->have_posts()): $myposts->the_post(); 
            $link_url = get_post_meta($post->ID, '_sites_link', true); 
            $default_ico = get_theme_file_uri('/images/favicon.png');
            if(io_is_visible( get_post_meta($post->ID, '_visible', true))):
          ?>
            <div class="xe-card <?php echo io_get_option('columns') ?> <?php echo get_post_meta($post->ID, '_wechat_qr', true)? 'wechat':''?>">
              <?php include( get_theme_file_path() .'/templates/site-card.php' ); ?>
            </div>
          <?php endif; endwhile; endif; wp_reset_postdata(); ?>
        </div>   
        <br /> 
<?php } ?>