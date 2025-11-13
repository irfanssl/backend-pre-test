<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'task';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'status_id',
        'creator_id',
        'assignee_id',
        'report'
    ];


   /**
     * Task status.
     */
    public function status()
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }
}
