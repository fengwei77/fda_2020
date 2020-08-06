<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class NewsletterForm extends Form
{

    public function buildForm()
    {
        $this
            ->add('images', Field::HIDDEN)
            ->add('scheduled_time', Field::HIDDEN, [
                'label' => '寄送日期',
                'wrapper' => ['class' => 'form-group far'],
                'attr' => [
                    'class' => 'form-control  bg-light',
                    'placeholder' => '請選擇日期..'
                ],
                'help_block' => [
                    'text' => null,
                    'tag' => 'i',
                    'attr' => ['class' => 'help-block']
                ]
            ])
            ->add('title', Field::TEXT, [
                'rules' => 'required',
                'label' => '電子報標題'])
            ->add('subject', Field::TEXT, [
                'rules' => 'required',
                'label' => '郵件主旨'])
            ->add('content', Field::TEXT, ['label' => '郵件內容'])
            ->add('files', Field::HIDDEN)
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
