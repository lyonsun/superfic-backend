<?php

require_once('model.php');

/**
 * Controller class
 * 
 * All available public endpoints:
 * - get_all_posts
 * - get_posts?page=1&count=1
 * - get_posts_count?user_id=1
 * - get_posts_count?user_id=1&month=1
 * - get_monthly_posts_count?user_id=1
 * - get_average_characters_count?user_id=1
 * - get_longest_post?user_id=1
 */
class Controller {
    public $model;

    public function __construct() {
        $this->model = new Model();
    }

    /**
     * get all posts
     */
    public function get_all_posts() {
        // get the posts from the database
        $posts = $this->model->get_all_posts();

        // return the posts
        echo json_encode($posts);
    }

    /**
     * get posts
     */
    public function get_posts() {
        $query_string = $this->_get_query_string();
        $offset = isset($query_string['page']) ? $query_string['page'] - 1 : 0;
        $limit = isset($query_string['count']) ? $query_string['count'] : 10;

        // get the posts from the database
        $posts = $this->model->get_posts($offset, $limit);

        // return the posts
        echo json_encode($posts);
    }

    /**
     * get user
     */
    public function get_user() {
        $query_string = $this->_get_query_string();

        if (isset($query_string['user_id'])) {
            // get the user from the database
            $user = $this->model->get_user_by_id($query_string['user_id']);

            // return the user
            echo json_encode($user);
        } else {
            echo json_encode(array('error' => 'user_id is required'));
        }
    }

    /**
     * get the number of posts each person made in total or each month
     */
    public function get_posts_count() {
        $query_string = $this->_get_query_string();
        $user_id = isset($query_string['user_id']) ? $query_string['user_id'] : NULL;
        $month = isset($query_string['month']) ? $query_string['month'] : NULL;

        // get posts count by user id
        $posts_count = $this->model->get_posts_count_by_user_id($user_id, $month);

        // return the posts count
        echo json_encode($posts_count);
    }

    /**
     * get the number of posts each person made in every month
     */
    public function get_monthly_posts_count() {
        $query_string = $this->_get_query_string();
        $user_id = isset($query_string['user_id']) ? $query_string['user_id'] : NULL;

        // get average characters count by user id
        $monthly_posts_count = $this->model->get_monthly_posts_count_by_user_id($user_id);

        // return the average characters count
        echo json_encode($monthly_posts_count);
    }

    /**
     * get average number of characters of their posts
     */
    public function get_average_characters_count() {
        $query_string = $this->_get_query_string();
        $user_id = isset($query_string['user_id']) ? $query_string['user_id'] : NULL;

        // get average characters count by user id
        $average_characters_count = $this->model->get_average_characters_count_by_user_id($user_id);

        // return the average characters count
        echo json_encode($average_characters_count);
    }

    /**
     * get each person’s longest post.
     */
    public function get_longest_post() {
        $query_string = $this->_get_query_string();
        $user_id = isset($query_string['user_id']) ? $query_string['user_id'] : NULL;

        // get longest post by user id
        $longest_post = $this->model->get_longest_post_by_user_id($user_id);

        // return the longest post
        echo json_encode($longest_post);
    }

    private function _get_query_string() {
        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $query);
            return $query;
        } else {
            return array();
        }
    }
}
