<?php  
namespace Model;
class Captcha extends ActiveRecord{

    public function getCaptcha($SecretKey){
        $respuesta=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LeFbVAcAAAAAFF6prsn7l-HZyXxRvjPbwLJ7KTj&response={$SecretKey}");
        $retorno=json_decode($respuesta);
       
        return $retorno;
    }
}
