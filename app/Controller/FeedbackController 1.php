<?php

class FeedbackController extends AppController{
    public $helpers = array('Html', 'Form', 'myHtml', 'myChart');
    public $uses = array('Feedback');

    public function index(){
        $feedbacks = $this->Feedback->find('all');
        $this->set('feedbacks', $feedbacks);
    }

    public function add(){
        if($this->request->is('post')){
            $this->Feedback->create();
            $this->Feedback->set($this->request->data);
            if($this->Feedback->save()){
                $this->Session->setFlash(__('Feedback has been saved'), 'myFlashSuccess');
                return $this->redirect(array('action' => 'index'));
            }
            else{
                $this->Session->setFlash(__('Unable to add the Feedback'), 'myFlashError');
            }
        }

        $this->set('months', $this->Feedback->months);
    }

    public function edit($id = null){
        if( !$id ){
            throw new NotFoundException(__('Invalid Feedback'));
        }

        $feedback = $this->Feedback->findById($id);
        if(!$feedback){
            throw new NotFoundException(__('Invalid Feedback'));
        }

        if($this->request->is(array('post', 'put'))){
            $this->Feedback->id = $id;
            $this->Feedback->set($this->request->data);
            if($this->Feedback->save()){
                $this->Session->setFlash(__('Feedback has been saved'), 'myFlashSuccess');
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to update the Feedback'), 'myFlashError');
        }

        if (!$this->request->data) {
            $this->request->data = $feedback;
        }

        $this->set('months', $this->Feedback->months);
    }
}

?>