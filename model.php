<?php

require_once('db.php');

/**
 * Model class
 */
class Model {
    public $db_conn;
    public $db;

    public function __construct() {
        $this->db_conn = new DB();
        $this->db = $this->db_conn->db;
    }

    /**
     * get all posts
     */
    public function get_all_posts() {
        // get the posts from the database
        $posts = $this->db->query("SELECT * FROM posts")->fetch_all(MYSQLI_ASSOC);

        // return the posts
        return $posts;
    }

    /**
     * get posts
     */
    public function get_posts($offset = 0, $limit = 10) {
        // get the posts from the database joined with the users
        $posts = $this->db->query("SELECT p.*, u.name AS user_name FROM posts AS p JOIN users as u ON p.user_id = u.id ORDER BY p.created_time DESC LIMIT {$offset}, {$limit} ")->fetch_all(MYSQLI_ASSOC);

        // return the posts
        return $posts;
    }

    /**
     * get user
     */
    public function get_user_by_id($user_id) {
        // get the user from the database
        $query = $this->db->query("SELECT * FROM users WHERE user_id = '{$user_id}'");

        $user = $query->fetch_assoc();

        // return the user
        return $user;
    }

    /**
     * get posts count by user id or user id and month
     */
    public function get_posts_count_by_user_id($user_id = NULL, $month = NULL) {
        $posts_count = 0;

        // get posts count by user id or user id and month
        if ($user_id !== NULL) {
            if ($month !== NULL) {
                $query = $this->db->query("SELECT SUM(count) as count, month, user_id FROM posts_count WHERE user_id = '{$user_id}' AND month = '{$month}'");
            } else {
                $query = $this->db->query("SELECT SUM(count) as count, user_id FROM posts_count WHERE user_id = '{$user_id}'");
            }
        } else {
            $query = $this->db->query("SELECT SUM(count) as count FROM posts_count");
        }

        $posts_count = $query->fetch_assoc();

        return $posts_count;
    }

    /**
     * get monthly posts count by user id
     */
    public function get_monthly_posts_count_by_user_id($user_id) {
        // get the posts count by user id
        $posts_count = $this->db->query("SELECT SUM(count) as count, month, user_id FROM posts_count WHERE user_id = '{$user_id}' GROUP BY month")->fetch_all(MYSQLI_ASSOC);

        // return the posts count
        return $posts_count;
    }

    /**
     * get average number of characters of their posts
     */
    public function get_average_characters_count_by_user_id($user_id = NULL) {
        $average_characters_count = 0;

        // get average characters count by user id
        if ($user_id !== NULL) {
            $query = $this->db->query("SELECT count, user_id FROM average_characters_count WHERE user_id = '{$user_id}'");
            $average_characters_count = $query->fetch_assoc();
        }

        return $average_characters_count;
    }

    /**
     * get longest post by user id
     */
    public function get_longest_post_by_user_id($user_id = NULL) {
        $longest_post = NULL;

        // get longest post by user id
        if ($user_id !== NULL) {
            $query = $this->db->query("SELECT post_id FROM longest_post WHERE user_id = '{$user_id}'");
            $result = $query->fetch_assoc();

            if (!empty($result)) {
                $longest_post_id = $result['post_id'];
                $post_result = $this->db->query("SELECT p.*, u.name AS user_name FROM posts AS p JOIN users AS u ON p.user_id = u.id WHERE p.id = '{$longest_post_id}'");
                $longest_post = $post_result->fetch_assoc();
            }
        }

        return $longest_post;
    }
}
