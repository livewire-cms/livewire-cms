

import get from 'get-value'
import set from 'get-value'

window.data_get = get
window.data_set = set

require("flatpickr");//日期时间选择器
window.Quill = require("Quill");//



// import {Sortable, Plugins} from '@shopify/draggable';
import Sortable from '@shopify/draggable/lib/sortable';
import SwapAnimation from '@shopify/draggable/lib/plugins/swap-animation';


window.sortAnimation =  function (that) {

    const containers = that.$refs['sort-animation'];
    if (!containers) {
      return false;
    }
    // const containers = document.querySelectorAll('.sort-animation');
    const sortable = new Sortable(containers, {
      draggable: '.Block--isDraggable',
      mirror: {
        constrainDimensions: true,
      },
      plugins: [SwapAnimation],
      swapAnimation: {
        duration: 200,
        easingFunction: 'ease-in-out',
      },
    });

    return sortable;
  }

import * as echarts from 'echarts';
window.echarts = echarts
//enable Disable


window.init_field = function(data){

    return {
        show:true,
        field:{},
        triggerAction:'',
        triggerCondition:'',
        triggerConditionValue:[],
        trigger_show_or_hide(){
            if(this.field.trigger){
                //triggerField = data_get(this.field, 'trigger.modelNameNotFirst');
                //triggerFieldValue =  data_get(JSON.parse(JSON.stringify(this.form)), triggerField,'');
               //JSON.parse(JSON.stringify(this.form))
                //console.log( 55555,this.field.modelName,this.triggerAction,triggerField,this.triggerCondition,triggerFieldValue)
            }
            var actions = this.triggerAction.split('|');
            for(let index = 0; index < actions.length; index++){
                if(['show','hide'].indexOf(actions[index])>-1){
                    if(actions[index]=='show'){
                        return this.onConditionChanged();
                    }else if(actions[index]=='hide'){
                        return !this.onConditionChanged();
                    }
                    return true
                }
            }
            return true;
        },
        trigger_endable_or_disable(){
            if(this.field.trigger){
                //var triggerField = data_get(this.field, 'trigger.modelName');
               // var triggerFieldValue =  data_get(JSON.parse(JSON.stringify(this.form)), triggerField,'');
                //console.log(JSON.parse(JSON.stringify(this.form)) ,this.field.modelName,this.triggerAction,triggerField,this.triggerCondition,triggerFieldValue)
            }

            var actions = this.triggerAction.split('|');


            for(let index = 0; index < actions.length; index++){
                if(['enable','disable','empty'].indexOf(actions[index])>-1){
                    if(actions[index]=='enable'){
                        return !this.onConditionChanged();
                    }else if(actions[index]=='disable'){
                        return this.onConditionChanged();
                    }
                    return false
                }
            }



            return false;
        },
        onConditionChanged(){
            var triggerField = data_get(this.field, 'trigger.modelNameNotFirst');
            var triggerFieldValue =  data_get(this.form, triggerField);

            if (this.triggerCondition == 'checked') {
                if(Array.isArray(triggerFieldValue)){
                    return triggerFieldValue.length>0;
                }else{
                    if(triggerFieldValue){
                        return true;
                    }else{
                        return false;
                    }
                }
                return false;
            }
            else if (this.triggerCondition == 'unchecked') {
                if(Array.isArray(triggerFieldValue)){
                    return triggerFieldValue.length==0;
                }else{
                    if(triggerFieldValue){
                        return false;
                    }else{
                        return true;
                    }
                }
                return false;
            }
            else if (this.triggerCondition == 'value') {
                if(Array.isArray(triggerFieldValue)){
                    return triggerFieldValue.filter(item=>{
                        return this.triggerConditionValue.indexOf(item)>-1
                    }).length>0
                }else{
                    return this.triggerConditionValue.filter(item=>{
                        return item==triggerFieldValue
                    }).length>0
                }
            }
        },
        init(){
            this.field=JSON.parse(this.$refs['field'].value);
            this.triggerAction = data_get(this.field, 'trigger.action','');

            this.triggerCondition = data_get(this.field, 'trigger.condition','');
            if (this.triggerCondition.indexOf('value') == 0) {
                var match = this.triggerCondition.match(/[^[\]]+(?=])/g)
                this.triggerCondition = 'value'
                this.triggerConditionValue = (match) ? match : ['']
            }

            if(this.extend_init){
                this.extend_init()
            }



        },
        ...data
    }
}




//todo 定制
import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import Document from '@tiptap/extension-document'
import Paragraph from '@tiptap/extension-paragraph'
import Text from '@tiptap/extension-text'

window.setupEditor = function (content) {
  return {
    editor: null,
    content: content,

    init(element) {
      this.editor = new Editor({
        element: element,
        extensions: [
          StarterKit,
          Document,
          Paragraph,
          Text
        ],
        content: this.content,
        onUpdate: ({ editor }) => {
          this.content = editor.getHTML()
        }
      })

      this.$watch('content', (content) => {
        // If the new content matches TipTap's then we just skip.
        if (content === this.editor.getHTML()) return

        /*
          Otherwise, it means that a force external to TipTap
          is modifying the data on this Alpine component,
          which could be Livewire itself.
          In this case, we just need to update TipTap's
          content and we're good to do.
          For more information on the `setContent()` method, see:
            https://www.tiptap.dev/api/commands/set-content
        */
        this.editor.commands.setContent(content, false)
      })
    }
  }
}

import E from "wangeditor";

window.wangeditor = E


window.marked = require("marked");


import CodeMirror from 'codemirror/lib/codemirror.js'
import 'codemirror/mode/yaml/yaml'
import 'codemirror/mode/php/php'
import 'codemirror/mode/markdown/markdown'
window.codemirror = CodeMirror



window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

// try {
//     window.Popper = require('popper.js').default;
//     window.$ = window.jQuery = require('jquery');

//     require('bootstrap');
// } catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
