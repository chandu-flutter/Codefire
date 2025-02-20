 // Fetch and display posts from a public API
function fetch_display_api_posts() {
    $api_url = 'https://jsonplaceholder.typicode.com/posts';

    // Fetch data from API
    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        return '<p>Error fetching data.</p>';
    }

    $body = wp_remote_retrieve_body($response);
    $posts = json_decode($body);

    if (empty($posts)) {
        return '<p>No posts found.</p>';
    }

    $output = '<div class="api-posts">';
    
    // Loop through the first 5 posts (you can adjust this limit)
    foreach (array_slice($posts, 0, 5) as $post) {
        $output .= '<div class="post">';
        $output .= '<h2>' . esc_html($post->title) . '</h2>';
        $output .= '<p>' . esc_html($post->body) . '</p>';
        $output .= '</div>';
    }

    $output .= '</div>';

    return $output;
}

// Register a shortcode to use in pages/posts
add_shortcode('fetch_api_posts', 'fetch_display_api_posts');
