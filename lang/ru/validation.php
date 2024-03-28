<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute должен быть принят.',
    'active_url' => ':attribute не является допустимым URL.',
    'after' => ':attribute должен быть датой после :date.',
    'after_or_equal' => ':attribute должен быть датой после или равной :date.',
    'alpha' => ':attribute может содержать только буквы.',
    'alpha_dash' => ':attribute может содержать только буквы, цифры, дефисы и подчеркивания.',
    'alpha_num' => ':attribute может содержать только буквы и цифры.',
    'array' => ':attribute должен быть массивом.',
    'before' => ':attribute должен быть датой до :date.',
    'before_or_equal' => ':attribute должен быть датой до или равной :date.',
    'between' => [
        'numeric' => ':attribute должен быть между :min и :max.',
        'file' => ':attribute должен быть между :min и :max килобайтами.',
        'string' => ':attribute должен содержать от :min до :max символов.',
        'array' => ':attribute должен содержать от :min до :max пунктов.',
    ],
    'boolean' => 'Поле :attribute должно быть истинным или ложным.',
    'confirmed' => 'Подтверждение :attribute не совпадает',
    'date' => ':attribute не является допустимой датой.',
    'date_equals' => ':attribute должен быть датой, равной :date.',
    'date_format' => ':attribute не соответствует формату :format.',
    'different' => ':attribute и :other должны быть разнымиразными.',
    'digits' => ':attribute должен содержать :digits цифр.',
    'digits_between' => ':attribute должен содержать от :min до :max цифр.',
    'dimensions' => ':attribute имеет недопустимые размеры изображения.',
    'distinct' => 'Поле :attribute имеет повторяющееся значение.',
    'email' => ':attribute должен быть действующим адресом электронной почты.',
    'ends_with' => ':attribute должен заканчиваться одним из following: :values.',
    'exists' => 'Выбранный :attribute недействителен.',
    'file' => ':attribute должен быть файлом.',
    'filled' => 'Поле :attribute должно иметь значение.',
    'gt' => [
        'numeric' => ':attribute должен быть больше, чем :value.',
        'file' => ':attribute должен быть больше, чем :value килобайт.',
        'string' => ':attribute должен быть больше, чем :value символов.',
        'array' => ':attribute должен иметь больше :value пунктов.',
    ],
    'gte' => [
        'numeric' => ':attribute должен быть больше или равен :value.',
        'file' => ':attribute должен быть больше или равен :value килобайтов.',
        'string' => ':attribute должен быть больше или равен :value символам.',
        'array' => ':attribute должен иметь :value пунктов или больше.',
    ],
    'image' => ':attribute должен быть изображением.',
    'in' => 'Выбранный :attribute недействителен.',
    'in_array' => 'Поле :attribute не существует в :other.',
    'integer' => ':attribute должен быть целым числом.',
    'ip' => ':attribute должен быть действительным IP-адресом.',
    'ipv4' => ':attribute должен быть действительным IPv4-адресом.',
    'ipv6' => ':attribute должен быть действительным IPv6-адресом.',
    'json' => ':attribute должен быть допустимой строкой JSON.',
    'lt' => [
        'numeric' => ':attribute должен быть меньше :value.',
        'file' => ':attribute должен быть меньше :value килобайт.',
        'string' => ':attribute должен быть меньше чем :value символов.',
        'array' => 'The :attribute должен содержать меньше :value пунктов.',
    ],
    'lte' => [
        'numeric' => ':attribute должен быть меньше или равен :value.',
        'file' => ':attribute должен быть меньше или равен :value килобайт.',
        'string' => ':attribute должен быть меньше или равен :value символов.',
        'array' => ':attribute не может содержать более :value пунктов.',
    ],
    'max' => [
        'numeric' => ':attribute не может быть больше, чем :max.',
        'file' => ':attribute не может быть больше, чем :max килобайт.',
        'string' => ':attribute не может быть больше, чем :max символов.',
        'array' => ':attribute не может содержать более :max пунктов.',
    ],
    'mimes' => ':attribute должен быть файлом type: :values.',
    'mimetypes' => ':attribute должен быть файлом type: :values.',
    'min' => [
        'numeric' => ':attribute должен быть не меньше :min.',
        'file' => ':attribute должен быть не меньше :min килобайтов.',
        'string' => ':attribute должен содержать не менее :min символов.',
        'array' => ':attribute должен содержать не менее :min пунктов.',
    ],
    'not_in' => 'Выбранный :attribute недействителен.',
    'not_regex' => 'Недопустимый формат :attribute.',
    'numeric' => ':attribute должен быть цифрой.',
    'password' => 'Неверный пароль.',
    'present' => 'Поле :attribute должно присутствовать.',
    'regex' => 'Недействительный формат :attribute.',
    'required' => 'Поле :attribute обязательно для заполнения.',
    'required_if' => 'Поле :attribute обязательно, когда :other равен :value.',
    'required_unless' => 'Поле :attribute обязательно если :other не равен :values.',
    'required_with' => 'Поле :attribute обязательно, когда присутствует :values.',
    'required_with_all' => 'Поле :attribute обязательно, когда всюду присутствуют :values.',
    'required_without' => 'Поле :attribute обязательно, когда :values отсутствует.',
    'required_without_all' => 'Поле :attribute обязательно, если ни один из :values не присутствует.',
    'same' => ':attribute и :other должны совпадать.',
    'size' => [
        'numeric' => ':attribute должен быть :size.',
        'file' => ':attribute должен быть :size килобайт.',
        'string' => ':attribute должен содержать :size символов.',
        'array' => ':attribute должен содержать :size пунктов.',
    ],
    'starts_with' => ':attribute должен начинаться с одного из following: :values.',
    'string' => ':attribute должен быть a строкой.',
    'timezone' => ':attribute должен содержать правильный часовой пояс.',
    'unique' => ':attribute уже используется.',
    'uploaded' => ':attribute не удалось загрузить.',
    'url' => ':attribute имеет неправильный формат.',
    'uuid' => ':attribute должен быть действительным UUID.',
    'result_search_dont_matched' => 'Результат поиска отсутствует.',


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],


    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        "email" => "Эл. почта"
    ]
];
