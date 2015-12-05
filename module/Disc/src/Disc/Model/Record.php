<?php
namespace Disc\Model;

use CmsIr\System\Model\Model;
use CmsIr\System\Util\Inflector;

class Record extends Model
{
    protected $id;
    protected $name;
    protected $slug;
    protected $title;
    protected $content;
    protected $listen;
    protected $buy;
    protected $status_id;
    protected $disc_id;

    public function exchangeArray($data) 
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->slug = (!empty($data['slug'])) ? $data['slug'] : Inflector::slugify($data['name']);;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->content = (!empty($data['content'])) ? $data['content'] : null;
        $this->listen = (!empty($data['listen'])) ? $data['listen'] : null;
        $this->buy = (!empty($data['buy'])) ? $data['buy'] : null;
        $this->disc_id = (!empty($data['disc_id'])) ? $data['disc_id'] : null;
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
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getListen()
    {
        return $this->listen;
    }

    /**
     * @param mixed $listen
     */
    public function setListen($listen)
    {
        $this->listen = $listen;
    }

    /**
     * @return mixed
     */
    public function getBuy()
    {
        return $this->buy;
    }

    /**
     * @param mixed $buy
     */
    public function setBuy($buy)
    {
        $this->buy = $buy;
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

    /**
     * @return mixed
     */
    public function getDiscId()
    {
        return $this->disc_id;
    }

    /**
     * @param mixed $disc_id
     */
    public function setDiscId($disc_id)
    {
        $this->disc_id = $disc_id;
    }
}