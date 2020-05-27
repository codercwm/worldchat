<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * validate验证失败模板
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $message = '';
        foreach (json_decode(json_encode($validator->errors()),1) as $error){
            $message = $error[0];
            break;
        }
        throw (new HttpResponseException(response()->json([
            'code' => 400,
            'msg'  => $message,
            'data' => []
        ])));
    }

    public function messages()
    {
        return [
            'accepted' => ':attribute必须接受',
            'active_url' => ':attribute必须是一个有效的 URL',
            'after' => ':attribute 必须是 :date 之后的一个日期',
            'after_or_equal' => ':attribute 必须是 :date 之后或相同的一个日期',
            'alpha' => ':attribute只能包含字母',
            'alpha_dash' => ':attribute只能包含字母、数字、中划线或下划线',
            'alpha_num' => ':attribute只能包含字母和数字',
            'array' => ':attribute必须是一个数组',
            'before' => ':attribute 必须是 :date 之前的一个日期',
            'before_or_equal' => ':attribute 必须是 :date 之前或相同的一个日期',
            'between' => [
                'numeric' => ':attribute 必须在 :min 到 :max 之间',
                'file' => ':attribute 必须在 :min 到 :max KB 之间',
                'string' => ':attribute 必须在 :min 到 :max 个字符之间',
                'array' => ':attribute 必须在 :min 到 :max 项之间',
            ],
            'boolean' => ':attribute 字符必须是 true 或 false',
            'confirmed' => ':attribute 二次确认不匹配',
            'date' => ':attribute 必须是一个有效的日期',
            'date_format' => ':attribute 与给定的格式 :format 不符合',
            'different' => ':attribute 必须不同于 :other',
            'digits' => ':attribute必须是 :digits 位.',
            'digits_between' => ':attribute 必须在 :min 和 :max 位之间',
            'dimensions' => ':attribute具有无效的图片尺寸',
            'distinct' => ':attribute字段具有重复值',
            'email' => ':attribute必须是一个有效的电子邮件地址',
            'exists' => '选定的 :attribute 是无效的.',
            'file' => ':attribute必须是一个文件',
            'filled' => ':attribute的字段是必填的',
            'image' => ':attribute必须是 jpeg, png, bmp 或者 gif 格式的图片',
            'in' => '选定的 :attribute 是无效的',
            'in_array' => ':attribute 字段不存在于 :other',
            'integer' => ':attribute 必须是个整数',
            'ip' => ':attribute必须是一个有效的 IP 地址。',
            'json' => ':attribute必须是一个有效的 JSON 字符串',
            'max' => [
                'numeric' => ':attribute 的最大长度为 :max 位',
                'file' => ':attribute 的最大为 :max',
                'string' => ':attribute 的最大长度为 :max 字符',
                'array' => ':attribute 的最大个数为 :max 个.',
            ],
            'mimes' => ':attribute 的文件类型必须是 :values',
            'min' => [
                'numeric' => ':attribute 的最小长度为 :min 位',
                'file' => ':attribute 大小至少为 :min KB',
                'string' => ':attribute 的最小长度为 :min 字符',
                'array' => ':attribute 至少有 :min 项',
            ],
            'not_in' => '选定的 :attribute 是无效的',
            'numeric' => ':attribute 必须是数字',
            'present' => ':attribute 字段必须存在',
            'regex' => ':attribute 格式是无效的',
            'required' => ':attribute 字段是必须的',
            'required_if' => ':attribute 字段是必须的当 :other 是 :value',
            'required_unless' => ':attribute 字段是必须的，除非 :other 是在 :values 中',
            'required_with' => ':attribute 字段是必须的当 :values 是存在的',
            'required_with_all' => ':attribute 字段是必须的当 :values 是存在的',
            'required_without' => ':attribute 字段是必须的当 :values 是不存在的',
            'required_without_all' => ':attribute 字段是必须的当 没有一个 :values 是存在的',
            'same' => ':attribute和:other必须匹配',
            'size' => [
                'numeric' => ':attribute 必须是 :size 位',
                'file' => ':attribute 必须是 :size KB',
                'string' => ':attribute 必须是 :size 个字符',
                'array' => ':attribute 必须包括 :size 项',
            ],
            'string' => ':attribute 必须是一个字符串',
            'timezone' => ':attribute 必须是个有效的时区.',
            'unique' => ':attribute 已存在',
            'url' => ':attribute 无效的格式',
        ];
    }

    public function attributes()
    {
        return [
            'email' => '邮箱',
            'nickname' => '昵称',
            'name' => '姓名',
            'password' => '密码',
        ];
    }
}
