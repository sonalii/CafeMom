<?php

namespace CafeMom;

class Post {
	protected $data;
    protected $date;

	public function __construct($data)
    {
		$this->data = $data;
        
        $ts = strtotime($this->getTimestamp());
        $this->date = date("m.d.y", $ts);
	}

    public function get($col)
    {
        return $this->data[$col];
    }

    public function getValues($columns)
    {
        $row = array();

        foreach ($columns as $col) {
            $row[$col] = $this->get($col);
        }

        return $row;
    }

	public function getId()
    {
		return $this->data['id'];
	}

	public function getTitle()
    {
		return $this->data['title'];
	}

	public function getPrivacy()
    {
		return $this->data['privacy'];
	}

	public function getLikes()
    {
		return intval($this->data['likes']);
	}

	public function getViews()
    {
		return intval($this->data['views']);
	}

	public function getComments()
    {
		return intval($this->data['comments']);
	}

	public function getTimestamp()
    {
		return $this->data['timestamp'];
	}
    
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Top Posts Rules:
     * The post must be public
     * The post must have over 10 comments and over 9000 views
     * The post title must be under 40 characters
     */
    public function isTopPost()
    {
        // The post must be public
        if (strcmp($this->getPrivacy(), "public"))
        {
            // The post must have over 10 comments and over 9000 views
            if ($this->getComments() >= 10 && $this->getViews() >= 9000)
            {
                // The post title must be under 40 characters
                if (strlen($this->getTitle()) < 40)
                {
                    return true;
                }
            }
        }

        return false;
    }
}