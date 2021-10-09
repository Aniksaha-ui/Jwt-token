<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\cart;
class apiCartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
       $data = array();
        $data['user_id'] = $request->user_id;
        $data['product_id'] = $request->product_id;
        $data['cart_quantity'] = $request->cart_quantity;
        $data['isOrdered'] = $request->isOrdered;

        $cart = cart::where('product_id',$request->product_id)->where("user_id",$request->user_id)->where("isOrdered","no")->count();

        if($cart>0){
          $insertgetId = cart::where('product_id',$request->product_id)->where("user_id",$request->user_id)->where("isOrdered","no")->increment('cart_quantity',$request->cart_quantity);
        }
        else{
        $insertgetId = DB::table('carts')->insertGetId($data);
        }

        return response()->json(["data"=>$data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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


   public function getUserCart(Request $request){
        $data = array();
        $userId = $request->user_id;

        $getUserCart = DB::table("carts")
                        ->join("products","carts.product_id","products.id")
                        ->join("categories","categories.id","products.category_id") 
                    ->join("subcategories","products.subcategory_id","subcategories.id")
                    ->join("brands","products.brand_id","brands.id")
                    ->join("products_colors","products.id","products_colors.product_id")
                    ->join("product_images","products.id","product_images.p_id")
                        ->where("carts.user_id",$request->user_id)
                        ->where("carts.isOrdered","no")
                        ->select("*")
                       ->get();


         foreach ($getUserCart as $key => $value) {
                $data[$key]["products"] = $value;
                $data[$key]['favourite'] = $this->isFavourite($userId,$value->p_id);
            }
                      

         // dd($getUserCart);              

         $total_price = 0;

         foreach($getUserCart as $getUserCarts){
            $total_price = $total_price + ($getUserCarts->selling_price*$getUserCarts->cart_quantity); 
         }              


         // return response()->json(["user_cart"=>$getUserCart]);

         return response()->json(["data"=>data,"total_price"=>$total_price]);              
    }

     private function isFavourite(string $userId,string $product_id){
        // dd($userId);
        // dd($product_id);
        return true;
    }

}
