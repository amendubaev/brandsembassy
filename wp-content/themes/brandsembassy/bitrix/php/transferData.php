<?php 
class transferData {
    public  $result = null;

    /* Принимает значения куда отправить и что отправить */
    public function curlStart($queryUrl, $Data){
        
        $Data = http_build_query($Data);
        $curl = curl_init();

        curl_setopt_array($curl, 
            array(  CURLOPT_SSL_VERIFYPEER => 0, 
                    CURLOPT_POST           => 1, 
                    CURLOPT_HEADER         => 0, 
                    CURLOPT_RETURNTRANSFER => 1, 
                    CURLOPT_URL            => $queryUrl, 
                    CURLOPT_POSTFIELDS     => $Data, 
                    )
        ); 

        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result, 1);

        return $result;
    }

    // пишем логи
    function writeToLog($data, $title = '') { 
        $date  =  date("m.d.y");
      $log =  getcwd() . '/log_'. $date .'.log';
      $log = file_get_contents($log);
   
      if (strlen($log) > 10000000) {
          $log = substr($log, -500000);
      }
         $log .= "\n------------------------\n"; 
      $log .= date("Y.m.d G:i:s") . "\n"; 
      $log .= (strlen($title) > 0 ? $title : 'DEBUG') . "\n"; $log .= print_r($data, 1); 
      $log .= "\n------------------------\n"; 
      file_put_contents(getcwd() . '/log_'. $date .'.log', $log);
    }
}
?>