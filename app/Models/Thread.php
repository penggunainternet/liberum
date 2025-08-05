<?php

namespace App\Models;

use App\Traits\HasLikes;
use App\Traits\HasViews;
use App\Traits\HasMedia;
use App\Models\ReplyAble;
use App\Traits\HasAuthor;
use App\Traits\HasReplies;
use Illuminate\Support\Str;
use Laravel\Jetstream\HasTeams;
use App\Traits\HasSubscriptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Thread extends Model implements ReplyAble, SubscriptionAble, Viewable
{
    use HasLikes;
    use HasAuthor;
    use HasFactory;
    use HasReplies;
    use HasSubscriptions;
    use InteractsWithViews;
    use HasMedia;

    const TABLE = 'threads';

    protected $table = self::TABLE;

    protected $fillable = [
        'title',
        'body',
        'slug',
        'category_id',
        'author_id',
    ];

    protected $with = [
        'category',
        'likesRelation',
        'authorRelation',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function excerpt(int $limit = 250): string
    {
        return Str::limit(strip_tags($this->body()), $limit);
    }

    public function replyAbleSubject(): string
    {
        return $this->title();
    }

    public function id(): int
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function delete()
    {
        parent::delete();
    }
}
