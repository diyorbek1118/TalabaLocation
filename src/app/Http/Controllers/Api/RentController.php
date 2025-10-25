<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RentStorerequest;
use App\Http\Requests\RentUpdaterequest;
use App\Models\Rent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RentController extends Controller
{
   
    public function index(): JsonResponse
    {
        try{
            $rents = Rent::with('images')->get();

            if($rents->isEmpty()){
                return response()->json([
                    "success" => false,
                    "error" => "Rents not found"
                ]);
            }
            return response()->json([
                "rents"=> $rents
            ],200);
        }catch (\Exception $e){
            return response()->json([
                'success'=>false,
                'message' => 'Sever error',
                "error"=> $e->getMessage()
            ],500);
        }
    }

    
    public function store(RentStorerequest $request): JsonResponse
    {
        try{
        $validate =$request->validated();

        $validate['renter_id'] = Auth::id();

        $rent = Rent::create($validate);

        if($request->hasFile('images')){
        foreach($request->file('images') as $image){
            $path = $image->store('rent_images', 'public'); 
            $rent->images()->create([
                'image_path' => $path
            ]);
        }
    }

        return response()->json([
            'rent'=> $rent->load("images"),
            'success'=>true
        ],201);
    } catch (\Illuminate\Validation\ValidationException $e) {
         return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
    }
    catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Server error',
            'error' => $e->getMessage()
        ], 500);
    }

    }

    public function show(string $id): JsonResponse
    {
        $rent = Rent::with('images')->where('id', $id)->first();

        if(!$rent){
            return response()->json([
                'success'=> false,
                'error'=> 'Rent not found'
            ],404);
        }

        return response()->json([
            'rent'=> $rent,
            'success'=> true
        ]);
    }

    public function update(RentUpdaterequest $request, string $id): JsonResponse
    {
        try{
            $validate =$request->validated();

        $rent = Rent::with('images')->where('id', $id)->first();
        
        if(!$rent){
            return response()->json([
            'success'=> false,
            'error' => 'Rent not found'
        ],404);
        }

        $rent->update($validate);

        if($request->hasFile('images')){
             foreach($rent->images as $oldImage){
            if (Storage::disk('public')->exists($oldImage->image_path)) {
                Storage::disk('public')->delete($oldImage->image_path);
            }
            $oldImage->delete(); 
            
        }

        foreach($request->file('images') as $image){
                $path = $image->store('rent_images', 'public');
                $rent->images()->create(['image_path' => $path]);
            }
        }

            return response()->json([
                'rent' => $rent,
                'success'=> true
            ],200);

        }catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success'=> false,
                'errors'=>$e->errors()
            ],422);
        }
        catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'message' => "Server error",
                'error'=> $e->getMessage()
            ],500);
        }
    }

    public function destroy(string $id)
    {
        try{
            $rent = Rent::find($id);
        if(!$rent){
            return response()->json([
                'success'=> false,
                'error'=> 'Rent not found'
            ],404);
        }
        $rent->delete();

        return response()->json([
            'success'=> true
        ],200);
        }catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'Server error',
                'error'=> $e->getMessage()
            ],500);
        }
    }
}
