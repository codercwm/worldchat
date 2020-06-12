import Vue from 'vue';
import '../Alert/index.css';
import './index.css';

const root = window.document.body;

export default function Alert(config) {
  const wrap = document.createElement('div');
  const div = document.createElement('div');
  wrap.style.position = 'absolute';
  wrap.style.width = '100%';
  wrap.style.height = '100%';
  wrap.style.left = '0';
  wrap.style.top = '0';

  root.appendChild(wrap);
  wrap.appendChild(div);

  config = config || {};

  return new Promise(resolve => new Vue({
    el: div,
    data: {
      title: config.title || '请输入',
      content: config.content || '',
      ok: config.ok || '确定',
      cancel: config.cancel || '取消'
    },
    methods: {
      submit() {
        root.removeChild(wrap);
        resolve({"res":"submit","content":this.content});
      },
      close() {
        root.removeChild(wrap);
        resolve({"res":"close","content":this.content});
      }
    },
    template: `
      <div class="wind-alert">
        <div class="wind-alert-bg"></div>
        <div class="wind-alert-dialog animate-scale">
          <div class="wind-alert-title">{{title}}</div>
          <input style="border: 1px solid #ddd;" :placeholder="title" v-model="content">
          <div class="wind-confirm-btns" >
            <div class="wind-confirm-btn" @click="submit">{{ok}}</div>
            <div class="wind-confirm-btn" @click="close">{{cancel}}</div>
          </div>
        </div>
      </div>
    `
  }));
}
