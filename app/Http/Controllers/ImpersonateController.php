<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class ImpersonateController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|max:255',
        ]);

        // Guard against administrator impersonate
        if (auth()->user()->can('admin')) {
            $user = User::findOrFail($request->id);
            Session::put('impersonate', $user->id);
        } else {
            Session::flash('error', 'Impersonate disabled for this user.');
        }

        return redirect()->home();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        abort_if(!auth()->user()->isImpersonating(), 401, 'No active Impersonation!');
        Session::forget('impersonate');
        return redirect()->home();
    }
}
