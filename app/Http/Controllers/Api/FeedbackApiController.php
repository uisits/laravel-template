<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeedbackFormRequest;
use App\Http\Resources\FeedbackResource;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class FeedbackApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $feedbacks = Feedback::with(['user'])
            ->when(auth()->user()->cannot('admin'), function(Builder $query,) {
                $query->where('user_id', auth()->user()->id);
            })
            ->get();
//        if (auth()->user()->can('is-admin')) {
//            $feedbacks = Feedback::with(['user'])
//                ->orderBy('created_at', 'DESC')
//                ->get();
//
//            return FeedbackResource::collection($feedbacks);
//        }
        return FeedbackResource::collection($feedbacks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return FeedbackResource
     */
    public function store(FeedbackFormRequest $feedbackFormRequest)
    {
        $user = User::where('uin', $feedbackFormRequest->user_uin)->firstOrFail();

        $feedback = Feedback::create([
            'user_id' => $user->id,
            'title' => $feedbackFormRequest->title,
            'description' => $feedbackFormRequest->description,
        ]);

        return new FeedbackResource($feedback);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return FeedbackResource
     */
    public function show($id)
    {
        $feedback = Feedback::findOrFail($id);

        return new FeedbackResource($feedback);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return FeedbackResource
     */
    public function update(FeedbackFormRequest $feedbackFormRequest, $id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->title = $feedbackFormRequest->title;
        $feedback->description = $feedbackFormRequest->description;
        $feedback->save();

        return new FeedbackResource($feedback);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
