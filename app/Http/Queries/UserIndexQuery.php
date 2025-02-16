<?php

namespace App\Http\Queries;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserIndexQuery extends QueryBuilder
{
    public function __construct(Request $request)
    {
        parent::__construct(User::withTrashed(), $request);

        $this
            ->allowedFilters([
                $this->globalSearch(),
                AllowedFilter::trashed(),
                'name',
                $this->filterLanguageDeveloper(),
                'email_verified_at',
                $this->filterCreatedAt(),
            ])
            ->allowedSorts([
                'name',
                'email',
                'language_developer',
                'email_verified_at',
                'deleted_at',
                'created_at',
                'updated_at',
            ]);
    }

    public function filterLanguageDeveloper(): AllowedFilter
    {
        return AllowedFilter::callback('language_developer', function ($query, $value) {
            $query->whereIn('language_developer', $value);
        });
    }

    public function filterCreatedAt(): AllowedFilter
    {
        return AllowedFilter::callback('created_at', function ($query, $value) {
            $value = Str::of($value)->explode(' to ');

            $query->whereDate('created_at', '>=', $value->first())
                ->whereDate('created_at', '<=', $value->last());
        });
    }

    public function globalSearch(): AllowedFilter
    {
        return AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query
                    ->where('email', 'LIKE', "%{$value}%");
            });
        });
    }

    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $paginator = parent::paginate($perPage, $columns, $pageName, $page);

        $paginator->appends(request()->query());

        return $paginator;
    }
}
