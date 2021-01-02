<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PhotoUser extends Pivot
{
    use HasFactory;


    /**
     * function that boot at model creation
     */
    protected static function booted()
    {
        /**
         * Verify the user belongs to the chosen groups before being able to comment
         *
         * @param Illuminate\Database\Eloquent\Model;
         * @return boolean;
         */
        static::creating(function($photoUser){
            $photo = Photo::where('id', $photoUser->photo_id)->first();
            $group_user = GroupUser::where('user_id', $photoUser->user_id)
                ->where('group_id', $photo->group->id);
            if(!$group_user->exists()) return false;
            return true;
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'photo_id',
        'created_at',
        'updated_at'
    ];
}
