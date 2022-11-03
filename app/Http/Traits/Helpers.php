<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait Helpers
{
    public function decryptFind($model, $encryptedId){
        try {
            $user = $model::findOrFail(decrypt($encryptedId));
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
        return $user;
    }

    public function deleteItem($item, $var)
    {
        if (!is_string($item) && $item->delete()){
            return redirect()->back()->with('success', $var.' Deleted!');
        }else {
            return redirect()->back()->with('error', $item);
        }
    }

    public function imageUpload($image)
    {
        $imageName = time().'.'.$image->extension();
        // Public Folder
        $image->move(public_path('images'), $imageName);
        return $imageName;
    }
    public function removeDirectoryFile($image)
    {
      return File::delete('images/'.$image);
    }

    public function getWorkingDays()
    {
        $currentTime = Carbon::now(); //returns current day
        $startDate = Carbon::parse($currentTime->firstOfMonth()->format('Y-m-d'));
        $endDate = Carbon::parse($currentTime->lastOfMonth()->format('Y-m-d'));

        $holidays = [
//            Carbon::parse("2022-11-04"),
//            Carbon::parse("2022-11-16"),
//            Carbon::parse("2022-11-02"),
        ];

       return $days = $startDate->diffInDaysFiltered(function (Carbon $date) use ($holidays) {
            return $date->isWeekday() && !in_array($date, $holidays);
        }, $endDate);

    }
}
