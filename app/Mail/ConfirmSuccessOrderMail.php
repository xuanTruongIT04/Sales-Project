<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ConfirmSuccessOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $data;

    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //GET VAR PRIMARY KEY
        $order_id = $this->data['order_id'];
        $customer_id = $this->data["customer_id"];

        // GET LIST PRODUCT
        $list_product = get_list_product_by_order_id($order_id);

        // GET INFOR CUSTOMER
        $info_customer = get_info_customer($customer_id);
        // dd($this->data);

        // GET INFOR ORDER
        $info_order = get_info_order($order_id);

        // UPDATE TYPE
        $date_order = date('h:i:s - d/m/Y', $this->data["date_order"]);
        $order_status = field_status_order($this->data["order_status"]);

        $this->data["list_product"] = $list_product;
        // dd($this->data['list_product']);
        $this->data["info_customer"] = $info_customer;
        $this->data["info_order"] = $info_order;
        $this->data["date_order"] = $date_order;
        $this->data["order_status"] = $order_status;
        
        return $this->view('mails.confirmSuccessOrder')
            ->from("nxt160602@gmail.com", "Ismart Shop")
            ->subject("[ISMART_SHOP] Thông báo đặt hàng thành công")
            ->with($this->data);
    }
}
