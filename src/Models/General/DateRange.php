<?php
namespace App\Models\General;

class DateRange
{
    public $datePicker;
    public $datePickerISO;
    /** @var DateTime $datePickerDate  */
    public $datePickerDate;

    public function __construct($from = null, $to = null, $format = 'd/m/Y', $separator = ' - ')
    {
        if (!$from && !$to) {return;}
        if ($to && $from > $to) {return;}
        $this->datePickerDate = $from ?: $to;
        $this->datePicker =
        !$to ? $from->format($format) :
        ($from ? $from->format($format) . $separator . $to->format($format) :
            $to->format($format));
        $this->datePickerISO = !$to ? $from->format('Y-m-d') :
        ($from ? $from->format('Y-m-d') . $separator . $to->format('Y-m-d') :
            $to->format('Y-m-d'));
    }
}