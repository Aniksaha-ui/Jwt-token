<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\products;

class apiProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $products = DB::table("products")
                    ->join("categories","categories.id","products.category_id") 
                    ->join("subcategories","products.subcategory_id","subcategories.id")
                    ->join("brands","products.brand_id","brands.id")
                    ->join("products_colors","products.id","products_colors.product_id")
                    ->join("product_images","products.id","product_images.p_id")
                    ->select("*")
                    ->get();

            $data = array();

           foreach ($products as $key => $value) {
                $data[$key]["products"] = $value;
                $data[$key]['favourite'] = false;
            }          


        return response()->json(['data'=>$data],200);             
    }

    public function productwithlogin(Request $request){
        $data = array();

        $userId = $request->user_id;

        $products = DB::table("products")
                    ->join("categories","categories.id","products.category_id") 
                    ->join("subcategories","products.subcategory_id","subcategories.id")
                    ->join("brands","products.brand_id","brands.id")
                    ->join("products_colors","products.id","products_colors.product_id")
                    ->join("product_images","products.id","product_images.p_id")
                    ->select("*")
                    ->get();

            foreach ($products as $key => $value) {
                $data[$key]["products"] = $value;
                $data[$key]['favourite'] = $this->isFavourite($userId,$value->p_id);
            }
        
                

         return response()->json(['data'=>$data]);             

    }

    private function isFavourite(string $userId,string $product_id){
        // dd($userId);
        // dd($product_id);
        return true;
    }


    public function searchByKeyWordProductName(Request $request){

       

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $products = DB::table("products")
                    ->join("categories","categories.id","products.category_id") 
                    ->join("subcategories","products.subcategory_id","subcategories.id")
                    ->join("brands","products.brand_id","brands.id")
                    ->join("products_colors","products.id","products_colors.product_id")
                    ->join("product_images","products.id","product_images.p_id")
                    ->where("products.id",$id)
                    ->select("*")
                    ->get();

         foreach ($products as $key => $value) {
                $data[$key]["products"] = $value;
                $data[$key]['favourite'] = false;
            }            


        return response()->json(['data'=>$products],200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function searchByProductName(Request $request){
        $data = array();
        $keyword = $request->keyword;

        $products = DB::table("products")
                    ->join("categories","categories.id","products.category_id") 
                    ->join("subcategories","products.subcategory_id","subcategories.id")
                    ->join("brands","products.brand_id","brands.id")
                    ->join("products_colors","products.id","products_colors.product_id")
                    ->join("product_images","products.id","product_images.p_id")
                    ->where('product_name', 'like', '%' . $keyword . '%')
                    ->select("*")
                    ->get();

           foreach ($products as $key => $value) {
                $data[$key]["products"] = $value;
                $data[$key]['favourite'] = false;
           }

        return response()->json(['data'=>$data],200);

    }

    public function mostpopularProducts(){

        $selectedproducts =DB::table('carts')
             ->join('products','carts.product_id','products.id')
             ->join("categories","categories.id","products.category_id") 
             ->join("subcategories","products.subcategory_id","subcategories.id")
             ->join("brands","products.brand_id","brands.id")
             ->leftjoin("products_colors","carts.product_id","products_colors.product_id")
             ->leftjoin("product_images","carts.product_id","product_images.p_id")
             ->select(
              '*',DB::raw('sum(carts.cart_quantity) as most_ordered'))
             ->where('carts.isOrdered', "yes")
             ->orderBy("most_ordered","desc")
             ->groupBy('products.id')
             ->get();


             $data = array();


        foreach ($products as $key => $value) {
                $data[$key]["products"] = $value;
                $data[$key]['favourite'] = false;
            }

        // $popular = DB::table('carts')
        //            ->join('products','carts.product_id','products.id')
        //            ->select('products.product_name','carts.product_id', DB::raw("SUM(carts.cart_quantity) as popular"))
        //              ->where('carts.isOrdered','yes')
        //             ->groupBy('carts.product_id','products.product_name')
        //            ->get();

        return response()->json(['data'=>$data]);     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
