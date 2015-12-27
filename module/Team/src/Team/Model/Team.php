<?php
namespace Team\Model;

use CmsIr\System\Model\Model;
use CmsIr\System\Util\Inflector;

class Team extends Model
{
    protected $id;
    protected $name;
    protected $first_name;
    protected $slug;
    protected $status_id;
    protected $facebook;
    protected $content;
    protected $short_content;
    protected $filename_main;
    protected $function;

    public function exchangeArray($data) 
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->slug = (!empty($data['slug'])) ? $data['slug'] : null;
        $this->status_id = (!empty($data['status_id'])) ? $data['status_id'] : 2;
        $this->facebook = (!empty($data['facebook'])) ? $data['facebook'] : null;
        $this->content = (!empty($data['content'])) ? $data['content'] : null;
        $this->first_name = (isset($data['first_name'])) ? $data['first_name'] : null;
        $this->filename_main = (!empty($data['filename_main'])) ? $data['filename_main'] : null;
        $this->function = (!empty($data['function'])) ? $data['function'] : null;
        $this->short_content = (!empty($data['short_content'])) ? $data['short_content'] : null;
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
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param mixed $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
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
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @param mixed $function
     */
    public function setFunction($function)
    {
        $this->function = $function;
    }

    /**
     * @return mixed
     */
    public function getShortContent()
    {
        return $this->short_content;
    }

    /**
     * @param mixed $short_content
     */
    public function setShortContent($short_content)
    {
        $this->short_content = $short_content;
    }
}