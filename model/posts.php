<?php

namespace CafeMom;

class DailyPosts 
{
	protected $all;
	protected $best;
	protected $top;
	protected $others;

	public function __construct() 
    {
		$this->all    = array();
		$this->top    = array();
		$this->others = array();
	}

	public function addPost($post) 
    {
        array_push($this->all, $post);
        
        // today's top post based on # of likes
        if (is_null($this->best)) {
            $this->best = $post;
        } else if ($post->getLikes() >= $this->best->getLikes()) {
            $this->best = $post;
        }

        if ($post->isTopPost()) {
            array_push($this->top,    $post);
        } else {
            array_push($this->others, $post);
        }
	}

    public function getBest()
    {
        return $this->best;
    }

	public function getTop()
    {
		return $this->top;
	}

	public function getOthers() 
    {
		return $this->others;
	}
}


class Posts 
{
	protected $daily;

	public function __construct() 
    {
		$this->daily = array();
	}

	public function addPost($post) 
    {
        if (isset($this->daily[$post->getDate()]) ) {
            $holder = $this->daily[$post->getDate()];
        } else {
            //echo "creating holder for date " . $post->getDate() . "<br>";
            $holder = new DailyPosts();
            $this->daily[$post->getDate()] = $holder;
        }
       
        $holder->addPost($post);
	}

    public function getPostsForDay($date)
    {
        return $this->daily[$date];
    }

    public function getAllDays()
    {
        return $this->daily;
    }
}

?>