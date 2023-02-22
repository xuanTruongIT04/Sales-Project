<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Mail\ConfirmSuccessOrderMail;
use App\Order;
use App\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    //
    public function __construct()
    {

    }

    public function cart()
    {
        $list_product_order = Cart::content();

        return view("client.order.cart", compact("list_product_order"));
    }

    public function add(Request $request, $slug)
    {
        $add_product_success = false;
        $product = get_product($slug);
        $id = get_product_id($slug);
        Cart::add([
            "id" => $id,
            'name' => $product->product_name,
            'qty' => 1,
            'price' => $product->price_new,
            'options' => [
                "product_code" => $product->product_code,
                "product_name" => $product->product_name,
                "slug" => $product->slug,
                "product_desc" => $product->product_desc,
                "product_detail" => $product->product_detail,
                "product_status" => $product->product_status,
                "price_old" => $product->price_old,
                "price_new" => $product->price_new,
                "qty_sold" => $product->qty_sold,
                "qty_remain" => $product->qty_remain,
                "sold_most" => $product->sold_most,
                "product_cat_id" => $product->product_cat_id,
                'product_thumb' => get_product_main_thumb($product->id),
            ],
        ]);
        $add_product_success = true;
        // return redirect()->back()->with("status", "Đã thêm sản phẩm có tên {$product->product_name} vào giỏ hàng thành công!");
        return redirect()->back()->with("add_product_success", "true");
    }

    public function update()
    {
        $product_id = !empty($_POST['product_id']) ? (int) $_POST['product_id'] : "";
        $qty_order = !empty($_POST['qty_order']) ? (int) $_POST['qty_order'] : "";

        $product_order = Cart::content();
        $into_money = 0;
        $total_price = 0;
        if (!empty($product_order) && count($product_order) > 0) {
            foreach ($product_order as $item) {
                if ($item->id == $product_id) {
                    $item->qty = $qty_order;
                    $item->total = $qty_order * $item->price;
                    $into_money += $item->total;
                }
                $total_price += $item->total;
            }
        }

        $data = array(
            'into_money' => currency_format($into_money),
            'total_price' => currency_format($total_price),
            "product_id" => $product_id,
        );
        echo json_encode($data);

    }

    public function buynow(Request $request, $slug)
    {
        $product = get_product($slug);
        $id = get_product_id($slug);
        Cart::add([
            "id" => $id,
            'name' => $product->product_name,
            'qty' => 1,
            'price' => $product->price_new,
            'options' => [
                "product_code" => $product->product_code,
                "product_name" => $product->product_name,
                "slug" => $product->slug,
                "product_desc" => $product->product_desc,
                "product_detail" => $product->product_detail,
                "product_status" => $product->product_status,
                "price_old" => $product->price_old,
                "price_new" => $product->price_new,
                "qty_sold" => $product->qty_sold,
                "qty_remain" => $product->qty_remain,
                "sold_most" => $product->sold_most,
                "product_cat_id" => $product->product_cat_id,
                'product_thumb' => get_product_main_thumb($product->id),
            ],
        ]);
        return redirect("thanh-toan.html")->with("status", "Bạn đã thêm sản phẩm có tên {$product->product_name} vào giỏ hàng thành công!");
    }

    public function checkout()
    {
        $list_product_order = get_list_product_order();
        $total_price = get_total_price();

        // Lấy ra mảng product_id và số lượng đi kèm
        return view("client.order.checkout", compact("list_product_order", "total_price"));
    }

    public function store(Request $requests)
    {
        if ($requests->input('order_now')) {
            $requests->validate(
                [
                    'fullname' => ['required', 'string', 'max:300'],
                    'email' => ['required', 'string', 'email', 'max:255'],
                    "address" => ['required', 'string', 'max:300'],
                    'phone' => "required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10",
                ],
                [
                    'required' => ":attribute không được để trống",
                    "max" => [
                        "numeric" => ":attribute không được lớn hơn :max.",
                        "file" => ":attribute không được nhiều hơn :max KB.",
                        "string" => ":attribute không được nhiều hơn :max kí tự.",
                        "array" => ":attribute không được nhiều hơn :max mục.",
                    ],
                    "min" => [
                        "numeric" => ":attribute không được bé hơn :min.",
                        "file" => ":attribute không được ít hơn :min KB.",
                        "string" => ":attribute không được ít hơn :min kí tự.",
                        "array" => ":attribute phải có ít nhất :min mục.",
                    ],
                ],
                [
                    "fullname" => "Tên khách hàng",
                    "phone" => "Số điện thoại",
                    "email" => "Địa chỉ email",
                    "address" => "Địa chỉ nhận hàng",
                ]
            );
            // Tạo biến
            $fullname = $requests->input("fullname");
            $email = $requests->input("email");
            $address = $requests->input("address");
            $phone = $requests->input("phone");
            $note = $requests->input("notes");
            $payment_method = $requests->input("payment_method");

            // Lấy ra mảng product_id và số lượng đi kèm
            $list_product_order = get_list_product_order();
            $list_info_product_order = get_array_key_lpo($list_product_order);
            $check_customer_familiar = check_customer_familiar($phone, $email);
            if ($check_customer_familiar > 0) {
                $customer_id = $check_customer_familiar;
            } else {
                //CREATE CUSTOMER
                $data_1 = array(
                    'customer_name' => $fullname,
                    'number_phone' => $phone,
                    'email' => $email,
                );
                $customer_id = Customer::create($data_1)->id;
            }
            $order_status = "pending";
            $data_2 = array(
                'address_delivery' => $address,
                'payment_method' => $payment_method,
                'notes' => $note,
                'order_status' => $order_status,
                'customer_id' => $customer_id,
            );
            // CREATE ORDER
            $order_id = Order::create($data_2)->id;

            $order_code = code_order_format($order_id);

            // UPDATE ORDER CODE
            $data_6 = array(
                'order_code' => $order_code,
            );
            $update_order_code = Order::where('id', $order_id)->update($data_6);

            $cnt_insert_detail_order = 0;
            if (!empty($list_info_product_order) && count($list_info_product_order) > 0 && $update_order_code > 0) {
                foreach ($list_info_product_order as $product_id => $number_order) {
                    $qty_remain = get_qty_remain_product($product_id);
                    $qty_sold = get_qty_sold_product($product_id);

                    //CREATE DETAIL ORDER
                    $data_3 = array(
                        'order_id' => $order_id,
                        'product_id' => $product_id,
                        'number_order' => $number_order,
                    );
                    $order_product_id = DB::table("order_product")->insertGetId($data_3);

                    // UPDATE QUANTITY PRODUCT
                    $data_4 = array(
                        'qty_remain' => $qty_remain - $number_order,
                        'qty_sold' => $qty_sold + $number_order,
                    );
                    $update_qty_product = Product::where("id", $product_id)->update($data_4);

                    // DATE ORDER
                    $data_5 = array(
                        "date_order" => time(),
                    );
                    if ($order_product_id > 0 && $update_qty_product > 0) {
                        $cnt_insert_detail_order++;
                        if (!empty($data_1)) {
                            $data_0 = array_merge($data_6, $data_1, $data_2, $data_3, $data_4, $data_5);
                        } else {
                            $data_0 = array_merge($data_6, $data_2, $data_3, $data_4, $data_5);
                        }
                        add_cart_order_success($data_0);
                    }
                }
            }

            if ($cnt_insert_detail_order > 0) {
                $data = $_SESSION['client']['cart']['order_success'];
                $requests->session()->put("detail_order_success", $data);
                return redirect("/gui-email");
            } else {
                return redirect("/thanh-toan-that-bai.html");
            }

        }
    }

    public function checkoutSuccess(Request $requests)
    {
        $detail_order_success = $requests->session()->get("detail_order_success");
        $order_id = $detail_order_success["order_id"];
        $list_product = get_list_product_by_order_id($order_id);

        clear_session_order_cart();
        if (count($detail_order_success) > 0) {
            return view("client.order.checkoutSuccess", compact("detail_order_success", "list_product"));
        }
    }

    public function checkoutFail()
    {
        return view("client.order.checkoutFail");
    }

    public function remove($rowId)
    {
        Cart::remove($rowId);
        return redirect("gio-hang.html")->with("status", "Đã xoá sản phẩm ra khỏi giỏ hàng thành công!");
    }

    public function destroy()
    {
        Cart::destroy();
        return redirect("gio-hang.html")->with("status", "Đã xoá giỏ hàng thành công!");
    }

    public function sendmail(Request $requests)
    {
        $detai_order_success = $requests->session()->get("detail_order_success");
        $data = array(
            "order_code" => $detai_order_success["order_code"],
            "customer_name" => $detai_order_success["customer_name"],
            "number_phone" => $detai_order_success["number_phone"],
            "email" => $detai_order_success["email"],
            "address_delivery" => $detai_order_success["address_delivery"],
            "payment_method" => $detai_order_success["payment_method"],
            "notes" => $detai_order_success["notes"],
            "order_status" => $detai_order_success["order_status"],
            "customer_id" => $detai_order_success["customer_id"],
            "order_id" => $detai_order_success["order_id"],
            "product_id" => $detai_order_success["product_id"],
            "number_order" => $detai_order_success["number_order"],
            "qty_remain" => $detai_order_success["qty_remain"],
            "qty_sold" => $detai_order_success["qty_sold"],
            "date_order" => $detai_order_success["date_order"],
        );

        Mail::to($data["email"])->send(new ConfirmSuccessOrderMail($data));
        return redirect("/thanh-toan-thanh-cong.html");
    }

}

