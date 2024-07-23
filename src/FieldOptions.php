<?php

namespace Juniora\Nova\Fields;

use Carbon\Carbon;
use Carbon\CarbonInterval;

trait FieldOptions
{
	/**
     * The format value that can be assigned to the field.
     *
     * @var string|null
     */
    public $format;

	/**
     * The type value that can be assigned to the field.
     *
     * @var string|null
     */
    public $type;

	/**
     * The color value that can be assigned to the field.
     *
     * @var string|null
     */
    public $color;

	/**
     * The editable value that can be assigned to the field.
     *
     * @var boolean
     */
    public $editable = true;

	/**
     * The humanize value that can be assigned to the field.
     *
     * @var boolean
     */
    public $humanize = false;

	/**
     * The formats value that can be assigned to the field.
     *
     * @var array|null
     */
    public $formats;

    /**
     * The minimum value that can be assigned to the field.
     *
     * @var string|null
     */
    public $min;

    /**
     * The maximum value that can be assigned to the field.
     *
     * @var string|null
     */
    public $max;

    /**
     * The step size the field will increment and decrement by.
     *
     * @var string|int|null
     */
    public $step;

	/**
     * format based on jalali-moment. See https://github.com/jalaali/moment-jalaali
     *
     * @param  string  $format
     * @return PersianDateTime
     */
    public function format($format)
    {
        $this->format = $format;

		return $this;
    }

	/**
     * type ['datetime', 'date', 'time']
     *
     * @param  string  datepicker type
     * @return PersianDateTime
     */
    public function type($type)
    {
        $this->type = $type;

		return $this;
    }

	/**
     * Set datePicker Color. See https://talkhabi.github.io/vue-persian-datetime-picker/#/theme
     *
     * @param $color
     * @return PersianDateTime
     */
    public function color($color)
    {
        $this->color = $color;

		return $this;
    }

	/**
     * Set Editable
     */
    public function editable()
    {
        $this->editable = true;

		return $this;
    }

    /**
     * Set Humanize Function for MOMENT.
     *
     * @return PersianDateTime
     */
    public function humanize()
    {
        $this->humanize = true;

		return $this;
    }

    /**
     * format based on jalali-moment. See https://github.com/jalaali/moment-jalaali
     *
     * @param  array  $formats
     * @return PersianDateTime
     */
    public function formats($formats)
    {
        $this->formats = $formats;

		return $this;
    }

    /**
     * The minimum value that can be assigned to the field.
     *
     * @param  \Carbon\CarbonInterface|string  $min
     * @return $this
     */
    public function min($min)
    {
        if (is_string($min)) {
            $min = Carbon::parse($min);
        }

        $this->min = $min->toDateString();

        return $this;
    }

    /**
     * The maximum value that can be assigned to the field.
     *
     * @param  \Carbon\CarbonInterface|string  $max
     * @return $this
     */
    public function max($max)
    {
        if (is_string($max)) {
            $max = Carbon::parse($max);
        }

        $this->max = $max->toDateString();

        return $this;
    }

    /**
     * The step size the field will increment and decrement by.
     *
     * @param  string|int|\Carbon\CarbonInterval  $step
     * @return $this
     */
    public function step($step)
    {
        $this->step = $step instanceof CarbonInterval ? $step->totalDays : $step;

        return $this;
    }
}
