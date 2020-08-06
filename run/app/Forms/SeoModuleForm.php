<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class SeoModuleForm extends Form
{

    public function buildForm()
    {
        $this
            ->add('lang', Field::HIDDEN, [ 'value' => 0,'label' => '語系'])
            ->add('property_name', Field::TEXT, [
                'rules' => 'required',
                'label' => '屬性名稱'])
            ->add('property_value', Field::TEXT, [
                'rules' => 'required',
                'label' => '屬性內容'])
            ->add('content', Field::TEXT, ['label' => '內容'])
            ->add('item_status', Field::CHECKBOX, [
                'wrapper' => ['class' => 'form-group'],
                'value' => 1,
                'checked' => false,
                'label' => '啟用狀態',
                'id' => 'item_status',
                'attr' => [
                    'data-toggle' => 'toggle',
                    'data-on' => '啟用',
                    'data-off' => '關閉',
                    'data-size' => 'xs',
                    'data-onstyle' => 'success',
                    'data-offstyle' => 'secondary',
                ]

            ])
            ->add('submit',
                Field::BUTTON_SUBMIT, [
                    'label' => '送出',
                    'attr' => ['class' => 'btn btn-primary']
                ]
            );
    }
}
