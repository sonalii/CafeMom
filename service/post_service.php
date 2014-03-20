<?php

namespace CafeMom;
	
require '../lib/csv/reader.php';
require '../lib/csv/writer.php';
require '../model/post.php';
require '../model/posts.php';

class PostService 
{
    protected $posts;

    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Loads data from given posts file
     */
    public function loadData($data_file)
    {
        $this->posts = new \CafeMom\Posts();
        $reader      = new \CafeMom\Reader($data_file);

        while ($row = $reader->getRow()) {
            $post = new \CafeMom\Post($row);
            $this->posts->addPost($post);
        }
    }

    /**
     * Helper function to find top posts from all days
     */
    public function findTopPosts()
    {
        $allTopPosts = array();

        foreach ($this->posts->getAllDays() as $aDayPosts) {
            $dayTopPosts = $aDayPosts->getTop();

            foreach ($dayTopPosts as $topPostOfDay) {
                array_push($allTopPosts, $topPostOfDay);
            }
        }

        usort($allTopPosts, array($this, 'comparePostIds'));

        return $allTopPosts;
    }

    /**
     * Helper function to find posts which are not top from all days
     */
    public function findOtherPosts()
    {
        $allOtherPosts = array();

        foreach ($this->posts->getAllDays() as $aDayPosts) {
            $dayOtherPosts = $aDayPosts->getOthers();

            foreach ($dayOtherPosts as $otherPostOfDay) {
                array_push($allOtherPosts, $otherPostOfDay);
            }
        }

        usort($allOtherPosts, array($this, 'comparePostIds'));

        return $allOtherPosts;
    }

    /**
     * Helper function to find highest scoring top post for day
     */
    public function findBestPosts()
    {
        $allBestPosts = array();

        foreach ($this->posts->getAllDays() as $dayPosts) {
            array_push($allBestPosts, $dayPosts->getBest());
        }

        usort($allBestPosts, array($this, 'comparePostIds'));

        return $allBestPosts;
    }

    public function comparePostIds($post1, $post2)
    {
        return $post1->getId() > $post2->getId();
    }

    public function write($file_name, $posts, $columns)
    {
        $writer = null;
        if (strpos($file_name, 'csv') !== false) {
            $writer = new \CafeMom\CSVWriter($file_name);
            $writer->writeRow($columns);
        } else {
            $writer = new \CafeMom\JSonWriter($file_name);
        }

        foreach ($posts as $aPost) {
            $writer->writeRow($aPost->getValues($columns));
        }

        $writer->finish();
    }
}