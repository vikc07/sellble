<?php
class Feedback extends AppModel{
    public $months = array(
        '',
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'May',
        'Jun',
        'Jul',
        'Aug',
        'Sep',
        'Oct',
        'Nov',
        'Dec'
    );

    public $order = 'year, month';

    public $validate = array(
        'year' => array(
            'rule' => array('range', 1999, 2100),
            'message' => 'Invalid year'
        ),
        'month' => array(
            'rule' => array('range', 0, 13),
            'message' => 'Invalid month'
        )
    );

    public function afterFind($feedbacks, $useless = false){
        $previous = 0;

        foreach($feedbacks as $key => $feedback){
            $feedbacks[$key]['Feedback']['month'] = $this->months[$feedback['Feedback']['month']];
            $feedbacks[$key]['Feedback']['change'] = $feedback['Feedback']['score'] - $previous;
            $feedbacks[$key]['Feedback']['change_percent'] = ($previous) ? $feedbacks[$key]['Feedback']['change']*100 / $previous : '';

            $data1[] = array(
                $feedbacks[$key]['Feedback']['month'] . " " .
                $feedback['Feedback']['year'],
                $feedbacks[$key]['Feedback']['change']
            );

            $data2[] = array(
                $feedbacks[$key]['Feedback']['month'] . " " .
                $feedback['Feedback']['year'],
                (int)$feedbacks[$key]['Feedback']['score']
            );

            $previous = $feedback['Feedback']['score'];
        }

        return $feedbacks;
    }
}