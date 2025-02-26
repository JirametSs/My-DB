<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Eloquent\Relations\Relation;

abstract class SearchableController extends Controller
{
    abstract function getQuery(): Builder;

    

    public function filterByTerm(Builder|Relation $query, ?string $term) : Builder|Relation
    {
        if (!empty($term)) {
            foreach (\preg_split('/\s+/', \trim($term)) as $word) {
                $query->where(function (Builder $innerQuery) use ($word) {
                    $innerQuery
                        ->where('code', 'LIKE', "%{$word}%")
                        ->orWhere('name', 'LIKE', "%{$word}%");
                        
                });
            }
        }
        return $query;
    }

    public function prepareSearch(array $search): array
    {
        return [
            'term' => $search['term'] ?? null,
        ];
    }

    public function filter(Builder|Relation $query, array $search) : Builder|Relation
    {
        return $this->filterByTerm($query, $search['term']);
    }

    public function search(array $search): Builder
    {
        return $this->filter($this->getQuery(), $search);
    }

    public function paginate(Builder $query, int $perPage = 3): LengthAwarePaginator
    {
        return $query->paginate($perPage);
    }

    // For easily searching by code.
    public function find(string $code): Model
    {
        return $this->getQuery()->where('code', $code)->firstOrFail();
    }
}