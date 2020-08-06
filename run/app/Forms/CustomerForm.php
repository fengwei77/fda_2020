<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class CustomerForm extends Form
{

    public function buildForm()
    {
        $this
            ->add('uuid', Field::HIDDEN)
            ->add('images', Field::HIDDEN)
            ->add('username', Field::TEXT, [
                'rules' => '',
                'label' => '暱稱'])
            ->add('email', Field::TEXT, [
                'rules' => '',
                'attr' => ['disabled' => 'disabled'],
                'label' => '信箱'])
            ->add('password', Field::TEXT, [
                'label' => '密碼',
                'attr' => ['class' => 'form-control bg-light'],
                'rules' => ''])
            ->add('roles', Field::TEXT, [
                'rules' => 'required',
                'label' => '權限'])
            ->add('reason', Field::HIDDEN)
            ->add('images_upload', Field::HIDDEN, [
                'label' => '圖片',
                'label_attr' => ['class' => 'images-label', 'for' => 'images'],
                'attr' => [
                    'class' => 'form-control file_wrap col-4',
                    'id' => 'images_upload'
                ],
                'help_block' => [
//                    'text' => 'progress',
//                    'tag' => 'p',
//                    'attr' => ['class' => 'image_progress progress progress-xxs col-4 nopadding']
                ]
            ])
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
            ->add('is_manager', Field::CHECKBOX, [
                'wrapper' => ['class' => 'form-group'],
                'value' => 1,
                'checked' => false,
                'label' => '是否創立者',
                'id' => 'item_status',
                'attr' => [
                    'data-toggle' => 'toggle',
                    'data-on' => '是',
                    'data-off' => '否',
                    'data-size' => 'xs',
                    'data-onstyle' => 'success',
                    'data-offstyle' => 'secondary',
                ]

            ])
            ->add('submit', Field::BUTTON_SUBMIT, ['label' => '送出']);
    }
}
