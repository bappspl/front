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
    protected $listen_spotify;
    protected $listen_deezer;
    protected $buy_itunes;
    protected $buy_google;
    protected $buy_playthemusic;
    protected $buy_muzodajnia;
    protected $allegro;
    protected $status_id;
    protected $disc_id;

    public function exchangeArray($data) 
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->slug = (!empty($data['slug'])) ? $data['slug'] : Inflector::slugify($data['name']);;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->content = (!empty($data['content'])) ? $data['content'] : null;
        $this->disc_id = (!empty($data['disc_id'])) ? $data['disc_id'] : null;
        $this->status_id = (!empty($data['status_id'])) ? $data['status_id'] : 2;

        $this->listen_deezer = (!empty($data['listen_deezer'])) ? $data['listen_deezer'] : null;
        $this->listen_spotify = (!empty($data['listen_spotify'])) ? $data['listen_spotify'] : null;

        $this->buy_google = (!empty($data['buy_google'])) ? $data['buy_google'] : null;
        $this->buy_itunes = (!empty($data['buy_itunes'])) ? $data['buy_itunes'] : null;
        $this->buy_muzodajnia = (!empty($data['buy_muzodajnia'])) ? $data['buy_muzodajnia'] : null;
        $this->buy_playthemusic = (!empty($data['buy_playthemusic'])) ? $data['buy_playthemusic'] : null;


        $this->allegro = (!empty($data['allegro'])) ? $data['allegro'] : null;
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

    /**
     * @return mixed
     */
    public function getBuyGoogle()
    {
        return $this->buy_google;
    }

    /**
     * @param mixed $buy_google
     */
    public function setBuyGoogle($buy_google)
    {
        $this->buy_google = $buy_google;
    }

    /**
     * @return mixed
     */
    public function getBuyItunes()
    {
        return $this->buy_itunes;
    }

    /**
     * @param mixed $buy_itunes
     */
    public function setBuyItunes($buy_itunes)
    {
        $this->buy_itunes = $buy_itunes;
    }

    /**
     * @return mixed
     */
    public function getBuyMuzodajnia()
    {
        return $this->buy_muzodajnia;
    }

    /**
     * @param mixed $buy_muzodajnia
     */
    public function setBuyMuzodajnia($buy_muzodajnia)
    {
        $this->buy_muzodajnia = $buy_muzodajnia;
    }

    /**
     * @return mixed
     */
    public function getBuyPlaythemusic()
    {
        return $this->buy_playthemusic;
    }

    /**
     * @param mixed $buy_playthemusic
     */
    public function setBuyPlaythemusic($buy_playthemusic)
    {
        $this->buy_playthemusic = $buy_playthemusic;
    }

    /**
     * @return mixed
     */
    public function getListenDeezer()
    {
        return $this->listen_deezer;
    }

    /**
     * @param mixed $listen_deezer
     */
    public function setListenDeezer($listen_deezer)
    {
        $this->listen_deezer = $listen_deezer;
    }

    /**
     * @return mixed
     */
    public function getListenSpotify()
    {
        return $this->listen_spotify;
    }

    /**
     * @param mixed $listen_spotify
     */
    public function setListenSpotify($listen_spotify)
    {
        $this->listen_spotify = $listen_spotify;
    }

    /**
     * @return mixed
     */
    public function getAllegro()
    {
        return $this->allegro;
    }

    /**
     * @param mixed $allegro
     */
    public function setAllegro($allegro)
    {
        $this->allegro = $allegro;
    }
}