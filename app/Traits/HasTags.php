<?php

namespace App\Traits;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasTags
{
    public function tags()
    {
        return $this->tagsRelation;
    }

    public function syncTags(array $tags)
    {
        $this->save();

        // Filter out empty values and convert to integers
        $validTags = array_filter(array_map('intval', $tags));

        $this->tagsRelation()->sync($validTags);
        $this->unsetRelation('tagsRelation');
    }

    public function removeTags()
    {
        $this->tagsRelation()->detach();
        $this->unsetRelation('tagsRelation');
    }

    public function tagsRelation(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }
}
