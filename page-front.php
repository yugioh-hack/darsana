<?php
/*
 Template Name: FrontPage
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/
?>

<?php get_header(); ?>
  <div class="container front-container">
    <div class="section front-sections">
      <div class="columns is-centered" id="content">

        <div class="column" id="content-inner">
          <article class="front-infomation">
            <?php narsada_get_custom_posts(); ?>
            <?php //shard_get_custom_posts(); ?>
          </article>
          <main class="mainContent" role="main" itemscope itemprop="mainContentOfPage" itemtype="https://schema.org/Blog">

            <article id="post-<?php the_ID(); ?>" <?php post_class('columns is-multiline front-article'); ?> role="article" itemscope itemtype="https://schema.org/BlogPosting">
              <div class="front-sections__header">
                <figure class="front-sections__figure">
                  <?php $svg_all_path = get_template_directory_uri().'/library/images/svg/'.'all_black.svg'; ?>
                  <?php echo '<img class="front-sections__img" src="'. $svg_all_path . '">' ?>
                </figure>
                <h1 class="front-sections__title">Ingressの遊び方</h1>
              </div>
              <?php shard_get_archive_custom_posts(); ?>
            </article>

            <article class="front-infomation">
              <?php narsada_get_custom_posts('anime','anime_cat','アニメ'); ?>
              <?php //shard_get_custom_posts('anime','anime_cat','アニメ'); ?>
            </article>

          </main>

          <aside class="front-aside--postList">
            <h3 class="front-aside__title">更新履歴</h3>
            <?php shard_frontPage_posts_list(); ?>
          </aside>
        </div> <!-- #content-inner -->



      </div> <?php #content ?>
    </div> <?php #section front-sections ?>
  </div> <?php #container front-container ?>


<?php get_footer(); ?>
