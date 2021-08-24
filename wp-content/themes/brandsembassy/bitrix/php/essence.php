<?php
require_once(__DIR__.'/transferData.php');

class essenceAdd extends transferData {
    /* 
        Этот класс для добавления/редактирования/обновления сущностей.
        По конструкту требует url вебхука
    */

    /* методы */
    const   methodAdd         = 'crm.lead.add.json'       ;
    const   methodUpdata      = 'crm.lead.update.json'    ;
    const   listUserfiledList = 'crm.lead.userfield.list' ;
    const   leadList          = 'crm.lead.list'           ;
    const   leadGet           = 'crm.lead.get'            ;

    const   contactAdd        = 'crm.contact.add'         ;
    const   contactList       = 'crm.contact.list'        ;
    const   contactDelete     = 'crm.contact.delete'      ;
    const   contactUpdate     = 'crm.contact.update'      ;
    const   contactGet        = 'crm.contact.get'         ;
    const   contactFields     = 'crm.contact.fields'      ;

    const   dealAdd           = 'crm.deal.add'            ;
    const   dealUpdata        = 'crm.deal.update'         ;
    const   dealList          = 'crm.deal.list'           ;
    const   dealGet           = 'crm.deal.get'            ;
    const   dealDelete        = 'crm.deal.delete'         ;
    const   dealcategoryStage = 'crm.dealcategory.stage.list';
    
    /* переменные для обращения CURL_url */
    public  $add              = null; 
    public  $upData           = null; 
    public  $userFieldList    = null; 
    public  $leadGet          = null; 
    public  $contactAdd       = null; 
    public  $contactList      = null;  
    public  $contactGet       = null;  
    public  $contactFields       = null;  
    public  $dealAdd          = null;  
    public  $dealUpdata       = null;  
    public  $dealList         = null;
    public  $contactUpdate    = null;  
    public  $dealGet          = null;  
    public  $dealcategoryStage          = null;


    public function __construct($url){
        $this->add              = $url . self::methodAdd         ;
        $this->upData           = $url . self::methodUpdata      ;
        $this->leadGet          = $url . self::leadGet           ;
        $this->leadList         = $url . self::leadList          ;

        $this->userFieldList    = $url . self::listUserfiledList ;
        
        $this->contactAdd       = $url . self::contactAdd        ;
        $this->contactList      = $url . self::contactList       ;
        $this->contactDelete    = $url . self::contactDelete     ;
        $this->contactUpdate    = $url . self::contactUpdate     ;
        $this->contactGet       = $url . self::contactGet        ;
        $this->contactFields       = $url . self::contactFields        ;

        $this->dealAdd          = $url . self::dealAdd           ;
        $this->dealUpdata       = $url . self::dealUpdata        ;
        $this->dealList         = $url . self::dealList          ;
        $this->dealGet          = $url . self::dealGet           ;
        $this->dealDelete       = $url . self::dealDelete        ;
        $this->dealcategoryStage       = $url . self::dealcategoryStage        ;
        


        
    }
    
    public function dealcategoryStage($id){
        $data = array(
            'id'    => $id,
        );
        return parent::curlStart($this->dealcategoryStage, $data);
    }

    public function leadGet($id){
        $data = array(
            'id'    => $id,
        );
        return parent::curlStart($this->leadGet, $data);
    }

    public function contactGet($id){
        $data = array(
            'id'    => $id,
        );
        return parent::curlStart($this->contactGet, $data);
    }

    public function contactFields($id){
        $data = array(
            'id'    => $id,
        );
        return parent::curlStart($this->contactFields, $data);
    }

    public function dealDelete($id){
        $data = array(
            'id'    => $id,
        );
        return parent::curlStart($this->dealDelete, $data);
    }

    public function contactDelete($id){
        $data = array(
            'id'    => $id,
        );
        return parent::curlStart($this->contactDelete, $data);
    }

    public function dealGet($id){
        $data = array(
            'id'    => $id,
        );
        return parent::curlStart($this->dealGet, $data);
    }

    public function leadList($data){
        return parent::curlStart($this->leadList, $data);
    }

    public function contactList($data){
        return parent::curlStart($this->contactList, $data);
    }

    public function contactAdd($data, $phone){
        $data = array(
            "fields" => $data,
            'params' => array("REGISTER_SONET_EVENT" => "Y")
        );
        if(is_numeric($phone)){
            $data['fields']['PHONE'] = array(array("VALUE" => $phone, "VALUE_TYPE" => "WORK"));
        }
        return parent::curlStart($this->contactAdd, $data);
    }

    public function add($data,$phone){
        $data = array(
            'fields' => $data,
            'params' => array("REGISTER_SONET_EVENT" => "Y")
        );

        if(is_numeric($phone)){
            $data['fields']['PHONE'] = array(array("VALUE" => $phone, "VALUE_TYPE" => "WORK"));
        }
        $result =  parent::curlStart($this->add, $data);

        if(!$result['result'] > 1){
            $result = FALSE;
        }
        return $result;
    }

    public function listUserfiled(){
        $data = array(
            'order'     => array( "SORT"        => "ASC" ),
            'filter'    => array( "MANDATORY"   => "N"   )
        );
        return  parent::curlStart($this->userFieldList, $data)  ;
    }

    public function leadUpdata($id, $arr){
        $data = array(
            'id'        =>  $id,
            'fields'    =>  $arr,
            'params'    =>  array("REGISTER_SONET_EVENT" => "Y")
        );
        return parent::curlStart($this->upData, $data);
    }

    public function dealUpdata($id, $arr){
        $data = array(
            'id'        =>  $id,
            'fields'    =>  $arr,
            'params'    =>  array("REGISTER_SONET_EVENT" => "Y")
        );
        return parent::curlStart($this->dealUpdata, $data);
    }

    public function dealList($data){
        return parent::curlStart($this->dealList, $data);
    }

    public function dealAdd($data, $phone){
        $data = array(
            'fields' => $data,
            'params' => array("REGISTER_SONET_EVENT" => "Y")
        );

        $result =  parent::curlStart($this->dealAdd, $data);

        if(!$result['result'] > 1){
            $result = FALSE;
        }
        return $result;
    }
    public function contactUpdate($id, $arr){
        $data = array(
            'id'        =>  $id,
            'fields'    =>  $arr,
            'params'    =>  array("REGISTER_SONET_EVENT" => "Y")
        );
        return parent::curlStart($this->contactUpdate, $data);
    }
}
?>