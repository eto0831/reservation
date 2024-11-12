<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\Genre;
use App\Models\Area;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $reservation = [
            'user_id' => auth()->user()->id, // ログイン中のユーザーID
            'shop_id' => $request->shop_id, // リクエストから取得した店舗ID
            'reserve_date' => $request->reserve_date, // リクエストから取得した予約日
            'reserve_time' => $request->reserve_time, // リクエストから取得した予約時間
            'guest_count' => $request->guest_count, // リクエストから取得した来店人数
        ];
        Reservation::create($reservation);

        return redirect('/');
    }

    public function destroy(Request $request)
    {
        auth()->user()->reservations()->where('id', $request->reservation_id)->delete();

        return back()->with('error', '予約の削除に失敗しました');
    }

    public function edit($id)
{
    $reservation = Reservation::find($id);
    $shop = $reservation->shop;

    return view('mypage.edit', compact('reservation', 'shop'));
}

    public function update(Request $request)
    {
        $reservation =  $request->all();
        Reservation::find($request->input('reservation_id'))->update($reservation);

        return redirect('/mypage');
    }

    public function scan()
{
    return view('admin.scan');
}

    public function verify($id)
    {
        $reservation = Reservation::find($id);
        if ($reservation) {
            return view('admin.verify', compact('reservation'));
        } else {
            return redirect()->back()->with('error', '予約が見つかりませんでした');
        }
    }

    public function updateIsVisited(Request $request, $id)
    {
        $reservation = Reservation::find($id);
        if ($reservation) {
            $reservation->is_visited = true;
            $reservation->save();
            return redirect()->back()->with('success', '来店が確認されました');
        } else {
            return redirect()->back()->with('error', '予約が見つかりませんでした');
        }
    }
}





