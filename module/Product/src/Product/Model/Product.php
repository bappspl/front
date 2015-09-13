<?php
namespace Product\Model;

use CmsIr\System\Model\Model;
use CmsIr\System\Util\Inflector;

class Product extends Model
{
    protected $id;
    protected $name;
    protected $status_id;
    protected $price;
    protected $catalog_number;
    protected $bestseller;
    protected $show_price;
    protected $category_id;

    // dictionary
    protected $class_id;
    protected $length_id;
    protected $height_id;
    protected $width_id;
    protected $volume_id;
    protected $weight_id;
    protected $unit_id;

    // virtual
    protected $blocks;
    protected $category;
    protected $category_name;
    protected $title;
    protected $content;

    public function exchangeArray($data) 
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->name = (!empty($data['name'])) ? $data['name'] : null;
        $this->status_id = (!empty($data['status_id'])) ? $data['status_id'] : 2;
        $this->price = (!empty($data['price'])) ? $data['price'] : null;
        $this->catalog_number = (!empty($data['catalog_number'])) ? $data['catalog_number'] : null;
        $this->bestseller = (!empty($data['bestseller'])) ? $data['bestseller'] : 0;
        $this->show_price = (!empty($data['show_price'])) ? $data['show_price'] : 0;
        $this->category_id = (!empty($data['category_id'])) ? $data['category_id'] : null;
        $this->blocks = (!empty($data['blocks'])) ? $data['blocks'] : null;
        $this->category = (!empty($data['category'])) ? $data['category'] : null;
        $this->category_name = (!empty($data['category_name'])) ? $data['category_name'] : null;

        // dictionary
        $this->class_id = (!empty($data['class_id'])) ? $data['class_id'] : null;
        $this->length_id = (!empty($data['length_id'])) ? $data['length_id'] : null;
        $this->height_id = (!empty($data['height_id'])) ? $data['height_id'] : null;
        $this->width_id = (!empty($data['width_id'])) ? $data['width_id'] : null;
        $this->volume_id = (!empty($data['volume_id'])) ? $data['volume_id'] : null;
        $this->weight_id = (!empty($data['weight_id'])) ? $data['weight_id'] : null;
        $this->unit_id = (!empty($data['unit_id'])) ? $data['unit_id'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->content = (!empty($data['content'])) ? $data['content'] : null;
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
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getCatalogNumber()
    {
        return $this->catalog_number;
    }

    /**
     * @param mixed $catalog_number
     */
    public function setCatalogNumber($catalog_number)
    {
        $this->catalog_number = $catalog_number;
    }

    /**
     * @return mixed
     */
    public function getBestseller()
    {
        return $this->bestseller;
    }

    /**
     * @param mixed $bestseller
     */
    public function setBestseller($bestseller)
    {
        $this->bestseller = $bestseller;
    }

    /**
     * @return mixed
     */
    public function getShowPrice()
    {
        return $this->show_price;
    }

    /**
     * @param mixed $show_price
     */
    public function setShowPrice($show_price)
    {
        $this->show_price = $show_price;
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
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @param mixed $blocks
     */
    public function setBlocks($blocks)
    {
        $this->blocks = $blocks;
    }

    /**
     * @return mixed
     */
    public function getClassId()
    {
        return $this->class_id;
    }

    /**
     * @param mixed $class_id
     */
    public function setClassId($class_id)
    {
        $this->class_id = $class_id;
    }

    /**
     * @return mixed
     */
    public function getLengthId()
    {
        return $this->length_id;
    }

    /**
     * @param mixed $length_id
     */
    public function setLengthId($length_id)
    {
        $this->length_id = $length_id;
    }

    /**
     * @return mixed
     */
    public function getWidthId()
    {
        return $this->width_id;
    }

    /**
     * @param mixed $width_id
     */
    public function setWidthId($width_id)
    {
        $this->width_id = $width_id;
    }

    /**
     * @return mixed
     */
    public function getVolumeId()
    {
        return $this->volume_id;
    }

    /**
     * @param mixed $volume_id
     */
    public function setVolumeId($volume_id)
    {
        $this->volume_id = $volume_id;
    }

    /**
     * @return mixed
     */
    public function getWeightId()
    {
        return $this->weight_id;
    }

    /**
     * @param mixed $weight_id
     */
    public function setWeightId($weight_id)
    {
        $this->weight_id = $weight_id;
    }

    /**
     * @return mixed
     */
    public function getUnitId()
    {
        return $this->unit_id;
    }

    /**
     * @param mixed $unit_id
     */
    public function setUnitId($unit_id)
    {
        $this->unit_id = $unit_id;
    }

    /**
     * @return mixed
     */
    public function getHeightId()
    {
        return $this->height_id;
    }

    /**
     * @param mixed $height_id
     */
    public function setHeightId($height_id)
    {
        $this->height_id = $height_id;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getCategoryName()
    {
        return $this->category_name;
    }

    /**
     * @param mixed $category_name
     */
    public function setCategoryName($category_name)
    {
        $this->category_name = $category_name;
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
}