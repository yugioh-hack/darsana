
              <?php
                /*
                 * This is the default post format.
                 *
                 * So basically this is a regular post. if you don't want to use post formats,
                 * you can just copy ths stuff in here and replace the post format thing in
                 * single.php.
                 *
                 * The other formats are SUPER basic so you can style them as you like.
                 *
                 * Again, If you want to remove post formats, just delete the post-formats
                 * folder and replace the function below with the contents of the "format.php" file.
                */
              ?>

              <article id="post-<?php the_ID(); ?>" <?php post_class('singlePost--article'); ?> role="article" itemscope itemprop="blogPost" itemtype="https://schema.org/BlogPosting">

                <header class="singlePost--header">
                  <p class="singlePost--byline">
                    <?php printf(
                       /* the time the post was published */
                       '<time class="singlePost--byline__updated" datetime="' . get_the_time('Y-m-d') . '" itemprop="datePublished">' . get_the_time(get_option('date_format')) . '</time>' //,
                       /* the author of the post */
                       //'<span class="singlePost--byline__author" itemprop="author" itemscope itemptype="https://schema.org/Person">' . get_the_author_link( get_the_author_meta( 'ID' ) ) . '</span>'
                    ) ; ?>

                  </p>
                  <h1 class="singlePost--title" itemprop="headline" rel="bookmark"><?php the_title(); ?></h1>


                </header> <?php // end article header ?>

                <section class="content singlePost--content" itemprop="articleBody">
                  <?php
                    // the content (pretty self explanatory huh)
                    the_content();

                    /*
                     * Link Pages is used in case you have posts that are set to break into
                     * multiple pages. You can remove this if you don't plan on doing that.
                     *
                     * Also, breaking content up into multiple pages is a horrible experience,
                     * so don't do it. While there are SOME edge cases where this is useful, it's
                     * mostly used for people to get more ad views. It's up to you but if you want
                     * to do it, you're wrong and I hate you. (Ok, I still love you but just not as much)
                     *
                     * http://gizmodo.com/5841121/google-wants-to-help-you-avoid-stupid-annoying-multiple-page-articles
                     *
                    */
                    wp_link_pages( array(
                      'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'bonestheme' ) . '</span>',
                      'after'       => '</div>',
                      'link_before' => '<span>',
                      'link_after'  => '</span>',
                    ) );
                  ?>
                </section> <?php // end article section ?>

                <footer class="singlePost--footer">
                  <?php
                    $singlePostTagList  = '<div class="singlePost--footer__info">';
                    $singlePostTagList .= '<p class="singlePost--footer__tagName">%1$s</p>';
                    $singlePostTagList .= '<p class="singlePost--footer__tagLists">%2$s</p>';
                    $singlePostTagList .= '</div>';

                    $catListName = "Categories";
                    $catLists = get_the_category_list(' ,');

                    $tagListName = "Tags";
                    $tagLists = get_the_tag_list('<span class="singlePost--footer__tagItem">','</span><span class="singlePost--footer__tagItem">','</span>');

                    printf( $singlePostTagList,$catListName,$catLists);
                    printf( $singlePostTagList,$tagListName,$tagLists);
                     ?>

                  <?php //the_tags( '<p class="tags"><span class="tags-title">' . __( 'Tags:', 'bonestheme' ) . '</span> ', ', ', '</p>' ); ?>
                </footer> <?php // end article footer ?>

                <?php //comments_template(); ?>

              </article> <?php // end article ?>
