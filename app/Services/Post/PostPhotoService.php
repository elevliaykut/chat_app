<?php
namespace App\Services\Post;

use App\Models\Post\PostPhoto;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PostPhotoService extends BaseService
{

    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'post_id',
        'photo_path',
        'media_id'
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
        return PostPhoto::class;
    }
}
