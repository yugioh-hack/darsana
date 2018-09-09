<?php get_header(); ?>

    <section class="container section index__mainSection">

      <div class="columns is-centered">

            <main id="main" class="column is-8" role="main" itemscope itemprop="mainContentOfPage" itemtype="https://schema.org/Blog">

              <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

              <article id="post-<?php the_ID(); ?>" <?php post_class( 'index__article box' ); ?> role="article">

                <?php //<header class="index__header"> ?>

                  <h1 class="index__title">
                    <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                      <?php the_title(); ?>
                    </a>
                  </h1>
                  <p class="byline entry-meta vcard">
                    <?php /* the time the post was published */
                     printf ('<time class="updated entry-time" datetime="' . get_the_time('Y-m-d') . '" itemprop="datePublished">' . get_the_time(get_option('date_format')) . '</time>'); ?>
                  </p>

                <?php //</header> ?>

                <section class="content index__loopSection">
                  <?php //the_content(); ?>
                  <?php the_excerpt(); ?>
                </section>

                <footer class="index__footer">
                  <p class="footer-comment-count">
                    <?php comments_number( __( '<span>No</span> Comments', 'bonestheme' ), __( '<span>One</span> Comment', 'bonestheme' ), __( '<span>%</span> Comments', 'bonestheme' ) );?>
                  </p>


                   <?php printf( '<p class="footer-category">' . __('filed under', 'bonestheme' ) . ': %1$s</p>' , get_the_category_list(', ') ); ?>

                  <?php the_tags( '<p class="footer-tags tags"><span class="tags-title">' . __( 'Tags:', 'bonestheme' ) . '</span> ', ', ', '</p>' ); ?>


                </footer>

              </article>

              <?php endwhile; ?>

                  <?php //bones_page_navi(); ?>
                  <?php custom_page_navi(); ?>

              <?php else : ?>

                  <article id="post-not-found" class="hentry">
                    <header class="article-header">
                      <h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
                    </header>
                    <section class="content">
                      <p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
                    </section>
                    <footer class="article-footer">
                        <p><?php _e( 'This is the error message in the index.php template.', 'bonestheme' ); ?></p>
                    </footer>
                  </article>

              <?php endif; ?>


            </main>

          <?php get_sidebar(); ?>

    </div>

  </section>

<?php get_footer(); ?>
