<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
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
        static::creating(function($comment){
            if(!GroupUser::where('user_id', $comment->user->id)
                ->where('group_id', $comment->photo->group->id)
                ->exists()) return false;
            return true;
        });
    }

    /**
     * Return pictures of a comment
     */
    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    /**
     * Return the reply to a comment
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'comment_id');
    }

    /**
     * Retourne the comments that the reply is linked to
     */
    public function replyTo()
    {
        return $this->belongsTo(Comment::class, 'comment_id', 'id');
    }

    /**
     * Return the user that posted the comment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text',
        'user_id',
        'photo_id',
        'comment_id'
    ];
}
