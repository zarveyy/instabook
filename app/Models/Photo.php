<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    /**
     * function that boot at model creation
     */
    protected static function booted()
    {
        /**
         * Verify the user belongs to the chosen groups before being able to post a picture
         *
         * @param Illuminate\Database\Eloquent\Model;
         * @return boolean;
         */
        static::creating(function($photo){
            if(!GroupUser::where('user_id', $photo->owner->id)
                ->where('group_id', $photo->group->id)
                ->exists()) return false;
            return true;
        });
    }


    /**
     * Return the comments of a picture
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    /**
     * Return the tags of a picture
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class)
            ->using(PhotoTag::class)
            ->withPivot('id')
            ->withTimestamps();
    }

    /**
     * Return the user that published the pic
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    /**
     * Return the tagged users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->using(PhotoUser::class)
            ->withPivot('id')
            ->withTimestamps();
    }


    /**
     * Return the group the pictures belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable =  [
        'title',
        'description',
        'date',
        'resolution',
        'width',
        'height',
        'created_at',
        'updated_at',
        'user_id'
    ];


}
