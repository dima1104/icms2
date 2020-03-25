<?php
class actionAuthgaLogin2fa extends cmsAction {

    public $lock_explicit_call = true;

    public function run($logged_user, $form, $data){

        $this->cms_template->setContext($this);

        $form_confirm = $this->getForm('confirm', [$logged_user]);

        $form_confirm->addField('basic', new fieldHidden('submit_confirm', array(
                'default' => 1
            ))
        );

        foreach ($data as $key => $value) {
            $form_confirm->addField('basic', new fieldHidden($key, array(
                    'default' => $value
                ))
            );
        }

        if ($this->request->has('submit_confirm')){

            $data = $form_confirm->parse($this->request, true);

            $errors = $form_confirm->validate($this,  $data);

            if (!$errors){
                return true;
            }

        }

        $tpl_params = [
            'data'   => $data,
            'form'   => $form_confirm,
            'errors' => isset($errors) ? $errors : false
        ];

        // Для вызовов из InstantCMS JSON API
        if ($this->request->has('api_context')){
            return $tpl_params;
        }

        return $this->cms_template->render('login_2fa', $tpl_params);

    }

}
