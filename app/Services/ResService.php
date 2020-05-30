<?php
namespace App\Services;

class ResService{
    //在对象中调用一个不可访问方法时(包括私有方法)，在这里被调用
    public function __call($method, $parameters)
    {
        return $this->$method(...$parameters);
    }

    //访问一个不存在的静态方法时
    public static function __callStatic($method, $parameters) {
        //注意这里，通过延迟静态绑定，仍然new了一个实例
        return (new static)->$method(...$parameters);
    }

    private $msg= '';
    private $data= [];
    private $errno = 0;

    private function res(){
        return response()->json([
            'errno' => $this->errno,
            'msg' => $this->msg,
            'data' => $this->data,
        ]);
    }

    private function success($errno=0){
        $this->errno = $errno;
        return $this->res();
    }

    private function fail($errno=1){
        $this->errno = $errno;
        return $this->res();
    }

    private function msg($msg= ''){
        $this->msg = $msg;
        return $this;
    }

    private function data($data){
        $this->data = $data;
        return $this;
    }
}
