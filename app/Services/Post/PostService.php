<?php
namespace App\Services\Post;

use App\Models\Post\Post;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PostService extends BaseService
{

    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'title',
        'description',
        'creator_user_id',
        'like_count',
        'favorite_count',
        'simile_count',
        'status'
    ];

    /**
     * @return array|string[]
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Post::class;
    }
}
