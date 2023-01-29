var this_host = window.location.protocol + "//" + window.location.hostname;

//для хранения состояния чекбоксов всех форма
let checkbox_id = [];

//аналог explode в php
function explode( delimiter, string ) {
    var emptyArray = { 0: '' };
    if ( arguments.length != 2 || typeof arguments[0] == 'undefined' || typeof arguments[1] == 'undefined' ) {
        return null;
    }
    if ( delimiter === '' || delimiter === false || delimiter === null ) {
        return false;
    }
    if ( typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object' ) {
        return emptyArray;
    }
    if ( delimiter === true ) {
        delimiter = '1';
    }
    return string.toString().split ( delimiter.toString() );
}

//проверяем есть ли элемент в массиве
function contains(arr, elem) {
    for (var i = 0; i < arr.length; i++) {
        if (arr[i] === elem) {
            return true;
        }
    }
    return false;
}

//собираем все активные checkbox на странице
function get_array_checkbox() {
    let arr_out =[];
    let arr = $(".s_check");
    $.each(arr,function(index) {
        if ($(arr[index]).is(':checked')) {
            let id = $(arr[index]).attr('id');
            let pre = explode('_',id);
            arr_out.push(pre[1]);
        }
    });
    return arr_out;
}

//собираем все активные checkbox на странице
function get_array_checkbox_last() {
    let arr_out =[];
    let arr = $(".s_check");
    $.each(arr,function(index) {
        if ($(arr[index]).is(':checked')) {
            let id = $(arr[index]).attr('id');
            arr_out.push(id);
        }
    });
    return arr_out;
}

//Удалить элемент из массива
Array.prototype.remove = function(el) {
    return this.splice(this.indexOf(el), 1);
};

$(function () {
    //Сериализация из фомры FROM дабы ускорить процесс переброски данных
    $.fn.serializeObject = function(){
        var self = this,
            json = {},
            push_counters = {},
            patterns = {
                "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                "key":      /[a-zA-Z0-9_]+|(?=\[\])/g,
                "push":     /^$/,
                "fixed":    /^\d+$/,
                "named":    /^[a-zA-Z0-9_]+$/
            };
        this.build = function(base, key, value){
            base[key] = value;
            return base;
        };
        this.push_counter = function(key){
            if(push_counters[key] === undefined){
                push_counters[key] = 0;
            }
            return push_counters[key]++;
        };
        $.each($(this).serializeArray(), function(){

            // skip invalid keys
            if(!patterns.validate.test(this.name)){
                return;
            }

            var k,
                keys = this.name.match(patterns.key),
                merge = this.value,
                reverse_key = this.name;

            while((k = keys.pop()) !== undefined){

                // adjust reverse_key
                reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

                // push
                if(k.match(patterns.push)){
                    merge = self.build([], self.push_counter(reverse_key), merge);
                }

                // fixed
                else if(k.match(patterns.fixed)){
                    merge = self.build([], k, merge);
                }

                // named
                else if(k.match(patterns.named)){
                    merge = self.build({}, k, merge);
                }
            }

            json = $.extend(true, json, merge);
        });

        return json;
    };
});

$.fn.setCursorPosition = function(pos) {
    this.each(function(index, elem) {
        if (elem.setSelectionRange) {
            elem.setSelectionRange(pos, pos);
        } else if (elem.createTextRange) {
            var range = elem.createTextRange();
            range.collapse(true);
            range.moveEnd('character', pos);
            range.moveStart('character', pos);
            range.select();
        }
    });
    return this;
};