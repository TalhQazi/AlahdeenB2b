<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatLabel;

class ChatLabelController extends Controller
{

    public function saveLabels($conversationId, Request $request, ChatLabel $chatLabel)
    {
        $this->authorize('saveLabels', $chatLabel);

        if($request->ajax()) {
            $labelIds = [];
            $savedLabels = [];
            try {
                if(!empty($request->label)) {
                    $labelIds = array_keys($request->label);
                    $existingLabels = $chatLabel::select('label_id')->where('conversation_id', $conversationId)->get()->pluck('label_id')->toArray();
                    if(!empty($existingLabels)) {
                        $labelIds = array_diff($labelIds, $existingLabels);
                    }

                    foreach($labelIds as $labelId) {
                        $chatLabel = new ChatLabel();
                        $chatLabel->conversation_id = $conversationId;
                        $chatLabel->label_id = $labelId;
                        $chatLabel->user_id = Auth::user()->id;

                        if($chatLabel->save()) {
                            array_push($savedLabels, $labelId);
                        }

                    }

                } else {
                    // TODO handle else case
                }

                return response()->json([$savedLabels]);
            } catch (\Throwable $th) {
                return response()->json([$th->getMessage()],400);
            }

        }
    }

    public function getLabels($conversationId, Request $request, ChatLabel $chatLabel)
    {
        $this->authorize('getLabels', $chatLabel);

        if($request->ajax()) {

            try {

                $labelIds = $chatLabel::select('label_id')
                            ->where('conversation_id', $conversationId)
                            ->where('user_id', Auth::user()->id)
                            ->get()
                            ->pluck('label_id')
                            ->toArray();

                return response()->json([$labelIds]);
            } catch (\Throwable $th) {
                return response()->json([$th->getMessage()],400);
            }

        }
    }

}
