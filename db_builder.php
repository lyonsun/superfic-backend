<?php

require_once('api_worker.php');
require_once('db.php');

/**
 * DB Builder class
 */
class DBBuilder {
    public $api_worker;
    public $db_conn;
    public $db;

    public function __construct() {
        $this->api_worker = new APIWorker();
        $this->db_conn = new DB();
        $this->db = $this->db_conn->db;
    }

    /**
     * build the database
     */
    public function build_db($db_table = '', $page = 0) {
        $all_posts = $this->_get_all_posts();

        switch ($db_table) {
            case 'users':
                $this->_build_users_table($all_posts);
                break;
            case 'posts':
                if ($page > 0) {
                    $posts = $this->api_worker->get_posts($page);
                    $this->_build_posts_table($posts);
                } else {
                    $this->_build_posts_table($all_posts);
                }
                break;
            case 'posts_count':
                $stats = $this->_get_user_statistics($all_posts);

                $this->_build_posts_count_table($stats['user_posts_count_per_month']);
                break;
            case 'average_characters_count':
                $stats = $this->_get_user_statistics($all_posts);

                $this->_build_average_characters_count_table($stats['user_total_characters_count'], $stats['user_posts_count']);
                break;
            case 'longest_post':
                $stats = $this->_get_user_statistics($all_posts);

                $this->_build_longest_post_table($stats['user_longest_post']);
                break;
            default:
                $stats = $this->_get_user_statistics($all_posts);

                $this->_build_users_table($all_posts);
                $this->_build_posts_table($all_posts);

                $this->_build_posts_count_table($stats['user_posts_count_per_month']);
                $this->_build_average_characters_count_table($stats['user_total_characters_count'], $stats['user_posts_count']);
                $this->_build_longest_post_table($stats['user_longest_post']);
                break;
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Data has been inserted into database'
        ]);
    }

    /**
     * get all posts
     */
    private function _get_all_posts($start = 1, $number_of_pages = 10) {
        $all_posts = [];

        for ($i = $start; $i <= $number_of_pages; $i++) {
            $posts = $this->api_worker->get_posts($i);
            $all_posts = array_merge($all_posts, $posts);
        }

        return $all_posts;
    }

    /**
     * build users table
     */
    private function _build_users_table($all_posts) {
        foreach ($all_posts as $post) {
            // insert all users into users table
            $user = $this->db->query("SELECT * FROM users WHERE user_id = '{$post['from_id']}'");
            if ($user->num_rows == 0) {
                $sql = "INSERT INTO users (user_id, name)
                    VALUES ('{$post['from_id']}', '{$post['from_name']}')";
                $this->db->query($sql);
            }
        }
    }

    /**
     * build posts table
     */
    private function _build_posts_table($all_posts) {
        foreach ($all_posts as $post) {
            // insert all posts into posts table
            $existing_post = $this->db->query("SELECT * FROM posts WHERE post_id = '{$post['id']}'");
            if ($existing_post->num_rows == 0) {
                // get user id
                $user = $this->db->query("SELECT * FROM users WHERE user_id = '{$post['from_id']}'")->fetch_assoc();
                $user_id = $user['id'];

                $sql = "INSERT INTO posts (post_id, message, type, created_time, user_id) 
                    VALUES ('{$post['id']}', '{$post['message']}', '{$post['type']}', '{$post['created_time']}', '{$user_id}')";
                $this->db->query($sql);
            }
        }
    }

    /**
     * build posts count table
     */
    private function _build_posts_count_table($user_posts_count_per_month) {
        // insert the number of posts each person made every month into posts_count table
        foreach ($user_posts_count_per_month as $user_id => $monthly_post_count) {
            foreach ($monthly_post_count as $month => $count) {
                $user_monthly_count = $this->db->query("SELECT * FROM posts_count WHERE user_id = '{$user_id}' AND month = '{$month}'");
                if ($user_monthly_count->num_rows == 0) {
                    $sql = "INSERT INTO posts_count (user_id, month, count)
                            VALUES ('{$user_id}', '{$month}', '{$count}')";
                    $this->db->query($sql);
                } else {
                    $sql = "UPDATE posts_count SET count = '{$count}' WHERE user_id = '{$user_id}' AND month = '{$month}'";
                    $this->db->query($sql);
                }
            }
        }
    }

    /**
     * build average characters count table
     */
    private function _build_average_characters_count_table($user_total_characters_count, $user_posts_count) {
        // insert the average number of characters of their posts into average_characters_count table
        foreach ($user_total_characters_count as $user_id => $total_characters_count) {
            $average_characters_count = floor($total_characters_count / $user_posts_count[$user_id]);

            $user_average_characters_count = $this->db->query("SELECT * FROM average_characters_count WHERE user_id = '{$user_id}'");
            if ($user_average_characters_count->num_rows == 0) {
                $sql = "INSERT INTO average_characters_count (user_id, count)
                        VALUES ('{$user_id}', '{$average_characters_count}')";
                $this->db->query($sql);
            } else {
                $sql = "UPDATE average_characters_count SET count = '{$average_characters_count}' WHERE user_id = '{$user_id}'";
                $this->db->query($sql);
            }
        }
    }

    /**
     * build longest post table
     */
    private function _build_longest_post_table($user_longest_post) {
        // insert the longest post each person has into longest_post table
        foreach ($user_longest_post as $user_id => $longest_post) {
            // get post by post id
            $post = $this->db->query("SELECT * FROM posts WHERE post_id = '{$longest_post['post_id']}'")->fetch_assoc();

            $user_longest_post = $this->db->query("SELECT * FROM longest_post WHERE user_id = '{$user_id}'");
            if ($user_longest_post->num_rows == 0) {
                $sql = "INSERT INTO longest_post (user_id, post_id, length)
                        VALUES ('{$user_id}', '{$post['id']}', '{$longest_post['length']}')";
                $this->db->query($sql);
            } else {
                $sql = "UPDATE longest_post SET id = '{$post['id']}', length = '{$longest_post['length']}' WHERE user_id = '{$user_id}'";
                $this->db->query($sql);
            }
        }
    }

    /**
     * manipulate data, calculate and build up all the statistics
     */
    private function _get_user_statistics($all_posts) {
        $user_posts_count_per_month = array();
        $user_posts_count = array();
        $user_total_characters_count = array();
        $user_longest_post = array();

        foreach ($all_posts as $post) {
            // get user id
            $user = $this->db->query("SELECT * FROM users WHERE user_id = '{$post['from_id']}'")->fetch_assoc();
            $user_id = $user['id'];

            $month = intval(date('m', strtotime($post['created_time'])));

            // Preset the array
            if (!array_key_exists($user_id, $user_posts_count_per_month)) {
                $user_posts_count_per_month[$user_id] = array();
            }

            if (!array_key_exists($month, $user_posts_count_per_month[$user_id])) {
                $user_posts_count_per_month[$user_id][$month] = 0;
            }

            if (!array_key_exists($user_id, $user_posts_count)) {
                $user_posts_count[$user_id] = 0;
            }

            if (!array_key_exists($user_id, $user_total_characters_count)) {
                $user_total_characters_count[$user_id] = 0;
            }

            if (!array_key_exists($user_id, $user_longest_post)) {
                $user_longest_post[$user_id] = array(
                    'length' => 0,
                    'post_id' => 0
                );
            }

            // calculate the number of posts each person made every month
            $user_posts_count_per_month[$user_id][$month]++;

            // calculate the number of posts each person made in total
            $user_posts_count[$user_id]++;

            // calculate the average number of characters of their posts, and insert into average_characters_count table
            $user_total_characters_count[$user_id] += strlen($post['message']);

            // get the longest post each person has, and insert into longest_post table
            if (strlen($post['message']) > $user_longest_post[$user_id]['length']) {
                $user_longest_post[$user_id]['length'] = strlen($post['message']);
                $user_longest_post[$user_id]['post_id'] = $post['id'];
            }
        }

        return array(
            'user_posts_count_per_month' => $user_posts_count_per_month,
            'user_posts_count' => $user_posts_count,
            'user_total_characters_count' => $user_total_characters_count,
            'user_longest_post' => $user_longest_post
        );
    }
}
