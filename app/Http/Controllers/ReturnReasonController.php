<?php

namespace App\Http\Controllers;

use App\Models\ReturnReason;
use Illuminate\Http\Request;

class ReturnReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index() {
        $reasons = ReturnReason::paginate(10);

        return view('return-reason.index', compact('reasons'));
    }

    public function store(Request $request)
    {
        $all_req = $request->all();
        ReturnReason::create($all_req);
        return redirect()->back()->with('success', 'Data was successfully added.');
    }

    public function update(Request $request, $id)
    {   
        $except_values = ['_token'];
        $all_req = $request->except($except_values);
        ReturnReason::where('id',$id)->update($all_req);
        return redirect()->back()->with('success', 'Data was updated successfully.');
    }

    public function delete(ReturnReason $reasons, $id)
    {
        if ($reasons::where('id',$id)->delete()) {
            return response()->json([
                'status' =>  'success',
                'message' => 'Data was deleted.'
            ], 200);
        }

        return response()->json([
            'status' =>  'error',
            'message' => 'Deleting data failed.'
        ], 200);
    }
}
