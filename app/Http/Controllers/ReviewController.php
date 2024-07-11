<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\File;
use App\Models\Review;
use App\Models\UserFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $userId=Auth::id();
        $cards = Card::with(['questions.userFeedbacks' => function($query) use ($userId) {
            $query->whereHas('review', function($query) use ($userId) {
                $query->where('u_id', $userId);
            });
        }])->get();
        $files=File::where("u_id",Auth::id())->get();
        return view('tiu.review', ['cards' => $cards,'files'=>$files]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $quantity = $request->quantity;
        $question_id = $request->question_id;
        $uid = Auth::id();

        // Create or update the review
        $review = Review::updateOrCreate(
            ['u_id' => $uid],
            ['u_id' => $uid]
        );

        // Create or update the feedback
        $feedback = UserFeedback::updateOrCreate(
            ['review_id' => $review->id, 'card_question_id' => $question_id],
            ['quantity' => $quantity]
        );

        // Return the feedback
        return response()->json(["success"=>true,"message"=>"Saved Successfully"]);
    }
    public function file_upload(Request $request){
        $request->validate([
            'files.*' => 'required|mimes:jpg,jpeg,png|max:2048',
        ]);
        foreach ($request->file('files') as $file) {
            $extension=$file->getClientOriginalExtension();
            $file_name=UuidV4::uuid4().'.'.$extension;
             $path = $file->storeAs('public/files', $file_name);
             File::create(['path' => $file_name,'u_id'=>Auth::id()]);
        }
        return response()->json(["success"=>true,"message"=>"Saved Successfully"]);

    }
}
