<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class PrizeForm extends Form
{

    public function buildForm()
    {
        $this
            ->add('uuid', Field::HIDDEN)
            ->add('event_id', Field::HIDDEN)
            ->add('images', Field::HIDDEN)
            ->add('lottery_list', Field::CHOICE, [
                'label' => '抽獎資料來源',
                'choices' => ['0' => '遊戲玩家', '1' => '發票登錄'],
                'choice_options' => [
                    'wrapper' => ['class' => 'choice-wrapper'],
                    'label_attr' => ['class' => 'label-class'],
                ],
                'attr' => [
                    'class' => 'form-control file_wrap col-2'
                ],
                'selected' => ['invoice'],
                'expanded' => false,
                'multiple' => false
            ])
            ->add('images_upload', Field::FILE, [
                'label' => '圖片',
                'label_attr' => ['class' => 'images-label', 'for' => 'prize_image'],
                'attr' => [
                    'class' => 'form-control file_wrap col-4',
                    'id' => 'images_upload'
                ],
                'help_block' => [
                    'text' => 'progress',
                    'tag' => 'p',
                    'attr' => ['class' => 'image_progress progress progress-xxs col-4 nopadding']
                ]
            ])
            ->add('name', Field::TEXT, [
                'rules' => 'required',
                'label' => '獎項名稱'])
            ->add('desc', Field::TEXT, ['label' => '描述'])
            ->add('qty', Field::NUMBER, [
                'attr' => ['class' => 'form-control text-center', 'min' => '0', 'max' => '10000', 'data-decimals' => '0'],
                'rules' => 'required|numeric',
                'wrapper' => ['class' => 'form-group far'],
                'label' => '獎項數量',
                'value' => '0',
                'error_messages' => ['請輸入數字']
            ])
            ->add('usage_sum', Field::TEXT, [
                'attr' => ['disabled', 'class' => 'form-control text-center', 'min' => '0', 'max' => '10000', 'data-decimals' => '0'],
                'wrapper' => ['class' => 'form-group far'],
                'value' => '0',
                'label' => '已抽到數量(不可修改)'])
            ->add('day_limit', Field::HIDDEN, [
                'attr' => ['class' => 'form-control text-center', 'min' => '0', 'max' => '10000', 'data-decimals' => '0'],
                'rules' => 'required|numeric',
                'wrapper' => ['class' => 'form-group far'],
                'value' => '0',
                'label' => '一天可抽幾個(0為不限制)'])
            ->add('pbb', Field::HIDDEN, [
                'attr' => ['class' => 'form-control text-center bg-teal col-2', 'min' => '0', 'max' => '100', 'data-decimals' => '0'],
                'rules' => 'required|numeric',
                'value' => '0',
                'label' => '機率(填入1~100整數)'])
            ->add('start_date', Field::TEXT, [
                'label' => '開始日期',
                'wrapper' => ['class' => 'form-group far'],
                'attr' => [
                    'class' => 'form-control  bg-warning',
                    'placeholder' => '請選擇日期..'
                ],
                'help_block' => [
                    'text' => null,
                    'tag' => 'i',
                    'attr' => ['class' => 'help-block']
                ]
            ])
            ->add('expire_date', Field::TEXT, [
                'label' => '結束日期',
                'wrapper' => ['class' => 'form-group far'],
                'attr' => [
                    'class' => 'form-control bg-warning',
                    'placeholder' => '請選擇日期..'
                ],
                'help_block' => [
                    'text' => null,
                    'tag' => 'i',
                    'attr' => ['class' => 'help-block']
                ]
            ])
            ->add('prize_type', Field::CHECKBOX, [
                'wrapper' => ['class' => 'form-group'],
                'value' => 1,
                'checked' => false,
                'label' => '前台即時抽獎',
                'id' => 'item_status',
                'attr' => [
                    'data-toggle' => 'toggle',
                    'data-on' => '啟用',
                    'data-off' => '關閉',
                    'data-size' => 'xs',
                    'data-onstyle' => 'info',
                    'data-offstyle' => 'secondary',
                ]

            ])
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
            ->add('submit', Field::BUTTON_SUBMIT, ['label' => '送出']);
    }
}
