<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;

class PageController extends Controller
{
    //
    public function __construct(){

    }

    function introduce() {
        $info_page = Page::where("slug", "gioi-thieu.html")->first();
        return view("client.page.introduce", compact("info_page"));
    }

    function contact() {
        $info_page = Page::where("slug", "lien-he.html")->first();
        return view("client.page.contact", compact("info_page"));
    }
}
