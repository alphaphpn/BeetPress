<?
// Shortcode to Display Rentals with Filters
function display_rentals_with_filter() {
    // Fetch taxonomy terms for Area, City, and District
    $areas = get_terms([
        'taxonomy' => 'area',
        'orderby'  => 'name',
        'hide_empty' => true,
    ]);

    $cities = get_terms([
        'taxonomy' => 'city',
        'orderby'  => 'name',
        'hide_empty' => true,
    ]);

    $districts = get_terms([
        'taxonomy' => 'district',
        'orderby'  => 'name',
        'hide_empty' => true,
    ]);

    ob_start();
    ?>
    <p><strong>Filter Rentals By:</strong></p>
    <div id="rentals-filter">
        <form id="filter-form">
            <!-- Area Filter -->
            <select id="filter-area" name="area" class="filter-rentals">
                <option value="">All Areas</option>
                <?php foreach ($areas as $area) : ?>
                    <option value="<?php echo esc_attr($area->term_id); ?>"><?php echo esc_html($area->name); ?></option>
                <?php endforeach; ?>
            </select>

            <!-- City Filter -->
            <select id="filter-city" name="city" class="filter-rentals">
                <option value="">All Cities</option>
                <?php foreach ($cities as $city) : ?>
                    <option value="<?php echo esc_attr($city->term_id); ?>"><?php echo esc_html($city->name); ?></option>
                <?php endforeach; ?>
            </select>

            <!-- District Filter -->
            <select id="filter-district" name="district" class="filter-rentals">
                <option value="">All Districts</option>
                <?php foreach ($districts as $district) : ?>
                    <option value="<?php echo esc_attr($district->term_id); ?>"><?php echo esc_html($district->name); ?></option>
                <?php endforeach; ?>
            </select>
        </form>

        <div id="rentals-results">
            <?php
            // Default query
            $query = new WP_Query([
                'post_type'      => 'rental',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'DATE',
                'order'          => 'DESC',
            ]);

            if ($query->have_posts()) {
                echo '<div class="rentals-grid">';
                while ($query->have_posts()) {
                    $query->the_post();
                    $rental_areas = get_the_terms(get_the_ID(), 'rental-area');
                    ?>
                    <div class="rental-item">
                        <div class="rental-thumbnail">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
                            <?php endif; ?>
                        </div>
                        <div class="rental-content">
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <?php if ($rental_areas && !is_wp_error($rental_areas)) : ?>
                                <h5 class="rental-area">
                                    <?php echo implode(', ', wp_list_pluck($rental_areas, 'name')); ?>
                                </h5>
                            <?php endif; ?>
                            <a class="view-details-btn" href="<?php the_permalink(); ?>">View Details</a>
                        </div>
                    </div>
                    <?php
                }
                echo '</div>';
            } else {
                echo '<p>No rentals found.</p>';
            }

            wp_reset_postdata();
            ?>
        </div>
    </div>

<script>
jQuery(document).ready(function($) {
    // Handle filter changes
    $('#filter-area, #filter-city, #filter-district').on('change', function() {
        let area = $('#filter-area').val();
        let city = $('#filter-city').val();
        let district = $('#filter-district').val();

        $('#overlay').show();

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'GET',
            data: {
                action: 'filter_rentals',
                area: area,
                city: city,
                district: district
            },
            success: function(response) {
                $('#overlay').hide();
                $('#rentals-results').html(response);
            },
            error: function() {
                $('#overlay').hide();
                alert('Failed to load filtered results. Please try again.');
            }
        });
    });
});
</script>

    <?php
    return ob_get_clean();
}
add_shortcode('rentals_filter', 'display_rentals_with_filter');


// AJAX Handler for Rentals
function filter_rentals() {
    $area = isset($_GET['area']) ? sanitize_text_field($_GET['area']) : '';
    $city = isset($_GET['city']) ? sanitize_text_field($_GET['city']) : '';
    $district = isset($_GET['district']) ? sanitize_text_field($_GET['district']) : '';

    $tax_query = ['relation' => 'AND'];

    if ($area) {
        $tax_query[] = [
            'taxonomy' => 'area',
            'field'    => 'term_id',
            'terms'    => $area,
        ];
    }

    if ($city) {
        $tax_query[] = [
            'taxonomy' => 'city',
            'field'    => 'term_id',
            'terms'    => $city,
        ];
    }

    if ($district) {
        $tax_query[] = [
            'taxonomy' => 'district',
            'field'    => 'term_id',
            'terms'    => $district,
        ];
    }

    $query = new WP_Query([
        'post_type'      => 'rental',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'DATE',
        'order'          => 'DESC',
        'tax_query'      => $tax_query,
    ]);

    if ($query->have_posts()) {
        echo '<div class="rentals-grid">';
        while ($query->have_posts()) {
            $query->the_post();
            $rental_areas = get_the_terms(get_the_ID(), 'rental-area');
            ?>
            <div class="rental-item">
                <div class="rental-thumbnail">
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
                    <?php endif; ?>
                </div>
                <div class="rental-content">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <?php if ($rental_areas && !is_wp_error($rental_areas)) : ?>
                        <h5 class="rental-area">
                            <?php echo implode(', ', wp_list_pluck($rental_areas, 'name')); ?>
                        </h5>
                    <?php endif; ?>
                    <a class="view-details-btn" href="<?php the_permalink(); ?>">View Details</a>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    } else {
        echo '<p>No rentals found.</p>';
    }

    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_filter_rentals', 'filter_rentals');
add_action('wp_ajax_nopriv_filter_rentals', 'filter_rentals');