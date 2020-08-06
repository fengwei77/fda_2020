<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\Field;

class ReplyForm extends Form
{

    public function buildForm()
    {
        $this
            ->add('cid', Field::HIDDEN, [
                'label' => 'cid'])
            ->add('qid', Field::HIDDEN, [
                'label' => 'qid'])
            ->add('title', Field::HIDDEN, [
                'label' => '電子報標題'])
            ->add('subject', Field::HIDDEN, [
                'label' => '郵件主旨'])
            ->add('content', Field::TEXT, ['label' => ' '])
            ->add('submit',
                Field::BUTTON_SUBMIT, [
                    'label' => '送出',
                    'attr' => ['class' => 'btn btn-primary']
                ]
            );
    }
}
