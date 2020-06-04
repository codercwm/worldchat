<template>
    <div class="clear" :class="[isSelf ? 'right' : 'left']" ref="msg">
        <div class="item">
            <div class="nickname">{{nickname}}</div>
            <div class="msg_time">{{getdate}}</div>
            <span class="head-place">
                <img :src="avatar" alt="" class="head">
            </span>
            <div v-if="img">
                <img
                    :src="pic"
                    alt=""
                    v-preview="img"
                    class="img"
                    preview-title-enable="true"
                    preview-nav-enable="true">
            </div>
            <span v-if="msg">
                <span v-html="linkMsg" class="msg"></span>
                <!-- {{msg | link}} -->
            </span>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
import dateFormat from "../../utils/date";
import { inHTMLData, uriInUnQuotedAttr } from "xss-filters-es6";
export default {
  props: ["nickname", "img", "msg", "avatar", "mytime", "is-self", "container", "isNeedScroll", "firstNode"],
  computed: {
    getdate() {
      return dateFormat(new Date(this.mytime), "yyyy-MM-dd HH:mm:ss");
    },
    linkMsg() {
      // é˜²æ­¢xss
      const filterValue = inHTMLData(this.msg);
      return filterValue.replace(
        /(http:\/\/|https:\/\/)((\w|=|\?|\.|\/|&|-)+)/g,
        function($0, $1) {
          const url = $0;
          return `<a style="color: #b374ff" href="${uriInUnQuotedAttr(
            url
          )}" target="_blank">${uriInUnQuotedAttr(url)}</a>`;
        }
      );
    },
    pic() {
      let pic = this.img;
      if (pic.indexOf("data:image") > -1) {
        return pic;
      }
      return `${pic}`;
    }
  },
  mounted() {
      //渲染完列表后滚动到最底部
     this.$refs.msg.scrollIntoView();
  }
};
</script>
<style lang="stylus" rel="stylesheet/stylus" scoped src="./index.styl"></style>

