<?php

namespace common\modules\user\forms;

use common\modules\user\models\WorkingHour;
use yii\base\Model;

class WorkingHourFormAdmin extends Model
{

    public $user_id;
    public $WorkingHours;

    public function rules()
    {
        return [
            [['WorkingHours'], 'required'],
            ['WorkingHours', 'safe'],
            ['user_id', 'integer']
        ];
    }

    public function save(){
        $transaction = \Yii::$app->db->beginTransaction();

        foreach ($this->WorkingHours as $hour){
            $workingHour = WorkingHour::findOne(['user_id' => $this->user_id, 'day' => $hour['day']]);
            if (!$workingHour){
                $workingHour = new WorkingHour();
            }
            $workingHour->day = $hour['day'];
            $workingHour->start_at = strtotime($hour['start_at']);
            $workingHour->end_at = strtotime($hour['end_at']);
            $workingHour->user_id = $this->user_id;
            $workingHour->type = $hour['type'];

            if (!$workingHour->save()){
                $transaction->rollBack();
                return false;
            }
        }

        $transaction->commit();
        return true;
    }

    public function getModel(){

        $times = WorkingHour::find()->where(['user_id' => $this->user_id])->asArray()->all();

        $this->WorkingHours[WorkingHour::MONDAY]['start_at'] = date('H:i',$times[0]['start_at']);
        $this->WorkingHours[WorkingHour::MONDAY]['end_at'] = date('H:i',$times[0]['end_at']);
        $this->WorkingHours[WorkingHour::MONDAY]['type'] = $times[0]['type'];

        $this->WorkingHours[WorkingHour::TUESDAY]['start_at'] = date('H:i',$times[1]['start_at']);
        $this->WorkingHours[WorkingHour::TUESDAY]['end_at'] = date('H:i',$times[1]['end_at']);
        $this->WorkingHours[WorkingHour::TUESDAY]['type'] = $times[1]['type'];

        $this->WorkingHours[WorkingHour::WEDNESDAY]['start_at'] = date('H:i',$times[2]['start_at']);
        $this->WorkingHours[WorkingHour::WEDNESDAY]['end_at'] = date('H:i',$times[2]['end_at']);
        $this->WorkingHours[WorkingHour::WEDNESDAY]['type'] = $times[2]['type'];

        $this->WorkingHours[WorkingHour::THURSDAY]['start_at'] = date('H:i',$times[3]['start_at']);
        $this->WorkingHours[WorkingHour::THURSDAY]['end_at'] = date('H:i',$times[3]['end_at']);
        $this->WorkingHours[WorkingHour::THURSDAY]['type'] = $times[3]['type'];

        $this->WorkingHours[WorkingHour::FRIDAY]['start_at'] = date('H:i',$times[4]['start_at']);
        $this->WorkingHours[WorkingHour::FRIDAY]['end_at'] = date('H:i',$times[4]['end_at']);
        $this->WorkingHours[WorkingHour::FRIDAY]['type'] = $times[4]['type'];

        $this->WorkingHours[WorkingHour::SATURDAY]['start_at'] = date('H:i',$times[5]['start_at']);
        $this->WorkingHours[WorkingHour::SATURDAY]['end_at'] = date('H:i',$times[5]['end_at']);
        $this->WorkingHours[WorkingHour::SATURDAY]['type'] = $times[5]['type'];

        $this->WorkingHours[WorkingHour::SUNDAY]['start_at'] = date('H:i',$times[6]['start_at']);
        $this->WorkingHours[WorkingHour::SUNDAY]['end_at'] = date('H:i',$times[6]['end_at']);
        $this->WorkingHours[WorkingHour::SUNDAY]['type'] = $times[6]['type'];

        return $this;
    }

}