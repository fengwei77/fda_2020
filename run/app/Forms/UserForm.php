<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class UserForm extends Form
{

    public function buildForm()
    {
        $this
            ->add('name', Field::TEXT, ['label' => '名字', 'rules' => 'required'])
            ->add('account', Field::TEXT, ['label' => '帳號', 'rules' => 'required'])
            ->add('password', Field::TEXT, [
                'label' => '密碼',
                'attr' => ['class' => 'form-control bg-light'],
                'rules' => 'required'])
            ->add('email', Field::HIDDEN)
            ->add('item_status', Field::CHECKBOX, [
                'wrapper' => ['class' => 'form-group'],
                'value' => 1,
                'checked' => false,
                'label' => '狀態',
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
