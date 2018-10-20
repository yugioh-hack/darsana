<?php get_header(); ?>
      <section class="container section archive-section">

        <div id="content" class="columns is-centered">

            <main id="main" class="column is-8" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">
              <header class="taxonomy-header">
                <?php
                the_archive_title( '<h1 class="taxonomy-title">', '</h1>' );
                the_archive_description( '<div class="taxonomy-description">', '</div>' );
                ?>
              </header>

              <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

              <section class="taxonomy-section">
              <article id="post-<?php the_ID(); ?>" <?php post_class( 'taxonomy-article' ); ?> role="article">

                  <h3 class="taxonomy-article__title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
                  <p class="byline entry-meta vcard">
                    <?php printf(
                                     /* the time the post was published */
                                     '<time class="taxonomy-article__time updated entry-time" datetime="' . get_the_time('Y-m-d') . '" itemprop="datePublished">' . get_the_time(get_option('date_format')) . '</time>'

                                       /* the author of the post */
                                       //'<span class="by">'.__('by', 'bonestheme').'</span> <span class="entry-author author" itemprop="author" itemscope itemptype="http://schema.org/Person">' . get_the_author_link( get_the_author_meta( 'ID' ) ) . '</span>'
                                   );
      echo get_the_term_list( $post->ID, 'how_to_tag', '<span class="taxonomy-article__tagItem">', '</span><span class="taxonomy-article__tagItem">','</span>' );
?>
                  </p>
              </article>

              <?php endwhile; ?>

                  <?php //bones_page_navi(); ?>
                  <?php custom_page_navi(); ?>

              <?php else : ?>

                  <article id="post-not-found" class="taxonomy-article">
                    <header class="article-header">
                      <h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
                    </header>
                    <section class="entry-content">
                      <p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
                    </section>
                    <footer class="article-footer">
                        <p><?php _e( 'This is the error message in the archive.php template.', 'bonestheme' ); ?></p>
                    </footer>
                  </article>

              <?php endif; ?>
              </section>
            </main>

          <?php get_sidebar(); ?>

        </div>

      </section>

<?php get_footer(); ?>
