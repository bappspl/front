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

    // virtual
    protected $blocks;

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
}