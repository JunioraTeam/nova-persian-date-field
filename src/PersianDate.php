<?php

namespace Juniora\Nova\Fields;

use Carbon\CarbonInterface;
use DateTimeInterface;
use Exception;
use Illuminate\Support\Arr;
use Laravel\Nova\Fields\Filters\DateFilter;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Contracts\FilterableField;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\FieldFilterable;
use Laravel\Nova\Fields\SupportsDependentFields;

class PersianDate extends Field implements FilterableField
{
    use FieldFilterable, SupportsDependentFields;
    use FieldOptions;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'persian-date';

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|\Closure|callable|object|null  $attribute
     * @param  (callable(mixed, mixed, ?string):mixed)|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback ?? function ($value) {
            if (! is_null($value)) {
                if ($value instanceof DateTimeInterface) {
                    return $value->format('Y-m-d');
                }

                throw new Exception("Date field must cast to 'date' in Eloquent model.");
            }
        });
    }

    /**
     * Resolve the default value for the field.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function resolveDefaultValue(NovaRequest $request): mixed
    {
        $value = parent::resolveDefaultValue($request);

        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        return $value;
    }

    /**
     * Define the default filterable callback.
     *
     * @return callable(\Laravel\Nova\Http\Requests\NovaRequest, \Illuminate\Database\Eloquent\Builder, mixed, string):\Illuminate\Database\Eloquent\Builder
     */
    protected function defaultFilterableCallback()
    {
        return function (NovaRequest $request, $query, $value, $attribute) {
            [$min, $max] = $value;

            if (! is_null($min) && ! is_null($max)) {
                return $query->whereDate($attribute, '>=', $min)
                             ->whereDate($attribute, '<=', $max);
            } elseif (! is_null($min)) {
                return $query->whereDate($attribute, '>=', $min);
            }

            return $query->whereDate($attribute, '<=', $max);
        };
    }

    /**
     * Make the field filter.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return \Laravel\Nova\Fields\Filters\Filter
     */
    protected function makeFilter(NovaRequest $request)
    {
        return new DateFilter($this);
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array
     */
    public function serializeForFilter(): array
    {
        return transform($this->jsonSerialize(), function ($field) {
            return Arr::only($field, [
                'uniqueKey',
                'name',
                'attribute',
                'type',
                'placeholder',
                'extraAttributes',
            ]);
        });
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), array_filter([
            'min' => $this->min,
            'max' => $this->max,
            'step' => $this->step ?? 'any',
            'format' => $this->format,
            'formats' => $this->formats,
            'type' => $this->type,
            'color' => $this->color,
            'editable' => $this->editable,
            'humanize' => $this->humanize,
        ]));
    }
}
