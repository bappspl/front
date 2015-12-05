<?php
namespace Disc\Model;

use CmsIr\System\Model\Model;
use CmsIr\System\Util\Inflector;

class Disc extends Model
{
    protected $id;
    protected $name;
    protected $slug;
    protected $title;
    protected $content_first;
    protected $content_second;
    protected $filename_main;
    protected $year;
    protected $album;
    protected $facebook;
    protected $position;
    protected $category_id;
    protected $status_id;

    public function exchangeArray($data) 
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->slug = (!empty($data['slug'])) ? $data['slug'] : Inflector::slugify($data['name']);;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->content_first = (!empty($data['content_first'])) ? $data['content_first'] : null;
        $this->content_second = (!empty($data['content_second'])) ? $data['content_second'] : null;
        $this->filename_main = (!empty($data['filename_main'])) ? $data['filename_main'] : null;
        $this->year = (!empty($data['year'])) ? $data['year'] : null;
        $this->album = (!empty($data['album'])) ? $data['album'] : null;
        $this->facebook = (!empty($data['facebook'])) ? $data['facebook'] : null;
        $this->position = isset($data['position']) ? $data['position'] : null;
        $this->category_id = (!empty($data['category_id'])) ? $data['category_id'] : null;
        $this->status_id = (!empty($data['status_id'])) ? $data['status_id'] : 2;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getContentFirst()
    {
        return $this->content_first;
    }

    /**
     * @param mixed $content_first
     */
    public function setContentFirst($content_first)
    {
        $this->content_first = $content_first;
    }

    /**
     * @return mixed
     */
    public function getContentSecond()
    {
        return $this->content_second;
    }

    /**
     * @param mixed $content_second
     */
    public function setContentSecond($content_second)
    {
        $this->content_second = $content_second;
    }

    /**
     * @return mixed
     */
    public function getFilenameMain()
    {
        return $this->filename_main;
    }

    /**
     * @param mixed $filename_main
     */
    public function setFilenameMain($filename_main)
    {
        $this->filename_main = $filename_main;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return mixed
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @param mixed $album
     */
    public function setAlbum($album)
    {
        $this->album = $album;
    }

    /**
     * @return mixed
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param mixed $facebook
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * @return mixed
     */
    public function getStatusId()
    {
        return $this->status_id;
    }

    /**
     * @param mixed $status_id
     */
    public function setStatusId($status_id)
    {
        $this->status_id = $status_id;
    }
}