<?php

namespace App\Http\Controllers;

use App\Present;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
{
    $present = Present::whereUserId(auth()->user()->id)->whereTanggal(date('Y-m-d'))->first();
    $url = 'https://kalenderindonesia.com/api/14e93a5ab9e38cd7/libur/masehi/'.date('Y/m');
    $kalender = file_get_contents($url);
    $kalender = json_decode($kalender, true);
    $libur = false;
    $holiday = null;

    if (isset($kalender['data']) && $kalender['data'] !== false) {
        if (isset($kalender['data']['libur']) && isset($kalender['data']['libur']['data'])) {
            foreach ($kalender['data']['libur']['data'] as $key => $value) {
                if ($value['date'] == date('Y-m-d')) {
                    $holiday = $value['name'];
                    $libur = true;
                    break;
                }
            }
        }
    }
    return view('home', compact('present','libur','holiday'));
}
}
