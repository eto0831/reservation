<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Genre;
use App\Models\Area;
use App\Models\Reservation;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AuthController extends Controller
{
    public function index()
    {
        $reservations = auth()->user()->reservations()->with('shop')->get();
        $reservation = auth()->user()->reservations()->first();
        $areas = Area::all();
        $genres = Genre::all();
        $favorites = auth()->user()->favorites()->with(['shop.area', 'shop.genre'])->get();
        $qrcode = QrCode::size(200)->generate(route('reservation.verify', $reservation->id));
        return view('mypage.index', compact('reservations', 'reservation', 'areas', 'genres','favorites'));
    }
}
