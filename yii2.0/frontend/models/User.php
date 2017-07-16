<?php
/**
 * Created by PhpStorm.
 * User: zzx
 * Date: 2017/7/15
 * Time: 20:23
 */

namespace frontend\models;


use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\models\HttpRequest;

class User extends ActiveRecord implements IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $email;
    public $is_admin;
    public $token;
    public $state;
    public $updated_time;
    public $created_time;

    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->email = $data['email'];
        $this->is_admin = $data['is_admin'];
        $this->token = $data['token'];
        $this->state = $data['state'];
        $this->updated_time = $data['updated_time'];
        $this->created_time = $data['created_time'];
    }

    public static  function findAPI($info){
        /*echo 'users/'.$info;die();*/
        $data=HttpRequest::request('users/'.$info);
        $data=json_decode($data,true)['data'];
        $data=$data[0];
        $data=new User($data);
        return $data;
    }

    public static function findIdentity($id)
    {
        return self::findAPI($id);
    }

    public static function findIdentityByAccessToken($username,$type=null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($username){
        return self::findAPI($username);
    }

    public static function findByPasswordResetToken($username){
        return self::findAPI($username);
    }

    public static function isPasswordResetTokenValid($username)
    {
        return self::findAPI($username);
    }

    public function getId()
    {
        return $this->id;
    }

    public function validatePassword($password)
    {
        return md5($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = \Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public  function getAuthkey(){
        return $this->token;
    }

    public function validateAuthKey($token)
    {
        return $token;
    }

}