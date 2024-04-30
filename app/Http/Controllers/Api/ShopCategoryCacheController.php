<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Cacher;
use App\Http\Controllers\Controller;
use App\Models\ShopCategory;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class ShopCategoryCacheController extends Controller
{
    use GeneralTrait;
    private  $cacher;
    public function __construct(Cacher $cache)
    {
        $this->cacher = $cache;
    }
    public function indexAction()
    {
        $Data = $this->cacher->getCached("ShopCategory");
        if (is_null($Data)) {
            $setData = $this->ShopCategory();
            $cachData =  $this->returnDataCache('ShopCategory', $setData);
            $this->cacher->setCached("ShopCategory", json_encode($cachData));
            return $this->returnData('ShopCategory', $setData);
        } else {
            return  $this->cacher->getCached("ShopCategory");
        }
    }
    public function deleteData()
    {
        $this->cacher->removeCached("ShopCategory");

        return response()->json(["message" => "Delete ShopCategory"]);
    }

    protected function ShopCategory()
    {
        return  ShopCategory::where('status', 1)->with('items')->get();
    }
}