function redirect_to($url)
{
    $path = url('/') . "/" . $url;
    if (!empty($path)) {
        return header("Location: " . $path);
    }

    return false;
}

function get_array_key_lpo($lpo)
{
    $list_product_id = array();
    if (!empty($lpo) && count($lpo) > 0) {
        foreach ($lpo as $key => $value) {
            $list_product_id[$value->id] = $value->qty;
        }
    }
    return $list_product_id;
}

function check_customer_familiar($phone, $mail)
{
    $sql = Customer::where("number_phone", "LIKE", $phone)
        ->where("email", $mail)
        ->select("id")
        ->first();
    if (!empty($sql)) {
        return $sql->customer_id;
    }

    return false;
}

function get_qty_remain_product($product_id)
{
    $sql = Product::find($product_id)
        ->qty_remain;
    if (!empty($sql)) {
        return $sql;
    }

    return false;
}

function get_qty_sold_product($product_id)
{
    $sql = Product::find($product_id)
        ->qty_sold;
    if (!empty($sql)) {
        return $sql;
    }

    return false;
}

function add_cart_order_success($data)
{
    $_SESSION['client']['cart']['order_success'] = $data;
    return $_SESSION['client']['cart']['order_success'];
}

function clear_session_order_cart()
{
    if (Cart::destroy()) {
        return true;
    }
    return false;
}
