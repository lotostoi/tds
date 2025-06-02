<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seller extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'seller_id',
        'pp_id',
        'title',
        'platform',
        'url',
        'api_key',
    ];


    protected $casts = [
        'seller_id' => 'integer',
        'pp_id' => 'integer',
        'title' => 'string',
        'platform' => 'string',
        'url' => 'string',
        'api_key' => 'string',
    ];

    public function getSellerId()
    {
        return $this->seller_id;
    }

    public function setSellerId($seller_id)
    {
        $this->seller_id = $seller_id;
        return $this;
    }

    public function getPpId()
    {
        return $this->pp_id;
    }

    public function setPpId($pp_id)
    {
        $this->pp_id = $pp_id;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getPlatform()
    {
        return $this->platform;
    }

    public function setPlatform($platform)
    {
        $this->platform = $platform;
        return $this;
    }

    public function getApiKey()
    {
        return $this->api_key;
    }

    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function clicks()
    {
        return $this->hasMany(Click::class, 'seller_id', 'seller_id');
    }
}
