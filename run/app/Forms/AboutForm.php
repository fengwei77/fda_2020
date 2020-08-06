<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class AboutForm extends Form
{

    public function buildForm()
    {
        $this
            ->add('lang', Field::HIDDEN, [ 'value' => 0,'label' => '語系'])
            ->add('uuid', Field::HIDDEN)
            ->add('title', Field::HIDDEN, [
                'rules' => '',
                'label' => '標題'])
            ->add('description', Field::HIDDEN, [
                'rules' => '',
                'label' => '描述'])
            ->add('content', Field::TEXTAREA, [
                'rules' => '',
                'label' => '內容'])
            ->add('images', Field::HIDDEN)
            ->add('images_upload', Field::HIDDEN, [
                'label' => '圖片',
                'label_attr' => ['class' => 'images-label', 'for' => 'images'],
                'attr' => [
                    'class' => 'form-control file_wrap col-4',
                    'id' => 'images_upload'
                ],
//                'help_block' => [
//                    'text' => 'progress',
//                    'tag' => 'p',
//                    'attr' => ['class' => 'image_progress progress progress-xxs col-4 nopadding']
//                ]
            ])
            ->add('files', Field::HIDDEN)
            ->add('item_date', Field::HIDDEN, [
                'label' => '日期',
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
            ->add('hot', Field::HIDDEN, [
                'wrapper' => ['class' => 'form-group'],
                'value' => 1,
                'checked' => false,
                'label' => '熱門',
                'id' => 'hot',
                'attr' => [
                    'data-toggle' => 'toggle',
                    'data-on' => '啟用',
                    'data-off' => '關閉',
                    'data-size' => 'xs',
                    'data-onstyle' => 'success',
                    'data-offstyle' => 'secondary',
                ]

            ])
            ->add('item_status', Field::HIDDEN, [
                'wrapper' => ['class' => 'form-group'],
                'value' => 1,
                'checked' => true,
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
