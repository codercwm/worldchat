import Vue from 'vue';
import './index.css';
import '../../../const/index.js';
import {TOAST_COLOR,TOAST_BACKGROUND} from "../../../const/index";

const root = window.document.body;

export default function Toast(config) {
    const wrap = document.createElement('div');
    const div = document.createElement('div');

    root.appendChild(wrap);
    wrap.appendChild(div);

    config = config || {};

    return new Promise(resolve => new Vue({
        el: div,
        data: {
            content: config.content || '',
            timeout: config.timeout || 1500,
            background: config.background || TOAST_BACKGROUND,
            color: config.color || TOAST_COLOR,
            toast: false
        },
        methods: {
            close() {
                root.removeChild(wrap);
                resolve(this);
            }
        },
        mounted() {
            setTimeout(() => {
                this.toast = true;
            }, this.timeout);
            setTimeout(() => {
                this.close();
            }, this.timeout + 1000);
        },
        template: `
      <div class="wind-toast" :class="{'opacity0': toast}">
        <div class="wind-toast-content" :style="{'background-color': background, 'color': color}">
          {{content}}
        </div>
      </div>
    `
    }));
}
