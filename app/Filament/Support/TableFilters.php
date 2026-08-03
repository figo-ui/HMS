<?php

namespace App\Filament\Support;

use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TableFilters
{
    public static function createdAt(string $column = 'created_at', string $label = 'Created Date'): Filter
    {
        return static::dateRange($column, $label);
    }

    public static function dateRange(string $column, string $label): Filter
    {
        return Filter::make($column)
            ->label($label)
            ->form([
                DatePicker::make('from')->label('From'),
                DatePicker::make('until')->label('Until'),
            ])
            ->query(function (Builder $query, array $data) use ($column): Builder {
                return $query
                    ->when(
                        $data['from'] ?? null,
                        fn (Builder $query, $date): Builder => $query->whereDate($column, '>=', $date),
                    )
                    ->when(
                        $data['until'] ?? null,
                        fn (Builder $query, $date): Builder => $query->whereDate($column, '<=', $date),
                    );
            });
    }

    public static function select(string $column, array $options, ?string $label = null): SelectFilter
    {
        $filter = SelectFilter::make($column)
            ->options($options)
            ->searchable()
            ->preload();

        if ($label !== null) {
            $filter->label($label);
        }

        return $filter;
    }

    public static function distinct(string $column, string $modelClass, ?string $label = null): SelectFilter
    {
        /** @var class-string<Model> $modelClass */
        $filter = SelectFilter::make($column)
            ->options(fn (): array => $modelClass::query()
                ->whereNotNull($column)
                ->distinct()
                ->orderBy($column)
                ->pluck($column, $column)
                ->all())
            ->searchable()
            ->preload();
 
        if ($label !== null) {
            $filter->label($label);
        }

        return $filter;
    }

    public static function relationship(
        string $name,
        string $titleAttribute,
        ?string $label = null,
        ?Closure $modifyQueryUsing = null,
    ): SelectFilter {
        $filter = SelectFilter::make($name)
            ->relationship($name, $titleAttribute, $modifyQueryUsing)
            ->searchable()
            ->preload();

        if ($label !== null) {
            $filter->label($label);
        }

        return $filter;
    }
}
